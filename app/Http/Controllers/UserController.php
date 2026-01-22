<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\tm_outlet;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        $user = Auth::user();
        $isOwner = $user->role === 'Owner';
        $data_user = User::when(!$isOwner, function ($query) use ($user) {
            return $query->where('id_outlet', $user->id_outlet);
                            })->orderBy('name','asc')->get();
        $outlet = tm_outlet::orderBy('nama_outlet','asc')->get();
        $o = tm_outlet::orderBy('nama_outlet','asc')->get();
        // dd($user);

        return view('User.index',compact('user','data_user','outlet','o'));
    }
    
    public function store(Request $request){
        // validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'telepon' => 'required',
            'role' => 'required',
            'id_outlet' => 'required',
        ]);
        $id_baru = User::orderBy('id', 'desc')->first();
        $id = $id_baru->id + 1;
        // dd($id);
        // simpan data user baru
        User::create([
            'id' => $id,
            'name' => $request->name,
            'email' => $request->email,
            'no_telepon' => $request->telepon,
            'role' => $request->role,
            'id_outlet' => $request->id_outlet,
            'password' => Hash::make('jasalaundry2025'), // password default
        ]);

        return redirect()->back()->with('success', 'User baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'telepon' => 'required',
            'role' => 'required',
            'id_outlet' => 'required',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'no_telepon' => $request->telepon,
            'role' => $request->role,
            'id_outlet' => $request->id_outlet,
        ]);

        return redirect()->back()->with('success', 'Data user berhasil diperbarui!');
    }


    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}
