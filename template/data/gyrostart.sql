
DROP TABLE IF EXISTS `actionlog`;

CREATE TABLE `actionlog` (
  `alogid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gsid` bigint(20) unsigned NOT NULL,
  `userid` bigint(20) unsigned NOT NULL,
  `logname` varchar(255) DEFAULT NULL,
  `logmessage` varchar(255) NOT NULL,
  `rawobj` longtext NOT NULL,
  `logdate` bigint(20) DEFAULT NULL,
  `sid` int(10) unsigned NOT NULL,
  `rectype` varchar(255) NOT NULL,
  `recid` bigint(20) unsigned NOT NULL,
  `wssdone` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`alogid`,`logdate`),
  KEY `logdate` (`logdate`),
  KEY `userid` (`userid`),
  KEY `wssdone` (`wssdone`),
  KEY `gsid` (`gsid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1
partition by range(logdate)
(
partition p2018a values less than (1514764800),
partition p2018b values less than (1527811200),
partition p2019a values less than (1546300800),
partition p2019b values less than (1559347200),
partition pmax values less than maxvalue
)
;

DROP TABLE IF EXISTS gss;
CREATE TABLE gss (
  gsid bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  gsname varchar(255) NOT NULL,
  stripecustomerid varchar(255) NOT NULL,
  gsexpiry bigint(20) unsigned NOT NULL DEFAULT '0',
  gstier tinyint(3) unsigned NOT NULL DEFAULT '0',  
  PRIMARY KEY (gsid),
  KEY gsname (gsname),
  KEY gsexpiry (gsexpiry)  
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;

#
# Dumping data for table `gss`
#

INSERT INTO `gss` VALUES (1, 'Default Instance', '', 0, 0);

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `userid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gsid` bigint(20) unsigned NOT NULL,
  `login` varchar(255) NOT NULL DEFAULT '',
  `dispname` varchar(255) NOT NULL DEFAULT '',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `virtualuser` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `password` varchar(255) NOT NULL DEFAULT '',
  `passreset` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `groupnames` longtext,
  `keyfilehash` varchar(255) DEFAULT NULL,
  `needkeyfile` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `usesms` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `smscell` varchar(255) NOT NULL DEFAULT '',  
  `smscode` varchar(255) NOT NULL DEFAULT '',  
  `certhash` varchar(255) DEFAULT NULL,
  `needcert` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `certname` varchar(255) DEFAULT NULL,  
  PRIMARY KEY (`userid`),
  UNIQUE KEY `login` (`login`),
  KEY `active` (`active`),
  KEY `virtualuser` (`virtualuser`),
  KEY `gsid` (`gsid`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- MCrypt --
-- INSERT INTO `users` VALUES (101,1,'admin','Admin',1,0,'FWZvboZOBbEqPhDQ5r04E4jfMgw4Q93kyDkx1xT/4z6jfngMPmGU3qvlfkT/Vp58OoZOvYwYUUDM9bfdvB+KEQ==',0,'users|admins|reportsettings|systemplateuse|systemplate|accounts|dbadmin|upgrademods',null,0,null,0,null);
-- OpenSSL --
 INSERT INTO `users` VALUES (101,1,'admin','Admin',1,0,'uSAsxyX3v44q3+/4Md7lzkhaNlIvTHFTMkZsN1lFRWVMRzB2UlloZTJqWHRjSG9mRDgybTI3U0xZUjhzYVhUeDlUZ2dLQ283QkUrVmExaEY=',0,'users|admins|reportsettings|systemplateuse|systemplate|accounts|dbadmin|upgrademods',null,0,0,'','',null,0,null);

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
  `gsid` bigint(20) unsigned NOT NULL,
  `templatetypename` varchar(255) NOT NULL,
  `templatetypekey` varchar(255) DEFAULT NULL,
  `activetemplateid` int(10) unsigned DEFAULT NULL,
  `templatetypegroup` varchar(255) NOT NULL,
  `plugins` varchar(255) NOT NULL,
  `classes` varchar(255) NOT NULL, 
  PRIMARY KEY (`templatetypeid`),
  KEY `templatetypekey` (`templatetypekey`),
  KEY `activetemplateid` (`activetemplateid`),
  KEY `gsid` (`gsid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

DROP TABLE IF EXISTS `templatevars`;
CREATE TABLE `templatevars` (
  `templatevarid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `templatevarname` varchar(255) NOT NULL,
  `templatevardesc` varchar(255) NOT NULL,
  `templatetypeid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`templatevarid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;
    
DROP TABLE IF EXISTS reports;
CREATE TABLE reports (
  reportid int(10) unsigned NOT NULL AUTO_INCREMENT,
  gsid bigint(20) unsigned NOT NULL,
  reportname_en varchar(255) DEFAULT NULL,
  reportgroup_en varchar(255) DEFAULT NULL,
  reportdesc_en longtext,
  reportname_de varchar(255) NOT NULL,
  reportgroup_de varchar(255) NOT NULL,
  reportdesc_de longtext NOT NULL,
  reportname_pt varchar(255) NOT NULL,
  reportgroup_pt varchar(255) NOT NULL,
  reportdesc_pt longtext NOT NULL,
  reportname_zh varchar(255) NOT NULL,
  reportgroup_zh varchar(255) NOT NULL,
  reportdesc_zh longtext NOT NULL,
  reportfunc varchar(255) DEFAULT NULL,
  reportkey varchar(255) NOT NULL,
  reportgroupnames varchar(255) NOT NULL,
  gyrosys tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (reportid),
  KEY reportkey (reportkey),
  KEY reportname_en (reportname_en),
  KEY reportgroup_en (reportgroup_en),
  KEY reportname_de (reportname_de),
  KEY reportgroup_de (reportgroup_de),
  KEY reportname_pt (reportname_pt),
  KEY reportgroup_pt (reportgroup_pt),
  KEY reportname_zh (reportname_zh),
  KEY reportgroup_zh (reportgroup_zh),
  KEY gsid (gsid)
) ENGINE=InnoDB;


INSERT INTO `reports` VALUES (1, 0, 'Activity Log', 'Security', 'This report is mostly used by system administrators for diagnostic purposes.', 'Aktivitätsprotokoll', 'Sicherheit', '', 'Registro de Atividade', '', 'admins', '1', '活动日志', '安全记录', '', 'actionlog', 'admins|reportsettings|systemplateuse|systemplate', 1);
     