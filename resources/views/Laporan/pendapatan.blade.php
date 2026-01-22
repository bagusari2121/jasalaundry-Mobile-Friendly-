@extends('layouts.main')

@section('title', 'Laporan Keuangan')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<style>
    .row-cancelled { text-decoration: line-through; color: #adb5bd; background-color: #f8f9fa !important; }
    .table-sm td, .table-sm th { font-size: 0.85rem; }
    .card-header { font-weight: bold; }
    
    @media print {
        .navbar, .sidebar, .btn, #resetFilter, .footer, .btn-group, .card-header button { display: none !important; }
        .container-fluid { width: 100% !important; padding: 0 !important; }
        .row { display: flex !important; flex-direction: row !important; }
        .col-xl-6 { width: 50% !important; float: left !important; }
        .d-print-block { display: block !important; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    {{-- Header Khusus Print --}}
    <div class="d-none d-print-block text-center mb-4">
        <h2>LAPORAN KEUANGAN JASA LAUNDRY</h2>
        <h5 id="printSubTitle">Periode: Semua | Outlet: Semua</h5>
        <hr>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
        <h1 class="h3 text-gray-800">Laporan Keuangan Bersih</h1>
        <div class="btn-group">
            <button type="button" class="btn btn-success btn-sm" id="btnExportExcel">
                <i class="fas fa-file-excel mr-1"></i> Excel
            </button>
            <button type="button" class="btn btn-danger btn-sm" id="btnExportPDF">
                <i class="fas fa-file-pdf mr-1"></i> PDF (Print)
            </button>
            <button id="resetFilter" class="btn btn-secondary btn-sm">
                <i class="fas fa-undo mr-1"></i> Reset
            </button>
        </div>
    </div>

    {{-- 1. WIDGET SUMMARY CARD --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Pendapatan (In)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalPendapatanCard">Rp 0</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-download fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pengeluaran (Out)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalPengeluaranCard">Rp 0</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-upload fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Profit Bersih</div>
                            <div class="h5 mb-0 font-weight-bold text-success" id="totalProfitCard">Rp 0</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-wallet fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. FILTER GLOBAL --}}
    <div class="card shadow mb-4 d-print-none">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label>Outlet</label>
                    <select id="filterOutlet" class="form-control">
                        <option value="">Semua Outlet</option>
                        @foreach ($outlet as $o)
                            <option value="{{ strtolower($o->nama_outlet) }}">{{ $o->nama_outlet }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Rentang Tanggal</label>
                    <div class="d-flex">
                        <input type="date" id="minDate" class="form-control me-2">
                        <input type="date" id="maxDate" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- KOLOM KIRI: PENDAPATAN --}}
        <div class="col-xl-6">
            <div class="card shadow mb-4 border-left-primary">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0">Data Pendapatan</h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="laporanTable" class="table table-bordered table-sm w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Kode</th>
                                    <th>Outlet</th>
                                    <th class="text-center">Status</th> {{-- Kolom Baru --}}
                                    <th>Pembayaran</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporan as $item)
                                @php 
                                    $stTrx = strtolower($item->status_transaksi ?? '');
                                    $isCancelled = ($stTrx == 'cancelled' || $stTrx == 'batal'); 
                                    $pembayaran = strtolower($item->status_pembayaran ?? '');
                                    $belum_lunas = ($pembayaran == 'belum lunas');
                                @endphp
                                <tr class="{{ $isCancelled ? 'row-cancelled' : '' }}">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td data-tanggal="{{ $item->tanggal_transaksi }}">{{ date('d/m/y', strtotime($item->tanggal_transaksi)) }}</td>
                                    <td>{{ $item->kode_transaksi }}</td>
                                    <td data-outlet="{{ strtolower($item->outlet->nama_outlet ?? '') }}">{{ $item->outlet->nama_outlet ?? '-' }}</td>
                                    <td class="text-center">
                                        {{-- Tampilan Badge Status --}}
                                        <span class="badge {{ $isCancelled ? 'bg-danger text-white' : 'bg-success text-white' }}">
                                            {{ ucfirst($item->status_transaksi ?? 'Proses') }}
                                        </span>
                                    </td>
                                    {{-- Data Status untuk hitungan JS --}}
                                    <td class="text-center">
                                        <span class="badge {{ $belum_lunas ? 'bg-danger text-white' : 'bg-success text-white' }}">
                                            {{ ucfirst($item->status_pembayaran) }}
                                        </span>
                                    </td>
                                    <td class="income-cell" data-total="{{ $item->total_transaksi }}" data-status="{{ $isCancelled ? 'batal' : 'aktif' }}">
                                        Rp {{ number_format($item->total_transaksi, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: PENGELUARAN --}}
        <div class="col-xl-6">
            <div class="card shadow mb-4 border-left-danger">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0">Data Pengeluaran</h6>
                </div>
                <div class="card-body p-2">
                    <div class="table-responsive">
                        <table id="pengeluaranTable" class="table table-bordered table-sm w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Outlet</th>
                                    <th class="text-center">Status</th> {{-- Kolom Baru --}}
                                    
                                    <th>Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pengeluaran as $p)
                                @php 
                                    $status = $p->status;
                                    $isBatal = ($status == 'Batal' || $status == 'Dibatalkan'); 
                                @endphp
                                <tr class="{{ $isBatal ? 'row-cancelled' : '' }}">
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td data-tanggal="{{ $p->created_at }}">{{ date('d/m/y', strtotime($p->created_at)) }}</td>
                                    <td>{{ $p->kategori->nama_pengeluaran }}</td>
                                    <td data-outlet="{{ strtolower($p->outlet->nama_outlet ?? '') }}">{{ $p->outlet->nama_outlet ?? '-' }}</td>
                                    <td class="text-center">
                                        {{-- Tampilan Badge Status --}}
                                        @php
                                            $badgeColor = 'bg-success';
                                            if($status == 'Request Batal') $badgeColor = 'bg-warning text-dark';
                                            if($isBatal) $badgeColor = 'bg-danger';
                                        @endphp
                                        <span class="badge {{ $badgeColor }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    {{-- Data Status untuk hitungan JS --}}
                                    <td class="expense-cell" data-nominal="{{ $p->nominal }}" data-status="{{ $isBatal ? 'batal' : 'aktif' }}">
                                        Rp {{ number_format($p->nominal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

<script>
$(document).ready(function() {
    
    const tableConfig = {
        dom: 'Bfrtip',
        buttons: [{ extend: 'excelHtml5', className: 'd-none', exportOptions: { columns: ':visible' } }],
        pageLength: 10,
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' }
    };

    const tableIn = $('#laporanTable').DataTable(tableConfig);
    const tableOut = $('#pengeluaranTable').DataTable(tableConfig);

    function formatRupiah(angka) {
        return 'Rp ' + angka.toLocaleString('id-ID');
    }

    function calculateFinance() {
        let totalIn = 0;
        let totalOut = 0;

        // Hitung Pendapatan
        tableIn.rows({ filter: 'applied' }).every(function() {
            const row = $(this.node());
            const targetCell = row.find('.income-cell');
            if (targetCell.attr('data-status') === 'aktif') {
                totalIn += parseFloat(targetCell.attr('data-total') || 0);
            }
        });

        // Hitung Pengeluaran
        tableOut.rows({ filter: 'applied' }).every(function() {
            const row = $(this.node());
            const targetCell = row.find('.expense-cell');
            if (targetCell.attr('data-status') === 'aktif') {
                totalOut += parseFloat(targetCell.attr('data-nominal') || 0);
            }
        });

        const profit = totalIn - totalOut;
        $('#totalPendapatanCard').text(formatRupiah(totalIn));
        $('#totalPengeluaranCard').text(formatRupiah(totalOut));
        $('#totalProfitCard').text(formatRupiah(profit));

        $('#totalProfitCard').toggleClass('text-danger', profit < 0).toggleClass('text-success', profit >= 0);
    }

    // Filter Logic
    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        const targetOutlet = $('#filterOutlet').val();
        const min = $('#minDate').val();
        const max = $('#maxDate').val();

        const row = $(settings.nTable).DataTable().row(dataIndex).node();
        const rowOutlet = $(row).find('td[data-outlet]').data('outlet');
        const rowTanggal = new Date($(row).find('td[data-tanggal]').data('tanggal'));

        if (targetOutlet && rowOutlet !== targetOutlet) return false;
        
        if (min) {
            const startDate = new Date(min);
            startDate.setHours(0,0,0,0);
            if (rowTanggal < startDate) return false;
        }
        
        if (max) {
            const endDate = new Date(max);
            endDate.setHours(23,59,59,999);
            if (rowTanggal > endDate) return false;
        }

        return true;
    });

    $('#filterOutlet, #minDate, #maxDate').on('change', function() {
        tableIn.draw();
        tableOut.draw();
        
        let outletText = $('#filterOutlet option:selected').text();
        let tgl = ($('#minDate').val() || '...') + ' s/d ' + ($('#maxDate').val() || '...');
        $('#printSubTitle').text('Periode: ' + tgl + ' | Outlet: ' + outletText);

        calculateFinance();
    });

    $('#resetFilter').click(function() {
        $('#filterOutlet, #minDate, #maxDate').val('').trigger('change');
    });

    $('#btnExportExcel').on('click', function() {
        tableIn.button('.buttons-excel').trigger();
        tableOut.button('.buttons-excel').trigger();
    });

    $('#btnExportPDF').on('click', function() { window.print(); });

    // Initial Calculation
    calculateFinance();
});
</script>
@endpush