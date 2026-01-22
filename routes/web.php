<?php

use App\Http\Controllers\KasirController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Output\Output;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware('auth')->group(function () {
    Route::get('/',[KasirController::class,'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
// Login
// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/'); // atau dashboard kamu
    }
    return view('auth.login');
})->name('login');
// Proses Login
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
// Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
// Proses Register
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Profile
Route::get('/profile', [KasirController::class, 'profile'])->name('profile')->middleware('auth');
// Edit Password
Route::post('/profile/update-password', [KasirController::class, 'updatePassword'])->name('profile.updatePassword');



// Outlet
Route::get('/outlet', [OutletController::class,'index'])->name('outlet')->middleware('auth');
// Outlet Store
Route::post('/outlet/store', [OutletController::class,'store'])->name('outlet.store')->middleware('auth');
// Edit page
Route::get('/outlet/edit/{id}', [OutletController::class,'edit'])->name('outlet.edit')->middleware('auth');
// Store Edit
Route::put('/outlet/edit/store/{id}',[OutletController::class, 'update'])->name('outlet.update')->middleware('auth');
// Delete Outlet
Route::delete('/outlet/destroy/{id}', [OutletController::class, 'destroy'])->name('outlet.destroy');

// data User
Route::get('/data_user',[UserController::class, 'index'])->name('user')->middleware('auth');
// store data user
Route::post('/data_user/store', [UserController::class, 'store'])->name('user.store');
// Delete User
Route::delete('/user/delete/{id}', [UserController::class, 'destroy'])->name('user.destroy');
// Update
Route::put('/user/update/{id}', [UserController::class, 'update'])->name('user.update');

// Data Produk
Route::get('/data_produk',[ProdukController::class, 'index'])->name('produk')->middleware('auth');
// Create Produk
Route::post('/data_produk/store', [ProdukController::class, 'store'])->name('produk.store');
// Update Produk
Route::put('/data_produk/update/{id}', [ProdukController::class, 'update'])->name('produk.update');
// Delete Produk
Route::get('/data_produk/delete/{id}', [ProdukController::class, 'delete'])->name('produk.delete');

// Stok Outlet
Route::get('/stok_outlet', [ProdukController::class,'stok_outlet'])->name('stok.outlet')->middleware('auth');
// Store Stok
Route::post('/stok_outlet/store',[ProdukController::class,'stok_outlet_store'])->name('stok.outlet.store');
// Edit Stok
Route::put('/stok_outlet/update/{id}', [ProdukController::class, 'stok_outlet_update'])->name('stok_outlet.update');
// Riwayat Stok
Route::get('/riwayat_stok', [ProdukController::class,'riwayat'])->name('stok.riwayat')->middleware('auth');

// Layanan
Route::get('/layanan',[LayananController::class, 'index'])->name('layanan')->middleware('auth');
// Layanan Store
Route::post('/layanan/store', [LayananController::class, 'store'])->name('layanan.store');
// Edit Layanan
Route::put('/layanan/update/{id}', [LayananController::class, 'update'])->name('layanan.update');

// Customer
Route::get('/customer',[CustomerController::class, 'index'])->name('customer')->middleware('auth');
// Customer Store
Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
// Customer Edit
Route::put('/customer/update/{id}', [CustomerController::class, 'update'])->name('customer.update');

// Halaman Transaksi
Route::get('/transaksi',[TransaksiController::class, 'index'])->name('index')->middleware('auth');
// Store
Route::post('/transaksi/store', [TransaksiController::class, 'store'])->name('transaksi.store');
// Nota
Route::get('/transaksi/nota/{id}', [TransaksiController::class, 'nota'])->name('transaksi.nota');

// Data Transaksi
Route::get('/data-transaksi', [TransaksiController::class, 'data'])->name('transaksi.data')->middleware('auth');
// Detail Transaksi
Route::get('/detail-transaksi/{id}', [TransaksiController::class, 'detail'])->name('transaksi.detail')->middleware('auth');
// Ganti Status
Route::put('/transaksi/{id}/status', [TransaksiController::class, 'updateStatus'])->name('transaksi.updateStatus');
// Request Delete
Route::put('/transaksi/{id}/request', [TransaksiController::class, 'request_delete'])->name('transaksi.request_delete');
// data Request delete
Route::get('/transaksi/request_delete', [TransaksiController::class, 'data_delete'])->name('transaksi.data_delete')->middleware('auth');
// Data Delete
Route::delete('/transaksi/delete/{id}', [TransaksiController::class, 'delete'])->name('transaksi.destroy');
// Gagal
Route::put('/transaksi/reject/{id}', [TransaksiController::class, 'reject'])->name('transaksi.reject');


// Laporan Penjualan
Route::get('/laporan_penjualan', [LaporanController::class, 'penjualan'])->name('laporan.penjualan')->middleware('auth');
// Cetak PDF
Route::get('/laporan/penjualan/cetak', [LaporanController::class, 'cetak'])->name('laporan.penjualan.cetak');
// laporan Pendapatan
Route::get('/laporan_pendapatan', [LaporanController::class, 'pendapatan'])->name('laporan.pendapatan')->middleware('auth');
// Laporan Harian
Route::get('/laporan/harian', [LaporanController::class, 'harian'])->name('laporan.pendapatan')->middleware('auth');
// Laporan Bulanan
Route::get('/laporan-bulanan', [LaporanController::class, 'laporanBulanan'])->name('laporan.bulanan')->middleware('auth');

Route::get('/laporan-bulanan/data', [LaporanController::class, 'getData'])->name('laporan.bulanan.data');
// Laporan Customer
Route::get('/laporan/customer', [LaporanController::class, 'laporanCustomer'])->name('laporan.customer')->middleware('auth');
// Laporan Produk
Route::get('/laporan/produk', [LaporanController::class, 'laporanProduk'])->name('laporan.produk')->middleware('auth');
// Laporan Produk
Route::get('/laporan/layanan', [LaporanController::class, 'laporanLayanan'])->name('laporan.layanan')->middleware('auth');

Route::middleware(['auth'])->group(function () {
// Data Pengeluaran
    Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
    // Store Pengeluaran
    Route::post('/pengeluaran/store', [PengeluaranController::class, 'store_pengeluaran'])
            ->name('pengeluaran.store');
    // Update Pengeluaran
    Route::put('/pengeluaran/edit/{id}', [PengeluaranController::class, 'update_pengeluaran'])
    ->name('pengeluaran.update');
    Route::put('/pengeluaran/cancel/{id}', [PengeluaranController::class, 'cancel'])->name('pengeluaran.cancel');
        
    Route::get('/pengeluaran/cancel', [PengeluaranController::class, 'dataCancel'])->name('pengeluaran.dataCancel');

    Route::put('/pengeluaran/acc-cancel/{id}', [PengeluaranController::class, 'accCancel'])->name('pengeluaran.acc_pembatalan');

    Route::put('/pengeluaran/total-cancel/{id}', [PengeluaranController::class, 'tolakCancel'])->name('pengeluaran.tolakCancel');
});

// Pengeluaran
Route::middleware(['auth'])->group(function () {
    Route::get('kategori-pengeluaran', [PengeluaranController::class, 'kategori'])
        ->name('kategori-pengeluaran.index');

    Route::post('kategori-pengeluaran/store', [PengeluaranController::class, 'store_kategori'])
        ->name('kategori-pengeluaran.store');

    Route::put('kategori-pengeluaran/edit/{id}', [PengeluaranController::class, 'update_kategori'])
        ->name('kategori-pengeluaran.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('deposit', [CustomerController::class, 'deposit'])
    ->name('deposit');
    // Pastikan ada ->name('deposit.store') di ujungnya
    Route::post('/deposit/store', [CustomerController::class, 'storeDeposit'])->name('deposit.store');
    // Sesuaikan dengan URL yang Anda set di JavaScript ( /deposit/update/{id} )
    Route::put('/deposit/update/{id}', [CustomerController::class, 'updateDeposit'])->name('deposit.update');
    Route::get('/deposit/riwayat', [CustomerController::class, 'riwayatDeposit'])->name('deposit.riwayat');
});

Route::middleware(['auth'])->group(function() {
    Route::get('/piutang',[PiutangController::class, 'index'])->name('piutang'); 
   // Gunakan POST untuk aksi yang merubah data di database
    Route::post('/bayar-transaksi/{id}', [PiutangController::class, 'bayar'])->name('transaksi.bayar');
    Route::post('/bulk-bayar-transaksi', [PiutangController::class, 'bulkBayar'])->name('transaksi.bulkBayar');
});



