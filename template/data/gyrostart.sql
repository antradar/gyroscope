
DROP TABLE IF EXISTS `actionlog`;

CREATE TABLE `actionlog` (
  `alogid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) unsigned NOT NULL,
  `logname` varchar(255) DEFAULT NULL,
  `logmessage` varchar(255) NOT NULL,
  `rawobj` longtext NOT NULL,
  `logdate` bigint(20) DEFAULT NULL,
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
  `dispname` varchar(20) NOT NULL DEFAULT '',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `virtualuser` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `password` varchar(255) NOT NULL DEFAULT '',
  `passreset` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `groupnames` longtext,
  `certhash` varchar(60) DEFAULT NULL,
  `needcert` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `certname` varchar(255) DEFAULT NULL,  
  PRIMARY KEY (`userid`),
  UNIQUE KEY `login` (`login`),
  KEY `active` (`active`),
  KEY `virtualuser` (`virtualuser`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

INSERT INTO `users` VALUES (101,'admin','Admin',1,0,'WGmijuXRkchwvuH5IPV8qZ7Bx+ER6KwpIrKSiPfoYod+zEqFq3V+q0X7I+pb7Iv9zosR6zfMg6DGy5D0D1zd2VxjdP0p3DbQk4nVT6ARAINrXM4XZRJAv9CSR1aW072Y',0,'users|admins|reportsettings|systemplateuse|systemplate|accounts|dbadmin|upgrademods',null,0,null);

DROP TABLE IF EXISTS `templates`;
CREATE TABLE `templates` (
  `templateid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `templatename` varchar(255) NOT NULL,
  `templatetypeid` int(10) unsigned NOT NULL,
  `templatetext` longtext,
  PRIMARY KEY (`templateid`),
  KEY `templatetypeid` (`templatetypeid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

DROP TABLE IF EXISTS `templatetypes`;
CREATE TABLE `templatetypes` (
  `templatetypeid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `templatetypename` varchar(255) NOT NULL,
  `templatetypekey` varchar(255) DEFAULT NULL,
  `activetemplateid` int(10) unsigned DEFAULT NULL,
  `templatetypegroup` varchar(255) NOT NULL,
  `plugins` varchar(255) NOT NULL,
  `classes` varchar(255) NOT NULL, 
  PRIMARY KEY (`templatetypeid`),
  UNIQUE KEY `templatetypekey` (`templatetypekey`),
  KEY `activetemplateid` (`activetemplateid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

DROP TABLE IF EXISTS `templatevars`;
CREATE TABLE `templatevars` (
  `templatevarid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `templatevarname` varchar(255) NOT NULL,
  `templatevardesc` varchar(255) NOT NULL,
  `templatetypeid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`templatevarid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;
    
CREATE TABLE `reports` (
  `reportid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reportname` varchar(255) NOT NULL,
  `reportgroup` varchar(255) NOT NULL,
  `reportdesc` longtext,
  `reportfunc` varchar(255) NULL,
  `reportkey` varchar(255) NOT NULL,
  `reportgroupnames` varchar(255) NOT NULL,
  `gyrosys` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`reportid`),
  UNIQUE KEY `reportkey` (`reportkey`),
  KEY `reportname` (`reportname`),
  KEY `reportgroup` (`reportgroup`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;


INSERT INTO `reports` VALUES (1, 'Activity Log', 'Security', 'This report is mostly used by system administrators for diagnostic purposes.',NULL,'actionlog', 'admins', 1);
    