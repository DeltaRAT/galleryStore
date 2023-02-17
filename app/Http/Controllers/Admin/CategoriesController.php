<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\CreateRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function create(){
        return view('admin.categories.create');
    }
    public function store(CreateRequest $request){
        $validatedData = $request->validated() ;
        $createdCategory = Category::create([
           'title' => $validatedData['title'],
            'slug' => $validatedData['slug']
        ]);
        if (!$createdCategory){
            return back()->with('failed','عملیات موفقیت آمیز نبود:(');
        }
        return back()->with('success', 'عملیات موفقیت آمیز بود:)');
    }

    public function all(){
        $categories = Category::all();

        return view('admin.categories.all', compact('categories'));
    }

}
