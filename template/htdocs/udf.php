<?php

include 'lb.php';
if ($usehttps) include 'https.php';
if (!$enableudf) die('UDF Editing is disabled');


include 'connect.php';
include 'settings.php';
include 'udf/listfuncs.inc.php';
include 'udf/newfunc.inc.php';

login();
$user=userinfo();

?>
<!doctype html>
<html>
<head>
	<title>UDF Editor - Antradar Gyroscope&trade;</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<style>
body{padding:0;margin:0;}
#funcs, #funcview{float:left;}
#funcs{width:28%;margin-right:2%;height:100%;overflow:auto;}
#funcview{width:68%;margin-right:2%;overflow:auto;}
#funcs a{display:block;}
.dbname{background:#666666;color:#ffffff;margin-bottom:5px;padding:5px;}
.dbname.main{background:#666699;}
	
.inplong{width:99%;display:block;}

.func{margin-left:10px;margin-bottom:8px;}
.buttonbar{padding:10px;background:#efefef;margin-top:20px;}

	</style>
</head>

<body>

<div id="funcs"><?php listfuncs();?></div>
<div id="funcview">
	<?php newfunc();?>
</div><!-- funcview -->

<script src="nano.js"></script>
<script>
salert=function(msg){alert(msg);}

filterkeys=function(d){
	if (d.onkeydown!=null) return;
	d.onkeydown=function(e){
		var keycode;
		if (e) keycode=e.keyCode; else keycode=event.keyCode;
		if (keycode==9) {
			var start=d.selectionStart;
			var end=d.selectionEnd;
			if (start==null){
				if (document.selection){
					var r=document.selection.createRange();
					if (r==null) return 0;
					var re = d.createTextRange();
					var rc = re.duplicate();
					re.moveToBookmark(r.getBookmark());
					rc.setEndPoint('EndToStart',re);
					start=rc.text.length;
					var lastchar=d.value.substring(start,start+1).replace(/\s/g,'');
					if (lastchar=='') start=start+2;
					end=start;
				}
			}
						
			if (start!=null){
				var val=d.value;
				d.value=val.substring(0,start)+"\t"+val.substring(end);
			}
			
			d.focus();
			if (d.selectionStart) d.setSelectionRange(start+1,start+1);
			return false;	
		}
	}	
}

function newfunc(){
	ajxpgn('funcview','funcsrv.php?cmd=newfunc');
}

function addfunc(gskey){
	var dbname=encodeHTML(gid('dbname_new').value);
	var func=encodeHTML(gid('funcname_new').value);
	
	var funcargs=encodeHTML(gid('funcargs_new').value);
	var funcpre=encodeHTML(gid('funcpre_new').value);	
	var functext=encodeHTML(gid('functext_new').value);
	
	ajxpgn('funcview','funcsrv.php?cmd=updatefunc&dbname='+dbname+'&func='+func,0,0,'args='+funcargs+'&pre='+funcpre+'&text='+functext,function(){
		ajxpgn('funcs','funcsrv.php?cmd=listfuncs');	
	},null,null,gskey);
}

function delfunc(dbname,func,gskey){
	if (!confirm('Are you sure you want to delete this function?')) return;
	
	
	ajxpgn('funcview','funcsrv.php?cmd=delfunc&dbname='+dbname+'&func='+func,0,0,null,function(){
		ajxpgn('funcs','funcsrv.php?cmd=listfuncs');	
	},null,null,gskey);
}

function updatefunc(dbname,func,gskey){
	var funcargs=encodeHTML(gid('funcargs_'+dbname+'_'+func).value);
	var funcpre=encodeHTML(gid('funcpre_'+dbname+'_'+func).value);	
	var functext=encodeHTML(gid('func_'+dbname+'_'+func).value);
	
	ajxpgn('funcview','funcsrv.php?cmd=updatefunc&dbname='+dbname+'&func='+func,0,0,'args='+funcargs+'&pre='+funcpre+'&text='+functext,function(){
		ajxpgn('funcs','funcsrv.php?cmd=listfuncs');	
	},null,null,gskey);
}

function showfunc(dbname, func){
	ajxpgn('funcview','funcsrv.php?cmd=showfunc&dbname='+dbname+'&func='+func);
}
	
function autosize(){
	var h=document.documentElement.clientHeight;
	gid('funcs').style.height=h+'px';
	gid('funcview').style.height=h+'px';
}

autosize();
window.onresize=autosize;
	
	
</script>
</body>
</html>
