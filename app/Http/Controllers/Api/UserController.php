<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * GET /api/user/profile
     * Retourne le profil utilisateur avec salaire et paramètres
     */
    public function profile(Request $request)
    {
        $user = auth()->user();
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'currency' => $user->currency,
            'monthly_salary' => (float)$user->monthly_salary,
            'freelance_split' => $user->freelance_split,
            'trial_ends_at' => $user->trial_ends_at,
            'role' => $user->role,
        ]);
    }

    /**
     * GET /api/user/settings
     * Retourne les paramètres utilisateur (dépenses fixes, objectifs)
     */
    public function settings(Request $request)
    {
        $user = auth()->user();
        
        return response()->json([
            'monthly_salary' => (float)$user->monthly_salary,
            'loyer' => (float)$user->loyer,
            'eau_electricite' => (float)$user->eau_electricite,
            'internet' => (float)$user->internet,
            'nourriture' => (float)$user->nourriture,
            'essence' => (float)$user->essence,
            'total_fixed_expenses' => (float)(
                $user->loyer + 
                $user->eau_electricite + 
                $user->internet + 
                $user->nourriture + 
                $user->essence
            ),
            'dette_initiale' => (float)$user->dette_initiale,
            'objectif_fonds_urgence' => (float)$user->objectif_fonds_urgence,
            'freelance_split' => $user->freelance_split,
        ]);
    }

    /**
     * GET /api/user/freelance-split
     * Retourne la répartition freelance 50/30/20
     */
    public function freelanceSplit(Request $request)
    {
        $split = $request->user()->freelance_split ?? '50/30/20';
        [$dette_pct, $epargne_pct, $loisirs_pct] = array_map('intval', explode('/', $split));

        return response()->json([
            'dette_pct' => $dette_pct,
            'epargne_pct' => $epargne_pct,
            'loisirs_pct' => $loisirs_pct,
            'raw' => $split,
        ]);
    }
}
