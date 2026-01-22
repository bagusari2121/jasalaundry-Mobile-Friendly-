<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ts_riwayat_deposit extends Model
{
    protected $table = 'ts_riwayat_deposit';
    protected $fillable = [
        'id_customer',
        'nominal',
        'saldo_akhir',
        'keterangan',
        'id_user'
    ];
    public function customer()
    {
        return $this->belongsTo(tm_customer::class,'id_customer');
    }

    public function user() 
    {
        return $this->belongsTo(User::class,'id_user');
    }
}
