@extends('layouts.main')

@section('title', 'Data Outlet')

@section('content')
@push('styles')
<style>
    .card-link-wrapper {
        display: block;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .card-link-wrapper:hover .outlet-item {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .card-link-wrapper:hover {
        text-decoration: none;
    }

    .card-link-wrapper:hover .card-title {
        color: #2e59d9; /* warna biru SB Admin 2 */
    }
</style>
@endpush

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Data Outlet</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Outlet</h6>
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalTambahOutlet">
                <i class="fas fa-plus"></i> Tambah Outlet
            </button>
        </div>

        <div class="card-body">
            <div class="row">
                @forelse($outlet as $o)
                    <div class="col-md-4 mb-4 outlet-card">
                        <a href="/outlet/edit/{{$o->id}}" class="card-link-wrapper text-decoration-none text-dark">
                            <div class="card outlet-item h-100 shadow-sm border-0 position-relative">
                                <div class="card-body">
                                    <h5 class="card-title text-primary mb-2">{{ $o->nama_outlet }}</h5>
                                    <p class="card-text mb-1">
                                        <i class="fas fa-map-marker-alt text-danger"></i>
                                        {{ $o->alamat }}
                                    </p>
                                    <p class="card-text text-muted mb-0">
                                        <i class="fas fa-phone text-success"></i>
                                        {{ $o->telepon ?? '-' }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-5">
                        <i class="fas fa-store-slash fa-3x mb-3"></i>
                        <p>Belum ada data outlet.</p>
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <!-- Modal Tambah Outlet -->
    <div class="modal fade" id="modalTambahOutlet" tabindex="-1" role="dialog" aria-labelledby="modalTambahOutletLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('outlet.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="modalTambahOutletLabel">Tambah Outlet Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Outlet</label>
                            <input type="text" name="nama_outlet" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Telepon</label>
                            <input type="text" name="telepon" class="form-control">
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
@endsection

@push('scripts')

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
