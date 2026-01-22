@extends('layouts.main')

@section('title', 'Laporan Pendapatan Harian')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Laporan Pendapatan Harian</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <div>
                <label class="mr-2 font-weight-bold">Bulan:</label>
                <select id="bulan" class="form-control d-inline-block w-auto">
                    @foreach(range(1,12) as $i)
                        <option value="{{ $i }}" {{ $i == date('n') ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>

                <label class="ml-3 mr-2 font-weight-bold">Tahun:</label>
                <select id="tahun" class="form-control d-inline-block w-auto">
                    @for($i = date('Y'); $i >= 2020; $i--)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>

                <button id="btnFilter" class="btn btn-primary ml-3">Tampilkan</button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tabelLaporan">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>QRIS (Rp)</th>
                            <th>Cash (Rp)</th>
                            <th>Total (Rp)</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot class="bg-light font-weight-bold">
                        <tr>
                            <td colspan="2" class="text-right">Total Bulan Ini</td>
                            <td id="totalQris">0</td>
                            <td id="totalCash">0</td>
                            <td id="totalSemua">0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('btnFilter').addEventListener('click', function() {
    const bulan = document.getElementById('bulan').value;
    const tahun = document.getElementById('tahun').value;

    fetch(`/laporan/pendapatan?bulan=${bulan}&tahun=${tahun}`)
        .then(response => response.json())
        .then(data => {
            const tbody = document.querySelector('#tabelLaporan tbody');
            tbody.innerHTML = '';

            let totalQris = 0, totalCash = 0, totalAll = 0;

            data.forEach(item => {
                totalQris += item.qris;
                totalCash += item.cash;
                totalAll += item.total;

                const row = `
                    <tr>
                        <td>${item.no}</td>
                        <td>${item.tanggal}</td>
                        <td>${item.qris.toLocaleString('id-ID')}</td>
                        <td>${item.cash.toLocaleString('id-ID')}</td>
                        <td>${item.total.toLocaleString('id-ID')}</td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });

            document.getElementById('totalQris').innerText = totalQris.toLocaleString('id-ID');
            document.getElementById('totalCash').innerText = totalCash.toLocaleString('id-ID');
            document.getElementById('totalSemua').innerText = totalAll.toLocaleString('id-ID');
        });
});

// Load otomatis saat pertama kali
document.getElementById('btnFilter').click();
</script>
@endpush
