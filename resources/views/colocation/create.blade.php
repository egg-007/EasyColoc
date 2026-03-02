@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto">
    
    <div class="mb-8">
        <a href="{{ route('colocations.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800 flex items-center gap-1 mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Retour
        </a>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Créer une colocation</h1>
        <p class="text-slate-500 mt-1">Configurez votre nouvel espace partagé.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 sm:p-8">
            <form method="POST" action="{{ route('colocations.store') }}">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nom de la colocation <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name"
                           value="{{ old('name') }}"
                           class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-primary-500 focus:border-primary-500 block p-3 transition-colors"
                           placeholder="Ex: La Villa, Appart 42..."
                           required>
                </div>

                <div class="mb-8">
                    <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Description <span class="text-slate-400 font-normal">(Optionnel)</span></label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-primary-500 focus:border-primary-500 block p-3 transition-colors resize-none"
                              placeholder="Quelques mots pour décrire l'esprit de votre coloc...">{{ old('description') }}</textarea>
                </div>

                <div class="bg-slate-50 -mx-6 sm:-mx-8 -mb-6 sm:-mb-8 px-6 sm:px-8 py-4 sm:py-5 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="bg-primary-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-primary-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Créer ma colocation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection