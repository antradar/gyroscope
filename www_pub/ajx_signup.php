<?php
include 'web_preheader.php';

include 'web_footerlinks.php';

	// check recaptcha or Cloudflare Turnstile
	//apperror('Invalid Recaptcha response');

	// insert into userreqs table
	
	// admin backend will later call gsclone and create the actual account

?>
<div>
	<p style="color:#ab0200;">
		todo: implement this feature in <em>ajx_signup.php</em>
	</p>
	<p>
	We have received your account creation request.
	</p>
	<p>
	You will be notified by email once the account is approved.
	</p>
	<p>
	Please note that the email will <em>not</em> be your login.
	</p>
	
	<div style="padding-top:40px;">
	<?php
	show_footer_links(null,'logincopyright');
	?>
	</div>
</div>
<?php