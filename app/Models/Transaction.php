<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ["billing_id", "cashier_id"];

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function billing()
    {
        return $this->belongsTo(Billing::class, 'billing_id');
    }
}
