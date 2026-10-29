<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/** Shared helpers for the sidebar filters on the laptops and parts listings. */
class ShopFilter
{
    /** Selected values for a checkbox group (accepts ?ram=8GB or ?ram[]=8GB&ram[]=16GB). */
    public static function selected(Request $request, string $key): array
    {
        return array_values(array_filter(array_map('strval', (array) $request->input($key, [])), 'strlen'));
    }

    /** Options for a list or checkbox group: value => count, from the items actually listed. */
    public static function options(Collection $items, string $field): Collection
    {
        return $items->pluck($field)->filter()->countBy()->sortKeys(SORT_NATURAL);
    }

    /**
     * Price slider bounds from priced items (unpriced ones are "Ask for price"),
     * plus the range the visitor picked. Null when nothing is priced.
     */
    public static function price(Request $request, Collection $items, int $step): ?array
    {
        $prices = $items->pluck('selling_price')->map(fn ($p) => (float) $p)->filter(fn ($p) => $p > 0);
        if ($prices->isEmpty()) {
            return null;
        }

        $min = (int) (floor($prices->min() / $step) * $step);
        $max = (int) (ceil($prices->max() / $step) * $step);
        if ($min === $max) {
            $max += $step;
        }

        $from = max($min, min($max, (int) $request->input('price_min', $min)));
        $to = max($from, min($max, (int) $request->input('price_max', $max)));

        return compact('min', 'max', 'step', 'from', 'to') + ['active' => $from > $min || $to < $max];
    }

    /** Applies a picked price range; "Ask for price" items drop out once a range is set. */
    public static function applyPrice(Builder $query, ?array $price): void
    {
        if ($price && $price['active']) {
            $query->where('selling_price', '>', 0)->whereBetween('selling_price', [$price['from'], $price['to']]);
        }
    }

    public static function applySearch(Builder $query, Request $request, array $columns): void
    {
        if ($request->filled('q')) {
            $q = '%'.str_replace(['%', '_'], ['\%', '\_'], $request->string('q')).'%';
            $query->where(function ($w) use ($columns, $q) {
                foreach ($columns as $column) {
                    $w->orWhere($column, 'like', $q);
                }
            });
        }
    }
}
