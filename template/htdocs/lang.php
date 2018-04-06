<?php
$deflang='en';

$langs=array(
	'en'=>'English',
//	'de'=>'Deutsch',
//	'fr'=>'Français',	
//	'pt'=>'Português',	
//	'ru'=>'Русский',
//	'zh'=>'中文',
//	'he'=>'עברית'
);

$lang=$deflang;
if (isset($_COOKIE['userlang'])) $lang=$_COOKIE['userlang'];
if (!in_array($lang,array_keys($langs))) $lang=$deflang;

include 'lang/dict.'.$lang.'.php';


function _tr($strkey,$reps=null){
	global $dict;
	
	$str=$dict[$strkey];
	if (!isset($str)) $str='['.$strkey.']';
	
	if (is_array($reps)){
		foreach ($reps as $k=>$v){
			$str=str_replace('%%'.$k.'%%',$v,$str);	
		} 	
	}
	
	return $str;
}

function tr($strkey,$reps=null){echo _tr($strkey,$reps);}

