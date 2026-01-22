<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stock_logs extends Model
{
    use HasFactory;
    protected $table = 'stock_logs';
    protected $fillable = [
        'id_outlet',
        'id_produk',
        'tipe',
        'jumlah',
        'tanggal',
        'keterangan',
        'pic'
    ];

    public function produk(){
        return $this->belongsTo(tm_produk::class,'id_produk');
    }

    public function user(){
        return $this->belongsTo(User::class,'pic');
    }

    public function outlet(){
        return $this->belongsTo(tm_outlet::class, 'id_outlet');
    }
}
