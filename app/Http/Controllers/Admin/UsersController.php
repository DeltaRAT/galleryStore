<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\StoreRequest;
use App\Http\Requests\Admin\Users\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function all(){

        $users = User::paginate();
        return view('admin.users.index', compact('users'));
    }

    public function create(){

        return view('admin.users.add');
    }

    public function store(StoreRequest $request){
        $validatedData = $request->validated();

        $created_user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'mobile' => $validatedData['mobile'],
            'role' => $validatedData['role']
        ]);

        if (!$created_user)
            return back()->with('failed', 'کاربر ایجاد نشد');
        return back()->with('success','کاربر ایجاد شد');

    }

    public function edit($user_id){
        $user = User::findOrFail($user_id);

        return view('admin.users.edit',compact('user'));
    }
    public function update(UpdateRequest $request, $user_id){
        $validatedData = $request->validated();

        $user = User::findOrFail($user_id);

        $updated_user = $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'mobile' => $validatedData['mobile'],
            'role' => $validatedData['role']
        ]);

        if (!$updated_user)
            return back()->with('failed', 'خطایی رخ داد');
        return back()->with('success', 'بروزرسانی با موفقیت انجام شد');


    }


    public function delete($user_id){

       $user = User::findOrFail($user_id);
        $user -> delete();

        return back()->with('success','کاربر حذف شد');
    }


}
