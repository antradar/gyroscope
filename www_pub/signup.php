<?php
include 'web_preheader.php';

include 'web_header.php';
include 'web_footer.php';
include 'web_login_header.php'; 
include 'web_login_footer.php'; 

show_web_header(null,array(
	'pagekey'=>'signup',
	'page_title'=>'Sign Up'
	));
	
show_web_login_header();	
?>
<div id="loginbox__" style="margin-top:60px;"><div id="loginbox_">
<div id="loginbox">

	<form id="signupform" method="POST" style="padding:20px;margin:0;padding-top:0px;" onsubmit="return checksignupform();">

		<div style="padding-top:10px;"><label for="username">Your Name:</label></div>
		<div style="padding-top:5px;padding-bottom:15px;">
			<input style="width:100%;" class="lfinp" id="username" type="text" autocomplete="off">
		</div>				
		
		<div style="padding-top:10px;"><label for="companyname">Company:</label></div>
		<div style="padding-top:5px;padding-bottom:15px;">
			<input style="width:100%;" class="lfinp" id="companyname" type="text" autocomplete="off">
		</div>
	
		<div style="padding-top:10px;"><label for="useremail">Email:</label></div>
		<div style="padding-top:5px;padding-bottom:15px;">
			<input style="width:100%;" class="lfinp" id="useremail" type="text" autocomplete="off">
		</div>
		
		<div style="padding-top:10px;"><label for="userphone">Phone:</label></div>
		<div style="padding-top:5px;padding-bottom:15px;">
			<input style="width:100%;" class="lfinp" id="userphone" type="text" autocomplete="off">
		</div>		
		
		<div><label for="password">Password:</label></div>
		<div style="padding-top:5px;padding-bottom:15px;position:relative;">
			<input style="width:100%;" class="lfinp" id="password" type="password" name="password" autocomplete="off">
			<img class="passtoggle" onclick="togglepass_login(this,'password','app/');" src="app/imgs/eye-slash.png">
		</div>
		
		<div><label for="password">Retype Password:</label></div>
		<div style="padding-top:5px;padding-bottom:15px;position:relative;">
			<input style="width:100%;" class="lfinp" id="password2" type="password" name="password2" autocomplete="off">
			<img class="passtoggle" onclick="togglepass_login(this,'password2','app/');" src="app/imgs/eye-slash.png">
		</div>		
		
	
		<div id="loginbar">
			<input id="loginbutton" class="loginbutton_" type="submit" value="Create Account">
		</div>
				
		<?php show_footer_links(null,'logincopyright');?>
	
	</form>
</div>
</div>

<script>
function checksignupform(){
	var params=[];
	ajxpgn('signupform','ajx_signup.php?',0,0,params.join('&'));
	
	return false;
}
</script>

<?php
show_web_login_footer();
show_web_footer();

