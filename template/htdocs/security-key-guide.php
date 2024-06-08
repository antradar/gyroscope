<?php
include 'lb.php';
include 'auth.php';

?>
<!doctype html>
<head>
	<title>Using Hardware/Biometric Keys</title>
	<meta name="viewport" content="width=device-width" />
	<style>
		body{padding:0;margin:0;text-align:center;font-family:'Times New Roman',serif;font-size:18px;background:#222222;line-height:1.45em;}
		#page{padding:40px;max-width:860px;text-align:left;margin:0 auto;background:#f8f8f8;min-height:600px;}
		#appicon{text-align:center;padding:20px 0;margin-bottom:20px;}
		  #appicon img{width:90px;border-radius:50%;}
		h1{padding:0;margin:0;margin-bottom:20px;font-size:32px;font-weight:normal;}
		.appname{color:#29ABE1;}
		
		h2{font-size:20px;padding-top:20px;}
		
		.fig{width:80%;max-width:600px;margin:20px auto;}
		.fig img{display:block;width:100%;border-radius:10px;}
		
		@media screen and (max-width:680px){
			
			.fig{width:90%;}	
		}

		@media screen and (max-width:580px){
			body{line-height:1.5em;}
			h1{font-size:28px;text-align:center;}
			.fig{width:100%;}	
		}
		
		@media screen and (max-width:490px){
			body{font-size:17px;}
			h1{font-size:26px;}
			#page{padding:30px;}
			p{word-wrap:break-word;hyphen:auto;}
		}
		
		@media screen and (max-width:420px){
			.trivial{display:none;}
		}
				
	</style>
</head>
<body>
<div id="page">

<div id="appicon"><img src="appicons/192x192.png"></div>

<h1>Using Hardware<span class="trivial">/Biometric</span> Keys</h1>

<p>
<span class="appname"><?php echo GYROSCOPE_PROJECT;?></span> supports a range of hardware and biometric security keys as additional, or replacement authentication methods.
The exact form factor of a "security key" depends on your browser and device capabilities. Typically these include hardware authentication tokens such as a YubiKey, or a fingerprint that's enrolled on your device.
</p>

<h2>Enrollment</h2>

<p>
To use a hardware security key, you must first sign in regularly, and register the security token.
<br><br>
Due to the very personal nature of multi-factor authentication (MFA), the interface for key enrollment is only available to your own account, under "Account Settings":
<br><br>
To set up a security key, go to Account Settings by clicking on the user icon on the top right corner of the app. Then check the box that says "enable hardware security keys and screen lock":
<br><br>
<div class="fig"><img src="help/yubi-config.png"></div>
<br><br>
You can enroll multiple keys and test the keys separately or at the same time. The "Test All" button is useful for identifying which device is picked as a primary authentication method by the system. In fact, one can plug in multiple keys and see which profile matches:
<br><br>
<div class="fig"><img src="help/yubi-multikey.jpg"></div>
<br><br>
You will be asked to touch the key first before going through the typical key registration steps.
<br><br>
In a browser that supports FIDO2, you will be asked to set up a PIN during the first time of using a security key. The PIN is then required before further verification: 
</p>
<div class="fig"><img src="help/yubi-pin.png"></div>
<br><br>
Afterward, you will be prompted to touch the key:
<br><br>
<div class="fig"><img src="help/yubi-touch.png"></div>
<br><br>
On a mobile device, you might be presented additional options:
<br><br>
<div class="fig"><img src="help/yubi-mobile.jpg"></div>
<br><br>
If a finger print is enrolled in the mobile device, it is then supported by the screen lock method. A NFC-enabled security key can also be tapped against the back of the device without selecting the "use security key with NFC" option first.

<h2>Striking a Balance between Convenience and Security</h2>
<p>
Although the flow of adding a physical authentication method is the same for a FIDO2 key, a FIDO/U2F key and a screen lock, the end results might be either convenience or added security. There is unfortunately a tradeoff. 
Luckily, <span class="appname"><?php echo GYROSCOPE_PROJECT;?></span> allows flexible configuration of keys, so that it's up to the user to deploy suitable strategies.
<br><br>
FIDO2 and FIDO/U2F are interfaces that may be supported by the same hardware key. The Universal 2nd Factor (U2F) is intended as an added security rather than replacing passwords. One can argue that a stolen key should not be sufficient to gain access to a critical system. FIDO2 was created to require an additional PIN. The difference in hardware interfaces translates to one extra step in the user interface: can a key be simply tapped to log in, or an extra PIN must be entered first?

There is one more difference. When a FIDO2 key is configured to run in U2F-only mode, the said key cannot be enrolled in <span class="appname"><?php echo GYROSCOPE_PROJECT;?></span>. You will see the following error: 

<div style="padding:20px 40px;">
"user must be present and verified"
</div>

The same error is given when enrolling a key through an NFC touch. This is by design.
<br><br>
To add an NFC key to an account, use a laptop to enroll the key first. Similarly, a FIDO2 key can be enrolled before switching it to U2F-only mode:
<br><br>
<div class="fig"><img src="help/yubi-disable-fido2.png"></div>
<br>
</p>

<h2>Enforcement Options</h2>
<p>
<span class="appname"><?php echo GYROSCOPE_PROJECT;?></span> allows very flexible security policy settings. Not all keys are the same. We can have one key that's PIN protected, and another for expediency. The combination of "security keys are optional" on the account level, and "password-less" on the key level allows the following scenarios:
<ul>
	<li>Both password and a security key are required</li>
	<li>Either a password or a key can log in</li>
	<li>A password, or a "password-less" key is required</li>
	<li>A key is required, password ignored</li>
</ul>
Furthermore, when additional 2FA methods such as SMS verification or Google authenticator code are enabled, they will be gathered prior to touching the security key.
</p>

<h2>Signing In</h2>
<p>
On the login screen, the username must be provided to enable the Hardware Key option. If the user has no security token configured, a link to this help page is provided instead.
<br><br>
<div class="fig"><img src="help/mfa-login.png"></div>
<br><br>
If the user signs in with a hardware key, the user's login is remembered for future sessions. A strict username-password sign-on would remove such memory.
<br><br>
If the use of a hardware key is not optional, clicking on the "Sign In" button will automatically trigger the Security Key (USB) icon.

</p>


<h2>Recovery</h2>
<p>
If an individual user is locked out due to the loss or inaccessibility to a hardware token, another user with the User Management privilege (a "Manager") may initiate a password reset for the locked-out user.
Even though the Manager cannot see, or provision a hardware key on behalf of another user, the Manager can revoke any MFA elements through a password reset.
</p>

</div><!-- page -->
</body>
</html>

