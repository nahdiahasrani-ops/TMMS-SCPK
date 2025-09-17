<?php

namespace App\Http\Controllers;

use App\Models\DataKaryawan;
use Illuminate\Http\Request;

class DataKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $role = $request->query('role'); // Ambil dari ?role=...

        $DataKaryawan = DataKaryawan::when($role, function ($query, $role) {
            return $query->where('role', $role);
        })->get();
    
        return view('DataKaryawan.index', compact('DataKaryawan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_karyawan' => 'required',
            'role' => 'required|in:Operator,Mekanik,HSSE',
            'masa_kerja' => 'required',
            
        ]);

        DataKaryawan::create([
            'nama_karyawan' => $request->nama_karyawan,
            'role' => $request->role, // otomatis isi 'operator'
            'masa_kerja' => $request->masa_kerja,
            
        ]);

        return redirect('/DataKaryawan');
        
    }

    public function edit($id)
    {
        $DataKaryawan = DataKaryawan::findOrFail($id);
        return view('DataKaryawan.edit', compact('DataKaryawan'));
    }

    public function update($id, Request $request)
    {
        $DataKaryawan = DataKaryawan::findOrFail($id);
        $DataKaryawan->update($request->except(['_token','submit']));
        return redirect('/DataKaryawan');
        
    }
    public function destroy($id)
    {
        $DataKaryawan = DataKaryawan::find($id);
        $DataKaryawan->delete();
        return redirect('/DataKaryawan');
    }
}
