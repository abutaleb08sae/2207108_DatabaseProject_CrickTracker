@extends('admin.layouts.admin')

@section('title', 'Schedule Fixtures - CrickTracker')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl h-fit">
        <h3 class="text-lg font-bold text-white mb-4">Generate Match Slot</h3>
        <form action="{{ route('admin.fixtures.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="team1_id" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Team A (Home)</label>
                <select id="team1_id" name="team1_id" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
                    <option value="" disabled selected>Select Home Team</option>
                    @foreach($realTeams ?? [] as $t)
                        <option value="{{ $t->team_id ?? $t->TEAM_ID }}">{{ $t->name ?? $t->NAME }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="team2_id" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Team B (Away)</label>
                <select id="team2_id" name="team2_id" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
                    <option value="" disabled selected>Select Away Team</option>
                    @foreach($realTeams ?? [] as $t)
                        <option value="{{ $t->team_id ?? $t->TEAM_ID }}">{{ $t->name ?? $t->NAME }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="match_date" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Match Commencing Date/Time</label>
                <input type="datetime-local" id="match_date" name="match_date" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
            </div>
            <button type="submit" class="w-full bg-emerald-500 text-slate-950 text-xs font-bold uppercase tracking-wide py-3 rounded-xl hover:bg-emerald-400 transition">Publish Fixture Row</button>
        </form>
    </div>
    <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
        <h3 class="text-lg font-bold text-white mb-4">Scheduled Tournament Fixtures</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-950 text-slate-400 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="p-4 rounded-l-xl">Match Details</th>
                        <th class="p-4 rounded-r-xl">Operational Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($matches ?? [] as $match)
                        <tr class="hover:bg-slate-850/50">
                            <td class="p-4">
                                <div class="text-white font-medium">{{ $match->team1_name ?? $match->TEAM1_NAME ?? 'Team One' }} vs {{ $match->team2_name ?? $match->TEAM2_NAME ?? 'Team Two' }}</div>
                                <div class="text-xs text-slate-500 font-mono mt-0.5">Match Ref: #{{ $match->match_id ?? $match->MATCH_ID ?? 1 }}</div>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded text-xs font-bold tracking-wide {{ ($match->match_status ?? $match->MATCH_STATUS ?? '') == 'Live' ? 'bg-emerald-950 text-emerald-400 border border-emerald-800' : 'bg-slate-950 text-slate-400 border border-slate-800' }}">
                                    {{ $match->match_status ?? $match->MATCH_STATUS ?? 'Scheduled' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="p-4 text-center text-slate-500">No active fixture arrays found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection