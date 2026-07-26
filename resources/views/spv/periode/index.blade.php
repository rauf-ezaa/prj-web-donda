@extends('layouts.app')
@php $currentPageTitle = 'Kelola Periode'; @endphp
@section('content')
<div class="p-4 md:p-6">
    <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Kelola Periode</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Buka, kunci, atau buka kunci periode semester.</p>
        </div>
        <a href="{{ route('spv.periode.create') }}">
            <x-ui.button size="md" variant="primary">+ Buat Periode Baru</x-ui.button>
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-2.5 text-sm text-green-700 dark:bg-green-500/10 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-2.5 text-sm text-error-500">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-white/[0.02]">
                <tr class="text-left text-xs font-medium text-gray-500 dark:text-gray-400">
                    <th class="px-4 py-3">Periode</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Jumlah Opname</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($periodes as $p)
                    <tr class="text-gray-700 dark:text-gray-300">
                        <td class="px-4 py-3 font-medium">{{ $p->nama }}</td>
                        <td class="px-4 py-3">
                            @if($p->isTerkunci())
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600 dark:bg-gray-500/15 dark:text-gray-400">🔒 Terkunci</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-success-50 px-2 py-0.5 text-xs text-success-600 dark:bg-success-500/15 dark:text-success-400">Aktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $p->stok_opnames_count }}</td>
                        <td class="px-4 py-3 text-right">
                            @if($p->isTerkunci())
                                <form action="{{ route('spv.periode.buka-kunci', $p->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Buka kunci periode ini?')">
                                    @csrf
                                    <x-ui.button size="sm" variant="secondary" type="submit">Buka Kunci</x-ui.button>
                                </form>
                            @else
                                <form action="{{ route('spv.periode.kunci', $p->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Kunci periode ini? Pastikan sudah ada opname yang selesai.')">
                                    @csrf
                                    <x-ui.button size="sm" variant="primary" type="submit">Kunci Periode</x-ui.button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada periode.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $periodes->links() }}</div>
</div>
@endsection
