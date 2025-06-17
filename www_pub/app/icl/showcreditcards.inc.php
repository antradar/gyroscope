<?php

include_once 'stripe.inc.php';

function showcreditcards($ctx=null){
	if (isset($ctx)) $db=$ctx->db; else global $db;
	global $stripe_name;
		
	global $lang;
	global $stripe_config;

	if (isset($stripe_config)) $stripe_pkey=$stripe_config['pkey_'.$stripe_config['mode']]; else $stripe_pkey='';
		
	$user=userinfo($ctx);
	$gsid=$user['gsid'];
		
	$query="select * from ".TABLENAME_GSS." where ".COLNAME_GSID."=?";
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
		$query="update ".TABLENAME_GSS." set stripecustomerid=? where ".COLNAME_GSID."=?";
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

	$customer=stripe_member($customerid);	
	$defcardid=$customer['default_source']??'';

	$res=stripe_membercards($customerid);
	$cards=$res['data'];
	
	//echo '<pre>'; print_r($cards); echo '</pre>';	
	
?>
<div class="section">

	<div class="sectiontitle">Stored Credit Cards</div>
		
	<div class="infobox">
		Your payment information is securely stored with Stripe.<br>
		<?php if (isset($stripe_name)&&$stripe_name!=''){?>
		Credit card charges will appear as <b><?php echo $stripe_name;?></b>.
		<?php }?>
	</div>

	<?php if (count($cards)>0){?>
	
	

	<div class="stable">
	<table class="subtable">
	<?php

	foreach ($cards as $card_){
		$card=$card_['card'];
		
		$default=0;
		$cardid=$card_['id'];
		if ($defcardid=='') $defcardid=$cardid;
		if ($defcardid==$cardid) $default=1;
		//$cardname=$card['name'];
		$brand=$card['brand'];
		$expmon=$card['exp_month'];
		$expyear=$card['exp_year'];
		$last4=$card['last4'];
		
	?>
	<tr>
	<td><?php echo strtoupper($brand);?></td>
	<td><?php echo $last4;?></td>
	<td><?php echo $expmon.'/'.$expyear;?></td>
	<td>
	<?php if ($default){?>
	        <span class="labelbutton">default</span>
	<?php }else{?>
	        <a class="hovlink" onclick="setdefaultcreditcard('<?php echo $cardid;?>','<?php echo $lang;?>','<?php echo $stripe_pkey;?>','<?php emitgskey('setdefaultcreditcard_'.$cardid,'',$ctx);?>');">set as default</a>
	<?php }?>
	</td>
	<td>&nbsp;</td>
	<td>
		<a href=# onclick="delcreditcard('<?php echo $cardid;?>','<?php echo $lang;?>','<?php echo $stripe_pkey;?>','<?php emitgskey('delcreditcard_'.$cardid);?>','',$ctx);return false;"><img src="imgs/t.gif" class="img-del"></a>
	</td>
	</tr>
	<?php
	}//foreach
	?>
	</table>
	</div><!-- stable -->
	<?php
	
	}//has cards

	?>
	
	<div class="clear" style="padding-top:20px;"></div>
	
	<div class="col">
	
	<div class="inputrow">
		<div class="formlabel">Add a Card:</div>
		<div class="inplong" style="border-style:solid;border-width:1px;">
			<div id="ccnum"></div>
		</div>
		<div class="clear"></div>
	</div>	
	<div class="inputrow">
	<div class="formlookup">
		<button onclick="addcreditcard('<?php echo $lang;?>','<?php echo $stripe_pkey;?>','<?php emitgskey('addcreditcard','',$ctx);?>');">Add Card</button>
	</div>
	</div>
	
	</div><!-- col -->
	<div class="clear"></div>
	
</div>
<?php	
		
}