<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\tm_layanan;



class LayananController extends Controller
{
    public function index(){
        $user = Auth::User();
        $layanan = tm_layanan::orderBy('nama_layanan','asc')->get();
        $satuanList = ['pcs', 'kg', 'liter', 'box', 'pak', 'lusin'];
        return view('Layanan.index',compact('user','layanan','satuanList'));
    }

    public function store(Request $request)
    {
        // 🔍 Validasi input
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
        ], [
            'nama_layanan.required' => 'Nama layanan wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'satuan.required' => 'Satuan wajib dipilih.',
        ]);

        try {
            // 💾 Simpan ke database
            tm_layanan::create([
                'nama_layanan' => $request->nama_layanan,
                'harga' => $request->harga,
                'satuan' => $request->satuan,
                'diskon' => $request->diskon,
            ]);

            // ✅ Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Layanan berhasil ditambahkan!');
        } catch (\Exception $e) {
            // ❌ Jika ada error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menambahkan layanan!');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
        ]);

        try {
            $layanan = tm_layanan::findOrFail($id);
            $layanan->update([
                'nama_layanan' => $request->nama_layanan,
                'harga' => $request->harga,
                'satuan' => $request->satuan,
                'diskon' => $request->diskon,
            ]);

            return redirect()->back()->with('success', 'Layanan berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui layanan!');
        }
    }

}
