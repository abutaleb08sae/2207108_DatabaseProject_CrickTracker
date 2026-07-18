@extends('admin.layouts.admin')

@section('title', 'Schedule Fixtures - CrickTracker')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- 1. GENERATE MATCH SLOT FORM BLOCK -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl h-fit">
        <h3 class="text-lg font-bold text-white mb-4">Generate Match Slot</h3>
        
        @if(session('success'))
            <div class="bg-emerald-950 border border-emerald-800 text-emerald-400 text-xs rounded-xl p-3 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.fixtures.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <!-- Team 1 selection -->
            <div>
                <label for="team1_id" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Team A (Home/Batting First)</label>
                <select id="team1_id" name="team1_id" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
                    <option value="" disabled selected>Select Home Team</option>
                    @foreach($realTeams ?? [] as $t)
                        <option value="{{ $t->team_id ?? $t->TEAM_ID }}">{{ $t->name ?? $t->NAME }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Team 2 selection -->
            <div>
                <label for="team2_id" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Team B (Away/Bowling First)</label>
                <select id="team2_id" name="team2_id" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
                    <option value="" disabled selected>Select Away Team</option>
                    @foreach($realTeams ?? [] as $t)
                        <option value="{{ $t->team_id ?? $t->TEAM_ID }}">{{ $t->name ?? $t->NAME }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Match Datetime input context -->
            <div>
                <label for="match_date" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Match Commencing Date/Time</label>
                <input type="datetime-local" id="match_date" name="match_date" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
            </div>

            <!-- Venue location tracking -->
            <div>
                <label for="venue" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Match Venue Location</label>
                <input type="text" id="venue" name="venue" value="KUET Main Playground" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
            </div>

            <!-- Match runtime lifecycle status state -->
            <div>
                <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Match Initial Status</label>
                <select id="status" name="status" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
                    <option value="Upcoming" selected>Upcoming</option>
                    <option value="LIVE">LIVE</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-emerald-500 text-slate-950 text-xs font-bold uppercase tracking-wide py-3 rounded-xl hover:bg-emerald-400 transition">Publish Fixture Row</button>
        </form>
    </div>

    <!-- 2. DATA GRID CONTROLS AND RECORDS DISPLAY -->
    <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
        <h3 class="text-lg font-bold text-white mb-4">Scheduled Tournament Fixtures</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm align-middle">
                <thead class="bg-slate-950 text-slate-400 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="p-4 rounded-l-xl">Match Details</th>
                        <th class="p-4">Date / Venue</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center rounded-r-xl">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($matches ?? [] as $match)
                        <tr class="hover:bg-slate-850/50">
                            <!-- Match Matchup information block -->
                            <td class="p-4">
                                <div class="text-white font-medium">{{ $match->team1_name ?? $match->TEAM1_NAME ?? 'Team One' }} vs {{ $match->team2_name ?? $match->TEAM2_NAME ?? 'Team Two' }}</div>
                                <div class="text-xs text-slate-500 font-mono mt-0.5">Match Ref: #{{ $match->match_id ?? $match->MATCH_ID ?? 1 }}</div>
                            </td>
                            
                            <!-- Relational schedule meta data container -->
                            <td class="p-4">
                                <div class="text-slate-300 text-xs font-semibold">
                                    {{ isset($match->date) ? \Carbon\Carbon::parse($match->date)->format('M d, Y') : 'TBD' }} @if(!empty($match->time)) - {{ $match->time }} @endif
                                </div>
                                <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-rose-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                                    {{ $match->venue_name ?? $match->VENUE_NAME ?? 'KUET Main Ground' }}
                                </div>
                            </td>

                            <!-- Dynamic interface conditional visualization badges -->
                            <td class="p-4 text-center">
                                @php 
                                    $currentStatus = $match->status ?? $match->STATUS ?? $match->match_status ?? $match->MATCH_STATUS ?? 'Upcoming';
                                @endphp
                                @if(strtoupper($currentStatus) == 'LIVE')
                                    <span class="px-2.5 py-1 rounded text-xs font-bold tracking-wide bg-rose-950 text-rose-400 border border-rose-800 uppercase animate-pulse">
                                        ● LIVE
                                    </span>
                                @elseif(strtoupper($currentStatus) == 'COMPLETED')
                                    <span class="px-2.5 py-1 rounded text-xs font-bold tracking-wide bg-slate-800 text-slate-400 border border-slate-700 uppercase">
                                        Completed
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded text-xs font-bold tracking-wide bg-emerald-950 text-emerald-400 border border-emerald-800 uppercase">
                                        Upcoming
                                    </span>
                                @endif
                            </td>

                            <!-- Destructive actions drop operations context -->
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.fixtures.destroy', $match->match_id ?? $match->MATCH_ID) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely erase this match fixture? All dashboard dependencies will instantly drop.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-slate-950 border border-slate-800 rounded-xl text-rose-500 hover:bg-rose-950 hover:border-rose-800 transition shadow-inner" title="Delete Fixture Row">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-500 font-medium">No active fixture arrays found inside current relational matrices.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection