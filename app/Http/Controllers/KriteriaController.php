<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role'); // Ambil dari ?role=...

        $kriteria = Kriteria::when($role, function ($query, $role) {
            return $query->where('role', $role);
        })->get();
    
        return view('kriteria.Operator.index', compact('kriteria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kriteria' => 'required',
            'bobot' => 'required|integer',
            'tipe' => 'required|in:Benefit,Cost',
            'role' => 'required|in:operator,mekanik,hsse',
        ]);

        Kriteria::create([
            'nama_kriteria' => $request->nama_kriteria,
            'bobot' => $request->bobot,
            'tipe' => $request->tipe,
            'role' => $request->role, // otomatis isi 'operator'
        ]);

        return redirect('/Kriteria-Operator');
        
    }

    public function edit($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        return view('Kriteria.Operator.edit', compact('kriteria'));
    }

    public function update($id, Request $request)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->update($request->except(['_token','submit']));
        return redirect('/Kriteria-Operator');
        
    }

    public function destroy($id)
    {
        $kriteria = Kriteria::find($id);
        $kriteria->delete();
        return redirect('/Kriteria-Operator');
    }
}


