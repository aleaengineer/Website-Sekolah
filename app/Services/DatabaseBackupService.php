<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class DatabaseBackupService
{
    public const DIRECTORY = 'backups';

    /**
     * @return array<int, array{name: string, size: int, modified: int}>
     */
    public function files(): array
    {
        $disk = Storage::disk('local');

        if (! $disk->exists(self::DIRECTORY)) {
            return [];
        }

        $files = [];

        foreach ($disk->files(self::DIRECTORY) as $path) {
            $name = basename($path);

            if (! $this->isAllowedName($name)) {
                continue;
            }

            $files[] = [
                'name' => $name,
                'size' => $disk->size($path),
                'modified' => $disk->lastModified($path),
            ];
        }

        usort($files, fn (array $a, array $b) => $b['modified'] <=> $a['modified']);

        return $files;
    }

    public function path(string $name): string
    {
        if (! $this->isAllowedName($name)) {
            abort(404);
        }

        $relative = self::DIRECTORY."/{$name}";

        if (! Storage::disk('local')->exists($relative)) {
            abort(404);
        }

        return Storage::disk('local')->path($relative);
    }

    public function create(): string
    {
        $driver = DB::getDriverName();

        return $driver === 'mysql' ? $this->dumpMysql() : $this->copySqlite();
    }

    public function delete(string $name): void
    {
        Storage::disk('local')->delete(self::DIRECTORY.'/'.basename($name));
    }

    /**
     * Restore the database from an uploaded dump file.
     */
    public function restore(string $uploadedPath): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            $this->importMysql($uploadedPath);

            return;
        }

        $target = (string) config('database.connections.sqlite.database');

        if ($target === '' || $target === ':memory:') {
            throw new RuntimeException('Restore SQLite hanya didukung untuk database file.');
        }

        if (! @copy($uploadedPath, $target)) {
            throw new RuntimeException('Gagal menimpa file database SQLite.');
        }
    }

    private function dumpMysql(): string
    {
        $binary = $this->mysqlBinary('mysqldump');
        $config = config('database.connections.mysql');
        $name = 'backup-'.now()->format('Ymd-His').'.sql';
        $target = Storage::disk('local')->path(self::DIRECTORY."/{$name}");

        @mkdir(dirname($target), 0755, true);

        $command = [
            $binary,
            '--host='.$config['host'],
            '--port='.(string) $config['port'],
            '--user='.$config['username'],
            '--routines',
            '--events',
            '--single-transaction',
            '--skip-column-statistics',
            $config['database'],
        ];

        $env = [];

        if (($config['password'] ?? '') !== '') {
            $env['MYSQL_PWD'] = $config['password'];
        }

        $result = Process::env($env)->run($command);

        if (! $result->successful()) {
            throw new RuntimeException('mysqldump gagal: '.trim($result->errorOutput() ?: $result->output()));
        }

        file_put_contents($target, $result->output());

        return $name;
    }

    private function importMysql(string $uploadedPath): void
    {
        $binary = $this->mysqlBinary('mysql');
        $config = config('database.connections.mysql');

        $command = [
            $binary,
            '--host='.$config['host'],
            '--port='.(string) $config['port'],
            '--user='.$config['username'],
            $config['database'],
        ];

        $env = [];

        if (($config['password'] ?? '') !== '') {
            $env['MYSQL_PWD'] = $config['password'];
        }

        $sql = (string) file_get_contents($uploadedPath);

        $result = Process::env($env)->input($sql)->run($command);

        if (! $result->successful()) {
            throw new RuntimeException('Restore MySQL gagal: '.trim($result->errorOutput() ?: $result->output()));
        }
    }

    private function copySqlite(): string
    {
        $source = (string) config('database.connections.sqlite.database');

        if ($source === '' || $source === ':memory:' || ! is_file($source)) {
            throw new RuntimeException('Backup SQLite hanya didukung untuk database file.');
        }

        $name = 'backup-'.now()->format('Ymd-His').'.sqlite';
        $target = self::DIRECTORY."/{$name}";

        Storage::disk('local')->put($target, file_get_contents($source));

        return $name;
    }

    private function mysqlBinary(string $binary): string
    {
        $configured = (string) config('database.mysql_dump_path', '');

        if ($configured !== '') {
            return $configured.DIRECTORY_SEPARATOR.$binary;
        }

        foreach (['C:\\xampp\\mysql\\bin\\'.$binary.'.exe', $binary] as $candidate) {
            if (str_ends_with($candidate, '.exe') && ! is_file($candidate)) {
                continue;
            }

            return $candidate;
        }

        return $binary;
    }

    private function isAllowedName(string $name): bool
    {
        if ($name !== basename($name)) {
            return false;
        }

        if (! preg_match('/^[A-Za-z0-9][A-Za-z0-9_.-]*$/', $name)) {
            return false;
        }

        return (bool) preg_match('/\.(sql|sqlite|db|gz)$/i', $name);
    }
}
