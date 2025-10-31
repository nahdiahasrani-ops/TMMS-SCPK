<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KriteriaController extends Controller
{
    /* ========== LIST ========== */
    public function index(Request $request)
    {
        $jabatan = $request->query('jabatan', 'Operator'); // default: Operator

        $list = Kriteria::with('subKriteria')
            ->when($jabatan !== 'all', function($query) use ($jabatan) {
                return $query->where('jabatan', $jabatan);
            })
            ->orderBy('kode_kriteria')
            ->paginate(10);

        return view('kriteria.index', [
            'list' => $list,
            'currentJabatan' => $jabatan
        ]);
    }

    /* ========== FORM CREATE ========== */
    public function create()
    {
        return view('kriteria.create');
    }

    /* ========== SIMPAN ========== */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_kriteria' => ['required','max:10','regex:/^[A-Za-z0-9]+$/','unique:kriteria,kode_kriteria'],
            'nama_kriteria' => ['required','max:100'],

            // ⬇️ input persen dari form
            'bobot_percent' => ['required','numeric','between:0,100'],

            'jabatan'       => ['nullable','in:Operator,Mekanik,HSSE'],
            'tipe'          => ['required','in:benefit,cost'],

            'sub_kriteria'                 => ['required','array','min:1'],
            'sub_kriteria.*.kode'          => ['nullable','max:20'],
            'sub_kriteria.*.nama'          => ['required','max:150'],
            // ⬇️ persen untuk sub
            'sub_kriteria.*.bobot_percent' => ['required','numeric','between:0,100'],
            'sub_kriteria.*._delete'       => ['nullable','boolean'],
        ]);

        // (opsional) pastikan total sub = 100%
       $sumPct = collect($data['sub_kriteria'])
            ->reject(fn($s) => !empty($s['_delete']))
            ->sum(fn($s) => (float)($s['bobot_percent'] ?? 0));

        if (abs($sumPct - (float)$data['bobot_percent']) > 0.01) {
            return back()
                ->withErrors(['sub_kriteria' => 'Total bobot sub-kriteria harus = '.$data['bobot_percent'].'%'])
                ->withInput();
        }

        DB::transaction(function () use ($data) {
            $kriteria = Kriteria::create([
                'kode_kriteria' => $data['kode_kriteria'],
                'nama_kriteria' => $data['nama_kriteria'],
                // ⬇️ simpan desimal 0–1
                'bobot'         => $data['bobot_percent'] / 100,
                'jabatan'       => $data['jabatan'] ?? null,
                'tipe'          => $data['tipe'],
            ]);

            $counter = 1;
            foreach ($data['sub_kriteria'] as $sub) {
                if (!empty($sub['_delete'])) continue;
                $kodeSub = $sub['kode'] ?: ($data['kode_kriteria'].'.'.$counter);

                SubKriteria::create([
                    'kriteria_id'       => $kriteria->id,
                    'kode_sub'          => $kodeSub,
                    'nama_sub_kriteria' => $sub['nama'],
                    // ⬇️ simpan desimal 0–1
                    'bobot'             => $sub['bobot_percent'] / 100,
                ]);
                $counter++;
            }
        });

        return redirect()->route('kriteria.index')->with('success','Kriteria & sub-kriteria berhasil disimpan.');
    }

    /* ========== DETAIL ========== */
    public function show(Kriteria $kriterium)
    {
        $kriterium->load('subKriteria');
        return view('kriteria.show', ['kriteria' => $kriterium]);
    }

   public function show1(Kriteria $kriteria)
    {
        return view('kriteria.show', [
            'kriteria' => $kriteria,
            'subKriterias' => $kriteria->subKriteria
        ]);
    }

    /* ========== FORM EDIT ========== */
    public function edit(Kriteria $kriterium)
    {
        $kriterium->load('subKriteria');
        return view('kriteria.edit', ['kriteria' => $kriterium]);
    }

    /* ========== PERBARUI ========== */
    public function update(Request $request, Kriteria $kriterium)
    {
        $data = $request->validate([
            'kode_kriteria' => [
                'required','max:10','regex:/^[A-Za-z0-9]+$/',
                Rule::unique('kriteria','kode_kriteria')->ignore($kriterium->id),
            ],
            'nama_kriteria' => ['required','max:100'],

            // ⬇️ persen dari form
            'bobot_percent' => ['required','numeric','between:0,100'],

            'jabatan'       => ['nullable','in:Operator,Mekanik,HSSE'],
            'tipe'          => ['required','in:benefit,cost'],

            'sub_kriteria'                 => ['required','array','min:1'],
            'sub_kriteria.*.id'            => ['nullable','integer','exists:sub_kriteria,id'],
            'sub_kriteria.*.kode'          => ['nullable','max:20'],
            'sub_kriteria.*.nama'          => ['required','max:150'],
            // ⬇️ persen untuk sub
            'sub_kriteria.*.bobot_percent' => ['required','numeric','between:0,100'],
            'sub_kriteria.*._delete'       => ['nullable','boolean'],
        ]);

        // (opsional) total 100%
    $sumPct = collect($data['sub_kriteria'])
            ->reject(fn($s) => !empty($s['_delete']))
            ->sum(fn($s) => (float)($s['bobot_percent'] ?? 0));

        if (abs($sumPct - (float)$data['bobot_percent']) > 0.01) {
            return back()
                ->withErrors(['sub_kriteria' => 'Total bobot sub-kriteria harus = '.$data['bobot_percent'].'%'])
                ->withInput();
        }

        DB::transaction(function () use ($data, $kriterium) {
            $kriterium->update([
                'kode_kriteria' => $data['kode_kriteria'],
                'nama_kriteria' => $data['nama_kriteria'],
                // ⬇️ simpan desimal 0–1
                'bobot'         => $data['bobot_percent'] / 100,
                'jabatan'       => $data['jabatan'] ?? null,
                'tipe'          => $data['tipe'],
            ]);

            $existingIds = $kriterium->subKriteria()->pluck('id')->toArray();
            $kept = [];
            $counter = 1;

            foreach ($data['sub_kriteria'] as $sub) {
                if (!empty($sub['_delete'])) continue;

                $kodeSub = $sub['kode'] ?: ($data['kode_kriteria'].'.'.$counter);
                $payload = [
                    'kode_sub'          => $kodeSub,
                    'nama_sub_kriteria' => $sub['nama'],
                    // ⬇️ simpan desimal 0–1
                    'bobot'             => $sub['bobot_percent'] / 100,
                ];

                if (!empty($sub['id'])) {
                    $model = SubKriteria::where('kriteria_id',$kriterium->id)->where('id',$sub['id'])->firstOrFail();
                    $model->update($payload);
                    $kept[] = $model->id;
                } else {
                    $model = SubKriteria::create($payload + ['kriteria_id'=>$kriterium->id]);
                    $kept[] = $model->id;
                }
                $counter++;
            }

            $toDelete = array_diff($existingIds, $kept);
            if ($toDelete) {
                SubKriteria::where('kriteria_id',$kriterium->id)->whereIn('id',$toDelete)->delete();
            }
        });

        return redirect()->route('kriteria.index')->with('success','Kriteria & sub-kriteria berhasil diperbarui.');
    }

    /* ========== HAPUS ========== */
    public function destroy(Kriteria $kriterium)
    {
        $kriterium->delete(); // FK kita sudah cascadeOnDelete
        return redirect()->route('kriteria.index')->with('success', 'Kriteria berhasil dihapus.');
    }
}
