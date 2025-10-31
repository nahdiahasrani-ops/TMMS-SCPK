@include('Layout.Nav')
<x-layouts.app :title="'Edit Kriteria'">
    <div class="flex mb-4">
        <a href="{{ route('kriteria.index') }}" class="text-blue-400 font-bold text-2xl hover:text-blue-900">Data
            Kriteria</a>
        <p class="text-blue-600 font-bold text-2xl">/Edit Kriteria</p>
    </div>

    <div class="bg-white rounded-2xl shadow p-5">
        <h1 class="text-lg font-semibold mb-4">Edit Kriteria: {{ $kriteria->kode_kriteria }}</h1>

        <form action="{{ route('kriteria.update', $kriteria) }}" method="POST" id="kriteriaForm">
            @csrf @method('PUT')
            @include('kriteria._form', ['mode' => 'edit', 'kriteria' => $kriteria])
        </form>
    </div>
</x-layouts.app>
