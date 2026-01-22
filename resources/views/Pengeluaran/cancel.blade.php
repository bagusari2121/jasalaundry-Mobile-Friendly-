@extends('layouts.main')

@section('title', 'Cancel Pengeluaran')

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')

<h1 class="h3 mb-4 text-gray-800">Permintaan Pembatalan Pengeluaran</h1>

{{-- FILTER --}}
{{-- RINGKASAN --}}
{{-- TABEL --}}
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Daftar Permintaan Pembatalan Pengeluaran</strong>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped" id="pengeluaranTable">
            <thead class="thead-light">
                <tr class="text-center">
                    <th>No.</th>
                    <th width="10%" class="text-center">Tanggal</th>
                    <th width="10%" class="text-center">Outlet</th>
                    <th>Kategori</th>
                    <th>Metode Pembayaran</th>
                    <th width="20%">Keterangan</th>
                    <th>PIC</th>
                    <th>Nominal</th>
                    <th>Status</th>
                    <th width="10%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengeluaran as $row)
                <tr>
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td class="text-center">{{ tanggalIndo($row->created_at) }}</td>
                    <td>{{ $row->outlet->nama_outlet }}</td>
                    <td>{{ $row->kategori->nama_pengeluaran }}</td>
                    <td class="text-center">{{ $row->metode_pembayaran }}</td>
                    <td>{{ $row->keterangan }}</td>
                    <td>{{ $row->user->name }}</td>
                    <td class="text-right">
                        Rp {{ number_format($row->nominal, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @php
                            $statusMap = [
                                'Aktif' => 'success',
                                'Request Batal' => 'warning',
                                'Batal' => 'danger',
                            ];
                        @endphp

                        <span class="badge badge-{{ $statusMap[$row->status] ?? 'secondary' }}">
                            {{ ucwords(str_replace('_', ' ', $row->status)) }}
                        </span>
                    </td>

                    <td class="text-center">
                        <div class="dropdown">
                            <button 
                                class="btn btn-sm btn-primary dropdown-toggle"
                                type="button"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button class="dropdown-item btn-confirm-acc" data-id="{{ $row->id }}">
                                    <i class="fas fa-check text-success mr-2"></i> Acc Pembatalan
                                </button>
                                <form id="form-acc-{{ $row->id }}" 
                                    action="{{ route('pengeluaran.acc_pembatalan', $row->id) }}" 
                                    method="POST" 
                                    style="display:none;">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <button
                                    class="dropdown-item btn-tolak-batal"
                                    data-toggle="modal"
                                    data-target="#modalTolakCancelPengeluaran"
                                    data-id="{{ $row->id }}"
                                >
                                    <i class="fas fa-times-circle text-danger mr-2"></i> Tolak Pembatalan
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTolakCancelPengeluaran" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Penolakan</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formTolakBatal" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p class="text-center">Apakah Anda yakin ingin <strong>menolak</strong> permintaan pembatalan ini?</p>
                    
                    <div class="form-group">
                        <label>Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea 
                            name="reject_reason" 
                            class="form-control" 
                            rows="3" 
                            placeholder="Contoh: Bukti sudah valid, tidak bisa dibatalkan." 
                            required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Tolak Pembatalan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
$('form').on('submit', function () {
    let btn = $(this).find('button[type=submit]');

    btn.prop('disabled', true);
    btn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
});
</script>
<script>
$(function () {
    $('#pengeluaranTable').DataTable({
        pageLength: 10,
        ordering: true,
        stateSave: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                previous: "‹",
                next: "›"
            }
        }
    });
});
</script>
<!-- sweet alert -->
<script>
$(document).ready(function() {
    // optional: tampilkan toast setelah delete berdasarkan session flash
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @endif
});
</script>
<script>
    $('.btn-confirm-acc').on('click', function() {
        let id = $(this).data('id');
        
        Swal.fire({
            title: 'Konfirmasi ACC',
            text: "Apakah Anda yakin ingin menyetujui pembatalan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form tersembunyi
                $(`#form-acc-${id}`).submit();
            }
        })
    });
</script>
<script>
    $(document).on('click', '.btn-tolak-batal', function() {
        let id = $(this).data('id');
        
        // Sesuaikan URL route dengan yang ada di web.php Anda
        // Mengarahkan form action ke ID yang sesuai
        $('#formTolakBatal').attr('action', '/pengeluaran/total-cancel/' + id);
    });
</script>

@endpush