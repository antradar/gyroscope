codegen=function(){}

// Current selected space
var codegen_space='gyrosgo';

// Load available spaces (framework variants) and auto-select gyrosgo
codegen_loadspaces=function(){
	ajxpgn('codegen_spacetabs',document.appsettings.codepage+'?cmd=codegen_listspaces',false,false,null,function(){
		// Auto-select gyrosgo (default) after tabs are loaded
		codegen_showspace('gyrosgo');
	});
}

// Show seeds for a specific space with tab highlighting
codegen_showspace=function(space){
	codegen_space=space;

	// Update tab styling
	var tabs=document.querySelectorAll('.codegen_spacetab');
	for(var i=0;i<tabs.length;i++){
		tabs[i].style.background='#555555';
	}
	var activeTab=gid('codegen_tab_'+space);
	if(activeTab) activeTab.style.background='#222222';

	// Load seeds for this space
	ajxpgn('codegen_seeds',document.appsettings.codepage+'?cmd=codegen_listseeds&space='+space);
}

// Show form for a seed (supports both old single-arg and new two-arg calling)
codegen_makeform=function(space,seed){
	if (typeof seed === 'undefined') {
		seed = space;
		space = codegen_space || 'gyrosgo';
	}
	codegen_space = space;
	ajxpgn('codegen_view',document.appsettings.codepage+'?cmd=codegen_makeform&space='+space+'&seed='+seed);
}

// Generate code from form
codegen_makecode=function(space,seed){
	if (typeof seed === 'undefined') {
		seed = space;
		space = codegen_space || 'gyrosgo';
	}

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

	var postfields=myfields.join('&');

	var rq=xmlHTTPRequestObject();
	rq.open('POST',document.appsettings.codepage+'?cmd=codegen_makecode&space='+space+'&seed='+seed,true);
	rq.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	rq.onreadystatechange=function(){
		if (rq.readyState==4){
			gid('codegen_codes').innerHTML=rq.responseText;
		}
	}
	rq.send(postfields);
}

codegen_copy=function(idx){
	gid('codegensnippet_'+idx).select();
	document.execCommand('copy');
}