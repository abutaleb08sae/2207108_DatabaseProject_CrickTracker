<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CrickTracker</title>
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
        .table th { color: #64748b; font-weight: 600; font-size: 0.85rem; text-uppercase: true; }
        .team-score-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
    </style>
</head>
<body>

    <div class="side-nav">
        <div class="sidebar-brand fs-4 text-uppercase">
            <i class="fa-solid fa-chart-line me-2"></i>CrickTracker
        </div>
        <div class="nav flex-column mt-4">
            <a href="#" class="nav-link active"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-history"></i> Recent Matches</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-calendar-days"></i> Upcoming Matches</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-user-astronaut"></i> Player Statistics</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-shield-halved"></i> Teams</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-newspaper"></i> News</a>
        </div>
    </div>

    <div class="main-content">
        <div class="row">
            
            <div class="col-lg-8">
                <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-circle-check text-success me-2"></i>Recent Matches</h5>
                <div class="row">
                    @foreach($recentMatches as $match)
                        <div class="col-md-6 mb-4">
                            <div class="card dashboard-card h-100 mb-0">
                                <div class="d-flex justify-content-between text-muted small mb-3">
                                    <span>{{ $match->type }} Match</span>
                                    <span class="text-success fw-bold">Finished</span>
                                </div>
                                <div class="team-score-row">
                                    <span class="fs-5 fw-bold text-dark">{{ $match->team1 }}</span>
                                    <span class="fs-5 fw-bold text-secondary">{{ $match->score1 }} <small class="text-muted text-xs">({{ $match->overs1 }})</small></span>
                                </div>
                                <div class="team-score-row mb-3">
                                    <span class="fs-5 fw-bold text-dark">{{ $match->team2 }}</span>
                                    <span class="fs-5 fw-bold text-secondary">{{ $match->score2 }} <small class="text-muted text-xs">({{ $match->overs2 }})</small></span>
                                </div>
                                <hr class="my-2 opacity-50">
                                <p class="text-success small mb-0 fw-bold"><i class="fa-solid fa-trophy me-1"></i> {{ $match->result }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-newspaper text-primary me-2"></i>Latest News</h5>
                <div class="card dashboard-card">
                    @foreach($news as $item)
                        <div class="mb-3 pb-3 border-bottom last-border-0">
                            <h6 class="fw-bold mb-1 text-dark" style="font-size:0.95rem;">{{ $item->title }}</h6>
                            <span class="text-muted small"><i class="fa-solid fa-clock me-1"></i>{{ $item->time }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="row mt-2">
            
            <div class="col-md-6 col-lg-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-clock text-warning me-2"></i>Upcoming Matches</h5>
                <div class="card dashboard-card">
                    @foreach($upcomingMatches as $match)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div>
                                <span class="fw-bold text-dark">{{ $match->team1 }} vs {{ $match->team2 }}</span>
                                <div class="text-muted small">{{ $match->venue }}</div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-light text-dark border d-block mb-1">{{ $match->date }}</span>
                                <small class="text-muted">{{ $match->time }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-star text-warning me-2"></i>Top Performers</h5>
                <div class="card dashboard-card">
                    @foreach($playerStats as $player)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div>
                                <span class="fw-bold text-dark">{{ $player->name }}</span>
                                <div class="text-muted small">Dept: {{ $player->team }}</div>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-primary d-block">{{ $player->runs }} Runs</span>
                                <small class="text-muted">SR: {{ $player->sr }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-table text-secondary me-2"></i>Teams Standings</h5>
                <div class="card dashboard-card p-2">
                    <table class="table table-borderless mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Team</th>
                                <th class="text-center">P</th>
                                <th class="text-center">W</th>
                                <th class="text-center">L</th>
                                <th class="text-center">PTS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teams as $team)
                                <tr>
                                    <td class="fw-bold text-dark">{{ $team->name }}</td>
                                    <td class="text-center">{{ $team->played }}</td>
                                    <td class="text-center text-success">{{ $team->won }}</td>
                                    <td class="text-center text-danger">{{ $team->lost }}</td>
                                    <td class="text-center fw-bold text-primary">{{ $team->points }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</body>
</html>