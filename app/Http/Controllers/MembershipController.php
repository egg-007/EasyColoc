<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Services\BalanceService;
use App\Models\Payment;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Membership $membership)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Membership $membership)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Membership $membership)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Membership $membership, BalanceService $balanceService)
    {
        $user = auth()->user();
        $isOwner = $membership->colocation->memberships()->where('user_id', $user->id)
            ->where(function($q) { $q->where('role', 'owner')->orWhere('role', 'Owner'); })
            ->whereNull('left_at')->exists();

        if ($membership->user_id !== $user->id && !$isOwner) {
            abort(403, 'Unauthorized action.');
        }

        $balances = $balanceService->calculate($membership->colocation);
        $balance = $balances[$membership->user_id]['balance'] ?? 0;

        if ($balance < 0) {
            $membership->user->decrement('reputation_score');
            
            if ($isOwner && $membership->user_id !== $user->id) {
                Payment::create([
                    'colocation_id' => $membership->colocation_id,
                    'from_user_id' => $membership->user_id,
                    'to_user_id' => $user->id,
                    'amount' => abs($balance),
                    'paid_at' => now(),
                ]);
            }
        } else {
            $membership->user->increment('reputation_score');
        }

        $membership->update(['left_at' => now()]);

        return redirect()->back()->with('success', 'Membre retiré/A quitté la colocation.');
    }
}
