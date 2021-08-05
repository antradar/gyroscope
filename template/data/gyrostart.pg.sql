DROP TABLE IF EXISTS actionlog;

CREATE TABLE actionlog (
  alogid serial primary key,
  gsid bigint NOT NULL,
  userid int,
  logname varchar(255) DEFAULT NULL,
  logmessage varchar(255),
  rawobj text,
  logdate int DEFAULT NULL,
  sid int,
  rectype varchar(255),
  recid int ,
  wssdone smallint  NOT NULL DEFAULT 0
) ;

create index logdate on actionlog(logdate);
create index userid on actionlog(userid);
create index wssdone on actionlog(wssdone);
create index actionlog_gsid on actionlog(gsid);

DROP TABLE IF EXISTS gss;
CREATE TABLE gss (
  gsid serial primary key,
  gsname varchar(255) NOT NULL,
  stripecustomerid varchar(255),
  gsexpiry bigint  DEFAULT '0',
  gstier smallint  NOT NULL DEFAULT '0',
  msgraphanchor text
) ;

create index gsname on gss(gsname);
create index gsexpiry on gss(gsexpiry);



INSERT INTO gss VALUES (1, 'Default Instance', '', 0, 0,null);
alter sequence gss_gsid_seq restart with 2;


DROP TABLE IF EXISTS users;

CREATE TABLE users (
  userid serial primary key,
  gsid bigint  NOT NULL,
  login varchar(255) NOT NULL DEFAULT '',
  dispname varchar(255) NOT NULL DEFAULT '',
  active smallint  NOT NULL DEFAULT '1',
  virtualuser smallint  NOT NULL DEFAULT '0',
  password varchar(255) NOT NULL DEFAULT '',
  passreset smallint  NOT NULL DEFAULT '0',
  groupnames text,
  keyfilehash varchar(255) DEFAULT NULL,
  needkeyfile smallint   DEFAULT '0',
  usesms smallint   DEFAULT '0',
  smscell varchar(255)  DEFAULT '',  
  smscode varchar(255)  DEFAULT '',  
  usega smallint   DEFAULT '0',
  gakey varchar(255) DEFAULT NULL,
  certhash varchar(255) DEFAULT NULL,
  needcert smallint   DEFAULT '0',
  certname varchar(255) DEFAULT NULL,
  msgraphtoken text
) ;

create unique index login on users (login);
create index active on users (active);
create index virtualuser on users (virtualuser);
create index users_gsid on users (gsid);


INSERT INTO users VALUES (101,1,'admin','Admin',1,0,'$2y$12$MbnxI00l82cSYWgNCGxAUeVtTL3CmlZTT.NFuscnnYUk6cbBs7vH2',0,'devreports|admins|reportsettings|systemplateuse|systemplate|accounts|dbadmin|creditcards|msdrive',null,0,0,'','',0,null,null,0,null,null);
alter sequence users_userid_seq restart with 102;

DROP TABLE IF EXISTS templates;
CREATE TABLE templates (
  templateid serial primary key,
  templatename varchar(255),
  templatetypeid int NOT NULL,
  templatetext text
) ;

create index templatetypeid on templates (templatetypeid);


DROP TABLE IF EXISTS templatetypes;
CREATE TABLE templatetypes (
  templatetypeid serial primary key,
  gsid bigint  NOT NULL,
  templatetypename varchar(255),
  templatetypekey varchar(255) DEFAULT NULL,
  activetemplateid bigint DEFAULT NULL,
  templatetypegroup varchar(255),
  plugins varchar(255),
  classes varchar(255) 
) ;

create index templatetypekey on templatetypes(templatetypekey);
create index activetemplateid on templatetypes(activetemplateid);
create index templatetypes_gsid on templatetypes(gsid);

DROP TABLE IF EXISTS templatevars;
CREATE TABLE templatevars (
  templatevarid serial primary key,
  templatevarname varchar(255),
  templatevardesc varchar(255),
  templatetypeid bigint
) ;


    
DROP TABLE IF EXISTS reports;
CREATE TABLE reports (
  reportid serial primary key,
  gsid bigint  NOT NULL,
  reportname_en varchar(255) DEFAULT NULL,
  reportgroup_en varchar(255) DEFAULT NULL,
  reportdesc_en text,
  reportname_de varchar(255),
  reportgroup_de varchar(255),
  reportdesc_de text ,
  reportname_pt varchar(255) ,
  reportgroup_pt varchar(255) ,
  reportdesc_pt text ,
  reportname_zh varchar(255) ,
  reportgroup_zh varchar(255) ,
  reportdesc_zh text ,
  reportfunc varchar(255) DEFAULT NULL,
  reportkey varchar(255) ,
  reportgroupnames varchar(255) ,
  gyrosys smallint  NOT NULL DEFAULT '0'
) ;

create index reportkey on reports (reportkey);
create index reportname_en on reports (reportname_en);
create index reportname_de on reports (reportname_de);
create index reportname_pt on reports (reportname_pt);
create index reportname_zh on reports (reportname_zh);
create index reportgroup_en on reports (reportgroup_en);
create index reportgroup_de on reports (reportgroup_de);
create index reportgroup_pt on reports (reportgroup_pt);
create index reportgroup_zh on reports (reportgroup_zh);
create index gsid on reports (gsid);


INSERT INTO reports VALUES (1, 1, 'Activity Log', 'Security', 'This report is mostly used by system administrators for diagnostic purposes.', 'Aktivitätsprotokoll', 'Sicherheit', '', 'Registro de Atividade', '', 'admins', '1', '????', '????', '', 'actionlog', 'admins|reportsettings|systemplateuse|systemplate', 0);
alter sequence reports_reportid_seq restart with 2;

DROP TABLE IF EXISTS userhelpspots;
CREATE TABLE userhelpspots (
  uhid serial primary key,
  userid int  NOT NULL,
  helptopic varchar(255)
) ;

create index userhelpspots_userid on userhelpspots(userid);

drop table if exists helptopics;
create table helptopics(
helptopicid serial primary key,
helptopictitle varchar(255),
helptopickeywords varchar(255),
helptopiclevel smallint default 0,
helptopicsort int unsigned,
helptopictext text
);

create index helptopictitle on helptopics(helptopictitle);
create index helptopickeywords on helptopics(helptopickeywords);
create index helptopicsort on helptopics(helptopicsort);

drop table if exists homedashreports;

create table homedashreports(
homedashreportid serial primary key,
userid int unsigned not null,
rptkey varchar(255),
rpttabkey varchar(255),
rptname varchar(255),
rpttitle varchar(255),
rptlink varchar(255)
);

create index userid on homedashreorts(userid);
create index rptname on homedashreports(rptname);

     
