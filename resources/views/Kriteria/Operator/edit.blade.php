<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>
<body class="flex justify-center py-40 bg-slate-300">
    <div class="p-3 bg-white rounded-sm border-2 border-gray-500 drop-shadow-lg">
        <p class="font-bold text-lg text-amber-500 text-center mb-3">Form Edit</p>
        <form action="/Kriteria-Operator/{{$kriteria->id}}" method="POST">
            @method('put')
            @csrf
            <div class=" gap-8">
                <div>
                    <label for="" class="font-semibold">Nama Kriteria :</label><br>
                    <input type="text" class="border-2 border-gray-500" name="nama_kriteria" value="{{ $kriteria->nama_kriteria }}" id="">
                </div>
                <div class="my-2">
                    <label for="" class="font-semibold">Bobot :</label><br>
                    <input type="text" class="border-2 border-gray-500" name="bobot" value="{{ $kriteria->bobot }}" id="">
                </div>
                <div>
                    <label for="" class="font-semibold">Tipe:</label><br>
                    <select class="w-40 border-2 border-gray-500" name="tipe" id="">
                        <option value="">Pilih tipe</option>
                        <option value="Benefit"@if ($kriteria->tipe == "Benefit") selected @endif>Benefit</option>
                        <option value="Cost"@if ($kriteria->tipe == "Cost") selected @endif>Cost</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-amber-500 w-full mt-3 p-1 text-white font-semibold">update</button>
            <a href="/Kriteria-Operator">
                <div class="w-full text-center bg-slate-300 mt-2">Kembali</div>
            </a>
        </form>
    </div>
</body>
</html>