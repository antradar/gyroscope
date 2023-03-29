<?php
if (!isset($dark)) $dark=0;
?>

#cancel{display:none;font-size:12px;padding:5px 0;}
#progress_{vertical-align:middle;display:inline-block;border:solid 1px #999999;border-radius:3px;overflow:hidden;width:85%;}
#progress{display:inline-block;background:#ffff00;height:12px;}
#cancelbutton{vertical-align:middle;display:inline-block;padding:1px 4px;background:#ab0200;color:#ffffff;font-size:11px;cursor:pointer;border-radius:50%;font-family:Arial,sans-serif;}

#debug{font-size:11px;padding-top:5px;}

<?php
if ($dark==0){
?>
@media (prefers-color-scheme:dark) {
<?php
}

if ($dark==0||$dark==1){
?>
	body{background:#0D1117;color:#C9D1D9;}
<?php
}

if ($dark==0){
?>
}
<?php
}