@props([
    'text' => '',
    'tone' => 'info', // info|success|warning|danger
])

@php
    $map = [
        'info' => 'bg-slate-100 text-slate-700 border-slate-200',
        'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'warning' => 'bg-amber-50 text-amber-700 border-amber-200',
        'danger' => 'bg-rose-50 text-rose-700 border-rose-200',
    ];
    $cls = $map[$tone] ?? $map['info'];
@endphp

<span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-lg border {{ $cls }}">
    {{ $text }}
</span>
