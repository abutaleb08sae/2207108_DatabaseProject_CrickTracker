<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class AdminMatchController extends Controller
{
    /**
     * Admin Dashboard Navigation Landing Page.
     * Grabs separate collection contexts for live dashboards vs upcoming fixtures.
     */
    public function showLiveScoring()
    {
        // 1. Fetch only true active live matches
        $rawLive = DB::select("
            SELECT m.match_id, m.match_status, 
                   t1.name as team1_name, t2.name as team2_name,
                   t1.short_name as team1_short_name, t2.short_name as team2_short_name,
                   v.name as venue_name,
                   TO_CHAR(m.match_date, 'HH:MI AM') as start_time
            FROM matches m
            LEFT JOIN teams t1 ON m.team1_id = t1.team_id
            LEFT JOIN teams t2 ON m.team2_id = t2.team_id
            LEFT JOIN venues v ON m.venue_id = v.venue_id
            WHERE UPPER(m.match_status) = 'LIVE'
            ORDER BY m.match_id DESC
        ");

        $activeLiveMatches = array_map(function($match) {
            $m = array_change_key_case((array)$match, CASE_LOWER);
            return (object)$m;
        }, $rawLive);

        // 2. Fetch upcoming scheduled fixtures that can be initialized via the Toss UI
        $rawScheduled = DB::select("
            SELECT m.match_id, m.match_status,
                   t1.team_id as team1_id, t2.team_id as team2_id,
                   t1.name as team1_name, t2.name as team2_name,
                   v.name as venue_name,
                   TO_CHAR(m.match_date, 'Month DD, YYYY') as match_date
            FROM matches m
            LEFT JOIN teams t1 ON m.team1_id = t1.team_id
            LEFT JOIN teams t2 ON m.team2_id = t2.team_id
            LEFT JOIN venues v ON m.venue_id = v.venue_id
            WHERE UPPER(m.match_status) = 'SCHEDULED'
            ORDER BY m.match_date ASC
        ");

        $scheduledMatches = array_map(function($match) {
            $m = array_change_key_case((array)$match, CASE_LOWER);
            return (object)$m;
        }, $rawScheduled);

        return view('admin.scoring-dashboard', compact('activeLiveMatches', 'scheduledMatches'));
    }

    /**
     * Dedicated Scoring Control Room Environment for a specific match context.
     * Dynamically segments batting/bowling squads based on the recorded toss decision.
     */
    public function openScoringRoom($id)
    {
        $matchArray = DB::select("
            SELECT * FROM (
                SELECT m.*, 
                       t1.name as team1_name, t2.name as team2_name,
                       t1.short_name as team1_short_name, t2.short_name as team2_short_name
                FROM matches m
                LEFT JOIN teams t1 ON m.team1_id = t1.team_id
                LEFT JOIN teams t2 ON m.team2_id = t2.team_id
                WHERE m.match_id = ?
            ) WHERE ROWNUM = 1
        ", [(int)$id]);

        if (empty($matchArray)) {
            return redirect()->route('admin.scoring.dashboard')->withErrors(['error' => 'Selected match instance could not be resolved.']);
        }

        $activeMatch = (object)array_change_key_case((array)$matchArray[0], CASE_LOWER);

        $tossWinnerId = $activeMatch->toss_winner_id ?? null;
        $tossDecision = strtoupper($activeMatch->toss_decision ?? '');
        $team1Id      = $activeMatch->team1_id ?? null;
        $team2Id      = $activeMatch->team2_id ?? null;

        $battingTeamId  = $team1Id;
        $bowlingTeamId  = $team2Id;

        if (!empty($tossWinnerId)) {
            if ($tossWinnerId == $team1Id) {
                $battingTeamId = ($tossDecision === 'BAT') ? $team1Id : $team2Id;
                $bowlingTeamId = ($tossDecision === 'BAT') ? $team2Id : $team1Id;
            } else {
                $battingTeamId = ($tossDecision === 'BAT') ? $team2Id : $team1Id;
                $bowlingTeamId = ($tossDecision === 'BAT') ? $team1Id : $team2Id;
            }
        }

        // FIXED: Left Join with stat components to display live cumulative data dynamically 
        $rawBatters = DB::select("
            SELECT p.player_id, p.first_name, p.last_name, NVL(b.total_runs, 0) as total_runs
            FROM players p 
            LEFT JOIN batting_details b ON p.player_id = b.player_id
            WHERE p.team_id = ?
        ", [$battingTeamId]);

        $rawBowlers = DB::select("
            SELECT p.player_id, p.first_name, p.last_name, NVL(bw.wickets_taken, 0) as wickets_taken
            FROM players p 
            LEFT JOIN bowling_details bw ON p.player_id = bw.player_id
            WHERE p.team_id = ?
        ", [$bowlingTeamId]);
        
        $battingSquad = array_map(function($p) {
            $pArr = array_change_key_case((array)$p, CASE_LOWER);
            $pArr['player_name'] = trim(($pArr['first_name'] ?? '') . ' ' . ($pArr['last_name'] ?? '')) . ' (' . ($pArr['total_runs'] ?? 0) . ' Runs)';
            return (object)$pArr;
        }, $rawBatters);

        $bowlingSquad = array_map(function($p) {
            $pArr = array_change_key_case((array)$p, CASE_LOWER);
            $pArr['player_name'] = trim(($pArr['first_name'] ?? '') . ' ' . ($pArr['last_name'] ?? '')) . ' (' . ($pArr['wickets_taken'] ?? 0) . ' Wkts)';
            return (object)$pArr;
        }, $rawBowlers);

        return view('admin.scoring', compact('activeMatch', 'battingSquad', 'bowlingSquad'));
    }

    /**
     * Initializes match state, registers the toss criteria, and transitions status to 'Live'.
     */
    public function startLiveMatch(Request $request)
    {
        $request->validate([
            'match_id'        => 'required|numeric',
            'toss_winner_id'  => 'required|numeric',
            'toss_decision'   => 'required|string|max:20'
        ]);

        try {
            $matchId = (int)$request->input('match_id');
            $tossWinnerId = (int)$request->input('toss_winner_id');
            $tossDecision = strtoupper(trim($request->input('toss_decision')));

            DB::update("
                UPDATE matches 
                SET match_status = 'Live',
                    toss_winner_id = ?,
                    toss_decision = ?
                WHERE match_id = ?
            ", [$tossWinnerId, $tossDecision, $matchId]);

            return redirect()->route('admin.scoring.room', $matchId)
                             ->with('success', 'Toss verified! Live scoring console is now running.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to initialize match: ' . $e->getMessage()]);
        }
    }

    /**
     * Stores ball events via the database match engine package.
     */
    public function storeBall(Request $request)
    {
        $request->validate([
            'match_id'        => 'required|numeric',
            'innings'         => 'required|numeric',
            'batsman_id'      => 'required|numeric', 
            'non_striker_id'  => 'nullable|numeric',
            'bowler_id'       => 'required|numeric',
            'runs_scored'     => 'required|numeric',
            'description'     => 'required|string|max:400',
            'extra_type'      => 'nullable|string|max:20',
            'extra_runs'      => 'nullable|numeric',
            'wicket_type'     => 'nullable|string|max:20',
        ]);

        try {
            $batsmanId   = (int)$request->batsman_id;
            $extraType   = !empty($request->extra_type) ? $request->extra_type : NULL;
            $extraRuns   = intval($request->extra_runs ?? 0);
            $wicketKind  = !empty($request->wicket_type) ? $request->wicket_type : NULL;
            $dismissedId = !empty($wicketKind) ? $batsmanId : NULL;

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
                'bat'          => $batsmanId,
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

    /**
     * Finalizes an active match, instantly shifting it into the historical logs.
     */
    public function completeLiveMatch(Request $request)
    {
        $request->validate([
            'match_id' => 'required|numeric'
        ]);

        try {
            $matchId = (int)$request->input('match_id');
            DB::update("UPDATE matches SET match_status = 'Completed' WHERE match_id = ?", [$matchId]);
            return redirect()->route('admin.scoring.dashboard')->with('success', 'Match finalized successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to wrap up match: ' . $e->getMessage()]);
        }
    }

    public function index()
    {
        return redirect()->route('admin.scoring.dashboard');
    }

    private function getFallbackGlobalContext()
    {
        $rawTeams = DB::select("SELECT team_id, name, short_name FROM teams ORDER BY team_id ASC");
        
        $realTeams = array_map(function($team) {
            return (object)array_change_key_case((array)$team, CASE_LOWER);
        }, $rawTeams);

        return ['realTeams' => $realTeams, 'matches' => [], 'news' => [], 'battingSquad' => [], 'bowlingSquad' => []];
    }

    public function showTeams()
    {
        $data = $this->getFallbackGlobalContext();
        return view('admin.teams', $data);
    }

    public function showPlayers()
    {
        $data = $this->getFallbackGlobalContext();
        
        $rawP = DB::select("
            SELECT p.*, t.name as team_name 
            FROM players p
            LEFT JOIN teams t ON p.team_id = t.team_id
            ORDER BY p.player_id DESC
        ");

        $data['allPlayers'] = array_map(function($p) {
            $pArr = array_change_key_case((array)$p, CASE_LOWER);
            $pArr['player_name'] = trim(($pArr['first_name'] ?? '') . ' ' . ($pArr['last_name'] ?? ''));
            return (object)$pArr;
        }, $rawP);

        return view('admin.players', $data);
    }

    public function showFixtures()
    {
        $data = $this->getFallbackGlobalContext();
        return view('admin.fixtures', $data);
    }

    public function showNews()
    {
        $data = $this->getFallbackGlobalContext();
        return view('admin.news', $data);
    }

    public function destroyFixture($id)
    {
        try {
            DB::delete("DELETE FROM matches WHERE match_id = ?", [(int)$id]);
            return back()->with('success', 'Match fixture erased successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to erase fixture entry: ' . $e->getMessage()]);
        }
    }
}