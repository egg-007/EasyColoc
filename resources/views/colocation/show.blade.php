@extends('layouts.app')

@section('content')

<div class="container mx-auto p-6">

    {{-- Colocation Title --}}
    <h1 class="text-2xl font-bold mb-4">
        {{ $colocation->name }}
    </h1>

    <p class="mb-6 text-gray-600">
        {{ $colocation->description }}
    </p>

    {{-- Flash Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- ============================= --}}
    {{-- Members Section --}}
    {{-- ============================= --}}
    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-3">Members</h2>

        @foreach($colocation->users as $user)
            <div class="border p-3 rounded mb-2 flex justify-between">
                <div>
                    <strong>{{ $user->name }}</strong>
                    <span class="text-sm text-gray-500">
                        ({{ $user->pivot->role }})
                    </span>
                </div>

                <div>
                    Reputation:
                    <span class="font-semibold">
                        {{ $user->reputation_score }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ============================= --}}
    {{-- Invitation Section (Owner Only) --}}
    {{-- ============================= --}}

    @php
        $isOwner = $colocation->isOwnedBy(auth()->user());
    @endphp

    @if($isOwner)
        <div class="border p-4 rounded mb-8">
            <h2 class="text-xl font-semibold mb-3">Invite Member</h2>

            <form method="POST" action="{{ route('invitations.store', $colocation) }}">
                @csrf

                <div class="mb-3">
                    <input 
                        type="email"
                        name="email"
                        placeholder="Enter email address"
                        class="border p-2 rounded w-full"
                        required
                    >
                </div>

                <button 
                    type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded"
                >
                    Send Invitation
                </button>
            </form>
        </div>

        {{-- Pending Invitations --}}
        <div class="border p-4 rounded">
            <h2 class="text-xl font-semibold mb-3">Pending Invitations</h2>

            @forelse($colocation->invitations->where('status', 'pending') as $invitation)
                <div class="border p-3 rounded mb-2">
                    <p><strong>Email:</strong> {{ $invitation->email }}</p>
                    <p>
                        <strong>Expires:</strong>
                        {{ $invitation->expired_at ? $invitation->expired_at->diffForHumans() : 'No expiration' }}
                    </p>
                    <p><strong>Status:</strong> {{ $invitation->status }}</p>
                </div>
            @empty
                <p class="text-gray-500">No pending invitations.</p>
            @endforelse
        </div>
    @endif

</div>

@endsection