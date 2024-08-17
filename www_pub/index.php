<?php
include 'web_preheader.php';
if (!$gs_public_web){header('Location: app/'); die();}

include 'web_header.php';
include 'web_footer.php';

show_web_header(null,array('pagekey'=>'home'));

?>
<div class="cwidth">
	<p class="first">
	You are seeing this page because the <em>$gs_public_web</em> switch is set to "1" in lb.php. 
	This activates a set of public-facing pages instead of directly launching the Gyroscope backend.
	</p>
	
	<p>
	The web template pages in the <em>www_pub</em> folder are provided for your convenience. These templates are particularly useful when configuring the Gyroscope app as a multi-tenant system.
	</p>
	
	<p>
	The Login page is designed with a distinct style to make the Gyroscope app, or the "Product" more prominent. On the Login page, the main menu is hidden, the site logo directs users to the home page, and the mobile menu is available when viewed on mobile devices.
	The master menu bar can be activated on the Login page by suppressing the CSS override in <em>web_login_header.php</em>.
	</p>
	
	<p>
	When extending the styles in <em>style.css</em>, ensure that the following features are preserved:
	<ul>
		<li>Dark mode</li>
		<li>Mobile-responsive layout</li>
		<li>Mobile menu functionality</li>
	</ul>
	In addition, use a pallete to replace the grayscale theme of this template website.
	</p>
	
	<p>
	By default, Gyroscope uses the <em>gss</em> table to manage the Tenants, each Tenant being a collection of Users. The login of each User has to be unique.
	A common practice is to use the email address as the login. The "Username" label on the Login page can be changed to "Email".
	While this approach is commonly accepted and facilitates password resets through email, it raises security concerns.
	</p>
	<p>
	An alternative and more secure approach involves using a "handle" system, akin to Twitter @handles, that distinguishes users within an organization's namespace.
	This method allows individuals to manage multiple accounts without needing multiple emails and mitigates the risks associated with email-based password resets.
	Adopting the handle-based strategy enhances security while providing flexibility for users managing multiple accounts.
	</p>
	<p>
	The Vendor Portal module in the Gyroscope code generator, or <em>CodeGen</em> provides the steps for implementing the user group handle, in the form of a <em>gsslug</em>.
	The CodeGen also presents an opportunity to "mount" the tenants to a more logical table other than the generic <em>gss</em> table.
	For example, if the Gyroscope app is intended for car dealerships, a <em>dealers</em> table can be used to store each tenant. The users are subsequently stored in the <em><u>dealer</u>users</em> table.	
	</p>
	<p>
	For the owner of the Gyroscope app, which is YOU, a separate Gyroscope instance should be created to manage all the tenant accounts.
	The <em>gs_public_web</em> flag must be set to "0" for this instance, and only the content from <em>www_pub/app</em> should be copied to a folder such as <em>/admin</em>.

	</p>

</div>
<?php

show_web_footer();