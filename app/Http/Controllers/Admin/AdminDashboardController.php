<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use App\Models\Subscription;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'user')->count();
        $activeSubscribers = Subscription::where('status', 'active')->where('ends_at', '>', now())->distinct('user_id')->count('user_id');
        $onTrial = User::where('role', 'user')->where('trial_ends_at', '>', now())->whereDoesntHave('subscriptions', fn($q) => $q->where('status', 'active'))->count();
        $revenueThisMonth = Payment::where('status', 'success')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount');
        $newUsersThisMonth = User::where('role', 'user')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $expired = User::where('role', 'user')->where('trial_ends_at', '<', now())->whereDoesntHave('subscriptions', fn($q) => $q->where('status', 'active')->where('ends_at', '>', now()))->count();
        $conversionRate = ($onTrial + $activeSubscribers) > 0 ? round(($activeSubscribers / ($onTrial + $activeSubscribers + $expired)) * 100, 1) : 0;

        // Charts data
        $signupLabels = [];
        $signupData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $signupLabels[] = $date->format('d/m');
            $signupData[] = User::whereDate('created_at', $date)->count();
        }

        $revenueLabels = [];
        $revenueData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenueLabels[] = $date->translatedFormat('M');
            $revenueData[] = Payment::where('status', 'success')->whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->sum('amount');
        }

        $recentUsers = User::where('role', 'user')->latest()->limit(10)->get();
        $recentPayments = Payment::with('user')->latest()->limit(10)->get();
        $pendingPayments = Payment::where('status', 'pending')->count();

        return view('admin.dashboard.index', compact(
            'totalUsers', 'activeSubscribers', 'onTrial', 'revenueThisMonth',
            'newUsersThisMonth', 'expired', 'conversionRate',
            'signupLabels', 'signupData', 'revenueLabels', 'revenueData',
            'recentUsers', 'recentPayments', 'pendingPayments'
        ));
    }

    public function reports()
    {
        // Simple analytics for the admin reports page
        $revenueData = Payment::where('status', 'success')
            ->selectRaw('TO_CHAR(created_at, \'YYYY-MM\') as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        $userStats = [
            'active' => User::where('role', 'user')->where('is_active', true)->count(),
            'inactive' => User::where('role', 'user')->where('is_active', false)->count(),
        ];

        return view('admin.reports.index', compact('revenueData', 'userStats'));
    }
}
