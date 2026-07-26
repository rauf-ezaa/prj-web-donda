
@props([
    'variant' => 'light',
    'size' => 'md',
    'color' => 'primary',
    'startIcon' => null,
    'endIcon' => null,
		'status'
])

@php
    $baseStyles = 'inline-flex items-center px-2.5 py-0.5 justify-center gap-1 rounded-full font-medium capitalize';

    $sizeStyles = [
        'sm' => 'text-xs',
        'md' => 'text-sm',
    ];

    $styles = match($status) {
        'draft'    => 'bg-gray-100 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400',
        'pending'  => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-400',
        'approved' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-400',
        'rejected' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-400',
        default    => 'bg-gray-100 text-gray-500 dark:bg-gray-500/15 dark:text-gray-400',
    };

    $label = match($status) {
        'draft'    => 'Draft',
        'pending'  => 'Menunggu Persetujuan',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        default    => ucfirst($status ?? '-'),
    };


    $variants = [
        'light' => [
            'primary' => 'bg-blue-50 text-blue-500 dark:bg-blue-500/15 dark:text-blue-400',
            'success' => 'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-500',
            'error' => 'bg-red-50 text-red-600 dark:bg-red-500/15 dark:text-red-500',
            'warning' => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-500/15 dark:text-orange-400',
            'info' => 'bg-sky-50 text-sky-500 dark:bg-sky-500/15 dark:text-sky-500',
            'light' => 'bg-gray-100 text-gray-700 dark:bg-white/5 dark:text-white/80',
            'dark' => 'bg-gray-500 text-white dark:bg-white/5 dark:text-white',
        ],
        'solid' => [
            'primary' => 'bg-blue-500 text-white dark:text-white',
            'success' => 'bg-green-500 text-white dark:text-white',
            'error' => 'bg-red-500 text-white dark:text-white',
            'warning' => 'bg-yellow-500 text-white dark:text-white',
            'info' => 'bg-sky-500 text-white dark:text-white',
            'light' => 'bg-gray-400 dark:bg-white/5 text-white dark:text-white/80',
            'dark' => 'bg-gray-700 text-white dark:text-white',
        ],
    ];

    $sizeClass = $sizeStyles[$size] ?? $sizeStyles['md'];
    $colorStyles = $variants[$variant][$color] ?? $variants['light']['primary'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium $styles"]) }}>
    {{ $label }}
</span>
