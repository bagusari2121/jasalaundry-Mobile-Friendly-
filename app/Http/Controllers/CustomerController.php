<?php

namespace App\Http\Controllers;

use App\Models\tm_customer;
use App\Models\ts_deposit;
use App\Models\ts_riwayat_deposit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(){
        $user = Auth::User();
        if($user->role != 'Owner'){
            $customer = tm_customer::where('id_outlet',$user->id_outlet)
                                    ->orderBy('nama_customer','asc')->get();
        }else{
            $customer = tm_customer::orderBy('nama_customer','asc')->get();
        }
        return view('Customer.index',compact('user','customer'));
    }

    public function store(Request $request)
    {
        $user = Auth::User();
        // Validasi input
        $validated = $request->validate([
            'nama_customer' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'is_langganan' => 'nullable'
        ]);

        // Simpan ke database
        tm_customer::create([
            'nama_customer' => $validated['nama_customer'],
            'telepon' => $validated['telepon'],
            'alamat' => $validated['alamat'] ?? null,
            'id_outlet' => $user->id_outlet,
            'is_langganan'  => $request->boolean('is_langganan'),
        ]);

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Customer berhasil ditambahkan!');
    }

    // Fungsi untuk mengupdate data customer
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_customer' => 'required|string|max:100',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'is_langganan' => 'nullable'
        ]);

        $customer = tm_customer::findOrFail($id);

        $customer->update([
            'nama_customer' => $request->nama_customer,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'is_langganan' => $request->boolean('is_langganan'),
        ]);

        return redirect()->back()->with('success', 'Data customer berhasil diperbarui!');
    }

    public function deposit(){
        $user = Auth::user();
        // Mengambil data transaksi deposit dan relasi customer-nya
        // Sesuaikan 'Owner' dengan nilai role yang ada di database kamu
        $isOwner = ($user->role === 'Owner'); 

        // 1. Query Deposit
        $deposit = ts_deposit::with(['customer.outlet']) // Load relasi bersarang (nested)
                                ->when(!$isOwner, function ($query) use ($user) {
                                    // Filter tetap lewat whereHas karena kolom id_outlet ada di tm_customer
                                    return $query->whereHas('customer', function($q) use ($user) {
                                        $q->where('id_outlet', $user->id_outlet);
                                    });
                                })
                                ->orderBy('created_at', 'desc')
                                ->get();
            
        // 2. Query List Customer Langganan
        $customer = tm_customer::where('is_langganan', '1')
            ->when(!$isOwner, function ($query) use ($user) {
                // Jika bukan owner, hanya ambil customer yang satu outlet dengan user
                return $query->where('id_outlet', $user->id_outlet);
            })
            ->orderBy('nama_customer', 'asc')
            ->get();

        return view('Customer.deposit', compact('user', 'deposit', 'customer'));
    }

    public function storeDeposit(Request $request)
    {
        $request->validate([
            'id_customer' => 'required',
            'nominal'     => 'required|numeric'
        ]);
        
        try {
            DB::beginTransaction();

            $deposit = ts_deposit::where('id_customer', $request->id_customer)->first();

            if ($deposit) {
                // Update saldo yang sudah ada
                $deposit->update([
                    'saldo' => $deposit->saldo + $request->nominal
                ]);
            } else {
                // Buat record deposit baru jika belum ada
                $deposit = ts_deposit::create([
                    'id_customer' => $request->id_customer,
                    'saldo'       => $request->nominal
                ]);
            }

            // Simpan catatan ke Riwayat Deposit
            ts_riwayat_deposit::create([
                'id_customer' => $request->id_customer,
                'nominal'     => $request->nominal,
                'saldo_akhir' => $deposit->saldo, // Saldo setelah transaksi
                'keterangan'  => 'Top Up Deposit', // Atau bisa ambil dari input $request->keterangan
                'id_user'     => auth()->id(),    // Mencatat siapa petugas yang menginput
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Deposit berhasil diproses!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal memproses deposit: ' . $e->getMessage());
        }
    }

    public function updateDeposit(Request $request, $id)
    {
        // 1. Validasi input nominal harus berupa angka
        $request->validate([
            'nominal' => 'required|numeric',
        ]);

        try {
            // 2. Cari data deposit berdasarkan ID
            $deposit = ts_deposit::findOrFail($id);
            // 3. Kalkulasi saldo baru: Saldo saat ini + Nominal input
            // Jika nominal bernilai negatif (misal -5000), maka saldo akan otomatis berkurang
            $saldoBaru = $deposit->saldo + $request->nominal;
            // 5. Catat riwayat ke tabel ts_riwayat_deposit
            ts_riwayat_deposit::create([
                'id_customer' => $deposit->id_customer,
                'nominal'     => $request->nominal,
                'saldo_akhir' => $saldoBaru, // Saldo setelah transaksi
                'keterangan'  => $request->keterangan ?? ($request->nominal > 0 ? 'Tambah Saldo' : 'Pengurangan Saldo'),
                'id_user'     => auth()->id(),    // Mencatat siapa petugas yang menginput
            ]);

            // Jika semua berhasil, simpan permanen
            DB::commit();
            // 4. Update kolom 'saldo' di database
            $deposit->update([
                'saldo' => $saldoBaru
            ]);

            // Jika semua berhasil, simpan permanen
            DB::commit();

            return redirect()->back()->with('success', 'Saldo customer berhasil diperbarui!');

        } catch (\Exception $e) {
            // Jika terjadi error (ID tidak ditemukan atau masalah DB)
            return redirect()->back()->with('error', 'Gagal memperbarui saldo: ' . $e->getMessage());
        }
    }

    public function riwayatDeposit()
    {
        $user = Auth::User();
        $isOwner = ($user->role === 'Owner');
        // Mengambil riwayat, urutkan dari yang terbaru
        // Pastikan Model TsRiwayatDeposit sudah punya relasi ke 'deposit'
        $riwayat = ts_riwayat_deposit::with('customer')
                    ->when(!$isOwner, function ($query) use ($user) {
                        return $query->whereHas('customer', function($q) use ($user) {
                                        $q->where('id_outlet', $user->id_outlet);
                                    });
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
        return view('customer.riwayat_deposit', compact('riwayat','user'));
    }

}
