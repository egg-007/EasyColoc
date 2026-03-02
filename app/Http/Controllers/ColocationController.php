<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreColocationRequest;
use App\Http\Requests\UpdateColocationRequest;
use App\Models\Colocation;
use App\Models\User;
use App\Services\ColocationService;
use App\Services\BalanceService;

class ColocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('colocation.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()->memberships()->whereNull('left_at')->exists()) {
            return redirect()->route('colocations.index')->with('error', 'Vous appartenez déjà à une colocation active.');
        }

        return view('colocation.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreColocationRequest $request, ColocationService $colocationService)
    {
        $colocation = $colocationService->createForUser(auth()->user(), $request->validated());

        return redirect()->route('colocations.show', $colocation)->with('success', 'Colocation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Colocation $colocation, BalanceService $balanceService)
    {
        $month = request('month'); // e.g. "2023-10"
        
        $expensesQuery = $colocation->expenses()->with('payer', 'category');
        
        if ($month) {
            $expensesQuery->where('expense_date', 'like', $month . '%');
        }

        $expenses = $expensesQuery->latest('expense_date')->get();

        $colocation->load('memberships.user');

        $balances = $balanceService->calculate($colocation);
        $settlements = $balanceService->settlements($colocation);

        return view('colocation.show', compact('colocation', 'balances', 'settlements', 'expenses', 'month'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Colocation $colocation)
    {
        return view('colocation.edit', compact('colocation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateColocationRequest $request, Colocation $colocation)
    {
        $colocation->update($request->validated());

        return redirect()->route('colocations.show', $colocation)->with('success', 'Colocation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Colocation $colocation, BalanceService $balanceService)
    {
        $user = auth()->user();

        $isOwner = $colocation->memberships()->where('user_id', $user->id)->where(function($q) {
            $q->where('role', 'owner')->orWhere('role', 'Owner');
        })->whereNull('left_at')->exists();

        if (! $isOwner) {
            abort(403, 'Unauthorized action.');
        }

        $balances = $balanceService->calculate($colocation);

        $activeMemberships = $colocation->memberships()->whereNull('left_at')->get();

        foreach ($activeMemberships as $membership) {
            $balance = $balances[$membership->user_id]['balance'] ?? 0;
            if ($balance < 0) {
                $membership->user->decrement('reputation_score');
            } else {
                $membership->user->increment('reputation_score');
            }
            $membership->update(['left_at' => now()]);
        }

        $colocation->update(['status' => 'cancelled']);

        return redirect()->route('dashboard')->with('success', 'Colocation cancelled successfully.');
    }
    public function leave(User $user, Colocation $colocation, BalanceService $balanceService)
{
    $balances = $balanceService->calculate($colocation);

    $balance = $balances[$user->id]['balance'] ?? 0;

    if ($balance < 0) {
        $user->decrement('reputation_score');
    } else {
        $user->increment('reputation_score');
    }


    $colocation->memberships()
        ->where('user_id', $user->id)
        ->whereNull('left_at')
        ->update(['left_at' => now()]);
}
    
}
