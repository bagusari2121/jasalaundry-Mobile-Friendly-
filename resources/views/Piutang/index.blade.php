@extends('layouts.main')

@section('title', 'Data Piutang Laundry') 

@section('content')
@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<style>
    .card-counter {
        border-left: 5px solid #e74a3b;
    }
</style>
@endpush

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Data Piutang (Belum Lunas)</h1>
    {{-- Card Ringkasan Total Piutang --}}
    <div class="card shadow h-100 py-2 card-counter px-3">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Tagihan Piutang</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        Rp. {{ number_format($transaksi->where('status_pembayaran', '!=', 'Lunas')->sum('total_transaksi')) }}
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-exclamation-circle fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-danger">Daftar Tagihan Pelanggan</h6>
    </div>
    <div class="card-body">

        {{-- 🔍 FILTER SECTION --}}
        <div class="row mb-3">
            @if($user->role == 'Owner')
            <div class="col-md-4">
                <label>Filter Outlet:</label>
                <select id="filterOutlet" class="form-control">
                    <option value="">Semua Outlet</option>
                    @foreach($outlet as $o)
                        <option value="{{ $o->nama_outlet }}">{{ $o->nama_outlet }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            <div class="col-md-4">
                <label>Dari Tanggal:</label>
                <input type="date" id="minDate" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Sampai Tanggal:</label>
                <input type="date" id="maxDate" class="form-control">
            </div>
        </div>
        {{-- Tambahkan Tombol Bayar Massal di atas tabel --}}
        <div class="mb-3">
            <button type="button" id="btnBulkBayar" class="btn btn-success" style="display: none;">
                <i class="fas fa-check-double"></i> Bayar Transaksi Terpilih (<span id="countSelected">0</span>)
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered" id="dataTablePiutang" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th width="5%"><input type="checkbox" id="selectAll"></th> {{-- Checkbox Header --}}
                        <th>Kode</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th>Outlet</th>
                        <th>Total Tagihan</th>
                        <th>Status Order</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $t)
                    {{-- Hanya tampilkan yang belum lunas --}}
                    @if($t->status_pembayaran != 'Lunas')
                    <tr>
                        <td class="text-center"><input type="checkbox" class="sub_chk" data-id="{{$t->id}}"></td>
                        <td class="font-weight-bold text-primary">{{ $t->kode_transaksi }}</td>
                        <td>{{ $t->customer->nama_customer }}</td>
                        <td>{{ $t->tanggal_transaksi }}</td>
                        <td>{{ $t->outlet->nama_outlet ?? '-' }}</td>
                        <td class="text-danger font-weight-bold">Rp. {{ number_format($t->total_transaksi) }}</td>
                        <td>
                            @if($t->status_transaksi == 'Selesai')
                                <span class="badge badge-success">Selesai (Belum Bayar)</span>
                            @else
                                <span class="badge badge-warning">{{ $t->status_transaksi }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{$t->id}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cog"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuButton{{$t->id}}">
                                    <h6 class="dropdown-header">Opsi Transaksi:</h6>
                                    
                                    {{-- Link Detail --}}
                                    <a class="dropdown-item" href="/detail-transaksi/{{$t->id}}">
                                        <i class="fas fa-eye fa-sm fa-fw mr-2 text-info"></i> Lihat Detail
                                    </a>

                                    {{-- Link Bayar --}}
                                    {{-- Tombol Bayar Diubah Menjadi Form --}}
                                    <a class="dropdown-item btn-bayar" href="javascript:void(0)" 
                                        data-id="{{ $t->id }}" 
                                        data-kode="{{ $t->kode_transaksi }}">
                                            <i class="fas fa-money-bill-wave fa-sm fa-fw mr-2 text-success"></i> Lunasi Pembayaran
                                    </a>
                                    {{-- Hidden Form untuk dikirim via JS --}}
                                    <form id="form-bayar-{{ $t->id }}" action="{{ route('transaksi.bayar', $t->id) }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- Form Tersembunyi untuk Bulk Update --}}
<form id="formBulkBayar" action="{{ route('transaksi.bulkBayar') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="ids" id="selectedIds">
</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Custom filtering function for date range
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var min = $('#minDate').val();
            var max = $('#maxDate').val();
            var date = data[3]; 

            if (!min && !max) return true;
            if (date) {
                var dateObj = new Date(date);
                if ((!min || dateObj >= new Date(min)) && (!max || dateObj <= new Date(max))) {
                    return true;
                }
            }
            return false;
        }
    );

    var table = $('#dataTablePiutang').DataTable({
        stateSave: true,
        "order": [[ 3, "desc" ]] // Urutkan dari tanggal terbaru
    });

    $('#filterOutlet').on('change', function () {
        table.column(4).search(this.value).draw();
    });

    $('#minDate, #maxDate').on('change', function () {
        table.draw();
    });
});
</script>
<script>
$(document).ready(function() {
    // 1. Handling Pesan Sukses/Error dari Controller
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 3000
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') }}",
        });
    @endif

    // 2. Handling Konfirmasi Pembayaran
    $('.btn-bayar').on('click', function() {
        let id = $(this).data('id');
        let kode = $(this).data('kode');

        Swal.fire({
            title: 'Konfirmasi Bayar',
            text: "Transaksi " + kode + " akan dilunasi. Lanjutkan?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Lunasi!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form hidden yang sesuai
                $('#form-bayar-' + id).submit();
            }
        });
    });
});
</script>
<script>
$(document).ready(function() {
    // 1. Logika Check All
    $('#selectAll').on('click', function() {
        if($(this).is(':checked',true)) {
            $(".sub_chk").prop('checked', true);
        } else {
            $(".sub_chk").prop('checked',false);
        }
        toggleBulkButton();
    });

    // 2. Logika Munculkan Tombol saat Checkbox diklik
    $('.sub_chk').on('click', function() {
        toggleBulkButton();
    });

    function toggleBulkButton() {
        var selectedCount = $(".sub_chk:checked").length;
        if(selectedCount > 0) {
            $('#btnBulkBayar').fadeIn();
            $('#countSelected').text(selectedCount);
        } else {
            $('#btnBulkBayar').fadeOut();
            $('#selectAll').prop('checked', false);
        }
    }

    // 3. Eksekusi Bulk Bayar via SweetAlert
    $('#btnBulkBayar').on('click', function() {
        var allVals = [];
        $(".sub_chk:checked").each(function() {
            allVals.push($(this).attr('data-id'));
        });

        Swal.fire({
            title: 'Bayar Massal?',
            text: "Anda akan melunasi " + allVals.length + " transaksi terpilih.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Ya, Bayar Semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#selectedIds').val(allVals.join(","));
                $('#formBulkBayar').submit();
            }
        });
    });
});
</script>
@endpush