@props(['type' => 'info', 'message'])

@php
    $classes = [
        'info' => 'bg-blue-100 border-blue-400 text-blue-700',
        'success' => 'bg-green-100 border-green-400 text-green-700',
        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
        'error' => 'bg-red-100 border-red-400 text-red-700',
    ];

    $icons = [
        'info' => 'fas fa-info-circle',
        'success' => 'fas fa-check-circle',
        'warning' => 'fas fa-exclamation-triangle',
        'error' => 'fas fa-exclamation-circle',
    ];

    $class = $classes[$type] ?? $classes['info'];
    $icon = $icons[$type] ?? $icons['info'];
@endphp

<div class="border px-4 py-3 rounded mb-4 {{ $class }}">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="{{ $icon }}"></i>
        </div>
        <div class="ml-3">
            <p class="text-sm">{{ $message }}</p>
        </div>
    </div>
</div>
