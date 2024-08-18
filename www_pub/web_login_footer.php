<?php
function show_web_login_footer(){
?>

</div><!-- web_login_form -->
</div><!-- web_login -->
<script>
function togglepass_login(s,id,egress){
	var d=gid(id); if (!d) return;
	if (!d.showing) {
		d.type='text';
		d.showing=true;
		s.src=egress+'imgs/eye.png';
	} else {
		d.type='password';
		d.showing=null;
		s.src=egress+'imgs/eye-slash.png';
	}
	d.focus();	
}
</script>
<?php	
}
