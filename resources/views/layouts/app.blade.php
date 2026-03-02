<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'EasyColoc') }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-white shadow">
    <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-indigo-600">
            EasyColoc
        </a>

        <div class="space-x-4">
            <a href="{{ route('colocations.index') }}" class="text-gray-700 hover:text-indigo-600">
                My Colocations
            </a>

            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="text-red-500 hover:text-red-700">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<main class="max-w-6xl mx-auto px-6 py-8">

    {{-- Flash --}}
    @if(session('success'))
        <div class="mb-6 bg-green-100 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-100 text-red-700 px-4 py-3 rounded">
            {{ $errors->first() }}
        </div>
    @endif

    @yield('content')

</main>

</body>
</html>