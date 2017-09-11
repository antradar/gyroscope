<?php

function listsettings(){
	global $db;
	$user=userinfo();

?>
<div class="section">
	<?	
	if (isset($user['groups']['accounts'])){
	?>
	<div class="listitem"><a onclick="ajxjs(self.showuser,'users_js.php');showview('core.users',1);">Accounts</a></div>	
	<?
	}

	if (isset($user['groups']['reportsettings'])){
	?>
	<div class="listitem"><a onclick="ajxjs(self.showreport,'reportsettings.js');showview('core.reportsettings',1);">Report Settings</a></div>	
	<?
	}
	
	if (isset($user['groups']['systemplate'])||isset($user['groups']['systemplateuse'])){
	?>
	<div class="listitem"><a onclick="showview('core.templatetypes',1);">System Templates</a></div>	
	<?
	}
			
	if (isset($user['groups']['dbadmin'])){
	?>
	<div class="listitem"><a onclick="addtab('rptsqlcomp','SQL Compare','rptsqlcomp');">SQL Compare</a></div>	
	<?
	}
	?>		
</div>
<script>
gid('tooltitle').innerHTML='<a>Settings</a>';
</script>
<?
}