<?php

namespace App\Services;

use App\Models\Colocation;
use App\Models\User;
use Exception;


class ColocationService
{
    public function createForUser(User $user, array $data)
    {

        if ($user->memberships()->whereNull('left_at')->exists()) {
            throw new Exception('You are already in an active colocation.');
        }
        $colocation = Colocation::create($data);
        $colocation->users()->attach($user->id,[
            'role' => 'owner'
            ]);
        return $colocation;
    }
}
