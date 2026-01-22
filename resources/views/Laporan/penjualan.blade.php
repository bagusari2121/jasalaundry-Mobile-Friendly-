@extends('layouts.main')

@section('title', 'Laporan Penjualan')

@section('content')
@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css" rel="stylesheet">
@endpush

<h1 class="h3 mb-4 text-gray-800">Laporan Penjualan</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Laporan Penjualan</h6>
    </div>
    <div class="card-body">

        {{-- 🔍 FILTER SECTION --}}
        <div class="row mb-3">
            <div class="col-md-3">
                <select id="filterOutlet" class="form-control">
                    <option value="">Semua Outlet</option>
                    @foreach($outlet as $o)
                        <option value="{{ $o->nama_outlet }}">{{ $o->nama_outlet }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select id="filterPembayaran" class="form-control">
                    <option value="">Semua Pembayaran</option>
                    <option value="Cash">Cash</option>
                    <option value="QRIS">QRIS</option>
                    <option value="Lain-lain">Lain-lain</option>
                </select>
            </div>

            <div class="col-md-3">
                <select id="filterStatus" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="Lunas">Lunas</option>
                    <option value="Belum Lunas">Belum Lunas</option>
                </select>
            </div>

            <div class="col-md-3">
                <div class="d-flex">
                    <input type="date" id="minDate" class="form-control mr-2" placeholder="Dari tanggal">
                    <input type="date" id="maxDate" class="form-control" placeholder="Sampai tanggal">
                </div>
            </div>
        </div>

        {{-- 🔽 TABEL DATA --}}
        <div class="table-responsive">
            <table class="table table-bordered" id="laporanTable" width="100%" cellspacing="0">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kode Transaksi</th>
                        <th>Customer</th>
                        <th>Outlet</th>
                        <th>Metode Pembayaran</th>
                        <th>Status Pembayaran</th>
                        <th>Total Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporan as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td data-tanggal="{{ $item->tanggal_transaksi }}">
                            {{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d-m-Y H:i') }}
                        </td>
                        <td>{{ $item->kode_transaksi }}</td>
                        <td>{{ $item->customer->nama_customer }}</td>
                        <td>{{ $item->outlet->nama_outlet ?? '-' }}</td>
                        <td>{{ ucfirst($item->metode_pembayaran) }}</td>
                        <td>{{ $item->status_pembayaran ?? 'Belum Lunas' }}</td>
                        <td data-total="{{ $item->total_transaksi }}">
                            Rp {{ number_format($item->total_transaksi, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- 💰 Total Pendapatan --}}
            <div class="mt-3">
                <h5>Total Pendapatan: <span id="totalPendapatan" class="text-success font-weight-bold">Rp 0</span></h5>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- DataTables & Export -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {

    // === 1️⃣ Inisialisasi DataTables dengan tombol export ===
    var table = $('#laporanTable').DataTable({
        stateSave: true,
        order: [[1, 'desc']],
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Export Excel',
                className: 'btn btn-success btn-sm mb-3',
                title: 'Laporan Penjualan',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> Export PDF',
                className: 'btn btn-danger btn-sm mb-3 ml-2',
                title: 'Laporan Penjualan',
                orientation: 'portrait',
                pageSize: 'A4',
                exportOptions: {
                    columns: ':visible'
                },
                customize: function(doc) {
                    doc.defaultStyle.fontSize = 10;
                    doc.styles.tableHeader.fontSize = 11;
                    doc.styles.title.fontSize = 14;
                }
            }
        ]
    });

    // === 2️⃣ Filter tanggal (custom search) ===
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var min = $('#minDate').val();
            var max = $('#maxDate').val();
            var tanggalAsli = $(table.row(dataIndex).node()).find('td[data-tanggal]').data('tanggal');
            if (!tanggalAsli) return true;

            var dateObj = new Date(tanggalAsli);
            if ((!min || dateObj >= new Date(min)) && (!max || dateObj <= new Date(max))) {
                return true;
            }
            return false;
        }
    );

    // === 3️⃣ Semua event filter ===
    $('#filterOutlet').on('change', function() {
        table.column(4).search(this.value).draw();
        hitungTotal();
    });

    $('#filterPembayaran').on('change', function() {
        table.column(5).search(this.value).draw();
        hitungTotal();
    });

    $('#filterStatus').on('change', function() {
        table.column(6).search(this.value).draw();
        hitungTotal();
    });

    $('#minDate, #maxDate').on('change', function() {
        table.draw();
        hitungTotal();
    });

    // === 4️⃣ Hitung total pendapatan berdasarkan filter aktif ===
    function hitungTotal() {
        var total = 0;
        table.rows({ filter: 'applied' }).every(function() {
            var nilai = $(this.node()).find('td[data-total]').data('total') || 0;
            total += parseFloat(nilai);
        });
        $('#totalPendapatan').text('Rp ' + total.toLocaleString('id-ID'));
    }

    // Jalankan pertama kali & saat tabel berubah
    hitungTotal();
    table.on('draw', function() {
        hitungTotal();
    });
});
</script>
@endpush
