<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Part;

class HomeController extends Controller
{
    /** Icon per part category (lowercase name => icon component name). */
    public const CATEGORY_ICONS = [
        'ram' => 'memory', 'memory' => 'memory', 'processor' => 'chip', 'cpu' => 'chip',
        'ssd' => 'drive', 'hdd' => 'drive', 'storage' => 'drive', 'charger' => 'plug', 'adapter' => 'plug',
        'monitor' => 'monitor', 'keyboard' => 'keyboard', 'mouse' => 'mouse', 'battery' => 'battery',
    ];

    public function __invoke()
    {
        $laptops = Laptop::available()->with('images')
            ->orderByRaw("status = 'reserved'")->latest()->get();

        $parts = Part::published()->where('quantity', '>', 0)->get();

        return view('home', [
            'laptops' => $laptops->take(8),
            'laptopCount' => $laptops->count(),
            'partCategories' => $parts->countBy('category')->sortKeys()->map(fn ($count, $name) => [
                'name' => $name,
                'count' => $count,
                'icon' => self::CATEGORY_ICONS[strtolower($name)] ?? 'box',
            ])->values(),
        ]);
    }
}
