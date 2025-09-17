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
        <div>
            <p class="text-xl font-semibold mt-3">Rekap Data Alternatif</p>
        </div>
        {{-- tabel --}}
        <div class="bg-white mt-2 rounded-sm border-2 border-gray-500 drop-shadow-lg px-5 py-3">
            <table class="w-full">
                <tr class="border-b-2 border-black">
                    <th class="w-20 py-3">No</th>
                    <th>Nama Karyawan</th>
                    <th>Jabatan</th>
                    <th>Produktifitas</th>
                    <th>K3</th>
                    <th>Efesiensi Bahan Bakar & Alat</th>
                    <th>Sikap & Disiplin</th>
                </tr>
                <tr class="font-light text-center">
                    <td class="w-20">1</td>
                    <td>Karyawan 1</td>
                    <td>Operator</td>
                    <td>100%</td>
                    <td>100%</td>
                    <td>100%</td>
                    <td>100%</td>

                </tr>
            </table>
        </div>
    </div>
</body>

</html>
