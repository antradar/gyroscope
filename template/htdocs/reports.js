_inline_lookupreport=function(d){
	var soundex='';
	if (d.soundex) soundex='&soundex=1';
	
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		ajxpgn('reportlist',document.appsettings.codepage+'?cmd=slv_core__reports&mode=embed&key='+encodeHTML(d.value)+soundex);
	},300
	);	
}

showreport=function(){
	
}

addhomedashreport=function(rpttitle,rpttabkey,rptkey,rptlink,bingo){
	if (bingo) bingo=1; else bingo=0;
	
	var rptname=sprompt('Custom Report Name');
	if (rptname==null||rptname=='') return;
	
	rptname=encodeHTML(rptname);
	
	rpttitle=encodeHTML(rpttitle);
	rptlink=encodeHTML(rptlink);
	
	ajxpgn('homedashreports',document.appsettings.codepage+'?cmd=addhomedashreport&rptname='+rptname+'&rpttabkey='+rpttabkey+'&rptkey='+rptkey+'&rpttitle='+rpttitle+'&rptlink='+rptlink+'&bingo='+bingo,0,0,null,function(){
		//salert('Report bookmark added to the Home Tab');
		showtab('welcome',{bookmark:'homedashreports'});
	});
	
}

delhomedashreport=function(homedashreportid){
	if (!sconfirm('Are you sure you want to remove this report bookmark?')) return;
	ajxpgn('homedashreports',document.appsettings.codepage+'?cmd=delhomedashreport&homedashreportid='+homedashreportid);		
}

sharehomedashreport=function(homedashreportid,d){
	var shared=0;
	if (d.checked) shared=1;
	ajxpgn('statusc',document.appsettings.codepage+'?cmd=sharehomedashreport&homedashreportid='+homedashreportid+'&shared='+shared);
}

rptinit_serverlog=function(){ //todo: drop "rpt"
	nav_loadcharts('rptserverlog','rptserverlogkey','rptserverlog',true); //bingo
}
rptreload_serverlog=function(){ //todo: drop "rpt"
	nav_loadcharts('rptserverlog','rptserverlogkey','rptserverlog',true); //bingo
}

rptinit_mxevents=function(){ //todo: drop "rpt"
	nav_loadcharts('rptmxevents','rptmxeventkey','rptmxevents',true); //bingo
}
rptreload_mxevents=function(){ //todo: drop "rpt"
	nav_loadcharts('rptmxevents','rptmxeventkey','rptmxevents',true); //bingo
}

rptinit_trace=function(){ //todo: drop "rpt"
	nav_loadcharts('rpttrace','rpttracekey','rpttrace',true); //bingo
}
rptreload_trace=function(){ //todo: drop "rpt"
	nav_loadcharts('rpttrace','rpttracekey','rpttrace',true); //bingo
}


rptinit_actionlog=function(){} //hook for loading for the first time
rptreload_actionlog=function(){} //sample hook for implicit reloading


//add report-specific functions and hooks here

/*
rptinit_###=function(){
	
}

rptreload_###=function(){
	
}
*/
