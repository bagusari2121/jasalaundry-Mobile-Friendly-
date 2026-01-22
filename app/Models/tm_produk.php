<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tm_produk extends Model
{
    use HasFactory;
    protected $table = 'tm_produk';
    protected $fillable = [
        'kode_produk',
        'nama_produk',
        'harga_beli',
        'harga_jual',
        'stok',
        'satuan',
        'diskon'
    ];
}
