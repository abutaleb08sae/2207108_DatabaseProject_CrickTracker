@extends('admin.layouts.app') 
{{-- Assumes your provided layout markup is stored inside admin/layouts/app.blade.php --}}

@section('title', 'Live Match Scoring Panel')

@section('content')
<div class="space-y-6">
    <!-- View Title Header -->
    <div>
        <h2 class="text-2xl font-bold tracking-tight text-white">Live Match Scoring Panel</h2>
        <p class="text-sm text-slate-400">Select an active game instance below to initialize real-time score streaming.</p>
    </div>

    <!-- Match Cards Grid Grid Setup -->
    @if(isset($liveMatches) && count($liveMatches) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($liveMatches as $match)
                @php
                    // Safeguard field extractions to cleanly handle Oracle uppercase/lowercase column key mapping variants
                    $matchId = $match->match_id ?? $match->MATCH_ID ?? 1;
                    $team1 = $match->team1_name ?? $match->TEAM1_NAME ?? 'Team 1';
                    $team2 = $match->team2_name ?? $match->TEAM2_NAME ?? 'Team 2';
                    $venue = $match->venue_name ?? $match->VENUE_NAME ?? $match->venue_id ?? $match->VENUE_ID ?? 'KUET Main Playground';
                    $time = $match->match_date ?? $match->MATCH_DATE ?? 'Started: --:--';
                @endphp

                <div class="bg-slate-900 border-l-4 border-rose-500 rounded-xl p-6 shadow-xl border-y border-r border-slate-800 flex flex-col justify-between space-y-6 transition hover:border-slate-700">
                    <div class="space-y-4">
                        <!-- Upper Badge Row Area -->
                        <div class="flex items-center justify-between text-xs">
                            <span class="flex items-center space-x-1.5 bg-rose-950 border border-rose-500/30 text-rose-400 font-bold px-2.5 py-1 rounded-md">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-ping"></span>
                                <span>LIVE</span>
                            </span>
                            <span class="text-slate-400 font-medium flex items-center space-x-1">
                                <span>📍</span>
                                <span>{{ $venue }}</span>
                            </span>
                        </div>

                        <!-- Match Head-to-Head Visuals -->
                        <div>
                            <h3 class="text-xl font-bold tracking-wide text-white flex items-center space-x-2">
                                <span>{{ $team1 }}</span>
                                <span class="text-xs font-normal text-slate-500 uppercase">vs</span>
                                <span>{{ $team2 }}</span>
                            </h3>
                            <p class="text-xs text-slate-400 font-medium mt-1 flex items-center space-x-1">
                                <span>🕒</span>
                                <span>{{ is_numeric($time) ? 'Started: '.date('h:i A', $time) : $time }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Open Action Button Wrapper Container -->
                    <div>
                        {{-- Explicitly passes dynamic ID mapping straight into your custom web.php routing layer --}}
                        <a href="/admin/scoring/{{ $matchId }}" class="w-full bg-transparent hover:bg-rose-950/30 text-rose-500 hover:text-rose-400 border border-rose-500/40 hover:border-rose-400 font-semibold text-sm py-2.5 px-4 rounded-xl transition flex items-center justify-center space-x-2">
                            <span>📡</span>
                            <span>Open Control Room</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty Slate/Placeholder View Area -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-12 text-center max-w-xl mx-auto space-y-4">
            <div class="text-4xl">🏏</div>
            <div class="space-y-1">
                <h3 class="text-lg font-bold text-white">No Matches Are Active Right Now</h3>
                <p class="text-sm text-slate-400 max-w-sm mx-auto">To start scoring, go to the "Schedule Fixtures" workspace panel and verify the match status state indicator says "Live".</p>
            </div>
            <div class="pt-2">
                <a href="{{ route('admin.fixtures') }}" class="inline-flex items-center space-x-2 text-xs font-semibold bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 px-4 py-2 rounded-xl transition">
                    <span>📅</span>
                    <span>Go to Fixtures Workspace</span>
                </a>
            </div>
        </div>
    @endif
</div>
@endsection