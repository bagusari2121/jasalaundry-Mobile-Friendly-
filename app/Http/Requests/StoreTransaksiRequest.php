<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransaksiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_customer'       => 'required|exists:tm_customer,id',
            'estimasi_selesai'  => 'required',
            'total'             => 'required|numeric',
            'metode_pembayaran' => 'required|in:Cash,QRIS,Transfer,Lain-Lain',
            'status_pembayaran' => 'required',
            'status_transaksi'  => 'required',
            'items'             => 'required|array|min:1', // Pastikan items adalah array
            'items.*.id_item'   => 'required',             // Validasi tiap id di dalam array
            'items.*.qty'       => 'required|numeric|min:0.1',
            'items.*.harga'     => 'required|numeric',
        ];
    }

    protected function prepareForValidation()
    {
    }
}
