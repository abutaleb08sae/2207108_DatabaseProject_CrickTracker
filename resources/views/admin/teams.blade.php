@extends('admin.layouts.admin')

@section('title', 'Team Management - CrickTracker')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl h-fit">
        <h3 class="text-lg font-bold text-white mb-4">Register New Franchise Team</h3>
        <form action="{{ route('admin.teams.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="team_name" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Franchise Name</label>
                <input type="text" id="team_name" name="name" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
            </div>
            <div>
                <label for="team_short_name" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Short Name/Abbreviation</label>
                <input type="text" id="team_short_name" name="short_name" placeholder="e.g. KCC" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
            </div>
            <button type="submit" class="w-full bg-emerald-500 text-slate-950 text-xs font-bold uppercase tracking-wide py-3 rounded-xl hover:bg-emerald-400 transition">Save Team Row</button>
        </form>
    </div>
    <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
        <h3 class="text-lg font-bold text-white mb-4">Existing Franchises Registry</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-950 text-slate-400 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="p-4 rounded-l-xl">ID</th>
                        <th class="p-4">Franchise Name</th>
                        <th class="p-4 rounded-r-xl">Short Code</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($realTeams ?? [] as $t)
                        <tr class="hover:bg-slate-850/50">
                            <td class="p-4 font-mono text-slate-400">{{ $t->team_id ?? $t->TEAM_ID }}</td>
                            <td class="p-4 text-white font-medium">{{ $t->name ?? $t->NAME }}</td>
                            <td class="p-4">
                                <span class="bg-slate-950 px-2.5 py-1 rounded text-emerald-400 font-bold border border-slate-800">
                                    {{ $t->short_name ?? $t->SHORT_NAME }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center text-slate-500">No database franchise entries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection