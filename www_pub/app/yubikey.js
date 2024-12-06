addyubikey=function(challenge,userid,login,username,gskey){
	navigator.credentials.create({
		publicKey:{
			challenge:stringToArrayBuffer(challenge),
			pubKeyCredParams:[{'type':'public-key','alg':-7},{'type':'public-key','alg':-257}],
			rp:{name:location.protocol+'//'+location.hostname},
			timeout: 30000,
			user:{
				id:stringToArrayBuffer('gs_'+userid),
				name:login,
				displayName: username
			}
		}
	}).then(
		function(raw){
			var att={
				id:arrayBufferToHex(raw.rawId),
				clientDataJSON:arrayBufferToString(raw.response.clientDataJSON),
				attestationObject:arrayBufferToHex(raw.response.attestationObject)
			}
			if (att.id!=''){

				var params=[];
				params.push('id='+att.id);
				params.push('clientdata='+encodeHTML(att.clientDataJSON));
				params.push('att='+att.attestationObject);
				
				ajxpgn('myaccount_yubikeys',document.appsettings.codepage+'?cmd=addyubikey',0,0,params.join('&'),null,null,null,gskey);
			} else {
				salert('Failed to add credential');
			}
		}
	);

}

testyubikey=function(challenge,attids,gskey){
	
	var creds=[];
	
	gid('myaccount_yubikeytest').style.display='block';
	gid('myaccount_yubikeytest').innerHTML='Testing...';
	
	for (var i=0;i<attids.length;i++){
		creds.push({type:'public-key',id:Uint8Array.from(atob(attids[i]), function(c){return c.charCodeAt(0);}).buffer});
	}
	
	navigator.credentials.get({
		publicKey:{
			challenge:stringToArrayBuffer(challenge),
			pubKeyCredParams:[{'type':'public-key','alg':-7},{'type':'public-key','alg':-257}],
			timeout: 30000,
			allowCredentials:creds
		}
	}).then(
		function(raw){
			var ass={ //assertion
				id:arrayBufferToHex(raw.rawId),
				clientDataJSON:arrayBufferToString(raw.response.clientDataJSON),
				//userHandle:arrayBufferToHex(raw.response.userHandle),
				signature:arrayBufferToHex(raw.response.signature),
				authenticatorData:arrayBufferToHex(raw.response.authenticatorData)
			}

			var params=[];
			params.push('id='+ass.id);
			params.push('clientdata='+encodeHTML(ass.clientDataJSON));
			params.push('signature='+ass.signature);
			params.push('auth='+ass.authenticatorData);

			ajxpgn('myaccount_yubikeytest',document.appsettings.codepage+'?cmd=testyubikey',0,0,params.join('&'),null,null,null,gskey);

		}
	);

}

delyubikey=function(keyid,gskey){
	if (!sconfirm('Are you sure you want to remove this authentication device?')) return;
	ajxpgn('myaccount_yubikeys',document.appsettings.codepage+'?cmd=delyubikey&keyid='+keyid,0,0,null,function(rq){
		var disableyubi=rq.getResponseHeader('disableyubi');
		if (disableyubi!=null&&disableyubi=='1'&&gid('myaccount_useyubi')) {
			gid('myaccount_useyubi').checked='';
			gid('myaccount_yubikeys').style.display='none';
		}	
	},null,null,gskey);	
}

updateyubikeyname=function(keyid,d,gskey){
	if (!valstr(d)) return;
	
	ajxpgn('myaccount_yubikeys',document.appsettings.codepage+'?cmd=updateyubikeyname&keyid='+keyid,0,0,'keyname='+encodeHTML(d.value),function(){
		marktabsaved('account');	
	},null,null,gskey);	
}

setyubikeypassless=function(keyid,d,gskey){
	var passless=0;
	if (d.checked) passless=1;

	ajxpgn('statusc',document.appsettings.codepage+'?cmd=setyubikeypassless&keyid='+keyid+'&passless='+passless,0,0,null,function(){
		marktabsaved('account');	
	},null,null,gskey);	
		
}