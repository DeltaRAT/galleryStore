<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;


class ProductsController extends Controller
{
    public function index(Request $request){

        $products = null;

        if (isset($request->filter,$request->action)){
            $products = $this->findFilter($request?->filter,$request?->action, $request?->value) ?? Product::all();
        }else if ($request->has('search')){
            $products = Product::where('title','LIKE', '%' . $request->input('search') . '%')->get();
        }else{
            $products = Product::all();
        }

        $categories = Category::all();

        return view('frontend.products.all', compact('products','categories'));
    }

    public function show($product_id){
        $product = Product::findOrFail($product_id);

        $similarProducts = Product::where('category_id', $product->category_id)->take(4)->get();

        return view('frontend.products.show', compact('product', 'similarProducts'));
    }

    private function findFilter(string $className, string $methodName, string|null $priceValue){

        $baseNamespace = "App\Http\Controllers\Filters\\";

        $className = $baseNamespace . (ucfirst($className) . 'Filter');

        if (!class_exists($className))
            return null;

        $object = new $className;

        if (!method_exists($object, $methodName))
            return null;

        return $object->{$methodName}($priceValue);
    }
}
