<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class stok_outlet extends Model
{
    use HasFactory;
    protected $table = 'stok_outlet';
    protected $fillable = [
        'id_outlet',
        'id_produk',
        'stok',
    ];

    public function produk()
    {
        return $this->belongsTo(tm_produk::class, 'id_produk');
    }

    public function outlet(){
        return $this->belongsTo(tm_outlet::class, 'id_outlet');
    }

}   
