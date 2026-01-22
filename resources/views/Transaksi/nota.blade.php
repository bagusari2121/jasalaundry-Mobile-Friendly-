<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 58mm;
            margin: 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .line { border-top: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }
    </style>
</head>
<body onload="window.print(); setTimeout(() => window.close(), 2000);">

    <div class="text-center">
        <strong>Jasa Laundry</strong><br>
        {{$user->outlet->nama_outlet}}<br>
        {{$user->outlet->alamat}}<br>
        {{$user->outlet->telepon}}
    </div>

    <div class="line"></div>

    <table>
        <tr>
            <td>Kode</td>
            <td>:{{ $transaksi->kode_transaksi }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:{{ \Carbon\Carbon::parse($transaksi->created_at)->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td>:{{ $transaksi->customer->nama_customer }}</td>
        </tr>
        <tr>
            <td>Telepon</td>
            <td>:{{ $transaksi->customer->telepon }}</td>
        </tr>
        <tr>
            <td>Pembayaran</td>
            <td>:{{ $transaksi->metode_pembayaran }}</td>
        </tr>
        <tr>
            <td>Status Pembayaran</td>
            <td>:{{ $transaksi->status_pembayaran }}</td>
        </tr>
        <tr>
            <td>Estimasi Selesai</td>
            <td>:{{ \Carbon\Carbon::parse($transaksi->estimasi_selesai)->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <table>
        @foreach($details as $d)
        <tr>
            <td>{{ $d->qty }}x</td>
            <td>{{ $d->nama_produk }}</td>
            <td class="text-right">{{ number_format($d->harga, 0, ',', '.') }}</td>
            <td class="text-right">{{ number_format($d->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <table>
        <table>
    <tr>
        <td><strong>Sub Total</strong></td>
        {{-- Menjumlahkan kolom 'harga' (Gross Price per unit) dikalikan 'qty' dari setiap detail. --}}
        {{-- Jika kamu menyimpan total Gross di kolom 'subtotal' di detail, gunakan $d->subtotal. --}}
        {{-- Jika 'subtotal' di detail adalah NET, kita perlu hitung ulang Gross Total --}}
        {{-- Kita akan asumsikan kamu ingin menjumlahkan semua Subtotal yang ada di detail (ini harusnya Total NET) --}}
        <td>:</td>
        {{-- Solusi 1: Menjumlahkan kolom 'subtotal' dari detail (Ini adalah Total NET per baris) --}}
        {{-- $details adalah Collection, jadi gunakan method ->sum() --}}
        <td class="text-right"><strong>{{ number_format(
                        $details->sum(function($d) {
                            return $d->harga * $d->qty;
                        }), 0, ',', '.')
                    }}</strong></td>
    </tr>
    <tr>
        <td>Diskon (Rp)</td>
        <td>:</td>
        {{-- Ambil diskon total dari kolom baru di tabel transaksi --}}
        <td class="text-right">{{ number_format(
    $details->sum(function($d) {
        // Rumus: Diskon_Rp = Diskon_Persen / 100 * Qty * Harga_Gross
        return $d->diskon / 100 * $d->qty * $d->harga;
    }), 0, ',', '.') 
}}</td>
    </tr>
    <tr>
        <td><strong>Total</strong></td>
        <td>:</td>
        {{-- Total Akhir adalah total_transaksi yang sudah disimpan di DB --}}
        <td class="text-right"><strong>{{ number_format($transaksi->total_transaksi, 0, ',', '.') }}</strong></td>
    </tr>
        <tr>
            <td>Bayar</td>
            <td>:</td>
            <td class="text-right">{{ number_format($transaksi->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembali</td>
            <td>:</td>
            <td class="text-right">{{ number_format(($transaksi->jumlah_bayar ?? 0) - $transaksi->total_transaksi, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="text-center">
        <em>Terima kasih sudah mencuci di sini!</em><br>
        ~ Semoga puas dengan layanan kami ~
    </div>

</body>
</html>
