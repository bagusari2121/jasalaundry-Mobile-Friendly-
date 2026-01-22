@extends('layouts.main')

@section('title', 'Riwayat Deposit')

@section('content')
@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<style>
    .text-nominal-plus { color: #28a745; font-weight: bold; }
    .text-nominal-minus { color: #dc3545; font-weight: bold; }
</style>
@endpush

    <h1 class="h3 mb-4 text-gray-800">Riwayat Deposit</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Log Transaksi Saldo Customer</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTableRiwayat" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th>Outlet</th>
                            <th>Tanggal & Waktu</th>
                            <th>Nama Customer</th>
                            <th>Nominal</th>
                            <th>Saldo Akhir</th>
                            <th>PIC</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($riwayat as $row)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{$row->customer->outlet->nama_outlet}}</td>
                            <td>{{ $row->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <strong>{{ $row->customer->nama_customer ?? 'N/A' }}</strong>
                            </td>
                            <td>
                                @if($row->nominal > 0)
                                    <span class="text-nominal-plus">+ Rp {{ number_format($row->nominal, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-nominal-minus">- Rp {{ number_format(abs($row->nominal), 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td><strong>Rp {{ number_format($row->saldo_akhir, 0, ',', '.') }}</strong></td>
                            <td>{{$row->user->name}}</td>
                            <td><small>{{ $row->keterangan }}</small></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script>
$(document).ready(function() {
    $('#dataTableRiwayat').DataTable({
        "order": [[ 1, "desc" ]], // Urutkan berdasarkan tanggal terbaru
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        }
    });
});
</script>
@endpush