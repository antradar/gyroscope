<?php
function livechat_ze(){
	global $chatkey;
?>
<script type="text/javascript">
  window.zESettings = {
    webWidget: {
      chat: {
        suppress: false,
        notifications: {
          mobile: {
            disable: true
          }
        }
      } 
    }
  };
</script>
<script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=<?php echo $chatkey;?>"> </script>
<script src="livechat_ze.js"></script>
<?php
}

function livechat_zopim(){
	global $chatkey;
?>
<script type="text/javascript">
if (window.WebSocket){

		
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="https://v2.zopim.com/?<?php echo $chatkey;?>";z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");


}
</script>
<script src="livechat_zopim.js"></script>
<?php
}

function livechat_gschat(){
	
	$user=userinfo();
	$gsid=$user['gsid'];
	$userid=$user['userid'];
	$pname=$user['dispname'];
	
	global $chatkey; //defined in lb.php
	global $targetgsid;
	global $targetgsauth;
	global $portalchannel;
	global $chatserver_http;
	global $chatserver_wss;
	
	$pauth=sha1($gsid.'-'.$chatkey.'-'.$userid);
	
	include 'gschatbox.php';
?>
<script src="pchat.js"></script>
<script>

document.gschatpath='<?php echo $chatserver_http;?>';
document.wspath='<?php echo $chatserver_wss;?>';


function livechat_init(){
	gschat_init('gschat','portalid=<?php echo $portalchannel;?>&pgsid=<?php echo $gsid;?>&puserid=<?php echo $userid;?>&portalauth=<?php echo $pauth;?>&pname=<?php echo urlencode($pname);?>','<?php echo $targetgsid;?>','<?php echo $targetgsauth;?>');	
}
function livechat_start(){
	gschat_setviewstate(1);	
}
</script>
<?php	
}

function livechat(){
	global $livechatmode;
	switch ($livechatmode){
		case 'ze': livechat_ze(); break;
		case 'zopim': livechat_zopim(); break;
		case 'gschat': livechat_gschat(); break;
	}	

}

