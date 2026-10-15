<?php

namespace App\Models;

use App\Models\Concerns\GeneratesSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use GeneratesSlug;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    // ---------- Relasi ----------

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ---------- Scope ----------

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeSearch(Builder $query, ?string $term): void
    {
        $query->when($term, fn (Builder $q) => $q->where('name', 'like', "%{$term}%"));
    }

    public function scopeInCategory(Builder $query, ?string $categorySlug): void
    {
        $query->when($categorySlug, fn (Builder $q) => $q->whereHas(
            'category',
            fn (Builder $category) => $category->where('slug', $categorySlug)
        ));
    }

    // ---------- Accessor & helper ----------

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => $this->image
            ? Storage::disk('public')->url($this->image)
            : 'https://placehold.co/600x600/44403c/fafaf9?text='.urlencode($this->name));
    }

    public function isAvailable(): bool
    {
        return $this->is_active && $this->stock > 0;
    }
}
