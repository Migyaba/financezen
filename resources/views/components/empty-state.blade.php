@props(['icon' => 'inbox', 'title' => 'Aucune donnée', 'description' => '', 'cta' => null, 'ctaUrl' => '#'])

<div class="flex flex-col items-center justify-center py-16 px-6">
    <div class="w-20 h-20 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center text-slate-300 dark:text-slate-600 mb-6">
        <i data-lucide="{{ $icon }}" class="w-10 h-10"></i>
    </div>
    <h3 class="font-bold text-slate-800 dark:text-white mb-2">{{ $title }}</h3>
    @if($description)
        <p class="text-sm text-slate-500 dark:text-slate-400 text-center max-w-sm mb-6">{{ $description }}</p>
    @endif
    @if($cta)
        <a href="{{ $ctaUrl }}" class="px-6 py-2.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-primary-dark transition text-sm">{{ $cta }}</a>
    @endif
</div>
