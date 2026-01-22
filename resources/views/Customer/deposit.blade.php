@extends('layouts.main')

@section('title', 'Data Deposit Langganan')

@section('content')
@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
@endpush

    <h1 class="h3 mb-4 text-gray-800">Data Deposit Langganan</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Transaksi Deposit</h6>
            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalTambahDeposit">
                <i class="fas fa-plus"></i> Tambah Deposit
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableUser" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th width="10%">Outlet</th>
                            <th>Nama Customer</th>
                            <th width="10%">Saldo</th>
                            <th width="15%">Tanggal Transaksi</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deposit as $d)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                {{ $d->customer->outlet->nama_outlet ?? 'Tanpa Outlet' }}
                            </td>
                            <td>
                                {{ $d->customer->nama_customer ?? 'N/A' }}
                                @if($d->customer && $d->customer->is_langganan == 1)
                                    <i class="fas fa-star text-warning" title="Pelanggan Langganan"></i>
                                @endif
                            </td>
                            <td class="font-weight-bold text-success">
                                Rp {{ number_format($d->saldo, 0, ',', '.') }}
                            </td>
                            <td>{{ $d->updated_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-info" 
                                    onclick="editDeposit('{{ $d->id }}', '{{ $d->customer->nama_customer ?? '' }}', '{{ $d->nominal }}')"
                                    data-toggle="modal" data-target="#modalEditDeposit">
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

    <div class="modal fade" id="modalTambahDeposit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{route('deposit.store')}}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold">Form Tambah Deposit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Pilih Customer (Langganan)</label>
                            <select name="id_customer" id="customer_id_select" class="form-control select2" required style="width: 100%;">
                                <option value="" disabled selected>-- Cari Customer --</option>
                                @foreach ($customer as $c)
                                    <option value="{{ $c->id }}" data-is-langganan="{{ $c->is_langganan }}">
                                        {{ $c->nama_customer }} - {{ $c->alamat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nominal Deposit (Rp)</label>
                            <input type="number" name="nominal" class="form-control" placeholder="Masukkan jumlah uang" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Deposit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="modalEditDeposit" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="formEditDeposit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold">Update Saldo Deposit</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="form-group">
                            <label>Nama Customer</label>
                            <input type="text" id="edit_nama_customer" class="form-control" readonly style="background-color: #f8f9fa;">
                        </div>
                        <div class="form-group">
                            <label>Input Nominal (Tambah/Kurang)</label>
                            <input type="number" name="nominal" id="edit_nominal" class="form-control" value="0" required>
                            
                            <div class="mt-2 p-2 rounded" style="background-color: #e3f2fd; border-left: 4px solid #2196f3;">
                                <small class="form-text text-dark">
                                    <strong><i class="fas fa-info-circle"></i> Petunjuk Pengisian:</strong><br>
                                    • Masukkan jumlah saldo yang akan ditambahkan.<br>
                                    • Jika ingin mengurangi, gunakan tanda minus (-) di depan angka.
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Saldo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // 1. Inisialisasi DataTable
    $('#dataTableUser').DataTable({
        stateSave: true,
    });

    // 2. Fungsi Format Icon Bintang di Select2
    function formatCustomer(state) {
        if (!state.id) return state.text;
        var isLangganan = $(state.element).data('is-langganan');
        var icon = isLangganan == 1 ? '<i class="fas fa-star text-warning mr-1"></i> ' : '';
        return $('<span>' + icon + state.text + '</span>');
    }

    // 3. Inisialisasi Select2
    $('#customer_id_select').select2({
        theme: 'bootstrap4',
        templateResult: formatCustomer,
        templateSelection: formatCustomer,
        escapeMarkup: function(m) { return m; }
    });

    // 4. Alert Notifikasi
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Sukses', text: '{{ session('success') }}', timer: 2000, showConfirmButton: false });
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

function editDeposit(id, nama, nominal) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama_customer').value = nama;
    
    // Reset nominal ke 0 setiap kali buka modal agar user tidak salah hitung
    document.getElementById('edit_nominal').value = ''; 
    
    document.getElementById('formEditDeposit').action = '/deposit/update/' + id;

    // Fokuskan kursor ke input nominal
    setTimeout(function() {
        document.getElementById('edit_nominal').focus();
    }, 500);
}
</script>
@endpush