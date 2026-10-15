<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Mengisi kolom `slug` otomatis dari `name` setiap kali model disimpan.
 * Kalau slug sudah dipakai, ditambah akhiran -2, -3, dst.
 */
trait GeneratesSlug
{
    protected static function bootGeneratesSlug(): void
    {
        static::saving(function ($model) {
            if (empty($model->slug) || $model->isDirty('name')) {
                $model->slug = static::makeUniqueSlug($model->name, $model->getKey());
            }
        });
    }

    protected static function makeUniqueSlug(string $name, mixed $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'item';
        $slug = $base;
        $counter = 2;

        while (
            static::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}
