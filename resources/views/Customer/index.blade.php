@extends('layouts.main')

@section('title', 'Data Customer')

@section('content')
@push('styles')
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
<!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Data Customer</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Customer</h6>
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalTambahCustomer">
                <i class="fas fa-plus"></i> Tambah Customer
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableUser" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th>Nama Customer</th>
                            <th>No.Telepon</th>
                            <th>Alamat</th>
                            <th width="5%">Langganan</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customer as $customer)
                        <tr>
                            <td class="text-center">{{$loop->iteration}}</td>
                            <td>{{$customer->nama_customer}}</td>
                            <td>{{$customer->telepon}}</td>
                            <td>{{$customer->alamat}}</td>
                            <td class="text-center" >
                                @if($customer->is_langganan)
                                    <span class="text-success" style="font-size: 1.2rem;">
                                        <i class="fas fa-check-square"></i>
                                    </span>
                                @else
                                    <span class="text-muted" style="opacity: 0.3;">
                                        <i class="far fa-square"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning" 
                                    data-toggle="modal" 
                                    data-target="#modalEditCustomer"
                                    onclick="editCustomer('{{ $customer->id }}', '{{ $customer->nama_customer }}', '{{ $customer->telepon }}', '{{ $customer->alamat }}','{{ $customer->is_langganan }}')">
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

    <!-- Modal Tambah Customer -->
    <div class="modal fade" id="modalTambahCustomer" tabindex="-1" role="dialog" aria-labelledby="modalTambahOutletLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="/customer/store" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="modalTambahOutletLabel">Tambah Customer Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Customer</label>
                            <input type="text" name="nama_customer" class="form-control" placeholder="Masukkan Nama Customer" required>
                        </div>
                        <div class="form-group">
                            <label>Nomor Telepon (WA)</label>
                            <input type="text" name="telepon" class="form-control" placeholder="Masukkan nomor telepon" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" name="alamat" class="form-control" placeholder="Masukkan alamat customer" required>
                        </div>
                        <div class="form-group form-check ml-1">
                            <input type="checkbox" class="form-check-input" name="is_langganan" id="is_langganan" value="1">
                            <label class="form-check-label" for="is_langganan">
                                <strong>Customer Langganan</strong>
                            </label>
                            <small class="form-text text-muted">Centang jika customer ini adalah pelanggan tetap.</small>
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

    <!-- Modal Edit Customer -->
    <div class="modal fade" id="modalEditCustomer" tabindex="-1" role="dialog" aria-labelledby="modalEditCustomerLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formEditCustomer" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="modalEditCustomerLabel">Edit Data Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">

                        <div class="form-group">
                            <label>Nama Customer</label>
                            <input type="text" name="nama_customer" id="edit_nama_customer" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Nomor Telepon (WA)</label>
                            <input type="text" name="telepon" id="edit_telepon" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <input type="text" name="alamat" id="edit_alamat" class="form-control" required>
                        </div>
                        <div class="form-group form-check ml-1">
                            <input type="checkbox" class="form-check-input" name="is_langganan" id="edit_is_langganan" value="1">
                            <label class="form-check-label" for="edit_is_langganan">
                                <strong>Customer Langganan</strong>
                            </label>
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
    function editCustomer(id, nama, telepon, alamat, isLangganan) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nama_customer').value = nama;
        document.getElementById('edit_telepon').value = telepon;
        document.getElementById('edit_alamat').value = alamat;

        // Logika untuk checkbox: jika isLangganan bernilai 1, maka centang (true)
        document.getElementById('edit_is_langganan').checked = (isLangganan == 1);

        // Atur action form agar sesuai dengan route update
        document.getElementById('formEditCustomer').action = '/customer/update/' + id;
    }
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
