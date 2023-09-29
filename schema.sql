CREATE TABLE presentations (
	user_id INTEGER PRIMARY KEY,
	username TEXT,
	first_name TEXT,
	last_name TEXT,
	status INTEGER NOT NULL DEFAULT 0,
	presentation TEXT,
	inviter TEXT
);
