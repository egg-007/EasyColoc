@extends('layouts.app')

@section('content')

@php
    $isOwner = $colocation->isOwnedBy(auth()->user());
@endphp

<div class="max-w-7xl mx-auto pb-12">
    
    <!-- Header Page -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('colocations.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800 flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Retour
                </a>
                @if($colocation->status === 'cancelled')
                    <span class="bg-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-full uppercase tracking-wide">
                        Annulée
                    </span>
                @endif
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                {{ $colocation->name }}
            </h1>
            <p class="text-slate-500 mt-2 text-lg">
                {{ $colocation->description ?: 'Tableau de bord de la colocation.' }}
            </p>
        </div>

        @if($isOwner && $colocation->status !== 'cancelled')
        <div class="flex items-center gap-3">
            <form action="{{ route('colocations.destroy', $colocation) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette colocation ? Cette action est irréversible.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-white border border-red-200 text-red-600 px-4 py-2 rounded-xl font-medium hover:bg-red-50 transition-colors shadow-sm">
                    Annuler la coloc
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: Operations (Expenses & Payments & Categories) -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Balances / Who owes who Overview -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Soldes et Dettes
                    </h2>
                </div>
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Balances List -->
                    <div>
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4 border-b pb-2">Soldes Actuels</h3>
                        <div class="space-y-3">
                            @foreach($balances as $data)
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-slate-700">{{ $data['user']->name }}</span>
                                    <span class="font-bold px-3 py-1 rounded-lg {{ $data['balance'] > 0 ? 'bg-emerald-100 text-emerald-700' : ($data['balance'] < 0 ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700') }}">
                                        {{ number_format($data['balance'], 2) }} €
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Settlements List -->
                    <div>
                        <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4 border-b pb-2">Qui doit à qui</h3>
                        <div class="space-y-4">
                            @forelse($settlements as $settlement)
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 relative">
                                    <div class="flex items-center gap-3">
                                        <div class="font-medium text-slate-800 flex-1 truncate">{{ $settlement['from']->name }}</div>
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        <div class="font-medium text-slate-800 flex-1 text-right truncate">{{ $settlement['to']->name }}</div>
                                    </div>
                                    <div class="mt-2 text-center text-lg font-bold text-red-600">
                                        {{ number_format($settlement['amount'], 2) }} €
                                    </div>
                                    
                                    <!-- Mark Paid Button -->
                                    <form method="POST" action="{{ route('payments.store', $colocation) }}" class="mt-3">
                                        @csrf
                                        <input type="hidden" name="from_user_id" value="{{ $settlement['from']->id }}">
                                        <input type="hidden" name="to_user_id" value="{{ $settlement['to']->id }}">
                                        <input type="hidden" name="amount" value="{{ $settlement['amount'] }}">
                                        <button class="w-full bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-bold py-1.5 rounded-lg transition-colors">
                                            Marquer Payé
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="text-center text-slate-500 py-6">
                                    <svg class="w-12 h-12 mx-auto text-emerald-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <p class="font-medium">Tous les comptes sont à jour.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expenses List -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path></svg>
                        Dépenses
                    </h2>

                    <!-- Month Filter -->
                    <form method="GET" class="flex gap-2 w-full sm:w-auto">
                        <input type="month" name="month" value="{{ $month }}" class="border-slate-200 rounded-lg text-sm bg-white" onchange="this.form.submit()">
                        @if($month)
                            <a href="{{ route('colocations.show', $colocation) }}" class="px-3 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 text-sm font-medium">Reset</a>
                        @endif
                    </form>
                </div>
                
                <div class="p-0">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider border-b border-slate-100">
                                <th class="p-4 font-bold">Date</th>
                                <th class="p-4 font-bold">Quoi</th>
                                <th class="p-4 font-bold hidden sm:table-cell">Catégorie</th>
                                <th class="p-4 font-bold">Payé par</th>
                                <th class="p-4 font-bold text-right">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($expenses as $expense)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="p-4 text-slate-600 whitespace-nowrap">{{ \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y') }}</td>
                                    <td class="p-4 font-medium text-slate-900">{{ $expense->title }}</td>
                                    <td class="p-4 hidden sm:table-cell">
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-semibold">{{ $expense->category->name }}</span>
                                    </td>
                                    <td class="p-4 text-slate-600">{{ $expense->payer->name }}</td>
                                    <td class="p-4 text-right font-bold text-slate-900">{{ number_format($expense->amount, 2) }} €</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-500">Aucune dépense pour cette période.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Expense Form -->
            @if($colocation->status !== 'cancelled')
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50">
                    <h2 class="text-xl font-bold text-slate-800">Ajouter une dépense</h2>
                </div>
                
                <form action="{{ route('expenses.store', $colocation) }}" method="POST" class="p-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Titre</label>
                            <input type="text" name="title" required class="w-full border-slate-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 bg-slate-50 p-2.5 border" placeholder="Courses de la semaine...">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Montant (€)</label>
                            <input type="number" step="0.01" name="amount" required class="w-full border-slate-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 bg-slate-50 p-2.5 border" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Date</label>
                            <input type="date" name="expense_date" required class="w-full border-slate-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 bg-slate-50 p-2.5 border" value="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Payé par</label>
                            <select name="payer_id" class="w-full border-slate-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 bg-slate-50 p-2.5 border">
                                @foreach($colocation->memberships->whereNull('left_at') as $memb)
                                    <option value="{{ $memb->user->id }}" {{ $memb->user->id === auth()->id() ? 'selected' : '' }}>{{ $memb->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Catégorie</label>
                            <select name="category_id" class="w-full border-slate-200 rounded-xl focus:ring-primary-500 focus:border-primary-500 bg-slate-50 p-2.5 border">
                                @foreach($colocation->categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @if($colocation->categories->isEmpty())
                                <p class="text-sm text-red-500 mt-2">Veuillez d'abord créer une catégorie via le panneau Owner.</p>
                            @endif
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-primary-600 text-white font-bold py-3 rounded-xl hover:bg-primary-700 transition shadow-sm disabled:opacity-50" {{ $colocation->categories->isEmpty() ? 'disabled' : '' }}>
                        Enregistrer la Dépense
                    </button>
                </form>
            </div>
            @endif

        </div>

        <!-- Right Column: Members & Settings -->
        <div class="space-y-8">
            
            <!-- Members List -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Membres
                    </h2>
                </div>
                
                <ul class="divide-y divide-slate-100 p-2">
                    @foreach($colocation->memberships as $membership)
                        <li class="p-4 flex items-center justify-between {{ $membership->left_at ? 'opacity-50 grayscale' : '' }}">
                            <div class="flex items-center gap-3 space-x-2">
                                <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold">
                                    {{ substr($membership->user->name, 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-800">{{ $membership->user->name }}</span>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 block w-fit shadow-xs">{{ $membership->role }}</span>
                                        <span class="text-xs font-medium text-slate-400">Rép: {{ $membership->user->reputation_score }}</span>
                                    </div>
                                    @if($membership->left_at)
                                        <span class="text-xs text-red-500 font-medium mt-1">Parti le {{ \Carbon\Carbon::parse($membership->left_at)->format('d/m/Y') }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            @if(!$membership->left_at && $colocation->status !== 'cancelled')
                                @if($isOwner && $membership->user_id !== auth()->id())
                                    <form method="POST" action="{{ route('memberships.destroy', $membership) }}" onsubmit="return confirm('Retirer ce membre ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 font-medium text-xs bg-red-50 hover:bg-red-100 px-2 py-1.5 rounded transition">Retirer</button>
                                    </form>
                                @endif
                                
                                @if(!$isOwner && $membership->user_id === auth()->id())
                                    <form method="POST" action="{{ route('memberships.destroy', $membership) }}" onsubmit="return confirm('Quitter la colocation ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 font-medium text-xs bg-red-50 hover:bg-red-100 px-2 py-1.5 rounded transition">Quitter</button>
                                    </form>
                                @endif
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Owner Panel: Categories & Invites -->
            @if($isOwner && $colocation->status !== 'cancelled')
                <!-- Categories -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50">
                        <h2 class="text-xl font-bold text-slate-800">Catégories</h2>
                    </div>
                    
                    <div class="p-6">
                        <ul class="space-y-2 mb-4">
                            @foreach($colocation->categories as $category)
                                <li class="flex justify-between items-center bg-slate-50 px-3 py-2 rounded-lg border border-slate-100">
                                    <span class="font-medium text-slate-700">{{ $category->name }}</span>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>

                        <form action="{{ route('categories.store', $colocation) }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="text" name="name" placeholder="Nouvelle ref..." required class="border-slate-200 bg-slate-50 text-slate-900 border rounded-xl px-3 py-2 w-full text-sm focus:ring-primary-500">
                            <button type="submit" class="bg-slate-800 text-white rounded-xl px-4 py-2 font-bold text-sm hover:bg-slate-900 transition">Ajouter</button>
                        </form>
                    </div>
                </div>

                <!-- Invites -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50">
                        <h2 class="text-xl font-bold text-slate-800">Invitations</h2>
                    </div>
                    
                    <div class="p-6">
                        <form method="POST" action="{{ route('invitations.store', $colocation) }}" class="mb-6">
                            @csrf
                            <label class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                            <div class="flex gap-2">
                                <input type="email" name="email" placeholder="ami@example.com" class="border-slate-200 bg-slate-50 border rounded-xl px-3 py-2 w-full text-sm focus:ring-primary-500" required>
                                <button type="submit" class="bg-primary-600 text-white rounded-xl px-4 py-2 font-bold text-sm hover:bg-primary-700 transition flex-shrink-0">Inviter</button>
                            </div>
                        </form>

                        @php $pendings = $colocation->invitations->where('status', 'pending'); @endphp
                        @if($pendings->count() > 0)
                            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">En attente</h3>
                            <ul class="space-y-2">
                                @foreach($pendings as $invitation)
                                    <li class="bg-slate-50 px-3 py-3 rounded-lg border border-slate-100 text-sm flex flex-col gap-2">
                                        <div class="flex justify-between text-slate-600">
                                            <span class="truncate font-semibold">{{ $invitation->email }}</span>
                                            <span class="text-slate-400 text-xs flex-shrink-0">{{ $invitation->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="flex gap-2 items-center">
                                            <input type="text" readonly value="{{ route('invitations.accept', ['token' => $invitation->token]) }}" class="text-xs bg-white border border-slate-200 rounded px-2 py-1 w-full text-slate-500 focus:outline-none" onclick="this.select();">
                                            <span class="text-[10px] text-slate-400 font-bold uppercase whitespace-nowrap">Lien d'invitation</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endif

        </div>

    </div>
</div>

@endsection