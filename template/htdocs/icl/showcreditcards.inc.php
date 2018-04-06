<?php

include_once 'stripe.inc.php';

function showcreditcards(){
	global $db;
	
	$user=userinfo();
	$gsid=$user['gsid']+0;
		
	$query="select * from gss where gsid=$gsid";
	$rs=sql_query($query,$db);
	if (!$myrow=sql_fetch_assoc($rs)) apperror('Invalid GS instance');
	
	$customerid=$myrow['stripecustomerid'];
	
	if ($customerid==''){
	?>
	<div class="section">
	<div class="warnbox">
	Config error: the Stripe Customer ID is missing for this Gyroscope instance.
	</div>
	</div>
	<?
		return;	
	}
	
	if (!is_callable('stripe_member')){
	?>
	<div class="section">
	<div class="warnbox">
	Config error: Stripe library (stripe.inc.php) is missing.
	</div>
	</div>
	<?
		return;	
		
	}

	$res=stripe_member($customerid);
	$cards=$res['sources']['data'];
	
?>
<div class="section">
	<div class="sectiontitle">Stored Cards</div>

	<?if (count($cards)>0){?>

	<div class="listitem">
	<?
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
	<?echo $cardname;?> <?echo $brand;?> <?echo $last4;?>
	<?echo $expmon.'/'.$expyear;?>
	<?if ($default){?>
	        <span class="labelbutton">default</span>
	<?}else{?>
	        <a class="hovlink" onclick="setdefaultcreditcard('<?echo $cardid;?>');">set as default</a>
	<?}?>
		&nbsp; &nbsp; <a href=# onclick="delcreditcard('<?echo $cardid;?>');return false;"><img src="imgs/t.gif" class="img-del"></a>
	</div>
	<?
	}//foreach
	?>
	</div><!-- subsection -->
	<?
	
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
		<?for ($i=1;$i<=12;$i++){
			$di=str_pad($i,2,'0',STR_PAD_LEFT);
		?>
		<option value="<?echo $di?>"><?echo $di;?></option>
		<?}?>
		</select>
		<span class="irtext before">/</span>
		<select class="inp" id="expyear">
		<?
		$baseyear=date('Y');
		for ($i=0;$i<15;$i++){
			$y=$baseyear+$i;
		?>
		<option value="<?echo $y;?>"><?echo $y;?></option>
		<?		
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
		<button onclick="addcreditcard();">Add Card</button>
	</div>
	</div>
	
</div>
<?	
		
}