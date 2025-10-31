{{-- resources/views/kriteria/import.blade.php --}}
<x-layouts.app :title="'Import Kriteria'">
  <div class="mb-4">
    <a href="{{ route('kriteria.index') }}" class="text-sm text-blue-700 hover:underline">← Kembali</a>
  </div>

  <div class="bg-white rounded-2xl shadow-sm p-5 max-w-2xl">
    <h1 class="text-lg font-semibold mb-4">Import Kriteria + Sub-Kriteria</h1>

    <p class="text-sm mb-3">
      Gunakan file sesuai template. 
      <a class="text-blue-700 underline" href="{{ url('/storage/templates/template_import_kriteria_tmms.xlsx') }}">Download template</a>
      atau pakai yang ku kirim di chat ini.
    </p>

    <form action="{{ route('kriteria.import.run') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
      @csrf
      <div>
        <label class="block text-sm font-medium">File (.xlsx / .csv)</label>
        <input type="file" name="file" accept=".xlsx,.csv" class="mt-1 w-full border rounded-xl px-3 py-2" required>
      </div>

      <div class="flex items-center gap-3 text-sm">
        <label class="inline-flex items-center gap-2">
          <input type="checkbox" name="replace_subs" value="1" checked>
          Ganti semua sub yang sudah ada
        </label>
        <label class="inline-flex items-center gap-2">
          <input type="checkbox" name="update_if_exists" value="1" checked>
          Update kriteria jika sudah ada
        </label>
      </div>

      <div class="pt-2">
        <button class="rounded-xl bg-green-600 text-white px-5 py-2 hover:bg-green-700">Import</button>
      </div>
    </form>
  </div>
</x-layouts.app>
