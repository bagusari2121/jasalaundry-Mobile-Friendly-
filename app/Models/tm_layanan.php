<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tm_layanan extends Model
{
    use HasFactory;
    protected $table = 'tm_layanan';
    protected $fillable = [
        'nama_layanan',
        'harga',
        'satuan',
        'estimasi_selesai',
        'diskon',
    ];
}
