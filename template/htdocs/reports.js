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

addhomedashreport=function(rpttitle,rpttabkey,rptkey,rptlink){
	var rptname=sprompt('Custom Report Name');
	if (rptname==null||rptname=='') return;
	
	rptname=encodeHTML(rptname);
	
	rpttitle=encodeHTML(rpttitle);
	rptlink=encodeHTML(rptlink);
	
	ajxpgn('homedashreports',document.appsettings.codepage+'?cmd=addhomedashreport&rptname='+rptname+'&rpttabkey='+rpttabkey+'&rptkey='+rptkey+'&rpttitle='+rpttitle+'&rptlink='+rptlink,0,0,null,function(){
		salert('Report bookmark added to the Home Tab');	
	});
	
}

delhomedashreport=function(homedashreportid){
	if (!sconfirm('Are you sure you want to remove this report bookmark?')) return;
	ajxpgn('homedashreports',document.appsettings.codepage+'?cmd=delhomedashreport&homedashreportid='+homedashreportid);		
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
