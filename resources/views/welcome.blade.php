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
        
        {{-- ================================================================= --}}
        {{-- 1. DASHBOARD VIEW                                                 --}}
        {{-- ================================================================= --}}
        @if($currentView == 'dashboard')
            <div class="welcome-hero shadow-sm">
                <h2 class="fw-bold mb-2">Welcome to CrickTracker KUET</h2>
                <p class="lead mb-0">The official live cricket tracking system for Khulna University of Engineering & Technology.</p>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-satellite-dish text-danger me-2"></i>Live Ongoing Action</h5>
                    
                    @if(count($liveMatches) > 0)
                        @foreach($liveMatches as $live)
                            <div class="card dashboard-card bg-white border border-danger-subtle shadow-sm">
                                <div class="d-flex justify-content-between text-danger small fw-bold mb-3">
                                    <span>Match #{{ $live->match_id }} • T20 Format</span>
                                    <span><i class="fa-solid fa-circle-dot animate-pulse"></i> LIVE NOW</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fs-5 fw-bold text-dark">{{ $live->team1_name }}</span>
                                    <span class="fs-5 fw-bold text-dark">
                                        {{ $live->team1_score ?? '0' }}/{{ $live->team1_wickets ?? '0' }}
                                        <small class="text-muted fs-6">({{ $live->team1_overs ?? '0.0' }} Ov)</small>
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="fs-5 fw-bold text-dark">{{ $live->team2_name }}</span>
                                    <span class="fs-5 fw-bold text-dark">
                                        @if($live->team2_score)
                                            {{ $live->team2_score }}/{{ $live->team2_wickets }}
                                            <small class="text-muted fs-6">({{ $live->team2_overs }} Ov)</small>
                                        @else
                                            <span class="text-muted small fs-6">Yet to bat</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="text-primary small fw-bold border-top pt-2 mt-2">
                                    <i class="fa-solid fa-clock me-1"></i> Live tracking active from {{ $live->venue_name }}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="card dashboard-card bg-light border p-4 text-center">
                            <p class="text-muted mb-0"><i class="fa-solid fa-bed me-2"></i>No matches are currently active right now.</p>
                        </div>
                    @endif

                    <!-- Quick Mini Panels for Summaries -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2 text-secondary">Latest Result Summary</h6>
                            @if(count($recentMatches) > 0)
                                <div class="card dashboard-card p-3 border">
                                    <span class="badge bg-success align-self-start mb-2">Completed</span>
                                    <div class="small fw-bold text-dark mb-1">{{ $recentMatches[0]->team1_name }} vs {{ $recentMatches[0]->team2_name }}</div>
                                    <div class="text-muted small">{{ $recentMatches[0]->custom_status_message ?? 'Match Completed Successfully' }}</div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2 text-secondary">Next Upcoming Fixture</h6>
                            @if(count($upcomingMatches) > 0)
                                <div class="card dashboard-card p-3 border">
                                    <span class="badge bg-warning text-dark align-self-start mb-2">Scheduled</span>
                                    <div class="small fw-bold text-dark mb-1">{{ $upcomingMatches[0]->team1_name }} vs {{ $upcomingMatches[0]->team2_name }}</div>
                                    <div class="text-muted small"><i class="fa-solid fa-location-dot me-1"></i> {{ $upcomingMatches[0]->venue_name }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar News widget inside dashboard -->
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-bolt text-warning me-2"></i>Quick Highlights</h5>
                    <div class="card dashboard-card p-3">
                        @foreach($news as $item)
                            <div class="mb-3 pb-2 border-bottom last-border-0">
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">{{ $item->title }}</h6>
                                <span class="text-muted style-normal" style="font-size: 0.75rem;"><i class="fa-regular fa-clock me-1"></i>{{ $item->time }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        {{-- ================================================================= --}}
        {{-- 2. RECENT MATCHES VIEW                                            --}}
        {{-- ================================================================= --}}
        @elseif($currentView == 'recent')
            <h4 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-history text-success me-2"></i>Recent Matches Results</h4>
            <div class="row">
                @forelse($recentMatches as $match)
                    <div class="col-md-6 mb-4">
                        <div class="card dashboard-card h-100 mb-0 shadow-sm border border-light">
                            <div class="text-muted small mb-2 d-flex justify-content-between">
                                <span>Tournament Match #{{ $match->match_id }}</span>
                                <span class="badge bg-light text-dark">{{ $match->venue_name }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="fw-bold text-dark">{{ $match->team1_name }}</span>
                                <span class="fw-bold text-dark">
                                    {{ $match->team1_score ?? '0' }}/{{ $match->team1_wickets ?? '0' }}
                                    <small class="text-muted fw-normal">({{ $match->team1_overs ?? '0.0' }})</small>
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold text-dark">{{ $match->team2_name }}</span>
                                <span class="fw-bold text-dark">
                                    {{ $match->team2_score ?? '0' }}/{{ $match->team2_wickets ?? '0' }}
                                    <small class="text-muted fw-normal">({{ $match->team2_overs ?? '0.0' }})</small>
                                </span>
                            </div>
                            <div class="text-success small fw-bold border-top pt-2 mt-auto">
                                <i class="fa-solid fa-trophy me-1 text-warning"></i> {{ $match->custom_status_message ?? 'Match concluded.' }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card dashboard-card p-4 text-center text-muted">No historical matches recorded yet.</div>
                    </div>
                @endforelse
            </div>

        {{-- ================================================================= --}}
        {{-- 3. UPCOMING MATCHES VIEW                                          --}}
        {{-- ================================================================= --}}
        @elseif($currentView == 'upcoming')
            <h4 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-calendar-days text-warning me-2"></i>Upcoming Matches Schedule</h4>
            <div class="card dashboard-card p-0 overflow-hidden shadow-sm border">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="p-3">Match Code</th>
                            <th class="p-3">Match Fixture</th>
                            <th class="p-3">Date</th>
                            <th class="p-3">Time</th>
                            <th class="p-3">Ground Venue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingMatches as $match)
                            <tr>
                                <td class="p-3 text-muted fw-semibold">#{{ $match->match_id }}</td>
                                <td class="p-3 fw-bold text-primary fs-6">{{ $match->team1 }} vs {{ $match->team2 }}</td>
                                <td class="p-3"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1.5">{{ $match->date }}</span></td>
                                <td class="p-3 text-dark fw-semibold">{{ $match->time }}</td>
                                <td class="p-3 text-muted"><i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $match->venue }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-4 text-center text-muted">No future fixtures scheduled in system.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        {{-- ================================================================= --}}
        {{-- 4. PLAYER STATISTICS VIEW                                         --}}
        {{-- ================================================================= --}}
        @elseif($currentView == 'stats')
            <h3 class="fw-bold mb-4 text-dark">Tournament Records</h3>
            <div class="row">
                <div class="col-md-3">
                    <div class="card dashboard-card p-2 shadow-sm border">
                        <div class="stat-menu-title mb-2">QUICK LINKS</div>
                        <a href="#batting-section" class="stat-menu-item fw-bold text-primary"><i class="fa-solid fa-gavel me-2"></i>Batting Leaders</a>
                        <a href="#bowling-section" class="stat-menu-item fw-bold text-success"><i class="fa-solid fa-baseball me-2"></i>Bowling Leaders</a>
                        <hr class="my-2">
                        <div class="stat-menu-title mb-2">TRACKING FILTERS</div>
                        <a href="#" class="stat-menu-item active"><i class="fa-solid fa-chevron-right float-end mt-1 small"></i>Most Runs</a>
                        <a href="#" class="stat-menu-item"><i class="fa-solid fa-chevron-right float-end mt-1 small"></i>Most Wickets</a>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="d-flex gap-2 mb-4">
                        <div class="filter-pill"><div><span>Format</span><strong>T20</strong></div><i class="fa-solid fa-chevron-down small text-muted"></i></div>
                        <div class="filter-pill"><div><span>Year</span><strong>2026</strong></div><i class="fa-solid fa-chevron-down small text-muted"></i></div>
                        <div class="filter-pill"><div><span>Engine Context</span><strong>Oracle 3NF</strong></div></div>
                    </div>

                    <!-- Batting View Section -->
                    <div id="batting-section" class="d-flex justify-content-between align-items-center mb-3">
                        <div class="table-section-title">Batting Records (Most Runs Leaders)</div>
                    </div>
                    <div class="card dashboard-card p-0 overflow-hidden mb-5 shadow-sm border">
                        <table class="table table-hover mb-0 align-middle text-center">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th class="text-start p-3 text-muted small fw-bold">Player</th>
                                    <th class="p-3 text-muted small fw-bold">Team</th>
                                    <th class="p-3 text-muted small fw-bold">Matches</th>
                                    <th class="p-3 text-muted small fw-bold">Inns</th>
                                    <th class="p-3 text-muted small fw-bold">Runs</th>
                                    <th class="p-3 text-muted small fw-bold">HS</th>
                                    <th class="p-3 text-muted small fw-bold">Avg</th>
                                    <th class="p-3 text-muted small fw-bold">SR</th>
                                    <th class="p-3 text-muted small fw-bold">100 / 50</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($battingStats as $player)
                                    <tr>
                                        <td class="text-start p-3 fw-bold text-primary">{{ $player->player_name }}</td>
                                        <td class="p-3"><span class="badge bg-secondary opacity-75">{{ $player->team_short }}</span></td>
                                        <td class="p-3 fw-semibold text-dark">{{ $player->matches_played }}</td>
                                        <td class="p-3 text-muted">{{ $player->innings_batted }}</td>
                                        <td class="p-3 text-dark fw-bold fs-6">{{ $player->runs_scored }}</td>
                                        <td class="p-3 text-muted">{{ $player->highest_score }}</td>
                                        <td class="p-3 text-dark fw-bold">{{ number_format($player->batting_avg, 2) }}</td>
                                        <td class="p-3 text-dark fw-semibold">{{ number_format($player->strike_rate, 2) }}</td>
                                        <td class="p-3 text-muted">{{ $player->hundreds }} / {{ $player->fifties }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="p-3 text-muted">No batting history found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Bowling View Section -->
                    <div id="bowling-section" class="d-flex justify-content-between align-items-center mb-3">
                        <div class="table-section-title">Bowling Records (Most Wickets Leaders)</div>
                    </div>
                    <div class="card dashboard-card p-0 overflow-hidden shadow-sm border">
                        <table class="table table-hover mb-0 align-middle text-center">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th class="text-start p-3 text-muted small fw-bold">Bowler</th>
                                    <th class="p-3 text-muted small fw-bold">Team</th>
                                    <th class="p-3 text-muted small fw-bold">Matches</th>
                                    <th class="p-3 text-muted small fw-bold">Wickets</th>
                                    <th class="p-3 text-muted small fw-bold">Runs Conc</th>
                                    <th class="p-3 text-muted small fw-bold">Best Bowling</th>
                                    <th class="p-3 text-muted small fw-bold">Economy</th>
                                    <th class="p-3 text-muted small fw-bold">5w</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bowlingStats as $player)
                                    <tr>
                                        <td class="text-start p-3 fw-bold text-success">{{ $player->player_name }}</td>
                                        <td class="p-3"><span class="badge bg-secondary opacity-75">{{ $player->team_short }}</span></td>
                                        <td class="p-3 text-dark fw-semibold">{{ $player->matches_played }}</td>
                                        <td class="p-3 text-success fw-bold fs-5">{{ $player->wickets_taken }}</td>
                                        <td class="p-3 text-muted">{{ $player->runs_conceded }}</td>
                                        <td class="p-3 text-dark fw-semibold">{{ $player->best_bowling_figures }}</td>
                                        <td class="p-3 text-dark table-active fw-bold">{{ number_format($player->economy_rate, 2) }}</td>
                                        <td class="p-3 text-muted">{{ $player->five_wicket_hauls }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="p-3 text-muted">No bowling stats found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        {{-- ================================================================= --}}
        {{-- 5. TEAMS / POINTS TABLE VIEW                                      --}}
        {{-- ================================================================= --}}
        @elseif($currentView == 'teams')
            <h4 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-table text-secondary me-2"></i>Points Table Standings</h4>
            <div class="card dashboard-card p-0 overflow-hidden shadow-sm border">
                <table class="table table-striped mb-0 text-center align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start p-3">Department Entity</th>
                            <th class="p-3">Played</th>
                            <th class="p-3 text-success">Won</th>
                            <th class="p-3 text-danger">Lost</th>
                            <th class="p-3 text-warning">Tied</th>
                            <th class="p-3 text-info">Net NRR</th>
                            <th class="p-3">Points Summary</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teams as $team)
                            <tr>
                                <td class="text-start p-3 fw-bold text-dark"><i class="fa-solid fa-circle-nodes me-2 text-muted small"></i>{{ $team->name }}</td>
                                <td class="p-3 fw-semibold text-dark">{{ $team->played }}</td>
                                <td class="p-3 text-success fw-bold">{{ $team->won }}</td>
                                <td class="p-3 text-danger">{{ $team->lost }}</td>
                                <td class="p-3 text-muted">{{ $team->tied }}</td>
                                <td class="p-3 text-info fw-semibold">{{ number_format($team->net_run_rate, 3) }}</td>
                                <td class="p-3 fw-bold text-primary fs-6 bg-light">{{ $team->points }} pts</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-4 text-muted">No team league records inside current relational matrix.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        {{-- ================================================================= --}}
        {{-- 6. NEWS VIEW                                                      --}}
        {{-- ================================================================= --}}
        @elseif($currentView == 'news')
            <h4 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-newspaper text-primary me-2"></i>Latest Tournament News</h4>
            <div class="row">
                <div class="col-md-9">
                    @forelse($allNews as $item)
                        <div class="card dashboard-card mb-4 shadow-sm border border-light-subtle">
                            <h5 class="fw-bold text-dark mb-2 text-primary-hover">{{ $item->title }}</h5>
                            <p class="text-secondary mb-3" style="font-size: 0.95rem; line-height: 1.6;">{{ $item->content }}</p>
                            <div class="text-muted small d-flex align-items-center gap-1 border-top pt-2">
                                <i class="fa-regular fa-calendar me-1"></i> Published: {{ $item->formatted_time }}
                            </div>
                        </div>
                    @empty
                        <div class="card dashboard-card p-4 text-center text-muted">No news feed postings available at the moment.</div>
                    @endforelse
                </div>
            </div>
        @endif

    </div>

</body>
</html>