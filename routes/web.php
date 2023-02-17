<?php

use Illuminate\Support\Facades\Route;

Route::get('products/all', function () {  #callBack function
    return view('frontend.products.all');
});
Route::get('admin/all', function () {  #callBack function
    return view('admin.index');
});Route::get('admin/users', function () {  #callBack function
    return view('admin.users.index');
});
