<?
$ua=$_SERVER['HTTP_USER_AGENT'];
$rhd='';
if (preg_match('/iphone/i',$ua)||preg_match('/ipad/i',$ua)||preg_match('/Android; Mobile/i',$ua)) $rhd='_hd';
if (preg_match('/iphone/i',$ua)||preg_match('/ipad/i',$ua)) $retina=1;
