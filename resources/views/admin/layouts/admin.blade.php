<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CrickTracker Admin Workspace')</title>
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
                        <a href="{{ route('admin.match-live') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition text-sm font-semibold {{ request()->routeIs('admin.match-live') ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/10' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>🏏 Live Scoring Panel</span>
                        </a>
                        <a href="{{ route('admin.teams') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition text-sm font-semibold {{ request()->routeIs('admin.teams') ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/10' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>👥 Team Management</span>
                        </a>
                        <a href="{{ route('admin.players') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition text-sm font-semibold {{ request()->routeIs('admin.players') ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/10' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>🎖️ Player Profiles</span>
                        </a>
                        <a href="{{ route('admin.fixtures') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition text-sm font-semibold {{ request()->routeIs('admin.fixtures') ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/10' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>📅 Schedule Fixtures</span>
                        </a>
                        <a href="{{ route('admin.news') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl transition text-sm font-semibold {{ request()->routeIs('admin.news') ? 'bg-emerald-500 text-slate-950 shadow-md shadow-emerald-500/10' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <span>📰 News Management</span>
                        </a>
                    </nav>
                </div>
            </div>
            
            <div class="pt-6 border-t border-slate-800 text-xs text-slate-500 font-medium">
                Connected Instance: <span class="text-slate-400 font-mono">Oracle_XE_1521</span>
            </div>
        </aside>

        <!-- Main Workspace Contents Container -->
        <main class="flex-1 p-6 md:p-10 space-y-8 overflow-y-auto max-w-7xl mx-auto w-full">
            
            <!-- Global Flash System Messages -->
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

            <!-- Individual Dynamic Views Inject Content Here -->
            @yield('content')

        </main>
    </div>

</body>
</html>