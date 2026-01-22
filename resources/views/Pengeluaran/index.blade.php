@extends('layouts.main')

@section('title', 'Data Pengeluaran')

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<style>
    /* Style untuk baris yang batal */
    .row-batal {
        text-decoration: line-through; /* Memberikan efek coretan */
        color: #adb5bd; /* Mengubah warna teks menjadi abu-abu pudar */
        background-color: #f8f9fa !important; /* Memberikan latar belakang sangat terang */
    }

    /* Memastikan tombol aksi dan badge tetap terlihat jelas meski dicoret */
    .row-batal .btn, .row-batal .badge {
        text-decoration: none !important;
        display: inline-block;
    }
</style>
@endpush

@section('content')

<h1 class="h3 mb-4 text-gray-800">Data Pengeluaran</h1>

{{-- FILTER --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="form-row align-items-end">
            <div class="col-md-3">
                <label>Outlet</label>
                <select name="outlet" class="form-control">
                    <option value="">Semua Outlet</option>
                    @foreach($outlets as $outlet)
                        <option value="{{ $outlet->id }}">{{ $outlet->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>Kategori</label>
                <select name="kategori" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label>Dari</label>
                <input type="date" name="from" class="form-control">
            </div>

            <div class="col-md-2">
                <label>Sampai</label>
                <input type="date" name="to" class="form-control">
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary btn-block">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </div>

        </form>
    </div>
</div>

{{-- RINGKASAN --}}
<!-- <div class="row mb-3">
    <div class="col-md-4">
        <div class="card border-left-danger shadow-sm">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                    Total Pengeluaran
                </div>
                <div class="h5 font-weight-bold text-gray-800">
                    Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>
</div> -->

{{-- TABEL --}}
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <strong>Daftar Pengeluaran</strong>
        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#addPengeluaranModal">
            <i class="fas fa-plus"></i> Tambah Pengeluaran
        </button>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped" id="pengeluaranTable">
            <thead class="thead-light">
                <tr class="text-center">
                    <th>No.</th>
                    <th width="10%" class="text-center">Tanggal</th>
                    <th width="10%" class="text-center">Outlet</th>
                    <th>Kategori</th>
                    <th>Metode Pembayaran</th>
                    <th width="20%">Keterangan</th>
                    <th>PIC</th>
                    <th>Nominal</th>
                    <th>Status</th>
                    <th width="10%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengeluaran as $row)
                <tr class="{{ $row->status == 'Batal' ? 'row-batal' : '' }}">
                    <td class="text-center">{{$loop->iteration}}</td>
                    <td class="text-center">{{ tanggalIndo($row->created_at) }}</td>
                    <td>{{ $row->outlet->nama_outlet }}</td>
                    <td>{{ $row->kategori->nama_pengeluaran }}</td>
                    <td class="text-center">{{ $row->metode_pembayaran }}</td>
                    <td>{{ $row->keterangan }}</td>
                    <td>{{ $row->user->name }}</td>
                    <td class="text-right">
                        Rp {{ number_format($row->nominal, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @php
                            $statusMap = [
                                'Aktif' => 'success',
                                'Request Batal' => 'warning',
                                'Batal' => 'danger',
                            ];
                        @endphp

                        <span class="badge badge-{{ $statusMap[$row->status] ?? 'secondary' }}">
                            {{ ucwords(str_replace('_', ' ', $row->status)) }}
                        </span>
                    </td>

                    <td class="text-center">
                        <div class="dropdown">
                            <button 
                                class="btn btn-sm btn-warning dropdown-toggle"
                                type="button"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-cog"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <button 
                                    class="dropdown-item btn-detail"
                                    data-toggle="modal"
                                    data-target="#modalDetailPengeluaran"
                                    data-tanggal="{{ tanggalIndo($row->created_at) }}"
                                    data-outlet="{{ $row->outlet->nama_outlet ?? '-' }}"
                                    data-kategori="{{ $row->kategori->nama_pengeluaran ?? '-' }}"
                                    data-metode="{{ $row->metode_pembayaran }}"
                                    data-nominal="Rp {{ number_format($row->nominal, 0, ',', '.') }}"
                                    data-keterangan="{{ $row->keterangan ?? '-' }}"
                                    data-bukti="{{ $row->bukti ? asset('storage/'.$row->bukti) : '' }}"
                                    data-rutin="{{ $row->is_rutin ?? '-' }}"
                                >
                                    <i class="fas fa-eye text-warning mr-2"></i> Detail
                                </button>
                                <button 
                                    class="dropdown-item btn-bukti"
                                    data-toggle="modal"
                                    data-target="#modalBukti"
                                    data-img="{{ $row->bukti ? asset('storage/'.$row->bukti) : '' }}">
                                    <i class="fas fa-image text-info mr-2"></i> Lihat Bukti
                                </button>
                                <div class="dropdown-divider"></div>
                                @if($row->canEdit())
                                    <button 
                                        class="dropdown-item btn-edit"
                                        data-toggle="modal"
                                        data-target="#modalEditPengeluaran"
                                        data-id="{{ $row->id }}"
                                        data-kategori="{{ $row->kategori_id }}"
                                        data-nominal="{{ $row->nominal }}"
                                        data-keterangan="{{ $row->keterangan }}"
                                        data-metode="{{ $row->metode_pembayaran }}"
                                        data-bukti="{{ $row->bukti }}">
                                        <i class="fas fa-edit mr-2 text-warning"></i> Edit
                                    </button>
                                @endif
                                @if(($user->id == $row->pic || $user->role == "Admin" || $user->role == "Owner") && $row->status == "Aktif")
                                    <button
                                        class="dropdown-item btn-batal"
                                        data-toggle="modal"
                                        data-target="#modalPembatalan"
                                        data-id="{{$row->id}}">
                                        <i class="fas fa-trash mr-2 text-danger"></i> Pembatalan
                                    </button>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-end">
                <h5 class="mb-0">
                    Total Pengeluaran :
                    <strong class="text-danger">
                        Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}
                    </strong>
                </h5>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pengeluaran -->
<div class="modal fade" id="addPengeluaranModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <form action="{{ route('pengeluaran.store') }}" method="POST" id="formAddPengeluaran" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Pengeluaran</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <!-- Tanggal -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" required>
                            </div>
                        </div>
                        <!-- Outlet -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Outlet</label>
                                <select name="outlet_id" class="form-control" required>
                                    <option value="">-- Pilih Outlet --</option>
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}">{{ $outlet->nama_outlet }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Kategori -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kategori</label>
                                <select name="kategori_id" class="form-control" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->nama_pengeluaran }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Nominal -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nominal</label>
                                <input type="number" name="nominal" class="form-control" placeholder="Contoh: 50000" required>
                            </div>
                        </div>
                        <!-- Metode Pembayaran -->
                         <div class="col-md-4">
                            <div class="form-group">
                                <label>Metode Pembayaran</label>
                                <select name="metode_pembayaran" class="form-control" required>
                                    <option>-- Metode Pembayaran --</option>
                                    <option value="Cash">Cash</option>
                                    <option value="QRIS">QRIS/Transfer</option>
                                </select>
                            </div>
                        </div>

                        <!-- Apakah Rutin -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pengeluaran Rutin</label>
                                <select name="is_rutin" class="form-control" required>
                                    <option>-- Rutin? --</option>
                                    <option value="Rutin">Rutin</option>
                                    <option value="Kondisional">Kondisional</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Bukti -->
                    <div class="form-group">
                        <label>Bukti Pengeluaran</label>
                        <input 
                            type="file"
                            name="bukti"
                            id="bukti"
                            class="form-control"
                            accept="image/*"
                            capture="environment"
                            required
                        >

                        <div class="mt-2">
                            <img id="previewBukti" style="display:none; max-width:200px; margin-top:10px;">
                        </div>
                    </div>

                    <!-- Keterangan -->
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Beli deterjen"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmitPengeluaran">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetailPengeluaran" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Detail Pengeluaran</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Tanggal</th>
                        <td id="dTanggal"></td>
                    </tr>
                    <tr>
                        <th>Outlet</th>
                        <td id="dOutlet"></td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td id="dKategori"></td>
                    </tr>
                    <tr>
                        <th>Metode</th>
                        <td id="dMetode"></td>
                    </tr>
                    <tr>
                        <th>Nominal</th>
                        <td id="dNominal" class="text-danger font-weight-bold"></td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td id="dKeterangan"></td>
                    </tr>
                    <tr>
                        <th>Bukti</th>
                        <td class="text-center">
                            <img id="dBukti" class="img-fluid rounded" style="max-height:300px; display:none;">
                            <span id="dBuktiKosong" class="text-muted">Tidak ada bukti</span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>
<!-- Modal Bukti -->
<div class="modal fade" id="modalBukti" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Bukti Pengeluaran</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <img 
                    id="imgBukti"
                    class="img-fluid rounded"
                    style="max-height:80vh; display:none;"
                >
                <p id="buktiKosong" class="text-muted mt-3">
                    Tidak ada bukti pengeluaran
                </p>
            </div>

        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEditPengeluaran" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" id="formEditPengeluaran" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Pengeluaran</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <!-- PREVIEW BUKTI -->
                    <div class="form-group">
                        <label>Bukti</label>

                        <!-- Bukti Lama -->
                        <div id="wrapperBuktiLama" class="mb-2 text-center">
                            <small class="text-muted d-block">Bukti Lama</small>
                            <img id="previewBuktiLama"
                                class="img-fluid rounded"
                                style="max-height:300px; display:none;">
                        </div>

                        <!-- Bukti Baru -->
                        <div id="wrapperBuktiBaru" class="text-center mt-2" style="display:none">
                            <small class="text-primary d-block">Bukti Baru</small>
                            <img id="previewBuktiBaru"
                                class="img-fluid rounded"
                                style="max-height:300px;">
                        </div>
                        <!-- INPUT -->
                        <input type="file"
                            name="bukti"
                            id="editBukti"
                            accept="image/*"
                            capture="environment"
                            class="form-control">
                    </div>


                    <!-- KATEGORI -->
                    <div class="form-group">
                        <label>Kategori</label>
                        <select name="kategori_id" id="editKategori" class="form-control" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">
                                    {{ $cat->nama_pengeluaran }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- METODE -->
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <select name="metode_pembayaran" id="editMetode" class="form-control" required>
                            <option value="Cash">Cash</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Transfer">Transfer</option>
                            <option value="Lain-lain">Lain-lain</option>
                        </select>
                    </div>

                    <!-- NOMINAL -->
                    <div class="form-group">
                        <label>Nominal</label>
                        <input type="number" name="nominal" id="editNominal"
                               class="form-control" required>
                    </div>

                    <!-- KETERANGAN -->
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea name="keterangan" id="editKeterangan"
                                  class="form-control"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Pembatalan -->
 <!-- Modal Pembatalan Pengeluaran -->
<div class="modal fade" id="modalPembatalan" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" id="formPembatalan">
                @csrf
                @method('PUT')

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Pembatalan Pengeluaran
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="alert alert-warning">
                        <strong>Perhatian!</strong><br>
                        Pengeluaran yang dibatalkan:
                        <ul class="mb-0">
                            <li>Tidak dihitung di laporan</li>
                            <li>Tidak dapat diedit kembali</li>
                            <li>Tetap tersimpan sebagai arsip</li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label>Alasan Pembatalan <span class="text-danger">*</span></label>
                        <textarea
                            name="cancel_reason"
                            class="form-control"
                            rows="3"
                            placeholder="Contoh: Salah input nominal"
                            required></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban mr-1"></i> Batalkan Pengeluaran
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
$('form').on('submit', function () {
    let btn = $(this).find('button[type=submit]');

    btn.prop('disabled', true);
    btn.html('<i class="fas fa-spinner fa-spin"></i> Processing...');
});
</script>
<script>
$(function () {
    $('#pengeluaranTable').DataTable({
        pageLength: 10,
        ordering: true,
        stateSave: true,
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
const input = document.getElementById('bukti');
const preview = document.getElementById('previewBukti');

input.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
        alert('File harus gambar');
        this.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        const img = new Image();
        img.onload = function () {

            const MAX_WIDTH = 1280;
            const MAX_HEIGHT = 1280;
            let width = img.width;
            let height = img.height;

            if (width > height && width > MAX_WIDTH) {
                height *= MAX_WIDTH / width;
                width = MAX_WIDTH;
            } else if (height > MAX_HEIGHT) {
                width *= MAX_HEIGHT / height;
                height = MAX_HEIGHT;
            }

            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            canvas.toBlob(function (blob) {
                if (blob.size > 2 * 1024 * 1024) {
                    alert('Ukuran masih lebih dari 2MB, silakan pilih foto lain');
                    input.value = '';
                    preview.style.display = 'none';
                    return;
                }

                const compressedFile = new File([blob], file.name, {
                    type: blob.type,
                    lastModified: Date.now()
                });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(compressedFile);
                input.files = dataTransfer.files;

                preview.src = canvas.toDataURL('image/jpeg', 0.7);
                preview.style.display = 'block';

            }, 'image/jpeg', 0.7);
        };

        img.src = e.target.result;
    };

    reader.readAsDataURL(file);
});
</script>
<script>
$(document).on('click', '.btn-detail', function () {

    $('#dTanggal').text($(this).data('tanggal'));
    $('#dOutlet').text($(this).data('outlet'));
    $('#dKategori').text($(this).data('kategori'));
    $('#dMetode').text($(this).data('metode'));
    $('#dNominal').text($(this).data('nominal'));
    $('#dKeterangan').text($(this).data('keterangan'));

    let bukti = $(this).data('bukti');

    if (bukti) {
        $('#dBukti').attr('src', bukti).show();
        $('#dBuktiKosong').hide();
    } else {
        $('#dBukti').hide();
        $('#dBuktiKosong').show();
    }
});
</script>
<script>
$(document).on('click', '.btn-bukti', function () {

    let img = $(this).data('img');

    if (img) {
        $('#imgBukti').attr('src', img).show();
        $('#buktiKosong').hide();
    } else {
        $('#imgBukti').hide();
        $('#buktiKosong').show();
    }
});
</script>
<!-- Modal Edit -->
<script>
$(document).on('click', '.btn-edit', function () {

    let id       = $(this).data('id');
    let kategori = $(this).data('kategori');
    let nominal  = $(this).data('nominal');
    let ket      = $(this).data('keterangan');
    let metode   = $(this).data('metode');
    let bukti    = $(this).data('bukti');

    $('#editKategori').val(kategori);
    $('#editNominal').val(nominal);
    $('#editKeterangan').val(ket);
    $('#editMetode').val(metode);

    // RESET INPUT & PREVIEW BARU
    $('#editBukti').val('');
    $('#wrapperBuktiBaru').hide();

    // BUKTI LAMA
    if (bukti) {
        $('#previewBuktiLama')
            .attr('src', '/storage/' + bukti)
            .show();
        $('#wrapperBuktiLama').show();
    } else {
        $('#wrapperBuktiLama').hide();
    }

    $('#formEditPengeluaran').attr('action', '/pengeluaran/edit/' + id);
});
</script>

<!-- Compress edit foto -->
<script>
$('#editBukti').on('change', function () {

    const input = this;
    const file  = input.files[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
        alert('File harus berupa gambar');
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {

        const img = new Image();
        img.onload = function () {

            const MAX_WIDTH  = 1280;
            const MAX_HEIGHT = 1280;

            let width  = img.width;
            let height = img.height;

            if (width > height && width > MAX_WIDTH) {
                height *= MAX_WIDTH / width;
                width = MAX_WIDTH;
            } else if (height > MAX_HEIGHT) {
                width *= MAX_HEIGHT / height;
                height = MAX_HEIGHT;
            }

            const canvas = document.createElement('canvas');
            canvas.width  = width;
            canvas.height = height;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            canvas.toBlob(function (blob) {

                if (blob.size > 2 * 1024 * 1024) {
                    alert('Ukuran foto masih lebih dari 2MB setelah kompres');
                    input.value = '';
                    $('#wrapperBuktiBaru').hide();
                    return;
                }

                // GANTI FILE DI INPUT (INI BAGIAN KRUSIAL)
                const compressedFile = new File([blob], file.name, {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(compressedFile);
                input.files = dataTransfer.files;

                // PREVIEW
                $('#previewBuktiBaru').attr(
                    'src',
                    URL.createObjectURL(blob)
                );
                $('#wrapperBuktiBaru').show();

            }, 'image/jpeg', 0.7); // quality 70%

        };

        img.src = e.target.result;
    };

    reader.readAsDataURL(file);
});
</script>

<script>
$('#editBukti').on('change', function () {

    const file = this.files[0];
    if (!file) {
        $('#wrapperBuktiBaru').hide();
        return;
    }

    if (!file.type.startsWith('image/')) {
        alert('File harus berupa gambar');
        this.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        $('#previewBuktiBaru').attr('src', e.target.result);
        $('#wrapperBuktiBaru').show();
    };

    reader.readAsDataURL(file);
});
</script>

<!-- Modal Pembatalan -->
 <script>
$(document).on('click', '.btn-batal', function () {
    const id = $(this).data('id');

    $('#formPembatalan').attr(
        'action',
        '{{ url("/pengeluaran/cancel") }}/' + id
    );
});
</script>


<!-- sweet alert -->
<script>
$(document).ready(function() {
    // optional: tampilkan toast setelah delete berdasarkan session flash
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses',
            text: '{{ session('success') }}',
            timer: 3000,
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