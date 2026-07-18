<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrickTracker Admin Workspace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0f172a; }
    </style>
</head>
<body class="text-slate-100 min-h-screen flex flex-col antialiased">

    <!-- Header Section -->
    <header class="bg-slate-900 border-b border-slate-800 px-6 py-4 flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center space-x-3">
            <div class="bg-emerald-500 text-slate-950 p-2 rounded-lg font-bold text-xl tracking-tight">CT</div>
            <div>
                <h1 class="font-bold text-lg tracking-wide text-white">CRICKTRACKER CENTER</h1>
                <p class="text-xs text-slate-400 font-medium">KUET Sports Management Console</p>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <span class="flex items-center space-x-2 bg-emerald-950 border border-emerald-500/30 px-3 py-1.5 rounded-full text-xs font-semibold text-emerald-400">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>ENGINE CONSOLE ACTIVE</span>
            </span>
            <a href="/" class="text-sm bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 px-4 py-2 rounded-lg transition font-medium">
                &larr; View Public Site
            </a>
        </div>
    </header>

    <div class="flex flex-1">
        <!-- Sidebar Navigation -->
        <aside class="w-72 bg-slate-900 border-r border-slate-800 p-6 flex flex-col justify-between hidden md:flex">
            <div class="space-y-7">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-500 block mb-3">Core Workspaces</span>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.match-live') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition text-sm font-semibold {{ !isset($currentAdminSubView) || $currentAdminSubView == 'scoring' ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/10' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>🏏 Live Scoring Panel</span>
                        </a>
                        <a href="{{ route('admin.teams') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition text-sm font-semibold {{ isset($currentAdminSubView) && $currentAdminSubView == 'teams' ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/10' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>👥 Team Management</span>
                        </a>
                        <a href="{{ route('admin.players') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition text-sm font-semibold {{ isset($currentAdminSubView) && $currentAdminSubView == 'players' ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/10' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>🎖️ Player Profiles</span>
                        </a>
                        <a href="{{ route('admin.fixtures') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition text-sm font-semibold {{ isset($currentAdminSubView) && $currentAdminSubView == 'fixtures' ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/10' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>📅 Schedule Fixtures</span>
                        </a>
                        <a href="{{ route('admin.news') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition text-sm font-semibold {{ isset($currentAdminSubView) && $currentAdminSubView == 'news' ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/10' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>📰 News Management</span>
                        </a>
                    </nav>
                </div>
            </div>
            
            <div class="pt-6 border-t border-slate-800 text-xs text-slate-500 font-medium">
                Connected Instance: <span class="text-slate-400 font-mono">Oracle_XE_1521</span>
            </div>
        </aside>

        <!-- Main Workspace -->
        <main class="flex-1 p-6 md:p-10 space-y-8 overflow-y-auto max-w-7xl mx-auto w-full">
            
            <!-- Session Messages -->
            @if(session('success'))
                <div class="bg-emerald-950/50 border border-emerald-500/50 text-emerald-400 p-4 rounded-xl flex items-center space-x-3 text-sm font-medium">
                    <span>✅</span> <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-rose-950/50 border border-rose-500/50 text-rose-400 p-4 rounded-xl space-y-1 text-sm font-medium">
                    @foreach($errors->all() as $error)
                        <div class="flex items-center space-x-2"><span>⚠️</span> <span>{{ $error }}</span></div>
                    @endforeach
                </div>
            @endif

            <!-- 1. LIVE SCORING PANEL -->
            @if(!isset($currentAdminSubView) || $currentAdminSubView == 'scoring')
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
                                    <label class="block text-sm font-bold uppercase tracking-wider text-slate-300 mb-2">On-Strike Batsman</label>
                                    <select name="batsman_id" required class="w-full bg-slate-950 border border-slate-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-white rounded-xl px-4 py-3 text-sm font-medium transition outline-none">
                                        <option value="" disabled selected>Select Batsman</option>
                                        @foreach($battingSquad ?? [] as $player)
                                            <option value="{{ $player->player_id ?? $player->PLAYER_ID }}">{{ $player->player_name ?? $player->PLAYER_NAME }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold uppercase tracking-wider text-slate-300 mb-2">Active Bowler</label>
                                    <select name="bowler_id" required class="w-full bg-slate-950 border border-slate-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-white rounded-xl px-4 py-3 text-sm font-medium transition outline-none">
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
                                    <label class="block text-sm font-bold uppercase tracking-wider text-slate-300 mb-2">Extras Registry</label>
                                    <div class="flex space-x-3">
                                        <select name="extra_type" class="bg-slate-950 border border-slate-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-white rounded-xl px-4 py-3 text-sm font-medium transition outline-none flex-1">
                                            <option value="">None (Normal Delivery)</option>
                                            <option value="WIDE">Wide Ball</option>
                                            <option value="NOBALL">No Ball</option>
                                            <option value="BYE">Bye</option>
                                            <option value="LEGBYE">Leg Bye</option>
                                        </select>
                                        <input type="number" name="extra_runs" value="0" min="0" max="10" class="w-20 text-center bg-slate-950 border border-slate-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-white rounded-xl py-3 text-sm font-bold outline-none">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold uppercase tracking-wider text-slate-300 mb-2">Wicket Outcome</label>
                                    <select name="wicket_type" class="w-full bg-slate-950 border border-slate-700 focus:border-rose-500 focus:ring-1 focus:ring-rose-500 text-white rounded-xl px-4 py-3 text-sm font-medium transition outline-none">
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
                                <label class="block text-sm font-bold uppercase tracking-wider text-slate-300 mb-2">Ball Commentary Feed Text</label>
                                <textarea name="description" placeholder="Describe the action event outcome briefly for the marquee live feed..." rows="3" class="w-full bg-slate-950 border border-slate-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-white placeholder-slate-600 rounded-xl p-4 text-sm font-medium transition outline-none resize-none"></textarea>
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

            <!-- 2. TEAM MANAGEMENT -->
            @elseif($currentAdminSubView == 'teams')
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl h-fit">
                        <h3 class="text-lg font-bold text-white mb-4">Register New Franchise Team</h3>
                        <form action="{{ route('admin.teams.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Franchise Name</label>
                                <input type="text" name="name" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Short Name/Abbreviation</label>
                                <input type="text" name="short_name" placeholder="e.g. KCC" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
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

            <!-- 3. PLAYER PROFILES -->
            @elseif($currentAdminSubView == 'players')
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl h-fit">
                        <h3 class="text-lg font-bold text-white mb-4">Enroll New Athlete Profile</h3>
                        <form action="{{ route('admin.players.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">First Name</label>
                                    <input type="text" name="first_name" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Last Name</label>
                                    <input type="text" name="last_name" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Assigned Team Group</label>
                                <select name="team_id" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
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

            <!-- 4. SCHEDULE FIXTURES -->
            @elseif($currentAdminSubView == 'fixtures')
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl h-fit">
                        <h3 class="text-lg font-bold text-white mb-4">Generate Match Slot</h3>
                        <form action="{{ route('admin.fixtures.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Team A (Home)</label>
                                <select name="team1_id" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
                                    <option value="" disabled selected>Select Home Team</option>
                                    @foreach($realTeams ?? [] as $t)
                                        <option value="{{ $t->team_id ?? $t->TEAM_ID }}">{{ $t->name ?? $t->NAME }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Team B (Away)</label>
                                <select name="team2_id" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
                                    <option value="" disabled selected>Select Away Team</option>
                                    @foreach($realTeams ?? [] as $t)
                                        <option value="{{ $t->team_id ?? $t->TEAM_ID }}">{{ $t->name ?? $t->NAME }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Match Commencing Date/Time</label>
                                <input type="datetime-local" name="match_date" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-3 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
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

            <!-- 5. NEWS MANAGEMENT -->
            @elseif($currentAdminSubView == 'news')
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl h-fit">
                        <h3 class="text-lg font-bold text-white mb-4">Broadcast Live News Feed</h3>
                        <form action="{{ route('admin.news.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Headline Title</label>
                                <input type="text" name="title" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl px-4 py-2.5 text-sm font-medium outline-none focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Article Body Content</label>
                                <textarea name="content" rows="4" required class="w-full bg-slate-950 border border-slate-700 text-white rounded-xl p-4 text-sm font-medium outline-none focus:border-emerald-500 resize-none"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-emerald-500 text-slate-950 text-xs font-bold uppercase tracking-wide py-3 rounded-xl hover:bg-emerald-400 transition">Publish News Article</button>
                        </form>
                    </div>
                    <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-xl">
                        <h3 class="text-lg font-bold text-white mb-4">Recent Published Bulletins</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-slate-950 text-slate-400 uppercase text-xs tracking-wider">
                                    <tr>
                                        <th class="p-4 rounded-l-xl">Headline / Context Details</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800">
                                    @forelse($news ?? [] as $item)
                                        <tr class="hover:bg-slate-850/50">
                                            <td class="p-4">
                                                <div class="text-white font-medium">{{ $item->title ?? $item->TITLE }}</div>
                                                <div class="text-xs text-slate-500 font-mono mt-1">Logged Timestamp: {{ $item->time ?? $item->TIME }}</div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="p-4 text-center text-slate-500">No active news feeds found inside the database instance.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </main>
    </div>

</body>
</html>