<?php
include 'web_preheader.php';


include 'web_header.php';
include 'web_footer.php';

show_web_header(null,array('pagekey'=>'about','page_title'=>'About Us'));

?>
<div class="cwidth">
	<p class="first">This page is a stub.</p>
	<p>
	You may create additional pages and add menu items in <em>web_header.php</em> accordingly.
	</p>
	<p>
		<textarea class="codesnippet">
&lt;?php
include 'web_preheader.php';

include 'web_header.php';
include 'web_footer.php';

show_web_header(null,array(
	'pagekey'=>'##pagekey##',
	'page_title'=>'##pagetitle##'
	));
?&gt;

Page Content Here...

&lt;?php
show_web_footer();

</textarea>
	</p>
	<p>
	The first parameter in <em>show_web_header</em> is an egress path. This can be left blank for root-level pages.
	The Login page is in the <em>/app</em> sub directory and therefore requires a <em>../</em> egress.
	</p>
	<p>
	Most of the theming can be done by modifying <em>style.css</em>. The markup for static pages is defined in
	<ul>
		<li>web_header.php</li>
		<li>web_footer.php</li>
	</ul>
	</p>
	<p>
		The Login page has additional container markups, which are defined in
		<ul>
			<li>web_login_header.php</li>
			<li>web_login_footer.php</li>
		</ul>
		
		These files are not to be confused with <em>app/login_header.php</em> and <em>app/login_footer.php</em>, which are activated when the <em>$gs_public_web</em> mode is switched off.
	</p>
	<p>
		The <em>images</em> folder stores public images; the <em>app/imgs</em> folder contains images for the Gyroscope backend app. The different spelling makes it easy to tell the two folders apart.
	</p>
	<p>
		There are two sets of application logos. One set is in <em>images</em>; the other in <em>app/imgs</em>. They are intentionally duplicated in case the web site's logo is different from the product logo.
		Each set contains a light and dark version of the logo.
	</p>
	
</div>
<?php

show_web_footer();