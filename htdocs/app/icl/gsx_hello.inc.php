<?php

// this function is a typical C in an LCHH construct

function gsx_hello($msg=null,$delay=null){
	
	return; //uncomment this to see gsx in action
	
	//the variable binding here is just an example
	
	if (!isset($msg)) $msg=SQET('msg');
	if (!isset($delay)) $delay=QETVAL('delay');
	
	
	//usleep($delay*1000*1000);
	
	//this should make the CPU sweat a bit
	
	$ta=microtime(1);	
	password_hash(rand(1000,9999).time(),PASSWORD_DEFAULT,array('cost'=>16));
	$tb=microtime(1);
	$delay=$tb-$ta;
	
?>
	<div class="listitem">
		<?php echo htmlspecialchars($msg);?> &nbsp; <em>(<?php echo $delay;?> sec)</em>
	</div>
<?php
}


