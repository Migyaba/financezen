<?php

use App\Http\Controllers\WebhookController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\BudgetController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\DebtController;
use App\Http\Controllers\User\SavingsController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\User\SubscriptionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::view('/tarifs', 'pricing')->name('pricing');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/mentions-legales', 'pages.legal')->name('legal');
Route::view('/confidentialite', 'pages.privacy')->name('privacy');

Route::middleware(['auth', 'verified'])->group(function () {
    // Debug route - temporary
    Route::get('/debug', function() {
        $user = auth()->user();
        $txCount = $user->transactions()->count();
        $catCount = $user->budgetCategories()->count();
        return response()->json([
            'authenticated' => true,
            'user_id' => $user->id,
            'user_email' => $user->email,
            'transactions_count' => $txCount,
            'categories_count' => $catCount,
            'recent_transactions' => $user->transactions()->latest()->limit(3)->get(['id', 'type', 'amount', 'transaction_date']),
        ]);
    })->name('debug');

    // Shared routes (no subscription check)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/checkout', [SubscriptionController::class, 'checkout'])->name('subscription.checkout');
    Route::get('/subscription/callback', [SubscriptionController::class, 'callback'])->name('subscription.callback');
    Route::get('/subscription/success', [SubscriptionController::class, 'success'])->name('subscription.success');

    // Protected routes (subscription required)
    Route::middleware(['check.subscription'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('budget', BudgetController::class)->except(['show']);
        Route::post('/budget/item', [BudgetController::class, 'updateItem'])->name('budget.item.update');
        Route::get('/budget/{year}/{month}', [BudgetController::class, 'show'])->name('budget.show');

        Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
        Route::get('/transactions/export/pdf', [TransactionController::class, 'exportPdf'])->name('transactions.export.pdf');
        Route::resource('transactions', TransactionController::class);

        Route::resource('debts', DebtController::class);
        Route::post('/debts/{debt}/payment', [DebtController::class, 'addPayment'])->name('debts.payment');

        Route::resource('savings', SavingsController::class);
        Route::post('/savings/{savingsGoal}/contribution', [SavingsController::class, 'addContribution'])->name('savings.contribution');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::get('/reports/export/csv', [ReportController::class, 'exportCsv'])->name('reports.export.csv');
    });
});

Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [AdminDashboardController::class, 'reports'])->name('reports');
    // Users
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/extend', [\App\Http\Controllers\Admin\UserController::class, 'extendSubscription'])->name('users.extend');

    // Subscriptions
    Route::get('/subscriptions', [\App\Http\Controllers\Admin\SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::put('/subscriptions/{subscription}/validate', [\App\Http\Controllers\Admin\SubscriptionController::class, 'validatePayment'])->name('subscriptions.validate');
    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

// FedaPay Webhook (excluded from CSRF)
Route::post('/webhook/fedapay', [WebhookController::class, 'fedapay'])->name('webhook.fedapay');

require __DIR__.'/auth.php';
