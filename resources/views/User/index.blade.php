@extends('layouts.main')

@section('title', 'Data User')
@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@section('content')
<!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Data User</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar User</h6>
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalTambahUser">
                <i class="fas fa-plus"></i> Tambah User
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableUser" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Outlet</th>
                            <th width="15%">Nama</th>
                            <th width="30%">Email</th>
                            <th width="10%">No. Telepon</th>
                            <th width="15%">Role</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_user as $index => $data_user)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $data_user->outlet->nama_outlet ?? ''}}</td>
                            <td>{{ $data_user->name }}</td>
                            <td>{{ $data_user->email }}</td>
                            <td>{{ $data_user->no_telepon }}</td>
                            <td>{{ $data_user->role ?? '-' }}</td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm" 
                                    onclick='editUser(@json($data_user))'>
                                    <i class="fas fa-edit"></i>
                                </button>
                                <!-- tombol delete -->
                                <!-- <form action="{{ route('user.destroy', $data_user->id) }}" method="POST" class="d-inline form-delete-user" id="form-delete-{{ $data_user->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="{{ $data_user->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form> -->
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Outlet -->
    <div class="modal fade" id="modalTambahUser" tabindex="-1" role="dialog" aria-labelledby="modalTambahOutletLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="modalTambahOutletLabel">Tambah User Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger">*Password default user adalah jasalaundry2025</p>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" placeholder="Masukkan Nama User" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Masukkan Email" required>
                        </div>
                        <div class="form-group">
                            <label>Telepon</label>
                            <input type="number" name="telepon" class="form-control" placeholder="Masukkan Nomor Telepon" required>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control" name="role">
                                <option disabled selected>-- Pilih Role --</option>
                                <option value="Admin">Admin</option>
                                <option value="Kasir">Kasir</option>
                                <option value="Owner">Owner</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Outlet</label>
                            <select class="form-control" name="id_outlet">
                                <option disabled selected>-- Pilih Outlet --</option>
                                @foreach($outlet as $outlet)
                                    <option value="{{$outlet->id}}">{{$outlet->nama_outlet}}</option>
                                @endforeach
                            </select>
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

    <!-- Modal Edit User -->
    <div class="modal fade" id="modalEditUser" tabindex="-1" role="dialog" aria-labelledby="modalEditUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formEditUser" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="modalEditUserLabel">Edit User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">

                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" id="edit_email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Telepon</label>
                            <input type="number" name="telepon" id="edit_telepon" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" id="edit_role" class="form-control" required>
                                <option value="Admin">Admin</option>
                                <option value="Kasir">Kasir</option>
                                <option value="Owner">Owner</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Outlet</label>
                            <select name="id_outlet" id="edit_id_outlet" class="form-control" required>
                                @foreach($o as $o)
                                    <option value="{{ $o->id }}">{{ $o->nama_outlet }}</option>
                                @endforeach
                            </select>
                        </div>

                        <p class="text-muted mt-3">Password user tidak bisa diubah di sini. Reset lewat fitur “Reset Password” bila perlu.</p>
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
$(function () {
    $('#dataTableUser').DataTable({
        pageLength: 10,
        ordering: true,
        stateSave: true, // ✅ INI YANG BENAR
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
<script>
    function editUser(user) {
        // isi field modal dengan data user
        $('#edit_id').val(user.id);
        $('#edit_name').val(user.name);
        $('#edit_email').val(user.email);
        $('#edit_telepon').val(user.no_telepon);
        $('#edit_role').val(user.role);
        $('#edit_id_outlet').val(user.id_outlet);

        // ubah action form sesuai id user
        $('#formEditUser').attr('action', '/user/update/' + user.id);

        // buka modal
        $('#modalEditUser').modal('show');
    }
</script>


<script>
$(document).ready(function() {
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();

        let id = $(this).data('id');
        let form = $('#form-delete-' + id);

        Swal.fire({
            title: 'Yakin menghapus user?',
            text: "Data user akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // submit form biasa (reload halaman setelah delete)
                form.submit();
            }
        });
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
