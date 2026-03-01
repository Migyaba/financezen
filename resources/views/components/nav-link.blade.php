@props(['active' => false, 'icon' => null, 'href'])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 px-4 py-3 rounded-xl bg-primary text-white shadow-md shadow-primary/20 transition group'
            : 'flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-slate-700/50 transition group';
@endphp

<a {{ $attributes->merge(['class' => $classes, 'href' => $href]) }}>
    @if($icon)
        <i data-lucide="{{ $icon }}" class="w-5 h-5 {{ $active ? 'text-white' : 'text-slate-500 group-hover:text-white' }} transition"></i>
    @endif
    <span class="font-medium text-sm tracking-wide">{{ $slot }}</span>
</a>
