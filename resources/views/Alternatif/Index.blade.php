@include('Layout.Nav')
<x-layouts.app :title="'Data Alternatif & Normalisasi'">
    <div class="mb-4">
        <p class="text-blue-600 font-bold text-2xl mb-3">Data Alternatif & Normalisasi</p>

        <form method="GET"
            class="flex flex-wrap justify-between w-full items-end gap-3 bg-white p-4 rounded-lg shadow-md">
            <div>
                <label class="text-sm font-semibold">Jabatan : </label>
                @php $opt=['Operator','Mekanik','HSSE']; @endphp
                <select name="jabatan" class="border-2 border-slate-300  rounded-lg px-3 py-2 mr-5">
                    @foreach ($opt as $j)
                        <option value="{{ $j }}" {{ $jabatan === $j ? 'selected' : '' }}>
                            {{ $j }}
                        </option>
                    @endforeach
                </select>
                <label class="text-sm font-semibold">Tahun : </label>
                <input type="number" name="tahun" value="{{ $tahun }}"
                    class="border-2 border-slate-300 rounded-lg px-3 py-2 w-28">

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
        </form>

    </div>

    {{-- REKAP ALTERNATIF (AVG per Sub) --}}
    <h2 class="text-md font-semibold mb-1">Rekap Data Alternatif</h2>
    <p class=" text-sm text-slate-500 mb-2">Rekap data alternatif adalah hasil dari rata-rata nilai 12 bulan (1 tahun)
        dibagi dengan
        bobot subkriteria.</p>
    <div class="bg-white rounded-md shadow-sm overflow-auto max-h-[500px] mb-8">
        <table class="w-full text-left text-sm ">
            <thead class="">
                <tr class="sticky top-0 z-10 bg-blue-400 ">
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Nama Karyawan</th>
                    <th class="px-4 py-3">Jabatan</th>
                    @foreach ($subs as $s)
                        <th class="px-2 py-3 text-center whitespace-nowrap">
                            {{ $s->kode_sub }}
                            <div class="text-[10px] text-slate-600">w:
                                {{ rtrim(rtrim(number_format($s->bobot * 100, 2, '.', ''), '0'), '.') }}%</div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($karyawans as $kar)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $kar->kode }}</td>
                        <td class="px-4 py-3">{{ $kar->nama }}</td>
                        <td class="px-4 py-3">{{ $kar->jabatan }}</td>
                        @foreach ($subs as $s)
                            <td class="px-2 py-3 text-center">
                                {{ is_null($roundedW[$kar->id][$s->id]) ? '–' : round($roundedW[$kar->id][$s->id] * 100, 0) . '%' }}
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 3 + $subs->count() }}" class="px-4 py-6 text-center text-gray-500">Tidak ada
                            karyawan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- NORMALISASI SAW --}}
    <h2 class="text-md font-semibold mb-1">Normalisasi Alternatif</h2>
    <p class=" text-sm mb-2 text-slate-500">Normalisasi alternatif adalah hasil dari rekap alternatif dibagi dengan
        nilai minimal dan
        maksimal sesuai tipe subkriteria.</p>

    <div class="bg-white rounded-md shadow-sm overflow-auto max-h-[500px]">
        <table class="w-full text-left text-sm">
            <thead class="">
                <tr class="sticky top-0 z-10 bg-blue-400 ">
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Nama Karyawan</th>
                    <th class="px-4 py-3">Jabatan</th>
                    @foreach ($subs as $s)
                        <th class="px-2 py-3 text-center whitespace-nowrap">
                            {{ $s->kode_sub }}
                            <div class="text-[10px] text-slate-600">{{ ucfirst($s->tipe) }}</div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($karyawans as $kar)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $kar->kode }}</td>
                        <td class="px-4 py-3">{{ $kar->nama }}</td>
                        <td class="px-4 py-3">{{ $kar->jabatan }}</td>
                        @foreach ($subs as $s)
                            @php $v = $norm[$kar->id][$s->id]; @endphp
                            <td class="px-2 py-3 text-center">
                                {{ is_null($v) ? '–' : (int) round($v*100, 0, PHP_ROUND_HALF_UP) . '%' }}
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 3 + $subs->count() }}" class="px-4 py-6 text-center text-gray-500">Tidak ada
                            karyawan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>
