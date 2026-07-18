@extends('admin.layouts.admin')

@section('title', 'Live Scoring Panel - CrickTracker')

@section('content')
<div class="space-y-6">
    <!-- Top Context Header Breadcrumb Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-800 pb-5 gap-4">
        <div>
            <h2 class="text-3xl font-black text-white tracking-tight flex items-center gap-3">
                <span>Live Console:</span>
                <span class="text-emerald-400">
                    {{ $activeMatch->team1_name ?? $activeMatch->TEAM1_NAME ?? 'Team A' }} 
                    <span class="text-slate-500 font-light text-xl lowercase mx-1">vs</span> 
                    {{ $activeMatch->team2_name ?? $activeMatch->TEAM2_NAME ?? 'Team B' }}
                </span>
            </h2>
            <p class="text-slate-400 text-sm mt-1">Manage transactional ball events, coordinate dynamic line-ups, and stream live analytical modules downstream.</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center space-x-2 text-sm bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 px-4 py-2.5 rounded-xl transition font-medium">
                <span>← Exit Control Room</span>
            </a>
        </div>
    </div>

    <!-- Feedback Notification Interceptor Engine -->
    @if(session('success'))
        <div class="bg-emerald-950/40 border border-emerald-500/30 text-emerald-400 p-4 rounded-xl text-sm font-medium flex items-center gap-2">
            <span>🎉</span> {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-950/40 border border-rose-500/30 text-rose-400 p-4 rounded-xl text-sm font-medium space-y-1">
            <span class="font-bold flex items-center gap-2">⚠️ Configuration / Database Engine Execution Errors:</span>
            <ul class="list-disc list-inside pl-2 text-xs opacity-90">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Active Lineup Management Panel Dropdowns Area -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl space-y-4">
        <div class="flex items-center space-x-2 text-slate-400 text-xs font-bold uppercase tracking-wider">
            <span>👥 Active Lineup Management</span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="batsman_id" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Batsman (On Strike)</label>
                <select id="batsman_id" name="batsman_id" form="ballScoringForm" required onchange="checkAndAutoSubmit()" class="w-full bg-slate-950 border border-slate-700 focus:border-emerald-500 text-white rounded-xl px-4 py-3 text-sm transition outline-none">
                    <option value="" disabled selected>Select Striker</option>
                    @foreach($battingSquad ?? [] as $player)
                        <option value="{{ $player->player_id ?? $player->PLAYER_ID }}">
                            {{ $player->player_name ?? $player->PLAYER_NAME ?? trim(($player->FIRST_NAME ?? $player->first_name ?? '') . ' ' . ($player->LAST_NAME ?? $player->last_name ?? '')) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="non_striker_id" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Batsman (Off Strike)</label>
                <select id="non_striker_id" name="non_striker_id" form="ballScoringForm" class="w-full bg-slate-950 border border-slate-700 focus:border-emerald-500 text-white rounded-xl px-4 py-3 text-sm transition outline-none">
                    <option value="" disabled selected>Select Non-Striker</option>
                    @foreach($battingSquad ?? [] as $player)
                        <option value="{{ $player->player_id ?? $player->PLAYER_ID }}">
                            {{ $player->player_name ?? $player->PLAYER_NAME ?? trim(($player->FIRST_NAME ?? $player->first_name ?? '') . ' ' . ($player->LAST_NAME ?? $player->last_name ?? '')) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="bowler_id" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Current Bowler</label>
                <select id="bowler_id" name="bowler_id" form="ballScoringForm" required onchange="checkAndAutoSubmit()" class="w-full bg-slate-950 border border-slate-700 focus:border-emerald-500 text-white rounded-xl px-4 py-3 text-sm transition outline-none">
                    <option value="" disabled selected>Select Bowler</option>
                    @foreach($bowlingSquad ?? [] as $player)
                        <option value="{{ $player->player_id ?? $player->PLAYER_ID }}">
                            {{ $player->player_name ?? $player->PLAYER_NAME ?? trim(($player->FIRST_NAME ?? $player->first_name ?? '') . ' ' . ($player->LAST_NAME ?? $player->last_name ?? '')) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Main Workspace Split Section Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Side: Digital Scoreboard Summary and Metrics Profile -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Large Primary Neon Dashboard Block -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 text-center shadow-2xl relative overflow-hidden">
                <div class="absolute top-4 left-4 bg-emerald-950 border border-emerald-500/30 text-emerald-400 font-bold px-3 py-1 rounded-full text-2xs uppercase tracking-widest">
                    Innings {{ $activeMatch->current_innings ?? $activeMatch->CURRENT_INNINGS ?? 1 }} Active
                </div>
                
                <div class="mt-6 mb-2">
                    <span class="text-xs uppercase tracking-widest font-bold text-slate-400 block mb-1">Batting: <span class="text-amber-400 font-black">{{ $activeMatch->team1_short_name ?? $activeMatch->TEAM1_SHORT_NAME ?? 'BAT' }}</span></span>
                    <div class="text-7xl font-black text-white tracking-tighter my-2 selection:bg-emerald-500">
                        {{ $activeMatch->team1_score ?? $activeMatch->TEAM1_SCORE ?? 0 }} <span class="text-slate-600 font-light">/</span> {{ $activeMatch->team1_wickets ?? $activeMatch->TEAM1_WICKETS ?? 0 }}
                    </div>
                    <div class="text-base font-semibold text-slate-400 mt-2">
                        Overs: <span class="text-emerald-400 font-mono">{{ number_format($activeMatch->team1_overs ?? $activeMatch->TEAM1_OVERS ?? 0.0, 1) }}</span>
                    </div>
                </div>
            </div>

            <!-- Current Live Performers Tracker Module -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-3">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800 pb-2 flex items-center gap-1.5">
                    <span>⚡</span><span>Current Live Performers</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="text-slate-500 uppercase border-b border-slate-800/60">
                                <th class="py-2 font-bold">Active Batters</th>
                                <th class="py-2 text-center font-bold">Runs</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-300 divide-y divide-slate-800/40">
                            @forelse($battingSquad ?? [] as $index => $player)
                                <tr class="{{ $index === 0 ? 'bg-emerald-950/20 text-emerald-400 border-l-2 border-emerald-500' : '' }}">
                                    <td class="py-2.5 pl-2 font-semibold text-white">
                                        {{ trim(($player->first_name ?? $player->FIRST_NAME ?? '') . ' ' . ($player->last_name ?? $player->LAST_NAME ?? '')) }} 
                                        {!! $index === 0 ? '<span class="text-emerald-400 ml-1 font-bold">*</span>' : '' !!}
                                    </td>
                                    <td class="py-2.5 text-center font-mono">
                                        {{ $player->total_runs ?? $player->TOTAL_RUNS ?? 0 }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-3 text-center text-slate-500 italic">No batting squad data verified.</td>
                                </tr>
                            @endforelse

                            <tr class="text-slate-500 uppercase border-b border-slate-800/60 font-bold text-2xs">
                                <th class="pt-4 pb-1">Active Bowlers</th>
                                <th class="pt-4 pb-1 text-center">Wickets</th>
                            </tr>
                            
                            @forelse($bowlingSquad ?? [] as $player)
                                <tr>
                                    <td class="py-2.5 font-semibold text-white pl-2">
                                        {{ trim(($player->first_name ?? $player->FIRST_NAME ?? '') . ' ' . ($player->last_name ?? $player->LAST_NAME ?? '')) }}
                                    </td>
                                    <td class="py-2.5 text-center font-mono text-emerald-400 font-bold">
                                        {{ $player->wickets_taken ?? $player->WICKETS_TAKEN ?? 0 }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-3 text-center text-slate-500 italic">No bowling squad data verified.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Match Board Total Summary Profile -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-xl space-y-3">
                <div class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800 pb-2">
                    📋 Match Board Summary
                </div>
                <div class="space-y-2 text-xs font-medium">
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-400">1st Inns ({{ $activeMatch->team1_short_name ?? $activeMatch->TEAM1_SHORT_NAME ?? 'TEAM 1' }}):</span>
                        <span class="text-white font-bold font-mono text-sm">{{ $activeMatch->team1_score ?? $activeMatch->TEAM1_SCORE ?? 0 }}/{{ $activeMatch->team1_wickets ?? $activeMatch->TEAM1_WICKETS ?? 0 }} ({{ number_format($activeMatch->team1_overs ?? $activeMatch->TEAM1_OVERS ?? 0.0, 1) }} Ov)</span>
                    </div>
                    <div class="flex justify-between items-center py-0.5">
                        <span class="text-slate-400">2nd Inns ({{ $activeMatch->team2_short_name ?? $activeMatch->TEAM2_SHORT_NAME ?? 'TEAM 2' }}):</span>
                        <span class="text-slate-500 font-bold font-mono text-sm">0/0 (0.0 Ov)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Rapid Input Core Operations Matrix Layout Panel -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
                
                <!-- Main Scoring Processing Form -->
                <form id="ballScoringForm" action="{{ route('admin.matches.storeBall') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="match_id" value="{{ $activeMatch->match_id ?? $activeMatch->MATCH_ID ?? 1 }}">
                    <input type="hidden" name="innings" value="{{ $activeMatch->current_innings ?? $activeMatch->CURRENT_INNINGS ?? 1 }}">

                    <!-- Visual Configuration Interface: Clickable Touch Grid -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Rapid Input Matrix (Select Ball Event)</label>
                        
                        <!-- Top Rows Area: Runs & Boundaries -->
                        <div class="grid grid-cols-3 gap-3 mb-3">
                            <button type="button" onclick="setBallOutcome(0, '', 0, '')" class="bg-slate-950 hover:bg-slate-800 border border-slate-700 text-white py-4 rounded-xl font-bold transition text-sm shadow-md active:scale-[0.98]">
                                Dot Ball
                            </button>
                            <button type="button" onclick="setBallOutcome(1, '', 0, '')" class="bg-blue-600 hover:bg-blue-500 border border-blue-500 text-white py-4 rounded-xl font-bold transition text-sm shadow-md active:scale-[0.98]">
                                +1 Run
                            </button>
                            <button type="button" onclick="setBallOutcome(2, '', 0, '')" class="bg-blue-600 hover:bg-blue-500 border border-blue-500 text-white py-4 rounded-xl font-bold transition text-sm shadow-md active:scale-[0.98]">
                                +2 Runs
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-3 gap-3 mb-3">
                            <button type="button" onclick="setBallOutcome(3, '', 0, '')" class="bg-blue-600 hover:bg-blue-500 border border-blue-500 text-white py-4 rounded-xl font-bold transition text-sm shadow-md active:scale-[0.98]">
                                +3 Runs
                            </button>
                            <button type="button" onclick="setBallOutcome(4, '', 0, '')" class="bg-emerald-600 hover:bg-emerald-500 border border-emerald-500 text-white py-4 rounded-xl font-black transition text-sm shadow-md active:scale-[0.98]">
                                4 (FOUR)
                            </button>
                            <button type="button" onclick="setBallOutcome(5, '', 0, '')" class="bg-blue-600 hover:bg-blue-500 border border-blue-500 text-white py-4 rounded-xl font-bold transition text-sm shadow-md active:scale-[0.98]">
                                +5 Runs
                            </button>
                        </div>

                        <div class="grid grid-cols-3 gap-3 mb-3">
                            <button type="button" onclick="setBallOutcome(6, '', 0, '')" class="bg-cyan-500 hover:bg-cyan-400 border border-cyan-500 text-slate-950 py-4 rounded-xl font-black transition text-sm shadow-md active:scale-[0.98]">
                                6 (SIX)
                            </button>
                            <button type="button" onclick="setBallOutcome(0, 'WIDE', 1, '')" class="bg-amber-500 hover:bg-amber-400 border border-amber-500 text-slate-950 py-4 rounded-xl font-bold transition text-sm shadow-md active:scale-[0.98]">
                                Wide
                            </button>
                            <button type="button" onclick="setBallOutcome(0, 'NOBALL', 1, '')" class="bg-amber-500 hover:bg-amber-400 border border-amber-500 text-slate-950 py-4 rounded-xl font-bold transition text-sm shadow-md active:scale-[0.98]">
                                No Ball
                            </button>
                        </div>

                        <!-- Crimson Out/Wicket Button Component -->
                        <div class="mb-6">
                            <button type="button" onclick="setBallOutcome(0, '', 0, 'BOWLED')" class="w-full bg-rose-600 hover:bg-rose-500 border border-rose-500 text-white py-4 rounded-xl font-black uppercase tracking-wider transition text-sm shadow-md active:scale-[0.98]">
                                OUT / Wicket Fall
                            </button>
                        </div>
                    </div>

                    <!-- Input Fields Matrix Form Elements -->
                    <div class="bg-slate-950/40 border border-slate-800 p-4 rounded-xl space-y-4">
                        <div class="text-2xs font-bold uppercase tracking-widest text-slate-500">Form Selection Overview</div>
                        
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label for="runs_scored" class="block text-2xs text-slate-400 font-bold uppercase mb-1">Bat Runs</label>
                                <input type="number" id="runs_scored" name="runs_scored" value="0" min="0" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2 font-bold text-center text-sm text-white font-mono outline-none">
                            </div>
                            <div>
                                <label for="extra_type" class="block text-2xs text-slate-400 font-bold uppercase mb-1">Extra Type</label>
                                <select id="extra_type" name="extra_type" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2 font-bold text-xs text-white outline-none">
                                    <option value="">NONE</option>
                                    <option value="WIDE">WIDE</option>
                                    <option value="NOBALL">NOBALL</option>
                                    <option value="BYE">BYE</option>
                                    <option value="LEGBYE">LEGBYE</option>
                                </select>
                            </div>
                            <div>
                                <label for="extra_runs" class="block text-2xs text-slate-400 font-bold uppercase mb-1">Extra Runs</label>
                                <input type="number" id="extra_runs" name="extra_runs" value="0" min="0" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2 font-bold text-center text-sm text-white font-mono outline-none">
                            </div>
                        </div>

                        <div>
                            <label for="wicket_type" class="block text-2xs text-slate-400 font-bold uppercase mb-1">Wicket Registry</label>
                            <select id="wicket_type" name="wicket_type" class="w-full bg-slate-950 border border-slate-800 rounded-lg p-2 font-bold text-xs text-white outline-none">
                                <option value="">NOT OUT</option>
                                <option value="BOWLED">BOWLED</option>
                                <option value="CAUGHT">CAUGHT OUT</option>
                                <option value="LBW">L.B.W.</option>
                                <option value="RUNOUT">RUN OUT</option>
                                <option value="STUMPED">STUMPED</option>
                            </select>
                        </div>
                    </div>

                    <!-- Commentary Description Field Area Box -->
                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Ball Commentary Feed Text</label>
                        <textarea id="description" name="description" placeholder="Describe the action event outcome briefly for the marquee live feed..." rows="2" required class="w-full bg-slate-950 border border-slate-700 focus:border-emerald-500 text-white placeholder-slate-600 rounded-xl p-4 text-sm transition outline-none resize-none"></textarea>
                    </div>

                    <!-- Submit Primary Processing Button Component -->
                    <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-sm font-bold uppercase tracking-wider py-4 rounded-xl transition shadow-lg shadow-emerald-500/10 active:translate-y-px">
                        ⚡ Transmit Event to Oracle Instance
                    </button>
                </form>

                <!-- Lower Innings Management Control Panel Form Cluster -->
                <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-slate-800/80">
                    <form action="{{ route('admin.match-live.complete') }}" method="POST" onsubmit="return confirm('Are you sure you want to finalize this match environment and move it to historical archives?');">
                        @csrf
                        <input type="hidden" name="match_id" value="{{ $activeMatch->match_id ?? $activeMatch->MATCH_ID ?? 1 }}">
                        <button type="submit" class="w-full bg-slate-950 hover:bg-slate-800 border border-slate-700 text-white text-xs font-bold uppercase tracking-wider py-3 px-4 rounded-xl transition text-center">
                            Finalize & Complete Match
                        </button>
                    </form>

                    <button type="button" onclick="resetFormState()" class="bg-transparent hover:bg-rose-950/20 border border-rose-500/30 hover:border-rose-500/50 text-rose-500 text-xs font-bold uppercase tracking-wider py-3 px-4 rounded-xl transition text-center">
                        Reset Panel View
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Inline JavaScript Event Mapper Logic Setup -->
<script>
    // Global lock state flags for tracking unsubmitted inputs
    let pendingSubmission = false;

    /**
     * Updates form values and automatically dispatches the action straight to the database
     */
    public function setBallOutcome(runs, extraType, extraRuns, wicketType) {
        // Step 1: Populate inputs fields
        document.getElementById('runs_scored').value = runs;
        document.getElementById('extra_type').value = extraType;
        document.getElementById('extra_runs').value = extraRuns;
        document.getElementById('wicket_type').value = wicketType;
        
        // Step 2: Auto-generate appropriate commentary templates 
        const genericFeed = document.getElementById('description');
        if(wicketType !== '') {
            genericFeed.value = "OUT! Wicket fall event recorded. Delivery resulting in standard " + wicketType.toLowerCase() + " dismissal.";
        } else if(extraType !== '') {
            genericFeed.value = "Extra recorded. Bowler penalized for illegal " + extraType.toLowerCase() + " delivery.";
        } else {
            genericFeed.value = runs === 0 ? "Good delivery, dot ball logged." : runs + " run(s) scored dynamically away into space.";
        }
        
        // Step 3: Validate required lineup items before automatic trigger firing
        const striker = document.getElementById('batsman_id').value;
        const bowler = document.getElementById('bowler_id').value;
        
        if (!striker || !bowler) {
            pendingSubmission = true;
            alert("Please pick a Batsman (On Strike) and a Current Bowler from the top configuration dropdown menu first!");
            return;
        }

        // Step 4: Automate transaction straight to Oracle connection
        executeFormSubmission();
    }

    /**
     * Helper to fires the native submission engine safely
     */
    function executeFormSubmission() {
        pendingSubmission = false;
        document.getElementById('ballScoringForm').submit();
    }

    /**
     * Triggered via dropdown inputs to fire an ongoing queued transaction event matrix
     */
    function checkAndAutoSubmit() {
        if (pendingSubmission) {
            const striker = document.getElementById('batsman_id').value;
            const bowler = document.getElementById('bowler_id').value;
            if (striker && bowler) {
                executeFormSubmission();
            }
        }
    }

    /**
     * Clears local UI configurations safely back to default.
     */
    function resetFormState() {
        pendingSubmission = false;
        document.getElementById('ballScoringForm').reset();
        document.getElementById('description').value = '';
    }
</script>
@endsection