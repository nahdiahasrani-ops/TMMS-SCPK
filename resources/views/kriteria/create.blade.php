@include('Layout.Nav')
<x-layouts.app :title="'Tambah Kriteria'">
    <div class="flex mb-4">
        <a href="{{ route('kriteria.index') }}" class="text-blue-400 font-bold text-2xl hover:text-blue-900">Data
            Kriteria</a>
        <p class="text-blue-600 font-bold text-2xl">/Tambah Kriteria</p>
    </div>

    <div class="bg-white shadow-lg rounded-2xl p-5">
        <h1 class="text-lg font-semibold mb-4">Tambah Kriteria</h1>

        <form action="{{ route('kriteria.store') }}" method="POST" id="kriteriaForm">
            @csrf
            @include('kriteria._form', ['mode' => 'create'])
        </form>
    </div>
</x-layouts.app>
