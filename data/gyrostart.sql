DROP TABLE IF EXISTS `actionlog`;
CREATE TABLE `actionlog` (
  `alogid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `gsid` bigint(20) unsigned NOT NULL,
  `userid` bigint(20) unsigned NOT NULL,
  `logname` varchar(255),
  `logmessage` varchar(255),
  `rawobj` text,
  `logdate` bigint(20) DEFAULT NULL,
  `sid` int(10) unsigned NOT NULL default 0,
  `rectype` varchar(255) NOT NULL default '',
  `recid` bigint(20) unsigned NOT NULL default 0,
  `wssdone` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `bulldozed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`alogid`),
  KEY `logdate` (`logdate`),
  KEY `userid` (`userid`),
  KEY `wssdone` (`wssdone`),
  KEY `bulldozed` (`bulldozed`),
  KEY `gsid` (`gsid`),
  KEY `recid` (`recid`)
) ENGINE=InnoDB;

create table actionctxs(
ctxid int unsigned not null auto_increment,
ctxname varchar(255),
primary key (ctxid),
key ctxname (ctxname)
);


DROP TABLE IF EXISTS gss;
CREATE TABLE gss (
  gsid bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  gsname varchar(255) NOT NULL,
  stripecustomerid varchar(255) NOT NULL,
  gsexpiry bigint(20) unsigned NOT NULL DEFAULT '0',
  gstier tinyint(3) unsigned NOT NULL DEFAULT '0',
  maxchats int unsigned default 10,
  chatsperagent int unsigned default 1,
  msgraphanchor text,  
  PRIMARY KEY (gsid),
  KEY gsname (gsname),
  KEY gsexpiry (gsexpiry)  
) ENGINE=InnoDB;

#
# Dumping data for table `gss`
#

INSERT INTO `gss` VALUES (1, 'Default Instance', '', 0, 0,5,1,null);

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `userid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gsid` bigint(20) unsigned NOT NULL,
  `login` varchar(255) NOT NULL DEFAULT '',
  `dispname` varchar(255) NOT NULL DEFAULT '',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `virtualuser` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `quicklist` tinyint(3) unsigned DEFAULT '1',
  `darkmode` tinyint(3) unsigned DEFAULT '0',
  `dowoffset` tinyint(3) unsigned DEFAULT '0',
  `password` varchar(255) NOT NULL DEFAULT '',
  `passreset` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `groupnames` text,
  `canchat` tinyint(1) unsigned DEFAULT '0',
  `keyfilehash` varchar(255) DEFAULT NULL,
  `needkeyfile` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `usesms` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `smscell` varchar(255) NOT NULL DEFAULT '',  
  `smscode` varchar(255) NOT NULL DEFAULT '',  
  `usega` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `gakey` varchar(255) DEFAULT NULL,
  `usegamepad` tinyint(1) unsigned default 0,
  `haspic` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `imgv` int unsigned default '0',
  `useyubi` tinyint(1) unsigned default 0,
  `yubimode` tinyint unsigned default 0,
  `certhash` varchar(255) DEFAULT NULL,
  `needcert` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `certname` varchar(255) DEFAULT NULL,
  `msgraphtoken` text,  
  PRIMARY KEY (`userid`),
  UNIQUE KEY `login` (`login`),
  KEY `active` (`active`),
  KEY `canchat` (`canchat`),
  KEY `virtualuser` (`virtualuser`),
  KEY `gsid` (`gsid`)
) ENGINE=InnoDB;

-- MCrypt --
-- INSERT INTO `users` VALUES (101,1,'admin','Admin',1,0,'FWZvboZOBbEqPhDQ5r04E4jfMgw4Q93kyDkx1xT/4z6jfngMPmGU3qvlfkT/Vp58OoZOvYwYUUDM9bfdvB+KEQ==',0,'users|admins|reportsettings|systemplateuse|systemplate|accounts|dbadmin|upgrademods|msdrive|helpedit',null,0,null,0,null);
-- OpenSSL --
-- INSERT INTO `users` VALUES (101,1,'admin','Admin',1,0,'uSAsxyX3v44q3+/4Md7lzkhaNlIvTHFTMkZsN1lFRWVMRzB2UlloZTJqWHRjSG9mRDgybTI3U0xZUjhzYVhUeDlUZ2dLQ283QkUrVmExaEY=',0,'users|admins|reportsettings|systemplateuse|systemplate|accounts|dbadmin|upgrademods',null,0,0,'','',null,0,null);
-- BCrypt --
INSERT INTO `users` VALUES (101,1,'admin','Admin',1,0,1,0,0,'$2y$12$MbnxI00l82cSYWgNCGxAUeVtTL3CmlZTT.NFuscnnYUk6cbBs7vH2',0,'devreports|admins|reportsettings|systemplateuse|systemplate|accounts|dbadmin|creditcards|helpedit|chatsettings|chats|sharedashreports|msgpipe|msgpipeuse',0,null,0,0,'','',0,null,0,0,0,0,0,null,0,null,null);

