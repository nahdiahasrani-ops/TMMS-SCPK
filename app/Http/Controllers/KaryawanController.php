<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index() {
        $list = Karyawan::orderBy('kode')->paginate(10);
        return view('karyawan.index', compact('list'));
    }

    public function create() {
        return view('karyawan.create');
    }

    public function store(Request $r) {
        $data = $r->validate([
            'kode' => ['required','max:30','unique:karyawans,kode'],
            'nama' => ['required','max:120'],
            'jabatan' => ['required','in:Operator,Mekanik,HSSE'],
            'tgl_masuk' => ['nullable','date'],
            'status' => ['required','in:aktif,nonaktif'],
        ]);
        Karyawan::create($data);
        return redirect()->route('karyawan.index')->with('success','Karyawan ditambahkan.');
    }

    public function edit(Karyawan $karyawan)
    {
        return view('karyawan.edit', compact('karyawan'));
    }

    public function update(Request $r, Karyawan $karyawan)
    {
        $data = $r->validate([
            'kode' => ['required','max:30','unique:karyawans,kode,'.$karyawan->id],
            'nama' => ['required','max:120'],
            'jabatan' => ['required','in:Operator,Mekanik,HSSE'],
            'tgl_masuk' => ['nullable','date'],
            'status' => ['required','in:aktif,nonaktif'],
        ]);
        $karyawan->update($data);
        return redirect()->route('karyawan.index')->with('success','Karyawan diperbarui.');
    }

    public function destroy(Karyawan $karyawan) {
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success','Karyawan dihapus.');
    }
}
