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

    protected $fillable = ['username', 'assign_id', 'first_name', 'last_name', 'password', 'street', 'barangay', 'phone_no', 'status', 'remember_token'];

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function area()
    {
        return $this->belongsTo(BillingArea::class, 'assign_id');
    }
    protected $hidden = [
        'username',
        'password',
    ];
}
