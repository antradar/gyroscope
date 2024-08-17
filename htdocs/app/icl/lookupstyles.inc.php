<?php

function lookupstyles(){
	$mode=SGET('mode');
	$id=SGET('id');
	
	$user=userinfo();
	$gsid=$user['gsid'];
	
	global $db;
	
?>
<div class="section">
<?php	
	switch ($mode){
		case 'systemplate':
			$query="select templates.* from templates,templatetypes where templateid=? and templates.templatetypeid=templatetypes.templatetypeid and gsid=?";
			$rs=sql_prep($query,$db,array($id,$gsid));
			$myrow=sql_fetch_assoc($rs);
			$templatetypeid=$myrow['templatetypeid'];
			$query="select * from templatetypes where templatetypeid=? and gsid=?";
			$rs=sql_prep($query,$db,array($templatetypeid,$gsid));
			$myrow=sql_fetch_assoc($rs);
			$classes=explode(',',$myrow['classes']);
			foreach ($classes as $class){
				$class=strip_tags($class);
			?>
			<div class="listitem" style="cursor:pointer;" onclick="if (document.hotspot&&document.hotspot.selection) {document.hotspot.selection.setContent('<div class=&quot;<?php echo $class;?>&quot;><p>'+(document.hotspot.selection.getContent()==''?'Text':document.hotspot.selection.getContent())+'</p></div>');document.hotspot.focus();}">
				<a>.<?php echo $class;?></a>
				<div class="pickerstyle_<?php echo $class;?>"><span>ABC</span></div>
			</div>
			<?php	
			}	
			
		break;
		default: echo 'undefined style handler '.$mode;
	}

	if ($_SERVER['REMOTE_ADDR']==='127.0.0.1'&&($_SERVER['O_IP']==='127.0.0.1'||$_SERVER['O_IP']==='::1')){	
?>
	<div class="infobox"><em>
		Developer notes: Styles are defined in
		<ul>
		<li>toolbar.css</li>
		<li>tiny_mce/editor.css</li>
		<li>../style.css</li>
		</ul>
	</em>
	</div>
<?php
	}
?>
</div>
<?php
}