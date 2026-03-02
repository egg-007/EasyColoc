@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">
    <!-- Header Section -->
    <div class="mb-10 text-center sm:text-left flex flex-col sm:flex-row justify-between items-center gap-6">
        <div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-2">
                Bonjour, {{ auth()->user()->name }} 👋
            </h1>
            <p class="text-lg text-slate-500">
                Gérez vos colocations et suivez vos dépenses partagées.
            </p>
        </div>
    </div>

    <!-- Stats & Reputation Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <!-- Score Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Score de Réputation</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-extrabold text-slate-900">{{ auth()->user()->reputation_score }}</span>
                    <span class="text-sm font-medium {{ auth()->user()->reputation_score >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                        pts
                    </span>
                </div>
            </div>
            <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center border-4 {{ auth()->user()->reputation_score >= 0 ? 'border-emerald-100 text-emerald-500' : 'border-red-100 text-red-500' }}">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    @if(auth()->user()->reputation_score >= 0)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5" />
                    @endif
                </svg>
            </div>
        </div>

        <!-- Quick Access Card -->
        <div class="bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl p-6 shadow-md text-white flex flex-col justify-center">
            <h3 class="text-xl font-bold mb-2">Prêt à partager ?</h3>
            <p class="text-primary-100 mb-6 font-medium">Rejoignez une colocation existante ou créez la vôtre.</p>
            <div class="flex gap-3">
                @if(!auth()->user()->memberships()->whereNull('left_at')->exists())
                <a href="{{ route('colocations.create') }}" class="bg-white text-primary-700 px-5 py-2.5 rounded-xl font-semibold hover:bg-primary-50 transition-colors shadow-sm inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nouvelle Coloc
                </a>
                @else
                <a href="{{ route('colocations.index') }}" class="bg-white text-primary-700 px-5 py-2.5 rounded-xl font-semibold hover:bg-primary-50 transition-colors shadow-sm inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Voir ma coloc
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection