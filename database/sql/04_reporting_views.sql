
-- 1. LIVE MATCH SCORECARD STATUS VIEW

CREATE OR REPLACE VIEW vw_live_scorecard AS
SELECT m.match_id,
       t1.short_name AS team1,
       t2.short_name AS team2,
       m.match_status,
       NVL(i.innings_number, 1) AS innings_number,
       NVL(i.total_runs, 0) AS total_runs,
       NVL(i.total_wickets, 0) AS total_wickets,
       NVL(i.overs_bowled, 0.0) AS overs_bowled,
       v.name AS venue_name
FROM matches m
JOIN teams t1 ON m.team1_id = t1.team_id   
JOIN teams t2 ON m.team2_id = t2.team_id   
JOIN venues v ON m.venue_id = v.venue_id
LEFT JOIN innings i ON m.match_id = i.match_id;
/

-- 2. LEADERBOARD: PLAYER BATTING PERFORMANCE RECORDS

CREATE OR REPLACE VIEW vw_player_batting_records AS
SELECT p.player_id,
       p.first_name || ' ' || p.last_name AS full_name,
       p.batting_style,
       c.matches,
       c.runs_scored,
       c.highest_score,
       c.batting_avg,
       c.strike_rate,
       c.hundreds,
       c.fifties
FROM players p
JOIN player_stats_cache c ON p.player_id = c.player_id;
/


-- 3. LEADERBOARD: PLAYER BOWLING PERFORMANCE RECORDS

CREATE OR REPLACE VIEW vw_player_bowling_records AS
SELECT p.player_id,
       p.first_name || ' ' || p.last_name AS full_name,
       p.bowling_style,
       c.matches,
       c.wickets_taken,
       c.runs_conceded,
       c.economy_rate,
       c.five_w
FROM players p
JOIN player_stats_cache c ON p.player_id = c.player_id
WHERE p.player_role IN ('Bowler', 'Allrounder');
/