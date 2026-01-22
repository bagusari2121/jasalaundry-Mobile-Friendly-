<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ts_transaksi extends Model
{
    use HasFactory;
    protected $table = 'ts_transaksi';
    protected $fillable = [
        'kode_transaksi',
        'id_customer',
        'id_outlet',
        'tanggal_transaksi',
        'estimasi_selesai',
        'total_transaksi',
        'pic',
        'jumlah_bayar',
        'jumlah_diskon',
        'metode_pembayaran',
        'status_pembayaran',
        'tgl_pelunasan',
        'status_transaksi',
        'alasan',
    ];

    public function user(){
        return $this->belongsTo(User::class,'pic');
    }

    public function customer(){
        return $this->belongsTo(tm_customer::class,'id_customer');
    }

    public function outlet(){
        return $this->belongsTo(tm_outlet::class,'id_outlet');
    }

    public function detail()
    {
        return $this->hasMany(ts_transaksi_detail::class, 'id_transaksi');
    }

}
