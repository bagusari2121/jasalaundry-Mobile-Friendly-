<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tm_user extends Model
{
    use HasFactory;
    protected $table = 'tm_user';
    protected $fillable = [
        'nama_user',
        'username',
        'email',
        'password',
        'role',
        'id_outlet'
    ];

    public function outlet(){
        return $this->belongsTo(tm_outlet::class);
    }
}
