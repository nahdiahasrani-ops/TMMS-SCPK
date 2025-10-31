@include('Layout.Nav')
<x-layouts.app :title="'Edit Karyawan'">
    <div class="flex mb-4">
        <a href="{{ route('karyawan.index') }}" class="text-blue-400 font-bold text-2xl hover:text-blue-900">Data
            Karyawan</a>
        <p class="text-blue-600 font-bold text-2xl">/Edit Karyawan</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5">
        <h1 class="text-lg font-semibold mb-4">Edit Karyawan: {{ $karyawan->nama }}</h1>

        <form action="{{ route('karyawan.update', $karyawan) }}" method="POST">
            @csrf @method('PUT')
            @include('karyawan._form', ['mode' => 'edit', 'karyawan' => $karyawan])
        </form>
    </div>
</x-layouts.app>
