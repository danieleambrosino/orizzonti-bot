CREATE TABLE presentations (
	userId INTEGER PRIMARY KEY,
	username TEXT,
	firstName TEXT,
	lastName TEXT,
	status INTEGER NOT NULL DEFAULT 0,
	presentation TEXT,
	inviter TEXT
);
