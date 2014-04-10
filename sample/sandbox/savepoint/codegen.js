codegen=function(){}

codegen_makeform=function(seed){
//	var seed=gid('codegen_seed').value;
//	if (seed=='') return;
	
	ajxpgn('codegen_view',document.appsettings.codepage+'?cmd=codegen_makeform&seed='+seed);	
}

codegen_makecode=function(seed){
	var obj=eval('('+gid('codegen_seedobj').value+')');
	var fields=obj.fields;
	
	var myfields=[];
	
	var valid=1;
	
	for (var i=0;i<fields.length;i++){
		var ofield=gid('codegenfield_'+fields[i].field);
		if (!fields[i].optional){
			if (!valstr(ofield)) valid=0;
			if (fields[i].numeric&&!valfloat(ofield)) valid=0;
		}
		myfields.push(fields[i].field+'='+encodeHTML(ofield.value));
	}	
	
	if (!valid) return;
	
	var fields=myfields.join('&');
	
	var rq=xmlHTTPRequestObject();
	rq.open('POST',document.appsettings.codepage+'?cmd=codegen_makecode&seed='+seed,true);
	rq.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	rq.onreadystatechange=function(){
		if (rq.readyState==4){
			gid('codegen_codes').innerHTML=rq.responseText;
		}	
	}	
	
	rq.send(fields);
	
	
	if (!valid) return;
}

