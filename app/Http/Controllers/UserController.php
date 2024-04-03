<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function all()
    {

        $users = User::all();

        return view('user', ['users' => $users]);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/admin/users');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        $attributes = request()->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            // 'address' => 'required',
            'street' => ['required'],
            'provinces' => ['required'],
            'phone_no' => 'required|max:11',
            'status' => ['required'],
        ]);

        $attributes['address'] = strtolower("{$attributes['street']}, {$attributes['provinces']}");

        $user->update($attributes);

        return redirect('/admin/users');
    }

    public function showEdit($id)
    {
        $user = User::findOrFail($id);
        return view('update.user', ['user' => $user]);
    }
}
