<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Services\InvitationService;
use Illuminate\Http\Request;

class InvitationController extends Controller
{

    public function store(Request $request, Colocation $colocation, InvitationService $invitationService)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $invitationService->createInvitation($colocation,auth()->user(),$validated['email']);

        return redirect()->route('colocations.show', $colocation)->with('success', 'Invitation sent successfully.');

    }


    public function accept(string $token, InvitationService $invitationService)
    {
        $colocation = $invitationService->acceptInvitation($token, auth()->user());
        return redirect()->route('colocations.show', $colocation)->with('success', 'Invitation accepted successfully.');
        
    }

    public function refuse(string $token, InvitationService $invitationService)
    {
    
        $invitationService->refuseInvitation($token, auth()->user());
        return redirect()->back()->with('success', 'Invitation refused successfully.');

    }
}
