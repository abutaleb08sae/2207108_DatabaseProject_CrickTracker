<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminMatchController;
use App\Models\User;

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

Route::get('/', function () {
    $context = getGlobalCricketContext();
    $liveMatches = DB::select("SELECT * FROM vw_live_scorecard WHERE match_status = 'Live'");
    $recentMatches = DB::select("SELECT * FROM (SELECT * FROM vw_live_scorecard WHERE match_status = 'Completed' ORDER BY match_id DESC) WHERE ROWNUM <= 3");
    $upcomingMatches = DB::select("SELECT * FROM (SELECT * FROM vw_live_scorecard WHERE match_status = 'Scheduled' ORDER BY match_id ASC) WHERE ROWNUM <= 3");

    return view('welcome', array_merge($context, [
        'currentView' => 'dashboard',
        'liveMatches' => $liveMatches,
        'recentMatches' => $recentMatches,
        'upcomingMatches' => $upcomingMatches
    ]));
});

Route::get('/recent-matches', function () {
    $context = getGlobalCricketContext();
    $recentMatches = DB::select("SELECT * FROM vw_live_scorecard WHERE match_status IN ('Completed', 'Abandoned') ORDER BY match_id DESC");

    return view('welcome', array_merge($context, [
        'currentView' => 'recent',
        'recentMatches' => $recentMatches
    ]));
});

Route::get('/upcoming-matches', function () {
    $context = getGlobalCricketContext();
    $upcomingMatches = DB::select("
        SELECT m.match_id,
               t1.name AS team1_name,
               t2.name AS team2_name,
               TO_CHAR(m.match_date, 'Month DD, YYYY') as \"date\",
               TO_CHAR(m.match_date, 'HH:MI AM') as \"time\",
               v.name AS venue_name
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

Route::get('/player-statistics', function () {
    $context = getGlobalCricketContext();
    $battingStats = DB::select("SELECT * FROM vw_player_batting_records WHERE runs_scored > 0 ORDER BY runs_scored DESC");
    $bowlingStats = DB::select("SELECT * FROM vw_player_bowling_records WHERE wickets_taken > 0 ORDER BY wickets_taken DESC");
    
    return view('welcome', array_merge($context, [
        'currentView' => 'stats',
        'battingStats' => $battingStats,
        'bowlingStats' => $bowlingStats
    ]));
});

Route::get('/teams', function () {
    $context = getGlobalCricketContext();
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

Route::get('/news', function () {
    $context = getGlobalCricketContext();
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

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin/dashboard', [AdminMatchController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/matches', [AdminMatchController::class, 'index'])->name('admin.matches.index');
Route::post('/admin/matches/ball-by-ball', [AdminMatchController::class, 'storeBall'])->name('admin.matches.storeBall');

Route::get('/admin/teams', function() {
    $adminController = new AdminMatchController();
    $data = invoker_get_dashboard_data($adminController);
    return view('admin.dashboard', array_merge($data, ['currentAdminSubView' => 'teams']));
})->name('admin.teams');

Route::get('/admin/players', function() {
    $adminController = new AdminMatchController();
    $data = invoker_get_dashboard_data($adminController);
    return view('admin.dashboard', array_merge($data, ['currentAdminSubView' => 'players']));
})->name('admin.players');

Route::get('/admin/fixtures', function() {
    $adminController = new AdminMatchController();
    $data = invoker_get_dashboard_data($adminController);
    return view('admin.dashboard', array_merge($data, ['currentAdminSubView' => 'fixtures']));
})->name('admin.fixtures');

Route::get('/admin/match-live', function() {
    $adminController = new AdminMatchController();
    $data = invoker_get_dashboard_data($adminController);
    return view('admin.dashboard', array_merge($data, ['currentAdminSubView' => 'scoring']));
})->name('admin.match-live');

function invoker_get_dashboard_data($controller) {
    $reflection = new \ReflectionClass(get_class($controller));
    $method = $reflection->getMethod('getDashboardData');
    $method->setAccessible(true);
    return $method->invoke($controller);
}