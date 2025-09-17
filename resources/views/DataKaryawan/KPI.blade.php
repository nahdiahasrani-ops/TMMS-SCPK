<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data KPI Karyawan</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-200 font-sans">
    @include('Layout.Nav')

    <div class="px-20 py-5">
        <h1 class="text-xl font-bold mb-2">Data KPI Karyawan</h1>

        {{-- Detail Karyawan --}}
        <div class="bg-white border border-gray-400 rounded-sm mb-4 p-4 flex gap-8">
            <div>
                <p class="font-semibold text-sm">Nama</p>
                <p>{{ $karyawan->nama_karyawan }}</p>
            </div>
            <div>
                <p class="font-semibold text-sm">Jabatan</p>
                <p>{{ $karyawan->role }}</p>
            </div>
        </div>

        {{-- Form Input Nilai --}}
        <div class="bg-white border border-gray-400 rounded-sm p-4 mb-4">
            <p class="text-sm italic text-gray-600 mb-2">*Form Tambah Data KPI Karyawan</p>
            <form action="{{ route('kpi.store') }}" method="POST" class="flex flex-wrap gap-3 items-center">
                @csrf
                <input type="hidden" name="data_karyawans_id" value="{{ $karyawan->id }}">
                @foreach ($kriterias as $kriteria)
                    <div>
                        <label class="font-semibold text-sm">{{ $kriteria->nama_kriteria }}</label>
                        <input type="number" name="nilai[{{ $kriteria->id }}]" min="0" max="100"
                            class="border-2 border-gray-400 w-20 text-center">
                    </div>
                @endforeach
                <div>
                    <label class="font-semibold text-sm">Tanggal</label>
                    <input type="date" name="tanggal" class="border-2 border-gray-400">
                </div>
                <button type="submit"
                    class="bg-emerald-600 hover:bg-emerald-800 text-white py-1 px-5 rounded-sm mt-4">Tambah</button>
            </form>
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-2">
                    <ul class="list-disc ml-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Tabel KPI --}}
        <div class="bg-white border border-gray-400 rounded-sm p-4">
            <table class="w-full text-sm text-center">
                <thead class="border-b-2 border-black">
                    <tr>
                        <th rowspan="2" class="py-2">KPI</th>
                        <th rowspan="2">Deskripsi</th>
                        <th colspan="12">Bulan</th>
                    </tr>
                    <tr>
                        @for ($i = 1; $i <= 12; $i++)
                            <th>{{ $i }}</th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kriterias as $index => $kriteria)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-left">{{ $kriteria->nama_kriteria }}</td>
                            @for ($bulan = 1; $bulan <= 12; $bulan++)
                                @php
                                    $nilai = $kpiNilais
                                        ->where('kriteria_id', $kriteria->id)
                                        ->where('bulan', $bulan)
                                        ->first();
                                @endphp
                                <td>{{ $nilai ? $nilai->nilai . '%' : '-' }}</td>
                            @endfor

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
