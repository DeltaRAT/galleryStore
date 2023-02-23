<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Products\CreateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\utilities\ImageUploader;

class ProductsController extends Controller
{
    public function all()
    {
        $products = Product::paginate(10);

        return view('admin.products.all', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.products.add', compact('categories'));
    }

    public function store(CreateRequest $request)
    {
        $validatedData = $request->validated();

        $admin = User::where('email', 'admin@gmail.com')->first();

        $createdProduct = Product::create([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'category_id' => $validatedData['category_id'],
            'price' => $validatedData['price'],
            'owner_id' => $admin->id
        ]);
        try {
            $basePath = 'products/' . $createdProduct->id . '/';

            $sourceImageFullPath = $basePath . 'source_url_' . $validatedData['source_url']->getClientOriginalName();

            $images = [
                'thumbnail_url' => $validatedData['thumbnail_url'],
                'demo_url' => $validatedData['demo_url']
            ];

            ImageUploader::upload($validatedData['source_url'], $sourceImageFullPath, 'local_storage');
            $imagePath = ImageUploader::uploadMany($images, $basePath);

            $updatedProduct = $createdProduct->update([
                'thumbnail_url' => $imagePath['thumbnail_url'],
                'demo_url' => $imagePath['demo_url'],
                'source_url' => $sourceImageFullPath
            ]);
            if (!$updatedProduct)
                throw new \Exception('تصاویر آپلود نشدند');
            return back()->with('success', 'محصول ایجاد شد');

        } catch (\Exception $e) {
            return back()->with('failed', $e->getMessage());
        }
    }

    public function delete($product_id){
        $product = Product::findOrFail($product_id);
        $product->delete();

        return back()->with('success','محصول حذف گردید');
    }

    public function downloadDemo($product_id)
    {
        $product = Product::findOrFail($product_id);
        return response()->download(public_path($product->demo_url));
    }

    public function downloadSource($product_id)
    {
        $product = Product::findOrFail($product_id);
        return response()->download(storage_path('app/local_storage/' . $product->source_url));
    }
}
