@extends('layouts.app')

@section('content')

<div class="bg-white p-10 rounded-2xl shadow text-center max-w-xl mx-auto">

    <h1 class="text-3xl font-bold mb-4">
        Welcome, {{ auth()->user()->name }}
    </h1>

    <p class="text-gray-500 mb-8">
        Reputation Score:
        <span class="font-semibold">
            {{ auth()->user()->reputation_score }}
        </span>
    </p>

    <a href="{{ route('colocations.create') }}"
       class="inline-block bg-indigo-600 text-white px-8 py-3 rounded-xl hover:bg-indigo-700 transition">
        + Create New Colocation
    </a>

</div>

@endsection