<?php

namespace App\Models\Concerns;

use App\Support\Media;
use Illuminate\Database\Eloquent\Builder;

/**
 * Shared by Laptop and Part. The website reads the admin's tables and must
 * never load cost or internal columns, so every query is limited to PUBLIC_COLUMNS.
 */
trait Showcase
{
    protected static function bootShowcase(): void
    {
        static::addGlobalScope('public_columns', function (Builder $query) {
            $table = $query->getModel()->getTable();
            $query->select(array_map(fn ($c) => "$table.$c", static::PUBLIC_COLUMNS));
        });
    }

    public function save(array $options = []): bool
    {
        throw new \LogicException('The website is read-only.');
    }

    public function delete(): ?bool
    {
        throw new \LogicException('The website is read-only.');
    }

    public function getHasPriceAttribute(): bool
    {
        return (float) $this->selling_price > 0;
    }

    public function getPriceLabelAttribute(): string
    {
        return $this->has_price ? Media::price($this->selling_price) : 'Ask for price';
    }

    public function getPhotoAttribute(): ?string
    {
        return Media::url(optional($this->images->first())->image_path);
    }

    public function getPhotosAttribute(): array
    {
        return $this->images->map(fn ($i) => Media::url($i->image_path))->unique()->values()->all();
    }

    /** Free key/value specs from the admin, without empty values. */
    public function getSpecListAttribute(): array
    {
        return collect($this->extra_specs ?: [])
            ->filter(fn ($v, $k) => is_string($k) && trim((string) $v) !== '')
            ->all();
    }
}
