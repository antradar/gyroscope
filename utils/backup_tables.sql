-- MySQL dump 10.13  Distrib 5.7.37, for Linux (x86_64)
--
-- Host: localhost    Database: gyrostart
-- ------------------------------------------------------
-- Server version	5.7.37-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `backups`
--

DROP TABLE IF EXISTS `backups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backups` (
  `backupid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `backupdate` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`backupid`),
  KEY `backupdate` (`backupdate`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `backuptables`
--

DROP TABLE IF EXISTS `backuptables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backuptables` (
  `tableid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `backupid` bigint(20) unsigned NOT NULL,
  `tablekey` varchar(80) DEFAULT NULL,
  `tablemodified` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`tableid`),
  KEY `backupid` (`backupid`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `backuptapes`
--

DROP TABLE IF EXISTS `backuptapes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `backuptapes` (
  `tapeid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `backupid` bigint(20) unsigned NOT NULL,
  `tapekey` varchar(80) DEFAULT NULL,
  `tapedate` bigint(20) unsigned DEFAULT NULL,
  `tapemin` bigint(20) unsigned DEFAULT NULL,
  `tapemax` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`tapeid`),
  KEY `backupid` (`backupid`),
  KEY `tapedate` (`tapedate`),
  KEY `tapemin` (`tapemin`),
  KEY `tapemax` (`tapemax`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


