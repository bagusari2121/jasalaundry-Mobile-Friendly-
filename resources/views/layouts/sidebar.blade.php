<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
    <div class="sidebar-brand-icon">
        <img src="{{ asset('img/logo.png') }}" alt="" width="50px">
    </div>
    <div class="sidebar-brand-text mx-3">Jasa Laundry</div>
</a>

<hr class="sidebar-divider my-0">

<li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
    <a class="nav-link" href="/">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>
</li>

<!-- Menu Laporan -->
@if($user->role == "Admin" || $user->role == "Owner")
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan"
        aria-expanded="true" aria-controls="collapseLaporan">
        <i class="fas fa-fw fa-file-alt"></i>
        <span>Laporan</span>
    </a>
    <div id="collapseLaporan" class="collapse" aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="/laporan_pendapatan">Laporan Pendapatan</a>
            <a class="collapse-item" href="/laporan/customer">Laporan Customer</a>
            <a class="collapse-item" href="/laporan/produk">Laporan Produk</a>
            <a class="collapse-item" href="/laporan/layanan">Laporan Layanan</a>
        </div>
    </div>
</li>
@endif
<hr class="sidebar-divider">

<div class="sidebar-heading">Menu</div>

<li class="nav-item {{ Request::is('transaksi') ? 'active' : '' }}">
    <a class="nav-link" href="/transaksi">
        <i class="fas fa-fw fa-print"></i>
        <span>Buat Transaksi</span>
    </a>
</li>

<li class="nav-item {{ Request::is('data-transaksi') ? 'active' : '' }}">
    <a class="nav-link" href="/data-transaksi">
        <i class="fas fa-fw fa-pen"></i>
        <span>Data Transaksi</span>
    </a>
</li>

<li class="nav-item {{ Request::is('piutang') ? 'active' : '' }}">
    <a class="nav-link" href="{{route('piutang')}}">
        <i class="fas fa-fw fa-money-bill-wave"></i>
        <span>Data Piutang</span>
        @php
            $countPiutang = \App\Models\ts_transaksi::where('status_pembayaran', '!=', 'Lunas')->count();
        @endphp
        @if($countPiutang > 0)
            <span class="badge badge-danger badge-counter">{{ $countPiutang }}</span>
        @endif
    </a>
</li>

<li class="nav-item {{ Request::is('transaksi/request_delete') ? 'active' : '' }}">
    <a class="nav-link" href="/transaksi/request_delete">
        <i class="fas fa-fw fa-trash"></i>
        <span>Request Delete Transaksi</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePengeluaran"
        aria-expanded="true" aria-controls="collapsePengeluaran">
        <i class="fas fa-fw fa-clipboard-list"></i>
        <span>Data Pengeluaran</span>
    </a>
    <div id="collapsePengeluaran" class="collapse" aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{route('pengeluaran.index')}}">Data Pengeluaran</a>
            @if($user->role == 'Admin')
            <a class="collapse-item" href="{{route('kategori-pengeluaran.index')}}">Kategori Pengeluaran</a>
            <a class="collapse-item" href="{{route('pengeluaran.dataCancel')}}">Pembatalan Pengeluaran</a>
            @endif
            
        </div>
    </div>
</li>

@if($user->role == "Owner" || $user->role == "Admin")
<li class="nav-item {{ Request::is('outlet') ? 'active' : '' }}">
    <a class="nav-link" href="/outlet">
        <i class="fas fa-fw fa-store"></i>
        <span>Data Outlet</span>
    </a>
</li>

<li class="nav-item {{ Request::is('data_user') ? 'active' : '' }}">
    <a class="nav-link" href="/data_user">
        <i class="fas fa-fw fa-user"></i>
        <span>Data User</span>
    </a>
</li>
@endif
<li class="nav-item {{ Request::is('customer') ? 'active' : '' }}">
    <a class="nav-link" href="/customer">
        <i class="fas fa-fw fa-user"></i>
        <span>Data Customer</span>
    </a>
</li>

<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseDeposit"
        aria-expanded="true" aria-controls="collapseDeposit">
        <i class="fas fa-fw fa-money-bill-wave"></i>
        <span>Data Deposit</span>
    </a>
    <div id="collapseDeposit" class="collapse" aria-labelledby="headingLaporan" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="{{route('deposit')}}">Data Deposit Pelanggan</a>
            <a class="collapse-item" href="{{route('deposit.riwayat')}}">Riwayat Deposit Saldo</a>
        </div>
    </div>
</li>

<!-- Menu Produk -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProduk"
        aria-expanded="true" aria-controls="collapseProduk">
        <i class="fas fa-fw fa-box"></i>
        <span>Data Produk</span>
    </a>
    <div id="collapseProduk" class="collapse" aria-labelledby="headingProduk" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            @if($user->role == "Owner" || $user->role == "Admin")
            <a class="collapse-item" href="/data_produk">Master Produk</a>
            @endif
            <a class="collapse-item" href="/stok_outlet">Stok Outlet</a>
            <a class="collapse-item" href="/riwayat_stok">Riwayat Stok Outlet</a>
        </div>
    </div>
</li>
@if($user->role == "Owner" || $user->role == "Admin")
<li class="nav-item {{ Request::is('layanan') ? 'active' : '' }}">
    <a class="nav-link" href="/layanan">
        <i class="fas fa-fw fa-tools"></i>
        <span>Data Layanan</span>
    </a>
</li>
@endif

<hr class="sidebar-divider d-none d-md-block">

<div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>
