<?php
global $deflang;
global $langs;
global $lang;

global $dict;
global $dict_mons;
global $dict_wdays;
global $dict_weekdays;
global $dict_dir;
global $helpspots;

$deflang='en';

$langs=array(
	'en'=>'English',
//	'de'=>'Deutsch',
//	'fr'=>'Français',	
//	'pt'=>'Português',	
//	'ru'=>'???????',
//	'zh'=>'??',
//	'he'=>'?????',
//	'ar'=>'????',
);

// when enabling a new language, copy the files in the langpack (not part of core download) folder to the lang folder

$lang=$deflang;
if (isset($_COOKIE['userlang'])) $lang=$_COOKIE['userlang'];
if (!in_array($lang,array_keys($langs))) $lang=$deflang;

include 'lang/dict.'.$lang.'.php';

if (!is_callable('_tr')){
	function _tr($strkey,$reps=null){
		global $dict;
		
		$str=isset($dict[$strkey])?$dict[$strkey]:'['.$strkey.']';
		
		if (is_array($reps)){
			foreach ($reps as $k=>$v){
				$str=str_replace('%%'.$k.'%%',$v,$str);	
			} 	
		}
		
		return $str;
	}
}

if (!is_callable('tr')){
	function tr($strkey,$reps=null){echo _tr($strkey,$reps);}
}
