<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CrickTracker - KUET Sports Portal')</title>
    <!-- Bootstrap 5 & FontAwesome CDN Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7fc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; }
        .sidebar-brand { background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); color: #fff; padding: 20px; text-align: center; font-weight: 700; letter-spacing: 1px; }
        .side-nav { background: #1e293b; min-height: 100vh; position: fixed; width: 260px; box-shadow: 4px 0 10px rgba(0,0,0,0.05); z-index: 100; }
        .side-nav .nav-link { color: #94a3b8; padding: 12px 25px; font-weight: 500; display: flex; align-items: center; gap: 12px; transition: all 0.3s; text-decoration: none; }
        .side-nav .nav-link:hover, .side-nav .nav-link.active { color: #fff; background: rgba(255,255,255,0.05); border-left: 4px solid #38bdf8; }
        .main-content { margin-left: 260px; padding: 40px; }
        .dashboard-card { border: none; border-radius: 12px; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px; padding: 24px; transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .dashboard-card:hover { transform: translateY(-2px); box-shadow: 0 6px 25px rgba(0,0,0,0.04); }
        .welcome-hero { background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white; border-radius: 16px; padding: 35px; margin-bottom: 30px; }
        .stat-menu-title { background: #e2e8f0; color: #334155; font-weight: 700; font-size: 0.85rem; padding: 10px 15px; letter-spacing: 0.5px; border-radius: 6px; }
        .stat-menu-item { border: none; background: transparent; color: #475569; text-align: left; padding: 10px 15px; font-size: 0.9rem; font-weight: 500; display: block; width: 100%; border-radius: 6px; transition: all 0.2s; text-decoration: none; }
        .stat-menu-item:hover, .stat-menu-item.active { background: #f1f5f9; color: #0f172a; font-weight: 600; }
        .stat-menu-item i { width: 20px; color: #64748b; }
        .filter-pill { background: #e2e8f0; border: none; padding: 8px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; color: #334155; display: inline-flex; align-items: center; gap: 8px; }
        .filter-pill span { display: block; font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; }
        .table-section-title { font-size: 1.15rem; font-weight: 700; color: #1e293b; border-left: 4px solid #3b82f6; padding-left: 10px; margin-top: 15px; }
        .last-border-0:last-child { border-bottom: 0 !important; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
        .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    </style>
</head>
<body>

    <!-- Sidebar Global Fixed Navigation -->
    <div class="side-nav d-flex flex-column justify-content-between">
        <div>
            <div class="sidebar-brand fs-4 text-uppercase">
                <i class="fa-solid fa-chart-line me-2"></i>CrickTracker
            </div>
            <div class="nav flex-column mt-4">
                <!-- Public Interfaces Routes Matching Controller Enpoints -->
                <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                <a href="{{ url('/recent-matches') }}" class="nav-link {{ request()->is('recent-matches') ? 'active' : '' }}">
                    <i class="fa-solid fa-history"></i> Recent Matches
                </a>
                <a href="{{ url('/upcoming-matches') }}" class="nav-link {{ request()->is('upcoming-matches') ? 'active' : '' }}">
                    <i class="fa-solid fa-calendar-days"></i> Upcoming Matches
                </a>
                <a href="{{ url('/player-statistics') }}" class="nav-link {{ request()->is('player-statistics') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-astronaut"></i> Player Statistics
                </a>
                <a href="{{ url('/teams') }}" class="nav-link {{ request()->is('teams') ? 'active' : '' }}">
                    <i class="fa-solid fa-shield-halved"></i> Teams
                </a>
                <a href="{{ url('/news') }}" class="nav-link {{ request()->is('news') ? 'active' : '' }}">
                    <i class="fa-solid fa-newspaper"></i> News
                </a>
                
                @auth
                    @if(Auth::user()->IS_ADMIN == '1' || Auth::user()->IS_ADMIN == 1)
                        <hr class="border-secondary my-2 mx-3">
                        <div class="px-3 mb-1 text-warning small fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">Admin Management</div>
                        
                        <!-- Route Prefixes Setup to Match Admin Route Groups -->
                        <a href="{{ route('admin.dashboard') }}" class="nav-link text-warning py-2 {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-gear"></i> Admin Dashboard
                        </a>
                        <a href="{{ route('admin.players') }}" class="nav-link text-warning py-2 {{ request()->is('admin/players') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-plus"></i> Update Players
                        </a>
                        <a href="{{ route('admin.fixtures') }}" class="nav-link text-warning py-2 {{ request()->is('admin/fixtures') ? 'active' : '' }}">
                            <i class="fa-solid fa-calendar-plus"></i> Manage Fixtures
                        </a>
                        <a href="{{ route('admin.teams') }}" class="nav-link text-warning py-2 {{ request()->is('admin/teams') ? 'active' : '' }}">
                            <i class="fa-solid fa-people-group"></i> Manage Teams
                        </a>
                        <a href="{{ route('admin.match-live') }}" class="nav-link text-danger fw-bold py-2 {{ request()->is('admin/match-live') ? 'active' : '' }}">
                            <i class="fa-solid fa-circle-dot animate-pulse"></i> Ball-by-Ball Live Entry
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        <!-- System Auth Portal Control Actions Block -->
        <div class="border-top border-secondary p-3 bg-dark-subtle">
            @guest
                <div class="d-grid gap-2">
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light"><i class="fa-solid fa-right-to-bracket me-2"></i>Login</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-primary"><i class="fa-solid fa-user-plus me-2"></i>Register</a>
                </div>
            @endguest

            @auth
                <div class="text-light small mb-2 text-center text-truncate px-1">
                    <i class="fa-solid fa-circle-user text-success me-1"></i> {{ Auth::user()->USERNAME ?? Auth::user()->name }}
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger w-100">
                        <i class="fa-solid fa-power-off me-2"></i>Logout
                    </button>
                </form>
            @endauth
        </div>
    </div>

    <!-- Main Dynamic Slot Injector Entrypoint -->
    <div class="main-content">
        @yield('content')
    </div>

    <!-- Core Bootstrap Script Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>