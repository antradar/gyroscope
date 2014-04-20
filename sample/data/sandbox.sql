
DROP TABLE IF EXISTS actionlog;
CREATE TABLE actionlog (
  alogid bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  userid int(20) unsigned NOT NULL,
  logmessage varchar(255) NOT NULL,
  rawobj longtext NOT NULL,
  logdate varchar(20) NOT NULL,
  PRIMARY KEY (alogid),
  KEY logdate (logdate),
  KEY userid (userid)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS actors;
CREATE TABLE actors (
  actorid int(10) unsigned NOT NULL AUTO_INCREMENT,
  fname varchar(255) NOT NULL,
  lname varchar(255) NOT NULL,
  bio LONGTEXT NOT NULL ,
  PRIMARY KEY (actorid)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS filmactors;
CREATE TABLE filmactors (
  faid int(10) unsigned NOT NULL AUTO_INCREMENT,
  filmid int(10) unsigned NOT NULL,
  actorid int(10) unsigned NOT NULL,
  role varchar(255) NOT NULL,
  PRIMARY KEY (faid)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;


DROP TABLE IF EXISTS films;
CREATE TABLE films (
  filmid int(10) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  filmyear varchar(4) NOT NULL,
  PRIMARY KEY (filmid),
  KEY filmyear (filmyear)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS landlords;
CREATE TABLE landlords (
  landlordid int(10) unsigned NOT NULL AUTO_INCREMENT,
  fname varchar(255) NOT NULL,
  lname varchar(255) NOT NULL,
  lladdr varchar(255) NOT NULL,
  llcity varchar(50) NOT NULL,
  llprov varchar(50) NOT NULL,
  llzip varchar(50) NOT NULL,
  PRIMARY KEY (landlordid),
  KEY fname (fname,lname)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS leases;
CREATE TABLE leases (
  lsid int(11) NOT NULL AUTO_INCREMENT,
  prid int(11) NOT NULL DEFAULT '0',
  rent decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (lsid)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS leasetenants;
CREATE TABLE leasetenants (
  lstnid int(11) NOT NULL AUTO_INCREMENT,
  lsid int(11) NOT NULL DEFAULT '0',
  tnid int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (lstnid),
  KEY lsid (lsid),
  KEY tnid (tnid)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


DROP TABLE IF EXISTS properties;
CREATE TABLE properties (
  prid int(11) NOT NULL AUTO_INCREMENT,
  addr varchar(50) NOT NULL DEFAULT '',
  city varchar(20) NOT NULL DEFAULT '',
  prov varchar(20) NOT NULL DEFAULT '',
  country varchar(20) NOT NULL DEFAULT 'Canada',
  zip varchar(8) NOT NULL DEFAULT '',
  landlordid int(11) DEFAULT '0',
  PRIMARY KEY (prid),
  KEY llid (landlordid)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



DROP TABLE IF EXISTS provs;
CREATE TABLE provs (
  pvid int(11) NOT NULL AUTO_INCREMENT,
  pvname varchar(200) NOT NULL DEFAULT '',
  pvzip varchar(5) NOT NULL DEFAULT '',
  PRIMARY KEY (pvid),
  KEY pvname (pvname),
  KEY pvzip (pvzip)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;


INSERT INTO provs VALUES (1, 'Ontario', 'ON');
INSERT INTO provs VALUES (2, 'British Columbia', 'BC');
INSERT INTO provs VALUES (3, 'Nova Scotia', 'NS');
INSERT INTO provs VALUES (4, 'Quebec', 'QC');
INSERT INTO provs VALUES (5, 'New Brunswick', 'NB');
INSERT INTO provs VALUES (6, 'Manitoba', 'MB');
INSERT INTO provs VALUES (7, 'Prince Edward Island', 'PE');
INSERT INTO provs VALUES (8, 'Saskatchewan', 'SK');
INSERT INTO provs VALUES (9, 'Alberta', 'AB');
INSERT INTO provs VALUES (10, 'Newfoundland', 'NL');
INSERT INTO provs VALUES (11, 'Northwest Territories', 'NT');
INSERT INTO provs VALUES (12, 'Yukon', 'YT');
INSERT INTO provs VALUES (13, 'Nunavut', 'NU');


DROP TABLE IF EXISTS tenants;
CREATE TABLE tenants (
  tnid int(10) unsigned NOT NULL AUTO_INCREMENT,
  fname varchar(255) NOT NULL,
  lname varchar(255) NOT NULL,
  addr varchar(255) NOT NULL,
  city varchar(50) NOT NULL,
  prov varchar(50) NOT NULL,
  zip varchar(50) NOT NULL,
  country varchar(50) NOT NULL,
  PRIMARY KEY (tnid),
  KEY fname (fname,lname)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS docs;
CREATE TABLE docs (
docid INT UNSIGNED NOT NULL AUTO_INCREMENT ,
doctitle VARCHAR( 255 ) NOT NULL ,
notes LONGTEXT NOT NULL ,
actorid int(10) unsigned NOT NULL,
clientid int(10) unsigned NOT NULL,
tenantid int(10) unsigned NOT NULL,
PRIMARY KEY ( docid )
) ENGINE = InnoDB DEFAULT CHARACTER SET = latin1;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  userid int(10) unsigned NOT NULL AUTO_INCREMENT,
  login varchar(20) NOT NULL DEFAULT '',
  `password` varchar(60) NOT NULL DEFAULT '',
  groupnames varchar(200) NOT NULL DEFAULT 'users',
  PRIMARY KEY (userid)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;



INSERT INTO users VALUES (1, 'guest', 'f9aca5fa9b9376cb5a8705c29c2d2b5a', 'users');
INSERT INTO users VALUES (2, 'admin', 'f9aca5fa9b9376cb5a8705c29c2d2b5a', 'users|admins');
