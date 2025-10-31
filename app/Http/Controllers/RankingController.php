<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SawService;

class RankingController extends Controller
{
     public function index(Request $req)
    {
        // tampilkan form periode + (opsional) filter role
        return view('Ranking.index', [
            'data' => null,
            'default_bulan' => now()->month,
            'default_tahun' => now()->year,
        ]);
    }

    public function run(Request $req, SawService $saw)
    {
        $req->validate([
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2000|max:2100',
            'role'  => 'nullable|in:operator,mekanik,hsse',
        ]);

        $data = $saw->run((int)$req->bulan, (int)$req->tahun, $req->role);
        return view('Ranking.index', [
            'data' => $data,
            'default_bulan' => (int)$req->bulan,
            'default_tahun' => (int)$req->tahun,
        ]);
    }
}
