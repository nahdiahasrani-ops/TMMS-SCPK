<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-300 font-sans">
    @include('Layout.Nav')
    {{-- konten --}}
    <div class="px-20">
        <div class="bg-white mt-5 rounded-sm border-2 border-gray-500 drop-shadow-lg px-5 py-3">
            <p class="text-gray-500 italic text-xs ">*Form Tambah Data Karyawan Operator</p>
            {{-- Tambah data --}}
            <form action="/DataKaryawan/store" method="POST" class="flex justify-between items-center">
                @csrf
                <div class="flex gap-8">
                    <div>
                        <label for="" class="font-semibold">Nama Karyawan :</label>
                        <input type="text" class="border-2 border-gray-500" name="nama_karyawan" id="">
                    </div>
                    <div>
                        <label for="" class="font-semibold">Jabatan :</label>
                        <select class="w-40 border-2 border-gray-500" name="role" id="">
                            <option value="">Pilih tipe</option>
                            <option value="Operator">Operator</option>
                            <option value="Mekanik">Mekanik</option>
                            <option value="HSSE">HSSE</option>
                        </select>
                    </div>
                    <div>
                        <label for="" class="font-semibold">Masa Kerja :</label>
                        <input type="text" class="border-2 border-gray-500" name="masa_kerja">
                    </div>
                    
                </div>
                <button class="bg-emerald-600 hover:bg-emerald-800 text-white py-1 px-5 rounded-sm"
                    type="submit">Tambah</button>
            </form>
        </div>
        {{-- tabel --}}
        <div class="bg-white mt-2 rounded-sm border-2 border-gray-500 drop-shadow-lg px-5 py-3">
            <form method="GET" action="/DataKaryawan" class="mb-4">
                <label for="filterRole" class="font-semibold mr-2">Tampilkan berdasarkan:</label>
                <select name="role" id="filterRole" onchange="this.form.submit()" class="border border-gray-400 px-2 py-1">
                    <option value="">-- Semua --</option>
                    <option value="Operator" {{ request('role') == 'Operator' ? 'selected' : '' }}>Operator</option>
                    <option value="Mekanik" {{ request('role') == 'Mekanik' ? 'selected' : '' }}>Mekanik</option>
                    <option value="HSSE" {{ request('role') == 'HSSE' ? 'selected' : '' }}>HSSE</option>
                </select>
            </form>
             {{-- Scrollable table body --}}
             <div class= "overflow-y-auto max-h-[300px]">
                <table class="w-full">
                    <thead class="sticky top-0 z-10 bg-gray-100">
                        <tr>
                            <th class="w-20 py-3">No</th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Masa Kerja</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                @foreach ($DataKaryawan as $DK) 
                <tr class="font-light text-center">
                    <td class="w-20">{{$loop->iteration}}</td>
                    <td>{{$DK->nama_karyawan}}</td>
                    <td>{{$DK->role}}</td>
                    <td>{{$DK->masa_kerja}}</td>
                    <td class="flex justify-center text-gray-100 gap-2 ">
                        <a href="/DataKaryawan/{{$DK->id}}/edit" class="bg-amber-500 hover:bg-amber-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="rounded-sm size-7" width="24"
                                height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M16.477 3.004c.167.015.24.219.12.338l-8.32 8.32a.75.75 0 0 0-.195.34l-1 3.83a.75.75 0 0 0 .915.915l3.829-1a.75.75 0 0 0 .34-.196l8.438-8.438a.198.198 0 0 1 .339.12a45.7 45.7 0 0 1-.06 10.073c-.223 1.905-1.754 3.4-3.652 3.613a47.5 47.5 0 0 1-10.461 0c-1.899-.213-3.43-1.708-3.653-3.613a45.7 45.7 0 0 1 0-10.611C3.34 4.789 4.871 3.294 6.77 3.082a47.5 47.5 0 0 1 9.707-.078" />
                                <path fill="currentColor"
                                    d="M17.823 4.237a.25.25 0 0 1 .354 0l1.414 1.415a.25.25 0 0 1 0 .353L11.298 14.3a.25.25 0 0 1-.114.065l-1.914.5a.25.25 0 0 1-.305-.305l.5-1.914a.25.25 0 0 1 .065-.114z" />
                            </svg>
                        </a>
                        <a href="{{ route('kpi.index', $DK->id) }}" class="bg-cyan-500 hover:bg-cyan-700 flex">
                            <svg xmlns="http://www.w3.org/2000/svg" class="rounded-sm size-7 p-[2px]" width="24"
                                height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M20 13.75a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75v6.75H14V4.25c0-.728-.002-1.2-.048-1.546c-.044-.325-.115-.427-.172-.484s-.159-.128-.484-.172C12.949 2.002 12.478 2 11.75 2s-1.2.002-1.546.048c-.325.044-.427.115-.484.172s-.128.159-.172.484c-.046.347-.048.818-.048 1.546V20.5H8V8.75A.75.75 0 0 0 7.25 8h-3a.75.75 0 0 0-.75.75V20.5H1.75a.75.75 0 0 0 0 1.5h20a.75.75 0 0 0 0-1.5H20z" />
                            </svg>
                            <p class="pr-1 font-semibold">KPI</p>
                        </a>
                        <form action="/DataKaryawan/{{ $DK->id }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white rounded-sm p-[2px]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 6a1 1 0 0 1 .117 1.993L20 8h-.081L19 19a3 3 0 0 1-2.824 2.995L16 22H8c-1.598 0-2.904-1.24-2.995-2.824L5 8h-.081L4 7.993A1 1 0 0 1 4.117 6H20zm-2 2H6l.001 12H18L18 8zM9 4h6a1 1 0 0 1 .117 1.993L15 6H9a1 1 0 0 1-.117-1.993L9 4z"/>
                                </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        </div>
    </div>
</body>

</html>
