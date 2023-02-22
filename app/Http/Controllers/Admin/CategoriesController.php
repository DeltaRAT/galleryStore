<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Categories\CreateRequest;
use App\Http\Requests\Admin\Categories\UpdateRequest;
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
        $categories = Category::paginate(5);  #pagination

        return view('admin.categories.all', compact('categories'));
    }

    public function delete($category_id){
        $category = Category::find($category_id);
        $category->delete();

        return back()->with('success','دسته بندی حذف شد');

    }

    public function edit($category_id){
        $category = Category::find($category_id);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateRequest $request, $category_id){
        $validatedData = $request->validated();

        $category = Category::find($category_id);

        $updated_category = $category->update([
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug']
        ]);

        if ($updated_category){
            return back()->with('success','دسته بندی بروزرسانی شد D:');
        }
            return back()->with('failed', 'اختلالی رخ داده است');
    }


}
