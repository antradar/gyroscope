<?php

function show_footer_links($egress='',$containerid){
?>
	<div id="<?php echo $containerid;?>">
		&copy; <?php echo date('Y');?> Company

<span id="footerlinks">
<a class="footerlink" href="<?php echo $egress;?>about.php">About</a>
&nbsp;
<a class="footerlink" href="<?php echo $egress;?>app/">Log In</a>
&nbsp;
<a class="footerlink" href="<?php echo $egress;?>signup.php">Sign Up</a>

</span>
	</div>
<?php	
}
