<?php

namespace App\Http\Controllers;

use App\Models\stock_logs;
use App\Models\stok_outlet;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\tm_produk;
use App\Models\tm_outlet;
use Illuminate\Support\Facades\Auth;


class ProdukController extends Controller
{
    public function index(){
        $user = Auth::User();
        $produk = tm_produk::orderBy('kode_produk','asc')->get();
        // Ambil produk terakhir berdasarkan ID
        $lastProduk = tm_produk::orderBy('id', 'desc')->first();

        // Buat kode baru
        $nextId = $lastProduk ? $lastProduk->id + 1 : 1;
        $kode_produk = "KM-" . str_pad($nextId, 3, '0', STR_PAD_LEFT); // contoh: KM-001, KM-002
        $satuanList = ['pcs', 'kg', 'liter', 'box', 'pak', 'lusin'];
        return view('Produk.index',compact('user','produk','kode_produk','satuanList'));
    }

    public function store(Request $request){
        // validasi input
        $request->validate([
            'kode_produk' => 'required',
            'nama_produk' => 'required',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'satuan' => 'required',
            'diskon' => 'required'
        ]);

        // simpan data user baru
        tm_produk::create([
            'kode_produk' => $request->kode_produk,
            'nama_produk' => $request->nama_produk,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'satuan' => $request->satuan,
            'diskon' => $request->diskon,
        ]);

        return redirect()->back()->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'satuan' => 'required',
            'diskon' => 'required',
        ]);

        $produk = tm_produk::findOrFail($id);
        $produk->update([
            'nama_produk' => $request->nama_produk,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'satuan' => $request->satuan,
            'diskon' => $request->diskon,
        ]);

        return redirect()->back()->with('success', 'Produk berhasil diperbarui!');
    }
    
    public function delete($id)
    {
        $produk = tm_produk::find($id);
        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        $produk->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }

    public function stok_outlet(){
        $user = Auth::User();
        if($user->role == 'Admin'){
            $produk = tm_produk::orderBy('kode_produk','asc')->get();
            $stok_outlet = stok_outlet::orderBy('id','asc')->get();
            $outlet = tm_outlet::orderBy('id','asc')->get();
        }else if($user->role == 'Owner'){
            $produk = tm_produk::orderBy('kode_produk','asc')->get();
            $stok_outlet = stok_outlet::orderBy('id','asc')->get();
            $outlet = tm_outlet::orderBy('id','asc')->get();
        }else{
            $produk = tm_produk::orderBy('kode_produk','asc')->get();
            $stok_outlet = stok_outlet::where('id_outlet',$user->id_outlet)->orderBy('id','asc')->get();
            $outlet = tm_outlet::where('id',$user->id_outlet)->orderBy('id','asc')->get();
        };
        
        return view('Produk.produk_outlet', compact('user','produk','stok_outlet','outlet'));
    }

    public function stok_outlet_store(Request $request){
        $user = Auth::User();
        $stok_outlet = stok_outlet::orderBy('id','asc')->get();
        $request->validate([
            'id_outlet' => 'required',
            'id_produk' => 'required',
            'stok' => 'required|numeric',
        ]);
        // Cek apakah data stok dengan id_outlet dan id_produk sudah ada
        $stok_outlet = stok_outlet::where('id_outlet', $request->id_outlet)
                        ->where('id_produk', $request->id_produk)
                        ->first();

        if ($stok_outlet) {
            // Jika sudah ada, tambahkan stoknya
            $stok_outlet->stok += $request->stok;
            $stok_outlet->save();
        } else {
            // Jika belum ada, buat data baru
            stok_outlet::create([
                'id_outlet' => $request->id_outlet,
                'id_produk' => $request->id_produk,
                'stok' => $request->stok,
            ]);
        }
        // dd($user->id);
        stock_logs::create([
            'id_outlet' => $request->id_outlet,
            'id_produk' => $request->id_produk,
            'jumlah' => $request->stok,
            'tipe' => 'Masuk',
            'pic' => $user->id,
            'keterangan' => 'Stok Masuk'
        ]);
        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }

    public function stok_outlet_update(Request $request, $id)
    {
        $user = Auth::User();
        $request->validate([
            'stok' => 'required|numeric',
        ]);

        $stok_outlet = stok_outlet::findOrFail($id);
        $stok_outlet->stok += $request->stok; // tambah atau kurangi stok
        $stok_outlet->save();
        if($request->stok < 0){
            stock_logs::create([
            'id_outlet' => $stok_outlet->id_outlet,
            'id_produk' => $stok_outlet->id_produk,
            'jumlah' => $request->stok,
            'tipe' => 'Keluar',
            'pic' => $user->id,
            'keterangan' => $request->keterangan
            ]);
        }else{
            stock_logs::create([
            'id_outlet' => $stok_outlet->id_outlet,
            'id_produk' => $stok_outlet->id_produk,
            'jumlah' => $request->stok,
            'tipe' => 'Masuk',
            'pic' => $user->id,
            'keterangan' => $request->keterangan
            ]);
        }
        return redirect()->back()->with('success', 'Stok outlet berhasil diperbarui.');
    }

    public function riwayat(){
        $user = Auth::User();
        $riwayat = stock_logs::orderBy('id','desc')->get();

        return view('Produk.riwayat',compact('user','riwayat'));
    }

}
