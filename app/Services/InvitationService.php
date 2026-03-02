<?php

namespace App\Services;

use App\Models\Colocation;
use App\Models\Invitation;
use App\Models\User;
use Exception;


class  InvitationService
{
    public function createInvitation(Colocation $colocation, User $owner, string $email)
    {
        $isOwner = $colocation->memberships()->where('user_id', $owner->id)
        ->where('role', 'owner')->whereNull('left_at')->exists();
        if (! $isOwner) {
            throw new Exception('Only the owner can send invitations.');
        }
        return Invitation::create([
            'colocation_id' => $colocation->id,
            'email' => $email,
            'token' => bin2hex(random_bytes(16)),
            'status' => 'pending',
            'expired_at' => now()->addDays(3),
        ]);
    }

    public function acceptInvitation(string $token, User $user)
    {
        $invitation = Invitation::where('token', $token)->where('status', 'pending')->firstOrFail();

        if ($invitation->expired_at->isPast()) {
            $invitation->update(['status' => 'expired']);
            throw new Exception('This invitation has expired.');
        }

        if($invitation->email !== $user->email) {
            throw new Exception('This invitation is not for your email address.');
        }
        if($user->memberships()->whereNull('left_at')->exists()) {
            throw new Exception('You are already in an active colocation.');
        }

        $invitation->colocation->users()->attach($user->id, ['role' => 'member']);
        $invitation->update(['status' => 'accepted']);

        return $invitation->colocation;
    }


    public function refuseInvitation(string $token, User $user)
    {
        $invitation = Invitation::where('token', $token)->where('status', 'pending')->firstOrFail();

        if ($invitation->expired_at->isPast()) {
            $invitation->update(['status' => 'expired']);
            throw new Exception('This invitation has expired.');
        }

        if($invitation->email !== $user->email) {
            throw new Exception('This invitation is not for your email address.');
        }

        $invitation->update(['status' => 'refused']);
    }


    
}