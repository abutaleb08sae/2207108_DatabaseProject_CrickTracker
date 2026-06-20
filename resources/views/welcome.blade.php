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
        .side-nav { background: #1e293b; min-height: 100vh; position: fixed; width: 260px; box-shadow: 4px 0 10px rgba(0,0,0,0.05); }
        .side-nav .nav-link { color: #94a3b8; padding: 12px 25px; font-weight: 500; display: flex; align-items: center; gap: 12px; transition: all 0.3s; text-decoration: none; }
        .side-nav .nav-link:hover, .side-nav .nav-link.active { color: #fff; background: rgba(255,255,255,0.05); border-left: 4px solid #38bdf8; }
        .main-content { margin-left: 260px; padding: 40px; }
        .dashboard-card { border: none; border-radius: 16px; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 24px; padding: 24px; }
        .welcome-hero { background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white; border-radius: 16px; padding: 35px; margin-bottom: 30px; }
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
                <p class="lead mb-0">The official live cricket tracking system for Khulna University of Engineering & Technology. Track real-time live match streams, tournament point matrices, structural department statistics, and recent highlights across our internal campus updates.</p>
            </div>
            
            <div class="row">
                <div class="col-md-7">
                    <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-satellite-dish text-danger me-2"></i>Featured Ongoing Action</h5>
                    <div class="card dashboard-card bg-light border">
                        <div class="d-flex justify-content-between text-danger small fw-bold mb-3">
                            <span>KUET Inter-Dept League</span>
                            <span><i class="fa-solid fa-circle-dot animate-pulse"></i> LIVE UPDATING BALL-BY-BALL</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fs-5 fw-bold text-dark">CSE Department</span>
                            <span class="fs-5 fw-bold text-dark">145/3 <small class="text-muted">(14.2 Overs)</small></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-5 fw-bold text-dark">EEE Department</span>
                            <span class="text-muted small">Yet to bat</span>
                        </div>
                        <hr class="my-3">
                        <p class="text-muted small mb-0"><i class="fa-solid fa-info-circle text-primary me-1"></i> <strong>Admin Panel Notice:</strong> This framework is configured for custom real-time score updates. Ball-by-ball inputs managed via backend entries will stream live indicators directly to the consumer screen.</p>
                    </div>
                </div>
                <div class="col-md-5">
                    <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-university text-secondary me-2"></i>KUET Arena Summary</h5>
                    <div class="card dashboard-card">
                        <p class="small mb-2"><strong>Venue:</strong> KUET Main Sports Ground</p>
                        <p class="small mb-2"><strong>Total Teams:</strong> 5 Engineering Departments</p>
                        <p class="small mb-0"><strong>Current Status:</strong> League stage matches are active. Select an item from the side panel navigation grid to access complete details.</p>
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
                                <span class="fw-bold">{{ $match->score1 }} <small class="text-muted">({{ $match->overs1 }})</small></span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold">{{ $match->team2 }}</span>
                                <span class="fw-bold">{{ $match->score2 }} <small class="text-muted">({{ $match->overs2 }})</small></span>
                            </div>
                            <div class="text-success small fw-bold border-top pt-2"><i class="fa-solid fa-trophy me-1"></i> {{ $match->result }}</div>
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
                            <th class="p-3">Ground Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingMatches as $match)
                            <tr>
                                <td class="p-3 fw-bold text-primary">{{ $match->team1 }} vs {{ $match->team2 }}</td>
                                <td class="p-3"><span class="badge bg-secondary">{{ $match->date }}</span></td>
                                <td class="p-3 text-muted">{{ $match->time }}</td>
                                <td class="p-3 small">{{ $match->venue }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @elseif($currentView == 'stats')
            <h4 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-user-astronaut text-info me-2"></i>Top Tournament Performers</h4>
            <div class="row">
                @foreach($playerStats as $player)
                    <div class="col-md-6 mb-3">
                        <div class="card dashboard-card">
                            <h5 class="fw-bold text-dark mb-1">{{ $player->name }}</h5>
                            <div class="text-muted small mb-3">Department: {{ $player->team }}</div>
                            <div class="row text-center bg-light g-0 p-2 rounded">
                                <div class="col-4 border-end"><small class="text-muted d-block">Runs</small><strong class="fs-5 text-primary">{{ $player->runs }}</strong></div>
                                <div class="col-4 border-end"><small class="text-muted d-block">Avg</small><strong class="fs-5 text-dark">{{ $player->avg }}</strong></div>
                                <div class="col-4"><small class="text-muted d-block">Strike Rate</small><strong class="fs-5 text-dark">{{ $player->sr }}</strong></div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
                    <div class="text-muted small"><i class="fa-solid fa-clock me-1"></i> Published {{ $item->time }}</div>
                </div>
            @endforeach
        @endif

    </div>

</body>
</html>