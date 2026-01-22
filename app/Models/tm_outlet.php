<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tm_outlet extends Model
{
    use HasFactory;
    protected $table = 'tm_outlet';
    protected $fillable = [
        'nama_outlet',
        'alamat',
        'telepon',
    ];
}
