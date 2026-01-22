@extends('layouts.main')

@section('title', 'Detail Data Transaksi Laundry')

@push('styles')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<style>
    /* Agar tampilan badge lebih proporsional di mobile */
    @media (max-width: 768px) {
        .badge { font-size: 0.8rem; padding: 10px !important; }
        .btn { margin-bottom: 5px; width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Detail Transaksi Laundry</h1>
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-receipt mr-2"></i>Informasi Transaksi
            </h6>
        </div>

        <div class="card-body">

            {{-- Info utama transaksi --}}
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Kode Transaksi:</strong> {{ $transaksi->kode_transaksi }}</p>
                    <p><strong>Nama Customer:</strong> {{ $transaksi->customer->nama_customer }}</p>
                    <p><strong>Tanggal Transaksi:</strong> {{ $transaksi->tanggal_transaksi }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Outlet:</strong> {{ $transaksi->outlet->nama_outlet }}</p>
                    <p><strong>PIC:</strong> {{ $transaksi->user->name }}</p>
                    <p><strong>Pembayaran:</strong> {{ $transaksi->metode_pembayaran }} | {{ $transaksi->status_pembayaran }}</p>
                </div>
            </div>

            {{-- Status transaksi --}}
            <div class="mb-4">
                <strong>Status Transaksi:</strong>
                @if($transaksi->status_transaksi == 'Pesanan Masuk')
                    <span class="badge bg-primary px-3 py-3 text-white">{{ $transaksi->status_transaksi }}</span>
                @elseif($transaksi->status_transaksi == 'Proses')
                    <span class="badge bg-warning text-dark px-3 py-3">{{ $transaksi->status_transaksi }}</span>
                @elseif($transaksi->status_transaksi == 'Selesai')
                    <span class="badge bg-success px-3 py-3 text-white">{{ $transaksi->status_transaksi }}</span>
                @elseif($transaksi->status_transaksi == 'Diambil')
                    <span class="badge bg-danger px-3 py-3 text-white">{{ $transaksi->status_transaksi }}</span>
                @elseif($transaksi->status_transaksi == 'Cancelled')
                    <span class="badge bg-danger px-3 py-3 text-white">{{ $transaksi->status_transaksi }}</span>
                @endif
                {{-- Tombol ubah status --}}
                @if($transaksi->status_transaksi !== 'Diambil' && $transaksi->status_transaksi != 'Cancelled')
                    <button class="btn btn-warning" data-toggle="modal" data-target="#modalUbahStatus">
                        <i class="fas fa-edit"></i> Ubah Status
                    </button>
                @endif
                {{-- Tombol Cetak Nota (Lama: <a>, Baru: <button>) --}}
                <button class="btn btn-primary" id="btnCetakRawBT">
                    <i class="fas fa-print mr-2"></i>Cetak Nota (Mobile)
                </button>

                {{-- Tetap sediakan tombol cetak standar untuk PC jika perlu --}}
                <a class="btn btn-secondary" href="/transaksi/nota/{{$transaksi->id}}" target="_blank">
                    <i class="fas fa-desktop mr-2"></i>Nota (PC)
                </a>
                @if($transaksi->status_transaksi != 'Request Dihapus' && $transaksi->status_transaksi != 'Cancelled')
                    <button class="btn btn-danger" data-toggle="modal" data-target="#modalHapusTransaksi"><i class="fas fa-trash"></i> Hapus</button>
                @endif
            </div>

            {{-- Detail item --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTableUser" width="100%" cellspacing="0">
                    <thead class="thead-dark text-center">
                        <tr>
                            <th width="5%">No.</th>
                            <th>Nama Produk / Layanan</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th class="text-right">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($details as $detail)
                        <tr">
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-left">{{ $detail->nama_produk }}</td>
                            <td>{{ $detail->jenis }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                            <td class="text-right">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="font-weight-bold">
                        <tr>
                            <td colspan="5" class="text-center">Total</td>
                            <td class="text-right">Rp{{ number_format($total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</div>
<!-- Modal Ubah Status -->
<div class="modal fade" id="modalUbahStatus" tabindex="-1" role="dialog" aria-labelledby="modalUbahStatusLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title font-weight-bold" id="modalUbahStatusLabel">Ubah Status Pesanan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{ route('transaksi.updateStatus', $transaksi->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label for="status_transaksi" class="font-weight-bold">Pilih Status Baru</label>
            <select name="status_transaksi" id="status_transaksi" class="form-control" required>
              <option value="">-- Pilih Status --</option>
              <option value="Pesanan Masuk" {{ $transaksi->status_transaksi == 'Pesanan Masuk' ? 'selected' : '' }}>Pesanan Masuk</option>
              <option value="Proses" {{ $transaksi->status_transaksi == 'Proses' ? 'selected' : '' }}>Proses</option>
              <option value="Selesai" {{ $transaksi->status_transaksi == 'Selesai' ? 'selected' : '' }}>Selesai</option>
              <option value="Diambil" {{ $transaksi->status_transaksi == 'Diambil' ? 'selected' : '' }}>Diambil</option>
            </select>
          </div>
          <div class="form-group">
            <label for="status_pembayaran" class="font-weight-bold">Pilih Status Pembayaran Baru</label>
            <select name="status_pembayaran" id="status_pembayaran" class="form-control" required>
              <option value="">-- Pilih Status --</option>
              <option value="Lunas" {{ $transaksi->status_pembayaran == 'Lunas' ? 'selected' : '' }}>Lunas</option>
              <option value="Belum Lunas" {{ $transaksi->status_pembayaran == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
            </select>
          </div>
          <div class="form-group">
            <label for="metode_pembayaran" class="font-weight-bold">Pilih Metode Pembayaran Baru</label>
            <select name="metode_pembayaran" id="metode_pembayaran" class="form-control" required>
              <option value="">-- Pilih Metode Pembayaran --</option>
              <option value="Cash" {{ $transaksi->metode_pembayaran == 'Cash' ? 'selected' : '' }}>Cash</option>
              <option value="QRIS" {{ $transaksi->metode_pembayaran == 'QRIS' ? 'selected' : '' }}>QRIS</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Hapus Transaksi -->
<div class="modal fade" id="modalHapusTransaksi" tabindex="-1" role="dialog" aria-labelledby="modalUbahStatusLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title font-weight-bold" id="modalUbahStatusLabel">Hapus Transaksi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('transaksi.request_delete', $transaksi->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label for="status_transaksi" class="font-weight-bold">Alasan Penghapusan</label>
            <textarea class="form-control" name="alasan" id="alasan" placeholder="Masukkan Alasan Penghapusan"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Simpan Perubahan</button>
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
$(document).ready(function() {
    $('#dataTableUser').DataTable({
        stateSave: true,
        ordering: false,
        searching: false,
        paging: false,
        info: false
    });

    // --- LOGIKA CETAK RAWBT ---
    $('#btnCetakRawBT').click(function() {
        const lebar = 32;
        const garis = "-".repeat(lebar) + "\n";
        
        // Command Bold untuk Printer Thermal
        const boldOn = "\x1B\x45\x01";
        const boldOff = "\x1B\x45\x00";

        // --- Helper Fungsi ---
        const center = (t) => {
            let s = Math.floor((lebar - t.length) / 2);
            return " ".repeat(s > 0 ? s : 0) + t + "\n";
        };

        // Fungsi agar titik dua lurus di karakter ke-11
        const rowLurus = (l, v, isBold = false) => {
            const titikDuaDi = 11; 
            let label = l.substring(0, titikDuaDi);
            let spasiLabel = titikDuaDi - label.length;
            let hasil = label + " ".repeat(spasiLabel) + ": " + v + "\n";
            return isBold ? boldOn + hasil + boldOff : hasil;
        };

        // Fungsi harga: titik dua lurus & angka rata kanan
        const rowHargaLurus = (l, v) => {
            const titikDuaDi = 11;
            let label = l.substring(0, titikDuaDi);
            let spasiLabel = titikDuaDi - label.length;
            let labelPart = label + " ".repeat(spasiLabel) + ": "; // Total 13 karakter
            
            let sisaLebar = lebar - labelPart.length; // 19 karakter
            let valuePart = v.toString();
            let spasiHarga = sisaLebar - valuePart.length;
            
            return labelPart + " ".repeat(spasiHarga > 0 ? spasiHarga : 0) + valuePart + "\n";
        };

        // Tarik data dari Blade/PHP
        const t = @json($transaksi);
        const d = @json($details);
        const outlet = @json($transaksi->outlet);

        // --- MULAI SUSUN STRUK ---
        let struk = center(outlet.nama_outlet);
        struk += center(outlet.alamat || "");
        struk += center(outlet.telepon || "");
        struk += garis;

        // Bagian Info (Bold pada Kode, Tanggal, dan Estimasi)
        struk += rowLurus("Kode", t.kode_transaksi, true);
        struk += rowLurus("Tanggal", t.tanggal_transaksi, true);
        // Asumsi field estimasi adalah t.estimasi_selesai
        struk += rowLurus("Estimasi", t.estimasi_selesai || "-", true); 
        struk += rowLurus("Cust", t.customer.nama_customer.substring(0, 18));
        struk += rowLurus("Bayar", t.metode_pembayaran);
        struk += rowLurus("Status", t.status_pembayaran);
        struk += garis;

        let totalGrossManual = 0;
        d.forEach(item => {
            struk += item.nama_produk.substring(0, 32) + "\n";
            let qh = item.qty + "x " + parseInt(item.harga).toLocaleString('id-ID');
            let sub = parseInt(item.subtotal).toLocaleString('id-ID');
            // Gunakan rowHargaLurus agar titik dua item juga sejajar
            struk += rowHargaLurus(qh, sub);
            totalGrossManual += (parseFloat(item.qty) * parseFloat(item.harga));
        });

        let totalNet = parseFloat(t.total_transaksi);
        let totalDiskonRp = totalGrossManual - totalNet;

        struk += garis;
        struk += rowHargaLurus("Total Gross", totalGrossManual.toLocaleString('id-ID'));
        struk += rowHargaLurus("Diskon (Rp)", totalDiskonRp.toLocaleString('id-ID'));
        struk += rowHargaLurus("Total Akhir", totalNet.toLocaleString('id-ID'));
        struk += rowHargaLurus("Bayar", parseInt(t.jumlah_bayar || 0).toLocaleString('id-ID'));
        
        let kembali = (parseInt(t.jumlah_bayar || 0) - totalNet);
        struk += rowHargaLurus("Kembali", (kembali > 0 ? kembali.toLocaleString('id-ID') : "0"));

        struk += garis;
        struk += center("Terima kasih!");
        struk += "\n\n\n\n";

        // Kirim ke RawBT dengan encoding yang aman
        try {
            let base64Data = btoa(unescape(encodeURIComponent(struk)));
            window.location.href = "rawbt:base64," + base64Data;
        } catch (e) {
            console.error("Encoding error:", e);
        }
    });
});
</script>
@endpush