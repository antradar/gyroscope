<?php

include 'models/user.meta.php';

function listwelcome(){
	global $toolbaritems;
	global $uiconfig;
	global $codepage;

	$user=userinfo();

	$userid=$user['userid'];
	$gsid=$user['gsid'];

	$usermeta=user_meta(null,$userid,$gsid);
	$quicklist=isset($usermeta['quicklist'])&&$usermeta['quicklist']?1:0;

	$dispname=$user['dispname'];

	// Determine config sources based on iphone mode
	// When iphone=1, use mobile_landscape config for toolbar_position, vertical_nav_persist, etc.
	$iphone=isset($_GET['iphone'])&&intval($_GET['iphone'])==1;

	$toolbarPos=$uiconfig['toolbar_position'];
	$vnp=isset($uiconfig['vertical_nav_persist'])&&$uiconfig['vertical_nav_persist'];
	$settingsPos=isset($uiconfig['settings_position'])?$uiconfig['settings_position']:'';
	$accountPos=isset($uiconfig['account_position'])?$uiconfig['account_position']:'';

	if ($iphone){
		$toolbarPos=$uiconfig['mobile_landscape']['toolbar_position'];
		$vnp=$uiconfig['mobile_landscape']['vertical_nav_persist'];
		if (isset($uiconfig['mobile_landscape']['settings_position'])&&$uiconfig['mobile_landscape']['settings_position']!='') $settingsPos=$uiconfig['mobile_landscape']['settings_position'];
		if (isset($uiconfig['mobile_landscape']['account_position'])&&$uiconfig['mobile_landscape']['account_position']!='') $accountPos=$uiconfig['mobile_landscape']['account_position'];
	}

	// Icon suffix: -light for left toolbar on mobile, empty for other positions
	$iconSuffix='-light';
	if ($iphone&&$toolbarPos!='left') $iconSuffix='';

	// When toolbar_position is left and vertical_nav_persist is false, force quicklist on
	if ($toolbarPos==='left'&&!$vnp) $quicklist=true;

	if (!$iphone){
		?>
	<div style="height:20px;"></div>
		<?php
	}

	?>
	<div id="mainmenuitems" style="overflow-y:auto;overflow-x:hidden;">
	<?php
	foreach ($toolbaritems as $modid=>$ti){
	if (isset($ti['type'])&&$ti['type']=='custom'){
		if ($iphone&&isset($ti['iphone'])&&$ti['iphone']!=''){
			echo $ti['iphone'];
		} elseif (!$iphone){
			echo $ti['desktop'];
		}
		continue;
	}

	// Skip noiphone items on mobile
	if ($iphone&&isset($ti['noiphone'])&&$ti['noiphone']) continue;

	// Skip break items
	if (isset($ti['type'])&&$ti['type']=='break') continue;

	// Skip core.settings when settings_position is not 'menu'
	// (it will be rendered separately in vnav_account if settings_position=='left',
	//  or in toolbaractions if settings_position=='top')
	if ($modid==='core.settings'&&$settingsPos!=='menu') continue;

	// Skip lv.welcome on mobile when toolbar_position is 'left' or 'top'
	// (the app logo replaces it)
	if ($iphone&&$modid==='lv.welcome'&&($toolbarPos==='left'||$toolbarPos==='top')) continue;

	if (!isset($ti['icon'])||$ti['icon']=='') continue;

	if (isset($ti['groups'])){
		$canview=0;
		$gs=explode('|',$ti['groups']);
		foreach ($gs as $g) if (isset($user['groups'][$g])) $canview=1;
		if (!$canview) continue;
	}

	$binmode='null';
	if (isset($ti['bingo'])&&$ti['bingo']==1) $binmode=1;

	$action="showview('".$modid."',1,1,null,null,".$binmode.");";
	if (isset($ti['action'])&&$ti['action']!='') $action=$ti['action'];

	$returnFalse=$iphone?';return false':'';

	$iconAttrs=$iphone?' width="28" height="28"':'';

	$dcurrent=$modid==='default'?' current':'';

?>
	<div class="listmenuitem<?php echo $dcurrent;?>">
	<a id="listmenu_<?php echo $modid;?>" onclick="<?php echo $action;?>;listmenu_setcurrent(this);<?php echo $returnFalse;?>"><img alt="<?php echo $ti['title'];?>" style="vertical-align:middle;margin-right:5px;" class="<?php echo $ti['icon'].$iconSuffix;?>" src="imgs/t.gif"<?php echo $iconAttrs;?> title="<?php echo $ti['title'];?>"> <span style="vertical-align:middle;"><?php echo $ti['title'];?></span></a>
	</div>

<?php }//foreach

	?>
	</div><!-- mainmenuitems -->

	<div id="vnav_account">
	<?php
	// Settings link (if settings_position=='left')
	if ($settingsPos==='left'){
		$settingsIconClass='img-settings-light';
		$settingsIconAttrs='';
		if ($iphone){
			if ($toolbarPos==='left'){
				$settingsIconClass='img-settings-light';
			} else {
				$settingsIconClass='img-settings';
			}
			$settingsIconAttrs=' width="28" height="28"';
		}
	?>
	<div class="listmenuitem">
	<a onclick="showview('core.settings',1,1,null,null,null);return false;"><img alt="Settings" style="vertical-align:middle;margin-right:5px;" class="<?php echo $settingsIconClass;?>" src="imgs/t.gif"<?php echo $settingsIconAttrs;?> title="Settings">
	<span style="vertical-align:middle;">Settings</span></a>
	</div>
	<?php }?>

	<?php
	// Account link (if account_position=='left')
	if ($accountPos==='left'){
		if ($usermeta['haspic']){
			$userProfileImg='<img class="mainuserprofile loaded" src="'.$codepage.'?cmd=imguserprofile&userid='.$userid.'" title="'.htmlspecialchars($dispname).'">';
		} else {
			$userProfileImg='<img class="mainuserprofile" alt="'.htmlspecialchars($dispname).'" src="imgs/t.gif" title="'.htmlspecialchars($dispname).'">';
		}
	?>
	<div class="listmenuitem">
	<a onclick="ajxjs2('setaccountpass','accounts.js',function(){reloadtab('account','My Account','showaccount');addtab('account','My Account','showaccount');});return false;" style="white-space:nowrap;overflow:hidden;">
	<?php echo $userProfileImg;?>
	<span style="vertical-align:middle;"><?php echo htmlspecialchars($dispname);?></span></a>
	</div>
	<?php }?>

	<?php
	// Collapse/expand toggle (only if vertical_nav_persist is true)
	if ($vnp){
		$hideStyle=$quicklist?'':'display:none;';
		$showStyle=$quicklist?'display:none;':'';
	?>
	<a id="vnavside_hide" onclick="setquicklist(false);" style="<?php echo $hideStyle;?>">
	<img src="imgs/t.gif" class="img-pageleft"> <span>Collapse</span>
	</a>
	<a id="vnavside_show" onclick="setquicklist(true);" style="<?php echo $showStyle;?>">
	<img src="imgs/t.gif" class="img-pageright"> <span>Expand</span>
	</a>
	<?php
	}
	?>
	</div><!-- vnav_account -->
<?php

}