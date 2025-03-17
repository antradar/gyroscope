<?php

function tmpl($content,$amp=0,$orglink=''){
	//pattern {{shortcode params}}
	$content=preg_replace('/{[\{(\S+?)\}\}/',"{{$1 noparam=1}}",$content);
	
	if ($amp){
		$noampcontent='<div class="noampcontent"><div class="noamplabel">The following content is not available on the AMP page.</div><a class="noampbutton" href="'.$orglink.'">View full site &raquo;</a></div>';
		
		$content=preg_replace('/<img class="full"([\S\s]*?)\/>/','<amp-img class="full" layout="responsive"$1></amp-img>',$content);
		$content=preg_replace('/<img class="left"([\S\s]*?)\/>/','<amp-img class="left" layout="responsive"$1></amp-img>',$content);
		$content=preg_replace('/<img class="right"([\S\s]*?)\/>/','<amp-img class="right" layout="responsive"$1></amp-img>',$content);
		$content=preg_replace('/<img class="org"([\S\s]*?)\/>/','<amp-img class="org"$1></amp-img>',$content);
		$content=str_replace('ampwidth','width',$content);
		$content=str_replace('ampheight','height',$content);
		$content=str_replace('{{youtube','{{ampyoutube',$content);
		$content=preg_replace('/<div class="noamp"><\!-- noampstart -->[\S\s]+?<\!-- noampend --><\/div>/',$noampcontent,$content);
	}
	
	$content=str_replace('../medialib/', './medialib/',$content); //adjust path if necessary

	//optional reflink protection	
	$content=str_replace('}}&nbsp;</span>','}}</span>&nbsp;',$content);
	$content=str_replace('}} </span>','}}</span> ',$content);

	//replace inline links
	$content=preg_replace('/<span class="reflink">([\S\s]+?)<\/span>/','<p>$1</p>',$content);

	if (!preg_match_all('/<p>\{\{(\S+)\s*([\S\s]*?)\}\}<\/p>/',$content,$matches,PREG_OFFSET_CAPTURE)) {
		echo $content;	
		return;
	}
	
	$funcs=$matches[1];
	$pos=0;
	
	foreach ($funcs as $idx=>$func){
		$funcname=$func[0];
		$strparams=$matches[2][$idx][0];
		$fullmatch=$matches[0][$idx][0];
		$start=$matches[0][$idx][1];
		$len=strlen($fullmatch);
		$end=$start+$len;
		
		$token=substr($content,$pos,$start-$pos);		
		$pos=$end;
		
		echo $token;
		
		$funcname='tmpl_'.$funcname;
		
		if (!is_callable($funcname)) {
			echo $func[0];
			continue;
		}
		$strparams=preg_replace('/(\S+?)=/',"|x|$1=",$strparams);
		$pparts=explode('|x|',$strparams);

		$params=array();
		foreach ($pparts as $ppart){
			if (trim($ppart)=='') continue;
			$pts=explode('=',$ppart);
			$params[trim($pts[0])]=trim($pts[1]);	
		}

		$funcname($params, $idx);
				
	}
	
	$token=substr($content,$pos);
	echo $token;
	
	//echo '<pre>';print_r($matches);echo '</pre>';
	
}

function tmpl_reflink($params){
	//print_r($params); die();	
	$id=$params['id']+0;
	$target=$params['target'];
	
	switch ($params['type']){

		
	default: $slugtitle=$params['title'];
	}
	
	$url=$id.'-'.$params['type'].'-'.makeslug($slugtitle);
?><a class="reflink" href="<?php echo $url;?>"<?php if ($target) echo ' target='.$target;?>><?php echo trim($params['title']);?></a><?php	

}

function tmpl_bloglist($params){
	$deptid=$params['id']+0;
	global $db;
	global $curlang;
	global $dict;
	
	include_once 'makeslug.php';
	
	$query="select * from blog where groupdeptid=? and published_$curlang=1 order by blogdate desc";
	
	$rs=sql_prep($query,$db,$deptid);

	while ($myrow=sql_fetch_array($rs)){
		$title=$myrow['title_'.$curlang];
		$blogid=$myrow['blogid'];
		$slug=makeslug($title);
?>
<div class="subpage-bloglistitem">
		<a href="<?php echo $dict['slug_blog']."-$blogid-$slug";?>"><?php echo $title;?> <b>></b></a>
</div>
<?php		
		
	}	
	
} 

