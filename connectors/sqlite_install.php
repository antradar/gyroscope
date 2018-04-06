<?php

include 'sql.php.sqlite';

$db=sql_get_db('gyroscope.sqlite3');

$query="
CREATE TABLE actionlog (
  alogid integer primary key,
  gsid integer,
  userid integer,
  logname text,
  logmessage text,
  rawobj text,
  logdate integer,
  sid integer,
  rectype text,
  recid integer,
  wssdone integer
)
";
sql_query($query,$db);

$query="create index logdate on actionlog(logdate)"; sql_query($query,$db);
$query="create index gsid1 on actionlog(gsid)"; sql_query($query,$db);
$query="create index userid on actionlog(userid)"; sql_query($query,$db);
$query="create index wssdone on actionlog(wssdone)"; sql_query($query,$db);

$query="
CREATE TABLE gss (
  gsid integer primary key,
  gsname text,
  stripecustomerid text,
  gsexpiry integer,
  gstier integer
)
";
sql_query($query,$db);

$query="create index gsname on gss(gsname)"; sql_query($query,$db);
$query="create index gsexpiry on gss(gsexpiry)"; sql_query($query,$db);

$query="insert into gss values (1,'Default Instance','',0,0)"; sql_query($query,$db);

$query="
CREATE TABLE users (
  userid integer primary key,
  gsid integer,
  login text,
  dispname text,
  active integer,
  virtualuser integer,
  password text,
  passreset integer,
  groupnames text,
  keyfilehash text,
  needkeyfile integer,
  usesms integer,
  smscell text,
  smscode text,
  certhash text,
  needcert integer,
  certname text  
)";
sql_query($query,$db);

$query="create unique index login on users(login)"; sql_query($query,$db);
$query="create index gsid2 on users(gsid)"; sql_query($query,$db);
$query="create index active on users(active)"; sql_query($query,$db);
$query="create index virtualuser on users(virtualuser)"; sql_query($query,$db);


$query="INSERT INTO users VALUES (101,1,'admin','Admin',1,0,'uSAsxyX3v44q3+/4Md7lzkhaNlIvTHFTMkZsN1lFRWVMRzB2UlloZTJqWHRjSG9mRDgybTI3U0xZUjhzYVhUeDlUZ2dLQ283QkUrVmExaEY=',0,'users|admins|reportsettings|systemplateuse|systemplate|accounts|dbadmin|upgrademods',null,0,0,'','',null,0,null)";
sql_query($query,$db);


$query="
CREATE TABLE templates (
  templateid integer primary key,
  templatename text,
  templatetypeid integer,
  templatetext text
)
";
sql_query($query,$db);

$query="create index templatetypeid on templates(templatetypeid)"; sql_query($query,$db);



$query="
CREATE TABLE templatetypes (
  templatetypeid integer primary key,
  gsid integer,
  templatetypename text,
  templatetypekey text,
  activetemplateid integer,
  templatetypegroup text,
  plugins text,
  classes text 
)
";

sql_query($query,$db);

$query="create index gsid3 on templatetypes(gsid)"; sql_query($query,$db);
$query="create index templatetypekey on templatetypes(templatetypekey)"; sql_query($query,$db);
$query="create index activetemplateid on templatetypes(activetemplateid)"; sql_query($query,$db);



$query="
CREATE TABLE templatevars (
  templatevarid integer primary key,
  templatevarname text,
  templatevardesc text,
  templatetypeid integer
)
";

sql_query($query,$db);



$query="
CREATE TABLE reports (
  reportid integer primary key,
  gsid integer,
  
  reportname_en text,
  reportgroup_en text,
  reportdesc_en text,

  reportname_de text,
  reportgroup_de text,
  reportdesc_de text,

  reportname_pt text,
  reportgroup_pt text,
  reportdesc_pt text,

  reportname_zh text,
  reportgroup_zh text,
  reportdesc_zh text,
      
  reportfunc text,
  reportkey text,
  reportgroupnames text,
  gyrosys integer
)
";

sql_query($query,$db);

$query="create index reportkey on reports(reportkey)"; sql_query($query,$db);
$query="create index reportname_en on reports(reportname_en)"; sql_query($query,$db);
$query="create index reportgroup_en on reports(reportgroup_en)"; sql_query($query,$db);

$query="create index reportname_de on reports(reportname_de)"; sql_query($query,$db);
$query="create index reportgroup_de on reports(reportgroup_de)"; sql_query($query,$db);

$query="create index reportname_pt on reports(reportname_pt)"; sql_query($query,$db);
$query="create index reportgroup_pt on reports(reportgroup_pt)"; sql_query($query,$db);

$query="create index reportname_zh on reports(reportname_zh)"; sql_query($query,$db);
$query="create index reportgroup_zh on reports(reportgroup_zh)"; sql_query($query,$db);

$query="INSERT INTO reports VALUES (1, 0, 'Activity Log', 'Security', 'This report is mostly used by system administrators for diagnostic purposes.', 'Aktivitätsprotokoll', 'Sicherheit', '', 'Registro de Atividade', '', 'admins', '1', '活动日志', '安全记录', '', 'actionlog', 'admins|reportsettings|systemplateuse|systemplate', 1)";
sql_query($query,$db);

