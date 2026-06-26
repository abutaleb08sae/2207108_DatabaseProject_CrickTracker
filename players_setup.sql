DROP TABLE player_statistics CASCADE CONSTRAINTS;

CREATE TABLE player_statistics (
    player_id NUMBER(10) PRIMARY KEY,
    name VARCHAR2(100) NOT NULL,
    department VARCHAR2(50) NOT NULL,
    matches NUMBER(5) DEFAULT 0,
    innings NUMBER(5) DEFAULT 0,
    runs NUMBER(6) DEFAULT 0,
    batting_average NUMBER(5,2) DEFAULT 0.00,
    strike_rate NUMBER(5,1) DEFAULT 0.0
);

INSERT INTO player_statistics (player_id, name, department, matches, innings, runs, batting_average, strike_rate) 
VALUES (1, 'Abir Hasan', 'CSE', 12, 12, 342, 42.75, 145.2);

INSERT INTO player_statistics (player_id, name, department, matches, innings, runs, batting_average, strike_rate) 
VALUES (2, 'Sakib Ahmed', 'EEE', 14, 13, 289, 36.12, 138.5);

INSERT INTO player_statistics (player_id, name, department, matches, innings, runs, batting_average, strike_rate) 
VALUES (3, 'Tanvir Rahman', 'ME', 10, 10, 245, 30.63, 124.8);

INSERT INTO player_statistics (player_id, name, department, matches, innings, runs, batting_average, strike_rate) 
VALUES (4, 'Nahid Chowdhury', 'ECE', 11, 11, 198, 19.80, 115.3);

COMMIT;