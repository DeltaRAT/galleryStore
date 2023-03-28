<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CheckoutController extends Controller
{
    public function show(){

        $products = json_decode(Cookie::get('basket'),true);

        $productsPrice = array_sum(array_column($products, 'price'));

        return view('frontend.checkout', compact('products', 'productsPrice'));
    }

    public function removeFromCheckout($product_id){

        $products = json_decode(Cookie::get('basket'),true);

        if (isset($products[$product_id]))
            unset($products[$product_id]);

        Cookie::queue('basket',json_encode($products));

        return back()->with('success', 'محصول حذف شد');

    }
}
