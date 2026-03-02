@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto pb-12">
    
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Panneau d'Administration</h1>
        <p class="text-slate-500 mt-2 text-lg">Statistiques globales et modération des utilisateurs.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col items-center justify-center text-center">
            <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <p class="text-3xl font-extrabold text-slate-900">{{ $stats['total_users'] }}</p>
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wide">Utilisateurs Inscrits</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col items-center justify-center text-center">
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
            <p class="text-3xl font-extrabold text-slate-900">{{ $stats['total_colocations'] }}</p>
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wide">Colocations Créées</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col items-center justify-center text-center">
            <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-3xl font-extrabold text-slate-900">{{ number_format($stats['total_expenses'], 2) }} €</p>
            <p class="text-sm font-medium text-slate-500 uppercase tracking-wide">Total Échangé</p>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50">
            <h2 class="text-xl font-bold text-slate-800">Gestion des Utilisateurs</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-max">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider border-b border-slate-100">
                        <th class="p-4 font-bold">Utilisateur</th>
                        <th class="p-4 font-bold">Email</th>
                        <th class="p-4 font-bold">Rép.</th>
                        <th class="p-4 font-bold">Colocs</th>
                        <th class="p-4 font-bold">Statut</th>
                        <th class="p-4 font-bold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50/50 transition-colors {{ $u->banned_at ? 'bg-red-50/30' : '' }}">
                            <td class="p-4 font-medium text-slate-900 flex items-center gap-2">
                                @if($u->is_admin)
                                    <span title="Administrateur"><svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd"></path></svg></span>
                                @endif
                                {{ $u->name }}
                            </td>
                            <td class="p-4 text-slate-600">{{ $u->email }}</td>
                            <td class="p-4">
                                <span class="font-bold px-2 py-1 rounded {{ $u->reputation_score >= 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $u->reputation_score }}
                                </span>
                            </td>
                            <td class="p-4 text-slate-600">{{ $u->colocations_count }}</td>
                            <td class="p-4">
                                @if($u->banned_at)
                                    <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">Banni</span>
                                @else
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">Actif</span>
                                @endif
                            </td>
                            <td class="p-4 text-right">
                                @if($u->id !== auth()->id() && !$u->is_admin)
                                    @if($u->banned_at)
                                        <form method="POST" action="{{ route('admin.users.unban', $u) }}" class="inline-block" onsubmit="return confirm('Réactiver ce compte ?');">
                                            @csrf
                                            @method('PATCH')
                                            <button class="text-emerald-600 hover:text-emerald-800 font-bold text-sm bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded transition">Débannir</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.users.ban', $u) }}" class="inline-block" onsubmit="return confirm('Bannir ce compte définitivement ?');">
                                            @csrf
                                            @method('PATCH')
                                            <button class="text-red-600 hover:text-red-800 font-bold text-sm bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded transition">Bannir</button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500">Aucun utilisateur trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-4 border-t border-slate-100 bg-slate-50">
            {{ $users->links() }}
        </div>
    </div>
</div>

@endsection
