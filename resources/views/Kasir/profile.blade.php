@extends('layouts.main')

@section('title', 'Profile')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Profil Pengguna</h1>

    <!-- Card Profil -->
    <div class="card shadow mb-4">
        <div class="card-body d-flex align-items-center">
            <img class="img-profile rounded-circle mr-4" 
                 src="{{ asset('img/undraw_profile.svg') }}" 
                 style="width: 100px; height: 100px;">

            <div>
                <h4 class="font-weight-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-1">{{ $user->email }}</p>
                <span class="badge bg-primary text-white">{{ ucfirst($user->role) }}</span>
                <p class="text-muted mb-1">{{ $user->outlet->nama_outlet }}</p>
                <p class="mt-2 mb-0"><i class="fas fa-calendar-alt"></i> Bergabung sejak: {{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Info Tambahan -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Akun</h6>
                </div>
                <div class="card-body">
                    <p><strong>Nama:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                    <p><strong>Outlet:</strong> {{ $user->outlet->nama_outlet }}</p>
                    <p><strong>Tanggal Bergabung:</strong> {{ $user->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Card Edit Password -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">Ubah Password</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.updatePassword') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="old_password">Password Lama</label>
                            <input type="password" name="old_password" id="old_password" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="new_password">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2000
    });
</script>
@endif
@endsection
