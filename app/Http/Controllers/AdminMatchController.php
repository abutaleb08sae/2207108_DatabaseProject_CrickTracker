<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class AdminMatchController extends Controller
{
    /**
     * Aggregates the relational payload workspace variables required by the dashboard context.
     * Inherits structural records natively matched for downstream Oracle targets.
     */
    private function getDashboardData()
    {
        $activeMatchArray = DB::select("
            SELECT * FROM (
                SELECT * FROM vw_live_scorecard 
                WHERE match_status = 'Live' 
                ORDER BY match_id DESC
            ) WHERE ROWNUM = 1
        ");

        $activeMatch = !empty($activeMatchArray) ? $activeMatchArray[0] : (object)[
            'match_id' => 1, 'MATCH_ID' => 1,
            'current_innings' => 1, 'CURRENT_INNINGS' => 1,
            'team1_id' => 1, 'TEAM1_ID' => 1,
            'team2_id' => 2, 'TEAM2_ID' => 2,
            'team1_short_name' => 'TEAM 1', 'team2_short_name' => 'TEAM 2', 
            'team1_score' => 0, 'team1_wickets' => 0, 'team1_overs' => 0.0,
            'team1_name' => 'Team One', 'team2_name' => 'Team Two', 'match_status' => 'Scheduled'
        ];

        $team1Id = $activeMatch->TEAM1_ID ?? $activeMatch->team1_id ?? 1;
        $team2Id = $activeMatch->TEAM2_ID ?? $activeMatch->team2_id ?? 2;

        $allPlayers = DB::select('SELECT * FROM "PLAYERS"');

        $battingSquad = [];
        $bowlingSquad = [];

        foreach ($allPlayers as $player) {
            $playerArray = (array)$player;
            
            $pId = $playerArray['PLAYER_ID'] ?? $playerArray['player_id'] ?? null;
            $fName = $playerArray['FIRST_NAME'] ?? $playerArray['first_name'] ?? '';
            $lName = $playerArray['LAST_NAME'] ?? $playerArray['last_name'] ?? '';
            
            $tId = $playerArray['TEAM_ID'] ?? $playerArray['team_id'] ?? 
                   $playerArray['TEAM1_ID'] ?? $playerArray['team1_id'] ?? 
                   $playerArray['TEAM_CODE'] ?? $playerArray['team_code'] ?? null;

            $mappedPlayer = (object)[
                'player_id' => $pId,
                'first_name' => $fName,
                'last_name' => $lName,
                'player_name' => trim($fName . ' ' . $lName)
            ];

            if ($tId == $team1Id) {
                $battingSquad[] = $mappedPlayer;
            } elseif ($tId == $team2Id) {
                $bowlingSquad[] = $mappedPlayer;
            } else {
                $battingSquad[] = $mappedPlayer;
                $bowlingSquad[] = $mappedPlayer;
            }
        }

        $matches = DB::select("SELECT * FROM vw_live_scorecard");
        if (empty($matches)) {
            $matches = [$activeMatch];
        }

        // Global administration dataset properties requested uniformly across workspace routes
        $realTeams = DB::select("SELECT team_id, name, short_name FROM teams ORDER BY team_id ASC");
        
        $news = DB::select("
            SELECT title, TO_CHAR(published_at, 'YYYY-MM-DD HH24:MI') as time 
            FROM news_feed 
            ORDER BY published_at DESC
        ");

        return compact('activeMatch', 'battingSquad', 'bowlingSquad', 'matches', 'realTeams', 'news');
    }

    public function index()
    {
        $data = $this->getDashboardData();
        return view('admin.dashboard', $data);
    }

    public function dashboard()
    {
        $data = $this->getDashboardData();
        return view('admin.dashboard', $data);
    }

    public function storeBall(Request $request)
    {
        $request->validate([
            'match_id'    => 'required|numeric',
            'innings'     => 'required|numeric',
            'batsman_id'  => 'required|numeric',
            'bowler_id'   => 'required|numeric',
            'runs_scored' => 'required|numeric',
            'description' => 'required|string|max:400',
            'extra_type'  => 'nullable|string|max:20',
            'extra_runs'  => 'nullable|numeric',
            'wicket_type' => 'nullable|string|max:20',
        ]);

        try {
            $extraType   = !empty($request->extra_type) ? $request->extra_type : NULL;
            $extraRuns   = intval($request->extra_runs ?? 0);
            $wicketKind  = !empty($request->wicket_type) ? $request->wicket_type : NULL;
            $dismissedId = !empty($wicketKind) ? $request->batsman_id : NULL;

            DB::statement("
                BEGIN
                    match_engine_pkg.register_ball_event(
                        p_match_id     => :match_id,
                        p_innings      => :innings,
                        p_bat          => :bat,
                        p_bowl         => :bowl,
                        p_runs         => :runs,
                        p_ext          => :ext,
                        p_ext_type     => :ext_type,
                        p_comm         => :comm,
                        p_wkt_kind     => :wkt_kind,
                        p_dismissed_id => :dismissed_id
                    );
                END;
            ", [
                'match_id'     => $request->match_id,
                'innings'      => $request->innings,
                'bat'          => $request->batsman_id,
                'bowl'         => $request->bowler_id,
                'runs'         => $request->runs_scored,
                'ext'          => $extraRuns,
                'ext_type'     => $extraType,
                'comm'         => $request->description,
                'wkt_kind'     => $wicketKind,
                'dismissed_id' => $dismissedId
            ]);

            return back()->with('success', 'Ball entry successfully finalized in Oracle Database.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Oracle Database Error: ' . $e->getMessage()]);
        }
    }
}