CREATE TABLE actionlog (
  alogid bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  userid int(20) unsigned NOT NULL,
  logmessage varchar(255) NOT NULL,
  rawobj longtext NOT NULL,
  logdate varchar(20) NOT NULL,
  PRIMARY KEY (alogid),
  KEY logdate (logdate),
  KEY userid (userid)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE users (
  userid int(10) unsigned NOT NULL AUTO_INCREMENT,
  login varchar(20) NOT NULL DEFAULT '',
  `password` varchar(60) NOT NULL DEFAULT '',
  groupnames varchar(200) NOT NULL DEFAULT 'users',
  PRIMARY KEY (userid)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO users VALUES (1, 'admin', 'f9aca5fa9b9376cb5a8705c29c2d2b5a', 'users|admins');