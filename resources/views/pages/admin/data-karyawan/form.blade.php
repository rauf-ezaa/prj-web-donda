{{-- resources/views/pages/admin/data-karyawan/data-karyawan-form.blade.php --}}
@php
    $isEdit = isset($karyawan);
@endphp

<div class="mb-4">
    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Nama Pegawai</label>
    <input type="text" name="nama_karyawan"
        value="{{ old('nama_karyawan', $isEdit ? $karyawan->nama_karyawan : '') }}"
        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('nama_karyawan') border-red-500 @enderror">
    @error('nama_karyawan') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">NRK</label>
    <input type="text" name="nrk"
        value="{{ old('nrk', $isEdit ? $karyawan->nrk : '') }}"
        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('nrk') border-red-500 @enderror">
    @error('nrk') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">NIP</label>
    <input type="text" name="nip"
        value="{{ old('nip', $isEdit ? $karyawan->nip : '') }}"
        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('nip') border-red-500 @enderror">
    @error('nip') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
</div>

@unless($isEdit)
<div class="mb-4">
    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
    <input type="password" name="password"
        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('password') border-red-500 @enderror">
    @error('password') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
</div>
@endunless

<div class="mb-4">
    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Jabatan</label>
    <select name="jabatan" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('jabatan') border-red-500 @enderror">
        <option value="">-- Pilih Jabatan --</option>
        @foreach(['pengguna' => 'Pengguna', 'tata usaha' => 'Tata Usaha', 'supervisor' => 'Supervisor'] as $value => $label)
            <option value="{{ $value }}" {{ old('jabatan', $isEdit ? $karyawan->jabatan : '') == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('jabatan') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
</div>

<div class="mb-4">
    <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
    <select name="role" class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900 dark:text-white @error('role') border-red-500 @enderror">
        <option value="">-- Pilih Role --</option>
        @foreach($roles as $role)
            <option value="{{ $role->name }}" {{ old('role', $isEdit ? $userRole : '') == $role->name ? 'selected' : '' }}>
                {{ ucfirst($role->name) }}
            </option>
        @endforeach
    </select>
    @error('role') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
</div>

<button type="submit" class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
    {{ $isEdit ? 'Update' : 'Simpan' }}
</button>
