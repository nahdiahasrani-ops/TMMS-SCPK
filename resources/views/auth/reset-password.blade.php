<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pembaruan Password</title>
    @vite('resources/css/app.css')
</head>

<body class="h-screen flex items-center justify-center bg-gradient-to-t from-blue-900 to-emerald-900">
    <div class="max-w-md mx-auto mt-12 bg-white w-1/3  p-8 rounded-lg shadow">
        <h2 class="text-2xl mb-4 font-bold text-blue-600 text-center">Permbaruan</h2>
        @if (session('error'))
            <div class="mb-4 text-red-600">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('reset.password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-4">
                <label for="email" class="block mb-1">Email</label>
                <input type="email" placeholder="Email" name="email" required
                    class="border border-gray-400 rounded w-full p-2">
                @error('email')
                    <span class="text-red-600">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password" class="block mb-1">Password Baru</label>
                <input type="password" placeholder="Password" name="password" required
                    class="border border-gray-400 rounded w-full p-2">
                @error('password')
                    <span class="text-red-600">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block mb-1">Konfirmasi Password Baru</label>
                <input type="password" placeholder="Konfirmasi Password" name="password_confirmation" required
                    class="border border-gray-400 rounded w-full p-2">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-emerald-500 hover:bg-emerald-700 text-white py-2 px-4 rounded">Reset
                    Password</button>
            </div>
        </form>
    </div>
</body>

</html>
