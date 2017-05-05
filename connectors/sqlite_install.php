<?php

include 'sql.php.sqlite';

$db=sql_get_db('gyroscope.sqlite3');

$query="
CREATE TABLE actionlog (
  alogid integer primary key,
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

$query="create index userid on actionlog(userid)"; sql_query($query,$db);
$query="create index wssdone on actionlog(wssdone)"; sql_query($query,$db);


$query="
CREATE TABLE users (
  userid integer primary key,
  login text,
  dispname text,
  active integer,
  virtualuser integer,
  password text,
  passreset integer,
  groupnames text,
  certhash text,
  needcert integer,
  certname text  
)";
sql_query($query,$db);

$query="create unique index login on users(login)"; sql_query($query,$db);
$query="create index active on users(active)"; sql_query($query,$db);
$query="create index virtualuser on users(virtualuser)"; sql_query($query,$db);


$query="INSERT INTO users VALUES (101,'admin','Admin',1,0,'HZ4ddm+gYX44SXBQAT6UVNCpknAwMrUPJwHCFz28BPBSy4WRKBqNfW025Pvrd+PaCsWnBl/W/tXxDFU//4TjNGlc+14olF8CjALw1ZaZLet8XPL9ytSpZanzr/C/KvyW',0,'users|admins|reportsettings|systemplateuse|systemplate|accounts|dbadmin|upgrademods',null,0,null)";
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
  templatetypename text,
  templatetypekey text,
  activetemplateid integer,
  templatetypegroup text,
  plugins text,
  classes text 
)
";

sql_query($query,$db);

$query="create unique index templatetypekey on templatetypes(templatetypekey)"; sql_query($query,$db);
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
  reportname text,
  reportgroup text,
  reportdesc text,
  reportfunc text,
  reportkey text,
  reportgroupnames text,
  gyrosys integer
)
";

sql_query($query,$db);

$query="create unique index reportkey on reports(reportkey)"; sql_query($query,$db);
$query="create index reportname on reports(reportname)"; sql_query($query,$db);
$query="create index reportgroup on reports(reportgroup)"; sql_query($query,$db);


$query="INSERT INTO reports VALUES (1, 'Activity Log', 'Security', 'This report is mostly used by system administrators for diagnostic purposes.',NULL,'actionlog', 'admins', 1)";
sql_query($query,$db);