function tmpl_blog($params){
	$deptid=$params['id']+0;
	global $db;
	global $curlang;
	global $dict;
	
	include_once 'makeslug.php';
	
	$perpage=5;
	if (is_numeric($params['perpage'])) $perpage=$params['perpage'];
	
	$params=array($deptid);
	$query="select blog.*,unix_timestamp(blogdate) as bdate from blog where groupdeptid=? and published_$curlang=1 ";
	$rs=sql_prep($query,$db,$params);
	$count=sql_affected_rows($db,$rs);
	
	$page=$_GET['page']+0;
	
	$maxpage=ceil($count/$perpage)-1;
	if ($page<0) $page=0;
	if ($maxpage<0) $maxpage=0;
	if ($page>$maxpage) $page=$maxpage;
	
	$start=$page*$perpage;
	
	$query.=" order by blogdate desc limit ?,? ";
	array_push($params,$start,$perpage);
	
	$rs=sql_prep($query,$db,$params);

	while ($myrow=sql_fetch_array($rs)){
		$title=$myrow['title_'.$curlang];
		$blogid=$myrow['blogid'];
		$slug=makeslug($title);
		$content=$myrow['blogtext_'.$curlang];
		$bdate=$myrow['bdate'];
		$dadate=date($dict['dateformat'],$bdate);	
?>
<div class="subpage-blogitem">
		<div class="blogtitle"><?php echo $dadate.' &nbsp;&ndash;&nbsp; '.$title;?></div>
		<div>
		<?php tmpl($content);?>
		</div>
</div>
<?php		
		
	}//while
	
	if ($maxpage>0){
		$baselink=$_SERVER['REQUEST_URI'];
		$baselink=preg_replace('/-page-\d+$/','',$baselink);
	?>
<div style="padding:10px;">
	<div style="float:left;<?php if ($page==0) echo 'visibility:hidden';?>"><a href="<?php echo $baselink?><?php echo $page<=1?'':'-page-'.($page-1);?>">&laquo; Prev</a></div>
	<div style="float:right;<?php if ($page==$maxpage) echo 'visibility:hidden';?>"><a href="<?php echo $baselink?>-page-<?php echo $page+1;?>">Next &raquo;</a></div>
	<div style="clear:both;"></div>
</div>
<?php		
	}	
	
} 

function tmpl_gsreplay($params){
	//todo: check access control
	
	$gsreplayid=intval($params['id']);
	
	global $db;
	global $codepage;
	global $tmpl_gsreplay_inited;
	if (!isset($tmpl_gsreplay_inited)){
?>
<script src="nano.js"></script>
<script src="gsreplay.js"></script>
<?php	
		$tmpl_gsreplay_inited=1;	
	}
		
	$query="select * from gsreplayframes where gsreplayid=? order by frameid";
	$rs=sql_prep($query,$db,array($gsreplayid));
	$frames=array();
	while ($myrow=sql_fetch_assoc($rs)){
		$frameid=$myrow['frameid'];
		array_push($frames,array(
			'frame'=>$codepage.'?cmd=img_gsreplayframe&frameid='.$frameid,
			'toffset'=>$myrow['frametoffset'],
			'itr'=>$myrow['frameitr']
		));	
	}//while
	
?>
<img id="gsreplay_help_<?php echo $gsreplayid;?>" src="imgs/t.gif" style="max-width:90%;margin:0 auto;cursor:pointer;">
<script>
frames=<?php echo json_encode($frames);?>;
gsreplay_play('gsreplay_help_<?php echo $gsreplayid;?>',frames,0,1);
</script>
<?php
}


function tmpl_youtube($params){
	$key=$params['key'];
	$key=preg_replace('/[^A-Za-z0-9-_]/','',$key);
	if ($key=='') return;
		
?>
<div class="videoanchor">
	<iframe class="videoframe"  src="//www.youtube.com/embed/<?php echo $key;?>" frameborder="0" allowfullscreen></iframe>
</div>
<?php
}

function tmpl_ampyoutube($params){
	$key=$params['key'];
	$key=preg_replace('/[^A-Za-z0-9-_]/','',$key);
	if ($key=='') return;
	
	//add to header: <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
?>
<amp-youtube width="800" height="450" layout="responsive" data-videoid="<?php echo $key;?>"></amp-youtube>
<?php
/*		
?>
// <script async custom-element="amp-iframe" src="https://cdn.ampproject.org/v0/amp-iframe-0.1.js"></script>
<amp-iframe width="800" height="450"
	sandbox="allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox" layout="responsive" frameborder="0"
	src="https://www.youtube.com/embed/<?php echo $key;?>" allowfullscreen></amp-iframe>
<?php
*/
}

function tmpl_matterport($params){
	$key=$params['key'];
	$key=preg_replace('/[^A-Za-z0-9-_]/','',$key);
	if ($key=='') return;
	
	$width=560;
	$height=400;
	
	if (isset($params['width'])) $width=$params['width'];
	if (isset($params['height'])) $height=$params['height'];
	
?>
<iframe width="<?php echo $width;?>" height="<?php echo $height;?>" src="https://my.matterport.com/show/?m=<?php echo $key;?>" frameborder="0" allowfullscreen></iframe>
<?php
}

function tmpl_slides($params, $idx){
	$mediaids = explode(",", $params['mediaids']);

}

//tmpl("This is a test {{youtube key=L8onlB0F1_A}} Line 2 {{blog id=1}} xyz");
