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
            <p class="text-gray-500 italic text-xs">*Form Tambah Data Kriteria Operator</p>
            {{-- Tambah data --}}
            <form action="/Kriteria-Operator/store" method= "POST" class="flex justify-between items-center">
                @csrf
                <div class="flex gap-8">
                    <div>
                        <label for="" class="font-semibold">Nama Kriteria :</label>
                        <input type="text" class="border-2 border-gray-500" name="nama_kriteria" id="">
                    </div>
                    <div>
                        <label for="" class="font-semibold">Bobot :</label>
                        <input type="text" class="border-2 border-gray-500" name="bobot" id="">
                    </div>
                    <div>
                        <label for="" class="font-semibold">Tipe:</label>
                        <select class="w-40 border-2 border-gray-500" name="tipe" id="">
                            <option value="">Pilih tipe</option>
                            <option value="Benefit">Benefit</option>
                            <option value="Cost">Cost</option>
                        </select>
                    </div>
                    <div>
                        <label for="" class="font-semibold">Jabatan :</label>
                        <select class="w-40 border-2 border-gray-500" name="role" id="">
                            <option value="">Pilih tipe</option>
                            <option value="operator">Operator</option>
                            <option value="mekanik">Mekanik</option>
                            <option value="hsse">HSSE</option>
                        </select>
                    </div>
                </div>
                <button class="bg-emerald-600 hover:bg-emerald-800 text-white py-1 px-5 rounded-sm"
                    type="submit">Tambah</button>
            </form>
        </div>
        {{-- tabel --}}
        <div class="bg-white mt-2 rounded-sm border-2 border-gray-500 drop-shadow-lg px-5 py-3">
            <form method="GET" action="/Kriteria-Operator" class="mb-4">
                <label for="filterRole" class="font-semibold mr-2">Tampilkan berdasarkan:</label>
                <select name="role" id="filterRole" onchange="this.form.submit()" class="border border-gray-400 px-2 py-1">
                    <option value="">-- Semua --</option>
                    <option value="operator" {{ request('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                    <option value="mekanik" {{ request('role') == 'mekanik' ? 'selected' : '' }}>Mekanik</option>
                    <option value="hsse" {{ request('role') == 'hsse' ? 'selected' : '' }}>HSSE</option>
                </select>
            </form>
             {{-- Scrollable table body --}}
            <div class= "overflow-y-auto max-h-[300px]">
            <table class="w-full">
                <thead class="sticky top-0 z-10 bg-gray-100">
                    <tr>
                        <th class="w-20 py-3">No</th>
                        <th>Kriteria</th>
                        <th>Bobot</th>
                        <th>Tipe</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                @foreach($kriteria as $K)
                <tr class="font-light text-center">
                    <td class="w-20">{{$loop->iteration}}</td>
                    <td>{{$K->nama_kriteria}}</td>
                    <td>{{$K->bobot}}%</td>
                    <td>{{$K->tipe}}</td>
                    <td class="flex justify-center text-gray-100 gap-2 ">
                        <a href="/Kriteria-Operator/{{$K->id}}/edit" class="bg-amber-500 hover:bg-amber-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="rounded-sm size-7" width="24"
                                height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M16.477 3.004c.167.015.24.219.12.338l-8.32 8.32a.75.75 0 0 0-.195.34l-1 3.83a.75.75 0 0 0 .915.915l3.829-1a.75.75 0 0 0 .34-.196l8.438-8.438a.198.198 0 0 1 .339.12a45.7 45.7 0 0 1-.06 10.073c-.223 1.905-1.754 3.4-3.652 3.613a47.5 47.5 0 0 1-10.461 0c-1.899-.213-3.43-1.708-3.653-3.613a45.7 45.7 0 0 1 0-10.611C3.34 4.789 4.871 3.294 6.77 3.082a47.5 47.5 0 0 1 9.707-.078" />
                                <path fill="currentColor"
                                    d="M17.823 4.237a.25.25 0 0 1 .354 0l1.414 1.415a.25.25 0 0 1 0 .353L11.298 14.3a.25.25 0 0 1-.114.065l-1.914.5a.25.25 0 0 1-.305-.305l.5-1.914a.25.25 0 0 1 .065-.114z" />
                            </svg>
                        </a>
                        <a href="" class="bg-cyan-500 hover:bg-cyan-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="rounded-sm size-7 p-1" width="16"
                                height="16" viewBox="0 0 16 16">
                                <path fill="currentColor" d="M16 10V6H5v1H3V4h9V0H0v4h2v10h3v2h11v-4H5v1H3V8h2v2z" />
                            </svg>
                        </a>
                        <form action="/Kriteria-Operator/{{ $K->id }}" method="POST" class="inline-block">
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
