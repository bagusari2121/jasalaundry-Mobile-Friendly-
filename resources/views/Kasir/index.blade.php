@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <h3>Selamat datang, {{ $user->name }} 🎉</h3>
        </div>
    </div>

    {{-- Kartu ringkasan pendapatan hari ini --}}
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pendapatan Hari Ini</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($pendapatan_hari_ini, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pembayaran Cash</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($pendapatan_cash, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Pembayaran QRIS</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($pendapatan_qris, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Baris Pengeluaran -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Pengeluaran Hari Ini</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($pengeluaran_today, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Pengeluaran Cash</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($pengeluaran_cash, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Pengeluaran QRIS</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($pengeluaran_qris, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grafik pendapatan 3 bulan terakhir --}}
    {{-- Grafik Keuntungan Bersih (Profit) --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Grafik Keuntungan Bersih (Profit) 3 Bulan Terakhir</h6>
        </div>
        <div class="card-body">
            <canvas id="pendapatanChart" height="75"></canvas>
        </div>
    </div>
@endsection

@section('scripts')
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('pendapatanChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($bulan) !!},
            datasets: [{
                label: 'Total Pendapatan',
                data: {!! json_encode($total_pendapatan) !!},
                backgroundColor: [
                    'rgba(78, 115, 223, 0.7)',
                    'rgba(28, 200, 138, 0.7)',
                    'rgba(54, 185, 204, 0.7)'
                ],
                borderColor: [
                    'rgba(78, 115, 223, 1)',
                    'rgba(28, 200, 138, 1)',
                    'rgba(54, 185, 204, 1)'
                ],
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                    }
                }
            }
        }
    });
</script> -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('pendapatanChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($bulan) !!},
            datasets: [{
                label: 'Profit Bersih',
                // Pastikan variabel di Controller namanya $total_bersih
                data: {!! json_encode($total_bersih) !!}, 
                backgroundColor: [
                    'rgba(78, 115, 223, 0.8)', // Biru
                    'rgba(28, 200, 138, 0.8)', // Hijau
                    'rgba(54, 185, 204, 0.8)'  // Cyan
                ],
                borderColor: [
                    'rgba(78, 115, 223, 1)',
                    'rgba(28, 200, 138, 1)',
                    'rgba(54, 185, 204, 1)'
                ],
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (context) => {
                            // Menampilkan format Rupiah saat kursor diarahkan ke grafik
                            return 'Profit: Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value)
                    }
                }
            }
        }
    });
</script>
@endsection
