@extends('layouts.app')
@section('content')
    <x-common.page-breadcrumb pageTitle="Data Pegawai" />

    @if(session('success'))
        <x-ui.alert variant="success" title="Success" :message="session('success')" linkHref="/" />
    @endif
    @if(session('error'))
        <x-ui.alert variant="error" title="Gagal" :message="session('error')" linkHref="/" />
    @endif

    <br>
    <div class="flex items-center gap-5">
        <a href="{{ route('data-pengguna.create') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            Tambah Data Pegawai
        </a>
    </div>
    <br>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="border-gray-200 border-y dark:border-gray-700">
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">No</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Nama Pegawai</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">NRK</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">NIP</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Jabatan</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Role</th>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($karyawans as $index => $karyawan)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $karyawans->firstItem() + $index }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $karyawan->nama_karyawan }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $karyawan->nrk }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $karyawan->nip }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ ucwords($karyawan->jabatan) }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ ucfirst($karyawan->user?->roles->first()?->name ?? '-') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('data-pengguna.edit', $karyawan->id) }}"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                                        Edit
                                    </a>
                                    <form action="{{ route('data-pengguna.destroy', $karyawan->users_id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-sm text-gray-500">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $karyawans->links() }}
        </div>
    </div>
@endsection
