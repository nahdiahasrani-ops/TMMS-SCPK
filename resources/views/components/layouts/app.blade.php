<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'TMMS - SPK Kenaikan Upah' }}</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 text-gray-900">
    {{-- <nav class="bg-white border-b">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
      <a href="{{ route('kriteria.index') }}" class="text-lg font-semibold">TMMS-SPK</a>
      <div class="text-sm text-gray-600">Kriteria & Sub-Kriteria</div>
    </div>
  </nav> --}}

    <main class="ml-64 px-10 py-6">
        @if (session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3">
                <div class="font-semibold mb-1">Terjadi kesalahan:</div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </main>

    @stack('scripts')
</body>

</html>
