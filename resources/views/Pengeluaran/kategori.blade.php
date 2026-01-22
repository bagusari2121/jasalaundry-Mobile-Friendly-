@extends('layouts.main')
@section('title', 'Kategori Pengeluaran')
@section('content')

<h1 class="h3 mb-4 text-gray-800">Data Kategori Pengeluaran</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Kategori</h6>
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalKategori">
                <i class="fas fa-plus"></i> Tambah Kategori
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableUser" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="80%">Nama Pengeluaran</th>
                            <th width="15%" class="text-center">Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $cat->nama_pengeluaran }}</td>
                            <td class="text-center">
                                @if($cat->is_active == 1)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button type="button"
                                    class="btn btn-warning btn-sm"
                                    data-toggle="modal"
                                    data-target="#editKategoriModal"
                                    data-id="{{ $cat->id }}"
                                    data-nama="{{ $cat->nama_pengeluaran }}"
                                    data-status="{{ $cat->is_active }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Outlet -->
    <div class="modal fade" id="modalKategori" tabindex="-1" role="dialog" aria-labelledby="modalTambahKategori" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{route('kategori-pengeluaran.store')}}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="modalTambahOutletLabel">Tambah Kategori Pengeluaran Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Kategori</label>
                            <input type="text" name="nama_pengeluaran" class="form-control" placeholder="Masukkan Nama Kateogi" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSubmit">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Edit Kategori -->
     <div class="modal fade" id="editKategoriModal" tabindex="-1" role="dialog" aria-labelledby="editKategoriLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

            <form method="POST" id="editKategoriForm">
                @csrf
                @method('PUT')

                <div class="modal-header">
                <h5 class="modal-title" id="editKategoriLabel">Edit Kategori Pengeluaran</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
                </div>

                <div class="modal-body">

                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" name="nama_pengeluaran" id="editNama" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="is_active" id="editStatus" class="form-control">
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                    </select>
                </div>

                </div>

                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>

            </form>

            </div>
        </div>
        </div>

@endsection
@push('scripts')

<script>
$('#editKategoriModal').on('show.bs.modal', function (event) {
    let button = $(event.relatedTarget);

    let id     = button.data('id');
    let nama   = button.data('nama');
    let status = button.data('status');

    let modal = $(this);

    modal.find('#editNama').val(nama);
    modal.find('#editStatus').val(status);

    // SET ACTION FORM
    modal.find('#editKategoriForm')
         .attr('action', '/kategori-pengeluaran/edit/' + id);
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
<script>
$('form').on('submit', function () {
    let btn = $(this).find('button[type=submit]');

    btn.prop('disabled', true);
    btn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
});
</script>

@endpush
