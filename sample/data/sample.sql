# phpMyAdmin SQL Dump
# version 2.5.5-pl1
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Oct 21, 2008 at 09:09 PM
# Server version: 5.0.51
# PHP Version: 4.4.2
# 
# Database : `gyrodemo`
# 

# --------------------------------------------------------

#
# Table structure for table `landlords`
#

DROP TABLE IF EXISTS landlords;
CREATE TABLE landlords (
  llid int(11) NOT NULL auto_increment,
  personid int(11) NOT NULL default '0',
  PRIMARY KEY  (llid),
  KEY personid (personid)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

#
# Dumping data for table `landlords`
#

INSERT INTO landlords VALUES (1, 1);
INSERT INTO landlords VALUES (2, 2);
INSERT INTO landlords VALUES (3, 3);
INSERT INTO landlords VALUES (4, 4);
INSERT INTO landlords VALUES (5, 5);

# --------------------------------------------------------

#
# Table structure for table `leases`
#

DROP TABLE IF EXISTS leases;
CREATE TABLE leases (
  lsid int(11) NOT NULL auto_increment,
  prid int(11) NOT NULL default '0',
  aday char(2) NOT NULL default '',
  amon char(2) NOT NULL default '',
  ayear varchar(4) NOT NULL default '',
  bday char(2) NOT NULL default '',
  bmon char(2) NOT NULL default '',
  byear varchar(4) NOT NULL default '',
  mrent decimal(10,3) NOT NULL default '0.000',
  deposit decimal(10,3) NOT NULL default '0.000',
  c_startdate varchar(15) NOT NULL default '',
  c_enddate varchar(15) NOT NULL default '',
  PRIMARY KEY  (lsid)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

#
# Dumping data for table `leases`
#


# --------------------------------------------------------

#
# Table structure for table `leasetenants`
#

DROP TABLE IF EXISTS leasetenants;
CREATE TABLE leasetenants (
  lstnid int(11) NOT NULL auto_increment,
  lsid int(11) NOT NULL default '0',
  tnid int(11) NOT NULL default '0',
  PRIMARY KEY  (lstnid),
  KEY lsid (lsid),
  KEY tnid (tnid)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

#
# Dumping data for table `leasetenants`
#


# --------------------------------------------------------

#
# Table structure for table `personcontacts`
#

DROP TABLE IF EXISTS personcontacts;
CREATE TABLE personcontacts (
  pcid int(11) NOT NULL auto_increment,
  personid int(11) NOT NULL default '0',
  ctname varchar(100) NOT NULL default '',
  ctval varchar(100) NOT NULL default '',
  PRIMARY KEY  (pcid),
  KEY personid (personid),
  KEY ctname (ctname)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

#
# Dumping data for table `personcontacts`
#

INSERT INTO personcontacts VALUES (1, 4, 'Phone', '(519) 451-9081');
INSERT INTO personcontacts VALUES (3, 1, 'Phone', '(709) 438-2936');
INSERT INTO personcontacts VALUES (4, 3, 'Phone', '(519) 451-9081');
INSERT INTO personcontacts VALUES (5, 3, 'Phone', '(506) 364-9013');
INSERT INTO personcontacts VALUES (6, 4, 'Phone', '(519) 624-2362');
INSERT INTO personcontacts VALUES (7, 4, 'Phone', '(519) 465-6192');

# --------------------------------------------------------

#
# Table structure for table `persons`
#

DROP TABLE IF EXISTS persons;
CREATE TABLE persons (
  personid int(11) NOT NULL auto_increment,
  fname varchar(50) NOT NULL default '',
  lname varchar(50) default NULL,
  addr varchar(100) NOT NULL default '',
  city varchar(50) NOT NULL default '',
  prov varchar(50) NOT NULL default '',
  zip varchar(10) NOT NULL default '',
  country varchar(10) NOT NULL default 'Canada',
  PRIMARY KEY  (personid)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

#
# Dumping data for table `persons`
#

INSERT INTO persons VALUES (1, 'Shirley', 'Bischel', '3401 17 Ave SW', 'Calgary', 'AB', 'T3E0B5', 'Canada');
INSERT INTO persons VALUES (2, 'Chuck', 'Goldstein', '25 Bridle Rd , Penetanguishene', 'Penetanguishene', 'ON', 'L9M1J5', 'Canada');
INSERT INTO persons VALUES (3, 'Henry', 'Pauls', '561 Admiral Crt', 'London', 'ON', 'N5V4L3', 'Canada');
INSERT INTO persons VALUES (4, 'Linda', 'Hayes', '51 Brooklyn Rd', 'Cambridge', 'ON', 'N1R1C6', 'Canada');
INSERT INTO persons VALUES (5, 'Steven', 'Wilson', '5044 48 Ave ', 'Delta', 'BC', 'V4K1V8', 'Canada');

# --------------------------------------------------------

#
# Table structure for table `properties`
#

DROP TABLE IF EXISTS properties;
CREATE TABLE properties (
  prid int(11) NOT NULL auto_increment,
  addr varchar(50) NOT NULL default '',
  city varchar(20) NOT NULL default '',
  prov varchar(20) NOT NULL default '',
  country varchar(20) NOT NULL default 'Canada',
  zip varchar(8) NOT NULL default '',
  llid int(11) NOT NULL default '0',
  nrooms int(11) NOT NULL default '0',
  nparking int(11) NOT NULL default '0',
  prdesc text,
  unit varchar(50) default NULL,
  PRIMARY KEY  (prid),
  KEY llid (llid,nrooms)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

#
# Dumping data for table `properties`
#

INSERT INTO properties VALUES (1, '5220 Bradco Blvd', 'Mississauga', 'ON', 'Canada', 'L4W1G7', 2, 5, 2, '', '');
INSERT INTO properties VALUES (2, '2690 Mill Bay Rd 109', 'Mill Bay', 'BC', 'Canada', 'V0R2P1', 5, 3, 1, '', '');

# --------------------------------------------------------

#
# Table structure for table `provs`
#

DROP TABLE IF EXISTS provs;
CREATE TABLE provs (
  pvid int(11) NOT NULL auto_increment,
  pvname varchar(200) NOT NULL default '',
  pvzip varchar(5) NOT NULL default '',
  PRIMARY KEY  (pvid),
  KEY pvname (pvname),
  KEY pvzip (pvzip)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

#
# Dumping data for table `provs`
#

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

# --------------------------------------------------------

#
# Table structure for table `tenants`
#

DROP TABLE IF EXISTS tenants;
CREATE TABLE tenants (
  tnid int(11) NOT NULL auto_increment,
  personid int(11) NOT NULL default '0',
  PRIMARY KEY  (tnid),
  KEY personid (personid)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

#
# Dumping data for table `tenants`
#


# --------------------------------------------------------

#
# Table structure for table `users`
#

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  userid int(10) unsigned NOT NULL auto_increment,
  login varchar(20) NOT NULL default '',
  `password` varchar(60) NOT NULL default '',
  groupnames varchar(200) NOT NULL default 'users',
  PRIMARY KEY  (userid)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

#
# Dumping data for table `users`
#

INSERT INTO users VALUES (1, 'guest', 'f9aca5fa9b9376cb5a8705c29c2d2b5a', 'users');
INSERT INTO users VALUES (2, 'admin', 'f9aca5fa9b9376cb5a8705c29c2d2b5a', 'users|admins');
