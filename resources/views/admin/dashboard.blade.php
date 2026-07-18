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

    <!-- Top Navigation Header -->
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
                ← View Public Site
            </a>
        </div>
    </header>

    <div class="flex flex-1">
        <!-- Sidebar Navigation Layout Layer -->
        <aside class="w-72 bg-slate-900 border-r border-slate-800 p-6 flex flex-col justify-between hidden md:flex">
            <div class="space-y-7">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-500 block mb-3">Core Workspaces</span>
                    <nav class="space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition text-sm font-semibold {{ !isset($currentAdminSubView) || $currentAdminSubView == 'scoring' ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/10' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
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
                    </nav>
                </div>
            </div>
            
            <div class="pt-6 border-t border-slate-800 text-xs text-slate-500 font-medium">
                Connected Instance: <span class="text-slate-400 font-mono">Oracle_XE_1521</span>
            </div>
        </aside>

        <!-- Dynamic Admin Content Viewspace Area -->
        <main class="flex-1 p-6 md:p-10 space-y-8 overflow-y-auto max-w-7xl mx-auto w-full">
            
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

            @if(!isset($currentAdminSubView) || $currentAdminSubView == 'scoring')
                <!-- 1. LIVE SCORING PANEL WORKSPACE -->
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Event Logging Control Form Column -->
                    <div class="flex-1 bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6 md:p-8">
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-white tracking-tight">Log Current Ball Outcome</h2>
                            <p class="text-slate-400 text-sm mt-1">Submit dynamic data parameters directly downstream to active transactional packages.</p>
                        </div>

                        <form action="{{ route('admin.matches.storeBall') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="match_id" value="{{ $activeMatch->match_id ?? $activeMatch->MATCH_ID ?? 1 }}">
                            <input type="hidden" name="innings" value="{{ $activeMatch->current_innings ?? $activeMatch->CURRENT_INNINGS ?? 1 }}">

                            <!-- Roster Selection Dropdowns Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold uppercase tracking-wider text-slate-300 mb-2">On-Strike Batsman</label>
                                    <select name="batsman_id" class="w-full bg-slate-950 border border-slate-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-white rounded-xl px-4 py-3 text-sm font-medium transition outline-none">
                                        @foreach($battingSquad as $player)
                                            <option value="{{ $player->player_id }}">{{ $player->player_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold uppercase tracking-wider text-slate-300 mb-2">Active Bowler</label>
                                    <select name="bowler_id" class="w-full bg-slate-950 border border-slate-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-white rounded-xl px-4 py-3 text-sm font-medium transition outline-none">
                                        @foreach($bowlingSquad as $player)
                                            <option value="{{ $player->player_id }}">{{ $player->player_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Runs Scoring Radio Grid Block -->
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

                            <!-- Extras and Dismissals Configuration Flex Blocks -->
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

                            <!-- Commentary Log Box -->
                            <div>
                                <label class="block text-sm font-bold uppercase tracking-wider text-slate-300 mb-2">Ball Commentary Feed Text</label>
                                <textarea name="description" placeholder="Describe the action event outcome briefly for the marquee live feed..." rows="3" class="w-full bg-slate-950 border border-slate-700 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-white placeholder-slate-600 rounded-xl p-4 text-sm font-medium transition outline-none resize-none"></textarea>
                            </div>

                            <!-- Transmit Event Submission Button -->
                            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-sm font-bold uppercase tracking-wider py-4 rounded-xl transition shadow-lg shadow-emerald-500/10 active:translate-y-px">
                                ⚡ Transmit Event to Oracle Instance
                            </button>
                        </form>
                    </div>

                    <!-- Live State Scorecard Display Dashboard Card -->
                    <div class="w-full lg:w-96 bg-slate-900 border border-slate-800 rounded-2xl shadow-xl p-6 flex flex-col justify-between max-h-[500px]">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-widest text-slate-500 block">Live State Tracker</span>
                            <h3 class="text-xl font-bold text-white mt-1 border-b border-slate-800 pb-4">
                                {{ $activeMatch->team1_short_name ?? 'TEAM 1' }} <span class="text-slate-500 text-sm font-medium mx-1">vs</span> {{ $activeMatch->team2_short_name ?? 'TEAM 2' }}
                            </h3>
                            
                            <div class="py-8 text-center">
                                <span class="text-xs font-bold tracking-wider text-slate-400 uppercase block mb-1">Current Score Metrics</span>
                                <div class="text-6xl font-black text-white tracking-tight">
                                    {{ $activeMatch->team1_score ?? 0 }}<span class="text-slate-500 font-light">/</span>{{ $activeMatch->team1_wickets ?? 0 }}
                                </div>
                                <span class="inline-block bg-slate-950 border border-slate-800 text-slate-300 rounded-full px-4 py-1.5 text-xs font-semibold mt-4">
                                    Overs Logged: {{ number_format($activeMatch->team1_overs ?? 0.0, 1) }}
                                </span>
                            </div>
                        </div>

                        <div class="bg-slate-950 rounded-xl p-4 border border-slate-800 space-y-2 text-xs">
                            <div class="flex justify-between"><span class="text-slate-400">Match Status:</span><span class="text-emerald-400 font-bold uppercase">{{ $activeMatch->match_status ?? 'Live' }}</span></div>
                            <div class="flex justify-between"><span class="text-slate-400">Current Innings:</span><span class="text-white font-mono font-semibold">{{ $activeMatch->current_innings ?? 1 }}</span></div>
                        </div>
                    </div>
                </div>

            @elseif($currentAdminSubView == 'teams')
                <!-- 2. TEAM MANAGEMENT WORKSPACE -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-white tracking-tight">Team Structure Management</h2>
                            <p class="text-slate-400 text-sm">Create league entries and configure team groupings inside the team entity schema.</p>
                        </div>
                        <button class="bg-emerald-500 text-slate-950 font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl hover:bg-emerald-400 transition">+ Register New Team</button>
                    </div>
                    <div class="bg-slate-950 border border-slate-800 rounded-xl p-8 text-center text-slate-400 font-medium text-sm">
                        📁 No structural changes pending. Dynamic data linked to active league tables.
                    </div>
                </div>

            @elseif($currentAdminSubView == 'players')
                <!-- 3. PLAYER PROFILES WORKSPACE -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-white tracking-tight">Player Profile Registries</h2>
                            <p class="text-slate-400 text-sm">Assign player entities, modify positions, and configure squad rosters.</p>
                        </div>
                        <button class="bg-emerald-500 text-slate-950 font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl hover:bg-emerald-400 transition">+ Add Player Entity</button>
                    </div>
                    <div class="bg-slate-950 border border-slate-800 rounded-xl p-8 text-center text-slate-400 font-medium text-sm">
                        🏏 Roster rosters mapped dynamically through array indexing parameters safely.
                    </div>
                </div>

            @elseif($currentAdminSubView == 'fixtures')
                <!-- 4. SCHEDULE FIXTURES WORKSPACE -->
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-xl">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-white tracking-tight">Schedule Fixtures Console</h2>
                            <p class="text-slate-400 text-sm">Generate tour structures, set match times, and allocate venues.</p>
                        </div>
                        <button class="bg-emerald-500 text-slate-950 font-bold text-xs uppercase tracking-wider px-4 py-2.5 rounded-xl hover:bg-emerald-400 transition">+ Generate Match Slot</button>
                    </div>
                    <div class="bg-slate-950 border border-slate-800 rounded-xl p-8 text-center text-slate-400 font-medium text-sm">
                        📅 All league schedules synchronized with frontend countdown objects.
                    </div>
                </div>
            @endif

        </main>
    </div>

</body>
</html>