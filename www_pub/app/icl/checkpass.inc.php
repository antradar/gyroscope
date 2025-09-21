<?php

include 'passtest.php';

function checkpass(){
	$pass=SQET('pass');
	
	$res=passtest($pass);
	
	$colors=array('#ffab00','#ddeedd','#99ff99');
	
	$color=$colors[$res['grade']];
	if ($res['found']>0) $color='#ff8080';
	
	$reqs=$res['reqs'];
	
	header('passcolor:'.$color);
	
	//if ($res['grade']==0) echo 'too weak';
	//if ($res['found']>0) echo ', '.number_format($res['found']).' known compromise'.($res['found']==1?'':'s');
	
	foreach ($reqs as $k=>$v){
	?>
	<acronym title="<?php echo $v;?>"><img src="imgs/t.gif" class="passwarn passwarn-<?php echo $k;?>"></acronym>
	<?php	
	}
	
}