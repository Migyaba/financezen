<?php

namespace App\Providers;

use App\Models\User;
use App\Models\BudgetCategory;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);

        // Partager les catégories globalement pour le FAB d'ajout rapide de transaction
        View::composer('layouts.app', function ($view) {
            if (auth()->check()) {
                $view->with('globalCategories', BudgetCategory::where('user_id', auth()->id())
                    ->orWhere('is_default', true)
                    ->orderBy('type')
                    ->orderBy('name')
                    ->get());
            }
        });
    }
}
