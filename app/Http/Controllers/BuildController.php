<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Part;

class BuildController extends Controller
{
    /**
     * "Build your setup": one slot for a laptop, then one per part category.
     * Prices are read from the database here; the browser only adds them up for
     * an estimate that the customer sends on WhatsApp.
     */
    public function __invoke()
    {
        $laptops = Laptop::where('is_published', 1)->where('status', 'in_stock')
            ->with('images')->latest()->get()
            ->map(fn (Laptop $l) => [
                'key' => 'l'.$l->id,
                'name' => $l->name,
                'meta' => implode(' · ', $l->spec_line),
                'price' => (float) $l->selling_price,
                'stock' => 1,
                'photo' => $l->photo,
                'url' => $l->url,
            ]);

        $order = config('mazz.build_order');

        $parts = Part::published()->with('images')->orderBy('name')->get()
            ->groupBy('category')
            ->sortBy(fn ($items, $category) => [
                ($i = array_search($category, $order, true)) === false ? PHP_INT_MAX : $i,
                $category,
            ]);

        $slots = collect([[
            'id' => 'laptop',
            'label' => 'Laptop',
            'icon' => 'laptop',
            'hint' => 'Start here, or skip it and just pick parts.',
            'items' => $laptops->values(),
        ]])->concat($parts->map(fn ($items, $category) => [
            'id' => 'c-'.str($category)->slug(),
            'label' => $category,
            'icon' => HomeController::CATEGORY_ICONS[strtolower($category)] ?? 'box',
            'hint' => null,
            'items' => $items->map(fn (Part $p) => [
                'key' => 'p'.$p->id,
                'name' => $p->name,
                'meta' => collect($p->spec_list)->take(3)->implode(' · '),
                'price' => (float) $p->selling_price,
                'stock' => $p->quantity,
                'photo' => $p->photo,
                'url' => $p->url,
            ])->values(),
        ])->values())
            // lowest shelf price per row, shown as "from Rs. ..."
            ->map(fn ($slot) => $slot + ['from' => collect($slot['items'])->where('stock', '>', 0)->pluck('price')->filter()->min()]);

        return view('build', ['slots' => $slots]);
    }
}
