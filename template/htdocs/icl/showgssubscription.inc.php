<?php

function showgssubscription(){
	global $db;
	
	$now=time();
	$user=userinfo();
	$gsexpiry=intval($user['gsexpiry']);
	$gstier=intval($user['gstier']);
	$dexpiry='<em>never</em>';

	if ($gsexpiry>0) $dexpiry=date('Y-n-j g:ia',$gsexpiry);
?>
<div class="section">
	<div class="sectiontitle">Subscription</div>
	
	<?php
	if ($gsexpiry!=0&&$gsexpiry<$now){
	?>
	<div class="warnbox">
	Your subscription has expired. Functionality of this application is reduced until a payment is arranged.
	</div>
	<?php	
	}
	?>
	
	<div class="inputrow">
		<div class="formlabel">
			Expiry: <?php echo $dexpiry;?>
		</div>
	</div>
	
</div>
<?php		
}