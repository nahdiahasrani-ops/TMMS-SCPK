@include('Layout.Nav')
<x-layouts.app :title="'Detail Kriteria'">
    <div class="flex mb-4">
         @if(Auth::user()->role === 1)
            <a href="{{ route('kriteria.index') }}" class="text-blue-400 font-bold text-2xl hover:text-blue-900">Data
                Kriteria</a>
            <p class="text-blue-600 font-bold text-2xl">/Detail Kriteria</p>
                @else
                   <a href="{{ route('kriteria1.index') }}" class="text-blue-400 font-bold text-2xl hover:text-blue-900">Data
                Kriteria</a>
            <p class="text-blue-600 font-bold text-2xl">/Detail Kriteria</p>


            @endif
    </div>

    <div class="bg-white rounded-2xl shadow p-5">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-xl font-semibold">{{ $kriteria->kode_kriteria }} — {{ $kriteria->nama_kriteria }}</h1>
                <div class="text-sm text-gray-600 mt-1">
                    Jabatan: <span class="font-medium">{{ $kriteria->jabatan ?? '-' }}</span> ·
                    Tipe: <span class="font-medium capitalize">{{ $kriteria->tipe }}</span> ·
                    Bobot: <span
                        class="font-medium">{{ rtrim(rtrim(number_format($kriteria->bobot * 100, 2, '.', ''), '0'), '.') }}%</span>
                </div>
            </div>
            
        </div>

        <div class="mt-5">
            <h2 class="font-semibold mb-2">Sub-Kriteria</h2>
            @if ($kriteria->subKriteria->isEmpty())
                <div class="text-gray-500 text-sm">Belum ada sub-kriteria</div>
            @else
                <div class="bg-gray-50 rounded-xl border">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3">Kode</th>
                                <th class="px-4 py-3">Nama</th>
                                <th class="px-4 py-3">Bobot</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($kriteria->subKriteria as $sub)
                                <tr>
                                    <td class="px-4 py-3 font-medium">{{ $sub->kode_sub }}</td>
                                    <td class="px-4 py-3">{{ $sub->nama_sub_kriteria }}</td>
                                    <td class="px-4 py-3">
                                        {{ rtrim(rtrim(number_format($sub->bobot * 100, 2, '.', ''), '0'), '.') }}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
