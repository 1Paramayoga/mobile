<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'user_id',
        'total_price',
        'total_item',
        'cash',
        'change',
        'date',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
