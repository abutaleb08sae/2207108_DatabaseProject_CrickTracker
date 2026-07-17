<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CrickTracker Admin - Match Control Panel</title>
    <!-- Bootstrap 5 & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #0f172a; font-family: 'Segoe UI', system-ui, sans-serif; color: #f8fafc; }
        .admin-sidebar { background: #1e293b; min-height: 100vh; position: fixed; width: 280px; border-right: 1px solid #334155; z-index: 100; }
        .admin-brand { background: #0f172a; padding: 24px; font-weight: 800; color: #10b981; letter-spacing: 0.5px; border-bottom: 1px solid #334155; }
        .admin-content { margin-left: 280px; padding: 40px; }
        .control-card { background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 24px; margin-bottom: 24px; }
        .scoring-btn { font-weight: 700; font-size: 1.1rem; padding: 15px; border-radius: 8px; transition: all 0.2s; }
        .btn-run { background: #334155; color: #f8fafc; border: 1px solid #475569; }
        .btn-run:hover { background: #475569; color: #fff; }
        .btn-wicket { background: #ef4444; color: white; border: none; }
        .btn-wicket:hover { background: #dc2626; }
        .btn-extra { background: #d97706; color: white; border: none; }
        .btn-extra:hover { background: #b45309; }
        .badge-live { background: #10b981; color: #0f172a; font-weight: 700; animation: pulse 2s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.6; } }
    </style>
</head>
<body>

    <!-- Admin Sidebar Navigation -->
    <div class="admin-sidebar">
        <div class="admin-brand text-uppercase d-flex align-items-center gap-2">
            <i class="fa-solid fa-screwdriver-wrench"></i> CrickTracker Center
        </div>
        <div class="nav flex-column p-3 gap-1">
            <a href="#" class="nav-link text-white bg-dark rounded px-3 py-2.5 active"><i class="fa-solid fa-radio me-2 text-emerald"></i> Live Scoring Panel</a>
            <a href="#" class="nav-link text-muted px-3 py-2.5"><i class="fa-solid fa-people-group me-2"></i> Team Management</a>
            <a href="#" class="nav-link text-muted px-3 py-2.5"><i class="fa-solid fa-user-gear me-2"></i> Player Profiles</a>
            <a href="#" class="nav-link text-muted px-3 py-2.5"><i class="fa-solid fa-calendar-check me-2"></i> Schedule Fixtures</a>
            <hr class="border-secondary my-2">
            <a href="{{ url('/') }}" class="nav-link text-info px-3 py-2.5"><i class="fa-solid fa-arrow-left me-2"></i> View Public Site</a>
        </div>
    </div>

    <!-- Main Controller Form Context -->
    <div class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1 text-white">Live Match Control</h2>
                <p class="text-muted mb-0">Push operational ball events straight down into Oracle relational structures.</p>
            </div>
            <div>
                <span class="badge badge-live px-3 py-2 fs-6">CONSOLE ACTIVE</span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success bg-emerald-subtle text-dark border-0 fw-semibold mb-4">{{ session('success') }}</div>
        @endif

        <div class="row">
            <!-- Left Panel: Core Transactional Scoring Input Form -->
            <div class="col-lg-8 col-md-12">
                <div class="control-card">
                    <h5 class="fw-bold mb-4 text-white border-bottom border-secondary pb-2"><i class="fa-solid fa-circle-plus text-success me-2"></i>Log Current Ball Outcome</h5>
                    
                    <form action="{{ url('/admin/match/ball') }}" method="POST">
                        @csrf
                        <!-- Active Match Hidden Target Context mapping back to parameters -->
                        <input type="hidden" name="match_id" value="{{ $activeMatch->match_id ?? 1 }}">
                        <input type="hidden" name="innings" value="{{ $activeMatch->current_innings ?? 1 }}">

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">ON-STRIKE BATSMAN</label>
                                <select class="form-select bg-dark text-white border-secondary" name="batsman_id" required>
                                    @foreach($battingSquad as $player)
                                        <option value="{{ $player->player_id }}">{{ $player->first_name }} {{ $player->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">ACTIVE BOWLER</label>
                                <select class="form-select bg-dark text-white border-secondary" name="bowler_id" required>
                                    @foreach($bowlingSquad as $player)
                                        <option value="{{ $player->player_id }}">{{ $player->first_name }} {{ $player->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Runs Matrix Select Options -->
                        <label class="form-label text-muted small fw-bold mb-2">RUNS SCORED FROM BAT</label>
                        <div class="row g-2 mb-4">
                            @foreach([0, 1, 2, 3, 4, 6] as $run)
                                <div class="col-2">
                                    <input type="radio" class="btn-check" name="runs_scored" id="run_{{ $run }}" value="{{ $run }}" {{ $run==0 ? 'checked' : '' }}>
                                    <label class="btn btn-run scoring-btn w-100" for="run_{{ $run }}">{{ $run }}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="row g-3 mb-4">
                            <!-- Extras Logic Checkboxes -->
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">EXTRAS / MISC EVENTS</label>
                                <select class="form-select bg-dark text-white border-secondary" name="extra_type">
                                    <option value="">None (Normal Delivery)</option>
                                    <option value="WD">Wide (WD)</option>
                                    <option value="NB">No Ball (NB)</option>
                                    <option value="LB">Leg Bye (LB)</option>
                                    <option value="B">Bye (B)</option>
                                </select>
                                <input type="number" class="form-control bg-dark text-white border-secondary mt-2" name="extra_runs" value="0" placeholder="Extra runs amount if any">
                            </div>
                            <!-- Wicket Dropdown Capture Elements -->
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-danger">WICKET OUTCOME</label>
                                <select class="form-select bg-dark text-white border-danger" name="wicket_type">
                                    <option value="">Not Out / Safe Delivery</option>
                                    <option value="Bowled">Bowled</option>
                                    <option value="Caught">Caught</option>
                                    <option value="LBH">LBW</option>
                                    <option value="Run Out">Run Out</option>
                                    <option value="Stumped">Stumped</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">BALL COMMENTARY TEXT</label>
                            <textarea class="form-control bg-dark text-white border-secondary" name="description" rows="2" placeholder="e.g., Short delivery handled beautifully past deep mid-wicket for boundaries..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-success bg-emerald text-dark fw-bold w-100 py-2.5"><i class="fa-solid fa-bolt me-1"></i> Transmit Event to Oracle Instance</button>
                    </form>
                </div>
            </div>

            <!-- Right Panel: Current State Monitor Panel -->
            <div class="col-lg-4 col-md-12">
                <div class="control-card text-center">
                    <span class="text-muted small fw-bold d-block mb-1">LIVE STATE TRACKER</span>
                    <h3 class="fw-bold text-white mb-2">{{ $activeMatch->team1_short ?? 'TEAM 1' }} vs {{ $activeMatch->team2_short ?? 'TEAM 2' }}</h3>
                    <hr class="border-secondary">
                    <div class="py-3">
                        <span class="text-muted d-block small">CURRENT SCORE</span>
                        <h1 class="display-4 fw-black text-emerald my-1">{{ $activeMatch->team1_score ?? '0' }}/{{ $activeMatch->team1_wickets ?? '0' }}</h1>
                        <span class="text-muted small">Overs Logged: <strong>{{ $activeMatch->team1_overs ?? '0.0' }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>