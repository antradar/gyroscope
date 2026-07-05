<?php

function listusermcpkeys($ctx=null){
	$user=userinfo($ctx);

	if (isset($ctx)) $db=&$ctx->db; else global $db;

	$userid=$user['userid'];
	
	$query="select * from mcpkeys where userid=?";
	$rs=sql_prep($query,$db,$userid);
	
	$haskey=0;
	
	while ($myrow=sql_fetch_assoc($rs)){
		$mcpkeyid=$myrow['mcpkeyid'];
		$mcpkey=$myrow['mcpkey'];
		$haskey=1;
	?>
	<div class="inputrow">
		<input id="mcpkey_<?php echo $mcpkeyid;?>" class="inpmed" type="password" value="<?php echo htmlspecialchars($mcpkey);?>">
		<img class="passtoggle" src="imgs/t.gif" onclick="togglepass(this,'mcpkey_<?php echo $mcpkeyid;?>');">
		&nbsp; &nbsp; <a onclick="ajxpgn('usermcpkeys_<?php echo $userid;?>',document.appsettings.codepage+'?cmd=delusermcpkey&mcpkeyid=<?php echo $mcpkeyid;?>',0,0,null,null,null,0,'<?php emitgskey('delusermcpkey_'.$mcpkeyid);?>');"><img src="imgs/t.gif" class="img-del"></a>
	</div>
	<?php		
	}//while
	
	?>
	<div class="inputrow">
		<input class="inpmed" disabled> 
		&nbsp; <a class="recadder" onclick="ajxpgn('usermcpkeys_<?php echo $userid;?>',document.appsettings.codepage+'?cmd=addusermcpkey',0,0,null,null,null,0,'<?php emitgskey('addusermcpkey_'.$userid);?>');"><img src="imgs/t.gif" class="img-addrec"></a>
	</div>
	<?php
	
	if ($haskey){
	?>
	<div class="stable">
	<div class="infobox">
		Link Style:<br>
		https://#mcp-server-domain#/app/mcp.php?mcp_user_id=<?php echo $userid;?>&mcp_user_auth=#mcp_key#
		<br><br>
		Header Style:
		https://#mcp-server-domain#/app/mcp.php<br>
		MCP_USER_ID: <?php echo $userid;?><br>
		MCP_USER_AUTH: #mcp_key#
	</div>
	</div>
	<?php	
	}
		
}
