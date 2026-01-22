@extends('layouts.main')

@section('title', 'Riwayat Stok')

@section('content')
@push('styles')
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
<!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Riwayat Stok</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Stok</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableUser" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Tanggal</th>
                            <th width="25%">Outlet</th>
                            <th width="25%">Nama Produk</th>
                            <th width="10%">Jenis</th>
                            <th width="10%">Stok</th>
                            <th width="25%">Keterangan</th>
                            <th>PIC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayat as $riwayat)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td>{{$riwayat->created_at}}</td>
                            <td>{{$riwayat->outlet->nama_outlet}}</td>
                            <td>{{$riwayat->produk->kode_produk}} {{$riwayat->produk->nama_produk}}</td>
                            <td>{{$riwayat->tipe}}</td>
                            <td>{{$riwayat->jumlah}}</td>
                            <td>{{$riwayat->keterangan}}</td>
                            <td>{{$riwayat->user->name ?? ' '}}</td>
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
    $('#dataTableUser').DataTable({
        stateSave: true, // 🔥 ini fitur penyimpanannya
    });
});
</script>
@endpush