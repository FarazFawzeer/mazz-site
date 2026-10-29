<?php

namespace App\Models;

use App\Models\Concerns\Showcase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/** A part from the admin's `parts` table (read-only). */
class Part extends Model
{
    use Showcase;

    public const PUBLIC_COLUMNS = [
        'id', 'sku', 'category', 'name', 'brand', 'model', 'extra_specs',
        'selling_price', 'quantity', 'is_published', 'created_at', 'updated_at',
    ];

    protected $casts = [
        'extra_specs' => 'array',
        'selling_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(PartImage::class)->orderByDesc('is_primary')->orderBy('id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', 1);
    }

    public function getInStockAttribute(): bool
    {
        return $this->quantity > 0;
    }

    public function getUrlAttribute(): string
    {
        return route('parts.show', ['part' => $this->id, 'slug' => Str::slug($this->name)]);
    }
}
