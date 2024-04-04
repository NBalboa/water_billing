<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'consumer_id',
        'reading_date',
        'due_date',
        'after_due',
        'previos',
        'current',
        'total_consumption',
        'status',
        'price',
        'source_charges',
        'total',
        'paid_at'
    ];

    public static function getYears()
    {
        return
            self::selectRaw('extract(year FROM reading_date) AS year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->get();
    }

    public function consumer()
    {
        return $this->belongsTo(Consumer::class);
    }
}
