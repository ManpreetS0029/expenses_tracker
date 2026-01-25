<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MonthlyTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'month',
        'total_income',
        'needs',
        'wants',
        'savings',
        'investments',
    ];

    protected $casts = [
        'month' => 'date',
        'total_income' => 'decimal:2',
        'needs' => 'decimal:2',
        'wants' => 'decimal:2',
        'savings' => 'decimal:2',
        'investments' => 'decimal:2',
    ];
}
