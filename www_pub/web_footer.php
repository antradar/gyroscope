<?php

function show_web_footer($egress=''){
?>

	<div id="copyright">
		&copy; <?php echo date('Y');?> Company Name
	</div>
</div><!-- page -->

<script src="<?php echo $egress;?>app/nano.js?v=4_9"></script>
<script>
function showmmenu(){
	if (!document.mshowing){
		gid('page').style.marginLeft='-70%';
		document.mshowing=true;	
		gid('mmenu').style.right=0;
	} else {
		gid('page').style.marginLeft=0;
		document.mshowing=null;
		gid('mmenu').style.right='-70%';
			
	}	
}
</script>

</body>
</html>
<?php	
}
