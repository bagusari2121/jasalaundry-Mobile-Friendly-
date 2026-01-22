@extends('layouts.main')

@section('title', 'Edit Outlet')

@section('content')
<h1 class="h3 mb-4 text-gray-800">Detail Outlet</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        {{-- Form Edit --}}
        <form action="/outlet/edit/store/{{ $outlet->id }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Outlet</label>
                <input 
                    type="text" 
                    name="nama_outlet" 
                    class="form-control" 
                    value="{{ $outlet->nama_outlet }}" 
                    required>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea 
                    name="alamat" 
                    class="form-control" 
                    rows="3" 
                    required>{{ $outlet->alamat }}</textarea>
            </div>

            <div class="form-group">
                <label>Telepon</label>
                <input 
                    type="text" 
                    name="telepon" 
                    class="form-control" 
                    value="{{ $outlet->telepon }}">
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>
                </form>
                &nbsp
                {{-- Tombol Hapus dan Kembali --}}
                <form 
                    action="{{ route('outlet.destroy', $outlet->id) }}" 
                    method="POST" 
                    class="d-inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger btn-delete">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
                &nbsp
                <a href="{{ route('outlet') }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        
    </div>
</div>
@endsection

@push('scripts')
<script>
    // SweetAlert konfirmasi hapus
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');

            Swal.fire({
                title: 'Yakin hapus outlet ini?',
                text: 'Data yang dihapus tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

{{-- Notifikasi sukses --}}
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        showConfirmButton: false,
        timer: 2000
    });
</script>
@endif

{{-- Notifikasi error --}}
@if($errors->any())
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: 'Terjadi kesalahan saat menyimpan data. Coba lagi.',
        showConfirmButton: true
    });
</script>
@endif
@endpush
