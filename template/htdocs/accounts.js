setaccountpass=function(){
	var ooldpass=gid('accountpass');
	var opass1=gid('accountpass1');
	var opass2=gid('accountpass2');
	
	if (!valstr(ooldpass)) return;
	if (!valstr(opass1)) return;
	if (!valstr(opass2)) return;

	var oldpass=encodeHTML(ooldpass.value);
	var pass1=encodeHTML(opass1.value);
	var pass2=encodeHTML(opass2.value);

	if (pass1!=pass2){
		alert('Passwords mismatch');
		return;
	}
	var rq=xmlHTTPRequestObject();
	rq.open('POST',document.appsettings.fastlane+'?cmd=setaccount',true);
	rq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	rq.onreadystatechange=function(){
		if (rq.readyState==4){
			alert(rq.responseText);	
		}	
	}
	
	rq.send('oldpass='+oldpass+'&pass='+pass1);
}