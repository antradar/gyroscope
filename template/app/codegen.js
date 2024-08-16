codegen=function(){}

codegen_makeform=function(seed){	
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
		var thisvalue=encodeHTML(ofield.value);
		if(fields[i].type=='checkbox'){
			thisvalue=ofield.checked?1:0;
		}
		myfields.push(fields[i].field+'='+thisvalue);
	}	
	
	if (gid('codegenfield_viewindex')) myfields.push('fviewindex='+encodeHTML(gid('codegenfield_viewindex').value.replace(/\./g,'__')));
	
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

codegen_copy=function(idx){
	gid('codegensnippet_'+idx).select();
	document.execCommand('copy');
}