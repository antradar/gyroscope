<?php

function listsettings(){
	global $db;
	$user=userinfo();
	$gsexpiry=$user['gsexpiry']+0;

?>
<div class="section">
	<?	
	if (isset($user['groups']['accounts'])){
	?>
	<div class="listitem"><a onclick="ajxjs(self.showuser,'users_js.php');showview('core.users',1);"><?tr('icon_accounts');?></a></div>	
	<?
	} else {
	?>
	<div class="listitem"><a onclick="ajxjs(self.setaccountpass,'accounts.js');reloadtab('account','<?tr('account_settings');?>','showaccount');addtab('account','<?tr('account_settings');?>','showaccount');return false;"><?tr('account_settings');?></a></div>
	<?
	}
	
	if (isset($user['groups']['creditcards'])){
	?>
	<div class="listitem"><a onclick="ajxjs(self.addcreditcard,'creditcards.js');addtab('creditcards','Credit Cards','showcreditcards');">Credit Cards</a></div>
	
	<?if ($gsexpiry!=0){?>	
	<div class="listitem"><a onclick="ajxjs(self.showgssubscription,'gssubscriptions.js');showgssubscription();">Subscription</a></div>	
	<?}?>
	
	<?
	}
	

	if (isset($user['groups']['reportsettings'])){
	?>
	<div class="listitem"><a onclick="ajxjs(self.showreport,'reportsettings.js');showview('core.reportsettings',1);"><?tr('icon_reportsettings');?></a></div>	
	<?
	}
	
	if (isset($user['groups']['systemplate'])||isset($user['groups']['systemplateuse'])){
	?>
	<div class="listitem"><a onclick="showview('core.templatetypes',1);"><?tr('icon_systemplates');?></a></div>	
	<?
	}
			
	if (isset($user['groups']['dbadmin'])){
	?>
	<div class="listitem"><a onclick="addtab('rptsqlcomp','SQL Compare','rptsqlcomp');"><?tr('icon_sqlcompare');?></a></div>	
	<?
	}
	?>		
</div>
<script>
gid('tooltitle').innerHTML='<a><?tr('icon_settings');?></a>';
</script>
<?
}