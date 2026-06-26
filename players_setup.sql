DROP TABLE player_statistics CASCADE CONSTRAINTS;

CREATE TABLE player_statistics (
    player_id NUMBER(10) PRIMARY KEY,
    name VARCHAR2(100) NOT NULL,
    department VARCHAR2(50) NOT NULL,
    matches NUMBER(5) DEFAULT 0,
    innings NUMBER(5) DEFAULT 0,
    runs NUMBER(6) DEFAULT 0,
    highest_score NUMBER(5) DEFAULT 0,
    batting_average NUMBER(5,2) DEFAULT 0.00,
    strike_rate NUMBER(5,1) DEFAULT 0.0,
    hundreds NUMBER(3) DEFAULT 0,
    fifties NUMBER(3) DEFAULT 0,
    wickets NUMBER(5) DEFAULT 0,
    bowling_runs NUMBER(5) DEFAULT 0,
    best_bowling VARCHAR2(20) DEFAULT '0/0',
    economy NUMBER(4,2) DEFAULT 0.00,
    five_w NUMBER(3) DEFAULT 0
);

-- 10 Dedicated Batsmen (High Runs, 0 Wickets)
INSERT INTO player_statistics VALUES (1, 'Abir Hasan', 'CSE', 12, 12, 342, 84, 42.75, 145.2, 0, 3, 0, 0, '0/0', 0.00, 0);
INSERT INTO player_statistics VALUES (2, 'Sakib Ahmed', 'EEE', 14, 13, 389, 102, 36.12, 138.5, 1, 1, 0, 0, '0/0', 0.00, 0);
INSERT INTO player_statistics VALUES (3, 'Tanvir Rahman', 'ME', 10, 10, 245, 68, 30.63, 124.8, 0, 2, 0, 0, '0/0', 0.00, 0);
INSERT INTO player_statistics VALUES (4, 'Naimur Rahman', 'ECE', 11, 11, 295, 74, 26.81, 118.4, 0, 2, 0, 0, '0/0', 0.00, 0);
INSERT INTO player_statistics VALUES (5, 'Mehedi Hasan', 'CE', 13, 11, 310, 75, 34.44, 129.1, 0, 2, 0, 0, '0/0', 0.00, 0);
INSERT INTO player_statistics VALUES (6, 'Sifatur Rahman', 'BME', 9, 9, 215, 62, 23.88, 131.0, 0, 1, 0, 0, '0/0', 0.00, 0);
INSERT INTO player_statistics VALUES (7, 'Rifat Hossain', 'IEM', 12, 12, 278, 81, 25.27, 122.5, 0, 1, 0, 0, '0/0', 0.00, 0);
INSERT INTO player_statistics VALUES (8, 'Tahmid Anjum', 'URP', 10, 10, 230, 59, 23.00, 115.8, 0, 1, 0, 0, '0/0', 0.00, 0);
INSERT INTO player_statistics VALUES (9, 'Zahidul Islam', 'LE', 11, 10, 195, 55, 19.50, 110.2, 0, 1, 0, 0, '0/0', 0.00, 0);
INSERT INTO player_statistics VALUES (10, 'Imtiaz Ahmed', 'TE', 8, 8, 182, 48, 22.75, 125.4, 0, 0, 0, 0, '0/0', 0.00, 0);

-- 10 Dedicated Bowlers (High Wickets, Low Runs)
INSERT INTO player_statistics VALUES (11, 'Nahid Chowdhury', 'ECE', 11, 4, 32, 14, 8.00, 85.3, 0, 0, 18, 210, '4/15', 5.25, 0);
INSERT INTO player_statistics VALUES (12, 'Asif Raihan', 'CE', 13, 6, 45, 18, 9.00, 92.1, 0, 0, 16, 245, '5/22', 5.80, 1);
INSERT INTO player_statistics VALUES (13, 'Kamrul Islam', 'CSE', 12, 5, 28, 12, 7.00, 78.4, 0, 0, 15, 198, '4/20', 4.95, 0);
INSERT INTO player_statistics VALUES (14, 'Mahfuzur Rahman', 'EEE', 14, 7, 50, 15, 10.00, 88.2, 0, 0, 14, 260, '3/18', 6.12, 0);
INSERT INTO player_statistics VALUES (15, 'Sanzid Rahman', 'ME', 10, 3, 15, 8, 5.00, 70.0, 0, 0, 13, 175, '3/25', 5.50, 0);
INSERT INTO player_statistics VALUES (16, 'Farhan Tanvir', 'BME', 9, 4, 22, 10, 5.50, 95.0, 0, 0, 12, 185, '4/28', 6.16, 0);
INSERT INTO player_statistics VALUES (17, 'Rayhan Ahmed', 'IEM', 12, 5, 38, 16, 7.60, 101.3, 0, 0, 11, 220, '3/14', 5.78, 0);
INSERT INTO player_statistics VALUES (18, 'Niaz Morshed', 'URP', 10, 4, 19, 9, 4.75, 82.1, 0, 0, 10, 168, '3/30', 5.60, 0);
INSERT INTO player_statistics VALUES (19, 'Shariar Kabir', 'LE', 11, 3, 12, 6, 4.00, 65.4, 0, 0, 9, 190, '2/15', 5.18, 0);
INSERT INTO player_statistics VALUES (20, 'Wasif Zaman', 'TE', 8, 2, 8, 5, 4.00, 60.2, 0, 0, 8, 142, '2/22', 5.46, 0);

COMMIT;