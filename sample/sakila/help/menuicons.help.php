Menu icons are configured in both <em>index.php</em> and <em>iphone.php</em> for the mobile version.
<br><br>
<textarea style="width:100%;height:80px;">
<acronym title="Entity 1"><a href=# title="Entity 1" onmouseover="hintstatus('Entity 1',this);" onclick="showview(0);">
	<img src="imgs/t.gif" class="img-big1" width="32" height="32">
</a></acronym>
</textarea>
<br><br>

Each icon should represent a base record type, as the icon invokes a corresponding <a onclick="showhelp('listviews','Writing a List View');"><u>list view</u></a>.
Here are some stock icons to get you started:
<br><br>

<img src="imgs/toolbar.gif">

<br><br>
All the icons are defined in <em>imgs/toolbar.gif</em> as a sprite sheet.
<?
/*
$imgs=array(
'card.gif','customer.gif','shop.gif','shipping2.gif','shipping1.gif','product.gif','order.gif','ico_reports.gif','docs.gif'
);

foreach ($imgs as $img){
?>
<acronym title="source: imgs/<?echo $img;?>"><img src="imgs/<?echo $img;?>" style="margin-right:5px;"></acronym>
<?	
}
*/
?>
