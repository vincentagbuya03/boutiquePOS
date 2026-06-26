<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::with(['category', 'firstAvailableBatch'])->paginate(15);
        
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form to add a new product.
     */
    public function create()
    {
        /** @var User $user */
        $user = Auth::user();

        // Only Staff and Owner can create products
        if (! Auth::check() || ! $user->canManageProducts()) {
            throw new AuthorizationException('You do not have permission to create products');
        }

        $categories = Category::orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Only Staff and Owner can store products
        if (! Auth::check() || ! $user->canManageProducts()) {
            throw new AuthorizationException('You do not have permission to create products');
        }

        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:100',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = Product::where('name', $value)
                        ->where('size', $request->size)
                        ->exists();
                    if ($exists) {
                        $fail('A product with this name and size already exists.');
                    }
                }
            ],
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')
                ->store('products', $this->productImagesDisk());
        }

        unset($validated['image']);
        $validated['date_added'] = today();

        $product = Product::create($validated);

        return redirect()->route('products.show', $product)->with('success', 'Product added successfully');
    }

    /**
     * Show product details.
     */
    public function show(Product $product)
    {
        $product->load('firstAvailableBatch');
        $inventories = $product->inventories()->get();
        return view('products.show', compact('product', 'inventories'));
    }

    /**
     * Show the form to edit a product.
     */
    public function edit(Product $product)
    {
        /** @var User $user */
        $user = Auth::user();

        // Only Staff and Owner can edit products
        if (! Auth::check() || ! $user->canManageProducts()) {
            throw new AuthorizationException('You do not have permission to edit products');
        }

        $categories = Category::orderBy('name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update a product.
     */
    public function update(Request $request, Product $product)
    {
        /** @var User $user */
        $user = Auth::user();

        // Only Staff and Owner can update products
        if (! Auth::check() || ! $user->canManageProducts()) {
            throw new AuthorizationException('You do not have permission to update products');
        }

        $validated = $request->validate([
            'name' => [
                'required', 
                'string', 
                'max:100',
                function ($attribute, $value, $fail) use ($request, $product) {
                    $exists = Product::where('name', $value)
                        ->where('size', $request->size)
                        ->where('id', '!=', $product->id)
                        ->exists();
                    if ($exists) {
                        $fail('A product with this name and size already exists.');
                    }
                }
            ],
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:100',
            'size' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $oldImagePath = $product->image_path;
            $validated['image_path'] = $request->file('image')
                ->store('products', $this->productImagesDisk());

            if ($oldImagePath) {
                Storage::disk($this->productImagesDisk())->delete($oldImagePath);
            }
        }

        unset($validated['image']);
        $product->update($validated);

        return redirect()->route('products.show', $product)->with('success', 'Product updated successfully');
    }

    /**
     * Archive a product.
     */
    public function destroy(Product $product)
    {
        /** @var User $user */
        $user = Auth::user();

        // Only Staff and Owner can archive products
        if (! Auth::check() || ! $user->canManageProducts()) {
            throw new AuthorizationException('You do not have permission to archive products');
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product archived successfully');
    }

    private function productImagesDisk(): string
    {
        return config('filesystems.product_images_disk', 'public');
    }
}
