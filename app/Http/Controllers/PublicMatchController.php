<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PublicMatchController extends Controller
{
    protected function getGlobalCricketContext()
    {
        try {
            $rawNews = DB::select("
                SELECT title, time 
                FROM (
                    SELECT title, TO_CHAR(published_at, 'YYYY-MM-DD HH24:MI') as time 
                    FROM news_feed ORDER BY published_at DESC
                ) WHERE ROWNUM <= 5
            ");
            $news = array_map(function($item) {
                return (object)array_change_key_case((array)$item, CASE_LOWER);
            }, $rawNews);
        } catch (\Exception $e) {
            $news = [];
        }

        // Sample sidebar news if database is empty
        if (empty($news)) {
            $news = [
                (object)['title' => 'KUET Inter-Department Cricket Tournament kicks off next week!', 'time' => '2026-07-18 10:00'],
                (object)['title' => 'ECE Department secures a thrilling victory against CSE.', 'time' => '2026-07-17 18:30'],
                (object)['title' => 'Civil Engineering announces final 15-man squad.', 'time' => '2026-07-15 14:00']
            ];
        }

        return $news;
    }

    public function showDashboard()
    {
        $news = $this->getGlobalCricketContext();

        // 1. Live Matches Setup
        $liveMatches = [];
        try {
            $rawLiveMatches = DB::select("
                SELECT match_id, match_status, team1_name, team2_name,
                       team1_score, team1_wickets, team1_overs,
                       team2_score, team2_wickets, team2_overs, venue_name
                FROM vw_live_scorecard WHERE UPPER(match_status) = 'LIVE'
            ");
            $liveMatches = array_map(function($match) {
                $m = array_change_key_case((array)$match, CASE_LOWER);
                return (object)[
                    'match_id'      => $m['match_id'] ?? 1,
                    'match_status'  => $m['match_status'] ?? 'Live',
                    'team1_name'    => $m['team1_name'] ?? 'Team 1',
                    'team2_name'    => $m['team2_name'] ?? 'Team 2',
                    'team1_score'   => $m['team1_score'] ?? null,
                    'team1_wickets' => $m['team1_wickets'] ?? 0,
                    'team1_overs'   => $m['team1_overs'] ?? '0.0',
                    'team2_score'   => $m['team2_score'] ?? null,
                    'team2_wickets' => $m['team2_wickets'] ?? 0,
                    'team2_overs'   => $m['team2_overs'] ?? '0.0',
                    'venue_name'    => $m['venue_name'] ?? 'KUET Ground'
                ];
            }, $rawLiveMatches);
        } catch (\Exception $e) { /* Fallback handled below */ }

        // Hardcoded Sample Live Match data if none exists in database
        if (empty($liveMatches)) {
            $liveMatches = [
                (object)[
                    'match_id'      => 999,
                    'match_status'  => 'LIVE',
                    'team1_name'    => 'CSE Strikers',
                    'team2_name'    => 'EEE Titans',
                    'team1_score'   => 145,
                    'team1_wickets' => 4,
                    'team1_overs'   => '16.2',
                    'team2_score'   => null,
                    'team2_wickets' => 0,
                    'team2_overs'   => '0.0',
                    'venue_name'    => 'KUET Main Playground'
                ]
            ];
        }

        // 2. Recent Dashboard List Setup
        $recentMatches = [];
        try {
            $rawRecent = DB::select("
                SELECT * FROM (
                    SELECT m.match_id, m.match_status, t1.name as team1_name, t2.name as team2_name 
                    FROM matches m 
                    LEFT JOIN teams t1 ON m.team1_id = t1.team_id 
                    LEFT JOIN teams t2 ON m.team2_id = t2.team_id 
                    WHERE UPPER(m.match_status) IN ('COMPLETED', 'ABANDONED') ORDER BY m.match_id DESC
                ) WHERE ROWNUM <= 3
            ");
            $recentMatches = array_map(function($match) {
                return (object)array_change_key_case((array)$match, CASE_LOWER);
            }, $rawRecent);
        } catch (\Exception $e) { /* Fallback handled below */ }

        // Hardcoded Sample Dashboard Recent Summary Data
        if (empty($recentMatches)) {
            $recentMatches = [
                (object)['match_id' => 991, 'match_status' => 'COMPLETED', 'team1_name' => 'ME Warriors', 'team2_name' => 'ECE Royals'],
                (object)['match_id' => 992, 'match_status' => 'COMPLETED', 'team1_name' => 'LE Kings', 'team2_name' => 'CE Giants']
            ];
        }

        // 3. Upcoming Dashboard List Setup
        $upcomingMatches = [];
        try {
            $rawUpcoming = DB::select("
                SELECT * FROM (
                    SELECT m.match_id, m.match_status, t1.name as team1_name, t2.name as team2_name, v.name as venue_name
                    FROM matches m 
                    LEFT JOIN teams t1 ON m.team1_id = t1.team_id 
                    LEFT JOIN teams t2 ON m.team2_id = t2.team_id 
                    LEFT JOIN venues v ON m.venue_id = v.venue_id
                    WHERE UPPER(m.match_status) = 'SCHEDULED' ORDER BY m.match_date ASC
                ) WHERE ROWNUM <= 3
            ");
            $upcomingMatches = array_map(function($match) {
                return (object)array_change_key_case((array)$match, CASE_LOWER);
            }, $rawUpcoming);
        } catch (\Exception $e) { /* Fallback handled below */ }

        if (empty($upcomingMatches)) {
            $upcomingMatches = [
                (object)['match_id' => 993, 'match_status' => 'SCHEDULED', 'team1_name' => 'CSE Strikers', 'team2_name' => 'URP Blasters', 'venue_name' => 'KUET Ground 2']
            ];
        }

        return view('public.dashboard', compact('news', 'liveMatches', 'recentMatches', 'upcomingMatches'));
    }

    public function showRecentMatches()
    {
        $news = $this->getGlobalCricketContext();
        $recentMatches = [];

        try {
            $rawRecent = DB::select("
                SELECT m.match_id, m.match_status, t1.name as team1_name, t2.name as team2_name,
                       t1.short_name as team1_short, t2.short_name as team2_short,
                       v.name as venue_name, TO_CHAR(m.match_date, 'DD Mon, YYYY') as formatted_date
                FROM matches m
                JOIN teams t1 ON m.team1_id = t1.team_id
                JOIN teams t2 ON m.team2_id = t2.team_id
                LEFT JOIN venues v ON m.venue_id = v.venue_id
                WHERE UPPER(m.match_status) IN ('COMPLETED', 'ABANDONED') ORDER BY m.match_id DESC
            ");
            $recentMatches = array_map(function($match) {
                return (object)array_change_key_case((array)$match, CASE_LOWER);
            }, $rawRecent);
        } catch (\Exception $e) { /* Fallback handled below */ }

        // Hardcoded Sample Data for the full /recent-matches view page
        if (empty($recentMatches)) {
            $recentMatches = [
                (object)[
                    'match_id' => 991, 
                    'match_status' => 'COMPLETED', 
                    'team1_name' => 'Mechanical Engineering Warriors', 'team2_name' => 'Electronics & Communication Royals',
                    'team1_short' => 'ME', 'team2_short' => 'ECE',
                    'venue_name' => 'KUET Main Field', 'formatted_date' => '16 Jul, 2026'
                ],
                (object)[
                    'match_id' => 992, 
                    'match_status' => 'COMPLETED', 
                    'team1_name' => 'Leather Engineering Kings', 'team2_name' => 'Civil Engineering Giants',
                    'team1_short' => 'LE', 'team2_short' => 'CE',
                    'venue_name' => 'KUET Campus Gym Ground', 'formatted_date' => '14 Jul, 2026'
                ]
            ];
        }

        return view('public.recent-matches', compact('news', 'recentMatches'));
    }
}