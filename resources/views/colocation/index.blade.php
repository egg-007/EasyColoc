@extends('layouts.app')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">My Colocations</h1>

    <a href="{{ route('colocations.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
        + New Colocation
    </a>
</div>

@forelse(auth()->user()->colocations as $colocation)
    <div class="bg-white p-4 rounded shadow mb-4 flex justify-between items-center">
        <div>
            <h2 class="text-lg font-semibold">
                {{ $colocation->name }}
            </h2>
            <p class="text-sm text-gray-500">
                {{ $colocation->description }}
            </p>
        </div>

        <a href="{{ route('colocations.show', $colocation) }}"
           class="text-indigo-600 hover:underline">
            View →
        </a>
    </div>
@empty
    <div class="bg-white p-6 rounded shadow text-center text-gray-500">
        You are not part of any colocation yet.
    </div>
@endforelse

@endsection