<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public const ACTION_CREATE = 'tambah';

    public const ACTION_UPDATE = 'ubah';

    public const ACTION_DELETE = 'hapus';

    public const ACTION_LOGIN = 'masuk';

    public const ACTION_LOGOUT = 'keluar';

    public const ACTION_EXPORT = 'ekspor';

    public const ACTION_IMPORT = 'impor';

    public const ACTION_VERIFY = 'verifikasi';

    public const ACTION_BACKUP = 'backup';

    public const ACTION_RESTORE = 'restore';

    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'ip_address',
        'user_agent',
    ];

    #[Scope]
    protected function recent(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subjectLabel(): string
    {
        if (! $this->subject_type) {
            return '—';
        }

        $name = class_basename($this->subject_type);

        return $this->subject_id ? "{$name} #{$this->subject_id}" : $name;
    }

    public static function record(string $action, ?string $description = null, ?Model $subject = null, ?User $user = null): void
    {
        $user ??= auth()->user();

        static::create([
            'user_id' => $user?->id,
            'action' => $action,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => substr((string) request()->userAgent(), 0, 255),
        ]);
    }
}
