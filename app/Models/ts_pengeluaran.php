<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;


class ts_pengeluaran extends Model
{
    use HasFactory;
    protected $table = 'ts_pengeluaran';
    protected $fillable = [
        'tanggal',
        'outlet_id',
        'kategori_id',
        'nominal',
        'metode_pembayaran',
        'keterangan',
        'bukti',
        'is_rutin',
        'user_id',
        'status',
        'alasan_pembatalan',
        'cancel_by',
        'cancel_at'
    ];
    

    public function canEdit()
    {
        return now()->lessThan($this->created_at->addMinutes(10));
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function kategori(){
        return $this->belongsTo(tm_kategori_pengeluaran::class,'kategori_id');
    }

    public function outlet(){
        return $this->belongsTo(tm_outlet::class,'outlet_id');
    }
}
