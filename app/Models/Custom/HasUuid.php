<?php


namespace App\Models\Custom;


use Illuminate\Support\Str;

trait HasUuid
{
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (empty( $model->{$model->getKeyName()} ))
                $model->id = Str::uuid();
        });
    }
}
