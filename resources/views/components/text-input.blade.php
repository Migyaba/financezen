@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 focus:border-primary focus:ring-primary rounded-xl shadow-sm transition']) }}>
