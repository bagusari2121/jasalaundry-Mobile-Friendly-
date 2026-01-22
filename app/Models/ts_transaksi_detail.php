<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ts_transaksi_detail extends Model
{
    //
    use HasFactory;
    protected $table = 'ts_transaksi_detail';
    protected $fillable = [
        'id_transaksi',
        'jenis',
        'nama_produk',
        'nama_layanan',
        'qty',
        'harga',
        'subtotal',
        'diskon'
    ];

    public function transaksi(){
        return $this->belongsTo(ts_transaksi::class, 'id_transaksi', 'id');
    }

    // public function produk(){
    //     return $this->belongsTo(tm_produk::class);
    // }

    // public function layanan(){
    //     return $this->belongsTo(tm_layanan::class);
    // }
}
