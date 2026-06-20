<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CricketHomeController extends Controller
{
    public function index()
    {
        
        $matches = DB::table('cricket_matches')
            ->orderBy('id', 'desc')
            ->get();


        $totalMatches = DB::table('cricket_matches')->count();
        $liveCount = DB::table('cricket_matches')->where('match_status', 'live')->count();

        return view('welcome', compact('matches', 'totalMatches', 'liveCount'));
    }
}