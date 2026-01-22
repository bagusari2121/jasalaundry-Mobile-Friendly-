<?php

namespace App\Http\Controllers;

use App\Models\tm_customer;
use Illuminate\Http\Request;
use App\Models\ts_transaksi;
use App\Models\tm_outlet;
use App\Models\ts_transaksi_detail;
use App\Models\ts_pengeluaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class LaporanController extends Controller
{
    public function penjualan()
    {
        $user = Auth::User();
        $laporan = ts_transaksi::with(['customer', 'outlet'])
                    ->latest()->get();

        $outlet = tm_outlet::all();

        return view('laporan.penjualan', compact('laporan', 'outlet','user'));
    }

    public function pendapatan() {
        $user = Auth::user(); //
        $isOwner = ($user->role === 'Owner');
        // Ambil data Pendapatan (Transaksi)
        $laporan = ts_transaksi::with(['customer', 'outlet'])
                                ->when(!$isOwner, function ($query) use ($user) {
                                    // Gunakan where biasa, bukan whereHas
                                    return $query->where('id_outlet', $user->id_outlet);
                                })
                                ->where('status_pembayaran', 'Lunas')
                                ->latest()
                                ->get();
        
        // Ambil data Pengeluaran (New)
        $pengeluaran = ts_pengeluaran::with(['kategori', 'outlet'])
                                ->when(!$isOwner, function ($query) use ($user) {
                                    // Gunakan where biasa, bukan whereHas
                                    return $query->where('outlet_id', $user->id_outlet);
                                })
                                ->latest()
                                ->get(); //
        
        // Ambil data Master Outlet untuk filter
        $outlet = tm_outlet::when(!$isOwner, function ($query) use ($user) {
                                    // Gunakan where biasa, bukan whereHas
                                    return $query->where('id', $user->id_outlet);
                                })->get(); //

        // Kirim semua variabel ke view
        return view('laporan.pendapatan', compact('laporan', 'pengeluaran', 'outlet', 'user')); //
    }

    public function harian(){
        // Ambil bulan & tahun dari filter
        $bulan = $request->bulan ?? date('n');
        $tahun = $request->tahun ?? date('Y');

        // Ambil semua transaksi dalam bulan & tahun tersebut
        $transaksi = ts_transaksi::whereMonth('tanggal_transaksi', $bulan)
            ->whereYear('tanggal_transaksi', $tahun)
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->tanggal)->format('Y-m-d');
            });

        // Format data agar mudah dipakai di JS
        $laporan = [];
        $no = 1;
        foreach ($transaksi as $tanggal => $data) {
            $totalQris = $data->where('metode_pembayaran', 'QRIS')->sum('total_harga');
            $totalCash = $data->where('metode_pembayaran', 'Cash')->sum('total_harga');
            $totalAll = $data->sum('total_transaksi');

            $laporan[] = [
                'no' => $no++,
                'tanggal_transaksi' => Carbon::parse($tanggal)->format('d-m-Y'),
                'qris' => $totalQris,
                'cash' => $totalCash,
                'total' => $totalAll
            ];
        }

        return response()->json($laporan);
    }

    public function laporanBulanan()
    {
        $user = Auth::User();
        return view('laporan.bulanan',compact('user'));
    }

    public function getData(Request $request)
    {
        $user = Auth::User();
        $tahun = $request->tahun ?? date('Y');

        // Contoh: ambil dari tabel transaksi
        // Nanti ubah sesuai tabel kamu, misal 'transaksis'
        $data = DB::table('transaksis')
            ->selectRaw("
                MONTH(tanggal) as bulan,
                SUM(CASE WHEN metode_pembayaran = 'qris' THEN total ELSE 0 END) as qris,
                SUM(CASE WHEN metode_pembayaran = 'cash' THEN total ELSE 0 END) as cash,
                SUM(total) as total
            ")
            ->whereYear('tanggal', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Bikin daftar semua bulan
        $bulanList = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        // Samakan agar semua bulan tampil (termasuk yang 0)
        $result = [];
        foreach ($bulanList as $i => $b) {
            $found = $data->firstWhere('bulan', $i);
            $result[] = [
                'no' => $i,
                'bulan' => $b,
                'qris' => $found->qris ?? 0,
                'cash' => $found->cash ?? 0,
                'total' => $found->total ?? 0,
            ];
        }

        return response()->json($result);
    }

    public function laporanCustomer(Request $request)
    {
        $user = Auth::User();
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $outlet = $request->outlet;
        $outletList = tm_outlet::all();
    // dd($outlet);
    // dd($request->all());
        $laporan = DB::table('ts_transaksi as t')
            ->join('tm_customer as c', 't.id_customer', '=', 'c.id')
            ->select(
                'c.nama_customer',
                DB::raw('COUNT(t.id) as jumlah_kunjungan'),
                DB::raw('MONTH(t.tanggal_transaksi) as bulan'),
                DB::raw('YEAR(t.tanggal_transaksi) as tahun')
            )
            ->whereYear('t.tanggal_transaksi', $tahun)
            ->whereMonth('t.tanggal_transaksi', $bulan)
            ->where('t.id_outlet', $outlet)
            ->groupBy('c.id', 'bulan', 'tahun', 'c.nama_customer')
            ->orderByDesc('jumlah_kunjungan')
            ->get();
        $outlet_selected = $outletSelected = tm_outlet::find($outlet);
        return view('laporan.laporan_customer',compact('user','laporan','bulan','tahun','outlet','outletList','outlet_selected'));
    }

    public function laporanProduk(Request $request)
    {
        $user = Auth::User();
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $outlet = $request->outlet ?? ('');
        $outletList = tm_outlet::all();
        $laporan = tm_customer::all();

        $laporan = ts_transaksi_detail::with('transaksi')
                ->where('jenis', 'Produk')
                ->whereHas('transaksi', function($q) use ($tahun, $bulan, $outlet) {
                    $q->whereYear('tanggal_transaksi', $tahun)
                    ->whereMonth('tanggal_transaksi', $bulan)
                    ->where('id_outlet', $outlet);
                })
                ->select('nama_produk', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_penjualan'))
                ->groupBy('nama_produk')
                ->orderByDesc('total_qty')
                ->get();
        return view('laporan.laporan_produk',compact('user','laporan','bulan','tahun','outlet','outletList'));
    }

    public function laporanLayanan(Request $request)
    {
        $user = Auth::User();
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');
        $outlet = $request->outlet ?? ('');
        $outletList = tm_outlet::all();

        $laporan = ts_transaksi_detail::with('transaksi')
                ->where('jenis', 'Layanan')
                ->whereHas('transaksi', function($q) use ($tahun, $bulan, $outlet) {
                    $q->whereYear('tanggal_transaksi', $tahun)
                    ->whereMonth('tanggal_transaksi', $bulan)
                    ->where('id_outlet', $outlet);
                })
                ->select('nama_produk', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_penjualan'))
                ->groupBy('nama_produk')
                ->orderByDesc('total_qty')
                ->get();
        return view('laporan.laporan_layanan',compact('user','laporan','bulan','tahun','outlet','outletList'));
    }
}
