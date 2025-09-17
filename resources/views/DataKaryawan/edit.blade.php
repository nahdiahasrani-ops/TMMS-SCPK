<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>
<body>
    <form action="/DataKaryawan/{{$DataKaryawan->id}}" method="POST">
        @method('put')
        @csrf
        <div class="flex gap-8">
            <div>
                <label for="" class="font-semibold">Nama Kriteria :</label>
                <input type="text" class="border-2 border-gray-500" name="nama_karyawan" value="{{ $DataKaryawan->nama_karyawan }}" id="">
            </div>
            <div>
                <label for="" class="font-semibold">Jabatan :</label>
                <input type="text" class="border-2 border-gray-500" name="jabatan" value="{{ $DataKaryawan->jabatan }}" id="">
            </div>
            <div>
                <label for="" class="font-semibold">Masa Kerja :</label>
                <input type="text" class="border-2 border-gray-500" name="masa_kerja" value="{{ $DataKaryawan->masa_kerja }}" id="">
            </div>
        </div>
        <button type="submit">update</button>
    </form>
</body>
</html>