@php
    $oldSubs = collect(old('sub_kriteria', []));
    $subs = isset($kriteria)
        ? ($oldSubs->isNotEmpty()
            ? $oldSubs
            : $kriteria->subKriteria->map(
                fn($s) => [
                    'id' => $s->id,
                    'kode' => $s->kode_sub,
                    'nama' => $s->nama_sub_kriteria,
                    'bobot_percent' => $s->bobot,
                ],
            ))
        : ($oldSubs->isNotEmpty()
            ? $oldSubs
            : collect([['kode' => null, 'nama' => null, 'bobot_percent' => null]]));

@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium">Kode Kriteria</label>
        <input type="text" name="kode_kriteria" value="{{ old('kode_kriteria', $kriteria->kode_kriteria ?? '') }}"
            class="mt-1 w-full border rounded-xl px-3 py-2" placeholder="C1" required>
    </div>

    <div>
        <label class="block text-sm font-medium">Nama Kriteria</label>
        <input type="text" name="nama_kriteria" value="{{ old('nama_kriteria', $kriteria->nama_kriteria ?? '') }}"
            class="mt-1 w-full border rounded-xl px-3 py-2" placeholder="Produktifitas" required>
    </div>

    <div>
        <label class="block text-sm font-medium">Jabatan</label>
        <select name="jabatan" class="mt-1 w-full border rounded-xl px-3 py-2">
            @php $jab = old('jabatan', $kriteria->jabatan ?? ''); @endphp
            <option value="" {{ $jab === '' ? 'selected' : '' }}>- Pilih -</option>
            <option value="Operator" {{ $jab === 'Operator' ? 'selected' : '' }}>Operator</option>
            <option value="Mekanik" {{ $jab === 'Mekanik' ? 'selected' : '' }}>Mekanik</option>
            <option value="HSSE" {{ $jab === 'HSSE' ? 'selected' : '' }}>HSSE</option>
        </select>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium">Tipe</label>
            <select name="tipe" class="mt-1 w-full border rounded-xl px-3 py-2" required>
                @php $tipe = old('tipe', $kriteria->tipe ?? 'benefit'); @endphp
                <option value="benefit" {{ $tipe === 'benefit' ? 'selected' : '' }}>Benefit</option>
                <option value="cost" {{ $tipe === 'cost' ? 'selected' : '' }}>Cost</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium">Bobot Kriteria (%)</label>
            <div class="mt-1 flex items-stretch gap-2">
                <input type="number" step="0.01" min="0" max="100" name="bobot_percent"
                    value="{{ old('bobot_percent', isset($kriteria) ? $kriteria->bobot * 100 : '') }}"
                    class="w-full border rounded-xl px-3 py-2" placeholder="45.00" required>
                <span class="inline-flex items-center px-3 border rounded-xl">%</span>
            </div>
        </div>
    </div>
</div>

<hr class="my-6 border-gray-200" />

<div class="flex items-center justify-between mb-2">
    <h2 class="font-semibold">Sub-Kriteria</h2>
    <div class="text-sm text-gray-500">Jika <em>kode</em> kosong, akan diisi otomatis (mis: C1.1, C1.2)</div>
</div>

<div class="overflow-x-auto bg-gray-50 border rounded-2xl">
    <table class="w-full text-left text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-3 py-2 w-32">Kode</th>
                <th class="px-3 py-2">Nama Sub-Kriteria</th>
                <th class="px-3 py-2 w-36">Bobot</th>
                <th class="px-3 py-2 w-20 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody id="subBody" class="divide-y">
            @foreach ($subs as $i => $sub)
                <tr class="sub-row">
                    <td class="px-3 py-2">
                        @if (isset($sub['id']))
                            <input type="hidden" name="sub_kriteria[{{ $i }}][id]"
                                value="{{ $sub['id'] }}">
                        @endif
                        <input type="text" name="sub_kriteria[{{ $i }}][kode]"
                            value="{{ $sub['kode'] ?? '' }}" class="w-full border rounded-lg px-2 py-1" placeholder="">
                    </td>
                    <td class="px-3 py-2">
                        <input type="text" name="sub_kriteria[{{ $i }}][nama]"
                            value="{{ $sub['nama'] ?? '' }}" class="w-full border rounded-lg px-2 py-1"
                            placeholder="Nama sub-kriteria" required>
                    </td>
                    <td class="px-3 py-2">
                        <div class="flex items-stretch gap-2">
                            <input type="number" step="0.01" min="0" max="100"
                                name="sub_kriteria[{{ $i }}][bobot_percent]"
                                value="{{ $sub['bobot_percent'] ?? (isset($sub['bobot']) ? $sub['bobot'] * 100 : '') }}"
                                class="w-full border rounded-lg px-2 py-1" placeholder="25.00" required>
                            <span class="inline-flex items-center px-2 border rounded-lg">%</span>
                        </div>
                    </td>
                    <td class="px-3 py-2 text-right">
                        <button type="button"
                            class="btn-remove px-2 py-1 rounded-lg border border-rose-300 text-rose-600 hover:bg-rose-50">Hapus</button>
                        <input type="hidden" name="sub_kriteria[{{ $i }}][_delete]" value="0">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-3 flex items-center justify-between">
    <button type="button" id="btnAddSub"
        class="inline-flex items-center border-blue-400 text-blue-600 rounded-xl border px-4 py-2 hover:bg-blue-50">+
        Tambah Sub</button>
    <div class="text-sm text-gray-600">
        Tips: total bobot sub-kriteria harus = jumlah bobot kriteria
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="rounded-xl bg-emerald-500 font-semibold text-white px-5 py-2 hover:bg-emerald-700">
        {{ $mode === 'edit' ? 'Perbarui' : 'Simpan' }}
    </button>
    <a href="{{ route('kriteria.index') }}"
        class="rounded-xl border-2 bg-slate-100 px-5 py-2 hover:bg-gray-200">Batal</a>
