<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrickTracker - KUET Sports Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7fc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; }
        .sidebar-brand { background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); color: #fff; padding: 20px; text-align: center; font-weight: 700; letter-spacing: 1px; }
        .side-nav { background: #1e293b; min-height: 100vh; position: fixed; width: 260px; box-shadow: 4px 0 10px rgba(0,0,0,0.05); z-index: 100; }
        .side-nav .nav-link { color: #94a3b8; padding: 12px 25px; font-weight: 500; display: flex; align-items: center; gap: 12px; transition: all 0.3s; text-decoration: none; }
        .side-nav .nav-link:hover, .side-nav .nav-link.active { color: #fff; background: rgba(255,255,255,0.05); border-left: 4px solid #38bdf8; }
        .main-content { margin-left: 260px; padding: 40px; }
        .dashboard-card { border: none; border-radius: 12px; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px; padding: 24px; }
        .welcome-hero { background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white; border-radius: 16px; padding: 35px; margin-bottom: 30px; }
        
        .stat-menu-title { background: #e2e8f0; color: #334155; font-weight: 700; font-size: 0.85rem; padding: 10px 15px; letter-spacing: 0.5px; border-radius: 6px; }
        .stat-menu-item { border: none; background: transparent; color: #475569; text-align: left; padding: 10px 15px; font-size: 0.9rem; font-weight: 500; display: block; width: 100%; border-radius: 6px; transition: all 0.2s; text-decoration: none; }
        .stat-menu-item:hover, .stat-menu-item.active { background: #f1f5f9; color: #0f172a; font-weight: 600; }
        .stat-menu-item i { width: 20px; color: #64748b; }
        
        .filter-pill { background: #e2e8f0; border: none; padding: 8px 16px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; color: #334155; display: inline-flex; align-items: center; gap: 8px; }
        .filter-pill span { display: block; font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase; }
        .table-section-title { font-size: 1.15rem; font-weight: 700; color: #1e293b; border-left: 4px solid #3b82f6; padding-left: 10px; margin-top: 15px; }
    </style>
</head>
<body>

    <div class="side-nav">
        <div class="sidebar-brand fs-4 text-uppercase">
            <i class="fa-solid fa-chart-line me-2"></i>CrickTracker
        </div>
        <div class="nav flex-column mt-4">
            <a href="{{ url('/') }}" class="nav-link {{ $currentView == 'dashboard' ? 'active' : '' }}"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="{{ url('/recent-matches') }}" class="nav-link {{ $currentView == 'recent' ? 'active' : '' }}"><i class="fa-solid fa-history"></i> Recent Matches</a>
            <a href="{{ url('/upcoming-matches') }}" class="nav-link {{ $currentView == 'upcoming' ? 'active' : '' }}"><i class="fa-solid fa-calendar-days"></i> Upcoming Matches</a>
            <a href="{{ url('/player-statistics') }}" class="nav-link {{ $currentView == 'stats' ? 'active' : '' }}"><i class="fa-solid fa-user-astronaut"></i> Player Statistics</a>
            <a href="{{ url('/teams') }}" class="nav-link {{ $currentView == 'teams' ? 'active' : '' }}"><i class="fa-solid fa-shield-halved"></i> Teams</a>
            <a href="{{ url('/news') }}" class="nav-link {{ $currentView == 'news' ? 'active' : '' }}"><i class="fa-solid fa-newspaper"></i> News</a>
        </div>
    </div>

    <div class="main-content">
        
        @if($currentView == 'dashboard')
            <div class="welcome-hero shadow-sm">
                <h2 class="fw-bold mb-2">Welcome to CrickTracker KUET</h2>
                <p class="lead mb-0">The official live cricket tracking system for Khulna University of Engineering & Technology.</p>
            </div>
            
            <div class="row">
                <div class="col-md-7">
                    <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-satellite-dish text-danger me-2"></i>Featured Ongoing Action</h5>
                    <div class="card dashboard-card bg-light border">
                        <div class="d-flex justify-content-between text-danger small fw-bold mb-3">
                            <span>KUET Inter-Dept League</span>
                            <span><i class="fa-solid fa-circle-dot"></i> LIVE UPDATING</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fs-5 fw-bold text-dark">CSE Department</span>
                            <span class="fs-5 fw-bold text-dark">145/3 <small class="text-muted">(14.2 Overs)</small></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-5 fw-bold text-dark">EEE Department</span>
                            <span class="text-muted small">Yet to bat</span>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($currentView == 'recent')
            <h4 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-history text-success me-2"></i>Recent Matches Results</h4>
            <div class="row">
                @foreach($recentMatches as $match)
                    <div class="col-md-6 mb-4">
                        <div class="card dashboard-card h-100 mb-0">
                            <div class="text-muted small mb-2">{{ $match->type }}</div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-bold">{{ $match->team1 }}</span>
                                <span class="fw-bold">{{ $match->score1 }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold">{{ $match->team2 }}</span>
                                <span class="fw-bold">{{ $match->score2 }}</span>
                            </div>
                            <div class="text-success small fw-bold border-top pt-2">{{ $match->result }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

        @elseif($currentView == 'upcoming')
            <h4 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-calendar-days text-warning me-2"></i>Upcoming Matches Schedule</h4>
            <div class="card dashboard-card p-0 overflow-hidden">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="p-3">Match Fixture</th>
                            <th class="p-3">Date</th>
                            <th class="p-3">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingMatches as $match)
                            <tr>
                                <td class="p-3 fw-bold text-primary">{{ $match->team1 }} vs {{ $match->team2 }}</td>
                                <td class="p-3"><span class="badge bg-secondary">{{ $match->date }}</span></td>
                                <td class="p-3 text-muted">{{ $match->time }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @elseif($currentView == 'stats')
            <h3 class="fw-bold mb-4 text-dark">Tournament Records</h3>
            <div class="row">
                <div class="col-md-3">
                    <div class="card dashboard-card p-2">
                        <div class="stat-menu-title mb-2">QUICK LINKS</div>
                        <a href="#batting-section" class="stat-menu-item fw-bold text-primary"><i class="fa-solid fa-gavel me-2"></i>Batting Leaders</a>
                        <a href="#bowling-section" class="stat-menu-item fw-bold text-success"><i class="fa-solid fa-baseball me-2"></i>Bowling Leaders</a>
                        <hr class="my-2">
                        <div class="stat-menu-title mb-2">BATTING</div>
                        <a href="#" class="stat-menu-item active"><i class="fa-solid fa-chevron-right float-end mt-1 small"></i>Most Runs</a>
                        <a href="#" class="stat-menu-item"><i class="fa-solid fa-chevron-right float-end mt-1 small"></i>Best Average</a>
                        
                        <div class="stat-menu-title mt-3 mb-2">BOWLING</div>
                        <a href="#" class="stat-menu-item"><i class="fa-solid fa-chevron-right float-end mt-1 small"></i>Most Wickets</a>
                        <a href="#" class="stat-menu-item"><i class="fa-solid fa-chevron-right float-end mt-1 small"></i>Best Economy</a>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="d-flex gap-2 mb-4">
                        <div class="filter-pill"><div><span>Format</span><strong>T20</strong></div><i class="fa-solid fa-chevron-down small text-muted"></i></div>
                        <div class="filter-pill"><div><span>Year</span><strong>2026</strong></div><i class="fa-solid fa-chevron-down small text-muted"></i></div>
                        <div class="filter-pill"><div><span>Venue</span><strong>KUET</strong></div><i class="fa-solid fa-chevron-down small text-muted"></i></div>
                    </div>

                    <div id="batting-section" class="d-flex justify-content-between align-items-center mb-3">
                        <div class="table-section-title">Batting Records (Most Runs)</div>
                    </div>
                    <div class="card dashboard-card p-0 overflow-hidden mb-5">
                        <table class="table table-hover mb-0 align-middle text-center">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th class="text-start p-3 text-muted small fw-bold">Batter</th>
                                    <th class="p-3 text-muted small fw-bold">Department</th>
                                    <th class="p-3 text-muted small fw-bold">Matches</th>
                                    <th class="p-3 text-muted small fw-bold">Inns</th>
                                    <th class="p-3 text-muted small fw-bold">Runs</th>
                                    <th class="p-3 text-muted small fw-bold">HS</th>
                                    <th class="p-3 text-muted small fw-bold">Avg</th>
                                    <th class="p-3 text-muted small fw-bold">SR</th>
                                    <th class="p-3 text-muted small fw-bold">100</th>
                                    <th class="p-3 text-muted small fw-bold">50</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($battingStats as $player)
                                    <tr>
                                        <td class="text-start p-3 fw-bold text-primary">{{ $player->name }}</td>
                                        <td class="p-3"><span class="badge bg-secondary opacity-75">{{ $player->department }}</span></td>
                                        <td class="p-3 fw-semibold text-dark">{{ $player->matches }}</td>
                                        <td class="p-3 text-muted">{{ $player->innings }}</td>
                                        <td class="p-3 text-dark fw-bold">{{ $player->runs }}</td>
                                        <td class="p-3 text-muted">{{ $player->highest_score }}</td>
                                        <td class="p-3 text-dark fw-bold">{{ $player->batting_average }}</td>
                                        <td class="p-3 text-dark fw-semibold">{{ $player->strike_rate }}</td>
                                        <td class="p-3 text-muted">{{ $player->hundreds }}</td>
                                        <td class="p-3 text-muted">{{ $player->fifties }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div id="bowling-section" class="d-flex justify-content-between align-items-center mb-3">
                        <div class="table-section-title">Bowling Records (Most Wickets)</div>
                    </div>
                    <div class="card dashboard-card p-0 overflow-hidden">
                        <table class="table table-hover mb-0 align-middle text-center">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th class="text-start p-3 text-muted small fw-bold">Bowler</th>
                                    <th class="p-3 text-muted small fw-bold">Department</th>
                                    <th class="p-3 text-muted small fw-bold">Matches</th>
                                    <th class="p-3 text-muted small fw-bold">Wickets</th>
                                    <th class="p-3 text-muted small fw-bold">Runs Conceded</th>
                                    <th class="p-3 text-muted small fw-bold">BBI</th>
                                    <th class="p-3 text-muted small fw-bold">Econ</th>
                                    <th class="p-3 text-muted small fw-bold">5w</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bowlingStats as $player)
                                    <tr>
                                        <td class="text-start p-3 fw-bold text-success">{{ $player->name }}</td>
                                        <td class="p-3"><span class="badge bg-secondary opacity-75">{{ $player->department }}</span></td>
                                        <td class="p-3 text-dark fw-semibold">{{ $player->matches }}</td>
                                        <td class="p-3 text-success fw-bold fs-6">{{ $player->wickets }}</td>
                                        <td class="p-3 text-muted">{{ $player->bowling_runs }}</td>
                                        <td class="p-3 text-dark fw-semibold">{{ $player->best_bowling }}</td>
                                        <td class="p-3 text-dark fw-bold">{{ $player->economy }}</td>
                                        <td class="p-3 text-muted">{{ $player->five_w }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        @elseif($currentView == 'teams')
            <h4 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-table text-secondary me-2"></i>Points Table Standings</h4>
            <div class="card dashboard-card p-0 overflow-hidden">
                <table class="table table-striped mb-0 text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start p-3">Department</th>
                            <th class="p-3">Played</th>
                            <th class="p-3 text-success">Won</th>
                            <th class="p-3 text-danger">Lost</th>
                            <th class="p-3">Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teams as $team)
                            <tr>
                                <td class="text-start p-3 fw-bold">{{ $team->name }}</td>
                                <td class="p-3">{{ $team->played }}</td>
                                <td class="p-3 text-success fw-bold">{{ $team->won }}</td>
                                <td class="p-3 text-danger">{{ $team->lost }}</td>
                                <td class="p-3 fw-bold text-primary">{{ $team->points }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @elseif($currentView == 'news')
            <h4 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-newspaper text-primary me-2"></i>Latest Tournament News</h4>
            @foreach($news as $item)
                <div class="card dashboard-card mb-3">
                    <h5 class="fw-bold text-dark mb-2">{{ $item->title }}</h5>
                    <div class="text-muted small">Published {{ $item->time }}</div>
                </div>
            @endforeach
        @endif

    </div>

</body>
</html>