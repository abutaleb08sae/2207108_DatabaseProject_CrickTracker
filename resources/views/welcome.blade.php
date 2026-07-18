@extends('layouts.master')

@section('title', 'CrickTracker - KUET Live Dashboard')

@section('content')
    <div class="welcome-hero shadow-sm">
        <h2 class="fw-bold mb-2">Welcome to CrickTracker KUET</h2>
        <p class="lead mb-0">The official live cricket tracking system for Khulna University of Engineering & Technology.</p>
    </div>
    
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-satellite-dish text-danger me-2"></i>Live Ongoing Action</h5>
            
            @forelse($liveMatches ?? [] as $live)
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
                            @if(($live->team2_score) !== null)
                                {{ $live->team2_score }}/{{ $live->team2_wickets ?? '0' }}
                                <small class="text-muted fs-6">({{ $live->team2_overs ?? '0.0' }} Ov)</small>
                            @else
                                <span class="text-muted small fs-6">Yet to bat</span>
                            @endif
                        </span>
                    </div>
                    <div class="text-primary small fw-bold border-top pt-2 mt-2">
                        <i class="fa-solid fa-clock me-1"></i> Live tracking active from {{ $live->venue_name ?? ($live->venue ?? 'KUET Ground') }}
                    </div>
                </div>
            @empty
                <div class="card dashboard-card bg-light border p-4 text-center shadow-sm">
                    <p class="text-muted mb-0"><i class="fa-solid fa-bed me-2"></i>No matches are currently active right now.</p>
                </div>
            @endforelse

            <div class="row mt-4">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <h6 class="fw-bold mb-2 text-secondary">Latest Result Summary</h6>
                    @if(isset($recentMatches) && count($recentMatches) > 0)
                        <div class="card dashboard-card h-100 p-3 border mb-0 shadow-sm">
                            <span class="badge bg-success align-self-start mb-2">Completed</span>
                            <div class="small fw-bold text-dark mb-1">{{ $recentMatches[0]->team1_name }} vs {{ $recentMatches[0]->team2_name }}</div>
                            <div class="text-muted small">{{ $recentMatches[0]->custom_status_message ?? 'Match Completed Successfully' }}</div>
                        </div>
                    @else
                        <div class="card dashboard-card h-100 p-3 border border-dashed text-center text-muted small mb-0 shadow-sm">No historical data available.</div>
                    @endif
                </div>
                <div class="col-sm-6">
                    <h6 class="fw-bold mb-2 text-secondary">Next Upcoming Fixture</h6>
                    @if(isset($upcomingMatches) && count($upcomingMatches) > 0)
                        <div class="card dashboard-card h-100 p-3 border mb-0 shadow-sm">
                            <span class="badge bg-warning text-dark align-self-start mb-2">Scheduled</span>
                            <div class="small fw-bold text-dark mb-1">{{ $upcomingMatches[0]->team1_name ?? ($upcomingMatches[0]->team1 ?? 'Team A') }} vs {{ $upcomingMatches[0]->team2_name ?? ($upcomingMatches[0]->team2 ?? 'Team B') }}</div>
                            <div class="text-muted small"><i class="fa-solid fa-location-dot me-1"></i> {{ $upcomingMatches[0]->venue_name ?? ($upcomingMatches[0]->venue ?? 'KUET Ground') }}</div>
                        </div>
                    @else
                        <div class="card dashboard-card h-100 p-3 border border-dashed text-center text-muted small mb-0 shadow-sm">No upcoming updates.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 mt-4 mt-lg-0">
            <h5 class="fw-bold mb-3 text-dark"><i class="fa-solid fa-bolt text-warning me-2"></i>Quick Highlights</h5>
            <div class="card dashboard-card p-3 shadow-sm">
                @forelse($news ?? [] as $item)
                    <div class="mb-3 pb-2 border-bottom last-border-0">
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">{{ $item->title }}</h6>
                        <span class="text-muted style-normal" style="font-size: 0.75rem;"><i class="fa-regular fa-clock me-1"></i>{{ $item->time }}</span>
                    </div>
                @empty
                    <div class="text-muted text-center py-3 small">No highlights listed.</div>
                @endauth
            </div>
        </div>
    </div>
@endsection