</div>

@push('scripts')
    <script>
        (function() {
            const form = document.getElementById('kriteriaForm');
            const subBody = document.getElementById('subBody');
            const btnAdd = document.getElementById('btnAddSub');

            const getKodeKriteria = () => (form.querySelector('[name="kode_kriteria"]').value || 'C?').trim();

            // generate index berikutnya berdasar jumlah baris yang belum di-delete
            function nextIndex() {
                const rows = subBody.querySelectorAll('tr.sub-row');
                let max = -1;
                rows.forEach(row => {
                    const nameAny = row.querySelector('input[name^="sub_kriteria["][name$="[nama]"]')?.name;
                    if (!nameAny) return;
                    const match = nameAny.match(/sub_kriteria\[(\d+)\]\[nama\]/);
                    if (match) {
                        max = Math.max(max, parseInt(match[1], 10));
                    }
                });
                return max + 1;
            }

            function currentVisibleCount() {
                return [...subBody.querySelectorAll('tr.sub-row')].filter(r => r.style.display !== 'none').length;
            }

            function addRow(defaults = {
                kode: '',
                nama: '',
                bobot_percent: ''
            }) {
                const idx = nextIndex();
                const tr = document.createElement('tr');
                tr.className = 'sub-row';
                tr.innerHTML = `
      <td class="px-3 py-2">
        <input type="text" name="sub_kriteria[${idx}][kode]" value="${defaults.kode || ''}" class="w-full border rounded-lg px-2 py-1" placeholder="">
      </td>
      <td class="px-3 py-2">
        <input type="text" name="sub_kriteria[${idx}][nama]" value="${defaults.nama || ''}" class="w-full border rounded-lg px-2 py-1" placeholder="Nama sub-kriteria" required>
      </td>
      <td class="px-3 py-2">
        <div class="flex items-stretch gap-2">
        <input type="number" step="0.01" min="0" max="100"
               name="sub_kriteria[${idx}][bobot_percent]"
               value="${defaults.bobot_percent || ''}"
               class="w-full border rounded-lg px-2 py-1" placeholder="25.00" required>
        <span class="inline-flex items-center px-2 border rounded-lg">%</span>
      </div>
      </td>
      <td class="px-3 py-2 text-right">
        <button type="button" class="btn-remove px-2 py-1 rounded-lg border border-red-300 text-red-600 hover:bg-red-50">Hapus</button>
        <input type="hidden" name="sub_kriteria[${idx}][_delete]" value="0">
      </td>
    `;
                subBody.appendChild(tr);
                autoFillCodes(); // isi kode kalau kosong
            }

            function autoFillCodes() {
                const kodeKriteria = getKodeKriteria();
                const rows = [...subBody.querySelectorAll('tr.sub-row')].filter(r => r.style.display !== 'none');
                rows.forEach((row, i) => {
                    const kodeInput = row.querySelector('input[name^="sub_kriteria["][name$="[kode]"]');
                    if (!kodeInput) return;
                    // ganti kalau kosong atau masih auto default "C?.x"
                    if (!kodeInput.value.trim() || /^C\?\.\d+$/.test(kodeInput.value.trim())) {
                        kodeInput.value = `${kodeKriteria}.${i+1}`;
                    }
                });
            }

            // Add row button
            btnAdd?.addEventListener('click', () => addRow());

            // Remove row (soft delete)
            subBody.addEventListener('click', (e) => {
                const btn = e.target.closest('.btn-remove');
                if (!btn) return;
                const tr = btn.closest('tr.sub-row');
                const del = tr.querySelector('input[name$="[_delete]"]');
                if (del) {
                    del.value = '1';
                }
                tr.style.display = 'none';
                // jaga minimal satu baris terlihat
                if (currentVisibleCount() === 0) {
                    addRow();
                }
            });

            // Auto regenerate kode saat kode kriteria berubah
            form.querySelector('[name="kode_kriteria"]').addEventListener('input', autoFillCodes);

            // Saat load pertama, isi kode kosong
            document.addEventListener('DOMContentLoaded', autoFillCodes);
        })();
    </script>
@endpush
