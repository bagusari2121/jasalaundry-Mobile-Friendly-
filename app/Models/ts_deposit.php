<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ts_deposit extends Model
{
    protected $table = 'ts_deposit';
    protected $fillable = [
        'id_customer',
        'saldo',
    ];

    public function customer()
    {
        return $this->belongsTo(tm_customer::class,'id_customer');
    }
}
