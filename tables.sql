DROP TABLE users;
DROP TABLE matches;
DROP TABLE registrants;
DROP TABLE sessions;
CREATE TABLE users (
	userid serial,
	username varchar,
	password varchar,
	email_address varchar,
	is_admin boolean
);
INSERT INTO users VALUES (default,'admin','$1$/nFprcYa$9c8tAska4Z2YlpI.hBz63.',null,true);
--Password is MatchAdmin!

CREATE TABLE sessions (
	sessionid serial,
	userid integer,
	token varchar,
	expires timestamp default now()+interval '24 hours'
);
CREATE TABLE matches (
	matchid serial,
	match_director integer,
	match_name varchar,
	match_start_date date,
	match_end_date date,
	num_fp integer,
	relay1 timestamp,
	relay2 timestamp,
	relay3 timestamp,
	relay4 timestamp,
	relay5 timestamp,
	relay6 timestamp,
	relay7 timestamp,
	relay8 timestamp,
	relay9 timestamp
);
INSERT INTO matches values (default,1,'2017 ND State Junior Smallbore Championship','2017-2-6','2017-2-6',12,'2017-02-06 12:00','2017-02-06 14:00');
INSERT INTO matches values (default,1,'2017 ND State Junior Smallbore Sectional','2017-2-7','2017-2-7',12,'2017-02-07 9:00','2017-02-06 11:00');


CREATE TABLE registrants (
	reg_id serial,
	matchid integer,
	Last_Name varchar,
	First_Name varchar,
	Middle_Name varchar,
	Birthdate date,
	Relay integer,
	Firing_Point integer,
	Address varchar,
	Address_Cont varchar,
	City varchar,
	State varchar,
	Zip varchar,
	Email varchar,
	Phone varchar
);
