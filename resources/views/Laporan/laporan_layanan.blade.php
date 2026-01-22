@extends('layouts.main')

@section('title', 'Laporan Layanan')

@section('content')
@push('styles')
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Laporan Layanan</h1>

    <form action="{{ route('laporan.layanan') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label>Bulan</label>
                <select name="bulan" class="form-control">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Tahun</label>
                <select name="tahun" class="form-control">
                    @for($y = date('Y') - 5; $y <= date('Y'); $y++)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label>Outlet</label>
                <select name="outlet" class="form-control">
                    <option selected disabled>-- Pilih Outlet --</option>
                    @foreach($outletList as $outlet)
                    <option value="{{$outlet->id}}">{{$outlet->nama_outlet}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-3">Periode: {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }} {{$outlet->nama_outlet}}</h5>
            <table class="table table-bordered table-striped" id="dataTableUser">
                <thead class="table-primary text-center">
                    <tr>
                        <th width='5%'>No</th>
                        <th>Nama Layanan</th>
                        <th class="text-center">Jumlah Load</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporan as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->nama_produk }}</td>
                        <td class="text-center">{{ $item->total_qty }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<!-- DataTables & Export -->
 <script>
$(document).ready(function() {
    $('#dataTableUser').DataTable({
        stateSave: true, // 🔥 ini fitur penyimpanannya
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        }
    });
});
</script>
 @endpush
