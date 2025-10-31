@include('Layout.Nav')
<x-layouts.app :title="'Data Karyawan'">
    <div class="flex items-center justify-between mb-4">
        <p class="text-blue-600 font-bold text-2xl mb-3">Data Karyawan</p>
        <div>
            @if (Auth::user()->role === 1)
                <button type="button" id="openImport"
                    class="rounded-md bg-amber-600 hover:bg-amber-700 font-semibold text-white px-4 py-2 text-sm">Import</button>
                <a href="{{ route('karyawan.create') }}"
                    class="rounded-md bg-emerald-500 font-semibold shadow-md text-white px-4 py-2 text-sm hover:bg-emerald-700">+
                    Tambah</a>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-md shadow-sm overflow-hidden">
        @if (Auth::user()->role === 1)
            <table class="w-full text-left">
                <thead class="bg-blue-400 text-sm">
                    <tr>
                        <th class="px-4 py-2">Kode</th>
                        <th class="px-4 py-2">Nama</th>
                        <th class="px-4 py-2">Jabatan</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($list as $row)
                        <tr>
                            <td class="px-4 py-2 font-medium">{{ $row->kode }}</td>
                            <td class="px-4 py-2">{{ $row->nama }}</td>
                            <td class="px-4 py-2">{{ $row->jabatan }}</td>
                            <td class="px-4 py-2">{{ ucfirst($row->status) }}</td>
                            <td class="px-4 py-2">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('karyawan.nilai.form', ['karyawan' => $row->id, 'tahun' => date('Y')]) }}"
                                        class="px-3 py-1.5 text-sm rounded-lg border border-emerald-400 text-emerald-600 hover:bg-emerald-50">Input
                                        Nilai</a>
                                    <a href="{{ route('karyawan.edit', $row) }}"
                                        class="px-3 py-1.5 text-sm rounded-lg border border-amber-400 text-amber-600 hover:bg-amber-50">Edit</a>
                                    <form action="{{ route('karyawan.destroy', $row) }}" method="POST"
                                        onsubmit="return confirm('Hapus karyawan?')">
                                        @csrf @method('DELETE')
                                        <button
                                            class="px-3 py-1.5 text-sm rounded-lg border border-red-300 text-red-600 hover:bg-red-50">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-3 py-6 text-center text-gray-500">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <table class="w-full text-left">
                <thead class="bg-blue-400 text-sm">
                    <tr>
                        <th class="px-4 py-3">Kode</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Jabatan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($list as $row)
                        <tr>
                            <td class="px-4 py-3 font-medium">{{ $row->kode }}</td>
                            <td class="px-4 py-3">{{ $row->nama }}</td>
                            <td class="px-4 py-3">{{ $row->jabatan }}</td>
                            <td class="px-4 py-3">{{ ucfirst($row->status) }}</td>
                            <td class="px-4 py-2">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('karyawan1.nilai.form', ['karyawan' => $row->id, 'tahun' => date('Y')]) }}"
                                        class="px-3 py-1.5 text-sm rounded-lg border border-emerald-400 text-emerald-600 hover:bg-emerald-50">Input
                                        Nilai</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    {{-- modal --}}
    <div id="importModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40" data-close="true"></div>

        <div class="relative mx-auto mt-24 w-full max-w-xl rounded-2xl bg-white p-6 shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold">Import Karyawan dan KPI </h2>
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

            <form action="{{ route('import.master') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <input type="file" name="file" accept=".xlsx,.csv" class="w-full border rounded-xl px-3 py-2"
                    required>

                <div class="grid sm:grid-cols-3 gap-3 text-sm">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="update_karyawan" value="1" checked>
                        Update data karyawan jika sudah ada
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="replace_year" value="1">
                        Hapus nilai KPI tahun yang sama
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" name="clear_empty" value="1">
                        Sel kosong = hapus nilai
                    </label>
                </div>

                <div class="text-xs text-gray-500">
                    Template: <a class="text-blue-700 underline"
                        href="{{ asset('storage/templates/template_import_tmms.xlsx') }}">Download</a>
                    atau gunakan file yang aku kirim di chat ini.
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" id="cancelImport"
                        class="rounded-xl border px-5 py-2 hover:bg-gray-50">Batal</button>
                    <button class="rounded-xl bg-green-600 text-white px-5 py-2 hover:bg-green-700">Import</button>
                </div>
            </form>
        </div>
    </div>


    <div class="mt-4">{{ $list->links() }}</div>
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
