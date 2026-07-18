@extends('layouts.master')

@section('title', 'CrickTracker - Upcoming Schedules')

@section('content')
    <h4 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-calendar-days text-warning me-2"></i>Upcoming Matches Schedule</h4>
    <div class="card dashboard-card p-0 overflow-hidden shadow-sm border">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="p-3">Match Code</th>
                        <th class="p-3">Match Fixture</th>
                        <th class="p-3">Date</th>
                        <th class="p-3">Time</th>
                        <th class="p-3">Ground Venue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($upcomingMatches ?? [] as $match)
                        <tr>
                            <td class="p-3 text-muted fw-semibold">#{{ $match->match_id }}</td>
                            <td class="p-3 fw-bold text-primary fs-6">{{ $match->team1_name ?? ($match->team1 ?? 'Team A') }} vs {{ $match->team2_name ?? ($match->team2 ?? 'Team B') }}</td>
                            <td class="p-3"><span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1.5">{{ $match->date }}</span></td>
                            <td class="p-3 text-dark fw-semibold">{{ $match->time }}</td>
                            <td class="p-3 text-muted"><i class="fa-solid fa-location-dot text-danger me-1"></i>{{ $match->venue_name ?? ($match->venue ?? 'Unknown Venue') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-muted">No future fixtures scheduled in system.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection