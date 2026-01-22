<?php

namespace App\Http\Controllers;

use App\Models\tm_kategori_pengeluaran;
use App\Models\tm_outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\tm_produk;
use App\Models\ts_pengeluaran;
use App\Models\User;


class PengeluaranController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();

        if($user->role !== 'Owner'){
            $outlets = tm_outlet::where('id',$user->id_outlet)->get();
            $pengeluaran = ts_pengeluaran::where('outlet_id',$user->id_outlet) 
                                            ->orderBy('created_at','desc')->get();
        }else{
            $outlets = tm_outlet::orderBy('nama_outlet','asc')->get();
            $pengeluaran = ts_pengeluaran::orderBy('created_at','desc')->get();
        }

        $categories = tm_kategori_pengeluaran::where('is_active','1')->orderBy('nama_pengeluaran','asc')->get();

        $totalPengeluaran = $pengeluaran->where('status','Aktif')
                                    ->sum('nominal');

        return view('pengeluaran.index', compact(
            'pengeluaran',
            'outlets',
            'categories',
            'totalPengeluaran',
            'user'
        ));
    }

    public function store_pengeluaran(Request $request)
    {
        // 1️⃣ VALIDASI (WAJIB, JANGAN DITAWAR)
        $request->validate([
            'tanggal'            => 'required|date',
            'outlet_id'          => 'required|exists:tm_outlet,id',
            'kategori_id'        => 'required|exists:tm_kategori_pengeluaran,id',
            'nominal'            => 'required|numeric|min:0',
            'metode_pembayaran'  => 'required|string',
            'is_rutin'           => 'required|string',
            'keterangan'         => 'nullable|string',
            'bukti'              => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        // dd($request);

        // 2️⃣ HANDLE FILE
        $file = $request->file('bukti');

        $filename = 'pengeluaran_'
            . now()->format('YmdHis') . '_'
            . Str::random(6) . '.'
            . $file->getClientOriginalExtension();

        $path = $file->storeAs('bukti_pengeluaran', $filename, 'public');

        // 3️⃣ SIMPAN KE DATABASE
        ts_pengeluaran::create([
            'tanggal'            => $request->tanggal,
            'outlet_id'          => $request->outlet_id,
            'kategori_id'        => $request->kategori_id,
            'nominal'            => $request->nominal,
            'metode_pembayaran'  => $request->metode_pembayaran,
            'is_rutin'           => $request->is_rutin,
            'keterangan'         => $request->keterangan,
            'bukti'              => $path,
            'status'             => 'Aktif',
            'user_id'            => Auth::id(),
        ]);

        // 4️⃣ REDIRECT
        return redirect()
            ->route('pengeluaran.index')
            ->with('success', 'Pengeluaran berhasil disimpan, Data Bisa Diedit Maksimal 10 Menit setelah data diupload');
    }

    public function update_pengeluaran(Request $request, $id)
    {
        $pengeluaran = ts_pengeluaran::findOrFail($id);

        $request->validate([
            'kategori_id' => 'required',
            'nominal' => 'required|numeric',
            'metode_pembayaran' => 'required',
            'bukti' => 'nullable|image|max:2048',
        ]);

        // JIKA ADA BUKTI BARU
        if ($request->hasFile('bukti')) {

            // HAPUS BUKTI LAMA
            if ($pengeluaran->bukti && Storage::disk('public')->exists($pengeluaran->bukti)) {
                Storage::disk('public')->delete($pengeluaran->bukti);
            }

            // SIMPAN BUKTI BARU
            $path = $request->file('bukti')->store('bukti_pengeluaran', 'public');
            $pengeluaran->bukti = $path;
        }

        $pengeluaran->update([
            'kategori_id' => $request->kategori_id,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'metode_pembayaran' => $request->metode_pembayaran,
            'status' => 'Aktif',
        ]);

        return redirect()->back()->with('success', 'Pengeluaran berhasil diperbarui');
    }

    public function cancel(Request $request, $id)
    {
        $user = Auth::User();
        $request->validate([
            'cancel_reason' => 'required|string|min:5'
        ]);

        $pengeluaran = ts_pengeluaran::findOrFail($id);

        if ($pengeluaran->status === 'dibatalkan') {
            abort(403);
        }

        $pengeluaran->update([
            'status'        => 'Request Batal',
            'alasan_pembatalan' => $request->cancel_reason,
            'cancel_by'     => auth()->id(),
            'cancel_at'     => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Pengeluaran berhasil diajukan untuk dibatalkan');
    }

    public function dataCancel(){
        $user = Auth::User();
        $pengeluaran = ts_pengeluaran::where('status','Request Batal')
                                        ->get();
        return view('Pengeluaran.cancel',compact('user','pengeluaran'));
    }

    public function accCancel(Request $request) 
    {
        // Ambil ID dari input hidden yang dikirim form
        $id = $request->id; 

        $pengeluaran = ts_pengeluaran::findOrFail($id);
        $pengeluaran->status = 'Batal';
        $pengeluaran->update();

        return redirect()->back()->with('success', 'Pengeluaran Berhasil Dibatalkan');
    }

    public function tolakCancel(Request $request, $id){
        $request->validate([
            'reject_reason' => 'required|string|max:255',
        ]);

        $pengeluaran = ts_pengeluaran::findOrFail($id);
        $alasan_lama = $pengeluaran->alasan_pembatalan ?? '';
        $alasan_baru = $alasan_lama . "\n" . 
                   "[" . now()->format('d/m/Y H:i') . " - Ditolak]: " . 
                   $request->reject_reason;
        
        $pengeluaran->update([
            'status' => 'Aktif', // Kembali ke status Aktif
            'alasan_pembatalan' => $alasan_baru, // Opsional: simpan alasan
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Permintaan pembatalan telah ditolak.');
    }

    public function kategori(){
        $user = Auth::User();
        $categories = tm_kategori_pengeluaran::orderBy('nama_pengeluaran')->get();
        return view('Pengeluaran.kategori', compact('categories','user'));
    }

    public function store_kategori(Request $request)
    {
        $user = Auth::User();
        // SECURITY: hanya owner
        if ($user->role == 'Kasir') {
            abort(403);
        }

        $request->validate([
            'nama_pengeluaran' => 'required|string|max:100|unique:tm_kategori_pengeluaran,nama_pengeluaran',
        ]);

        tm_kategori_pengeluaran::create([
            'nama_pengeluaran'      => $request->nama_pengeluaran,
            'is_active' => 1
        ]);

        return redirect()
            ->route('kategori-pengeluaran.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * UPDATE KATEGORI (EDIT – MODAL)
     */
    public function update_kategori(Request $request, $id)
    {
        $user = Auth::user();

        // SECURITY: hanya owner / admin
        if ($user->role === 'Kasir') {
            abort(403);
        }

        $kategori = tm_kategori_pengeluaran::findOrFail($id);

        $request->validate([
            'nama_pengeluaran' => 'required|string|max:100|unique:tm_kategori_pengeluaran,nama_pengeluaran,' . $kategori->id,
            'is_active'        => 'required|in:0,1',
        ]);

        $kategori->update([
            'nama_pengeluaran' => $request->nama_pengeluaran,
            'is_active'        => $request->is_active,
        ]);

        return redirect()
            ->route('kategori-pengeluaran.index')
            ->with('success', 'Kategori berhasil diperbarui');
    }

}
