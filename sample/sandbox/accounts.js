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
	
	var res=ajxb(document.appsettings.codepage+'?cmd=setaccount&oldpass='+oldpass+'&pass='+pass1);
	alert(res);
}