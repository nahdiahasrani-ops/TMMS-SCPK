<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lupa Password</title>
    @vite('resources/css/app.css')
</head>

<body class="h-screen flex items-center justify-center bg-gradient-to-t from-blue-900 to-emerald-900">
    <div class=" bg-white p-8 rounded-lg shadow w-1/3">
        <h2 class="text-2xl mb-4 font-bold text-blue-600 text-center">Lupa Password</h2>
        @if (session('message'))
            <div class="mb-4 text-green-600">{{ session('message') }}</div>
        @endif
        <form method="POST" action="{{ route('forgot.password.send') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block mb-1 text-center text-sm text-slate-500">Masukan Email anda disini
                    :</label>
                <input type="email" placeholder="Email" name="email" required
                    class="border-2 border-gray-400 rounded w-full p-2">
                @error('email')
                    <span class="text-red-600">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex justify-between">
                <a href="/login" class="bg-gray-300 hover:bg-gray-500 px-3 py-2 rounded">Kembali</a>

                <button type="submit"
                    class="bg-emerald-500 hover:bg-emerald-700 block text-white py-2 px-4 rounded">Kirim
                    Link
                    Reset</button>


            </div>
        </form>
    </div>
</body>

</html>
