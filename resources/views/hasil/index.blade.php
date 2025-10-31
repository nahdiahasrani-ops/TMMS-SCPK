@include('Layout.Nav')
<x-layouts.app :title="'Hasil Akhir – Preferensi V'">
    <div class="mb-4">
        <p class="text-blue-600 font-bold text-2xl mb-3">Hasil Akhir – Preferensi V</p>
        <form method="GET"
            class="flex flex-wrap justify-between w-full items-end gap-3 bg-white p-4 rounded-lg shadow-md">
            <div>
                <label class="block text-xs text-gray-600">Jabatan</label>
                @php $opts=['Operator','Mekanik','HSSE']; @endphp
                <select name="jabatan" class="border-2 border-slate-300 rounded-lg px-3 py-2">
                    @foreach ($opts as $j)
                        <option value="{{ $j }}" {{ $jabatan === $j ? 'selected' : '' }}>{{ $j }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-600">Tahun</label>
                <input type="number" name="tahun" value="{{ $tahun }}"
                    class="border-2 border-slate-300 rounded-lg px-3 py-2 w-28">
            </div>
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs text-gray-600">Cari (kode/nama)</label>
                <input type="text" name="q" value="{{ $q }}"
                    class="w-full border-2 border-slate-300 rounded-lg px-3 py-2" placeholder="Cari…">
            </div>
            <div>
                <label class="block text-xs text-gray-600">Filter</label>
                <select name="filter" class="border-2 border-slate-300 rounded-lg px-3 py-2">
                    <option value="" {{ $filter === '' || $filter === null ? 'selected' : '' }}>Semua</option>
                    <option value="eligible" {{ $filter === 'eligible' ? 'selected' : '' }}>Naik gaji</option>
                    <option value="ineligible" {{ $filter === 'ineligible' ? 'selected' : '' }}>Tidak naik gaji</option>
                    <option value="highest" {{ $filter === 'highest' ? 'selected' : '' }}>Nilai tertinggi</option>
                    <option value="lowest" {{ $filter === 'lowest' ? 'selected' : '' }}>Nilai terendah</option>
                </select>
            </div>
            <button
                class="rounded-lg border-2 border-slate-300 px-4 py-2 flex gap-3 bg-emerald-500 font-semibold text-white hover:bg-emerald-800 transition-all ease-in-out">
                <span><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 " width="24" height="24"
                        viewBox="0 0 24 24">
                        <path fill="currentColor"
                            d="M20 3H4a1 1 0 0 0-1 1v2.227l.008.223a3 3 0 0 0 .772 1.795L8 12.886V21a1 1 0 0 0 1.316.949l6-2l.108-.043A1 1 0 0 0 16 19v-6.586l4.121-4.12A3 3 0 0 0 21 6.171V4a1 1 0 0 0-1-1" />
                    </svg></span>
                Filter
            </button>
        </form>
    </div>

    <div class="mb-3 text-sm text-gray-600">
        Standar kelayakan: <span class="font-semibold">{{ $threshold }}%</span> ke atas.
    </div>

    <div class="bg-white rounded-md shadow-sm overflow-auto max-h-[450px]">
        <table class="w-full text-left text-sm">
            <thead class="">
                <tr class="sticky top-0 z-10 bg-blue-400 ">
                    <th class="px-3 py-3">No</th>
                    <th class="px-3 py-3">Kode</th>
                    <th class="px-3 py-3">Nama</th>
                    <th class="px-3 py-3">Jabatan</th>
                    <th class="px-3 py-3 text-center">V (Pref.)</th>
                    <th class="px-3 py-3 text-center">Status</th>
                    <th class="px-3 py-3 text-center">aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($rows as $r)
                    <tr>
                        <td class="px-3 py-3">{{ $r['rank'] }}</td>
                        <td class="px-3 py-3 font-medium">{{ $r['kode'] }}</td>
                        <td class="px-3 py-3">{{ $r['nama'] }}</td>
                        <td class="px-3 py-3">{{ $r['jabatan'] }}</td>
                        <td class="px-3 py-3 text-center">
                            {{ number_format($r['score_pct']) }}%
                        </td>
                        <td class="px-3 py-3 text-center">
                            @if ($r['eligible'])
                                <span
                                    class="inline-flex items-center rounded-full bg-green-100 text-green-700 border border-green-300 px-2 py-1 text-xs">Naik
                                    gaji</span>
                            @else
                                <span
                                    class="inline-flex items-center rounded-full bg-red-100 text-red-700 border border-red-300 px-2 py-1 text-xs">Tidak</span>
                            @endif
                        </td>
                        <td class="px-3 py-3 flex justify-center ">
                            <a href="{{ route('hasil.pdf', ['karyawan' => $r['id'], 'tahun' => $tahun]) }}"
                                class="text-rose-600 hover:text-rose-800 hover:scale-105 transition-all ease-in-out">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-6" width="15" height="15"
                                    viewBox="0 0 15 15">
                                    <path fill="currentColor"
                                        d="M3.5 8H3V7h.5a.5.5 0 0 1 0 1M7 10V7h.5a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5z" />
                                    <path fill="currentColor" fill-rule="evenodd"
                                        d="M1 1.5A1.5 1.5 0 0 1 2.5 0h8.207L14 3.293V13.5a1.5 1.5 0 0 1-1.5 1.5h-10A1.5 1.5 0 0 1 1 13.5zM3.5 6H2v5h1V9h.5a1.5 1.5 0 1 0 0-3m4 0H6v5h1.5A1.5 1.5 0 0 0 9 9.5v-2A1.5 1.5 0 0 0 7.5 6m2.5 5V6h3v1h-2v1h1v1h-1v2z"
                                        clip-rule="evenodd" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-3 py-6 text-center text-gray-500">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
