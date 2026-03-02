@extends('layouts.app')

@section('content')

<div class="bg-white p-6 rounded shadow max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Colocation</h1>

    <form method="POST" action="{{ route('colocations.update', $colocation) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block mb-1 font-medium">Name</label>
            <input type="text" name="name"
                   value="{{ $colocation->name }}"
                   class="w-full border p-2 rounded"
                   required>
        </div>

        <div class="mb-6">
            <label class="block mb-1 font-medium">Description</label>
            <textarea name="description"
                      class="w-full border p-2 rounded">{{ $colocation->description }}</textarea>
        </div>

        <button class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Update
        </button>
    </form>
</div>

@endsection