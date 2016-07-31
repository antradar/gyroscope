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

rptinit_actionlog=function(){} //hook for loading for the first time
rptreload_actionlog=function(){} //sample hook for implicit reloading


//add report-specific functions and hooks here

/*
rptinit_###=function(){
	
}

rptreload_###=function(){
	
}
*/
