<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminMatchController;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Global application framework context utilities
 */
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

function invoker_get_dashboard_data($controller) {
    $reflection = new \ReflectionClass(get_class($controller));
    $method = $reflection->getMethod('getDashboardData');
    $method->setAccessible(true);
    return $method->invoke($controller);
}

/*
|--------------------------------------------------------------------------
| Public Facing Route Rules
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Authentication Protocols
|--------------------------------------------------------------------------
*/

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Engine Workspace Operations
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    
    // Core Dashboard & Core Match Workpanes
    Route::get('/dashboard', [AdminMatchController::class, 'dashboard'])->name('dashboard');
    Route::get('/matches', [AdminMatchController::class, 'index'])->name('matches.index');
    Route::post('/matches/ball-by-ball', [AdminMatchController::class, 'storeBall'])->name('matches.storeBall');

    // 1. Live Scoring Panel Route Mapping
    Route::get('/match-live', function() {
        $adminController = new AdminMatchController();
        $data = invoker_get_dashboard_data($adminController);
        return view('admin.dashboard', array_merge($data, ['currentAdminSubView' => 'scoring']));
    })->name('match-live');

    // 2. Franchise Management (Teams) Workspace Routes
    Route::get('/teams', function() {
        $adminController = new AdminMatchController();
        $data = invoker_get_dashboard_data($adminController);
        $realTeams = DB::select("SELECT team_id, name, short_name FROM teams ORDER BY team_id ASC");
        return view('admin.dashboard', array_merge($data, [
            'currentAdminSubView' => 'teams',
            'realTeams' => $realTeams
        ]));
    })->name('teams');

    Route::post('/teams', function(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:10'
        ]);

        $name = $request->input('name');
        $shortName = strtoupper($request->input('short_name'));

        $exists = DB::select("SELECT COUNT(*) as count FROM teams WHERE LOWER(name) = LOWER(?) OR UPPER(short_name) = UPPER(?)", [$name, $shortName]);
        
        if (($exists[0]->count ?? $exists[0]->COUNT ?? 0) > 0) {
            return redirect()->back()->withErrors(['duplicate' => 'A franchise team with this name or short code already exists.']);
        }

        DB::insert("INSERT INTO teams (name, short_name) VALUES (?, ?)", [$name, $shortName]);

        return redirect()->route('admin.teams')->with('success', 'Team registration committed successfully.');
    })->name('teams.store');

    // 3. Athlete Enrollment (Players) Workspace Routes
    Route::get('/players', function() {
        $adminController = new AdminMatchController();
        $data = invoker_get_dashboard_data($adminController);
        $realTeams = DB::select("SELECT team_id, name FROM teams ORDER BY name ASC");
        return view('admin.dashboard', array_merge($data, [
            'currentAdminSubView' => 'players',
            'realTeams' => $realTeams
        ]));
    })->name('players');

    Route::post('/players', function(Request $request) {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'team_id' => 'required|integer'
        ]);

        $fullName = $request->input('first_name') . ' ' . $request->input('last_name');

        DB::insert("INSERT INTO players (name, team_id) VALUES (?, ?)", [
            $fullName,
            $request->input('team_id')
        ]);

        return redirect()->route('admin.players')->with('success', 'Athlete profile enrolled successfully.');
    })->name('players.store');

    // 4. Tournament Calendar (Fixtures) Workspace Routes
    Route::get('/fixtures', function() {
        $adminController = new AdminMatchController();
        $data = invoker_get_dashboard_data($adminController);
        $realTeams = DB::select("SELECT team_id, name FROM teams ORDER BY name ASC");
        
        // Fetch fixtures to safely pass through to the dashboard data loop
        $matches = DB::select("
            SELECT m.match_id, m.match_status, t1.name as team1_name, t2.name as team2_name 
            FROM matches m
            JOIN teams t1 ON m.team1_id = t1.team_id
            JOIN teams t2 ON m.team2_id = t2.team_id
            ORDER BY m.match_date DESC
        ");

        return view('admin.dashboard', array_merge($data, [
            'currentAdminSubView' => 'fixtures',
            'realTeams' => $realTeams,
            'matches' => $matches
        ]));
    })->name('fixtures');

    Route::post('/fixtures', function(Request $request) {
        $request->validate([
            'team1_id' => 'required|integer',
            'team2_id' => 'required|integer',
            'match_date' => 'required'
        ]);

        $team1 = (int)$request->input('team1_id');
        $team2 = (int)$request->input('team2_id');

        if ($team1 === $team2) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['team2_id' => 'A team cannot play against itself. Please select two different squads.']);
        }

        $formattedDate = date('Y-m-d H:i:s', strtotime($request->input('match_date')));

        DB::insert("
            INSERT INTO matches (team1_id, team2_id, match_date, match_status, tournament_id, venue_id) 
            VALUES (?, ?, TO_DATE(?, 'YYYY-MM-DD HH24:MI:SS'), 'Scheduled', 1, 1)
        ", [
            $team1,
            $team2,
            $formattedDate
        ]);

        return redirect()->route('admin.fixtures')->with('success', 'Tournament fixture slot row published successfully.');
    })->name('fixtures.store');

    // 5. News Feed Management System Workspace Routes
    Route::get('/news', function() {
        $adminController = new AdminMatchController();
        $data = invoker_get_dashboard_data($adminController);
        
        $news = DB::select("
            SELECT title, TO_CHAR(published_at, 'YYYY-MM-DD HH24:MI') as time 
            FROM news_feed 
            ORDER BY published_at DESC
        ");

        return view('admin.dashboard', array_merge($data, [
            'currentAdminSubView' => 'news',
            'news' => $news
        ]));
    })->name('news');

    Route::post('/news', function(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        DB::insert("
            INSERT INTO news_feed (title, content, published_at) 
            VALUES (?, ?, SYSDATE)
        ", [
            $request->input('title'),
            $request->input('content')
        ]);

        return redirect()->route('admin.news')->with('success', 'News bulletin broadcasted successfully.');
    })->name('news.store');
});