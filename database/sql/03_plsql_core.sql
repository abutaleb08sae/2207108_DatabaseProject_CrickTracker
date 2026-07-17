-- ====================================================================
-- 1. ANALYTICAL FUNCTION SUITE FOR CRICKET METRICS
-- ====================================================================

CREATE OR REPLACE FUNCTION fn_calculate_strike_rate(
    p_runs NUMBER, 
    p_balls NUMBER
) RETURN NUMBER IS
BEGIN
    IF p_balls = 0 THEN 
        RETURN 0.00; 
    END IF;
    RETURN ROUND((p_runs / p_balls) * 100, 2);
END;
/

CREATE OR REPLACE FUNCTION fn_calculate_economy(
    p_runs NUMBER, 
    p_balls NUMBER
) RETURN NUMBER IS
    v_overs NUMBER;
BEGIN
    IF p_balls = 0 THEN 
        RETURN 0.00; 
    END IF;
    -- Standardize fractional over calculations back to clean arithmetic division values
    RETURN ROUND(p_runs / ((TRUNC(p_balls / 6)) + (MOD(p_balls, 6) / 6)), 2);
END;
/

-- ====================================================================
-- 2. MATCH LOGIC INTERFACE PACKAGE SPECIFICATION
-- ====================================================================
CREATE OR REPLACE PACKAGE match_engine_pkg AS
    PROCEDURE create_match_fixture(
        p_tourn NUMBER, 
        p_t1 NUMBER, 
        p_t2 NUMBER, 
        p_venue NUMBER, 
        p_date TIMESTAMP
    );
    
    PROCEDURE initialize_innings(
        p_match_id NUMBER, 
        p_innings_num NUMBER, 
        p_batting_team_id NUMBER, 
        p_bowling_team_id NUMBER
    );
    
    PROCEDURE register_ball_event(
        p_innings NUMBER, 
        p_over NUMBER, 
        p_ball NUMBER, 
        p_bat NUMBER, 
        p_non NUMBER, 
        p_bowl NUMBER, 
        p_runs NUMBER, 
        p_ext NUMBER, 
        p_ext_type VARCHAR2, 
        p_comm VARCHAR2, 
        p_wkt_kind VARCHAR2 DEFAULT NULL, 
        p_dismissed_id NUMBER DEFAULT NULL
    );
    
    PROCEDURE finalize_match_state(
        p_match_id NUMBER, 
        p_winner_id NUMBER, 
        p_margin VARCHAR2
    );
END match_engine_pkg;
/

-- ====================================================================
-- 3. MATCH LOGIC INTERFACE PACKAGE BODY
-- ====================================================================
CREATE OR REPLACE PACKAGE BODY match_engine_pkg AS

    PROCEDURE create_match_fixture(
        p_tourn NUMBER, 
        p_t1 NUMBER, 
        p_t2 NUMBER, 
        p_venue NUMBER, 
        p_date TIMESTAMP
    ) IS
    BEGIN
        INSERT INTO matches (tournament_id, team1_id, team2_id, venue_id, match_date, match_status)
        VALUES (p_tourn, p_t1, p_t2, p_venue, p_date, 'Scheduled');
    END create_match_fixture;

    PROCEDURE initialize_innings(
        p_match_id NUMBER, 
        p_innings_num NUMBER, 
        p_batting_team_id NUMBER, 
        p_bowling_team_id NUMBER
    ) IS
    BEGIN
        INSERT INTO innings (match_id, innings_number, batting_team_id, bowling_team_id, total_runs, total_wickets, overs_bowled)
        VALUES (p_match_id, p_innings_num, p_batting_team_id, p_bowling_team_id, 0, 0, 0.0);
        
        UPDATE matches SET match_status = 'Live' WHERE match_id = p_match_id;
    END initialize_innings;

    PROCEDURE register_ball_event(
        p_innings NUMBER, p_over NUMBER, p_ball NUMBER, p_bat NUMBER, p_non NUMBER, p_bowl NUMBER, 
        p_runs NUMBER, p_ext NUMBER, p_ext_type VARCHAR2, p_comm VARCHAR2, p_wkt_kind VARCHAR2 DEFAULT NULL, p_dismissed_id NUMBER DEFAULT NULL
    ) IS
        v_ball_id NUMBER;
    BEGIN
        -- Insert tracking node directly into database history logs
        INSERT INTO ball_by_ball (innings_id, over_number, ball_number, batsman_id, non_striker_id, bowler_id, runs_batsman, runs_extras, extra_type, commentary)
        VALUES (p_innings, p_over, p_ball, p_bat, p_non, p_bowl, p_runs, p_ext, p_ext_type, p_comm)
        RETURNING ball_id INTO v_ball_id;
        
        -- Process conditional wicket details safely before trigger propagation
        IF p_wkt_kind IS NOT NULL AND p_dismissed_id IS NOT NULL THEN
            INSERT INTO wickets (ball_id, player_dismissed_id, kind)
            VALUES (v_ball_id, p_dismissed_id, p_wkt_kind);
        END IF;
    END register_ball_event;

    PROCEDURE finalize_match_state(
        p_match_id NUMBER, 
        p_winner_id NUMBER, 
        p_margin VARCHAR2
    ) IS
    BEGIN
        UPDATE matches 
        SET match_status = 'Completed',
            match_winner_id = p_winner_id,
            result_margin = p_margin
        WHERE match_id = p_match_id;
        
        -- Update the tournament rankings and standings cache
        UPDATE points_table 
        SET played = played + 1,
            points = CASE WHEN team_id = p_winner_id THEN points + 2 ELSE points END,
            won = CASE WHEN team_id = p_winner_id THEN won + 1 ELSE won END,
            lost = CASE WHEN team_id != p_winner_id THEN lost + 1 ELSE lost END
        WHERE tournament_id = (SELECT tournament_id FROM matches WHERE match_id = p_match_id);
    END finalize_match_state;

