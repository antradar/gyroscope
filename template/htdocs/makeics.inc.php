<?php

function makeics($guid,$domain,$fromemail,$guestemail,$start,$end,$title,$desc,$location){
	
	$tz=date_default_timezone_get();
	date_default_timezone_set('UTC');
		$dstart=date('Ymd\THis\Z',$start);
		$dend=date('Ymd\THis\Z',$end);
		$dnow=date('Ymd\THis\Z');
	date_default_timezone_set($tz);
	
	
	$rawics="
BEGIN:VCALENDAR
PRODID:-//Antradar Software//Antradar Calendar 2.2//EN
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:REQUEST
BEGIN:VEVENT
DTSTART:$dstart
DTEND:$dend
DTSTAMP:$dnow
ORGANIZER;CN=$fromemail:mailto:$fromemail
UID:invite$guid@$domain
ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN=$guestemail;X-NUM-GUESTS=0:mailto:$guestemail
CREATED:20121211T180837Z
DESCRIPTION:$desc
LAST-MODIFIED:20121212T180837Z
LOCATION:$location
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY:$title
TRANSP:OPAQUE
END:VEVENT
END:VCALENDAR	
";

	$ics='';
	$lines=explode("\n",$rawics);
	foreach ($lines as $line){
		$line=trim($line);
		if ($line=='') continue;
		$ics.=wordwrap($line,75, "\n ",true)."\n";
	}

	
		
	return $ics;
		
}