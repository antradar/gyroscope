_inline_lookupreport=function(d){
	var soundex='';
	if (d.soundex) soundex='&soundex=1';
	
	if (!d.oclassname) d.oclassname=d.className;	

	if (d.lastkey!=null&&d.lastkey==d.value) {
		lookupentity_completed(d);
		return;
	}
	d.lastkey=d.value;
			
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		//d.setAttribute('readonly',''); //do not block
		d.className=d.oclassname+' busy';		
		ajxpgn('reportlist',document.appsettings.codepage+'?cmd=slv_core__reports&mode=embed&key='+encodeHTML(d.value)+soundex,0,0,null,function(){
			lookupentity_completed(d);	
		});
	},400
	);	
}

showreport=function(){
	
}

addhomedashreport=function(rpttitle,rpttabkey,rptkey,rptlink,bingo,gskey){
	if (bingo==null) bingo=0;
	
	var rptname=sprompt('Custom Report Name');
	if (rptname==null||rptname=='') return;
	
	rptname=encodeHTML(rptname);
	
	rpttitle=encodeHTML(rpttitle);
	rptlink=encodeHTML(rptlink);
	
	ajxpgn('homedashreports',document.appsettings.codepage+'?cmd=addhomedashreport&rptname='+rptname+'&rpttabkey='+rpttabkey+'&rptkey='+rptkey+'&rpttitle='+rpttitle+'&rptlink='+rptlink+'&bingo='+bingo,0,0,null,function(){
		//salert('Report bookmark added to the Home Tab');
		showtab('welcome',{bookmark:'homedashreports'});
	},null,null,gskey);
	
}

delhomedashreport=function(homedashreportid,gskey){
	if (!sconfirm('Are you sure you want to remove this report bookmark?')) return;
	ajxpgn('homedashreports',document.appsettings.codepage+'?cmd=delhomedashreport&homedashreportid='+homedashreportid,0,0,null,null,null,null,gskey);		
}

sharehomedashreport=function(homedashreportid,d){
	var shared=0;
	if (d.checked) shared=1;
	ajxpgn('statusc',document.appsettings.codepage+'?cmd=sharehomedashreport&homedashreportid='+homedashreportid+'&shared='+shared);
}

rptinit_serverlog=function(){ //todo: drop "rpt"
	nav_loadcharts('rptserverlog','rptserverlogkey','rptserverlog',1); //bingo
}
rptreload_serverlog=function(){ //todo: drop "rpt"
	nav_loadcharts('rptserverlog','rptserverlogkey','rptserverlog',1); //bingo
}

rptinit_serverdigest=function(){ //todo: drop "rpt"
	nav_loadcharts('rptserverdigest','rptserverdigestkey','rptserverdigest',1); //bingo
}
rptreload_serverdigest=function(){ //todo: drop "rpt"
	nav_loadcharts('rptserverdigest','rptserverdigestkey','rptserverdigest',1); //bingo
}

rptinit_mxevents=function(){ //todo: drop "rpt"
	nav_loadcharts('rptmxevents','rptmxeventkey','rptmxevents',1); //bingo
}
rptreload_mxevents=function(){ //todo: drop "rpt"
	nav_loadcharts('rptmxevents','rptmxeventkey','rptmxevents',1); //bingo
}

rptinit_trace=function(){ //todo: drop "rpt"
	nav_loadcharts('rpttrace','rpttracekey','rpttrace',1); //bingo
}
rptreload_trace=function(){ //todo: drop "rpt"
	nav_loadcharts('rpttrace','rpttracekey','rpttrace',1); //bingo
}


rptinit_actionlog=function(){} //hook for loading for the first time
rptreload_actionlog=function(){} //sample hook for implicit reloading

cale_cellfunc=function(cell,obj){
	cell.innerHTML='<b style="font-size:20px;margin-top:10px;color:red;display:inline-block;">'+obj.count+'</b>';
}

cale_cellclick=function(cell,daykey){return function(){
	console.log(cell,daykey);
	alert("selected: "+daykey);	
}}

cale_filters=function(){
	return '&test_filter=123';	
}

rptinit_cale=function(){
	ajxjs2('scal_init','scal.js',function(){
		scal_init('rptcale',{cellfunc:cale_cellfunc,cellclick:cale_cellclick,filterfunc:cale_filters});			
	});

}

rptreload_cale=function(){
	ajxjs(null,'scal.js');
	scal_init('rptcale',{cellfunc:cale_cellfunc,cellclick:cale_cellclick,filterfunc:cale_filters});	
}

//add report-specific functions and hooks here

/*
rptinit_###=function(){
	
}

rptreload_###=function(){
	
}
*/
