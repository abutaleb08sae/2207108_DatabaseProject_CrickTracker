@extends('layouts.master') <!-- Extends your main master layout framework -->

@section('title', 'KUET CrickTracker - Recent Match Log Entries')

@section('content')
<div class="container-fluid p-4">
    <!-- Section Heading Title Frame -->
    <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
        <div class="d-flex align-items-center">
            <i class="fas fa-history text-success fa-2x mr-3"></i>
            <div>
                <h3 class="mb-0 font-weight-bold text-dark">Recent Matches Results</h3>
                <p class="text-muted mb-0 small">Historical department scorecards processed by backend database procedures.</p>
            </div>
        </div>
    </div>

    <!-- Conditional Matrix Renderer Block -->
    @if(isset($recentMatches) && count($recentMatches) > 0)
        <div class="row">
            @foreach($recentMatches as $match)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 card-hover-effect">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <!-- Top Status Ribbon Row -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge badge-light border text-muted px-2 py-1 small">
                                        <i class="far fa-calendar-alt mr-1"></i>{{ $match->formatted_date }}
                                    </span>
                                    <span class="badge badge-success px-2 py-1 text-uppercase font-weight-bold" style="font-size: 0.75rem;">Completed</span>
                                </div>
                                
                                <!-- Department vs Department Visual Bracket -->
                                <div class="py-2">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <h6 class="font-weight-bold text-dark mb-0 text-truncate" style="max-width: 85%;">{{ $match->team1_name }}</h6>
                                        <span class="badge badge-secondary font-weight-bold">{{ $match->team1_short }}</span>
                                    </div>
                                    <div class="text-muted small my-1 pl-2 font-italic" style="font-size: 0.8rem;">vs</div>
                                    <div class="d-flex align-items-center justify-content-between mt-2">
                                        <h6 class="font-weight-bold text-dark mb-0 text-truncate" style="max-width: 85%;">{{ $match->team2_name }}</h6>
                                        <span class="badge badge-secondary font-weight-bold">{{ $match->team2_short }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Infrastructure Information Metadata -->
                            <div>
                                <hr class="my-3">
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="fas fa-map-marker-alt text-secondary mr-2"></i>
                                    <span class="text-truncate">{{ $match->venue_name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Fallback Empty State Layout -->
        <div class="card shadow-sm border-0 p-5 text-center bg-white rounded-lg">
            <div class="py-4">
                <i class="fas fa-folder-open fa-4x text-light mb-3"></i>
                <h5 class="text-dark font-weight-bold">No Historical Matches Recorded Yet</h5>
                <p class="text-muted small mb-0">The archival points engine contains no entries matching status definitions.</p>
            </div>
        </div>
    @endif
</div>

<style>
    /* Subtle hover presentation enhancement styling rules */
    .card-hover-effect {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card-hover-effect:hover {
        transform: translateY(-4px);
        box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.08)!important;
    }
</style>
@endsection