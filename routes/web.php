<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $matches = [
        (object)[
            'team1_id' => 'CSE',
            'team2_id' => 'EEE',
            'team1_score' => '189/6',
            'team2_score' => '196/5',
            'match_status' => 'live'
        ],
        (object)[
            'team1_id' => 'ME',
            'team2_id' => 'ECE',
            'team1_score' => '170/7',
            'team2_score' => '168/5',
            'match_status' => 'ECE  won by 2 runs'
        ],
        (object)[
            'team1_id' => 'BME',
            'team2_id' => 'EEE',
            'team1_score' => '144/6',
            'team2_score' => '140/9',
            'match_status' => 'BME won by 4 runs'
        ]
    ];

    return view('welcome', [
        'matches' => $matches,
        'totalMatches' => count($matches),
        'liveCount' => count($matches)
    ]);
});