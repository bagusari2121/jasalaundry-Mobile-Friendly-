<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ts_transaksi;
use App\Models\ts_deposit;
use App\Models\tm_outlet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class PiutangController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // 1. Ambil data transaksi yang BELUM LUNAS saja
        $query = ts_transaksi::with(['customer', 'outlet', 'user'])
                            ->where('status_pembayaran', '!=', 'Lunas')
                            ->orderBy('tanggal_transaksi', 'desc');

        // 2. Jika bukan Owner, biasanya hanya bisa melihat piutang di outletnya sendiri
        if ($user->role !== 'Owner') {
            $query->where('id_outlet', $user->id_outlet);
        }

        $transaksi = $query->get();

        // 3. Ambil data outlet untuk dropdown filter di view (khusus Owner)
        $outlet = tm_outlet::all();

        return view('Piutang.index', compact('transaksi', 'outlet', 'user'));
    }

    public function bayar($id)
    {
        $user = Auth::user();

        // 1. Ambil data transaksi
        $transaksi = ts_transaksi::findOrFail($id);

        // 2. Cek apakah customer ini memiliki record di tabel deposit
        $deposit = ts_deposit::where('id_customer', $transaksi->id_customer)->first();

        // Jalankan transaksi database agar aman
        DB::beginTransaction();

        try {
            if (!$deposit) {
                // SKENARIO 1: Tidak ada record deposit (Bayar Tunai Langsung)
                $transaksi->status_pembayaran = 'Lunas';
                $transaksi->tgl_pelunasan = Carbon::now();
                $transaksi->save();

                DB::commit();
                return redirect()->back()->with('success', 'Pelunasan berhasil diproses.');

            } else {
                // SKENARIO 2: Customer punya deposit
                // Cek apakah saldo mencukupi
                if ($deposit->saldo >= $transaksi->total_transaksi) {
                    
                    // A. Kurangi saldo deposit
                    $deposit->saldo -= $transaksi->total_transaksi;
                    $deposit->save();

                    // B. Update status transaksi menjadi lunas
                    $transaksi->status_pembayaran = 'Lunas';
                    $transaksi->tgl_pelunasan = Carbon::now();
                    $transaksi->save();

                    DB::commit();
                    return redirect()->back()->with('success', 'Pembayaran via Saldo Deposit berhasil.');

                } else {
                    // SKENARIO 3: Deposit ada tapi tidak cukup
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Saldo deposit tidak mencukupi untuk melunasi transaksi ini.');
                }
            }
        } catch (\Exception $e) {
            // Jika ada error sistem tak terduga
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function bulkBayar(Request $request)
    {
        $ids = explode(',', $request->ids);
        $successCount = 0;
        $failCount = 0;
        $errors = [];

        foreach ($ids as $id) {
            DB::beginTransaction();
            try {
                $transaksi = ts_transaksi::findOrFail($id);
                $deposit = ts_deposit::where('id_customer', $transaksi->id_customer)->first();

                if (!$deposit) {
                    // Bayar Tunai
                    $transaksi->update(['status_pembayaran' => 'Lunas', 'tgl_pelunasan' => now()]);
                    $successCount++;
                } else {
                    if ($deposit->saldo >= $transaksi->total_transaksi) {
                        // Potong Deposit
                        $deposit->decrement('saldo', $transaksi->total_transaksi);
                        $transaksi->update(['status_pembayaran' => 'Lunas', 'tgl_pelunasan' => now()]);
                        $successCount++;
                    } else {
                        $failCount++;
                        $errors[] = "Transaksi {$transaksi->kode_transaksi} gagal (Saldo kurang)";
                        DB::rollBack();
                        continue; // Skip ke transaksi berikutnya
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $failCount++;
            }
        }

        if ($failCount > 0) {
            return redirect()->back()->with('error', "Berhasil: $successCount, Gagal: $failCount. " . implode(', ', $errors));
        }
        return redirect()->back()->with('success', "Berhasil melunasi $successCount transaksi.");
    }
}
