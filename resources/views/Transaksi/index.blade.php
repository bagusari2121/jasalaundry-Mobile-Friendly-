@extends('layouts.main')

@section('title', 'Transaksi Laundry') 

@section('content')
<style>
@media (max-width: 768px) {
    /* Sembunyikan Header Tabel di Mobile */
    #tableTransaksi thead {
        display: none;
    }

    /* Ubah Baris Tabel menjadi Kartu */
    #tableTransaksi tbody tr {
        display: block;
        border: 1px solid #e3e6f0;
        border-radius: 10px;
        margin-bottom: 15px;
        padding: 10px;
        background: #fff;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    /* Buat setiap kolom memenuhi lebar layar */
    #tableTransaksi tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: none;
        padding: 5px 0;
        width: 100% !important;
        text-align: left !important;
    }

    /* Tambahkan Label di sebelah kiri menggunakan data-label */
    #tableTransaksi tbody td::before {
        content: attr(data-label);
        font-weight: bold;
        color: #4e73df;
        margin-right: 10px;
    }

    /* Sesuaikan input agar lebar maksimal */
    .itemSelect, .harga, .qty, .subtotal {
        width: 60% !important;
        max-width: 200px;
    }

    /* Tombol hapus agar di pojok kanan atas kartu */
    .btn-hapus {
        width: 100%;
        margin-top: 10px;
    }
}
</style>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Transaksi Laundry</h1>

    <div class="row">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="m-0">Data Pelanggan</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="customer_id">Pilih Customer</label>
                        <div class="input-group">
                            <table width="100%">
                                <tr>
                                    <td width="80%"> 
                                        <select id="customer_id" class="select2 form-control">
                                            <option value="">-- Pilih Customer --</option>
                                            @foreach ($customer as $c)
                                                <option value="{{ $c->id }}" 
                                                        data-hp="{{ $c->telepon }}" 
                                                        data-is-langganan="{{ $c->is_langganan }}">
                                                    {{ $c->nama_customer }} - {{ $c->alamat }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td width="20%" class="text-right">
                                        <button type="button" class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#modalTambahCustomer">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="no_hp">No. HP</label>
                        <input type="text" id="no_hp" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="estimasi_selesai">Estimasi Selesai (Tanggal & Jam)</label>
                        <input type="datetime-local" id="estimasi_selesai" class="form-control" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-12">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white d-flex flex-wrap justify-content-between align-items-center py-2">
                    <h6 class="m-0">Detail Transaksi</h6>
                    <div class="mt-2 mt-sm-0">
                        <button class="btn btn-light btn-sm mb-1" id="btnTambahLayanan">
                            <i class="fas fa-plus"></i> Tambah Layanan
                        </button>
                        <button class="btn btn-light btn-sm mb-1" id="btnTambahProduk">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-sm" id="tableTransaksi">
                        <thead class="text-center bg-light">
                            <tr>
                                <th width="15%">Jenis</th>
                                <th>Nama Item</th>
                                <th width="15%">Harga</th>
                                <th width="10%">Qty</th>
                                <th width="15%">Subtotal</th>
                                <th width="5%">#</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="card-body">
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Gross Total: <strong id="totalDisplay">Rp 0</strong></h5>
                            <h5 class="mb-3 text-danger">Total Diskon: <strong id="diskonDisplay">Rp 0</strong></h5>
                            <hr>
                            <h4 class="mb-3">Grand Total (Net): <strong id="subTotalDisplay" class="text-success">Rp 0</strong></h4>
                            <input type="hidden" id="totalDiskon" value="0">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label>Bayar (Nominal)</label>
                                <input type="number" id="bayar" class="form-control form-control-lg border-primary" placeholder="Masukkan jumlah uang">
                            </div>
                            <div class="form-group mb-3">
                                <label>Kembalian</label>
                                <input type="text" id="kembalian" class="form-control font-weight-bold" value="Rp 0" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>Metode Pembayaran</label>
                                <select id="metode_pembayaran" class="form-control form-control-sm">
                                    <option value="Cash" selected>Cash</option>
                                    <option value="QRIS">QRIS</option>
                                    <option value="Transfer">Transfer</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>Status Pembayaran</label>
                                <select id="status_pembayaran" class="form-control form-control-sm">
                                    <option value="Belum Lunas">Belum Lunas</option>
                                    <option value="Lunas" selected>Lunas</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-3">
                                <label>Status Transaksi</label>
                                <select id="status_transaksi" class="form-control form-control-sm">
                                    <option value="Pesanan Masuk">Pesanan Masuk</option>
                                    <option value="Proses">Proses</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <button id="btnSimpanTransaksi" class="btn btn-primary btn-block btn-lg">
                        <i class="fas fa-save"></i> SIMPAN TRANSAKSI
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH CUSTOMER --}}
<div class="modal fade" id="modalTambahCustomer" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title">Tambah Customer Baru</h6>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('customer.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label>Nama Pelanggan</label>
                        <input type="text" name="nama_customer" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label>No. Telepon/WA</label>
                        <input type="text" name="telepon" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single { height: 38px !important; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 38px !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function(){
    const layananList = @json($layanan);
    const produkList = @json($stok_outlet);

    $('.select2').select2({ width: '100%' });

    $('#customer_id').on('change', function() {
        let noHp = $(this).find(':selected').data('hp') || '-';
        $('#no_hp').val(noHp);
    });

    function hitungTotal() {
        let grandGross = 0;
        let grandDiskon = 0;
        let grandNet = 0;

        $('#tableTransaksi tbody tr').each(function() {
            const row = $(this);
            const harga = parseFloat(row.find('.itemSelect :selected').data('harga') || 0);
            const diskonPersen = parseFloat(row.find('.itemSelect :selected').data('diskon-persen') || 0);
            const qty = parseFloat(row.find('.qty').val() || 0);

            const gross = harga * qty;
            const diskonRupiah = gross * (diskonPersen / 100);
            const net = gross - diskonRupiah;

            row.find('.harga').val(harga);
            row.find('.subtotal').val(net);
            row.find('.diskon-info').text(`Diskon: ${diskonPersen}%`);

            grandGross += gross;
            grandDiskon += diskonRupiah;
            grandNet += net;
        });

        $('#totalDisplay').text('Rp ' + grandGross.toLocaleString('id-ID'));
        $('#diskonDisplay').text('Rp ' + grandDiskon.toLocaleString('id-ID'));
        $('#subTotalDisplay').text('Rp ' + grandNet.toLocaleString('id-ID'));
        $('#totalDiskon').val(grandDiskon);

        hitungKembalian(grandNet);
        return grandNet;
    }

    function hitungKembalian(totalTagihan = null) {
        if(totalTagihan === null) {
            let txt = $('#subTotalDisplay').text().replace(/[^\d]/g, '');
            totalTagihan = parseFloat(txt || 0);
        }
        
        let bayar = parseFloat($('#bayar').val() || 0);
        let kembali = bayar - totalTagihan;
        
        $('#kembalian').val('Rp ' + (kembali > 0 ? kembali.toLocaleString('id-ID') : 0));
        
        if (kembali < 0 && bayar > 0) {
            $('#kembalian').addClass('text-danger');
            $('#bayar').addClass('is-invalid');
        } else {
            $('#kembalian').removeClass('text-danger');
            $('#bayar').removeClass('is-invalid');
        }
    }

    function addRow(type, list, namaKey, hargaKey) {
        let options = list.map(item => 
            `<option value="${item.id}" data-harga="${item[hargaKey]}" data-diskon-persen="${item.diskon || 0}">
                ${item[namaKey]}
            </option>`
        ).join('');

        let row = `
            <tr>
                <td class="text-center"><span class="badge badge-info">${type}</span></td>
                <td>
                    <select class="form-control form-control-sm itemSelect">${options}</select>
                    <small class="text-success diskon-info">Diskon: 0%</small>
                </td>
                <td><input type="number" class="form-control form-control-sm harga" readonly></td>
                <td><input type="number" class="form-control form-control-sm qty" value="1" min="0.1" step="0.1" width="10%"></td>
                <td><input type="number" class="form-control form-control-sm subtotal" readonly></td>
                <td class="text-center">
                    <button class="btn btn-danger btn-sm btn-hapus"><i class="fas fa-trash"></i></button>
                </td>
            </tr>`;
        
        $('#tableTransaksi tbody').append(row);
        hitungTotal();
    }

    $('#btnTambahLayanan').click(() => addRow('Layanan', layananList, 'nama_layanan', 'harga'));
    $('#btnTambahProduk').click(() => addRow('Produk', produkList, 'nama_produk', 'harga_jual'));

    $(document).on('change', '.itemSelect, .qty', hitungTotal);
    $(document).on('click', '.btn-hapus', function() {
        $(this).closest('tr').remove();
        hitungTotal();
    });
    $('#bayar').on('input', () => hitungKembalian());

    // --- SIMPAN DATA ---
    $('#btnSimpanTransaksi').click(function() {
        const totalNet = parseFloat($('#subTotalDisplay').text().replace(/[^\d]/g, '') || 0);
        const nominalBayar = parseFloat($('#bayar').val() || 0);

        const dataMaster = {
            id_customer: $('#customer_id').val(),
            metode_pembayaran: $('#metode_pembayaran').val(),
            status_pembayaran: $('#status_pembayaran').val(),
            status_transaksi: $('#status_transaksi').val(),
            estimasi_selesai: $('#estimasi_selesai').val(),
            jumlah_bayar: nominalBayar,
            total_diskon: $('#totalDiskon').val(),
            total: totalNet,
            _token: "{{ csrf_token() }}"
        };

        if (!dataMaster.id_customer) return Swal.fire('Eits!', 'Pilih customer dulu ya.', 'warning');
        if ($('#tableTransaksi tbody tr').length === 0) return Swal.fire('Kosong!', 'Tambahkan minimal 1 item.', 'warning');
        if (!dataMaster.estimasi_selesai) return Swal.fire('Waktu!', 'Estimasi selesai harus diisi.', 'warning');

        if (nominalBayar < totalNet) {
            return Swal.fire({
                icon: 'error',
                title: 'Uang Kurang!',
                text: 'Nominal bayar tidak boleh lebih kecil dari Grand Total.',
            });
        }

        let items = [];
        $('#tableTransaksi tbody tr').each(function() {
            const row = $(this);
            const selectedOption = row.find('.itemSelect :selected'); 
            
            items.push({
                jenis: row.find('td:first').text().trim(),
                id_item: row.find('.itemSelect').val(),
                qty: row.find('.qty').val(),
                harga: row.find('.harga').val(),
                diskon_persen: selectedOption.data('diskon-persen') || 0, 
                subtotal: row.find('.subtotal').val()
            });
        });

        $.ajax({
            url: "{{ route('transaksi.store') }}",
            type: "POST",
            data: { ...dataMaster, items: items },
            success: function(res) {
                if (res.success) {
                    const d = res.data; 
                    const lebar = 32; 

                    // --- Helper Fungsi Agar Semua Rapi ---

                    // 1. Teks di Tengah
                    const center = (t) => {
                        let s = Math.floor((lebar - t.length) / 2);
                        return " ".repeat(s > 0 ? s : 0) + t + "\n";
                    };

                    // 2. Info Transaksi (Titik dua di karakter ke-11, Nilai di kiri)
                    const rowLurus = (l, v) => {
                        const titikDuaDi = 11; 
                        let label = l.substring(0, titikDuaDi);
                        let spasiLabel = titikDuaDi - label.length;
                        return label + " ".repeat(spasiLabel) + ": " + v + "\n";
                    };

                    // 3. Info Harga (Titik dua di karakter ke-11, Nilai rata kanan)
                    const rowHargaLurus = (l, v) => {
                        const titikDuaDi = 11;
                        const lebarTotal = 32;
                        let label = l.substring(0, titikDuaDi);
                        let spasiLabel = titikDuaDi - label.length;
                        let labelPart = label + " ".repeat(spasiLabel) + ": "; // Total 13 karakter
                        
                        let sisaLebar = lebarTotal - labelPart.length; // 19 karakter
                        let valuePart = v.toString();
                        let spasiHarga = sisaLebar - valuePart.length;
                        
                        return labelPart + " ".repeat(spasiHarga > 0 ? spasiHarga : 0) + valuePart + "\n";
                    };

                    const boldOn = "\x1B\x45\x01";
                    const boldOff = "\x1B\x45\x00";

                    // --- 1. DATA HEADER ---
                    let headerUtama = center("{{ Auth::user()->outlet->nama_outlet }}");
                    headerUtama += center("{{ Auth::user()->outlet->alamat }}");
                    headerUtama += center("Telp : {{ Auth::user()->outlet->telepon }}");
                    headerUtama += "--------------------------------\n";

                    // --- 2. DATA ITEM & TOTAL (SHARED) ---
                    let detailBelanja = "";
                    let totalGrossPrint = 0;
                    d.items.forEach(item => {
                        detailBelanja += item.nama_produk.substring(0, 32) + "\n";
                        let qtyHarga = item.qty + "x " + parseInt(item.harga).toLocaleString('id-ID');
                        let sub = parseInt(item.subtotal).toLocaleString('id-ID');
                        
                        detailBelanja += rowHargaLurus(qtyHarga, sub);
                        totalGrossPrint += (parseFloat(item.qty) * parseFloat(item.harga));
                    });

                    detailBelanja += "--------------------------------\n";
                    let nTotalNet = parseFloat(d.total || 0);
                    let nTotalDiskon = parseFloat(d.total_diskon || 0);
                    let nBayar = parseInt(d.bayar || 0);
                    let nKembali = nBayar - nTotalNet;

                    detailBelanja += rowHargaLurus("Sub Total", totalGrossPrint.toLocaleString('id-ID'));
                    detailBelanja += rowHargaLurus("Potongan", nTotalDiskon.toLocaleString('id-ID'));
                    detailBelanja += rowHargaLurus("Total", nTotalNet.toLocaleString('id-ID'));
                    detailBelanja += rowHargaLurus("Bayar", nBayar.toLocaleString('id-ID'));
                    detailBelanja += rowHargaLurus("Kembali", (nKembali > 0 ? nKembali.toLocaleString('id-ID') : "0"));
                    detailBelanja += "--------------------------------\n";

                    // --- 3. SUSUN COPY PELANGGAN ---
                    let strukPelanggan = center("");
                    strukPelanggan += headerUtama;
                    strukPelanggan += rowLurus("Kode", d.kode_transaksi);
                    strukPelanggan += rowLurus("Customer", d.customer.substring(0, 18));
                    strukPelanggan += rowLurus("Est.Selesai", d.estimasi_selesai);
                    strukPelanggan += rowLurus("Pembayaran", d.metode_pembayaran);
                    strukPelanggan += rowLurus("Status", d.status_pembayaran);
                    strukPelanggan += rowLurus("Tanggal", d.tanggal);
                    strukPelanggan += "--------------------------------\n";
                    strukPelanggan += detailBelanja;
                    strukPelanggan += center("Terima kasih sudah mencuci!");
                    strukPelanggan += center("~ Semoga puas ~");

                    // --- 4. SUSUN COPY TOKO ---
                    let strukToko = center("--- COPY TOKO ---");
                    strukToko += headerUtama;
                    // Bagian yang dibold (KODE, CUSTOMER, ESTIMASI)
                    strukToko += boldOn + rowLurus("KODE", d.kode_transaksi) + boldOff;
                    strukToko += boldOn + rowLurus("CUSTOMER", d.customer.substring(0, 18)) + boldOff;
                    strukToko += boldOn + rowLurus("EST.SELESAI", d.estimasi_selesai) + boldOff;
                    // Bagian normal (PEMBAYARAN, STATUS, TANGGAL)
                    strukToko += rowLurus("Pembayaran", d.metode_pembayaran);
                    strukToko += rowLurus("Status", d.status_pembayaran);
                    strukToko += rowLurus("Tanggal", d.tanggal);
                    strukToko += "--------------------------------\n";
                    strukToko += detailBelanja;
                    // Tanpa footer sesuai permintaan

                    // --- 5. GABUNG DAN KIRIM KE PRINTER ---
                    let jarakPotong = "\n\n\n\n\n"; 
                    let strukFinal = strukPelanggan + jarakPotong + strukToko + "\n\n\n\n";

                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Transaksi tersimpan. Cetak struk?',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Cetak (Bluetooth)',
                        cancelButtonText: 'Tutup'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            try {
                                let base64Data = btoa(unescape(encodeURIComponent(strukFinal)));
                                window.location.href = "rawbt:base64," + base64Data;
                            } catch (e) {
                                console.error("Encoding error:", e);
                            }
                        }
                        setTimeout(() => {
                            window.location.href = "/transaksi/nota/" + d.id_transaksi;
                        }, 1200);
                    });
                }
            },
            error: function(xhr) {
                let errorMsg = xhr.responseJSON ? xhr.responseJSON.message : xhr.responseText;
                Swal.fire('Gagal!', errorMsg, 'error');
            }
        });
    });
});
</script>
<script>
    $(document).ready(function() {
    function formatCustomer(state) {
        if (!state.id) {
            return state.text;
        }

        // Ambil data langganan dari attribute option
        var isLangganan = $(state.element).data('is-langganan');
        
        // Jika langganan, tambahkan icon FontAwesome bintang emas
        var icon = '';
        if (isLangganan == 1) {
            icon = '<i class="fas fa-star text-warning mr-1"></i> ';
        }

        var $state = $(
            '<span>' + icon + state.text + '</span>'
        );
        return $state;
    }

    $('#customer_id').select2({
        templateResult: formatCustomer,
        templateSelection: formatCustomer,
        escapeMarkup: function(m) {
            return m; // Agar HTML (icon) tidak di-escape/diabaikan
        }
    });
});
</script>
@endpush