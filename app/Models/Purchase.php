<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    // protected $guarded = [];

    protected $fillable = [
        'invoice',
        'date',
        'total',
        'discount',
        'sub_total',
        'paid',
        'due',
        'transport',
        'vat',
        'previous_due',
        'status',
    ];

}
