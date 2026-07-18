@extends('layouts.master') <!-- Extends your main master layout framework -->

@section('title', 'KUET CrickTracker - Live Dashboard')

@section('content')
<div class="container-fluid p-4">
    <!-- Top Hero Welcome Banner Component -->
    <div class="bg-primary text-white p-4 rounded mb-4 shadow-sm" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
        <h2 class="font-weight-bold">Welcome to CrickTracker KUET</h2>
        <p class="mb-0 text-white-50">The official live cricket tracking system for Khulna University of Engineering & Technology.</p>
    </div>

    <div class="row">
        <!-- Live Ongoing Action Panel -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm h-100 border-0">
                <div class="card-header bg-white font-weight-bold text-danger d-flex align-items-center border-bottom-0 pt-3">
                    <span class="spinner-grow spinner-grow-sm text-danger mr-2" role="status" aria-hidden="true"></span>
                    Live Ongoing Action
                </div>
                <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 200px;">
                    @if(isset($liveMatch) && $liveMatch)
                        <div class="text-center w-100 p-3">
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <h3 class="font-weight-bold text-dark px-3 mb-0">{{ strtoupper($liveMatch['team1_short']) }}</h3>
                                <span class="badge badge-light text-muted font-weight-bold mx-2">VS</span>
                                <h3 class="font-weight-bold text-dark px-3 mb-0">{{ strtoupper($liveMatch['team2_short']) }}</h3>
                            </div>
                            <p class="text-muted mb-3"><i class="fas fa-map-marker-alt mr-1"></i> {{ $liveMatch['venue_name'] }}</p>
                            <span class="badge badge-danger p-2 px-3 shadow-sm" style="font-size: 0.9rem;">
                                Toss: {{ strtoupper($liveMatch['toss_decision']) }} elected to bat first
                            </span>
                        </div>
                    @else
                        <div class="text-muted text-center p-4">
                            <i class="fas fa-calendar-times fa-3x mb-3 text-light"></i>
                            <p class="mb-0 font-weight-bold">No matches are currently active right now.</p>
                            <small class="text-muted">Check back later when departmental iterations begin.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Hand Metrics Summary Cards -->
        <div class="col-lg-4 mb-4">
            <!-- Latest Result Summary Card -->
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white font-weight-bold text-dark border-bottom-0 pt-3">
                    Latest Result Summary
                </div>
                <div class="card-body">
                    @if(isset($latestResult) && $latestResult)
                        <div class="p-2 border rounded bg-light mb-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="font-weight-bold text-primary">{{ $latestResult['team1_short'] }} vs {{ $latestResult['team2_short'] }}</span>
                                <span class="badge badge-success text-uppercase">Finished</span>
                            </div>
                        </div>
                        <small class="text-muted d-block mt-2">Historical logging sync complete via Oracle Engine.</small>
                    @else
                        <div class="text-center p-3 text-muted">
                            <p class="mb-0 small">No historical summary data available.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Context Highlight Information Component -->
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body p-3">
                    <h6 class="font-weight-bold text-dark"><i class="fas fa-bolt text-warning mr-2"></i>Quick Highlights</h6>
                    <hr class="my-2">
                    <p class="small text-muted mb-0">KUET Inter-Department Cricket League execution context finalized inside database parameters.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection