@include('Layout.Nav')
<x-layouts.app :title="'Input Nilai KPI'">
    <div class="flex mb-4">
        @if(Auth::user()->role === 1)
        <a href="{{ route('karyawan.index') }}" class="text-blue-400 font-bold text-2xl hover:text-blue-900">Data
            Karyawan</a>
        @else
        <a href="{{ route('karyawan1.index') }}" class="text-blue-400 font-bold text-2xl hover:text-blue-900">Data
            Karyawan</a>
        @endif
        <p class="text-blue-600 font-bold text-2xl">/Nilai KPI Karyawan</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-sm text-gray-600">Karyawan</div>
                <div class="text-lg font-semibold">{{ $karyawan->nama }} · <span
                        class="text-gray-600">{{ $karyawan->jabatan }}</span></div>
            </div>

            <form method="GET" class="flex items-center gap-2">
                <input type="hidden" name="karyawan" value="{{ $karyawan->id }}">
                <label class="text-sm">Tahun</label>
                <input type="number" name="tahun" value="{{ $tahun }}"
                    class="border-2 rounded-lg px-3 py-1 w-28">
                <button class="rounded-lg border-slate-300 px-3 py-1 bg-slate-200 hover:bg-gray-300">Filter</button>
            </form>
        </div>

        <form action="{{ route('karyawan.nilai.save', $karyawan) }}" method="POST">
            @csrf
            <input type="hidden" name="tahun" value="{{ $tahun }}">

            <div class="overflow-auto max-h-[440px]">
                <table class="w-full text-left text-sm">
                    <thead class="">
                        <tr>
                            <th rowspan="2" class="px-3 py-2 sticky top-0 z-10 bg-blue-300">Kriteria / Sub-Kriteria
                            </th>
                            <th colspan="13" class="px-3 pt-2 text-center sticky top-0 z-10 bg-blue-300">Bulan</th>
                        </tr>
                        <tr>
                            @for ($m = 1; $m <= 12; $m++)
                                <th class="px-2 py-2 text-center w-24 sticky top-7 z-10 bg-blue-300">{{ $m }}
                                </th>
                            @endfor
                            <th class="px-2 py-2 text-center w-24 sticky top-7 z-10 bg-blue-300">Avg</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($kriterias as $krit)
                            <tr class="bg-gray-50">
                                <td class="px-3 py-2 font-semibold" colspan="14">{{ $krit->kode_kriteria }} —
                                    {{ $krit->nama_kriteria }} ({{ ucfirst($krit->tipe) }})</td>
                            </tr>
                            @foreach ($krit->subKriteria as $sub)
                                @php
                                    $vals = ($existing[$sub->id] ?? collect())->keyBy('bulan'); // 1..12
                                @endphp
                                <tr>
                                    <td class="px-3 py-2">
                                        <span class="font-medium">{{ $sub->kode_sub }}</span> —
                                        {{ $sub->nama_sub_kriteria }}
                                    </td>
                                    @for ($m = 1; $m <= 12; $m++)
                                        @php
                                            $pct = optional($vals->get($m))->nilai;
                                            $pct = is_null($pct) ? '' : round($pct * 100, 2);
                                        @endphp
                                        <td class="px-2 py-1">
                                             @if(Auth::user()->role === 1)
                                                <input type="number" step="0.01" min="0" max="100"
                                                    name="nilai[{{ $sub->id }}][{{ $m }}]"
                                                    value="{{ old("nilai.$sub->id.$m", $pct) }}"
                                                    class="w-full rounded-md border-2 border-gray-300 px-2 py-1 text-right"
                                                    oninput="recalcRowAvg(this)">
                                            @else
                                                <span class="block w-full px-2 py-1 text-right">
                                                    {{ $pct ? $pct : '–' }}
                                                </span>
                                            @endif
                                        </td>
                                    @endfor
                                    @php
                                        // ambil rata-rata dari data yang ada (0..1 -> persen)
                                        $filled = ($existing[$sub->id] ?? collect())->filter(
                                            fn($x) => $x->nilai !== null,
                                        );
                                        $avgServer = $filled->isEmpty() ? null : round($filled->avg('nilai') * 100, 2);
                                    @endphp
                                    <td class="px-2 py-1 text-center">
                                        <span class="row-avg text-gray-700">
                                            {{ is_null($avgServer) ? '–' : rtrim(rtrim(number_format($avgServer, 2, '.', ''), '0'), '.') }}
                                        </span>%
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(Auth::user()->role === 1)
                <div class="mt-4 flex justify-end gap-2">
                    <button class="rounded-xl bg-emerald-500 font-semibold text-white px-5 py-2 hover:bg-emerald-700">Simpan
                        Nilai</button>
                </div>
            @endif
        </form>
    </div>

    @push('scripts')
        <script>
            function recalcRowAvg(el) {
                const tr = el.closest('tr');
                if (!tr) return; // guard: mis. input Tahun di header

                // hanya ambil input angka pada SEL tr ini (bukan seluruh halaman)
                const inputs = tr.querySelectorAll('td input[type="number"]');

                let sum = 0,
                    cnt = 0;
                inputs.forEach(i => {
                    const v = parseFloat(i.value);
                    if (!isNaN(v)) {
                        sum += v;
                        cnt++;
                    }
                });

                const out = tr.querySelector('.row-avg');
                if (!out) return;

                if (cnt === 0) {
                    out.textContent = '–';
                } else {
                    const avg = sum / cnt;
                    // tampilkan 2 desimal tapi hilangkan .00 di akhir
                    out.textContent = (Math.round(avg * 100) / 100).toFixed(2).replace(/\.00$/, '');
                }
            }

            // hitung saat load — BATASI hanya input angka di body tabel
            window.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('tbody td input[type="number"]').forEach(i => recalcRowAvg(i));
            });

            // tetap hitung saat user mengetik
            document.addEventListener('input', (e) => {
                if (e.target.matches('tbody td input[type="number"]')) recalcRowAvg(e.target);
            });
        </script>
    @endpush

</x-layouts.app>
