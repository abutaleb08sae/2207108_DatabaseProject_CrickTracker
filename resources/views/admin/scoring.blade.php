@extends('admin.layouts.admin')

@section('title', 'Live Scoring Panel - CrickTracker')

@section('content')
<div class="flex flex-col lg:flex-row gap-8">
    <div class="flex-1 bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6 md:p-8">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-white tracking-tight">Log Current Ball Outcome</h2>
            <p class="text-slate-400 text-sm mt-1">Submit dynamic data parameters directly downstream to active transactional packages.</p>
        </div>

        <form action="{{ route('admin.matches.storeBall') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="match_id" value="{{ $activeMatch->match_id ?? $activeMatch->MATCH_ID ?? 1 }}">
            <input type="hidden" name="innings" value="{{ $activeMatch->current_innings ?? $activeMatch->CURRENT_INNINGS ?? 1 }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="batsman_id" class="block text-sm font-bold uppercase tracking-wider text-slate-300 mb-2">On-Strike Batsman</label>
                    <select id="batsman_id" name="batsman_id" required class="w-full bg-slate-950 border border-slate-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-white rounded-xl px-4 py-3 text-sm font-medium transition outline-none">
                        <option value="" disabled selected>Select Batsman</option>
                        @foreach($battingSquad ?? [] as $player)
                            <option value="{{ $player->player_id ?? $player->PLAYER_ID }}">{{ $player->player_name ?? $player->PLAYER_NAME }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="bowler_id" class="block text-sm font-bold uppercase tracking-wider text-slate-300 mb-2">Active Bowler</label>
                    <select id="bowler_id" name="bowler_id" required class="w-full bg-slate-950 border border-slate-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-white rounded-xl px-4 py-3 text-sm font-medium transition outline-none">
                        <option value="" disabled selected>Select Bowler</option>
                        @foreach($bowlingSquad ?? [] as $player)
                            <option value="{{ $player->player_id ?? $player->PLAYER_ID }}">{{ $player->player_name ?? $player->PLAYER_NAME }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold uppercase tracking-wider text-slate-300 mb-3">Runs Scored From Bat</label>
                <div class="grid grid-cols-6 gap-3">
                    @foreach([0,1,2,3,4,6] as $run)
                        <label class="cursor-pointer group">
                            <input type="radio" name="runs_scored" value="{{ $run }}" class="sr-only peer" {{ $run == 0 ? 'checked' : '' }}>
                            <div class="bg-slate-950 border border-slate-700 text-white text-center py-3 rounded-xl font-bold transition group-hover:bg-slate-800 peer-checked:bg-emerald-500 peer-checked:text-slate-950 peer-checked:border-emerald-500 text-base shadow-sm">
                                {{ $run }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label for="extra_type" class="block text-sm font-bold uppercase tracking-wider text-slate-300 mb-2">Extras Registry</label>
                    <div class="flex space-x-3">
                        <select id="extra_type" name="extra_type" class="bg-slate-950 border border-slate-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-white rounded-xl px-4 py-3 text-sm font-medium transition outline-none flex-1">
                            <option value="">None (Normal Delivery)</option>
                            <option value="WIDE">Wide Ball</option>
                            <option value="NOBALL">No Ball</option>
                            <option value="BYE">Bye</option>
                            <option value="LEGBYE">Leg Bye</option>
                        </select>
                        <input type="number" name="extra_runs" value="0" min="0" max="10" aria-label="Extra Runs Amount" class="w-20 text-center bg-slate-950 border border-slate-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-white rounded-xl py-3 text-sm font-bold outline-none">
                    </div>
                </div>
                <div>
                    <label for="wicket_type" class="block text-sm font-bold uppercase tracking-wider text-slate-300 mb-2">Wicket Outcome</label>
                    <select id="wicket_type" name="wicket_type" class="w-full bg-slate-950 border border-slate-700 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 text-white rounded-xl px-4 py-3 text-sm font-medium transition outline-none">
                        <option value="">Not Out / Safe Delivery</option>
                        <option value="BOWLED">Bowled</option>
                        <option value="CAUGHT">Caught Out</option>
                        <option value="LBW">L.B.W.</option>
                        <option value="RUNOUT">Run Out</option>
                        <option value="STUMPED">Stumped</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-bold uppercase tracking-wider text-slate-300 mb-2">Ball Commentary Feed Text</label>
                <textarea id="description" name="description" placeholder="Describe the action event outcome briefly for the marquee live feed..." rows="3" class="w-full bg-slate-950 border border-slate-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-white placeholder-slate-600 rounded-xl p-4 text-sm font-medium transition outline-none resize-none"></textarea>
            </div>

            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-sm font-bold uppercase tracking-wider py-4 rounded-xl transition shadow-lg shadow-emerald-500/10 active:translate-y-px">
                ⚡ Transmit Event to Oracle Instance
            </button>
        </form>
    </div>

    <!-- Live Side Metrics Panel -->
    <div class="w-full lg:w-96 bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6 flex flex-col justify-between max-h-[500px]">
        <div>
            <span class="text-xs font-bold uppercase tracking-widest text-slate-500 block">Live State Tracker</span>
            <h3 class="text-xl font-bold text-white mt-1 border-b border-slate-800 pb-4">
                {{ $activeMatch->team1_short_name ?? $activeMatch->TEAM1_SHORT_NAME ?? 'TEAM 1' }} 
                <span class="text-slate-500 text-sm font-medium mx-1">vs</span> 
                {{ $activeMatch->team2_short_name ?? $activeMatch->TEAM2_SHORT_NAME ?? 'TEAM 2' }}
            </h3>
            
            <div class="py-8 text-center">
                <span class="text-xs font-bold tracking-wider text-slate-400 uppercase block mb-1">Current Score Metrics</span>
                <div class="text-6xl font-black text-white tracking-tight">
                    {{ $activeMatch->team1_score ?? $activeMatch->TEAM1_SCORE ?? 0 }}<span class="text-slate-500 font-light">/</span>{{ $activeMatch->team1_wickets ?? $activeMatch->TEAM1_WICKETS ?? 0 }}
                </div>
                <span class="inline-block bg-slate-950 border border-slate-800 text-slate-300 rounded-full px-4 py-1.5 text-xs font-semibold mt-4">
                    Overs Logged: {{ number_format($activeMatch->team1_overs ?? $activeMatch->TEAM1_OVERS ?? 0.0, 1) }}
                </span>
            </div>
        </div>

        <div class="bg-slate-950 rounded-xl p-4 border border-slate-800 space-y-2 text-xs">
            <div class="flex justify-between"><span class="text-slate-400">Match Status:</span><span class="text-emerald-400 font-bold uppercase">{{ $activeMatch->match_status ?? $activeMatch->MATCH_STATUS ?? 'Live' }}</span></div>
            <div class="flex justify-between"><span class="text-slate-400">Current Innings:</span><span class="text-white font-mono font-semibold">{{ $activeMatch->current_innings ?? $activeMatch->CURRENT_INNINGS ?? 1 }}</span></div>
        </div>
    </div>
</div>
@endsection