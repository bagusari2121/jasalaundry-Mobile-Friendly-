<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class KasirController extends Controller
{

    public function index()
{
    $user = Auth::user();
    $today = Carbon::today();
    $startDate = Carbon::now()->subMonths(2)->startOfMonth();

    // Helper untuk menentukan apakah harus filter berdasarkan outlet
    // Jika role bukan 'Owner', maka filter berdasarkan id_outlet si user
    $isOwner = $user->role === 'Owner'; // Sesuaikan string 'Owner' dengan database kamu

    // --- Pendapatan hari ini ---
    $pendapatan_hari_ini = DB::table('ts_transaksi')
        ->where('status_transaksi', '!=', 'Cancelled')
        ->where('status_pembayaran','Lunas')
        ->whereDate('tgl_pelunasan', $today)
        ->when(!$isOwner, function ($query) use ($user) {
            return $query->where('id_outlet', $user->id_outlet);
        })
        ->sum('total_transaksi');

    // --- Cash hari ini ---
    $pendapatan_cash = DB::table('ts_transaksi')
        ->where('status_transaksi', '!=', 'Cancelled')
        ->whereDate('tgl_pelunasan', $today)
        ->where('status_pembayaran','Lunas')
        ->where('metode_pembayaran', 'Cash')
        ->when(!$isOwner, function ($query) use ($user) {
            return $query->where('id_outlet', $user->id_outlet);
        })
        ->sum('total_transaksi');

    // --- QRIS hari ini ---
    $pendapatan_qris = DB::table('ts_transaksi')
        ->where('status_transaksi', '!=', 'Cancelled')
        ->whereDate('tgl_pelunasan', $today)
        ->where('status_pembayaran','Lunas')
        ->where('metode_pembayaran', 'Qris')
        ->when(!$isOwner, function ($query) use ($user) {
            return $query->where('id_outlet', $user->id_outlet);
        })
        ->sum('total_transaksi');

    // --- Pengeluaran hari ini ---
    $pengeluaran_today = DB::table('ts_pengeluaran')
        ->where('status', 'Aktif')
        ->whereDate('tanggal', $today)
        ->when(!$isOwner, function ($query) use ($user) {
            return $query->where('outlet_id', $user->id_outlet);
        })
        ->sum('nominal');

    $pengeluaran_cash = DB::table('ts_pengeluaran')
        ->where('status', 'Aktif')
        ->where('metode_pembayaran', 'Cash')
        ->whereDate('tanggal', $today)
        ->when(!$isOwner, function ($query) use ($user) {
            return $query->where('outlet_id', $user->id_outlet);
        })
        ->sum('nominal');

    $pengeluaran_qris = DB::table('ts_pengeluaran')
        ->where('status', 'Aktif')
        ->where('metode_pembayaran', 'Qris')
        ->whereDate('tanggal', $today)
        ->when(!$isOwner, function ($query) use ($user) {
            return $query->where('outlet_id', $user->id_outlet);
        })
        ->sum('nominal');

    // --- Ambil Data Pendapatan (Grafik) ---
    $data_pendapatan = DB::table('ts_transaksi')
        ->select(
            DB::raw('MONTH(tanggal_transaksi) as bulan'),
            DB::raw('YEAR(tanggal_transaksi) as tahun'),
            DB::raw('SUM(total_transaksi) as total')
        )
        ->where('tanggal_transaksi', '>=', $startDate)
        ->where('status_transaksi', '!=', 'Cancelled')
        ->where('status_pembayaran','Lunas')
        ->when(!$isOwner, function ($query) use ($user) {
            return $query->where('id_outlet', $user->id_outlet);
        })
        ->groupBy('tahun', 'bulan')
        ->orderBy('tahun')
        ->orderBy('bulan')
        ->get();

    // --- Ambil Data Pengeluaran (Grafik) ---
    $data_pengeluaran = DB::table('ts_pengeluaran')
        ->select(
            DB::raw('MONTH(tanggal) as bulan'),
            DB::raw('YEAR(tanggal) as tahun'),
            DB::raw('SUM(nominal) as total')
        )
        ->where('tanggal', '>=', $startDate)
        ->where('status', '!=', 'Batal')
        ->when(!$isOwner, function ($query) use ($user) {
            return $query->where('outlet_id', $user->id_outlet);
        })
        ->groupBy('tahun', 'bulan')
        ->get();

    // --- Gabungkan Data untuk Chart.js ---
    $bulan = [];
    $total_pendapatan = [];
    $total_bersih = [];

    foreach ($data_pendapatan as $p) {
        $pengeluaran = $data_pengeluaran->where('bulan', $p->bulan)->where('tahun', $p->tahun)->first();
        $nominal_pengeluaran = $pengeluaran ? $pengeluaran->total : 0;

        $bulan[] = Carbon::create($p->tahun, $p->bulan)->translatedFormat('F');
        $total_pendapatan[] = $p->total;
        $total_bersih[] = $p->total - $nominal_pengeluaran;
    }

    return view('Kasir.index', compact(
        'user',
        'pendapatan_hari_ini',
        'pendapatan_cash',
        'pendapatan_qris',
        'bulan',
        'total_pendapatan',
        'pengeluaran_today',
        'pengeluaran_cash',
        'pengeluaran_qris',
        'total_bersih'
    ));
}

    public function profile(){
        $user = Auth::User();

        return view('Kasir.profile',compact('user'));
    }

    // app/Http/Controllers/ProfileController.php
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return back()->withErrors(['old_password' => 'Password lama salah']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }
}
