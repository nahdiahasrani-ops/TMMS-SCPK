@php
    $val = fn($key, $default = '') => old($key, $karyawan->$key ?? $default);
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium">Kode</label>
        <input type="text" name="kode" value="{{ $val('kode') }}" class="mt-1 w-full border rounded-xl px-3 py-2"
            placeholder="NIK / KODE-001" required>
        <p class="text-xs text-gray-500 mt-1">Harus unik. Contoh: OP-001</p>
    </div>

    <div>
        <label class="block text-sm font-medium">Nama</label>
        <input type="text" name="nama" value="{{ $val('nama') }}" class="mt-1 w-full border rounded-xl px-3 py-2"
            placeholder="Nama lengkap" required>
    </div>

    <div>
        <label class="block text-sm font-medium">Jabatan</label>
        @php $jab = $val('jabatan'); @endphp
        <select name="jabatan" class="mt-1 w-full border rounded-xl px-3 py-2" required>
            <option value="" {{ $jab === '' ? 'selected' : '' }}>- Pilih -</option>
            <option value="Operator" {{ $jab === 'Operator' ? 'selected' : '' }}>Operator</option>
            <option value="Mekanik" {{ $jab === 'Mekanik' ? 'selected' : '' }}>Mekanik</option>
            <option value="HSSE" {{ $jab === 'HSSE' ? 'selected' : '' }}>HSSE</option>
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium">Tanggal Masuk</label>
        <input type="date" name="tgl_masuk" value="{{ $val('tgl_masuk') }}"
            class="mt-1 w-full border rounded-xl px-3 py-2">
    </div>

    <div>
        <label class="block text-sm font-medium">Status</label>
        @php $st = $val('status','aktif'); @endphp
        <select name="status" class="mt-1 w-full border rounded-xl px-3 py-2" required>
            <option value="aktif" {{ $st === 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ $st === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <button type="submit" class="rounded-xl bg-emerald-500 text-white font-semibold px-5 py-2 hover:bg-emerald-700">
        {{ ($mode ?? 'create') === 'edit' ? 'Perbarui' : 'Simpan' }}
    </button>
    <a href="{{ route('karyawan.index') }}"
        class="rounded-xl border-2 bg-slate-100 px-5 py-2 hover:bg-gray-200">Batal</a>
</div>
