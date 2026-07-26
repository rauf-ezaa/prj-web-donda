@extends('layouts.app')
@php $currentPageTitle = 'Riwayat Aktivitas Saya'; @endphp
@section('content')
<div class="p-4 md:p-6">
    <div class="mb-6">
        <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Riwayat Aktivitas Saya</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Gabungan seluruh aktivitas kamu di semua modul, diurutkan dari yang terbaru.
        </p>
    </div>

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Modul</th>
                    <th class="px-4 py-3">Kode/Referensi</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($riwayat as $index => $r)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-xs text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">
                                {{ $r['modul_label'] }}
                            </span>
                        </td>
                        <td class="px-4 py-3 font-medium">{{ $r['kode'] }}</td>
                        <td class="px-4 py-3">{{ str_replace('_', ' ', ucfirst($r['status'])) }}</td>
                        <td class="px-4 py-3">{{ $r['tanggal']->format('d M Y, H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