DROP TABLE IF EXISTS `templates`;
CREATE TABLE `templates` (
  `templateid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `templatename` varchar(255) NOT NULL,
  `templatetypeid` int(10) unsigned NOT NULL,
  `templatetext` mediumtext,
  PRIMARY KEY (`templateid`),
  KEY `templatetypeid` (`templatetypeid`)
) ENGINE=InnoDB;

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
) ENGINE=InnoDB;

DROP TABLE IF EXISTS `templatevars`;
CREATE TABLE `templatevars` (
  `templatevarid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `templatevarname` varchar(255) NOT NULL,
  `templatevardesc` varchar(255) NOT NULL,
  `templatetypeid` int(10) unsigned NOT NULL,
  PRIMARY KEY (`templatevarid`)
) ENGINE=InnoDB;
    
DROP TABLE IF EXISTS reports;
CREATE TABLE reports (
  reportid int(10) unsigned NOT NULL AUTO_INCREMENT,
  gsid bigint(20) unsigned NOT NULL,
  reportname_en varchar(255) DEFAULT NULL,
  reportgroup_en varchar(255) DEFAULT NULL,
  reportdesc_en text,
  reportname_de varchar(255) NOT NULL default '',
  reportgroup_de varchar(255) NOT NULL default '',
  reportdesc_de text,
  reportname_pt varchar(255) NOT NULL default '',
  reportgroup_pt varchar(255) NOT NULL default '',
  reportdesc_pt text,
  reportname_zh varchar(255) NOT NULL default '',
  reportgroup_zh varchar(255) NOT NULL default '',
  reportdesc_zh text,
  reportfunc varchar(255) DEFAULT NULL default '',
  reportkey varchar(255) NOT NULL default '',
  reportgroupnames varchar(255) NOT NULL default '',
  reportparams varchar(255),
  bingo tinyint(3) unsigned default 0,
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

INSERT INTO `reports` VALUES (1, 1, 'Activity Log', 'Security', 'This report is mostly used by system administrators for diagnostic purposes.', 'Aktivitätsprotokoll', 'Sicherheit', '', 'Registro de Atividade', '', 'admins', '1', '????', '????', '', 'actionlog', 'admins|reportsettings|systemplateuse|systemplate','',0, 0);
INSERT INTO `reports` VALUES (2, 1, 'Activity Summary', 'Security', '', '', '', '', '', '', 'admins', '1', '', '', '', 'trace', 'admins|reportsettings|systemplateuse|systemplate','',1, 0);
INSERT INTO `reports` VALUES (3, 1, 'Server Access Log', 'Security', '', '', '', '', '', '', 'admins', '1', '', '', '', 'serverlog', 'admins|reportsettings|systemplateuse|systemplate','',1, 0);
INSERT INTO `reports` VALUES (4, 1, 'Mail Server Log', 'Security', '', '', '', '', '', '', 'admins', '1', '', '', '', 'mxevents', 'admins|reportsettings|systemplateuse|systemplate','',1, 0);
INSERT INTO `reports` VALUES (5, 1, 'Fault Log', 'Security', '', '', '', '', '', '', 'admins', '1', '', '', '', 'faultlog', 'faultlog','',0, 0);
INSERT INTO `reports` VALUES (6, 1, 'Activity Calendar (Demo)', 'Security', '', '', '', '', '', '', 'admins', '1', '', '', '', 'cale', 'admins|reportsettings|systemplateuse|systemplate','\'+cale_filters()+\'',0, 0);
INSERT INTO `reports` VALUES (7, 1, 'App Usage Summary', 'Security', '', '', '', '', '', '', 'admins', '1', '', '', '', 'serverdigest', 'admins|reportsettings|systemplateuse|systemplate','',1, 0);

DROP TABLE IF EXISTS userhelpspots;
CREATE TABLE userhelpspots (
  uhid bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  userid bigint(20) unsigned NOT NULL,
  helptopic varchar(255) NOT NULL,
  PRIMARY KEY (uhid),
  KEY userid (userid)
) ENGINE=InnoDB;
     
drop table if exists homedashreports;
create table homedashreports(
homedashreportid bigint unsigned not null auto_increment,
gsid bigint unsigned not null,
userid bigint unsigned not null,
rptkey varchar(255),
rpttabkey varchar(255),
rptname varchar(255),
rpttitle varchar(255),
rptlink varchar(255),
bingo tinyint(1) unsigned default 0,
shared tinyint(1) unsigned default 0,
primary key (homedashreportid),
key gsid (gsid),
key userid (userid),
key rptname (rptname)
);

drop table if exists helptopics;
create table helptopics(
helptopicid bigint unsigned not null auto_increment,
helptopictitle varchar(255),
helptopickeywords varchar(255),
helptopiclevel tinyint unsigned default 0,
helptopicsort bigint unsigned,
helptopictext mediumtext,
primary key (helptopicid),
key helptopictitle (helptopictitle),
key helptopickeywords(helptopickeywords),
key helptopicsort (helptopicsort)
);

drop table if exists yubikeys;
CREATE TABLE `yubikeys` (
  `keyid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `userid` bigint(20) unsigned DEFAULT NULL,
  `passless` tinyint(1) unsigned DEFAULT 0,
  `keyname` varchar(255) DEFAULT NULL,
  `credid` varchar(255) DEFAULT NULL,
  `kty` tinyint(4) DEFAULT NULL,
  `alg` tinyint(4) DEFAULT NULL,
  `crv` tinyint(4) DEFAULT NULL,
  `x` varchar(255) DEFAULT NULL,
  `y` varchar(255) DEFAULT NULL,
  `n` varchar(255) DEFAULT NULL,
  `e` varchar(255) DEFAULT NULL,
  `attid` varchar(255) DEFAULT NULL,
  `lastsigncount` bigint unsigned DEFAULT 0,
  PRIMARY KEY (`keyid`),
  KEY `userid` (`userid`),
  KEY `credid` (`credid`),
  KEY `attid` (`attid`)
);

create table faults(
faultid bigint unsigned not null auto_increment,
faultdate bigint,
gsid bigint unsigned,
userid bigint unsigned,
faultfile varchar(255),
faultline bigint unsigned,
faulttype tinyint unsigned default 0,
faultcode int unsigned,
faultmessage varchar(255),
faultdiagdata text,
callfile varchar(255),
callline bigint unsigned,
callfunc varchar(255),
callargs varchar(255),
primary key (faultid),
key gsid(gsid),
key userid(userid),
key faultdate(faultdate),
key faultcode(faultcode),
key faulttype(faulttype)
);

create table turlq(
turlid bigint unsigned not null auto_increment,
turlurl text,
turldate bigint,
turlattempt tinyint unsigned default 0,
turlnext bigint,
finalattempt tinyint(1) unsigned default 0,
turlerr varchar(255),
turlopts text,
primary key (turlid),
key turldate (turldate),
key turlnext (turlnext),
key turlattempt (turlattempt),
key finalattempt (finalattempt)
);

create table accesslogseq(
  logid bigint(20) unsigned NOT NULL auto_increment,
  primary key(logid)
);


create table msgpipes(
msgpipeid bigint unsigned not null auto_increment,
gsid bigint unsigned not null,
msgpipekey varchar(50),
msgpipename varchar(100),
primary key (msgpipeid),
key gsid (gsid),
key msgpipekey (msgpipekey)
);

create table msgpipeusers(
msgpipeuserid bigint unsigned not null auto_increment,
msgpipeid bigint unsigned not null,
userid bigint unsigned not null,
primary key (msgpipeuserid),
key msgpipeid (msgpipeid),
key userid (userid)
);


create table gsreplays (
  gsreplayid bigint(20) unsigned not null auto_increment,
  gsid bigint(20) unsigned,
  gsreplaydate bigint(20) unsigned,
  gsreplayuserid bigint(20) unsigned,
  gsreplaysharestatus tinyint(3) unsigned default '0',
  gsreplaywidth int(10) unsigned,
  gsreplayheight int(10) unsigned,
  gsreplaytitle varchar(50),
  gsreplaydesc varchar(255),
  primary key (gsreplayid),
  key gsid (gsid),
  key gsreplaydate (gsreplaydate),
  key gsreplayuserid (gsreplayuserid),
  key gsreplaysharestatus (gsreplaysharestatus),
  key gsreplaytitle (gsreplaytitle)
);

create table gsreplayframes (
  frameid bigint(20) unsigned not null auto_increment,
  gsreplayid bigint(20) unsigned,
  frametoffset int(10) unsigned,
  frameitr tinyint(3) unsigned default '0',
  primary key (frameid),
  key gsreplayid (gsreplayid),
  key frametoffset (frametoffset),
  key frameitr (frameitr)
);
