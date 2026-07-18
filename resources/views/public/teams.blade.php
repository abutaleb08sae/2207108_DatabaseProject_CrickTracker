@extends('layouts.master')

@section('title', 'CrickTracker - Department Points Standing')

@section('content')
    <h4 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-table text-secondary me-2"></i>Points Table Standings</h4>
    <div class="card dashboard-card p-0 overflow-hidden shadow-sm border">
        <div class="table-responsive">
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
                    @forelse($teams ?? [] as $team)
                        <tr>
                            <td class="text-start p-3 fw-bold text-dark"><i class="fa-solid fa-circle-nodes me-2 text-muted small"></i>{{ $team->name ?? 'Unknown Team' }}</td>
                            <td class="p-3 fw-semibold text-dark">{{ $team->played ?? 0 }}</td>
                            <td class="p-3 text-success fw-bold">{{ $team->won ?? 0 }}</td>
                            <td class="p-3 text-danger">{{ $team->lost ?? 0 }}</td>
                            <td class="p-3 text-muted">{{ $team->tied ?? 0 }}</td>
                            <td class="p-3 text-info fw-semibold">{{ number_format($team->net_run_rate ?? 0, 3) }}</td>
                            <td class="p-3 fw-bold text-primary fs-6 bg-light">{{ $team->points ?? 0 }} pts</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-muted">No team league records inside current relational matrix.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection