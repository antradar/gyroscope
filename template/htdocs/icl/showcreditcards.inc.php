<?php

include_once 'stripe.inc.php';

function showcreditcards(){
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid'];
		
	$query="select * from gss where gsid=?";
	$rs=sql_prep($query,$db,$gsid);
	if (!$myrow=sql_fetch_assoc($rs)) apperror('Invalid GS instance');
	
	$customerid=$myrow['stripecustomerid'];
	
	if ($customerid==''){
	?>
	<div class="section">
	<div class="warnbox">
	Config error: the Stripe Customer ID is missing for this Gyroscope instance.
	</div>
	</div>
	<?php

	/*
		//auto recovery for switching from test to live tokens
		$res=stripe_addmember($gsid);
		$customerid=$res['id'];
		$query="update gss set stripecustomerid=? where gsid=?";
		sql_prep($query,$db,array($customerid,$gsid));
	*/
		return;	
	}
	
	if (!is_callable('stripe_member')){
	?>
	<div class="section">
	<div class="warnbox">
	Config error: Stripe library (stripe.inc.php) is missing.
	</div>
	</div>
	<?php
		return;	
		
	}

	$res=stripe_member($customerid);
	$cards=$res['sources']['data'];
	
?>
<div class="section">
	<div class="sectiontitle">Stored Cards</div>

	<?php if (count($cards)>0){?>

	<div class="listitem">
	<?php
	$defcardid=$res['default_source'];
	foreach ($cards as $card){
		$default=0;
		$cardid=$card['id'];
		if ($defcardid=='') $defcardid=$cardid;
		if ($defcardid==$cardid) $default=1;
		$cardname=$card['name'];
		$brand=$card['brand'];
		$expmon=$card['exp_month'];
		$expyear=$card['exp_year'];
		$last4=$card['last4'];
	?>
	<div class="inputrow">
	<?php echo $cardname;?> <?php echo $brand;?> <?php echo $last4;?>
	<?php echo $expmon.'/'.$expyear;?>
	<?php if ($default){?>
	        <span class="labelbutton">default</span>
	<?php }else{?>
	        <a class="hovlink" onclick="setdefaultcreditcard('<?php echo $cardid;?>','<?php emitgskey('setdefaultcreditcard_'.$cardid);?>');">set as default</a>
	<?php }?>
		&nbsp; &nbsp; <a href=# onclick="delcreditcard('<?php echo $cardid;?>','<?php emitgskey('delcreditcard_'.$cardid);?>');return false;"><img src="imgs/t.gif" class="img-del"></a>
	</div>
	<?php
	}//foreach
	?>
	</div><!-- subsection -->
	<?php
	
	}//has cards

	?>
	<div class="inputrow" style="padding-top:20px;"><b>Add a New Card:</b></div>
	<div class="inputrow">
		<div class="formlabel">Name on Card:</div>
		<input class="inp" id="ccname" autocomplete="off">
		<div class="clear"></div>
	</div>
	<div class="inputrow">
		<div class="formlabel">Card Number:</div>
		<input class="inp" id="ccnum" autocomplete="off">
		<div class="clear"></div>
	</div>
	<div class="inputrow">
		<div class="formlabel">Expiry:</div>
		<select class="inp" id="expmon">
		<?php for ($i=1;$i<=12;$i++){
			$di=str_pad($i,2,'0',STR_PAD_LEFT);
		?>
		<option value="<?php echo $di?>"><?php echo $di;?></option>
		<?php }?>
		</select>
		<span class="irtext before">/</span>
		<select class="inp" id="expyear">
		<?php
		$baseyear=date('Y');
		for ($i=0;$i<15;$i++){
			$y=$baseyear+$i;
		?>
		<option value="<?php echo $y;?>"><?php echo $y;?></option>
		<?php		
		}
		?>
		</select>
		<div class="clear"></div>
	</div>
	<div class="inputrow">
		<div class="formlabel">CVC:</div>
		<input class="inpshort" id="ccv" autocomplete="off">
		<div class="clear"></div>
	</div>
	
	<div class="inputrow">
	<div class="formlookup">
		<button onclick="addcreditcard('<?php emitgskey('addcreditcard');?>');">Add Card</button>
	</div>
	</div>
	
</div>
<?php	
		
}