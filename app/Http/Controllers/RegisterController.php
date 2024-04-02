<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPUnit\Metadata\Uses;
use \App\Models\User;
use \Illuminate\Support\Str;

class RegisterController extends Controller
{
    //
    public function createAdmin()
    {
        return view('register.admin');
    }

    public function createCollector()
    {
        return view('register.collector');
    }


    public function storeAdmin()
    {


        $attributes = request()->validate([
            'username' => ['required', 'min:4', 'unique:users,username'],
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required|min:7',
            // 'address' => 'required',
            'street' => ['required'],
            'provinces' => ['required'],
            'municipalities' => ['required'],
            'barangays' => ['required'],
            'phone_no' => 'required|max:11',
            'confirm_password' => ['required', 'min:7', 'same:password'],
        ]);
        $attributes['address'] = strtolower("{$attributes['street']}, {$attributes['barangays']}, {$attributes['municipalities']}, {$attributes['provinces']}");
        // $attributes['address'] = "{$attributes['street']} {$attributes['barangays']} {$attributes['municipalities']} {$attributes['provinces']}";
        $attributes['status'] = 0; //Admin
        $attributes['password'] = bcrypt($attributes['password']);
        $attributes['remember_token'] = Str::random(60);
        unset($attributes['confirm_password']);

        User::create($attributes);

        session()->flash('success', 'Admin Created Successfully');


        return redirect('/admin/register/admin');
        // var_dump($data);
    }

    public function storeCollector()
    {
        $attributes = request()->validate([
            'username' => ['required', 'min:4', 'unique:users,username'],
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required|min:7',
            // 'address' => 'required',
            'street' => ['required'],
            'provinces' => ['required'],
            'municipalities' => ['required'],
            'barangays' => ['required'],
            'phone_no' => 'required|max:11',
            'confirm_password' => ['required', 'min:7', 'same:password'],
        ]);
        $attributes['address'] = strtolower("{$attributes['street']}, {$attributes['barangays']}, {$attributes['municipalities']}, {$attributes['provinces']}");

        // $attributes['address'] = "{$attributes['street']} {$attributes['barangays']} {$attributes['municipalities']} {$attributes['provinces']}";
        $attributes['status'] = 1; //Collector
        $attributes['password'] = bcrypt($attributes['password']);
        $attributes['remember_token'] = Str::random(60);
        unset($attributes['confirm_password']);

        User::create($attributes);

        session()->flash('success', 'Collector Created Successfully');

        return redirect('/admin/register/collector');
    }
}
