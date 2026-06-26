DROP TABLE teams CASCADE CONSTRAINTS;

CREATE TABLE teams (
    id NUMBER(10) PRIMARY KEY,
    name VARCHAR2(50) NOT NULL UNIQUE,
    played NUMBER(3) DEFAULT 0,
    won NUMBER(3) DEFAULT 0,
    lost NUMBER(3) DEFAULT 0,
    points NUMBER(4) DEFAULT 0
);

INSERT INTO teams (id, name, played, won, lost, points) VALUES (1, 'CSE', 4, 3, 1, 6);
INSERT INTO teams (id, name, played, won, lost, points) VALUES (2, 'EEE', 4, 3, 1, 6);
INSERT INTO teams (id, name, played, won, lost, points) VALUES (3, 'ME',  4, 2, 2, 4);
INSERT INTO teams (id, name, played, won, lost, points) VALUES (4, 'ECE', 4, 0, 4, 0);

COMMIT;