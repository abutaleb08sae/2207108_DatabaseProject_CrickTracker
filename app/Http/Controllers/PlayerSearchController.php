<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        // Search using Oracle's UPPER conversion for case-insensitive filtering
        $results = DB::select("
            SELECT * FROM view_detailed_player_profiles 
            WHERE UPPER(full_name) LIKE UPPER(?) OR UPPER(team_name) LIKE UPPER(?)
        ", ["%{$query}%", "%{$query}%"]);

        return view('welcome', [
            'currentView' => 'search_results',
            'results' => $results,
            'query' => $query,
            'news' => []
        ]);
    }

    public function profile($id)
    {
        $player = DB::select("SELECT * FROM view_detailed_player_profiles WHERE player_id = ?", [$id]);
        
        return view('welcome', [
            'currentView' => 'player_profile',
            'player' => !empty($player) ? $player[0] : null,
            'news' => []
        ]);
    }
}