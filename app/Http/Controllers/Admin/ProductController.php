<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        // Search by name or description
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Paginate
        $products = $query->paginate(10);

        // Get categories for filter dropdown
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0',
            'preparation_time' => 'nullable|integer|min:0',
            'expiry_date' => 'nullable|date|after_or_equal:today',
            'requires_refrigeration' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        // Generate slug
$validated['slug'] = Str::slug($validated['name']);

// TAMBAHKAN INI: Generate SKU unik
// Contoh: "NAMA-PRODUK-RANDOMSTRING"
$validated['sku'] = strtoupper(Str::slug($validated['name'], '-')) . '-' . Str::random(5);

        try {
            DB::beginTransaction();

            // Set default values for checkboxes
            $validated['is_featured'] = $request->has('is_featured');
            $validated['is_active'] = $request->has('is_active');
            $validated['requires_refrigeration'] = $request->has('requires_refrigeration');

            // Create product
            $product = Product::create($validated);

            // Handle multiple image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => false, // Make the first uploaded image primary
                    ]);
                }

                // Set first image as primary if any images were uploaded
                $firstImage = $product->images()->first();
                if ($firstImage) {
                    $firstImage->update(['is_primary' => true]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil ditambahkan');
} catch (\Exception $e) {
    DB::rollBack();
    dd($e);
    return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
}
    }

    /**
     * Display the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        $product->load('category', 'images');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View
     */
    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'weight' => 'required|numeric|min:0',
            'preparation_time' => 'nullable|integer|min:0',
            'expiry_date' => 'nullable|date',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update slug only if name has changed
        if ($product->name != $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        try {
            DB::beginTransaction();

            // Set default values for checkboxes
            $validated['is_featured'] = $request->has('is_featured');
            $validated['is_active'] = $request->has('is_active');
            $validated['requires_refrigeration'] = $request->has('requires_refrigeration');

            // Update product
            $product->update($validated);

            // Handle multiple image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => false,
                    ]);
                }

                // If no primary image exists, make the first one primary
                $primaryImage = $product->images()->where('is_primary', true)->first();
                if (!$primaryImage) {
                    $firstImage = $product->images->first();
                    if ($firstImage) {
                        $firstImage->update(['is_primary' => true]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.products.show', $product)
                ->with('success', 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();

            // Delete product images from storage
            foreach ($product->images as $image) {
                Storage::delete('public/' . $image->image_path);
            }

            // Delete product and its related data
            $product->delete();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }

    /**
     * Set image as primary for a product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @param  \App\Models\ProductImage  $image
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setPrimaryImage(Request $request, Product $product, ProductImage $image)
    {
        if ($image->product_id != $product->id) {
            return back()->with('error', 'Image does not belong to this product');
        }

        DB::transaction(function () use ($product, $image) {
            // Remove primary flag from all images
            $product->images()->update(['is_primary' => false]);

            // Set current image as primary
            $image->update(['is_primary' => true]);
        });

        return back()->with('success', 'Gambar utama telah diperbarui');
    }

    /**
     * Delete an image from a product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @param  \App\Models\ProductImage  $image
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteImage(Request $request, Product $product, ProductImage $image)
    {
        if ($image->product_id != $product->id) {
            return back()->with('error', 'Image does not belong to this product');
        }

        try {
            DB::beginTransaction();

            // Check if this is the primary image
            $isPrimary = $image->is_primary;

            // Delete file from storage
            Storage::delete('public/' . $image->image_path);

            // Delete image record
            $image->delete();

            // If this was the primary image, set another one as primary
            if ($isPrimary) {
                $newPrimary = $product->images()->first();
                if ($newPrimary) {
                    $newPrimary->update(['is_primary' => true]);
                }
            }

            DB::commit();

            return back()->with('success', 'Gambar berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus gambar: ' . $e->getMessage());
        }
    }

    /**
     * Toggle the featured status of a product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleFeatured(Product $product)
    {
        $product->update([
            'is_featured' => !$product->is_featured
        ]);

        return back()->with('success', 'Status featured produk berhasil diubah');
    }

    /**
     * Toggle the active status of a product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggleActive(Product $product)
    {
        $product->update([
            'is_active' => !$product->is_active
        ]);

        return back()->with('success', 'Status aktif produk berhasil diubah');
    }
}
