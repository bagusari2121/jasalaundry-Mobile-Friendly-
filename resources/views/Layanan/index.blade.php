@extends('layouts.main')

@section('title', 'Data Layanan')

@section('content')
@push('styles')
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
<!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Data Layanan</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Layanan</h6>
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalTambahLayanan">
                <i class="fas fa-plus"></i> Tambah Layanan
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableUser" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Layanan</th>
                            <th>Harga</th>
                            <th>Satuan</th>
                            <th>Diskon (%)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($layanan as $item)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td>{{$item->nama_layanan}}</td>
                            <td>Rp.{{number_format($item->harga)}}</td>
                            <td>{{$item->satuan}}</td>
                            <td>{{$item->diskon}} %</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning btn-edit"
                                    data-id="{{ $item->id }}"
                                    data-nama="{{ $item->nama_layanan }}"
                                    data-harga="{{ $item->harga }}"
                                    data-satuan="{{ $item->satuan }}"
                                    data-estimasi="{{ $item->estimasi_selesai }}"
                                    data-diskon="{{ $item->diskon}}"
                                    >
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal Tambah Layanan  -->
    <div class="modal fade" id="modalTambahLayanan" tabindex="-1" role="dialog" aria-labelledby="modalTambahOutletLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="/layanan/store" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="modalTambahOutletLabel">Tambah Layanan Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Layanan</label>
                            <input type="text" name="nama_layanan" class="form-control" placeholder="Masukkan Nama Layanan" required>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" name="harga" class="form-control" placeholder="Masukkan Harga" required>
                        </div>
                        <div class="form-group">
                            <label>Satuan</label>
                            <select name="satuan" id="satuan" class="form-control">
                                <option disabled selected>-- Pilih Satuan --</option>
                                @foreach($satuanList as $satuan)
                                <option value="{{$satuan}}">{{ ucfirst($satuan) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Diskon (%)</label>
                            <input type="number" name="diskon" class="form-control" placeholder="Masukkan Diskon" min="0" max="100">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Edit Layanan -->
    <div class="modal fade" id="modalEditLayanan" tabindex="-1" role="dialog" aria-labelledby="modalEditLayananLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formEditLayanan" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title" id="modalEditLayananLabel">Edit Layanan</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">

                        <div class="form-group">
                            <label for="edit_nama_layanan">Nama Layanan</label>
                            <input type="text" name="nama_layanan" id="edit_nama_layanan" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_harga">Harga (Rp)</label>
                            <input type="number" name="harga" id="edit_harga" class="form-control" min="0" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_satuan">Satuan</label>
                            <select name="satuan" id="edit_satuan" class="form-control" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($satuanList as $s)
                                    <option value="{{ $s }}">{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_diskon">Diskon (%)</label>
                            <input type="number" name="diskon" id="edit_diskon" class="form-control" min="0" max="100">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                </div>
            </form>
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

<script>
$(document).ready(function() {
    // Saat tombol edit diklik
    $('.btn-edit').on('click', function() {
        const id = $(this).data('id');
        const nama = $(this).data('nama');
        const harga = $(this).data('harga');
        const satuan = $(this).data('satuan');
        const estimasi = $(this).data('estimasi');
        const diskon = $(this).data('diskon');

        // Isi data ke form modal
        $('#edit_id').val(id);
        $('#edit_nama_layanan').val(nama);
        $('#edit_harga').val(harga);
        $('#edit_satuan').val(satuan);
        $('#edit_estimasi_selesai').val(estimasi);
        $('#edit_diskon').val(diskon);

        // Set action form sesuai id layanan
        $('#formEditLayanan').attr('action', '/layanan/update/' + id);

        // Tampilkan modal
        $('#modalEditLayanan').modal('show');
    });
});
</script>

<script>
$(document).ready(function() {
    // optional: tampilkan toast setelah delete berdasarkan session flash
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '{{ session('success') }}',
            timer: 1800,
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
@endpush
