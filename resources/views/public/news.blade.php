@extends('layouts.master')

@section('title', 'CrickTracker - News Bulletins')

@section('content')
    <h4 class="fw-bold mb-4 text-dark"><i class="fa-solid fa-newspaper text-primary me-2"></i>Latest Tournament News</h4>
    <div class="row">
        <div class="col-lg-9 col-md-12">
            @forelse($allNews ?? [] as $item)
                <div class="card dashboard-card mb-4 shadow-sm border border-light-subtle">
                    <h5 class="fw-bold text-dark mb-2 dynamic-title-color">{{ $item->title }}</h5>
                    <p class="text-secondary mb-3" style="font-size: 0.95rem; line-height: 1.6;">{{ $item->content }}</p>
                    <div class="text-muted small d-flex align-items-center gap-1 border-top pt-2">
                        <i class="fa-regular fa-calendar me-1"></i> Published: {{ $item->formatted_time ?? 'Recently' }}
                    </div>
                </div>
            @empty
                <div class="card dashboard-card p-4 text-center text-muted shadow-sm">No news feed postings available at the moment.</div>
            @endforelse
        </div>
    </div>
@endsection