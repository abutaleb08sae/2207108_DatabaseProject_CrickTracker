@extends('layouts.master')

@section('title', 'Recent Matches - CrickTracker')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center gap-3 mb-4">
        <h2 class="text-dark fw-bold mb-0">
            <i class="fa-solid fa-history text-primary me-2"></i>Recent Matches Results
        </h2>
    </div>
    
    <div class="dashboard-card border-0 bg-white shadow-sm p-4">
        <p class="text-muted border-bottom pb-3 mb-4">Historical department scorecards processed by backend database procedures.</p>

        <!-- 3 to 4 Match Clean Grid Layout with Short Names and Runs Displayed -->
        <div class="row row-cols-1 row-cols-md-2 g-4">
            
            <!-- MATCH CARD 1 -->
            <div class="col">
                <div class="card h-100 border-0 shadow-sm bg-light-subtle rounded-3 border-start border-primary border-3">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-secondary px-2.5 py-1.5 rounded text-white font-monospace">MATCH #991</span>
                            <span class="text-muted small fw-bold"><i class="fa-solid fa-calendar me-1"></i> 16 Jul, 2026</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h4 class="fw-bold text-dark mb-0">ME</h4>
                            <span class="fs-5 fw-bold text-primary">162 / 5 <small class="text-muted text-xs">(20.0)</small></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-bold text-muted mb-0">ECE</h4>
                            <span class="fs-5 fw-bold text-muted">158 / 8 <small class="text-muted text-xs">(20.0)</small></span>
                        </div>
                        <div class="alert alert-success py-2 px-3 mb-3 small fw-bold border-0 rounded-3">
                            <i class="fa-solid fa-trophy text-warning me-2"></i>ME won by 5 wickets
                        </div>
                        <div class="text-muted small"><i class="fa-solid fa-location-dot me-1"></i> KUET Main Field</div>
                    </div>
                </div>
            </div>

            <!-- MATCH CARD 2 -->
            <div class="col">
                <div class="card h-100 border-0 shadow-sm bg-light-subtle rounded-3 border-start border-primary border-3">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-secondary px-2.5 py-1.5 rounded text-white font-monospace">MATCH #992</span>
                            <span class="text-muted small fw-bold"><i class="fa-solid fa-calendar me-1"></i> 14 Jul, 2026</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h4 class="fw-bold text-dark mb-0">LE</h4>
                            <span class="fs-5 fw-bold text-primary">184 / 3 <small class="text-muted text-xs">(20.0)</small></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-bold text-muted mb-0">CE</h4>
                            <span class="fs-5 fw-bold text-muted">160 / 10 <small class="text-muted text-xs">(18.4)</small></span>
                        </div>
                        <div class="alert alert-success py-2 px-3 mb-3 small fw-bold border-0 rounded-3">
                            <i class="fa-solid fa-trophy text-warning me-2"></i>LE won by 24 runs
                        </div>
                        <div class="text-muted small"><i class="fa-solid fa-location-dot me-1"></i> KUET Gym Ground</div>
                    </div>
                </div>
            </div>

            <!-- MATCH CARD 3 -->
            <div class="col">
                <div class="card h-100 border-0 shadow-sm bg-light-subtle rounded-3 border-start border-primary border-3">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-secondary px-2.5 py-1.5 rounded text-white font-monospace">MATCH #993</span>
                            <span class="text-muted small fw-bold"><i class="fa-solid fa-calendar me-1"></i> 12 Jul, 2026</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h4 class="fw-bold text-dark mb-0">CSE</h4>
                            <span class="fs-5 fw-bold text-primary">195 / 2 <small class="text-muted text-xs">(20.0)</small></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-bold text-muted mb-0">URP</h4>
                            <span class="fs-5 fw-bold text-muted">132 / 9 <small class="text-muted text-xs">(20.0)</small></span>
                        </div>
                        <div class="alert alert-success py-2 px-3 mb-3 small fw-bold border-0 rounded-3">
                            <i class="fa-solid fa-trophy text-warning me-2"></i>CSE won by 63 runs
                        </div>
                        <div class="text-muted small"><i class="fa-solid fa-location-dot me-1"></i> KUET Main Field</div>
                    </div>
                </div>
            </div>

            <!-- MATCH CARD 4 -->
            <div class="col">
                <div class="card h-100 border-0 shadow-sm bg-light-subtle rounded-3 border-start border-primary border-3">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-secondary px-2.5 py-1.5 rounded text-white font-monospace">MATCH #994</span>
                            <span class="text-muted small fw-bold"><i class="fa-solid fa-calendar me-1"></i> 10 Jul, 2026</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h4 class="fw-bold text-muted mb-0">BEC</h4>
                            <span class="fs-5 fw-bold text-muted">120 / 10 <small class="text-muted text-xs">(17.1)</small></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="fw-bold text-dark mb-0">TE</h4>
                            <span class="fs-5 fw-bold text-primary">124 / 4 <small class="text-muted text-xs">(15.3)</small></span>
                        </div>
                        <div class="alert alert-success py-2 px-3 mb-3 small fw-bold border-0 rounded-3">
                            <i class="fa-solid fa-trophy text-warning me-2"></i>TE won by 6 wickets
                        </div>
                        <div class="text-muted small"><i class="fa-solid fa-location-dot me-1"></i> KUET Gym Ground</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection