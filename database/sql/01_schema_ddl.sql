-- Clean up existing objects using standard Oracle cascading drops
DROP TABLE audit_logs CASCADE CONSTRAINTS;
DROP TABLE news_feed CASCADE CONSTRAINTS;
DROP TABLE points_table CASCADE CONSTRAINTS;
DROP TABLE partnerships CASCADE CONSTRAINTS;
DROP TABLE wickets CASCADE CONSTRAINTS;
DROP TABLE ball_by_ball CASCADE CONSTRAINTS;
DROP TABLE innings CASCADE CONSTRAINTS;
DROP TABLE matches CASCADE CONSTRAINTS;
DROP TABLE venues CASCADE CONSTRAINTS;
DROP TABLE team_players CASCADE CONSTRAINTS;
DROP TABLE player_stats_cache CASCADE CONSTRAINTS;
DROP TABLE team_stats_cache CASCADE CONSTRAINTS;
DROP TABLE players CASCADE CONSTRAINTS;
DROP TABLE teams CASCADE CONSTRAINTS;
DROP TABLE tournaments CASCADE CONSTRAINTS;
DROP TABLE users CASCADE CONSTRAINTS;

-- 1. USERS TABLE
CREATE TABLE users (
    user_id NUMBER PRIMARY KEY,
    username VARCHAR2(50) NOT NULL UNIQUE,
    email VARCHAR2(100) NOT NULL UNIQUE,
    password_hash VARCHAR2(255) NOT NULL,
    role VARCHAR2(20) DEFAULT 'Admin' CONSTRAINT chk_user_role CHECK (role IN ('SuperAdmin', 'Admin', 'Scorer')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE SEQUENCE seq_users_id START WITH 1 INCREMENT BY 1;
CREATE OR REPLACE TRIGGER trg_users_id
BEFORE INSERT ON users FOR EACH ROW
BEGIN
    IF :NEW.user_id IS NULL THEN SELECT seq_users_id.NEXTVAL INTO :NEW.user_id FROM DUAL; END IF;
END;
/

-- 2. TOURNAMENTS TABLE
CREATE TABLE tournaments (
    tournament_id NUMBER PRIMARY KEY,
    name VARCHAR2(100) NOT NULL UNIQUE,
    edition VARCHAR2(20) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    format VARCHAR2(10) NOT NULL CONSTRAINT chk_tourn_format CHECK (format IN ('T20', 'ODI', 'TEST')),
    CONSTRAINT chk_tourn_dates CHECK (end_date >= start_date)
);

CREATE SEQUENCE seq_tournaments_id START WITH 1 INCREMENT BY 1;
CREATE OR REPLACE TRIGGER trg_tournaments_id
BEFORE INSERT ON tournaments FOR EACH ROW
BEGIN
    IF :NEW.tournament_id IS NULL THEN SELECT seq_tournaments_id.NEXTVAL INTO :NEW.tournament_id FROM DUAL; END IF;
END;
/

-- 3. TEAMS TABLE
CREATE TABLE teams (
    team_id NUMBER PRIMARY KEY,
    name VARCHAR2(100) NOT NULL UNIQUE,
    short_name VARCHAR2(10) NOT NULL UNIQUE,
    logo_path VARCHAR2(255),
    created_at DATE DEFAULT SYSDATE
);

CREATE SEQUENCE seq_teams_id START WITH 1 INCREMENT BY 1;
CREATE OR REPLACE TRIGGER trg_teams_id
BEFORE INSERT ON teams FOR EACH ROW
BEGIN
    IF :NEW.team_id IS NULL THEN SELECT seq_teams_id.NEXTVAL INTO :NEW.team_id FROM DUAL; END IF;
END;
/

-- 4. PLAYERS TABLE
CREATE TABLE players (
    player_id NUMBER PRIMARY KEY,
    first_name VARCHAR2(50) NOT NULL,
    last_name VARCHAR2(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    birth_place VARCHAR2(100),
    batting_style VARCHAR2(50) NOT NULL CONSTRAINT chk_bat_style CHECK (batting_style IN ('Right-hand bat', 'Left-hand bat')),
    bowling_style VARCHAR2(50) CONSTRAINT chk_bowl_style CHECK (bowling_style IN ('Right-arm fast', 'Right-arm medium', 'Right-arm offbreak', 'Left-arm orthodox', 'Left-arm fast', 'Legbreak')),
    player_role VARCHAR2(30) NOT NULL CONSTRAINT chk_play_role CHECK (player_role IN ('Batsman', 'Bowler', 'Allrounder', 'Wicketkeeper'))
);

CREATE SEQUENCE seq_players_id START WITH 1 INCREMENT BY 1;
CREATE OR REPLACE TRIGGER trg_players_id
BEFORE INSERT ON players FOR EACH ROW
BEGIN
    IF :NEW.player_id IS NULL THEN SELECT seq_players_id.NEXTVAL INTO :NEW.player_id FROM DUAL; END IF;
END;
/

-- 5. TEAM STATS CACHE TABLE
CREATE TABLE team_stats_cache (
    team_id NUMBER PRIMARY KEY,
    played NUMBER DEFAULT 0,
    won NUMBER DEFAULT 0,
    lost NUMBER DEFAULT 0,
    tied NUMBER DEFAULT 0,
    no_result NUMBER DEFAULT 0,
    CONSTRAINT fk_tsc_team FOREIGN KEY (team_id) REFERENCES teams(team_id) ON DELETE CASCADE
);

-- 6. PLAYER STATS CACHE TABLE
CREATE TABLE player_stats_cache (
    player_id NUMBER PRIMARY KEY,
    matches NUMBER DEFAULT 0,
    runs_scored NUMBER DEFAULT 0,
    highest_score NUMBER DEFAULT 0,
    batting_avg NUMBER(5,2) DEFAULT 0.00,
    strike_rate NUMBER(5,2) DEFAULT 0.00,
    hundreds NUMBER DEFAULT 0,
    fifties NUMBER DEFAULT 0,
    wickets_taken NUMBER DEFAULT 0,
    runs_conceded NUMBER DEFAULT 0,
    economy_rate NUMBER(4,2) DEFAULT 0.00,
    five_w NUMBER DEFAULT 0,
    CONSTRAINT fk_psc_player FOREIGN KEY (player_id) REFERENCES players(player_id) ON DELETE CASCADE
);

-- 7. TEAM PLAYERS BRIDGE TABLE (Roster management)
CREATE TABLE team_players (
    team_id NUMBER,
    player_id NUMBER,
    tournament_id NUMBER,
    is_captain NUMBER(1) DEFAULT 0 CONSTRAINT chk_tp_cap CHECK (is_captain IN (0,1)),
    is_wicketkeeper NUMBER(1) DEFAULT 0 CONSTRAINT chk_tp_wk CHECK (is_wicketkeeper IN (0,1)),
    PRIMARY KEY (team_id, player_id, tournament_id),
    CONSTRAINT fk_tp_team FOREIGN KEY (team_id) REFERENCES teams(team_id),
    CONSTRAINT fk_tp_player FOREIGN KEY (player_id) REFERENCES players(player_id),
    CONSTRAINT fk_tp_tourn FOREIGN KEY (tournament_id) REFERENCES tournaments(tournament_id)
);

-- 8. VENUES TABLE
CREATE TABLE venues (
    venue_id NUMBER PRIMARY KEY,
    name VARCHAR2(100) NOT NULL UNIQUE,
    city VARCHAR2(50) NOT NULL,
    capacity NUMBER,
    floodlights NUMBER(1) DEFAULT 1 CONSTRAINT chk_venue_fl CHECK (floodlights IN (0,1))
);

CREATE SEQUENCE seq_venues_id START WITH 1 INCREMENT BY 1;
CREATE OR REPLACE TRIGGER trg_venues_id
BEFORE INSERT ON venues FOR EACH ROW
BEGIN
    IF :NEW.venue_id IS NULL THEN SELECT seq_venues_id.NEXTVAL INTO :NEW.venue_id FROM DUAL; END IF;
END;
/

-- 9. MATCHES TABLE
CREATE TABLE matches (
    match_id NUMBER PRIMARY KEY,
    tournament_id NUMBER NOT NULL,
    team1_id NUMBER NOT NULL,
    team2_id NUMBER NOT NULL,
    venue_id NUMBER NOT NULL,
    match_date TIMESTAMP NOT NULL,
    match_status VARCHAR2(20) DEFAULT 'Scheduled' 
        CONSTRAINT chk_match_status CHECK (match_status IN ('Scheduled', 'Live', 'Completed', 'Abandoned', 'Delayed')),
    toss_winner_id NUMBER,
    toss_decision VARCHAR2(10) CONSTRAINT chk_toss_dec CHECK (toss_decision IN ('Bat', 'Bowl')),
    match_winner_id NUMBER,
    result_margin VARCHAR2(100),
    total_overs NUMBER(3,1) DEFAULT 20.0,
    CONSTRAINT fk_match_tourn FOREIGN KEY (tournament_id) REFERENCES tournaments(tournament_id),
    CONSTRAINT fk_match_t1 FOREIGN KEY (team1_id) REFERENCES teams(team_id),
    CONSTRAINT fk_match_t2 FOREIGN KEY (team2_id) REFERENCES teams(team_id),
    CONSTRAINT fk_match_venue FOREIGN KEY (venue_id) REFERENCES venues(venue_id),
    CONSTRAINT chk_match_teams CHECK (team1_id != team2_id)
);

CREATE SEQUENCE seq_matches_id START WITH 1 INCREMENT BY 1;
CREATE OR REPLACE TRIGGER trg_matches_id
BEFORE INSERT ON matches FOR EACH ROW
BEGIN
    IF :NEW.match_id IS NULL THEN SELECT seq_matches_id.NEXTVAL INTO :NEW.match_id FROM DUAL; END IF;
END;
/

-- 10. INNINGS TABLE
CREATE TABLE innings (
    innings_id NUMBER PRIMARY KEY,
    match_id NUMBER NOT NULL,
    batting_team_id NUMBER NOT NULL,
    bowling_team_id NUMBER NOT NULL,
    innings_number NUMBER(1) NOT NULL CONSTRAINT chk_inn_num CHECK (innings_number IN (1,2,3,4)),
    total_runs NUMBER DEFAULT 0,
    total_wickets NUMBER DEFAULT 0,
    overs_bowled NUMBER(3,1) DEFAULT 0.0,
    CONSTRAINT fk_inn_match FOREIGN KEY (match_id) REFERENCES matches(match_id) ON DELETE CASCADE,
    CONSTRAINT fk_inn_bt FOREIGN KEY (batting_team_id) REFERENCES teams(team_id),
    CONSTRAINT fk_inn_blt FOREIGN KEY (bowling_team_id) REFERENCES teams(team_id),
    CONSTRAINT uq_match_inn UNIQUE (match_id, innings_number)
);

CREATE SEQUENCE seq_innings_id START WITH 1 INCREMENT BY 1;
CREATE OR REPLACE TRIGGER trg_innings_id
BEFORE INSERT ON innings FOR EACH ROW
BEGIN
    IF :NEW.innings_id IS NULL THEN SELECT seq_innings_id.NEXTVAL INTO :NEW.innings_id FROM DUAL; END IF;
END;
/

-- 11. BALL_BY_BALL TABLE
CREATE TABLE ball_by_ball (
    ball_id NUMBER PRIMARY KEY,
    innings_id NUMBER NOT NULL,
    over_number NUMBER(2) NOT NULL,
    ball_number NUMBER(2) NOT NULL,
    batsman_id NUMBER NOT NULL,
    non_striker_id NUMBER NOT NULL,
    bowler_id NUMBER NOT NULL,
    runs_batsman NUMBER(1) DEFAULT 0 CONSTRAINT chk_bbb_runs CHECK (runs_batsman BETWEEN 0 AND 6),
    runs_extras NUMBER(2) DEFAULT 0,
    extra_type VARCHAR2(10) DEFAULT 'None' CONSTRAINT chk_extra_type CHECK (extra_type IN ('None', 'WD', 'NB', 'LB', 'B', 'P')),
    commentary VARCHAR2(4000),
    CONSTRAINT fk_bbb_inn FOREIGN KEY (innings_id) REFERENCES innings(innings_id) ON DELETE CASCADE,
    CONSTRAINT fk_bbb_bat FOREIGN KEY (batsman_id) REFERENCES players(player_id),
    CONSTRAINT fk_bbb_non FOREIGN KEY (non_striker_id) REFERENCES players(player_id),
    CONSTRAINT fk_bbb_bowl FOREIGN KEY (bowler_id) REFERENCES players(player_id)
);

CREATE SEQUENCE seq_ball_by_ball_id START WITH 1 INCREMENT BY 1;
CREATE OR REPLACE TRIGGER trg_ball_by_ball_id
BEFORE INSERT ON ball_by_ball FOR EACH ROW
BEGIN
    IF :NEW.ball_id IS NULL THEN SELECT seq_ball_by_ball_id.NEXTVAL INTO :NEW.ball_id FROM DUAL; END IF;
END;
/

-- 12. WICKETS TABLE
CREATE TABLE wickets (
    wicket_id NUMBER PRIMARY KEY,
    ball_id NUMBER NOT NULL UNIQUE,
    player_dismissed_id NUMBER NOT NULL,
    kind VARCHAR2(20) NOT NULL CONSTRAINT chk_wkt_kind CHECK (kind IN ('Bowled', 'Caught', 'LBW', 'Run Out', 'Stumped', 'Hit Wicket', 'Retired Hurt')),
    fielder_id NUMBER,
    CONSTRAINT fk_wkt_ball FOREIGN KEY (ball_id) REFERENCES ball_by_ball(ball_id) ON DELETE CASCADE,
    CONSTRAINT fk_wkt_pd FOREIGN KEY (player_dismissed_id) REFERENCES players(player_id),
    CONSTRAINT fk_wkt_fld FOREIGN KEY (fielder_id) REFERENCES players(player_id)
);

CREATE SEQUENCE seq_wickets_id START WITH 1 INCREMENT BY 1;
CREATE OR REPLACE TRIGGER trg_wickets_id
BEFORE INSERT ON wickets FOR EACH ROW
BEGIN
    IF :NEW.wicket_id IS NULL THEN SELECT seq_wickets_id.NEXTVAL INTO :NEW.wicket_id FROM DUAL; END IF;
END;
/

-- 13. PARTNERSHIPS TABLE
CREATE TABLE partnerships (
    partnership_id NUMBER PRIMARY KEY,
    innings_id NUMBER NOT NULL,
    player1_id NUMBER NOT NULL,
    player2_id NUMBER NOT NULL,
    total_runs NUMBER DEFAULT 0,
    balls_faced NUMBER DEFAULT 0,
    CONSTRAINT fk_part_inn FOREIGN KEY (innings_id) REFERENCES innings(innings_id) ON DELETE CASCADE,
    CONSTRAINT fk_part_p1 FOREIGN KEY (player1_id) REFERENCES players(player_id),
    CONSTRAINT fk_part_p2 FOREIGN KEY (player2_id) REFERENCES players(player_id)
);

CREATE SEQUENCE seq_partnerships_id START WITH 1 INCREMENT BY 1;
CREATE OR REPLACE TRIGGER trg_partnerships_id
BEFORE INSERT ON partnerships FOR EACH ROW
BEGIN
    IF :NEW.partnership_id IS NULL THEN SELECT seq_partnerships_id.NEXTVAL INTO :NEW.partnership_id FROM DUAL; END IF;
END;
/

-- 14. POINTS TABLE
CREATE TABLE points_table (
    points_id NUMBER PRIMARY KEY,
    tournament_id NUMBER NOT NULL,
    team_id NUMBER NOT NULL,
    played NUMBER DEFAULT 0,
    won NUMBER DEFAULT 0,
    lost NUMBER DEFAULT 0,
    tied NUMBER DEFAULT 0,
    points NUMBER DEFAULT 0,
    net_run_rate NUMBER(6,3) DEFAULT 0.000,
    CONSTRAINT fk_pt_tourn FOREIGN KEY (tournament_id) REFERENCES tournaments(tournament_id) ON DELETE CASCADE,
    CONSTRAINT fk_pt_team FOREIGN KEY (team_id) REFERENCES teams(team_id) ON DELETE CASCADE,
    CONSTRAINT uq_tourn_team UNIQUE (tournament_id, team_id)
);

CREATE SEQUENCE seq_points_table_id START WITH 1 INCREMENT BY 1;
CREATE OR REPLACE TRIGGER trg_points_table_id
BEFORE INSERT ON points_table FOR EACH ROW
BEGIN
    IF :NEW.points_id IS NULL THEN SELECT seq_points_table_id.NEXTVAL INTO :NEW.points_id FROM DUAL; END IF;
END;
/

-- 15. NEWS FEED TABLE
CREATE TABLE news_feed (
    news_id NUMBER PRIMARY KEY,
    title VARCHAR2(255) NOT NULL,
    content CLOB NOT NULL,
    author_id NUMBER,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_news_auth FOREIGN KEY (author_id) REFERENCES users(user_id) ON DELETE SET NULL
);

CREATE SEQUENCE seq_news_feed_id START WITH 1 INCREMENT BY 1;
CREATE OR REPLACE TRIGGER trg_news_feed_id
BEFORE INSERT ON news_feed FOR EACH ROW
BEGIN
    IF :NEW.news_id IS NULL THEN SELECT seq_news_feed_id.NEXTVAL INTO :NEW.news_id FROM DUAL; END IF;
END;
/

-- 16. AUDIT LOGS TABLE
CREATE TABLE audit_logs (
    log_id NUMBER PRIMARY KEY,
    table_name VARCHAR2(50) NOT NULL,
    operation VARCHAR2(20) NOT NULL,
    record_id NUMBER NOT NULL,
    performed_by VARCHAR2(50) DEFAULT 'SYSTEM',
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    old_values CLOB,
    new_values CLOB
);

CREATE SEQUENCE seq_audit_logs_id START WITH 1 INCREMENT BY 1;
CREATE OR REPLACE TRIGGER trg_audit_logs_id
BEFORE INSERT ON audit_logs FOR EACH ROW
BEGIN
    IF :NEW.log_id IS NULL THEN SELECT seq_audit_logs_id.NEXTVAL INTO :NEW.log_id FROM DUAL; END IF;
END;
/