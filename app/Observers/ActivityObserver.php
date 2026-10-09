<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityObserver
{
    /**
     * Log admin CRUD. Public (guest) writes are skipped.
     */
    public function created(Model $model): void
    {
        $this->log(ActivityLog::ACTION_CREATE, $model, $this->describe($model, 'menambahkan'));
    }

    public function updated(Model $model): void
    {
        if ($model instanceof ActivityLog) {
            return;
        }

        $this->log(ActivityLog::ACTION_UPDATE, $model, $this->describe($model, 'mengubah'));
    }

    public function deleted(Model $model): void
    {
        $this->log(ActivityLog::ACTION_DELETE, $model, $this->describe($model, 'menghapus'));
    }

    private function log(string $action, Model $model, ?string $description): void
    {
        if (! auth()->check()) {
            return;
        }

        ActivityLog::record($action, $description, $model);
    }

    private function describe(Model $model, string $verb): string
    {
        $label = method_exists($model, 'getLogLabel') ? $model->getLogLabel() : "#{$model->getKey()}";

        return class_basename($model)." {$verb} {$label}";
    }
}
