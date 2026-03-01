@props(['title', 'value', 'currency' => 'FCFA', 'icon', 'color' => 'primary', 'trend' => null, 'trendUp' => true])

@php
$colors = [
    'primary' => 'bg-primary/10 text-primary',
    'success' => 'bg-success/10 text-success',
    'warning' => 'bg-warning/10 text-warning',
    'danger' => 'bg-danger/10 text-danger',
    'indigo' => 'bg-indigo-500/10 text-indigo-500',
    'blue' => 'bg-blue-500/10 text-blue-500',
];
$colorClasses = $colors[$color] ?? $colors['primary'];
@endphp

<div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 hover:shadow-md transition group overflow-hidden relative">
    <div class="flex items-center justify-between relative z-10">
        <div>
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mb-1">{{ $title }}</p>
            <h3 class="text-2xl font-bold text-slate-900 dark:text-white flex items-baseline gap-1">
                {{ number_format($value, 0, ',', ' ') }}
                <span class="text-sm font-semibold text-slate-400">{{ $currency }}</span>
            </h3>
            
            @if($trend)
            <div class="mt-2 flex items-center gap-1">
                <span class="text-xs font-bold {{ $trendUp ? 'text-success' : 'text-danger' }}">
                    {{ $trendUp ? '↑' : '↓' }} {{ $trend }}
                </span>
                <span class="text-[10px] text-slate-400 font-medium">vs mois dernier</span>
            </div>
            @endif
        </div>

        <div class="w-12 h-12 rounded-xl {{ $colorClasses }} flex items-center justify-center group-hover:scale-110 transition duration-300">
            <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
        </div>
    </div>
    
    <!-- Background Decor -->
    <div class="absolute -right-4 -bottom-4 w-24 h-24 {{ $colorClasses }} opacity-5 rounded-full blur-2xl group-hover:opacity-10 transition"></div>
</div>
