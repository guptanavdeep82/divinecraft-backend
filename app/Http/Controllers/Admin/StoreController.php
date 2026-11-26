<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Enquiry;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\Setting;
use App\Models\User;
use App\Services\Razorpay;
use App\Services\Shiprocket;
use App\Support\Groups;
use App\Support\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'products' => Product::query()->count(),
            'banners' => Banner::query()->where('is_active', true)->count(),
            'enquiries' => Enquiry::query()->where('status', 'new')->count(),
            'revenue' => Order::query()->where('status', 'paid')->sum('amount'),
            'paidOrders' => Order::query()->where('status', 'paid')->count(),
            'pendingOrders' => Order::query()->where('status', 'pending')->count(),
            'customers' => User::query()->where('role', 'customer')->count(),
            'blogs' => Blog::query()->whereNotNull('published_at')->count(),
        ]);
    }

    public function products()
    {
        return view('admin.products.index', [
            'products' => Product::query()->with('category')->orderBy('sort')->orderBy('name')->get(),
        ]);
    }

    public function createProduct()
    {
        return view('admin.products.form', $this->productForm(new Product(['badge_color' => '#7A1E2C', 'is_active' => true])));
    }

    public function storeProduct(Request $request)
    {
        $product = new Product;
        $this->saveProduct($request, $product);

        return redirect()->route('admin.products')->with('status', 'Poshak saved.');
    }

    public function editProduct(Product $product)
    {
        return view('admin.products.form', $this->productForm($product));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $this->saveProduct($request, $product);

        return redirect()->route('admin.products')->with('status', 'Poshak updated.');
    }

    public function destroyProduct(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products')->with('status', 'Poshak removed.');
    }

    public function banners()
    {
        return view('admin.banners.index', [
            'banners' => Banner::query()->orderBy('sort')->get(),
        ]);
    }

    public function createBanner()
    {
        return view('admin.banners.form', ['banner' => new Banner(['background' => '#5A1220', 'cta_href' => '/collections', 'is_active' => true])]);
    }

    public function storeBanner(Request $request)
    {
        $this->saveBanner($request, new Banner);

        return redirect()->route('admin.banners')->with('status', 'Banner saved.');
    }

    public function editBanner(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function updateBanner(Request $request, Banner $banner)
    {
        $this->saveBanner($request, $banner);

        return redirect()->route('admin.banners')->with('status', 'Banner updated.');
    }

    public function destroyBanner(Banner $banner)
    {
        $banner->delete();

        return redirect()->route('admin.banners')->with('status', 'Banner removed.');
    }

    public function categories()
    {
        return view('admin.categories.index', [
            'categories' => Category::query()->withCount('products')->orderBy('sort')->get(),
        ]);
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'sort' => ['nullable', 'integer', 'min:0'],
        ]);
        Category::query()->create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
            'sort' => $data['sort'] ?? 0,
        ]);

        return redirect()->route('admin.categories')->with('status', 'Category saved.');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories')->with('status', 'Category removed.');
    }

    public function enquiries()
    {
        return view('admin.enquiries.index', [
            'enquiries' => Enquiry::query()->with('product')->latest()->get(),
        ]);
    }

    public function updateEnquiry(Request $request, Enquiry $enquiry)
    {
        $data = $request->validate(['status' => ['required', Rule::in(['new', 'read', 'closed'])]]);
        $enquiry->update($data);

        return redirect()->route('admin.enquiries')->with('status', 'Enquiry updated.');
    }

    public function settings()
    {
        return view('admin.settings', [
            'settings' => Setting::query()->pluck('value', 'key'),
            'razorpayReady' => app(Razorpay::class)->ready(),
            'secretSaved' => filled(Setting::get('razorpay_key_secret')) || filled(config('services.razorpay.secret')),
            'shiprocketReady' => app(Shiprocket::class)->ready(),
            'shiprocketPasswordSaved' => filled(Setting::get('shiprocket_password')) || filled(config('services.shiprocket.password')),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'whatsapp' => ['required', 'string', 'max:20'],
            'whatsapp_label' => ['required', 'string', 'max:40'],
            'announce_1' => ['nullable', 'string', 'max:160'],
            'announce_2' => ['nullable', 'string', 'max:160'],
            'announce_3' => ['nullable', 'string', 'max:160'],
            'announce_4' => ['nullable', 'string', 'max:160'],
            'razorpay_key_id' => ['nullable', 'string', 'max:80'],
            'razorpay_key_secret' => ['nullable', 'string', 'max:80'],
            'shiprocket_email' => ['nullable', 'email', 'max:160'],
            'shiprocket_password' => ['nullable', 'string', 'max:160'],
            'shiprocket_pickup' => ['nullable', 'string', 'max:80'],
            'shiprocket_pickup_pincode' => ['nullable', 'digits:6'],
        ]);

        $secret = $data['razorpay_key_secret'] ?? null;
        $shiprocketPassword = $data['shiprocket_password'] ?? null;
        unset($data['razorpay_key_secret'], $data['shiprocket_password']);
        foreach ($data as $key => $value) {
            Setting::put($key, $value);
        }
        if (filled($secret)) {
            Setting::put('razorpay_key_secret', $secret);
        }
        if (filled($shiprocketPassword)) {
            Setting::put('shiprocket_password', $shiprocketPassword);
        }

        return redirect()->route('admin.settings')->with('status', 'Settings saved. The shop will pick them up on refresh.');
    }

    private function productForm(Product $product): array
    {
        return [
            'product' => $product,
            'categories' => Category::query()->orderBy('sort')->get(),
            'groups' => Groups::ALL,
            'selected' => $product->exists ? $product->groups->pluck('group')->all() : [],
        ];
    }

    private function saveProduct(Request $request, Product $product): void
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:140'],
            'slug' => ['nullable', 'string', 'max:160', Rule::unique('products', 'slug')->ignore($product->id)],
            'price' => ['required', 'integer', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'tier' => ['nullable', 'string', 'max:160'],
            'badge' => ['nullable', 'string', 'max:40'],
            'badge_color' => ['nullable', 'string', 'max:20'],
            'eta' => ['nullable', 'string', 'max:40'],
            'image' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:8192'],
            'description' => ['nullable', 'string', 'max:4000'],
            'deity' => ['nullable', 'string', 'max:80'],
            'colour' => ['nullable', 'string', 'max:40'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'groups' => ['nullable', 'array'],
            'groups.*' => [Rule::in(array_keys(Groups::ALL))],
        ]);

        $image = Uploads::image($request->file('photo'), $data['image'] ?? $product->image);
        if (! $image) {
            abort(422, 'Add a photo or an image path.');
        }

        $product->fill([
            'name' => $data['name'],
            'slug' => $data['slug'] ?: Str::slug($data['name']),
            'price' => $data['price'],
            'category_id' => $data['category_id'] ?? null,
            'tier' => $data['tier'] ?? null,
            'badge' => $data['badge'] ?? null,
            'badge_color' => $data['badge_color'] ?: '#7A1E2C',
            'eta' => $data['eta'] ?? null,
            'image' => $image,
            'description' => $data['description'] ?? null,
            'deity' => $data['deity'] ?? null,
            'colour' => $data['colour'] ?? null,
            'sort' => $data['sort'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);
        $product->save();

        $product->groups()->delete();
        foreach (array_values($data['groups'] ?? []) as $index => $group) {
            ProductGroup::query()->create([
                'product_id' => $product->id,
                'group' => $group,
                'sort' => $index,
            ]);
        }
    }

    private function saveBanner(Request $request, Banner $banner): void
    {
        $data = $request->validate([
            'eyebrow' => ['nullable', 'string', 'max:80'],
            'title' => ['required', 'string', 'max:160'],
            'subtitle' => ['nullable', 'string', 'max:500'],
            'cta' => ['nullable', 'string', 'max:80'],
            'cta_href' => ['nullable', 'string', 'max:160'],
            'cta2' => ['nullable', 'string', 'max:80'],
            'note' => ['nullable', 'string', 'max:160'],
            'image' => ['nullable', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'max:8192'],
            'image_alt' => ['nullable', 'string', 'max:160'],
            'image2' => ['nullable', 'string', 'max:255'],
            'chip_top' => ['nullable', 'string', 'max:60'],
            'chip' => ['nullable', 'string', 'max:60'],
            'background' => ['nullable', 'string', 'max:20'],
            'sort' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $image = Uploads::image($request->file('photo'), $data['image'] ?? $banner->image);
        if (! $image) {
            abort(422, 'Add a banner photo or an image path.');
        }

        $banner->fill([
            'eyebrow' => $data['eyebrow'] ?? null,
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
            'cta' => $data['cta'] ?? null,
            'cta_href' => $data['cta_href'] ?: '/collections',
            'cta2' => $data['cta2'] ?? null,
            'note' => $data['note'] ?? null,
            'image' => $image,
            'image_alt' => $data['image_alt'] ?? null,
            'image2' => $data['image2'] ?? null,
            'chip_top' => $data['chip_top'] ?? null,
            'chip' => $data['chip'] ?? null,
            'background' => $data['background'] ?: '#5A1220',
            'sort' => $data['sort'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);
        $banner->save();
    }
}
