<?php

namespace App\Http\Controllers;

use App\Models\BillingArea;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function all()
    {

        $users = User::where('is_deleted', 0)->with('area')->get();

        return view('user', ['users' => $users]);
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
}
