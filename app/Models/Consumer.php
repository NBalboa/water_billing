<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumer extends Model
{
    use HasFactory;

    protected $fillable = ['meter_code', 'first_name', 'last_name', 'address', 'phone_no'];

    public function scopeFilter($query)
    {
        if (request('table_search') ?? false) {
            $query->whereAny(
                ['meter_code', 'first_name', 'last_name', 'phone_no', 'address'],
                'LIKE',
                '%' . request('table_search') . '%'
            )->get();
        }
    }
    public function billings()
    {
        return $this->hasMany(Billing::class)->latest();
    }
}
