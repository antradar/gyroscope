DROP TABLE IF EXISTS actionlog;
drop sequence if exists seq_actionlog;
create sequence seq_actionlog;

CREATE TABLE actionlog (
  uid_alogid uuid primary key default gen_random_uuid(),
  alogid int default nextval('seq_actionlog'),
  gsid int NOT NULL,
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
drop sequence if exists seq_gss;
create sequence seq_gss;

CREATE TABLE gss (
  uid_gsid uuid primary key default gen_random_uuid(),
  gsid int default nextval('seq_gss'),
  gsname varchar(255) NOT NULL,
  stripecustomerid varchar(255),
  gsexpiry bigint  DEFAULT '0',
  gstier smallint  NOT NULL DEFAULT '0',
  msgraphanchor text
) ;

create index gsname on gss(gsname);
create index gsexpiry on gss(gsexpiry);



INSERT INTO gss (gsid,gsname,stripecustomerid,gsexpiry,gstier) VALUES (1, 'Default Instance', '', 0, 0);
select setval('seq_gss',2,false);


DROP TABLE IF EXISTS users;

drop sequence if exists seq_users;
create sequence seq_users;

CREATE TABLE users (
  uid_userid uuid primary key default gen_random_uuid(),
  userid int default nextval('seq_users'),
  gsid int NOT NULL,
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
  msgraphtoken text,
  usegamepad smallint default '0'
) ;

create unique index login on users (login);
create index active on users (active);
create index virtualuser on users (virtualuser);
create index users_gsid on users (gsid);

INSERT INTO users (userid,gsid,login,dispname,active,virtualuser,password,passreset,groupnames,keyfilehash,needkeyfile,usesms,smscell,smscode,usega,gakey,certhash,needcert,certname,msgraphtoken,usegamepad) values (101,1,'admin','Admin',1,0,'$2y$12$MbnxI00l82cSYWgNCGxAUeVtTL3CmlZTT.NFuscnnYUk6cbBs7vH2',0,'devreports|admins|reportsettings|systemplateuse|systemplate|accounts|dbadmin|creditcards|msdrive|helpedit',null,0,0,'','',0,null,null,0,null,null,0);
select setval('seq_users',102,false);

DROP TABLE IF EXISTS templates;

drop sequence if exists seq_templates;
create sequence seq_templates;

CREATE TABLE templates (
  uid_templateid uuid primary key default gen_random_uuid(),
  templateid int default nextval('seq_templates'),
  templatename varchar(255),
  templatetypeid int NOT NULL,
  templatetext text
) ;

create index templatetypeid on templates (templatetypeid);


DROP TABLE IF EXISTS templatetypes;

drop sequence if exists seq_templatetypes;
create sequence seq_templatetypes;

CREATE TABLE templatetypes (
  uid_templatetypeid uuid primary key default gen_random_uuid(),
  templatetypeid int default nextval('seq_templatetypes'),
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

drop sequence if exists seq_templatevars;
create sequence seq_templatevars;

CREATE TABLE templatevars (
  uid_templatevarid uuid primary key default gen_random_uuid(),
  templatevarid int default nextval('seq_templatevars'),
  templatevarname varchar(255),
  templatevardesc varchar(255),
  templatetypeid int
) ;


    
DROP TABLE IF EXISTS reports;
drop sequence if exists seq_reports;
create sequence seq_reports;

CREATE TABLE reports (
  uid_reportid uuid primary key default gen_random_uuid(),
  reportid int default nextval('seq_reports'),
  gsid int  NOT NULL,
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


INSERT INTO reports (reportid,gsid,reportname_en,reportgroup_en,reportdesc_en,reportname_de,reportgroup_de,reportdesc_de,reportname_pt,reportgroup_pt,reportdesc_pt,reportname_zh,reportgroup_zh,reportdesc_zh,reportfunc,reportkey,reportgroupnames,gyrosys) VALUES (1, 1, 'Activity Log', 'Security', 'This report is mostly used by system administrators for diagnostic purposes.', 'Aktivitätsprotokoll', 'Sicherheit', '', 'Registro de Atividade', '', 'admins', '1', '????', '????', '', 'actionlog', 'admins|reportsettings|systemplateuse|systemplate', 0);
select setval('seq_reports',2,false);


DROP TABLE IF EXISTS userhelpspots;
drop sequence if exists seq_userhelpspots;
create sequence seq_userhelpspots;

CREATE TABLE userhelpspots (
  uid_uhid uuid primary key default gen_random_uuid(),
  uhid int default nextval('seq_userhelpspots'),
  userid int  NOT NULL,
  helptopic varchar(255)
) ;

create index userhelpspots_userid on userhelpspots(userid);

drop table if exists helptopics;
drop sequence if exists seq_helptopics;
create sequence seq_helptopics;

create table helptopics(
uid_helptopicid uuid primary key default gen_random_uuid(),
helptopicid int default nextval('seq_helptopics'),
helptopictitle varchar(255),
helptopickeywords varchar(255),
helptopiclevel smallint default 0,
helptopicsort int,
helptopictext text
);

create index helptopictitle on helptopics(helptopictitle);
create index helptopickeywords on helptopics(helptopickeywords);
create index helptopicsort on helptopics(helptopicsort);

drop table if exists homedashreports;
drop sequence if exists seq_homedashreports;
create sequence seq_homedashreports;

create table homedashreports(
uid_homedashreportid uuid primary key default gen_random_uuid(),
homedashreportid int default nextval('seq_homedashreports'),
userid int not null,
rptkey varchar(255),
rpttabkey varchar(255),
rptname varchar(255),
rpttitle varchar(255),
rptlink varchar(255)
);

create index userid on homedashreports(userid);
create index rptname on homedashreports(rptname);

     
