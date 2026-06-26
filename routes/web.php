<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

$data = [
    'recentMatches' => [
        (object)['team1' => 'CSE', 'team2' => 'EEE', 'score1' => '189/6', 'score2' => '196/5', 'overs1' => '20.0', 'overs2' => '19.2', 'result' => 'EEE won by 5 wickets', 'type' => 'Inter-Dept T20'],
        (object)['team1' => 'ME', 'team2' => 'ECE', 'score1' => '170/7', 'score2' => '168/5', 'overs1' => '20.0', 'overs2' => '20.0', 'result' => 'ME won by 2 runs', 'type' => 'Inter-Dept T20']
    ],
    'upcomingMatches' => [
        (object)['team1' => 'BME', 'team2' => 'CSE', 'date' => 'Tomorrow', 'time' => '02:00 PM', 'venue' => 'KUET Main Playground'],
        (object)['team1' => 'ECE', 'team2' => 'EEE', 'date' => 'June 25', 'time' => '10:00 AM', 'venue' => 'KUET Main Playground']
    ],
    'playerStats' => [
        (object)['name' => 'Abir Hasan', 'team' => 'CSE', 'runs' => 342, 'wickets' => 12, 'avg' => '42.75', 'sr' => '145.2'],
        (object)['name' => 'Sakib Ahmed', 'team' => 'EEE', 'runs' => 289, 'wickets' => 8, 'avg' => '36.12', 'sr' => '138.5']
    ],
    'news' => [
        (object)['title' => 'KUET Inter-Department Cricket Tournament 2026 kicks off in style', 'time' => '2 hours ago'],
        (object)['title' => 'Vice-Chancellor inaugurates the newly renovated main sports ground pitch', 'time' => '1 day ago']
    ]
];

Route::get('/', function () use ($data) {
    return view('welcome', array_merge($data, ['currentView' => 'dashboard']));
});

Route::get('/recent-matches', function () use ($data) {
    return view('welcome', array_merge($data, ['currentView' => 'recent']));
});

Route::get('/upcoming-matches', function () use ($data) {
    return view('welcome', array_merge($data, ['currentView' => 'upcoming']));
});

Route::get('/player-statistics', function () use ($data) {
    return view('welcome', array_merge($data, ['currentView' => 'stats']));
});

Route::get('/teams', function () use ($data) {
    $teams = DB::select('SELECT name, played, won, lost, points FROM teams ORDER BY points DESC, name ASC');
    return view('welcome', [
        'teams' => $teams,
        'currentView' => 'teams'
    ]);
});

Route::get('/news', function () use ($data) {
    return view('welcome', array_merge($data, ['currentView' => 'news']));
});