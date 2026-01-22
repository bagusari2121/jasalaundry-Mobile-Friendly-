@extends('layouts.main')

@section('title', 'Data Produk')

@section('content')
@push('styles')
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
<!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Data Produk</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6>
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalTambahProduk">
                <i class="fas fa-plus"></i> Tambah Produk
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableUser" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Satuan</th>
                            <th>Diskon</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produk as $produk)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td>{{$produk->kode_produk}}</td>
                            <td>{{$produk->nama_produk}}</td>
                            <td>Rp.{{number_format($produk->harga_beli)}}</td>
                            <td>Rp.{{number_format($produk->harga_jual)}}</td>
                            <td>{{$produk->satuan}}</td>
                            <td>{{$produk->diskon}} %</td>
                            <td>
                                <button 
                                class="btn btn-warning btnEditProduk"
                                data-id="{{ $produk->id }}"
                                data-kode="{{ $produk->kode_produk }}"
                                data-nama="{{ $produk->nama_produk }}"
                                data-beli="{{ $produk->harga_beli }}"
                                data-jual="{{ $produk->harga_jual }}"
                                data-satuan="{{ $produk->satuan }}"
                                data-diskon="{{ $produk->diskon }}"
                            >
                                <i class="fas fa-edit"></i>
                            </button>
                            <!-- <button class="btn btn-danger btnDelete" data-id="{{ $produk->id }}">
                                <i class="fas fa-trash"></i>
                            </button> -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal tambah produk -->
<div class="modal fade" id="modalTambahProduk" tabindex="-1" role="dialog" aria-labelledby="modalTambahOutletLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="/data_produk/store" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="modalTambahOutletLabel">Tambah Produk Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Kode Produk</label>
                            <input type="text" name="kode_produk" class="form-control" value={{$kode_produk}} readonly>
                        </div>
                        <div class="form-group">
                            <label>Nama Produk</label>
                            <input type="text" name="nama_produk" class="form-control" placeholder="Masukkan Nama Produk" required>
                        </div>
                        <div class="form-group">
                            <label>Harga Beli</label>
                            <input type="number" name="harga_beli" class="form-control" placeholder="Masukkan Harga Beli" required>
                        </div>
                        <div class="form-group">
                            <label>Harga Jual</label>
                            <input type="number" name="harga_jual" class="form-control" placeholder="Masukkan Harga Jual" required>
                        </div>
                        <div class="form-group">
                            <label for="satuan">Satuan Produk</label>
                            <select name="satuan" id="satuan" class="form-control" required>
                                <option value="">-- Pilih Satuan --</option>
                                @foreach($satuanList as $satuan)
                                    <option value="{{ $satuan }}">{{ ucfirst($satuan) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Diskon (%)</label>
                            <input type="number" name="diskon" class="form-control" placeholder="Masukkan Diskon dalam %" required>
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
    <!-- Modal Edit Produk -->
<div class="modal fade" id="modalEditProduk" tabindex="-1" role="dialog" aria-labelledby="modalEditProdukLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formEditProduk" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold" id="modalEditProdukLabel">Edit Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label>Kode Produk</label>
                        <input type="text" name="kode_produk" id="edit_kode" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" name="nama_produk" id="edit_nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Beli</label>
                        <input type="number" name="harga_beli" id="edit_beli" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Harga Jual</label>
                        <input type="number" name="harga_jual" id="edit_jual" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Satuan</label>
                        <select name="satuan" id="edit_satuan" class="form-control" required>
                            @foreach($satuanList as $satuan)
                                <option value="{{ $satuan }}">{{ ucfirst($satuan) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Diskon</label>
                        <input type="number" name="diskon" id="edit_diskon" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
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

// === SweetAlert DELETE ===
    $('.btnDelete').on('click', function() {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data produk ini tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = `/data_produk/delete/${id}`;
            }
        });
    });

    // === Modal Edit Produk ===
    $('.btnEditProduk').on('click', function() {
        const id = $(this).data('id');
        $('#edit_id').val(id);
        $('#edit_kode').val($(this).data('kode'));
        $('#edit_nama').val($(this).data('nama'));
        $('#edit_beli').val($(this).data('beli'));
        $('#edit_jual').val($(this).data('jual'));
        $('#edit_satuan').val($(this).data('satuan'));
        $('#edit_diskon').val($(this).data('diskon'));

        $('#formEditProduk').attr('action', `/data_produk/update/${id}`);
        $('#modalEditProduk').modal('show');
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
});
</script>
@endpush
