@extends('layouts.master')

@section('title', 'Dashboard - CrickTracker KUET')

@section('content')
<div class="container-fluid">
    <!-- Welcome Hero -->
    <div class="welcome-hero mb-4">
        <h2>Welcome to CrickTracker KUET</h2>
        <p class="mb-0">The official live cricket tracking system for Khulna University of Engineering & Technology.</p>
    </div>

    <div class="row">
        <!-- Left Column: Live Matches -->
        <div class="col-lg-8">
            <div class="dashboard-card border-0 bg-white shadow-sm p-4">
                <h4 class="mb-4 text-danger fw-bold d-flex align-items-center">
                    <i class="fa-solid fa-circle-dot animate-pulse me-2"></i>Live Ongoing Action
                </h4>

                <!-- PREMIUM LIVE MATCH CARD -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3" style="background: linear-gradient(145deg, #ffffff, #f8fafc); border-left: 5px solid #dc3545 !important;">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3 px-4 border-0">
                        <span class="fw-bold tracking-wider text-uppercase small"><i class="fa-solid fa-tower-broadcast text-danger me-2"></i>MATCH #999 — LIVE</span>
                        <span class="badge bg-danger px-3 py-2 fw-bold animate-pulse rounded-pill">OVER 16.2</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center text-center">
                            <!-- Team 1 -->
                            <div class="col-md-5">
                                <h1 class="fw-extrabold text-dark display-6 mb-1">CSE</h1>
                                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill fw-bold small">BATTING</span>
                                <h2 class="mt-3 text-primary fw-bold display-5">145 / 4</h2>
                                <p class="text-muted small mb-0">(16.2 Overs)</p>
                            </div>
                            <!-- VS Splitter -->
                            <div class="col-md-2 my-4 my-md-0">
                                <div class="d-inline-block bg-light border rounded-circle p-3 shadow-sm">
                                    <span class="fw-bold text-muted px-1">VS</span>
                                </div>
                            </div>
                            <!-- Team 2 -->
                            <div class="col-md-5">
                                <h1 class="fw-extrabold text-muted display-6 mb-1">EEE</h1>
                                <span class="badge bg-secondary-subtle text-secondary px-3 py-1 rounded-pill fw-bold small">BOWLING</span>
                                <h2 class="mt-3 text-muted fw-bold display-5">-- / --</h2>
                                <p class="text-muted small mb-0">(Yet to Bat)</p>
                            </div>
                        </div>
                        
                        <div class="border-top mt-4 pt-3 d-flex flex-wrap justify-content-between align-items-center text-muted small gap-2">
                            <span><i class="fa-solid fa-location-dot text-secondary me-1"></i> KUET Main Playground</span>
                            <span class="bg-light px-3 py-1 rounded-pill border"><strong>Toss:</strong> EEE won toss & elected to bowl first</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar Summaries -->
        <div class="col-lg-4">
            <!-- Latest Result Summary Sidebar Widget -->
            <div class="dashboard-card border-0 bg-white shadow-sm p-4 mb-4">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                    <i class="fa-solid fa-square-poll-horizontal text-primary me-2"></i>Latest Results
                </h5>
                <div class="p-3 bg-light rounded-3 border-start border-success border-3 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark">ME (162/5)</span>
                        <span class="badge bg-success-subtle text-success rounded-pill px-2">Won</span>
                    </div>
                    <div class="text-muted small mb-1">vs ECE (158/8)</div>
                    <div class="text-success small fw-semibold"><i class="fa-solid fa-trophy me-1"></i> ME won by 5 wickets</div>
                </div>
                <div class="p-3 bg-light rounded-3 border-start border-success border-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark">LE (184/3)</span>
                        <span class="badge bg-success-subtle text-success rounded-pill px-2">Won</span>
                    </div>
                    <div class="text-muted small mb-1">vs CE (160/10)</div>
                    <div class="text-success small fw-semibold"><i class="fa-solid fa-trophy me-1"></i> LE won by 24 runs</div>
                </div>
            </div>

            <!-- Quick Highlights Feed -->
            <div class="dashboard-card border-0 bg-white shadow-sm p-4">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                    <i class="fa-solid fa-bolt text-warning me-2"></i>Quick Highlights
                </h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-2 pb-2 border-bottom last-border-0">
                        <a href="#" class="text-decoration-none text-dark fw-semibold d-block small">Inter-Dept Tournament kicks off next week!</a>
                        <small class="text-muted">Today, 10:00 AM</small>
                    </li>
                    <li class="mb-2 pb-2 border-bottom last-border-0">
                        <a href="#" class="text-decoration-none text-dark fw-semibold d-block small">ECE secures a thrilling last-ball victory over CSE.</a>
                        <small class="text-muted">Yesterday</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection