@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Mes Colocations</h1>
            <p class="text-slate-500 mt-1">Gérez et consultez les détails de vos colocations.</p>
        </div>

        @if(!auth()->user()->memberships()->whereNull('left_at')->exists())
        <a href="{{ route('colocations.create') }}"
           class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2.5 rounded-xl font-semibold shadow-sm transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Nouvelle Colocation
        </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse(auth()->user()->colocations as $colocation)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col hover:shadow-md transition-shadow relative overflow-hidden group">
                
                <div class="absolute top-0 right-0 p-4 opacity-50 text-slate-200 group-hover:scale-110 group-hover:text-primary-50 transition-all duration-500 ease-out">
                    <svg class="w-20 h-20" fill="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </div>

                <div class="relative z-10 flex-grow">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="bg-primary-100 text-primary-700 text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">
                            {{ $colocation->pivot->role }}
                        </span>
                        @if($colocation->status === 'cancelled')
                             <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">
                                Annulée
                            </span>
                        @endif
                    </div>
                    
                    <h2 class="text-xl font-bold text-slate-900 mb-2 truncate" title="{{ $colocation->name }}">
                        {{ $colocation->name }}
                    </h2>
                    
                    <p class="text-sm text-slate-500 line-clamp-2">
                        {{ $colocation->description ?: 'Aucune description fournie.' }}
                    </p>
                </div>

                <div class="relative z-10 mt-6 pt-4 border-t border-slate-100">
                    <a href="{{ route('colocations.show', $colocation) }}"
                       class="text-primary-600 hover:text-primary-800 font-semibold text-sm flex items-center gap-1 group-hover:gap-2 transition-all">
                        Consulter le tableau de bord
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-12 text-center text-slate-500">
                <svg class="w-16 h-16 mx-auto text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                <p class="text-lg font-medium text-slate-600 mb-1">Vous ne faites partie d'aucune colocation</p>
                <p class="text-sm">Créez-en une ou attendez une invitation.</p>
            </div>
        @endforelse
    </div>

</div>

@endsection