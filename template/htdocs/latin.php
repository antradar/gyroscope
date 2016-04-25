<?
$input=$_POST['input'];
if ($input!='') header('Content-Type: text/html; charset=iso-8859-1');
else header('Content-Type: text/html; charset=utf-8');

?><html>
<head>
	<title>Latin1 Encoder</title>
</head>
<body>
<?
	$encoded='';
	$input=$_POST['input'];
	if ($input!=''){
		$encoded=$input;
?>
	<div style="font-family:mono-space;">
		<textarea style="width:100%;height:200px;background:#ffffcc;"><?echo htmlspecialchars($encoded);?></textarea>
	</div>
<?
	} else {
?>
	<form method="POST" target="output">
	<textarea id="input" name="input" style="width:100%;height:200px;"><?echo $input;?></textarea>
	<input type="submit" value="Encode">
	</form>

	<iframe style="width:100%;height:220px;" name="output" frameborder="no"></iframe>
<?	
	}
?>

</body>

</html>