
CREATE OR REPLACE PACKAGE match_engine_pkg AS
    PROCEDURE create_match_fixture(
        p_tourn NUMBER, 
        p_t1 NUMBER, 
        p_t2 NUMBER, 
        p_venue NUMBER, 
        p_date TIMESTAMP
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
END match_engine_pkg;
/

-- ====================================================================
-- 2. MATCH LOGIC INTERFACE PACKAGE BODY
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

    PROCEDURE register_ball_event(
        p_innings NUMBER, p_over NUMBER, p_ball NUMBER, p_bat NUMBER, p_non NUMBER, p_bowl NUMBER, 
        p_runs NUMBER, p_ext NUMBER, p_ext_type VARCHAR2, p_comm VARCHAR2, p_wkt_kind VARCHAR2 DEFAULT NULL, p_dismissed_id NUMBER DEFAULT NULL
    ) IS
        v_ball_id NUMBER;
        v_bat_runs NUMBER := p_runs;
        v_extra_runs NUMBER := p_ext;
    BEGIN
        -- Insert tracking node (Fixed: p_extra_type corrected to p_ext_type)
        INSERT INTO ball_by_ball (innings_id, over_number, ball_number, batsman_id, non_striker_id, bowler_id, runs_batsman, runs_extras, extra_type, commentary)
        VALUES (p_innings, p_over, p_ball, p_bat, p_non, p_bowl, v_bat_runs, v_extra_runs, p_ext_type, p_comm)
        RETURNING ball_id INTO v_ball_id;
        
        -- Process conditional wicket details
        IF p_wkt_kind IS NOT NULL AND p_dismissed_id IS NOT NULL THEN
            INSERT INTO wickets (ball_id, player_dismissed_id, kind)
            VALUES (v_ball_id, p_dismissed_id, p_wkt_kind);
        END IF;
    END register_ball_event;

END match_engine_pkg;
/

-- ====================================================================
-- 3. AUTOMATED REALTIME STATE UPDATER TRIGGER
-- ====================================================================
CREATE OR REPLACE TRIGGER trg_sync_ball_metrics
AFTER INSERT ON ball_by_ball
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
    
    -- 2. Check if a wicket fell on this specific ball instance
    SELECT COUNT(*) INTO v_is_wkt FROM wickets WHERE ball_id = :NEW.ball_id;
    
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
-- 4. AUDIT LOGGING SYSTEM ENGINE TRIGGER
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