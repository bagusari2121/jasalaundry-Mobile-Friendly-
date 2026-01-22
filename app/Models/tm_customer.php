<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tm_customer extends Model
{
    use HasFactory;
    protected $table = 'tm_customer';
    protected $fillable = [
        'nama_customer',
        'telepon',
        'alamat',
        'id_outlet',
        'is_langganan'
    ];

    public function outlet()
    {
        // Pastikan nama model outlet sesuai (misal: tm_outlet)
        // 'id_outlet' adalah foreign key yang ada di tabel tm_customer
        return $this->belongsTo(tm_outlet::class, 'id_outlet');
    }
}
