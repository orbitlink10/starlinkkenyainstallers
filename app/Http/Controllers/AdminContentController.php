<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\SitePage;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminContentController extends Controller
{
    public function pagesIndex(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $pages = SitePage::query()
            ->when($search !== '', fn (Builder $query): Builder => $query->where('page_title', 'like', "%{$search}%"))
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('admin.pages.index', [
            'pages' => $pages,
            'search' => $search,
            'activeSection' => 'pages',
        ]);
    }

    public function pagesCreate(): View
    {
        return view('admin.pages.create', [
            'activeSection' => 'pages',
        ]);
    }

    public function pagesPreview(SitePage $page): View
    {
        return view('admin.pages.preview', [
            'page' => $page,
        ]);
    }

    public function pagesEdit(SitePage $page): View
    {
        return view('admin.pages.edit', [
            'page' => $page,
            'activeSection' => 'pages',
        ]);
    }

    public function pagesStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'page_title' => ['required', 'string', 'max:255'],
            'image_alt_text' => ['nullable', 'string', 'max:255'],
            'heading_2' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:50'],
            'page_description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        $imagePath = $request->file('image')?->store('pages', 'public');

        SitePage::create([
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'page_title' => $validated['page_title'],
            'slug' => $this->uniqueSlug($validated['page_title'], SitePage::query()),
            'image_alt_text' => $validated['image_alt_text'] ?? null,
            'heading_2' => $validated['heading_2'] ?? null,
            'type' => $validated['type'],
            'page_description' => $validated['page_description'] ?? null,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('pages.index')->with('success', 'Page created successfully.');
    }

    public function pagesUpdate(Request $request, SitePage $page): RedirectResponse
    {
        $validated = $request->validate([
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'page_title' => ['required', 'string', 'max:255'],
            'image_alt_text' => ['nullable', 'string', 'max:255'],
            'heading_2' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:50'],
            'page_description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        $payload = [
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'page_title' => $validated['page_title'],
            'image_alt_text' => $validated['image_alt_text'] ?? null,
            'heading_2' => $validated['heading_2'] ?? null,
            'type' => $validated['type'],
            'page_description' => $validated['page_description'] ?? null,
        ];

        if ($page->page_title !== $validated['page_title']) {
            $payload['slug'] = $this->uniqueSlug($validated['page_title'], SitePage::query(), $page->id);
        }

        if ($request->hasFile('image')) {
            if ($page->image_path && Storage::disk('public')->exists($page->image_path)) {
                Storage::disk('public')->delete($page->image_path);
            }

            $payload['image_path'] = $request->file('image')->store('pages', 'public');
        }

        $page->update($payload);

        return redirect()->route('pages.index')->with('success', 'Page updated successfully.');
    }

    public function pagesDestroy(SitePage $page): RedirectResponse
    {
        if ($page->image_path && Storage::disk('public')->exists($page->image_path)) {
            Storage::disk('public')->delete($page->image_path);
        }

        $page->delete();

        return redirect()->route('pages.index')->with('success', 'Page deleted successfully.');
    }

    public function categoriesIndex(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $categories = Category::query()
            ->when($search !== '', fn (Builder $query): Builder => $query->where('name', 'like', "%{$search}%"))
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('admin.categories.index', [
            'categories' => $categories,
            'search' => $search,
            'activeSection' => 'categories',
        ]);
    }

    public function categoriesCreate(): View
    {
        return view('admin.categories.create', [
            'activeSection' => 'categories',
        ]);
    }

    public function categoriesEdit(Category $category): View
    {
        return view('admin.categories.edit', [
            'category' => $category,
            'activeSection' => 'categories',
        ]);
    }

    public function categoriesStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        $photoPath = $request->file('photo')?->store('categories', 'public');

        Category::create([
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug($validated['name'], Category::query()),
            'meta_description' => $validated['meta_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'photo_path' => $photoPath,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function categoriesUpdate(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        $payload = [
            'name' => $validated['name'],
            'meta_description' => $validated['meta_description'] ?? null,
            'description' => $validated['description'] ?? null,
        ];

        if ($category->name !== $validated['name']) {
            $payload['slug'] = $this->uniqueSlug($validated['name'], Category::query(), $category->id);
        }

        if ($request->hasFile('photo')) {
            if ($category->photo_path && Storage::disk('public')->exists($category->photo_path)) {
                Storage::disk('public')->delete($category->photo_path);
            }

            $payload['photo_path'] = $request->file('photo')->store('categories', 'public');
        }

        $category->update($payload);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function categoriesDestroy(Category $category): RedirectResponse
    {
        if ($category->photo_path && Storage::disk('public')->exists($category->photo_path)) {
            Storage::disk('public')->delete($category->photo_path);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }

    public function productsIndex(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $products = Product::query()
            ->with(['category', 'subCategory'])
            ->when($search !== '', fn (Builder $query): Builder => $query->where('name', 'like', "%{$search}%"))
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'search' => $search,
            'activeSection' => 'products',
        ]);
    }

    public function productsCreate(): View
    {
        return view('admin.products.create', [
            'categories' => Category::query()->orderBy('name')->get(),
            'subCategories' => SubCategory::query()->orderBy('name')->get(),
            'activeSection' => 'products',
        ]);
    }

    public function productsEdit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::query()->orderBy('name')->get(),
            'subCategories' => SubCategory::query()->orderBy('name')->get(),
            'activeSection' => 'products',
        ]);
    }

    public function productsStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'marked_price' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'sub_category_id' => ['nullable', 'exists:sub_categories,id'],
            'meta_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'google_merchant' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        $imagePath = $request->file('image')?->store('products', 'public');

        Product::create([
            'name' => $validated['name'],
            'slug' => $this->uniqueSlug($validated['name'], Product::query()),
            'price' => $validated['price'],
            'marked_price' => $validated['marked_price'] ?? null,
            'stock' => $validated['quantity'] ?? 0,
            'quantity' => $validated['quantity'] ?? 0,
            'category_id' => $validated['category_id'] ?? null,
            'sub_category_id' => $validated['sub_category_id'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'google_merchant' => (bool) ($validated['google_merchant'] ?? false),
            'image_path' => $imagePath,
            'is_active' => true,
        ]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function productsUpdate(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'marked_price' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'sub_category_id' => ['nullable', 'exists:sub_categories,id'],
            'meta_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'google_merchant' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
        ]);

        $payload = [
            'name' => $validated['name'],
            'price' => $validated['price'],
            'marked_price' => $validated['marked_price'] ?? null,
            'stock' => $validated['quantity'] ?? 0,
            'quantity' => $validated['quantity'] ?? 0,
            'category_id' => $validated['category_id'] ?? null,
            'sub_category_id' => $validated['sub_category_id'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'google_merchant' => (bool) ($validated['google_merchant'] ?? false),
        ];

        if ($product->name !== $validated['name']) {
            $payload['slug'] = $this->uniqueSlug($validated['name'], Product::query(), $product->id);
        }

        if ($request->hasFile('image')) {
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            $payload['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($payload);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function productsDestroy(Product $product): RedirectResponse
    {
        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    private function uniqueSlug(string $name, Builder $query, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $root = $base !== '' ? $base : 'item';
        $slug = $root;
        $counter = 1;

        while ((clone $query)
            ->when($ignoreId, fn (Builder $builder): Builder => $builder->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $root.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
