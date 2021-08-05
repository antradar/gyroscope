# use MySQL client bridge on port 9004 to import
# mysql -udefault -p --port 9004 -h 127.0.0.1 --database gyrostart < gyrostart.clickhouse.sql

DROP TABLE IF EXISTS actionlog;
CREATE TABLE actionlog (
  alogid UUID default generateUUIDv4() comment 'identity',
  gsid UUID,
  userid UUID,
  logname String,
  logmessage String,
  rawobj String,
  logdate int,
  sid UInt64,
  rectype String,
  recid String ,
  wssdone UInt8 DEFAULT 0
)  engine=MergeTree()
primary key alogid
order by alogid;

DROP TABLE IF EXISTS gss;
CREATE TABLE gss (
  gsid  default generateUUIDv4() comment 'identity',
  gsname String,
  stripecustomerid String,
  gsexpiry UInt64 default 0,
  gstier UInt8 default 0,
  msgraphanchor String
) engine=MergeTree()
primary key gsid
order by gsid;

INSERT INTO gss (gsid,gsname,stripecustomerid,gsexpiry,gstier) VALUES ('00000000-0000-0000-0001-000000000001', 'Default Instance', '', 0, 0);

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  userid default generateUUIDv4() comment 'identity',
  gsid UUID,
  login String,
  dispname String,
  active UInt8 default 1,
  virtualuser UInt8 default 0,
  password String,
  passreset UInt8 default 0,
  groupnames String,
  keyfilehash String,
  needkeyfile UInt8 default 0,
  usesms UInt8 default 0,
  smscell String,  
  smscode String,  
  usega UInt8 default 0,
  gakey String,
  certhash String,
  needcert UInt8 default 0,
  certname String,
  msgraphtoken String,
  usegamepad UInt8 default 0
) engine=MergeTree()
primary key userid
order by userid;

INSERT INTO users (userid,gsid,login,dispname,active,virtualuser,password,passreset,groupnames,keyfilehash,needkeyfile,usesms,smscell,smscode,usega,gakey,certhash,needcert,certname,msgraphtoken,usegamepad) values ('00000000-0000-0000-0002-000000000101','00000000-0000-0000-0001-000000000001','admin','Admin',1,0,'$2y$12$MbnxI00l82cSYWgNCGxAUeVtTL3CmlZTT.NFuscnnYUk6cbBs7vH2',0,'devreports|admins|reportsettings|systemplateuse|systemplate|accounts|dbadmin|creditcards|msdrive|helpedit',null,0,0,'','',0,null,null,0,null,null,0);

DROP TABLE IF EXISTS templates;
CREATE TABLE templates (
  templateid UUID default generateUUIDv4() comment 'identity',
  templatename String,
  templatetypeid UUID,
  templatetext String
) engine=MergeTree()
primary key templateid
order by templateid;

DROP TABLE IF EXISTS templatetypes;
CREATE TABLE templatetypes (
  templatetypeid UUID default generateUUIDv4() comment 'identity',
  gsid UUID,
  templatetypename String,
  templatetypekey String,
  activetemplateid UUID,
  templatetypegroup String,
  plugins String,
  classes String 
) engine=MergeTree()
primary key templatetypeid
order by templatetypeid;

DROP TABLE IF EXISTS templatevars;
CREATE TABLE templatevars (
  templatevarid UUID default generateUUIDv4() comment 'identity',
  templatevarname String,
  templatevardesc String,
  templatetypeid UUID
) engine=MergeTree()
primary key templatevarid
order by templatevarid;

DROP TABLE IF EXISTS reports;
CREATE TABLE reports (
  reportid UUID default generateUUIDv4() comment 'identity',
  gsid UUID,
  reportname_en String,
  reportgroup_en String,
  reportdesc_en String,
  reportname_de String,
  reportgroup_de String,
  reportdesc_de String ,
  reportname_pt String ,
  reportgroup_pt String ,
  reportdesc_pt String ,
  reportname_zh String ,
  reportgroup_zh String ,
  reportdesc_zh String ,
  reportfunc String,
  reportkey String ,
  reportgroupnames String ,
  gyrosys UInt8 default 0
) engine=MergeTree()
primary key reportid
order by reportid;

INSERT INTO reports (reportid,gsid,reportname_en,reportgroup_en,reportdesc_en,reportname_de,reportgroup_de,reportdesc_de,reportname_pt,reportgroup_pt,reportdesc_pt,reportname_zh,reportgroup_zh,reportdesc_zh,reportfunc,reportkey,reportgroupnames,gyrosys) VALUES ('00000000-0000-0000-0006-000000000001', '00000000-0000-0000-0001-000000000001', 'Activity Log', 'Security', 'This report is mostly used by system administrators for diagnostic purposes.', 'Aktivitätsprotokoll', 'Sicherheit', '', 'Registro de Atividade', '', 'admins', '1', '????', '????', '', 'actionlog', 'admins|reportsettings|systemplateuse|systemplate', 0);

DROP TABLE IF EXISTS userhelpspots;
CREATE TABLE userhelpspots (
  uhid UUID default generateUUIDv4() comment 'identity',
  userid UUID,
  helptopic String
) engine=MergeTree()
primary key uhid
order by uhid;

drop table if exists helptopics;
create table helptopics(
helptopicid UUID default generateUUIDv4() comment 'identity',
helptopictitle String,
helptopickeywords String,
helptopiclevel UInt8 default 0,
helptopicsort UUID,
helptopictext String
) engine=MergeTree()
primary key helptopicid
order by helptopicid;

drop table if exists homedashreports;
create table homedashreports(
homedashreportid UUID default generateUUIDv4() comment 'identity',
userid UUID,
rptkey String,
rpttabkey String,
rptname String,
rpttitle String,
rptlink String
) engine=MergeTree()
primary key homedashreportid
order by homedashreportid;

