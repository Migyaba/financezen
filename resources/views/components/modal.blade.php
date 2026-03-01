@props(['id' => 'modal-' . uniqid(), 'title' => ''])

<div id="{{ $id }}" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-200">
    <div class="bg-white dark:bg-slate-800 w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden animate-in slide-in-from-bottom-8 duration-300">
        <!-- Header -->
        <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
            <h3 class="text-xl font-bold text-slate-800 dark:text-white">{{ $title }}</h3>
            <button @click="closeModal()" class="p-2 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                <i data-lucide="x" class="w-5 h-5 text-slate-400"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-8">
            {{ $slot }}
        </div>
    </div>
</div>
