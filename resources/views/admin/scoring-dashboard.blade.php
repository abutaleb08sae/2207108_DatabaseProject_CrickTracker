@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    
    <!-- Clean Header Layout Context -->
    <div class="mb-4 border-bottom border-secondary pb-3">
        <h2 class="text-white fw-bold m-0" style="font-family: 'Segoe UI', system-ui, sans-serif;">
            Live Match Scoring Panel
        </h2>
    </div>

    <!-- Active Streams Visual Grid -->
    <div class="row g-4">
        @forelse($activeLiveMatches as $match)
            <div class="col-12 col-md-6 col-xxl-4">
                <!-- Clean Dark Surface Card with Red Accent Border -->
                <div class="card shadow-lg position-relative" style="background-color: #1a233a; border: 1px solid #2d3748; border-left: 5px solid #dc3545 !important; border-radius: 6px;">
                    <div class="card-body p-4">
                        
                        <!-- Top Metadata Context Row -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge rounded-pill bg-danger px-3 py-2 text-uppercase fw-bold tracking-wider d-flex align-items-center" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                                <span class="spinner-grow spinner-grow-sm text-white me-1" style="width: 8px; height: 8px;" role="status"></span> LIVE
                            </span>
                            <span class="text-info small fw-semibold">
                                <i class="bi bi-geo-alt-fill me-1"></i> {{ $match->venue_name ?? 'KUET Main Playground' }}
                            </span>
                        </div>

                        <!-- High-Contrast Team Titles -->
                        <h4 class="text-white fw-bold mb-3" style="font-family: 'Segoe UI', sans-serif;">
                            {{ $match->team1_short_name ?? $match->team1_name }} 
                            <span class="text-muted fw-normal fs-5 mx-1">vs</span> 
                            {{ $match->team2_short_name ?? $match->team2_name }}
                        </h4>

                        <!-- Time Context Details -->
                        <p class="text-muted small mb-4 d-flex align-items-center">
                            <i class="bi bi-clock me-2 text-secondary"></i> 
                            <span>Started: <strong class="text-light">{{ $match->start_time ?? '12:31 PM' }}</strong></span>
                        </p>

                        <!-- Explicitly Styled CTA Action Button -->
                        <a href="{{ route('admin.scoring.room', $match->match_id) }}" 
                           class="btn btn-outline-danger w-100 fw-bold py-2 custom-action-btn d-flex align-items-center justify-content-center">
                            <i class="bi bi-broadcast me-2"></i> Open Control Room
                        </a>

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 border border-secondary border-dashed rounded" style="background-color: #1a233a;">
                    <i class="bi bi-broadcast text-muted display-4 d-block mb-3"></i>
                    <h5 class="text-muted fw-normal">No live match streams currently running.</h5>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    /* Prevent custom border-dash syntax crashes */
    .border-dashed { border-style: dashed !important; }
    
    /* Clean interactive buttons styled over the dark background container */
    .custom-action-btn {
        color: #ff4d5a !important;
        border-color: #ff4d5a !important;
        background-color: transparent;
        transition: all 0.2s ease-in-out;
    }
    .custom-action-btn:hover {
        background-color: #ff4d5a !important;
        color: #ffffff !important;
    }
</style>
@endsection