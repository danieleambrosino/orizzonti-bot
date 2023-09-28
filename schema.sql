CREATE TABLE Presentation (
	userId INTEGER PRIMARY KEY,
	username VARCHAR(32),
	firstName VARCHAR(64),
	lastName VARCHAR(64),
	status TINYINT NOT NULL,
	presentation VARCHAR(4096),
	inviter VARCHAR(4096)
);
