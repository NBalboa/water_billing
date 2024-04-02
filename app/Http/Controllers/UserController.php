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

    public function showEdit($id)
    {
        $user = User::findOrFail($id);
        dd($user);
        return view('update.user', ['user' => $user]);
    }
}
