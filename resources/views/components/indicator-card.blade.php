@props(['label' => '', 'status' => 'success', 'message' => '', 'description' => '', 'icon' => 'check-circle'])

@php
$colorMap = [
    'success' => [
        'bg' => 'bg-emerald-100/50 dark:bg-emerald-950/20',
        'text' => 'text-emerald-700 dark:text-emerald-400',
        'circle' => 'bg-emerald-500',
        'border' => 'border-emerald-200/50 dark:border-emerald-800/30'
    ],
    'warning' => [
        'bg' => 'bg-amber-100/50 dark:bg-amber-950/20',
        'text' => 'text-amber-700 dark:text-amber-400',
        'circle' => 'bg-amber-500',
        'border' => 'border-amber-200/50 dark:border-amber-800/30'
    ],
    'danger' => [
        'bg' => 'bg-rose-100/50 dark:bg-rose-950/20',
        'text' => 'text-rose-700 dark:text-rose-400',
        'circle' => 'bg-rose-500',
        'border' => 'border-rose-200/50 dark:border-rose-800/30'
    ]
];
$colors = $colorMap[$status] ?? $colorMap['success'];
@endphp

<div class="p-5 rounded-2xl border {{ $colors['border'] }} {{ $colors['bg'] }} flex flex-col items-center text-center group hover:scale-[1.02] transition duration-300">
    <div class="flex items-center gap-2 mb-3">
        <div class="w-3 h-3 rounded-full {{ $colors['circle'] }} shadow-[0_0_12px_rgba(0,0,0,0)] group-hover:shadow-{{ $status }} transition"></div>
        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400">{{ $label }}</p>
    </div>

    <div class="w-12 h-12 rounded-xl bg-white dark:bg-slate-800/50 flex items-center justify-center {{ $colors['text'] }} mb-4 shadow-sm">
        <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
    </div>

    <h4 class="font-bold text-slate-900 dark:text-white mb-1">{{ $message }}</h4>
    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">{{ $description }}</p>
</div>
