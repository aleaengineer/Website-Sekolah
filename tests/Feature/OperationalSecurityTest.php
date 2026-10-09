<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\News;
use App\Models\PpdbWave;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OperationalSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Storage::disk('local')->deleteDirectory('backups');

        parent::tearDown();
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    public function test_ppdb_rejects_wrong_captcha(): void
    {
        $wave = PpdbWave::create([
            'name' => 'Gelombang 1',
            'start_date' => now()->subDay()->toDateString(),
            'end_date' => now()->addWeek()->toDateString(),
            'quota' => 100,
            'is_active' => true,
        ]);

        $this->withSession(['captcha_ppdb' => 7])->post(route('ppdb.store'), [
            'student_name' => 'Budi Santoso',
            'birth_place' => 'Pangandaran',
            'birth_date' => '2013-05-10',
            'gender' => 'L',
            'previous_school' => 'SDN 1 Kalijati',
            'parent_name' => 'Ahmad Santoso',
            'parent_phone' => '081234567890',
            'address' => 'Dusun Kalijati',
            'jalur' => 'zonasi',
            'ppdb_wave_id' => $wave->id,
            'captcha' => 9,
        ])->assertSessionHasErrors('captcha');

        $this->assertDatabaseCount('ppdb_registrations', 0);
    }

    public function test_contact_rejects_wrong_captcha(): void
    {
        $this->withSession(['captcha_contact' => 5])->post(route('contact.store'), [
            'name' => 'Siti',
            'contact' => 'siti@example.com',
            'message' => 'Halo sekolah.',
            'captcha' => 6,
        ])->assertSessionHasErrors('captcha');

        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_login_and_logout_are_logged(): void
    {
        $admin = $this->admin();

        $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => ActivityLog::ACTION_LOGIN,
        ]);

        $this->post(route('logout'))->assertRedirect(route('home'));

        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $admin->id,
            'action' => ActivityLog::ACTION_LOGOUT,
        ]);
    }

    public function test_admin_crud_is_logged_but_public_writes_are_not(): void
    {
        $this->actingAs($this->admin())->post(route('admin.news.store'), [
            'title' => 'Berita Tercatat Log',
            'body' => '<p>Isi.</p>',
        ])->assertRedirect();

        $this->assertDatabaseHas('activity_logs', [
            'action' => ActivityLog::ACTION_CREATE,
            'subject_type' => News::class,
        ]);

        News::factory()->create(['title' => 'Berita Publik Tanpa Login']);

        $this->assertDatabaseMissing('activity_logs', [
            'description' => 'News menambahkan Berita Publik Tanpa Login',
        ]);
    }

    public function test_activity_log_index_is_admin_only(): void
    {
        $this->get(route('admin.activity-logs.index'))->assertRedirect(route('login'));

        $operator = User::factory()->create(['role' => User::ROLE_OPERATOR]);
        $this->actingAs($operator)->get(route('admin.activity-logs.index'))->assertForbidden();

        $guru = User::factory()->create(['role' => User::ROLE_GURU]);
        $this->actingAs($guru)->get(route('admin.activity-logs.index'))->assertForbidden();

        $this->actingAs($this->admin())->get(route('admin.activity-logs.index'))->assertOk();
    }

    public function test_backup_index_is_admin_only(): void
    {
        $this->get(route('admin.backups.index'))->assertRedirect(route('login'));

        $operator = User::factory()->create(['role' => User::ROLE_OPERATOR]);
        $this->actingAs($operator)->get(route('admin.backups.index'))->assertForbidden();

        $this->actingAs($this->admin())->get(route('admin.backups.index'))
            ->assertOk()
            ->assertSee('Backup Database');
    }

    public function test_sqlite_file_backup_is_created_and_listed(): void
    {
        $source = tempnam(sys_get_temp_dir(), 'siswa').'.sqlite';
        file_put_contents($source, 'sqlite-dummy');

        config(['database.connections.sqlite.database' => $source]);

        $this->actingAs($this->admin())->post(route('admin.backups.store'))
            ->assertRedirect(route('admin.backups.index'))
            ->assertSessionHas('success');

        $files = Storage::disk('local')->files('backups');
        $this->assertCount(1, $files);
        $this->assertEquals('sqlite-dummy', Storage::disk('local')->get($files[0]));

        $this->assertDatabaseHas('activity_logs', ['action' => ActivityLog::ACTION_BACKUP]);

        @unlink($source);
    }

    public function test_backup_creation_fails_gracefully_without_file_database(): void
    {
        config(['database.connections.sqlite.database' => ':memory:']);

        $this->actingAs($this->admin())->post(route('admin.backups.store'))
            ->assertRedirect(route('admin.backups.index'))
            ->assertSessionHasErrors('backup');
    }

    public function test_restore_rejects_invalid_upload(): void
    {
        $this->actingAs($this->admin())->post(route('admin.backups.restore'), [])
            ->assertSessionHasErrors('backup');
    }

    public function test_backup_download_rejects_unknown_file(): void
    {
        $this->actingAs($this->admin())->get(route('admin.backups.download', 'tidak-ada.sql'))
            ->assertNotFound();
    }

    public function test_backup_download_rejects_path_traversal(): void
    {
        $this->actingAs($this->admin())->get(route('admin.backups.download', '..%2F.env'))
            ->assertNotFound();
    }
}
