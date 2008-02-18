CREATE TABLE `users` (
	`userid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`username` varchar(30) NOT NULL,
	`password` varchar(50) NOT NULL,
	`email` varchar(255) NOT NULL,
	`disabled` tinyint(1) NOT NULL DEFAULT '0',
	`activated` tinyint(1) NOT NULL DEFAULT '0',
	`actcode` varchar(45) NOT NULL,
	`userlevel` tinyint(1) NOT NULL DEFAULT '1',
	PRIMARY KEY (`userid`)
);
	
CREATE TABLE `frequencies` (
	`tableid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`userid` int(11) NOT NULL,
	`tablename` varchar(255) NOT NULL,
	`location` text(65535) NOT NULL,
	`lastedit` timestamp(8),
	`numchans` int(11) NOT NULL,
	`frequency` longtext NOT NULL,
	`alphatag` longtext NOT NULL,
	`description` longtext NOT NULL,
	`public` int(2) NOT NULL DEFAULT '0',
	`sharecode` varchar(50) NOT NULL,
	PRIMARY KEY (`tableid`)
);

INSERT INTO `users` (username, password, activated, userlevel)
VALUES ('admin', '5f4dcc3b5aa765d61d8327deb882cf99', '1', '9');

	