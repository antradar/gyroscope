<?php
include 'stripe.inc.php';

function listsettings($ctx=null){
	if (isset($ctx)) $db=&$ctx->db; else global $db;
	
	$user=userinfo($ctx);
	$gsexpiry=intval($user['gsexpiry']);
	
	global $lang;
	global $stripe_config;
	global $enable_db_profiler;
	global $db_profiler;
	
	if (isset($ctx)) $manticore=&$ctx->manticore; else global $manticore;
	
	if (isset($stripe_config)) $stripe_pkey=$stripe_config['pkey_'.$stripe_config['mode']]; else $stripe_pkey='';
	
	gs_header($ctx,'listviewtitle', tabtitle(_tr('icon_settings')));
?>
<div class="section">
	<div class="listitem <?php if (isset($user['groups']['accounts'])) echo ' mobileonly';?>"><a onclick="ajxjs2('setaccountpass','accounts.js',function(){reloadtab('account','<?php tr('account_settings');?>','showaccount');addtab('account','<?php tr('account_settings');?>','showaccount');});return false;"><?php tr('account_settings');?></a></div>
	
	<?php	
	if (isset($user['groups']['accounts'])){
	?>
	<div class="listitem"><a onclick="ajxjs2('showuser','users.js');ajxjs2('setaccountpass','accounts.js');showview('core.users',1,null,null,null,null,true);"><?php tr('icon_accounts');?></a></div>	
	<?php
	}
	if (isset($manticore)&&isset($user['groups']['kbman'])){
	?>
	<div class="listitem"><a onclick="ajxjs2('kbman_showtopic','kbman.js');showview('core.kbman',1,1);">AI Knowledge Base <span class="botchatresolver_ func_topic_selector" style="display:inline-block;"></span></a></div>				
	<?php	
	}
	?>
		
	<?php
	
	
	
	if (isset($user['groups']['creditcards'])){
	?>
	
	<?php if ($gsexpiry!=0){?>	
	<div class="listitem"><a onclick="ajxjs(<?php jsflag('showgssubscription');?>,'gssubscriptions.js');showgssubscription();">Subscription</a></div>	
	<?php }?>
	
	<div class="listitem"><a onclick="ajxjs(<?php jsflag('addcreditcard');?>,'creditcards.js');addtab('creditcards','Credit Cards','showcreditcards',function(){stripe_init('<?php echo $lang;?>','<?php echo $stripe_pkey;?>');});">Credit Cards</a></div>
	
	
	<?php
	}
	

	if (isset($user['groups']['reportsettings'])){
	?>
	<div class="listitem"><a onclick="ajxjs2('showreportsetting','reportsettings.js');showview('core.reportsettings',1);"><?php tr('icon_reportsettings');?></a></div>	
	<?php
	}
	
	if (isset($user['groups']['systemplate'])||isset($user['groups']['systemplateuse'])){
	?>
	<div class="listitem"><a onclick="ajxjs2('showtemplatetype','templatetypes.js');showview('core.templatetypes',1);"><?php tr('icon_systemplates');?></a></div>	
	<?php
	}

	?>
	<div class="listitem"><a onclick="ajxjs2('showgsreplay','gsreplay.js');showview('core.gsreplays',1);">Replay Clips</a></div>		
	<?php
	/*
	if ($user['groups']['msdrive']){
	?>
	<div class="listitem"><a onclick="ajxjs(self.showmsfiles,'msgraph.js');showmsfiles();">MS Drive Explorer</a></div>		
	<?php
	}
	*/
	
	if (isset($user['groups']['chatsettings'])){
	?>
	<div class="listitem"><a onclick="ajxjs(<?php jsflag('showchatsettings');?>,'chats.js');addtab('chatsettings','Chat Settings','showchatsettings',null,null,{bingo:1});">Chat Settings</a></div>	
	<?php
	}
	
	if (isset($user['groups']['msgpipe'])||isset($user['groups']['msgpipeuse'])){

	?>
	<div class="listitem"><a onclick="ajxjs2('dashmsgpipes','msgpipes.js',function(){dashmsgpipes()});">Notification Lists</a></div>			
	<?php
	}
	
			
	if (isset($user['groups']['dbadmin'])){
	?>
	<div class="listitem" style="position:relative;">
		<a onclick="addtab('rptsqlcomp','SQL Compare','rptsqlcomp');"><?php tr('icon_sqlcompare');?></a>
		<span style="position:absolute;top:3px;right:70px;">
		<?php makehelp(null,'settings_sqlcompare','this tool is used to compare the structures of databases on both local and remote servers');?>
		</span>
	</div>
	<?php if (isset($enable_db_profiler)&&file_exists($db_profiler)&&file_exists('icl/gsdb_showquerysummary.inc.php')){?>
	<div class="listitem">
		<a onclick="addtab('querysummary','SQL Query Summary','gsdb_showquerysummary');">Query Summary
			<?php if ($enable_db_profiler){?>&nbsp;<span class="labelbutton">On</span><?php }?>
		</a>
	</div>	
	<?php
	}
	}
	
		
?>	
</div>
<?php
/*
?>
<script>
gid('tooltitle').innerHTML='<a><?php tr('icon_settings');?></a>';
</script>
<?php
*/

}