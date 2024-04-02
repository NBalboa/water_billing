<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\Billing;
// use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    // protected $guard = [];

    protected $fillable = ['username', 'first_name', 'last_name', 'password', 'address', 'phone_no', 'status', 'remember_token'];

    public function billings()
    {
        return $this->hasMany(Billing::class)->latest();
    }
    protected $hidden = [
        'username',
        'password',
    ];
}
