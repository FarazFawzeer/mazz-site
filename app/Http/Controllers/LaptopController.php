<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Support\ShopFilter;
use Illuminate\Http\Request;

class LaptopController extends Controller
{
    /** Checkbox groups in the sidebar (brand is the link list above them). */
    private const GROUPS = ['ram' => 'Memory (RAM)', 'ssd' => 'Storage', 'generation' => 'Generation', 'screen_size' => 'Screen size'];

    public function index(Request $request)
    {
        $all = Laptop::available()->get();
        $brand = $request->string('brand')->toString();

        // Filter options come from what is actually in stock.
        $groups = collect(self::GROUPS)->map(fn ($label, $key) => [
            'label' => $label,
            'options' => ShopFilter::options($all, $key),
            'selected' => ShopFilter::selected($request, $key),
        ]);
        $price = ShopFilter::price($request, $all, 5000);

        $query = Laptop::available()->with('images');
        if ($brand !== '') {
            $query->where('brand', $brand);
        }
        foreach ($groups as $key => $group) {
            if ($group['selected']) {
                $query->whereIn($key, $group['selected']);
            }
        }
        ShopFilter::applyPrice($query, $price);
        ShopFilter::applySearch($query, $request, ['name', 'cpu', 'model']);

        match ($request->input('sort')) {
            // Unpriced laptops ("Ask for price") go last in price sorts.
            'price_asc' => $query->orderByRaw('selling_price = 0')->orderBy('selling_price'),
            'price_desc' => $query->orderByDesc('selling_price'),
            default => $query->orderByRaw("status = 'reserved'")->latest(),
        };

        return view('laptops.index', [
            'laptops' => $query->paginate(12)->withQueryString(),
            'brands' => ShopFilter::options($all, 'brand'),
            'brand' => $brand,
            'groups' => $groups,
            'price' => $price,
            'total' => $all->count(),
        ]);
    }

    public function show(string $slug)
    {
        $laptop = Laptop::with('images')->where('slug', $slug)->where('is_published', 1)->firstOrFail();

        $similar = Laptop::available()->with('images')
            ->whereKeyNot($laptop->id)
            ->orderByRaw('brand = ? desc', [$laptop->brand])
            ->latest()->take(4)->get();

        return view('laptops.show', compact('laptop', 'similar'));
    }
}
