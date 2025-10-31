@include('Layout.Nav')
<x-layouts.app :title="'Daftar Kriteria'">
    <div class="flex items-center justify-between mb-3">
        <p class="text-blue-600 font-bold text-2xl">Data Kriteria</p>
    </div>
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center gap-4 bg-white p-3 rounded-lg shadow">
            <form method="GET" class="flex items-center gap-2">
                <label class="text-sm text-gray-600">Filter Jabatan:</label>
                <select name="jabatan" onchange="this.form.submit()" class="border border-slate-500 rounded-lg px-3 py-1">
                    <option value="all" {{ $currentJabatan == 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="Operator" {{ $currentJabatan == 'Operator' ? 'selected' : '' }}>Operator</option>
                    <option value="Mekanik" {{ $currentJabatan == 'Mekanik' ? 'selected' : '' }}>Mekanik</option>
                    <option value="HSSE" {{ $currentJabatan == 'HSSE' ? 'selected' : '' }}>HSSE</option>
                </select>
            </form>
        </div>

        @if (Auth::user()->role === 1)
            <a href="{{ route('kriteria.create') }}"
                class="bg-emerald-500 text-white px-4 py-2 font-semibold rounded-lg hover:bg-emerald-600">
                Tambah Kriteria
            </a>
        @endif
    </div>

    <div class="bg-white rounded-md shadow overflow-hidden ">
        <table class="w-full text-left">
            <thead class="bg-blue-400 text-sm">
                <tr>
                    <th class="px-4 py-3">Kode</th>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Jabatan</th>
                    <th class="px-4 py-3">Tipe</th>
                    <th class="px-4 py-3">Bobot</th>
                    <th class="px-4 py-3">Sub-Kriteria</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($list as $item)
                    <tr class="align-top">
                        <td class="px-4 py-3 font-medium">{{ $item->kode_kriteria }}</td>
                        <td class="px-4 py-3">{{ $item->nama_kriteria }}</td>
                        <td class="px-4 py-3">{{ $item->jabatan ?? '-' }}</td>
                        <td class="px-4 py-3 capitalize">{{ $item->tipe }}</td>
                        <td class="px-4 py-3">
                            {{ rtrim(rtrim(number_format($item->bobot * 100, 2, '.', ''), '0'), '.') }}%</td>
                        <td class="px-4 py-3">
                            @if ($item->subKriteria->isEmpty())
                                <span class="text-gray-400 text-sm">Belum ada</span>
                            @else
                                <ul class="list-disc pl-5 text-sm space-y-1">
                                    @foreach ($item->subKriteria as $sub)
                                        <li><span class="font-medium">{{ $sub->kode_sub }}</span> —
                                            {{ $sub->nama_sub_kriteria }} (bobot:
                                            {{ rtrim(rtrim(number_format($sub->bobot * 100, 2, '.', ''), '0'), '.') }}%)
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                @if (Auth::user()->role === 1)
                                    <a href="{{ route('kriteria.show', $item) }}"
                                        class="px-3 py-1.5 border-blue-400 text-blue-600 text-sm rounded-lg border hover:bg-blue-50">Detail</a>
                                    <a href="{{ route('kriteria.edit', $item) }}"
                                        class="px-3 py-1.5 border-amber-400 text-amber-600 text-sm rounded-lg border hover:bg-amber-50">Edit</a>
                                    <form action="{{ route('kriteria.destroy', $item) }}" method="POST"
                                        onsubmit="return confirm('Hapus kriteria ini?')">
                                        @csrf @method('DELETE')
                                        <button
                                            class="px-3 py-1.5 text-sm rounded-lg border border-red-300 text-red-600 hover:bg-red-50">Hapus</button>
                                    </form>
                                @else
                                    <a href="{{ route('kriteria1.show', $item) }}"
                                        class="px-3 py-1.5 border-blue-400 text-blue-600 text-sm rounded-lg border hover:bg-blue-50">Detail</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $list->links() }}
    </div>

    <!-- Modal Import -->
    <div id="importModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40" data-close="true"></div>

        <div class="relative mx-auto mt-24 w-full max-w-xl rounded-2xl bg-white p-6 shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold">Import Kriteria + Sub-Kriteria</h2>
                <button type="button" id="closeImport" class="rounded-full p-2 hover:bg-gray-100"
                    aria-label="Tutup">✕</button>
            </div>

            {{-- error khusus import --}}
            @if ($errors->import?->any())
                <div class="mb-3 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                    <div class="font-semibold mb-1">Terjadi kesalahan:</div>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->import->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('kriteria.import.run') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium">File (.xlsx / .csv)</label>
                    <input type="file" name="file" accept=".xlsx,.csv"
                        class="mt-1 w-full border rounded-xl px-3 py-2" required>
                    <p class="mt-2 text-xs text-gray-500">
                        Gunakan template:
                        <a class="text-blue-700 underline"
                            href="{{ asset('storage/templates/template_import_kriteria_tmms.xlsx') }}">
                            template_import_kriteria_tmms.xlsx
                        </a>
                    </p>
                </div>

                <div class="flex items-center gap-4 text-sm">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="replace_subs" value="1"
                            {{ old('replace_subs', true) ? 'checked' : '' }}>
                        Ganti semua sub yang sudah ada
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="update_if_exists" value="1"
                            {{ old('update_if_exists', true) ? 'checked' : '' }}>
                        Update kriteria jika sudah ada
                    </label>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" id="cancelImport"
                        class="rounded-xl border px-5 py-2 hover:bg-gray-50">Batal</button>
                    <button class="rounded-xl bg-green-600 text-white px-5 py-2 hover:bg-green-700">Import</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                const modal = document.getElementById('importModal');
                const openBtn = document.getElementById('openImport');
                const closeBtn = document.getElementById('closeImport');
                const cancel = document.getElementById('cancelImport');
                const overlay = modal?.querySelector('[data-close]');

                function open() {
                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }

                function close() {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }

                openBtn?.addEventListener('click', open);
                closeBtn?.addEventListener('click', close);
                cancel?.addEventListener('click', close);
                overlay?.addEventListener('click', close);
                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape') close();
                });

                // auto-buka jika ada error pada bag 'import'
                @if ($errors->import?->any())
                    open();
                @endif
            })();
        </script>
    @endpush
</x-layouts.app>
