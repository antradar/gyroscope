
DROP TABLE IF EXISTS `actionlog`;

CREATE TABLE `actionlog` (
  `alogid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) unsigned NOT NULL,
  `logname` varchar(255) DEFAULT NULL,
  `logmessage` varchar(255) NOT NULL,
  `rawobj` longtext NOT NULL,
  `logdate` varchar(20) NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  `rectype` varchar(255) NOT NULL,
  `recid` bigint(20) unsigned NOT NULL,
  `wssdone` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`alogid`),
  KEY `logdate` (`logdate`),
  KEY `userid` (`userid`),
  KEY `wssdone` (`wssdone`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `userid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(20) NOT NULL DEFAULT '',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `virtual` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `password` varchar(60) NOT NULL DEFAULT '',
  `passreset` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `groupnames` varchar(200) NOT NULL DEFAULT 'users',
  `certhash` varchar(60) DEFAULT NULL,
  `needcert` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `certname` varchar(255) DEFAULT NULL,  
  PRIMARY KEY (`userid`),
  KEY `active` (`active`),
  KEY `virtual` (`virtual`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

INSERT INTO `users` VALUES (101,'admin',1,0,'f9aca5fa9b9376cb5a8705c29c2d2b5a',0,'users|admins|accounts',null,0,null);

