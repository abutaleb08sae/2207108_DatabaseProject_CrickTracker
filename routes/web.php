<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Context Helper: Fetches global sidebar content or marquee updates dynamically from Oracle 11g
function getGlobalCricketContext() {
    return [
        'news' => DB::select("
            SELECT title, time 
            FROM (
                SELECT title, 
                       TO_CHAR(published_at, 'YYYY-MM-DD HH24:MI') as time 
                FROM news_feed 
                ORDER BY published_at DESC
            ) 
            WHERE ROWNUM <= 5
        ")
    ];
}

// 1. DASHBOARD / HOME VIEW
Route::get('/', function () {
    $context = getGlobalCricketContext();
    
    // Live matches pull directly from our relational view
    $liveMatches = DB::select("SELECT * FROM vw_live_scorecard WHERE match_status = 'Live'");
    
    // Quick summaries for home marquee panels using ROWNUM subqueries for Oracle 11g
    $recentMatches = DB::select("
        SELECT * FROM (
            SELECT * FROM vw_live_scorecard 
            WHERE match_status = 'Completed'
            ORDER BY match_id DESC
        ) WHERE ROWNUM <= 3
    ");
    
    $upcomingMatches = DB::select("
        SELECT * FROM (
            SELECT * FROM vw_live_scorecard 
            WHERE match_status = 'Scheduled'
            ORDER BY match_id ASC
        ) WHERE ROWNUM <= 3
    ");

    return view('welcome', array_merge($context, [
        'currentView' => 'dashboard',
        'liveMatches' => $liveMatches,
        'recentMatches' => $recentMatches,
        'upcomingMatches' => $upcomingMatches
    ]));
});

// 2. RECENT MATCHES VIEW
Route::get('/recent-matches', function () {
    $context = getGlobalCricketContext();
    
    // Filter complete matches matching past chronological states
    $recentMatches = DB::select("
        SELECT * FROM vw_live_scorecard 
        WHERE match_status IN ('Completed', 'Abandoned') 
        ORDER BY match_id DESC
    ");

    return view('welcome', array_merge($context, [
        'currentView' => 'recent',
        'recentMatches' => $recentMatches
    ]));
});

// 3. UPCOMING MATCHES VIEW
Route::get('/upcoming-matches', function () {
    $context = getGlobalCricketContext();
    
    // Fixed: Standardized JOIN columns to team_id and replaced reserved word aliases 'date' and 'time'
    $upcomingMatches = DB::select("
        SELECT m.match_id,
               t1.short_name AS team1,
               t2.short_name AS team2,
               TO_CHAR(m.match_date, 'Month DD') as match_date_string,
               TO_CHAR(m.match_date, 'HH:MI AM') as match_time_string,
               v.name AS venue
        FROM matches m
        JOIN teams t1 ON m.team1_id = t1.team_id
        JOIN teams t2 ON m.team2_id = t2.team_id
        JOIN venues v ON m.venue_id = v.venue_id
        WHERE m.match_status IN ('Scheduled', 'Delayed')
        ORDER BY m.match_date ASC
    ");

    return view('welcome', array_merge($context, [
        'currentView' => 'upcoming',
        'upcomingMatches' => $upcomingMatches
    ]));
});

// 4. PLAYER STATISTICS LEADERBOARD VIEW
Route::get('/player-statistics', function () {
    $context = getGlobalCricketContext();
    
    // Pull from high-performance Oracle analytical views
    $battingStats = DB::select("SELECT * FROM vw_player_batting_records WHERE runs_scored > 0 ORDER BY runs_scored DESC");
    $bowlingStats = DB::select("SELECT * FROM vw_player_bowling_records WHERE wickets_taken > 0 ORDER BY wickets_taken DESC");
    
    return view('welcome', array_merge($context, [
        'currentView' => 'stats',
        'battingStats' => $battingStats,
        'bowlingStats' => $bowlingStats
    ]));
});

// 5. TEAMS / LEAGUE POINTS TABLE VIEW
Route::get('/teams', function () {
    $context = getGlobalCricketContext();
    
    // Fetch data mapped directly to the active tournament context standings table
    $teams = DB::select("
        SELECT t.name, pt.played, pt.won, pt.lost, pt.tied, pt.points, pt.net_run_rate 
        FROM points_table pt
        JOIN teams t ON pt.team_id = t.team_id
        ORDER BY pt.points DESC, pt.net_run_rate DESC
    ");
    
    return view('welcome', array_merge($context, [
        'currentView' => 'teams',
        'teams' => $teams
    ]));
});

// 6. EDITORIAL NEWS FEED VIEW
Route::get('/news', function () {
    $context = getGlobalCricketContext();
    
    // Fetch a complete list of news articles
    $allNews = DB::select("
        SELECT title, 
               content,
               TO_CHAR(published_at, 'Day, DD Mon YYYY, HH:MI AM') as formatted_time 
        FROM news_feed 
        ORDER BY published_at DESC
    ");
    
    return view('welcome', array_merge($context, [
        'currentView' => 'news',
        'allNews' => $allNews
    ]));
});