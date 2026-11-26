<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Product;
use App\Models\Review;
use App\Models\Setting;
use App\Models\Video;
use App\Support\Groups;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function home()
    {
        $tabs = collect(Groups::ALL)->map(function (string $name, string $key) {
            $products = Product::query()
                ->with(['category', 'groups'])
                ->where('is_active', true)
                ->whereHas('groups', fn ($query) => $query->where('group', $key))
                ->get()
                ->sortBy(fn (Product $product) => $product->groups->firstWhere('group', $key)?->sort ?? 0)
                ->values()
                ->map(fn (Product $product) => $product->toCatalog());

            return [
                'key' => $key,
                'name' => $name,
                'products' => $products,
            ];
        })->values();

        return response()->json([
            'settings' => Setting::query()->pluck('value', 'key'),
            'banners' => Banner::query()->where('is_active', true)->orderBy('sort')->get()->map->toCatalog(),
            'tabs' => $tabs,
            'reviews' => Review::query()->where('is_active', true)->orderBy('sort')->orderBy('id')->get()->map->toCatalog(),
            'videos' => Video::query()->where('is_active', true)->orderBy('sort')->orderBy('id')->get()->map->toCatalog(),
        ]);
    }

    public function categories()
    {
        return response()->json(
            Category::query()->orderBy('sort')->orderBy('name')->get(['name', 'slug'])
        );
    }

    public function products(Request $request)
    {
        $products = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->when($request->query('q'), function ($query, $term) {
                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%")
                        ->orWhere('deity', 'like', "%{$term}%")
                        ->orWhere('colour', 'like', "%{$term}%")
                        ->orWhere('tier', 'like', "%{$term}%");
                });
            })
            ->when($request->query('colour'), fn ($query, $colour) => $query->where('colour', $colour))
            ->when($request->query('category'), fn ($query, $slug) => $query->whereHas('category', fn ($category) => $category->where('slug', $slug)))
            ->orderBy('sort')
            ->orderBy('name')
            ->get()
            ->map->toCatalog();

        return response()->json($products);
    }

    public function product(string $slug)
    {
        $product = Product::query()->with('category')->where('slug', $slug)->where('is_active', true)->firstOrFail();
        $payload = $product->toCatalog();
        $payload['suggestions'] = $this->suggestions($product);

        return response()->json($payload);
    }

    private function suggestions(Product $product)
    {
        $related = Product::query()
            ->with('category')
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->when($product->category_id, fn ($query) => $query->where('category_id', $product->category_id))
            ->orderBy('sort')
            ->orderBy('name')
            ->limit(4)
            ->get();

        if ($related->count() < 4) {
            $more = Product::query()
                ->with('category')
                ->where('is_active', true)
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $related->pluck('id'))
                ->orderBy('sort')
                ->orderBy('name')
                ->limit(4 - $related->count())
                ->get();
            $related = $related->concat($more);
        }

        return $related->map->toCatalog()->values();
    }

    public function page(string $slug)
    {
        $page = Page::query()->where('slug', $slug)->firstOrFail();

        return response()->json($page->toCatalog());
    }

    public function faqs()
    {
        $faqs = Faq::query()->where('is_active', true)->orderBy('sort')->orderBy('id')->get(['question', 'answer']);

        return response()->json($faqs);
    }

    public function search(Request $request)
    {
        $term = trim((string) $request->query('q', ''));
        $products = Product::query()
            ->where('is_active', true)
            ->when($term !== '', function ($query) use ($term) {
                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%")
                        ->orWhere('deity', 'like', "%{$term}%")
                        ->orWhere('colour', 'like', "%{$term}%");
                });
            })
            ->orderBy('sort')
            ->limit(8)
            ->get()
            ->map(fn (Product $product) => [
                'title' => $product->name,
                'href' => '/product/'.$product->slug,
                'kind' => $product->price ? '₹'.number_format($product->price) : 'Poshak',
            ]);

        $pages = Page::query()
            ->when($term !== '', fn ($query) => $query->where(function ($inner) use ($term) {
                $inner->where('title', 'like', "%{$term}%")->orWhere('summary', 'like', "%{$term}%")->orWhere('body', 'like', "%{$term}%");
            }))
            ->limit(4)
            ->get()
            ->map(fn (Page $page) => [
                'title' => $page->title,
                'href' => '/'.$page->slug,
                'kind' => 'Page',
            ]);

        return response()->json($products->concat($pages)->values());
    }

    public function settings()
    {
        return response()->json(Setting::query()->pluck('value', 'key'));
    }

    public function enquire(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:160'],
            'message' => ['nullable', 'string', 'max:2000'],
            'product_slug' => ['nullable', 'string', 'max:160'],
            'personalise' => ['nullable', 'boolean'],
            'embroidery_name' => ['nullable', 'string', 'max:80'],
            'script' => ['nullable', 'string', 'max:40'],
            'zari' => ['nullable', 'string', 'max:40'],
        ]);

        $product = null;
        if (! empty($data['product_slug'])) {
            $product = Product::query()->where('slug', $data['product_slug'])->first();
        }

        $lines = array_filter([
            $data['message'] ?? null,
            array_key_exists('personalise', $data)
                ? 'Personalise: '.($data['personalise'] ? 'yes' : 'no')
                : null,
            ! empty($data['embroidery_name']) ? 'Name: '.$data['embroidery_name'] : null,
            ! empty($data['script']) ? 'Script: '.$data['script'] : null,
            ! empty($data['zari']) ? 'Embroidery: '.$data['zari'] : null,
        ]);

        Enquiry::query()->create([
            'product_id' => $product?->id,
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'message' => implode("\n", $lines),
            'status' => 'new',
        ]);

        return response()->json(['ok' => true]);
    }
}
