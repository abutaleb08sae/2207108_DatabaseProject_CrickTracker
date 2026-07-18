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
        // 1. Fetch only true active live matches (Oracle Uppercase Standard)
        $rawLive = DB::select("
            SELECT M.MATCH_ID, M.MATCH_STATUS, 
                   T1.NAME AS TEAM1_NAME, T2.NAME AS TEAM2_NAME,
                   T1.SHORT_NAME AS TEAM1_SHORT_NAME, T2.SHORT_NAME AS TEAM2_SHORT_NAME,
                   V.NAME AS VENUE_NAME,
                   TO_CHAR(M.MATCH_DATE, 'HH:MI AM') AS START_TIME
            FROM MATCHES M
            LEFT JOIN TEAMS T1 ON M.TEAM1_ID = T1.TEAM_ID
            LEFT JOIN TEAMS T2 ON M.TEAM2_ID = T2.TEAM_ID
            LEFT JOIN VENUES V ON M.VENUE_ID = V.VENUE_ID
            WHERE UPPER(M.MATCH_STATUS) = 'LIVE'
            ORDER BY M.MATCH_ID DESC
        ");

        $activeLiveMatches = array_map(function($match) {
            $m = array_change_key_case((array)$match, CASE_LOWER);
            return (object)$m;
        }, $rawLive);

        // 2. Fetch upcoming scheduled fixtures that can be initialized via the Toss UI
        $rawScheduled = DB::select("
            SELECT M.MATCH_ID, M.MATCH_STATUS,
                   T1.TEAM_ID AS TEAM1_ID, T2.TEAM_ID AS TEAM2_ID,
                   T1.NAME AS TEAM1_NAME, T2.NAME AS TEAM2_NAME,
                   V.NAME AS VENUE_NAME,
                   TO_CHAR(M.MATCH_DATE, 'Month DD, YYYY') AS MATCH_DATE
            FROM MATCHES M
            LEFT JOIN TEAMS T1 ON M.TEAM1_ID = T1.TEAM_ID
            LEFT JOIN TEAMS T2 ON M.TEAM2_ID = T2.TEAM_ID
            LEFT JOIN VENUES V ON M.VENUE_ID = V.VENUE_ID
            WHERE UPPER(M.MATCH_STATUS) = 'SCHEDULED'
            ORDER BY M.MATCH_DATE ASC
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
                SELECT M.*, 
                       T1.NAME AS TEAM1_NAME, T2.NAME AS TEAM2_NAME,
                       T1.SHORT_NAME AS TEAM1_SHORT_NAME, T2.SHORT_NAME AS TEAM2_SHORT_NAME
                FROM MATCHES M
                LEFT JOIN TEAMS T1 ON M.TEAM1_ID = T1.TEAM_ID
                LEFT JOIN TEAMS T2 ON M.TEAM2_ID = T2.TEAM_ID
                WHERE M.MATCH_ID = ?
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

        // --- SAFE FALLBACK CALCULATIONS FOR BATTERS ---
        try {
            // Strategy A: Try to find an aggregated metrics caching layer
            $rawBatters = DB::select("
                SELECT P.PLAYER_ID, P.FIRST_NAME, P.LAST_NAME, 
                       NVL((SELECT SUM(RUNS_SCORED) FROM PLAYER_STATISTICS WHERE PLAYER_ID = P.PLAYER_ID AND MATCH_ID = ?), 0) AS TOTAL_RUNS
                FROM PLAYERS P WHERE P.TEAM_ID = ?
            ", [(int)$id, $battingTeamId]);
        } catch (Exception $e) {
            try {
                // Strategy B: Calculate using shorter common naming standards on Ball-by-Ball table
                $rawBatters = DB::select("
                    SELECT P.PLAYER_ID, P.FIRST_NAME, P.LAST_NAME, 
                           NVL((SELECT SUM(B.RUNS) FROM BALL_BY_BALL B JOIN INNINGS I ON B.INNINGS_ID = I.INNINGS_ID WHERE B.BATSMAN_ID = P.PLAYER_ID AND I.MATCH_ID = ?), 0) AS TOTAL_RUNS
                    FROM PLAYERS P WHERE P.TEAM_ID = ?
                ", [(int)$id, $battingTeamId]);
            } catch (Exception $e2) {
                // Strategy C: Absolute safeguard fallback to guarantee page execution
                $rawBatters = DB::select("
                    SELECT P.PLAYER_ID, P.FIRST_NAME, P.LAST_NAME, 0 AS TOTAL_RUNS 
                    FROM PLAYERS P WHERE P.TEAM_ID = ?
                ", [$battingTeamId]);
            }
        }

        // --- SAFE FALLBACK CALCULATIONS FOR BOWLERS ---
        try {
            // Strategy A: Try using aggregate statistical tables
            $rawBowlers = DB::select("
                SELECT P.PLAYER_ID, P.FIRST_NAME, P.LAST_NAME, 
                       NVL((SELECT SUM(WICKETS_TAKEN) FROM PLAYER_STATISTICS WHERE PLAYER_ID = P.PLAYER_ID AND MATCH_ID = ?), 0) AS WICKETS_TAKEN
                FROM PLAYERS P WHERE P.TEAM_ID = ?
            ", [(int)$id, $bowlingTeamId]);
        } catch (Exception $e) {
            try {
                // Strategy B: Calculate dynamically via structural foreign key tables
                $rawBowlers = DB::select("
                    SELECT P.PLAYER_ID, P.FIRST_NAME, P.LAST_NAME, 
                           NVL((SELECT COUNT(*) FROM WICKETS W JOIN INNINGS I ON W.INNINGS_ID = I.INNINGS_ID WHERE W.BOWLER_ID = P.PLAYER_ID AND I.MATCH_ID = ?), 0) AS WICKETS_TAKEN
                    FROM PLAYERS P WHERE P.TEAM_ID = ?
                ", [(int)$id, $bowlingTeamId]);
            } catch (Exception $e2) {
                // Strategy C: Absolute safeguard fallback to guarantee page execution
                $rawBowlers = DB::select("
                    SELECT P.PLAYER_ID, P.FIRST_NAME, P.LAST_NAME, 0 AS WICKETS_TAKEN 
                    FROM PLAYERS P WHERE P.TEAM_ID = ?
                ", [$bowlingTeamId]);
            }
        }
        
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
                UPDATE MATCHES 
                SET MATCH_STATUS = 'Live',
                    TOSS_WINNER_ID = ?,
                    TOSS_DECISION = ?
                WHERE MATCH_ID = ?
            ", [$tossWinnerId, $tossDecision, $matchId]);

            return redirect()->route('admin.scoring.room', $matchId)
                             ->with('success', 'Toss verified! Live scoring console is now running.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to initialize match: ' . $e->getMessage()]);
        }
    }

    /**
     * Stores ball events via the database match engine package using named parameter binding.
     */
    public function storeBall(Request $request)
    {
        $request->validate([
            'match_id'   => 'required|numeric',
            'batsman_id' => 'required|numeric', 
            'bowler_id'  => 'required|numeric'
        ]);

        try {
            $matchId     = (int)$request->input('match_id');
            $innings     = (int)$request->input('innings', 1);
            $batsmanId   = (int)$request->input('batsman_id');
            $bowlerId    = (int)$request->input('bowler_id');
            
            $runs = 0;
            if ($request->has('runs_scored')) {
                $runs = (int)$request->input('runs_scored');
            } elseif ($request->has('runs')) {
                $runs = (int)$request->input('runs');
            }

            $description = $request->input('description') ?? 'Delivery recorded.';
            
            // Explicitly force safe types and values for Oracle custom PL/SQL types
            $extraType = $request->input('extra_type');
            $extraType = empty($extraType) ? 'NONE' : (string)$extraType;
            
            $extraRuns = (int)$request->input('extra_runs', 0);
            
            $wicketKind = $request->input('wicket_type');
            $wicketKind = empty($wicketKind) ? 'NOT OUT' : (string)$wicketKind;
            
            $dismissedId = 0;
            if ($wicketKind !== 'NOT OUT') {
                $dismissedId = $request->filled('dismissed_id') ? (int)$request->input('dismissed_id') : $batsmanId;
            }

        //pl/sql
            DB::statement("
                BEGIN
                    MATCH_ENGINE_PKG.REGISTER_BALL_EVENT(
                        P_MATCH_ID     => :match_id,
                        P_INNINGS      => :innings,
                        P_BAT          => :bat,
                        P_BOWL         => :bowl,
                        P_RUNS         => :runs,
                        P_EXT          => :ext,
                        P_EXT_TYPE     => :ext_type,
                        P_COMM         => :comm,
                        P_WKT_KIND     => :wkt_kind,
                        P_DISMISSED_ID => :dismissed_id
                    );
                END;
            ", [
                'match_id'     => $matchId,
                'innings'      => $innings,
                'bat'          => $batsmanId,
                'bowl'         => $bowlerId,
                'runs'         => $runs,
                'ext'          => $extraRuns,
                'ext_type'     => $extraType,
                'comm'         => (string)$description,
                'wkt_kind'     => $wicketKind,
                'dismissed_id' => $dismissedId
            ]);

            return back()->with('success', 'Ball entry successfully finalized in Oracle Database.');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Oracle Database Error: ' . $e->getMessage()]);
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
            DB::update("UPDATE MATCHES SET MATCH_STATUS = 'Completed' WHERE MATCH_ID = ?", [$matchId]);
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
        $rawTeams = DB::select("SELECT TEAM_ID, NAME, SHORT_NAME FROM TEAMS ORDER BY TEAM_ID ASC");
        
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
            SELECT P.*, T.NAME AS TEAM_NAME 
            FROM PLAYERS P
            LEFT JOIN TEAMS T ON P.TEAM_ID = T.TEAM_ID
            ORDER BY P.PLAYER_ID DESC
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
            DB::delete("DELETE FROM MATCHES WHERE MATCH_ID = ?", [(int)$id]);
            return back()->with('success', 'Match fixture erased successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Failed to erase fixture entry: ' . $e->getMessage()]);
        }
    }
}