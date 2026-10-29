<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Support\ShopFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PartController extends Controller
{
    public function index(Request $request)
    {
        $all = Part::published()->get();
        $category = $request->string('category')->toString();

        // Brand and price options follow the chosen category.
        $inCategory = $category !== '' ? $all->where('category', $category) : $all;
        $groups = collect(['brand' => 'Brand'])->map(fn ($label, $key) => [
            'label' => $label,
            'options' => ShopFilter::options($inCategory, $key),
            'selected' => ShopFilter::selected($request, $key),
        ]);
        $price = ShopFilter::price($request, $inCategory, 500);

        $query = Part::published()->with('images');
        if ($category !== '') {
            $query->where('category', $category);
        }
        if ($groups['brand']['selected']) {
            $query->whereIn('brand', $groups['brand']['selected']);
        }
        if ($request->boolean('in_stock')) {
            $query->where('quantity', '>', 0);
        }
        ShopFilter::applyPrice($query, $price);
        ShopFilter::applySearch($query, $request, ['name', 'brand', 'model']);

        match ($request->input('sort')) {
            'price_asc' => $query->orderByRaw('selling_price = 0')->orderBy('selling_price'),
            'price_desc' => $query->orderByDesc('selling_price'),
            default => $query->orderByRaw('quantity > 0 desc')->orderBy('category')->orderBy('name'),
        };

        return view('parts.index', [
            'parts' => $query->paginate(15)->withQueryString(),
            'categories' => ShopFilter::options($all, 'category'),
            'category' => $category,
            'groups' => $groups,
            'price' => $price,
            'total' => $all->count(),
        ]);
    }

    public function show(int $part, ?string $slug = null)
    {
        $part = Part::published()->with('images')->findOrFail($part);

        if ($slug !== Str::slug($part->name)) {
            return redirect($part->url, 301);
        }

        $related = Part::published()->with('images')
            ->where('category', $part->category)->whereKeyNot($part->id)
            ->orderByRaw('quantity > 0 desc')->take(4)->get();

        return view('parts.show', compact('part', 'related'));
    }
}
