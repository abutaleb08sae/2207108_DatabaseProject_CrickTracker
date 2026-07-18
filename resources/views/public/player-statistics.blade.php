@extends('layouts.master')

@section('title', 'CrickTracker - Performance Statistics')

@section('content')
    <h3 class="fw-bold mb-4 text-dark">Tournament Records</h3>
    <div class="row">
        <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
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

        <div class="col-lg-9 col-md-12">
            <div class="d-flex flex-wrap gap-2 mb-4">
                <div class="filter-pill"><div><span>Format</span><strong>T20</strong></div><i class="fa-solid fa-chevron-down small text-muted"></i></div>
                <div class="filter-pill"><div><span>Year</span><strong>2026</strong></div><i class="fa-solid fa-chevron-down small text-muted"></i></div>
                <div class="filter-pill"><div><span>Engine Context</span><strong>Oracle 3NF</strong></div></div>
            </div>

            <!-- Batting Block -->
            <div id="batting-section" class="d-flex justify-content-between align-items-center mb-3">
                <div class="table-section-title">Batting Records (Most Runs Leaders)</div>
            </div>
            <div class="card dashboard-card p-0 overflow-hidden mb-5 shadow-sm border">
                <div class="table-responsive">
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
                            @forelse($battingStats ?? [] as $player)
                                <tr>
                                    <td class="text-start p-3 fw-bold text-primary">{{ $player->player_name }}</td>
                                    <td class="p-3"><span class="badge bg-secondary opacity-75">{{ $player->team_short }}</span></td>
                                    <td class="p-3 fw-semibold text-dark">{{ $player->matches_played }}</td>
                                    <td class="p-3 text-muted">{{ $player->innings_batted }}</td>
                                    <td class="p-3 text-dark fw-bold fs-6">{{ $player->runs_scored }}</td>
                                    <td class="p-3 text-muted">{{ $player->highest_score }}</td>
                                    <td class="p-3 text-dark fw-bold">{{ number_format($player->batting_avg ?? 0, 2) }}</td>
                                    <td class="p-3 text-dark fw-semibold">{{ number_format($player->strike_rate ?? 0, 2) }}</td>
                                    <td class="p-3 text-muted">{{ $player->hundreds ?? 0 }} / {{ $player->fifties ?? 0 }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="p-3 text-muted">No batting history found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bowling Block -->
            <div id="bowling-section" class="d-flex justify-content-between align-items-center mb-3">
                <div class="table-section-title">Bowling Records (Most Wickets Leaders)</div>
            </div>
            <div class="card dashboard-card p-0 overflow-hidden shadow-sm border">
                <div class="table-responsive">
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
                            @forelse($bowlingStats ?? [] as $player)
                                <tr>
                                    <td class="text-start p-3 fw-bold text-success">{{ $player->player_name }}</td>
                                    <td class="p-3"><span class="badge bg-secondary opacity-75">{{ $player->team_short }}</span></td>
                                    <td class="p-3 text-dark fw-semibold">{{ $player->matches_played }}</td>
                                    <td class="p-3 text-success fw-bold fs-5">{{ $player->wickets_taken }}</td>
                                    <td class="p-3 text-muted">{{ $player->runs_conceded }}</td>
                                    <td class="p-3 text-dark fw-semibold">{{ $player->best_bowling_figures }}</td>
                                    <td class="p-3 text-dark table-active fw-bold">{{ number_format($player->economy_rate ?? 0, 2) }}</td>
                                    <td class="p-3 text-muted">{{ $player->five_wicket_hauls ?? 0 }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="p-3 text-muted">No bowling stats found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection