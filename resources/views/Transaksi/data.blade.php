@extends('layouts.main')

@section('title', 'Data Transaksi Laundry') 

@section('content')
@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

<h1 class="h3 mb-4 text-gray-800">Data Transaksi</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Data Transaksi</h6>
    </div>
    <div class="card-body">

        {{-- 🔍 FILTER SECTION --}}
        <div class="row mb-3">
            <div class="col-md-3">
                <select id="filterStatus" class="form-control">
                    <option value="">Semua Status</option>
                    <option value="Pesanan Masuk">Pesanan Masuk</option>
                    <option value="Proses">Proses</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Diambil">Diambil</option>
                </select>
            </div>
            @if($user->role == 'Owner')
            <div class="col-md-3">
                <select id="filterOutlet" class="form-control">
                    <option value="">Semua Outlet</option>
                    @foreach($outlet as $o)
                        <option value="{{ $o->nama_outlet }}">{{ $o->nama_outlet }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-md-3">
                <input type="date" id="minDate" class="form-control" placeholder="Dari tanggal">
            </div>
            <div class="col-md-3">
                <input type="date" id="maxDate" class="form-control" placeholder="Sampai tanggal">
            </div>
        </div>

        {{-- 🔽 TABEL DATA --}}
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableUser" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th class="text-center">Kode Transaksi</th>
                        <th class="text-center">Customer</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Outlet</th>
                        <th class="text-center">PIC</th>
                        <th class="text-center">Total Transaksi</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Status Pembayaran</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $transaksi)
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td>{{$transaksi->kode_transaksi}}</td>
                        <td>{{$transaksi->customer->nama_customer}}</td>
                        <td>{{$transaksi->tanggal_transaksi}}</td>
                        <td>{{$transaksi->outlet->nama_outlet ?? '-'}}</td>
                        <td>{{$transaksi->user->name ?? '-'}}</td>
                        <td>Rp. {{number_format($transaksi->total_transaksi)}}</td>
                        <td class="text-center">
                            @if($transaksi->status_transaksi == 'Pesanan Masuk')
                                <span class="badge bg-primary px-4 py-2 text-white">{{ $transaksi->status_transaksi }}</span>
                            @elseif($transaksi->status_transaksi == 'Proses')
                                <span class="badge bg-warning text-dark px-4 py-2 text-white">{{ $transaksi->status_transaksi }}</span>
                            @elseif($transaksi->status_transaksi == 'Diambil')
                                <span class="badge bg-danger text-white px-4 py-2 text-white">{{ $transaksi->status_transaksi }}</span>
                            @elseif($transaksi->status_transaksi == 'Selesai')
                                <span class="badge bg-success px-4 py-2 text-white">{{ $transaksi->status_transaksi }}</span>
                            @elseif($transaksi->status_transaksi == 'Request Dihapus')
                                <span class="badge bg-secondary px-4 py-2 text-white">{{ $transaksi->status_transaksi }}</span>
                            @elseif($transaksi->status_transaksi == 'Cancelled')
                                <span class="badge bg-danger text-white px-4 py-2 text-white">{{ $transaksi->status_transaksi }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($transaksi->status_pembayaran == 'Lunas')
                                <span class="badge bg-success px-4 py-2 text-white">{{ $transaksi->status_pembayaran }}</span>
                            @else
                                <span class="badge bg-danger px-4 py-2 text-white">{{ $transaksi->status_pembayaran }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="/detail-transaksi/{{$transaksi->id}}" class="btn btn-primary" target="_blank">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Custom filtering function for date range
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var min = $('#minDate').val();
            var max = $('#maxDate').val();
            var date = data[3]; // kolom ke-4 = tanggal_transaksi

            if (!min && !max) return true;

            if (date) {
                var dateObj = new Date(date);

                if (
                    (!min || dateObj >= new Date(min)) &&
                    (!max || dateObj <= new Date(max))
                ) {
                    return true;
                }
            }
            return false;
        }
    );

    var table = $('#dataTableUser').DataTable({
        stateSave: true,
    });

    // 🔍 Filter status
    $('#filterStatus').on('change', function () {
        table.column(7).search(this.value).draw();
    });

    // 🔍 Filter outlet
    $('#filterOutlet').on('change', function () {
        table.column(4).search(this.value).draw();
    });

    // 📅 Filter tanggal
    $('#minDate, #maxDate').on('change', function () {
        table.draw();
    });
});
</script>

@endpush
