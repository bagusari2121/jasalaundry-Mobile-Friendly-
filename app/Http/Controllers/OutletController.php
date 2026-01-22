<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\tm_outlet;


class OutletController extends Controller
{
    public function index(){
        $user = Auth::User();
        $outlet = tm_outlet::orderBy('nama_outlet','asc')->get();
        return view('Outlet.index',compact('user','outlet'));
    }

    public function store(Request $request){
        $request->validate([
            'nama_outlet' => 'required',
            'alamat' => 'required',
            'telepon' => 'nullable',
        ]);

        tm_outlet::create([
            'nama_outlet' => $request->nama_outlet,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
        ]);

        return redirect()->route('outlet')->with('success', 'Outlet berhasil ditambahkan!');
    }

    public function edit($id)
    {   
        $user = Auth::User();
        $outlet = tm_outlet::findOrFail($id);
        return view('Outlet.edit', compact('outlet','user'));
    }

    public function update(Request $request, $id)
    {
        // 1️⃣ Validasi data
        $request->validate([
            'nama_outlet' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'nullable|string|max:20',
        ]);

        // 2️⃣ Ambil outlet berdasarkan id
        $outlet = tm_outlet::findOrFail($id);

        // 3️⃣ Update data
        $outlet->update([
            'nama_outlet' => $request->nama_outlet,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
        ]);

        // 4️⃣ Redirect + SweetAlert sukses
        return redirect()
            ->route('outlet')
            ->with('success', 'Data outlet berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            $outlet = tm_outlet::findOrFail($id);
            $outlet->delete();
            return redirect()->route('outlet')->with('success', 'Outlet berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus outlet.');
        }
    }

}