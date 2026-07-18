@extends('admin.layouts.admin')

@section('title', 'Player Profiles - CrickTracker')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl h-fit">
        <h3 class="text-lg font-bold text-white mb-4">Enroll New Athlete Profile</h3>
        <form action="{{ route('admin.players.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="first_name" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">First Name</label>
                    <input type="text" id="first_name" name="first_name" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
                </div>
                <div>
                    <label for="last_name" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
                </div>
            </div>
            <div>
                <label for="player_team_id" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Assigned Team Group</label>
                <select id="player_team_id" name="team_id" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
                    <option value="" disabled selected>Select Team Franchise</option>
                    @foreach($realTeams ?? [] as $t)
                        <option value="{{ $t->team_id ?? $t->TEAM_ID }}">{{ $t->name ?? $t->NAME }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full bg-emerald-500 text-slate-950 text-xs font-bold uppercase tracking-wide py-3 rounded-xl hover:bg-emerald-400 transition">Commit Athlete Record</button>
        </form>
    </div>
    <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
        <h3 class="text-lg font-bold text-white mb-4">Active System Rosters</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-950 text-slate-400 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="p-4 rounded-l-xl">ID</th>
                        <th class="p-4">Full Athlete Name</th>
                        <th class="p-4 rounded-r-xl">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse(array_merge($battingSquad ?? [], $bowlingSquad ?? []) as $index => $player)
                        <tr class="hover:bg-slate-850/50">
                            <td class="p-4 font-mono text-slate-400">{{ $player->player_id ?? $player->PLAYER_ID ?? ($index + 1) }}</td>
                            <td class="p-4 text-white font-medium">{{ $player->player_name ?? $player->PLAYER_NAME }}</td>
                            <td class="p-4 text-slate-300"><span class="bg-slate-950 px-2 py-1 rounded text-xs border border-slate-800 text-emerald-400 font-semibold">Enrolled</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="p-4 text-center text-slate-500">No active player metrics available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection