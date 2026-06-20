<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    
    $recentMatches = [
        (object)[
            'team1' => 'CSE', 'team2' => 'EEE', 
            'score1' => '189/6', 'score2' => '196/5', 
            'overs1' => '20.0', 'overs2' => '19.2',
            'result' => 'EEE won by 5 wickets', 'type' => 'T20'
        ],
        (object)[
            'team1' => 'ME', 'team2' => 'ECE', 
            'score1' => '170/7', 'score2' => '168/5', 
            'overs1' => '20.0', 'overs2' => '20.0',
            'result' => 'ME won by 2 runs', 'type' => 'T20'
        ]
    ];

    $upcomingMatches = [
        (object)['team1' => 'BME', 'team2' => 'CSE', 'date' => 'Tomorrow', 'time' => '02:00 PM', 'venue' => 'Main Ground'],
        (object)['team1' => 'ECE', 'team2' => 'EEE', 'date' => 'June 25', 'time' => '10:00 AM', 'venue' => 'Oval Field']
    ];

    $playerStats = [
        (object)['name' => 'Abir Hasan', 'team' => 'CSE', 'runs' => 342, 'wickets' => 12, 'avg' => '42.75', 'sr' => '145.2'],
        (object)['name' => 'Sakib Ahmed', 'team' => 'EEE', 'runs' => 289, 'wickets' => 8, 'avg' => '36.12', 'sr' => '138.5']
    ];

    $teams = [
        (object)['name' => 'CSE', 'played' => 4, 'won' => 3, 'lost' => 1, 'points' => 6],
        (object)['name' => 'EEE', 'played' => 4, 'won' => 3, 'lost' => 1, 'points' => 6],
        (object)['name' => 'ME', 'played' => 4, 'won' => 2, 'lost' => 2, 'points' => 4],
        (object)['name' => 'ECE', 'played' => 4, 'won' => 0, 'lost' => 4, 'points' => 0]
    ];

    $news = [
        (object)['title' => 'EEE clinches a thriller against CSE on the final ball', 'time' => '2 hours ago'],
        (object)['title' => 'Inter-Department Tournament schedule modifications announced', 'time' => '1 day ago']
    ];

    return view('welcome', compact('recentMatches', 'upcomingMatches', 'playerStats', 'teams', 'news'));
});