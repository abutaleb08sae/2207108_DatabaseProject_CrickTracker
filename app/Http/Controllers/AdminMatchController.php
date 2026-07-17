<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class AdminMatchController extends Controller
{
    /**
     * Render the back-end match console overview with loaded squad selections.
     */
    public function index()
    {
        // 1. Fetch the first active live match from the view
        $activeMatchArray = DB::select("
            SELECT * FROM (
                SELECT * FROM vw_live_scorecard 
                WHERE match_status = 'Live' 
                ORDER BY match_id DESC
            ) WHERE ROWNUM = 1
        ");

        // Fallback context structure to prevent UI crashes if no match is currently flagged 'Live'
        $activeMatch = !empty($activeMatchArray) ? $activeMatchArray[0] : (object)[
            'match_id' => 1, 'current_innings' => 1, 'team1_id' => 1, 'team2_id' => 2,
            'team1_short_name' => 'TEAM 1', 'team2_short_name' => 'TEAM 2', 
            'team1_score' => 0, 'team1_wickets' => 0, 'team1_overs' => 0.0
        ];

        // 2. Fetch rosters dynamically for the batting and bowling teams using their IDs
        $battingSquad = DB::select("SELECT player_id, first_name, last_name FROM players WHERE team_id = ?", [$activeMatch->team1_id ?? 1]);
        $bowlingSquad = DB::select("SELECT player_id, first_name, last_name FROM players WHERE team_id = ?", [$activeMatch->team2_id ?? 2]);

        return view('admin.dashboard', compact('activeMatch', 'battingSquad', 'bowlingSquad'));
    }

    /**
     * Process ball events into Oracle from Admin interface form inputs.
     */
    public function storeBall(Request $request)
    {
        // Validate inputs matching the field signatures of your Blade template
        $request->validate([
            'match_id'    => 'required|numeric',
            'innings'     => 'required|numeric',
            'batsman_id'  => 'required|numeric',
            'bowler_id'   => 'required|numeric',
            'runs_scored' => 'required|numeric',
            'description' => 'required|string|max:400',
        ]);

        try {
            // Re-calculating automated overs metrics or pulling state if needed by the procedure.
            // Re-mapping variables safely to feed match_engine_pkg expectations
            $extraType   = !empty($request->extra_type) ? $request->extra_type : NULL;
            $extraRuns   = intval($request->extra_runs ?? 0);
            $wicketKind  = !empty($request->wicket_type) ? $request->wicket_type : NULL;
            
            // Deduce dismissed player context if wicket occurred
            $dismissedId = !empty($wicketKind) ? $request->batsman_id : NULL;

            // Execute the transactional procedure directly inside Oracle's processing layer
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