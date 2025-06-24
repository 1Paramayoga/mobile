<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreInfo extends Model
{
    use HasFactory;

        public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $table = 'stores';

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'website',
    ];

}
