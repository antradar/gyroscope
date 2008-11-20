# phpMyAdmin SQL Dump
# version 2.5.5-pl1
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Oct 23, 2008 at 03:17 PM
# Server version: 5.0.51
# PHP Version: 4.4.2
# 
# Database : `gyrostart`
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
