<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Colocation;
use App\Models\Expense;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with stats.
     */
    public function dashboard()
    {
        $this->authorizeAdmin();

        $stats = [
            'total_users' => User::count(),
            'total_colocations' => Colocation::count(),
            'total_expenses' => Expense::sum('amount'),
            'banned_users' => User::whereNotNull('banned_at')->count(),
        ];

        $users = User::withCount('colocations')->latest()->paginate(20);

        return view('admin.dashboard', compact('stats', 'users'));
    }

    /**
     * Ban a user.
     */
    public function ban(User $user)
    {
        $this->authorizeAdmin();

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas vous bannir vous-même.');
        }

        $user->update(['banned_at' => now()]);

        return back()->with('success', "L'utilisateur {$user->name} a été banni.");
    }

    /**
     * Unban a user.
     */
    public function unban(User $user)
    {
        $this->authorizeAdmin();

        $user->update(['banned_at' => null]);

        return back()->with('success', "L'utilisateur {$user->name} a été débanni.");
    }

    /**
     * Ensure the user is admin.
     */
    private function authorizeAdmin()
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Accès non autorisé.');
        }
    }
}
