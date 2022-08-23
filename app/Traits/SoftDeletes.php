<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\SoftDeletes as EloquentSoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Schema;

trait SoftDeletes
{
    use EloquentSoftDeletes {
        bootSoftDeletes as private parentBootSoftDeletes;
        runSoftDelete as private parentRunSoftDelete;
    }

    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootSoftDeletes()
    {
        $table = resolve(self::class)->getTable();
        
        if (Schema::hasColumn($table, 'deleted_at')) {
            static::addGlobalScope(new SoftDeletingScope);
        }
    }

    /**
     * Perform the actual delete query on this model instance.
     *
     * @return void
     */
    protected function runSoftDelete()
    {
        $table = resolve(self::class)->getTable();
        
        if (Schema::hasColumn($table, 'deleted_at')) {
            $query = $this->setKeysForSaveQuery($this->newModelQuery());

            $time = $this->freshTimestamp();

            $columns = [$this->getDeletedAtColumn() => $this->fromDateTime($time)];

            $this->{$this->getDeletedAtColumn()} = $time;

            if ($this->timestamps && ! is_null($this->getUpdatedAtColumn())) {
                $this->{$this->getUpdatedAtColumn()} = $time;

                $columns[$this->getUpdatedAtColumn()] = $this->fromDateTime($time);
            }

            $query->update($columns);

            $this->syncOriginalAttributes(array_keys($columns));

            $this->fireModelEvent('trashed', false);
        }
    }
}
