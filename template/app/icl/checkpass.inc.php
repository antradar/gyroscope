<?php

include 'passtest.php';

function checkpass(){
	$pass=SQET('pass');
	
	$res=passtest($pass);
	
	$colors=array('#ffab00','#ddeedd','#99ff99');
	
	$color=$colors[$res['grade']];
	if ($res['found']>0) $color='#ff8080';
	
	header('passcolor:'.$color);
	
	if ($res['grade']==0) echo 'too weak';
	if ($res['found']>0) echo ', '.number_format($res['found']).' known compromise'.($res['found']==1?'':'s');
	
	
}