@if(session('success') || session('error') || session('warning') || session('info'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-2"
     class="fixed top-6 right-6 z-[100] max-w-sm w-full">
    
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/40 rounded-2xl shadow-lg backdrop-blur-sm">
        <div class="w-9 h-9 rounded-xl bg-emerald-500 flex items-center justify-center text-white flex-shrink-0"><i data-lucide="check" class="w-5 h-5"></i></div>
        <p class="text-sm font-bold text-emerald-800 dark:text-emerald-300 flex-1">{{ session('success') }}</p>
        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    @endif

    @if(session('error'))
    <div class="flex items-center gap-3 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800/40 rounded-2xl shadow-lg backdrop-blur-sm">
        <div class="w-9 h-9 rounded-xl bg-red-500 flex items-center justify-center text-white flex-shrink-0"><i data-lucide="alert-circle" class="w-5 h-5"></i></div>
        <p class="text-sm font-bold text-red-800 dark:text-red-300 flex-1">{{ session('error') }}</p>
        <button @click="show = false" class="text-red-400 hover:text-red-600 transition"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    @endif

    @if(session('warning'))
    <div class="flex items-center gap-3 p-4 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/40 rounded-2xl shadow-lg backdrop-blur-sm">
        <div class="w-9 h-9 rounded-xl bg-amber-500 flex items-center justify-center text-white flex-shrink-0"><i data-lucide="alert-triangle" class="w-5 h-5"></i></div>
        <p class="text-sm font-bold text-amber-800 dark:text-amber-300 flex-1">{{ session('warning') }}</p>
        <button @click="show = false" class="text-amber-400 hover:text-amber-600 transition"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    @endif

    @if(session('info'))
    <div class="flex items-center gap-3 p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800/40 rounded-2xl shadow-lg backdrop-blur-sm">
        <div class="w-9 h-9 rounded-xl bg-blue-500 flex items-center justify-center text-white flex-shrink-0"><i data-lucide="info" class="w-5 h-5"></i></div>
        <p class="text-sm font-bold text-blue-800 dark:text-blue-300 flex-1">{{ session('info') }}</p>
        <button @click="show = false" class="text-blue-400 hover:text-blue-600 transition"><i data-lucide="x" class="w-4 h-4"></i></button>
    </div>
    @endif
</div>
@endif
