<?php

namespace App\Models;

use App\Models\Concerns\Showcase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/** A laptop unit from the admin's `products` table (read-only). */
class Laptop extends Model
{
    use Showcase;

    public const PUBLIC_COLUMNS = [
        'id', 'slug', 'name', 'brand', 'model', 'cpu', 'ram', 'ssd', 'generation',
        'screen_size', 'extra_specs', 'selling_price', 'status', 'is_published', 'created_at', 'updated_at',
    ];

    protected $table = 'products';

    protected $casts = [
        'extra_specs' => 'array',
        'selling_price' => 'decimal:2',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class, 'product_id')
            ->orderByDesc('is_primary')->orderBy('id');
    }

    /** Laptops a customer can buy or ask about: published, in stock or reserved. */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_published', 1)->whereIn('status', ['in_stock', 'reserved']);
    }


    public function getIsReservedAttribute(): bool
    {
        return $this->status === 'reserved';
    }

    public function getIsSoldAttribute(): bool
    {
        return $this->status === 'sold';
    }

    /** Short spec line used on cards: "i5-8350U · 8GB · 256GB · 14 inch". */
    public function getSpecLineAttribute(): array
    {
        return array_values(array_filter([$this->cpu, $this->ram, $this->ssd ? $this->ssd.' SSD' : null, $this->screen_size]));
    }

    /** Main specs in display order, label => value. */
    public function getMainSpecsAttribute(): array
    {
        return array_filter([
            'Processor' => $this->cpu,
            'Generation' => $this->generation,
            'Memory' => $this->ram,
            'Storage' => $this->ssd ? $this->ssd.' SSD' : null,
            'Screen' => $this->screen_size,
            'Brand' => $this->brand,
            'Model' => $this->model,
        ]);
    }

    public function getUrlAttribute(): string
    {
        return route('laptops.show', $this->slug);
    }
}
