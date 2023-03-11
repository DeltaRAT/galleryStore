<?php

namespace App\Http\Controllers\Filters;

use App\Models\Product;
use Illuminate\Http\Request;

class PriceFilter
{
    public function budget($priceValue){

        if (isset($priceValue))
        {
            $range_price = explode('to',$priceValue);
            return Product::whereBetween('price', [$range_price[0],$range_price[1]])->get();
        }
    }
}
