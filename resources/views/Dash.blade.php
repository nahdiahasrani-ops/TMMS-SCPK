<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    @vite('resources/css/app.css')
</head>
@include('Layout.Nav')
<body class="bg-slate-300 font-sans">
    <div class="min-h-screen bg-slate-300">

               {{-- Konten Utama --}}
        <div class="bg-gray-100 py-10 ">
            <div class="px-20 mx-auto">

                <h1 class="text-2xl font-bold mb-6">Dashboard Sistem Pendukung Keputusan</h1>

                <!-- Stat Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white p-4 rounded-lg shadow text-center">
                        <p class="text-gray-500">Total Karyawan</p>
                        <p class="text-2xl font-bold text-blue-600">25</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow text-center">
                        <p class="text-gray-500">Total Kriteria</p>
                        <p class="text-2xl font-bold text-green-600">5</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow text-center">
                        <p class="text-gray-500">Total Alternatif</p>
                        <p class="text-2xl font-bold text-yellow-600">8</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow text-center">
                        <p class="text-gray-500">Last Update</p>
                        <p class="text-lg font-medium text-gray-700">22 Apr 2025</p>
                    </div>
                </div>

                <!-- Tabel Ranking -->
                <div class="bg-white p-6 rounded-lg shadow mb-8">
                    <h2 class="text-xl font-semibold mb-4">Hasil Ranking</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-700">
                            <thead class="bg-gray-100 text-xs uppercase font-semibold">
                                <tr>
                                    <th class="px-4 py-2">Rank</th>
                                    <th class="px-4 py-2">Nama Karyawan</th>
                                    <th class="px-4 py-2">Nilai Akhir</th>
                                    <th class="px-4 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b">
                                    <td class="px-4 py-2">1</td>
                                    <td class="px-4 py-2">Karyawan 1</td>
                                    <td class="px-4 py-2">0.89</td>
                                    <td class="px-4 py-2 text-green-600 font-medium">Terbaik</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="px-4 py-2">2</td>
                                    <td class="px-4 py-2">Karyawan 2</td>
                                    <td class="px-4 py-2">0.75</td>
                                    <td class="px-4 py-2">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
