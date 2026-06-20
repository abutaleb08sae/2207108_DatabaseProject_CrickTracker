<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CrickTracker Systems</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7fc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; }
        .sidebar-brand { background: linear-gradient(135deg, #0f2027, #203a43, #2c5364); color: #fff; padding: 20px; text-align: center; font-weight: 700; letter-spacing: 1px; }
        .side-nav { background: #1e293b; min-height: 100vh; position: fixed; width: 260px; box-shadow: 4px 0 10px rgba(0,0,0,0.05); }
        .side-nav .nav-link { color: #94a3b8; padding: 12px 25px; font-weight: 500; display: flex; align-items: center; gap: 12px; transition: all 0.3s; }
        .side-nav .nav-link:hover, .side-nav .nav-link.active { color: #fff; background: rgba(255,255,255,0.05); border-left: 4px solid #38bdf8; }
        .main-content { margin-left: 260px; padding: 40px; }
        .page-header { background: #fff; padding: 20px 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        .stat-card { border: none; border-radius: 16px; background: #fff; box-shadow: 0 4px 20px rgba(0,0,0,0.02); transition: transform 0.3s; overflow: hidden; }
        .stat-card:hover { transform: translateY(-5px); }
        .match-card-premium { border: none; border-radius: 16px; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.03); transition: all 0.3s; border-top: 4px solid #64748b; }
        .match-card-premium.live { border-top-color: #ef4444; }
        .live-pulse { height: 8px; width: 8px; background: #ef4444; border-radius: 50%; display: inline-block; animation: pulse 1.5s infinite; }
        @keyframes pulse { 0% { transform: scale(0.9); opacity: 1; } 50% { transform: scale(1.2); opacity: 0.5; } 100% { transform: scale(0.9); opacity: 1; } }
        .quick-action-btn { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 15px; text-align: center; font-weight: 600; color: #475569; transition: all 0.2s; display: block; text-decoration: none; }
        .quick-action-btn:hover { background: #38bdf8; color: #fff; border-color: #38bdf8; transform: translateY(-2px); }
    </style>
</head>
<body>

    <div class="side-nav">
        <div class="sidebar-brand fs-4 text-uppercase">
            <i class="fa-solid fa-chart-line me-2"></i>CrickTracker
        </div>
        <div class="nav flex-column mt-4">
            <a href="#" class="nav-link active"><i class="fa-solid fa-house"></i> Dashboard</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-circle-nodes"></i> Live Matches</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-users"></i> Teams Directory</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-id-card"></i> Player Roster</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-database"></i> SQL Queries</a>
        </div>
    </div>

    <div class="main-content">
        
        <div class="page-header">
            <div>
                <h4 class="fw-bold mb-1 text-dark">System Overview Panel</h4>
                <p class="text-muted small mb-0">Connected Schema Pool: Sandbox Environment</p>
            </div>
            <div class="badge bg-primary px-3 py-2 rounded-pill"><i class="fa-solid fa-circle-check me-1"></i> Preview Mode Active</div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card stat-card p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted small fw-bold mb-1">Total Relational Matches</h6>
                            <h2 class="fw-bold text-dark mb-0">{{ $totalMatches }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary"><i class="fa-solid fa-database fa-2xl"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted small fw-bold mb-1">Active Match Realtime</h6>
                            <h2 class="fw-bold text-dark mb-0">{{ $liveCount }}</h2>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded-3 text-danger"><i class="fa-solid fa-tower-broadcast fa-2xl"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase text-muted small fw-bold mb-1">System State</h6>
                            <h2 class="fw-bold text-success mb-0" style="font-size: 24px;">Operational</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-3 text-success"><i class="fa-solid fa-shield-halved fa-2xl"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-satellite-dish text-danger me-2"></i>Live Track Streams</h5>
        
        <div class="row g-4 mb-5">
            @forelse($matches as $match)
                <div class="col-md-6 col-lg-4">
                    <div class="card match-card-premium p-4 {{ strtolower($match->match_status) == 'live' ? 'live' : '' }}">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-light text-dark fw-bold border">Tournament Match</span>
                            @if(strtolower($match->match_status) == 'live')
                                <span class="text-danger small fw-bold d-flex align-items-center gap-1"><span class="live-pulse"></span> LIVE</span>
                            @else
                                <span class="text-muted small fw-bold text-uppercase">{{ $match->match_status }}</span>
                            @endif
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fs-5 fw-bold text-dark">{{ $match->team1_id }}</span>
                            <span class="fs-5 fw-bold text-secondary">{{ $match->team1_score }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fs-5 fw-bold text-dark">{{ $match->team2_id }}</span>
                            <span class="fs-5 fw-bold text-secondary">{{ $match->team2_score }}</span>
                        </div>
                        
                        <hr class="my-3 opacity-50">
                        <p class="text-muted small mb-0 text-truncate"><i class="fa-solid fa-circle-info me-1"></i> Status: Match is currently {{ $match->match_status }}</p>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="bg-white p-5 rounded-4 text-center border shadow-sm">
                        <i class="fa-solid fa-folder-open text-muted mb-3 fa-3x"></i>
                        <h5 class="text-dark fw-bold">No Match Streams Available</h5>
                    </div>
                </div>
            @endforelse
        </div>

        <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-bolt text-warning me-2"></i>System Navigation Hub</h5>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <a href="#" class="quick-action-btn"><i class="fa-solid fa-plus-circle d-block mb-2 fa-lg text-primary"></i> Add Match Entry</a>
            </div>
            <div class="col-6 col-md-3">
                <a href="#" class="quick-action-btn"><i class="fa-solid fa-user-plus d-block mb-2 fa-lg text-success"></i> Register Player</a>
            </div>
            <div class="col-6 col-md-3">
                <a href="#" class="quick-action-btn"><i class="fa-solid fa-shield d-block mb-2 fa-lg text-warning"></i> Manage Teams</a>
            </div>
            <div class="col-6 col-md-3">
                <a href="#" class="quick-action-btn"><i class="fa-solid fa-file-code d-block mb-2 fa-lg text-info"></i> View System Logs</a>
            </div>
        </div>

    </div>

</body>
</html>