<?php

namespace App\Http\Controllers;

use App\Models\stock_logs;
use App\Models\stok_outlet;
use App\Models\tm_produk;
use App\Models\tm_outlet;
use App\Models\tm_layanan;
use App\Models\tm_customer;
use App\Models\ts_transaksi;
use App\Models\ts_transaksi_detail;
use App\Models\ts_deposit;
use App\Models\ts_riwayat_deposit;
use App\Http\Requests\StoreTransaksiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class TransaksiController extends Controller
{
    public function index(){
        $user = Auth::User();
        $isOwner = $user->role === 'Owner';
        // ambil layanan langsung
        $layanan = tm_layanan::all();

        // ambil stok_outlet dengan join ke produk biar bisa ambil nama & harga
        $stok_outlet = stok_outlet::where('id_outlet',$user->id_outlet)->with(['produk' => function($q) {
            $q->select('id', 'nama_produk', 'harga_jual','diskon');
        }])->get();

        $customer = tm_customer::when(!$isOwner, function ($query) use ($user) {
            return $query->where('id_outlet', $user->id_outlet);
        })->orderBy('nama_customer','asc')->get();
        // ubah jadi format yang lebih gampang dikonsumsi di JS
        $stok_outlet = $stok_outlet->map(function($item) {
            return [
                'id' => $item->id,
                'id_produk' => $item->id_produk,
                'nama_produk' => $item->produk->nama_produk ?? '-',
                'harga_jual' => $item->produk->harga_jual ?? 0,
                'stok' => $item->stok,
                'diskon' => $item->produk->diskon ?? 0,
            ];
        });

        return view('Transaksi.index',compact('user','stok_outlet','layanan','customer'));
    }

    // TransaksiController.php (method store)
    public function store(StoreTransaksiRequest $request)
    {
        $user = Auth::user();

        DB::beginTransaction();

        try {
            $totalDiskonHitung = 0; // Variabel penampung diskon
            // 1. Validasi Awal
            if (!is_array($request->items) || count($request->items) === 0) {
                throw new \Exception('Item transaksi tidak boleh kosong');
            }

            // 2. Format Tanggal
            // Jika pakai datetime-local, langsung ambil. Jika input cuma jam, pakai logika Anda.
            $estimasiSelesai = $request->estimasi_selesai; 
            if ($request->status_pembayaran == 'Lunas') {
                $tanggal_pelunasan = now();
            } else {
                $tanggal_pelunasan = null;
            }
            // Gunakan first() untuk mendapatkan satu objek, bukan kumpulan data
            $checkDeposit = ts_deposit::where('id_customer', $request->id_customer)->first();
            $status_pembayaran = $request->status_pembayaran;
            if ($checkDeposit && $checkDeposit->saldo > $request->total) {
                $pengurangan = $request->total;
                if($checkDeposit->saldo >= $pengurangan){
                    // Hitung saldo baru
                    $saldoBaru = $checkDeposit->saldo - $pengurangan;
                    
                    // Update datanya
                    $checkDeposit->update([
                        'saldo' => $saldoBaru
                    ]);

                    // Simpan catatan ke Riwayat Deposit
                    ts_riwayat_deposit::create([
                        'id_customer' => $request->id_customer,
                        'nominal'     => '-'.$pengurangan,
                        'saldo_akhir' => $saldoBaru, // Saldo setelah transaksi
                        'keterangan'  => 'Untuk Transaksi '. 'TRX-' . strtoupper(Str::random(5)) . now()->format('His'), // Atau bisa ambil dari input $request->keterangan
                        'id_user'     => auth()->id(),    // Mencatat siapa petugas yang menginput
                    ]);

                    $status_pembayaran = 'Lunas';
                }else{
                    $status_pembayaran = 'Belum Lunas';
                }
                // Opsional: Tampilkan pesan sukses atau log riwayat di sini
            }
            // 3. Simpan Master Transaksi
            $transaksi = ts_transaksi::create([
                'kode_transaksi'      => 'TRX-' . strtoupper(Str::random(5)) . now()->format('His'),
                'id_outlet'           => $user->id_outlet,
                'id_customer'         => $request->id_customer,
                'tanggal_transaksi'   => now(),
                'estimasi_selesai'    => $estimasiSelesai,
                'total_transaksi'     => $request->total,
                'total_diskon'        => $request->total_diskon ?? 0,
                'jumlah_bayar'        => $request->jumlah_bayar ?? 0,
                'metode_pembayaran'   => $request->metode_pembayaran,
                'status_pembayaran'   => $status_pembayaran,
                'tgl_pelunasan'       => $tanggal_pelunasan,
                'status_transaksi'    => $request->status_transaksi,
                'pic'                 => $user->id,
            ]);

            foreach ($request->items as $item) {
                // SINKRONISASI: Gunakan 'id_item' sesuai kiriman JavaScript
                if (!isset($item['id_item'], $item['qty'], $item['harga'])) {
                    throw new \Exception('Format item tidak valid (id_item missing)');
                }

                $itemId = $item['id_item'];
                $qty = ($item['jenis'] === 'Produk') ? (int) ceil($item['qty']) : (float) $item['qty'];
                $nama_item = '-';

                if ($item['jenis'] === 'Produk') {
                    // LOCK stok agar tidak terjadi overlap pembelian di milidetik yang sama
                    $stok = stok_outlet::where('id', $itemId)->lockForUpdate()->first();
                    
                    if (!$stok) throw new \Exception('Produk tidak ditemukan di stok outlet');
                    if ($stok->stok < $qty) throw new \Exception("Stok produk tidak cukup (Sisa: $stok->stok)");

                    $produk = tm_produk::find($stok->id_produk);
                    $nama_item = $produk->nama_produk ?? 'Produk';

                    // Update Stok
                    $stok->decrement('stok', $qty);

                    // Log Stok
                    stock_logs::create([
                        'id_outlet'   => $user->id_outlet,
                        'id_produk'   => $stok->id_produk,
                        'tipe'        => 'keluar',
                        'jumlah'      => $qty,
                        'keterangan'  => 'Terjual ('.$transaksi->kode_transaksi.')',
                        'pic'         => $user->id,
                    ]);

                } else {
                    $layanan = tm_layanan::find($itemId);
                    if (!$layanan) throw new \Exception('Layanan tidak ditemukan');
                    $nama_item = $layanan->nama_layanan;
                }
                // HITUNG DISKON DISINI
                $hargaGross = $item['harga'] * $item['qty'];
                $nominalDiskon = $hargaGross - $item['subtotal'];
                $totalDiskonHitung += $nominalDiskon;

                // Simpan Detail
                ts_transaksi_detail::create([
                    'id_transaksi' => $transaksi->id,
                    'jenis'        => $item['jenis'],
                    'nama_produk'  => $nama_item,
                    'qty'          => $qty,
                    'harga'        => $item['harga'],
                    'diskon'       => $item['diskon_persen'] ?? 0,
                    'subtotal'     => $item['subtotal'],
                ]);
            }

            DB::commit();

            // 1. Ambil data lengkap beserta relasi customer
            $transaksiLengkap = ts_transaksi::with('customer')->find($transaksi->id);

            // 2. Ambil detail transaksi (sudah benar di kode Anda)
            $details = ts_transaksi_detail::where('id_transaksi', $transaksi->id)->get();
            
            // 3. RETURN JSON (Tambahkan field pendukung nota di sini)
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'data' => [
                    'id_transaksi'      => $transaksi->id,
                    'kode_transaksi'    => $transaksi->kode_transaksi,
                    'tanggal'           => $transaksi->created_at->format('d/m/Y H:i'),
                    
                    // Sesuaikan 'nama_customer' dengan kolom di tabel customer Anda
                    'customer'          => $transaksiLengkap->customer->nama_customer ?? 'Umum', 
                    'customer_telp'     => $transaksiLengkap->customer->telepon ?? '-',
                    
                    'estimasi_selesai'  => \Carbon\Carbon::parse($transaksi->estimasi_selesai)->format('d/m/Y H:i'),
                    'metode_pembayaran' => $transaksi->metode_pembayaran,
                    'status_pembayaran' => $transaksi->status_pembayaran,
                    
                    'total'             => $transaksi->total_transaksi,
                    'total_diskon'      => $totalDiskonHitung,
                    'bayar'             => $transaksi->jumlah_bayar,
                    'kembali'           => $transaksi->jumlah_bayar - $transaksi->total_transaksi,
                    
                    'items'             => $details 
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    public function nota($id)
    {
        $user = Auth::User();
        $transaksi = ts_transaksi::findOrFail($id);
        $details = ts_transaksi_detail::where('id_transaksi',$id)->get();
        return view('transaksi.nota', compact('transaksi','details','user'));
    }

    public function data(){
        $user = Auth::user();
        if($user->role == 'Admin'){
            $transaksi = ts_transaksi::where('id_outlet',$user->id_outlet)
                                    ->orderBy('created_at','desc')->get();
        }elseif($user->role == 'Owner'){
            $transaksi = ts_transaksi::orderBy('created_at','desc')->get();
        }else{
            $transaksi = ts_transaksi::where('id_outlet',$user->id_outlet)
                                    ->where('pic',$user->id)
                                    ->orderBy('created_at','desc')->get();
        }

        $outlet = tm_outlet::all();

        return view('Transaksi.data', compact('user','transaksi','outlet'));
    }

    public function detail($id)
    {
        $user = Auth::user();
        $transaksi = ts_transaksi::findOrFail($id);
        $details = ts_transaksi_detail::where('id_transaksi', $id)->get();
        $total = $details->sum('subtotal'); // <- ini cara yang benar

        return view('Transaksi.detail', compact('user', 'transaksi', 'details', 'total'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_transaksi' => 'required|string',
            'status_pembayaran' => 'required|string',
            'metode_pembayaran' => 'required|string',
        ]);
        if ($request->status_pembayaran == 'Lunas') {
            $tanggal_pelunasan = now();
        } else {
            $tanggal_pelunasan = null;
        }
        $transaksi = ts_transaksi::findOrFail($id);
        $transaksi->status_transaksi = $request->status_transaksi;
        $transaksi->status_pembayaran = $request->status_pembayaran;
        $transaksi->tgl_pelunasan = $tanggal_pelunasan;
        $transaksi->metode_pembayaran = $request->metode_pembayaran;
        $transaksi->save();

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui!');
    }

    public function request_delete(Request $request,$id){
        $user = Auth::User();
        $transaksi = ts_transaksi::findOrFail($id);
        $transaksi->alasan = $request->alasan .' - ' . $user->name;
        $transaksi->status_transaksi = 'Request Dihapus';
        $transaksi->save();
        return redirect()->back()->with('success', 'Transaksi Telah Diajukan untuk dihapus!');
    }

    public function data_delete(){
        $user = Auth::User();
        $transaksi = ts_transaksi::where('status_transaksi','Request Dihapus')->get();

        return view('Transaksi.delete',compact('user','transaksi'));
    }

    public function delete($id){
        $transaksi = ts_transaksi::findOrFail($id);

        // Hapus relasi detail
        $transaksi->status_transaksi = 'Cancelled';

        // Hapus transaksi
        $transaksi->update();

        return redirect()->back()->with('success', 'Transaksi Telah Dihapus/Dicancel!, Transaksi sudah tidak tersedia');
    }

    public function reject($id)
    {
        $transaksi = ts_transaksi::findOrFail($id);
        $transaksi->status_transaksi = 'Selesai';
        $transaksi->save();

        return redirect()->back()->with('success', 'Request hapus ditolak. Status diubah menjadi Selesai.');
    }
}
