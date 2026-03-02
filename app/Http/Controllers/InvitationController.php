<?php

namespace App\Http\Controllers;

use App\Mail\ColocationInvitation;
use App\Models\Colocation;
use App\Services\InvitationService;
use App\Http\Requests\StoreInvitationRequest;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{

    public function store(StoreInvitationRequest $request, Colocation $colocation, InvitationService $invitationService)
    {
        $validated = $request->validated();

        $invitation = $invitationService->createInvitation($colocation, auth()->user(), $validated['email']);
        
        Mail::to($validated['email'])->send(
            new ColocationInvitation($invitation, $colocation->name, auth()->user()->name)
        );

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
