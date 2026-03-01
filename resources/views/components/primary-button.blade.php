<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-3 bg-primary border border-transparent rounded-xl font-bold text-sm text-white tracking-wide hover:bg-primary-dark transition active:scale-[0.98] shadow-lg shadow-primary/20']) }}>
    {{ $slot }}
</button>
