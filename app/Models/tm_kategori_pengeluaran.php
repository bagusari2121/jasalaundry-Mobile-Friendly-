<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class tm_kategori_pengeluaran extends Model
{
    use HasFactory;
    protected $table = 'tm_kategori_pengeluaran';
    protected $fillable = [
        'nama_pengeluaran',
        'is_active',
    ];
}
