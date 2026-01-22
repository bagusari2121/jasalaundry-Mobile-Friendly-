@extends('layouts.main')

@section('title', 'Data Request Delete Transaksi') 

@section('content')
@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

<h1 class="h3 mb-4 text-gray-800">Data Request Delete Transaksi</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Data Request Delete Transaksi</h6>
    </div>
    <div class="card-body">
        {{-- 🔽 TABEL DATA --}}
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableUser" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th class="text-center">Kode Transaksi</th>
                        <th class="text-center">Customer</th>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Outlet</th>
                        <th class="text-center">Total Transaksi</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $transaksi)
                    <tr>
                        <td class="text-center">{{$loop->iteration}}</td>
                        <td>{{$transaksi->kode_transaksi}}</td>
                        <td>{{$transaksi->customer->nama_customer}}</td>
                        <td>{{$transaksi->tanggal_transaksi}}</td>
                        <td>{{$transaksi->outlet->nama_outlet ?? '-'}}</td>
                        <td>Rp. {{number_format($transaksi->total_transaksi)}}</td>
                        <td>{{$transaksi->alasan}}</td>
                        <td class="text-center">
                            @if($transaksi->status_transaksi == 'Pesanan Masuk')
                                <span class="badge bg-primary px-4 py-2 text-white">{{ $transaksi->status_transaksi }}</span>
                            @elseif($transaksi->status_transaksi == 'Proses')
                                <span class="badge bg-warning text-dark px-4 py-2 text-white">{{ $transaksi->status_transaksi }}</span>
                            @elseif($transaksi->status_transaksi == 'Diambil')
                                <span class="badge bg-danger text-white px-4 py-2 text-white">{{ $transaksi->status_transaksi }}</span>
                            @elseif($transaksi->status_transaksi == 'Selesai')
                                <span class="badge bg-success px-4 py-2 text-white">{{ $transaksi->status_transaksi }}</span>
                            @elseif($transaksi->status_transaksi == 'Request Dihapus')
                                <span class="badge bg-secondary px-4 py-2 text-white">{{ $transaksi->status_transaksi }}</span>
                            @endif
                        </td>
                        
                        <td class="text-center">
                          @if($user->role == 'Owner' || $user->role == 'Admin')
                            <button type="button" class="btn btn-success" 
                                    data-toggle="modal" 
                                    data-target="#modalReject"
                                    data-id="{{ $transaksi->id}}"
                                    data-kode="{{ $transaksi->kode_transaksi }}">
                                Tidak Disetujui
                            </button>
                            <button type="button" class="btn btn-danger" 
                                    data-toggle="modal" 
                                    data-target="#confirmDeleteModal" 
                                    data-id="{{ $transaksi->id }}"
                                    data-kode="{{ $transaksi->kode_transaksi }}">
                                Hapus
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal Konfirmasi Delete -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="confirmDeleteLabel">Konfirmasi Hapus</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="deleteMessage">Apakah Anda yakin ingin menghapus transaksi ini?</p>
      </div>
      <div class="modal-footer">
        <form id="deleteForm" method="POST" action="">
          @csrf
          @method('DELETE')
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Ya, Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi Delete -->
<div class="modal fade" id="modalReject" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="confirmDeleteLabel">Konfirmasi Hapus</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="deleteMessage">Kembalikan Transaksi ?</p>
      </div>
      <div class="modal-footer">
        <form id="deleteForm" method="POST" action="">
          @csrf
          @method('PUT')
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Ya, Kembalikan</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// 🗑️ Modal konfirmasi delete
$('#confirmDeleteModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var kode = button.data('kode');

    var modal = $(this);
    modal.find('#deleteMessage').text('Apakah Anda yakin ingin menghapus transaksi dengan kode ' + kode + '?' + ' Penghasilan akan dihapus di laporan pendapatan');
    modal.find('#deleteForm').attr('action', '/transaksi/delete/' + id);
});

$('#modalReject').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');
    var kode = button.data('kode');

    var modal = $(this);
    modal.find('#deleteMessage').text('Kembalikan Data Transaksi');
    modal.find('#deleteForm').attr('action', '/transaksi/reject/' + id);
});

</script>

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
