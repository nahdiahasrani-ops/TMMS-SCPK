@include('Layout.Nav')
<x-layouts.app :title="'Dashboard'">
    <div class="mb-4">
        <p class="text-blue-600 font-bold text-2xl mb-3">Dashboard</p>
        <form method="GET"
            class="flex flex-wrap justify-between w-full items-end gap-3 bg-white p-4 rounded-lg shadow-sm">

            <div>
                <label class="text-sm font-semibold">Jabatan : </label>
                @php $opts=['Operator','Mekanik','HSSE']; @endphp
                <select name="jabatan" class=" border-2 border-slate-300 rounded-lg px-3 py-2 mr-5">
                    @foreach ($opts as $j)
                        <option value="{{ $j }}" {{ $jabatan === $j ? 'selected' : '' }}>
                            {{ $j }}
                        </option>
                    @endforeach
                </select>
                <label class="text-sm font-semibold">Tahun : </label>
                <input type="number" name="tahun" value="{{ $tahun }}"
                    class=" border-2 border-slate-300 rounded-lg px-3 py-2 w-28">
            </div>

            <button
                class="rounded-lg border px-4 py-2 flex gap-3 bg-emerald-500 font-semibold text-white hover:bg-emerald-800 transition-all ease-in-out">
                <span><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 " width="24" height="24"
                        viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M20 3H4a1 1 0 0 0-1 1v2.227l.008.223a3 3 0 0 0 .772 1.795L8 12.886V21a1 1 0 0 0 1.316.949l6-2l.108-.043A1 1 0 0 0 16 19v-6.586l4.121-4.12A3 3 0 0 0 21 6.171V4a1 1 0 0 0-1-1" />
                    </svg></span>
                Filter
            </button>
            {{-- <div class="text-sm text-gray-500 ml-auto">Batas kelayakan: <b>{{ $threshold }}%</b></div> --}}
        </form>
    </div>

    {{-- Cards --}}
    <div class="flex justify-between gap-4">
        <div class="flex flex-col gap-5 mb-6 w-1/3">
            <div class="bg-white rounded-2xl p-4 shadow-sm">
                <div class="text-xs text-gray-500">Total Karyawan</div>
                <div class="text-2xl font-semibold">{{ $cards['totalKar'] }}</div>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-sm">
                <div class="text-xs text-gray-500">Layak Naik Gaji</div>
                <div class="text-2xl font-semibold text-green-600">{{ $cards['eligible'] }}</div>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-sm">
                <div class="text-xs text-gray-500">Tidak Layak</div>
                <div class="text-2xl font-semibold text-red-600">{{ $cards['ineligible'] }}</div>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-sm">
                <div class="text-xs text-gray-500">Rata-rata V</div>
                <div class="text-2xl font-semibold">
                    {{ rtrim(rtrim(number_format($cards['avgScore'], 2, '.', ''), '0'), '.') }}%</div>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-2xl p-4 shadow-sm h-96 mx-auto">
                <div class="text-sm font-semibold mb-2">Distribusi Kelayakan</div>
                <canvas id="eligChart" height=""></canvas>
            </div>
            <div class="bg-white rounded-2xl p-4 shadow-sm h-96 mx-auto">
                <div class="text-sm font-semibold mb-2">Rata-rata Normalisasi per Kriteria</div>
                <canvas id="kritChart" height="140"></canvas>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="p-4 text-sm font-semibold flex gap-2 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-500" width="14" height="14"
                    viewBox="0 0 14 14">
                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1">
                        <path d="M9.5 3.5h4v4" />
                        <path d="M13.5 3.5L7.85 9.15a.5.5 0 0 1-.7 0l-2.3-2.3a.5.5 0 0 0-.7 0L.5 10.5" />
                    </g>
                </svg>
                <p>
                    5 Peringkat Karyawan Terbaik
                </p>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-blue-400">
                    <tr>
                        <th class="px-3 py-2">Kode</th>
                        <th class="px-3 py-2">Nama</th>
                        <th class="px-3 py-2 text-right">V</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($top5 as $r)
                        <tr class="text-center">
                            <td class="px-3 py-2">{{ $r['kode'] }}</td>
                            <td class="px-3 py-2">{{ $r['nama'] }}</td>
                            <td class="px-3 py-2 text-right">
                                {{ rtrim(rtrim(number_format($r['score'] * 100, 2, '.', ''), '0'), '.') }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-3 py-4 text-center text-gray-500">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="p-4 text-sm font-semibold flex gap-2 items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-500" width="14" height="14"
                    viewBox="0 0 14 14">
                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1">
                        <path d="M9.5 10.5h4v-4" />
                        <path d="M13.5 10.5L7.85 4.85a.5.5 0 0 0-.7 0l-2.3 2.3a.5.5 0 0 1-.7 0L.5 3.5" />
                    </g>
                </svg>
                <p>
                    5 Peringkat Karyawan Terbawah
                </p>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-blue-400">
                    <tr>
                        <th class="px-3 py-2">Kode</th>
                        <th class="px-3 py-2">Nama</th>
                        <th class="px-3 py-2 text-right">V</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($bottom5 as $r)
                        <tr class="text-center">
                            <td class="px-3 py-2">{{ $r['kode'] }}</td>
                            <td class="px-3 py-2">{{ $r['nama'] }}</td>
                            <td class="px-3 py-2 text-right">
                                {{ rtrim(rtrim(number_format($r['score'] * 100, 2, '.', ''), '0'), '.') }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-3 py-4 text-center text-gray-500">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Distribusi kelayakan
        new Chart(document.getElementById('eligChart'), {
            type: 'doughnut',
            data: {
                labels: ['Layak', 'Tidak'],
                datasets: [{
                    data: [{{ $dist['eligible'] }}, {{ $dist['ineligible'] }}]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Rata-rata normalisasi per kriteria
        const kritLabels = [
            @php
                $labels = $subs->groupBy('kriteria_id')->map(fn($g) => $g->first()->kode_kriteria ?? $g->first()->id);
                echo $labels->map(fn($v) => "'$v'")->join(',');
            @endphp
        ];
        const kritData = [
            @php
                echo $avgPerKriteria->map(fn($v) => round($v * 100, 2))->join(',');
            @endphp
        ];
        new Chart(document.getElementById('kritChart'), {
            type: 'bar',
            data: {
                labels: kritLabels,
                datasets: [{
                    label: 'Avg (%)',
                    data: kritData
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</x-layouts.app>
