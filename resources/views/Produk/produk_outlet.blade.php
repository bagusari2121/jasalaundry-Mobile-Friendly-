@extends('layouts.main')

@section('title', 'Data Produk di Outlet')

@section('content')
@push('styles')
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
<!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Data Produk Di Outlet</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Produk di Outlet</h6>
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalTambahStok">
                <i class="fas fa-plus"></i> Tambah Stok Produk
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableUser" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Nama Outlet</th>
                            <th width="10%">Kode Produk</th>
                            <th width="40%">Nama Produk</th>
                            <th width="10%">Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stok_outlet as $stok)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td>{{$stok->outlet->nama_outlet}}</td>
                            <td>{{$stok->produk->kode_produk}}</td>
                            <td>{{$stok->produk->nama_produk}}</td>
                            <td>{{$stok->stok}} {{$stok->produk->satuan}}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" 
                                    data-toggle="modal" 
                                    data-target="#modalEditStok"
                                    data-id="{{ $stok->id }}"
                                    data-outlet="{{ $stok->outlet->nama_outlet }}"
                                    data-produk="{{ $stok->produk->nama_produk }}"
                                    data-stok="{{ $stok->stok }}">
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
    <!-- Modal Tambah Stok -->
    <div class="modal fade" id="modalTambahStok" tabindex="-1" role="dialog" aria-labelledby="modalTambahOutletLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="/stok_outlet/store" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="modalTambahOutletLabel">Tambah Stok Produk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Outlet</label>
                            <select name="id_outlet" id="id_outlet" class="form-control">
                                <option disabled selected>-- Pilih Oulet --</option>
                                @foreach($outlet as $outlet)
                                <option value="{{$outlet->id}}">{{$outlet->nama_outlet}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nama Produk</label>
                            <select name="id_produk" id="id_produk" class="form-control">
                                <option disabled selected>-- Pilih Produk --</option>
                                @foreach($produk as $produk)
                                <option value="{{$produk->id}}">{{$produk->kode_produk}} - {{$produk->nama_produk}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Stok Masuk</label>
                            <input type="number" name="stok" class="form-control" placeholder="Masukkan Stok Masuk" required>
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
    <!-- Modal Edit Stok Outlet -->
    <div class="modal fade" id="modalEditStok" tabindex="-1" role="dialog" aria-labelledby="modalEditStokLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formEditStok" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="modalEditStokLabel">Edit Stok Outlet</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">

                        <div class="form-group">
                            <label>Nama Outlet</label>
                            <input type="text" id="edit_outlet" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label>Nama Produk</label>
                            <input type="text" id="edit_produk" class="form-control" readonly>
                        </div>

                        <div class="form-group">
                            <label>Stok Saat Ini</label>
                            <input type="number" id="edit_stok_lama" class="form-control" readonly>
                        </div>
                        

                        <div class="form-group">
                            <label>Tambah / Kurangi Stok</label>
                            <input type="number" name="stok" id="edit_stok_baru" class="form-control" required placeholder="Masukkan jumlah perubahan stok">
                            <small class="text-muted">Masukkan angka positif untuk menambah stok, atau negatif untuk mengurangi stok.</small>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Masukkan Alasan Mengubah stok Produk"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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

$('#modalEditStok').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var outlet = button.data('outlet');
    var produk = button.data('produk');
    var stok = button.data('stok');

    var modal = $(this);
    modal.find('#edit_id').val(id);
    modal.find('#edit_outlet').val(outlet);
    modal.find('#edit_produk').val(produk);
    modal.find('#edit_stok_lama').val(stok);

    // arahkan action form sesuai route update
    modal.find('#formEditStok').attr('action', '/stok_outlet/update/' + id);
});

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
</script>
@endpush