END match_engine_pkg;
/

-- ====================================================================
-- 4. AUTOMATED REALTIME STATE UPDATER TRIGGER (MUTATION-SAFE)
-- ====================================================================
CREATE OR REPLACE TRIGGER trg_sync_ball_metrics
BEFORE INSERT ON ball_by_ball
FOR EACH ROW
DECLARE
    v_is_wkt NUMBER := 0;
BEGIN
    -- 1. Roll up batting stats cache entries
    UPDATE player_stats_cache
    SET runs_scored = runs_scored + :NEW.runs_batsman,
        highest_score = CASE 
            WHEN (runs_scored + :NEW.runs_batsman) > highest_score THEN (runs_scored + :NEW.runs_batsman) 
            ELSE highest_score 
        END
    WHERE player_id = :NEW.batsman_id;
    
    -- 2. Evaluate if ball context implies a wicket without querying mutating table
    IF :NEW.commentary LIKE '%OUT%' OR :NEW.commentary LIKE '%WICKET%' THEN
        v_is_wkt := 1;
    ELSE
        v_is_wkt := 0;
    END IF;
    
    -- 3. Roll up bowling stats cache entries
    UPDATE player_stats_cache
    SET runs_conceded = runs_conceded + :NEW.runs_batsman + :NEW.runs_extras,
        wickets_taken = wickets_taken + v_is_wkt
    WHERE player_id = :NEW.bowler_id;
    
    -- 4. Sync cumulative parent Innings state
    UPDATE innings
    SET total_runs = total_runs + :NEW.runs_batsman + :NEW.runs_extras,
        total_wickets = total_wickets + v_is_wkt,
        overs_bowled = :NEW.over_number + (:NEW.ball_number / 6)
    WHERE innings_id = :NEW.innings_id;
END;
/

-- ====================================================================
-- 5. AUDIT LOGGING SYSTEM ENGINE TRIGGER
-- ====================================================================
CREATE OR REPLACE TRIGGER trg_audit_matches
AFTER UPDATE ON matches
FOR EACH ROW
BEGIN
    INSERT INTO audit_logs (table_name, operation, record_id, performed_by, old_values, new_values)
    VALUES ('matches', 'UPDATE', :NEW.match_id, 'ORACLE_ENGINE', 
            'Status: ' || :OLD.match_status || ', Winner: ' || NVL(TO_CHAR(:OLD.match_winner_id), 'NULL'),
            'Status: ' || :NEW.match_status || ', Winner: ' || NVL(TO_CHAR(:NEW.match_winner_id), 'NULL'));
END;
/