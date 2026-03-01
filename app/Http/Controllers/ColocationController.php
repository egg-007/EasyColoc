<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreColocationRequest;
use App\Http\Requests\UpdateColocationRequest;
use App\Models\Colocation;
use App\Services\ColocationService;

class ColocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('colocation.index');
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
    public function show(Colocation $colocation)
    {
        return view('colocation.show', compact('colocation'));
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
        $user = auth()->user();

        $isOwner = $colocation->memberships()->where('user_id', $user->id)->where('role', 'owner')->whereNull('left_at')->exists();

        if (! $isOwner) {
            abort(403);
        }

        $colocation->update($request->validated());

        return redirect()->route('colocations.show', $colocation)->with('success', 'Colocation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Colocation $colocation)
    {
         $user = auth()->user();

        $isOwner = $colocation->memberships()->where('user_id', $user->id)->where('role', 'owner')->whereNull('left_at')->exists();

        if (! $isOwner) {
            abort(403);
        }

        $colocation->delete();

        return redirect()->route('colocations.index')->with('success', 'Colocation deleted successfully.');
    }
    
}
