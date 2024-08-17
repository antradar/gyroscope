<?php
include 'web_preheader.php';
if (!$gs_public_web){header('Location: app/'); die();}

include 'web_header.php';
include 'web_footer.php';

show_web_header(null,array('pagekey'=>'about','page_title'=>'About Us'));

?>
<div class="cwidth">
	<p class="first">This page is a stub.</p>
	<p>
	You may create additional pages and add menu items in <em>web_header.php</em> accordingly.
	</p>
</div>
<?php

show_web_footer();