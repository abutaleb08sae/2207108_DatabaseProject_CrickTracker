-- Disable special character parsing so strings like admin@kuet.ac.bd do not break SQL*Plus
SET DEFINE OFF;

-- ====================================================================
-- 1. SEEDING CORE LOOKUP DATA
-- ====================================================================

-- Users
INSERT INTO users (username, email, password_hash, role) 
VALUES ('admin_kuet', 'admin@kuet.ac.bd', 'hash_secure_2026', 'SuperAdmin');

-- Tournaments
INSERT INTO tournaments (name, edition, start_date, end_date, format) 
VALUES ('KUET Inter-Dept League', '2026', TO_DATE('2026-06-01','YYYY-MM-DD'), TO_DATE('2026-07-30','YYYY-MM-DD'), 'T20');

-- Teams
INSERT INTO teams (name, short_name) VALUES ('Computer Science & Engineering', 'CSE');
INSERT INTO teams (name, short_name) VALUES ('Electrical & Electronic Engineering', 'EEE');
INSERT INTO teams (name, short_name) VALUES ('Mechanical Engineering', 'ME');
INSERT INTO teams (name, short_name) VALUES ('Civil Engineering', 'CE');
INSERT INTO teams (name, short_name) VALUES ('Electronics & Communication Engineering', 'ECE');

-- Venues
INSERT INTO venues (name, city, capacity, floodlights) 
VALUES ('KUET Main Sports Ground', 'Khulna', 5000, 0);


-- ====================================================================
-- 2. SEEDING PLAYER PROFILES
-- ====================================================================

-- Seeding 10 Batting-focused Profiles
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, player_role) VALUES ('Abir', 'Hasan', TO_DATE('2003-04-12','YYYY-MM-DD'), 'Right-hand bat', 'Batsman');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, player_role) VALUES ('Sakib', 'Ahmed', TO_DATE('2002-11-20','YYYY-MM-DD'), 'Left-hand bat', 'Batsman');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, player_role) VALUES ('Tanvir', 'Rahman', TO_DATE('2004-01-15','YYYY-MM-DD'), 'Right-hand bat', 'Batsman');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, player_role) VALUES ('Naimur', 'Rahman', TO_DATE('2003-08-05','YYYY-MM-DD'), 'Right-hand bat', 'Batsman');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, player_role) VALUES ('Mehedi', 'Hasan', TO_DATE('2002-06-25','YYYY-MM-DD'), 'Left-hand bat', 'Batsman');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, player_role) VALUES ('Sifatur', 'Rahman', TO_DATE('2003-09-18','YYYY-MM-DD'), 'Right-hand bat', 'Batsman');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, player_role) VALUES ('Rifat', 'Hossain', TO_DATE('2002-02-11','YYYY-MM-DD'), 'Right-hand bat', 'Batsman');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, player_role) VALUES ('Tahmid', 'Anjum', TO_DATE('2004-05-30','YYYY-MM-DD'), 'Left-hand bat', 'Batsman');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, player_role) VALUES ('Zahidul', 'Islam', TO_DATE('2003-12-04','YYYY-MM-DD'), 'Right-hand bat', 'Batsman');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, player_role) VALUES ('Imtiaz', 'Ahmed', TO_DATE('2002-07-14','YYYY-MM-DD'), 'Right-hand bat', 'Batsman');

-- Seeding 10 Bowling-focused Profiles
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, bowling_style, player_role) VALUES ('Nahid', 'Chowdhury', TO_DATE('2003-01-01','YYYY-MM-DD'), 'Right-hand bat', 'Right-arm fast', 'Bowler');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, bowling_style, player_role) VALUES ('Asif', 'Raihan', TO_DATE('2002-05-19','YYYY-MM-DD'), 'Right-hand bat', 'Left-arm orthodox', 'Bowler');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, bowling_style, player_role) VALUES ('Kamrul', 'Islam', TO_DATE('2004-03-22','YYYY-MM-DD'), 'Right-hand bat', 'Right-arm offbreak', 'Bowler');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, bowling_style, player_role) VALUES ('Mahfuzur', 'Rahman', TO_DATE('2003-07-07','YYYY-MM-DD'), 'Left-hand bat', 'Right-arm fast', 'Bowler');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, bowling_style, player_role) VALUES ('Sanzid', 'Rahman', TO_DATE('2002-10-10','YYYY-MM-DD'), 'Right-hand bat', 'Right-arm medium', 'Bowler');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, bowling_style, player_role) VALUES ('Farhan', 'Tanvir', TO_DATE('2004-02-28','YYYY-MM-DD'), 'Right-hand bat', 'Left-arm fast', 'Bowler');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, bowling_style, player_role) VALUES ('Rayhan', 'Ahmed', TO_DATE('2003-11-11','YYYY-MM-DD'), 'Right-hand bat', 'Legbreak', 'Bowler');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, bowling_style, player_role) VALUES ('Niaz', 'Morshed', TO_DATE('2002-08-24','YYYY-MM-DD'), 'Right-hand bat', 'Right-arm medium', 'Bowler');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, bowling_style, player_role) VALUES ('Shariar', 'Kabir', TO_DATE('2003-04-04','YYYY-MM-DD'), 'Left-hand bat', 'Left-arm orthodox', 'Bowler');
INSERT INTO players (first_name, last_name, date_of_birth, batting_style, bowling_style, player_role) VALUES ('Wasif', 'Zaman', TO_DATE('2004-06-18','YYYY-MM-DD'), 'Right-hand bat', 'Right-arm fast', 'Bowler');

-- Explicitly commit regular insert data before triggering the loop block
COMMIT;


-- ====================================================================
-- 3. BRIDGE ARCHITECTURE INITIALIZATION (PL/SQL BLOCK)
-- ====================================================================
DECLARE
    v_tournament_id NUMBER;
BEGIN
    -- Dynamically grab the exact ID of the tournament created right above
    SELECT MAX(tournament_id) INTO v_tournament_id FROM tournaments;

    -- Initialize team statistics caches and the global points table structure safely
    IF v_tournament_id IS NOT NULL THEN
        FOR r IN (SELECT team_id FROM teams) LOOP
            INSERT INTO team_stats_cache (team_id) VALUES (r.team_id);
            INSERT INTO points_table (tournament_id, team_id) VALUES (v_tournament_id, r.team_id);
        END LOOP;
    END IF;

    -- Initialize standard individual career-tracking structures
    FOR p IN (SELECT player_id FROM players) LOOP
        INSERT INTO player_stats_cache (player_id) VALUES (p.player_id);
    END LOOP;

    -- Make structural additions final
    COMMIT;
END;
/

-- Re-enable variable prompts for standard SQL*Plus usage down the line
SET DEFINE ON;