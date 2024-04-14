<?php

namespace App\Http\Controllers;

use App\Models\BillingArea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function all()
    {

        $users = User::where('is_deleted', 0)->with('area')->get();

        return view('user', ['users' => $users]);
    }

    public function profile($id)
    {
        $user =  User::with('area')->findOrFail($id);

        if (auth()->user()->id == $user->id) {
            return view('profile.user', compact("user"));
        }

        return abort(Response::HTTP_FORBIDDEN);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = 1;
        $user->save();

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
            'barangay' => ['required'],
            'assign_id' => 'required',
            'phone_no' => 'required|max:11',
            'status' => ['required'],
        ]);



        $user->update($attributes);

        return redirect('/admin/users');
    }

    public function showEdit($id)
    {
        $user = User::findOrFail($id);

        if ($user->status == 1) {
            $area = BillingArea::find($user->assign_id);
            $areas = BillingArea::all();
            return view('update.user', ['user' => $user, 'areas' => $areas, 'area' => $area]);
        }
        return view('update.user', ['user' => $user]);
    }


    public function changePassword($id)
    {

        $user = User::findOrFail($id);

        $attributes = request()->validate([
            'old_password' => ['required'],
            'new_password' => ['required', 'min:7'],
            'conf_new_password' => ['required', 'min:7', 'same:new_password']
        ]);

        if (Hash::check($attributes['old_password'], $user->password)) {
            $user->password = Hash::make($attributes['new_password']);
            $user->save();
        } else {

            // dd('incorrect password');
            return redirect("/profile/{$user->id}")->withErrors(['old_password' => 'Invalid Password']);
        }

        return redirect("/profile/{$user->id}")->with('success', 'Password Successfully Change');
    }

    public function changeUsername($id)
    {
        $user = User::findOrFail($id);

        $attributes = request()->validate([
            'username' => ['required', 'min:4', 'unique:users,username'],
            'password' => ['required']
        ]);

        if (Hash::check($attributes['password'], $user->password)) {
            $user->username = $attributes['username'];
            $user->save();
        } else {
            return redirect("/profile/{$user->id}")->withErrors(['password' => 'Invalid Password']);
        }

        return redirect("/profile/{$user->id}")->with('success', 'Password Successfully Change');
    }
}
