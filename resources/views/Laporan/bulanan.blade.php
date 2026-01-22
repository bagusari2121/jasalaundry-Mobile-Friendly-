@extends('layouts.main')

@section('title', 'Laporan Bulanan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800 text-center">Laporan Penghasilan Bulanan</h1>

    <div class="d-flex justify-content-center mb-3">
        <div class="form-inline">
            <label class="mr-2">Tahun:</label>
            <select id="tahun" class="form-control mr-2">
                @for ($i = 2023; $i <= date('Y') + 1; $i++)
                    <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
            <button id="btnFilter" class="btn btn-primary">Tampilkan</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered" id="laporanTable">
            <thead class="thead-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Bulan</th>
                    <th>QRIS</th>
                    <th>Cash</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody></tbody>
            <tfoot class="text-center font-weight-bold bg-light">
                <tr>
                    <td colspan="2">Total Keseluruhan</td>
                    <td id="sumQris">Rp 0</td>
                    <td id="sumCash">Rp 0</td>
                    <td id="sumTotal">Rp 0</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", () => {
    const btnFilter = document.getElementById("btnFilter");
    const tahunSelect = document.getElementById("tahun");

    const loadData = () => {
        const tahun = tahunSelect.value;
        fetch(`{{ route('laporan.bulanan.data') }}?tahun=${tahun}`)
            .then(res => res.json())
            .then(data => tampilkanTabel(data))
            .catch(err => console.error(err));
    };

    const tampilkanTabel = (data) => {
        const tbody = document.querySelector("#laporanTable tbody");
        tbody.innerHTML = "";
        let sumQris = 0, sumCash = 0, sumTotal = 0;

        data.forEach(row => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td class="text-center">${row.no}</td>
                <td>${row.bulan}</td>
                <td class="text-right">Rp ${row.qris.toLocaleString("id-ID")}</td>
                <td class="text-right">Rp ${row.cash.toLocaleString("id-ID")}</td>
                <td class="text-right">Rp ${row.total.toLocaleString("id-ID")}</td>
            `;
            tbody.appendChild(tr);

            sumQris += parseFloat(row.qris);
            sumCash += parseFloat(row.cash);
            sumTotal += parseFloat(row.total);
        });

        document.getElementById("sumQris").textContent = "Rp " + sumQris.toLocaleString("id-ID");
        document.getElementById("sumCash").textContent = "Rp " + sumCash.toLocaleString("id-ID");
        document.getElementById("sumTotal").textContent = "Rp " + sumTotal.toLocaleString("id-ID");
    };

    btnFilter.addEventListener("click", loadData);
    loadData(); // load pertama
});
</script>
@endpush
