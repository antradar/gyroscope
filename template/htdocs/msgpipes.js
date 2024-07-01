dashmsgpipes=function(){
	addtab('dashmsgpipes','<img src="imgs/t.gif" class="ico-setting">Notification List','dashmsgpipes');
}

addmsgpipe=function(gskey){
	var omsgpipekey=gid('nmsgpipekey');
	var omsgpipename=gid('nmsgpipename');
	
	if (!valstr(omsgpipekey)) return;
	if (!valstr(omsgpipename)) return;
	
	var msgpipekey=encodeHTML(omsgpipekey.value);
	var msgpipename=encodeHTML(omsgpipename.value);
	
	reloadtab('dashmsgpipes','','addmsgpipe',null,'msgpipekey='+msgpipekey+'&msgpipename='+msgpipename,null,gskey);
}

addmsgpipeuser=function(d,msgpipeid,gskey){
	var userid=d.value2;
	if (!userid) return;
	
	ajxpgn('msgpipeusers_'+msgpipeid,document.appsettings.codepage+'?cmd=addmsgpipeuser&msgpipeid='+msgpipeid+'&userid='+userid,0,0,null,function(){
		
	},null,1,gskey);
}

delmsgpipeuser=function(msgpipeid,userid,gskey){
	if (!sconfirm('Are you sure you want to remove this user from the list?')) return;
	
	
	ajxpgn('msgpipeusers_'+msgpipeid,document.appsettings.codepage+'?cmd=delmsgpipeuser&msgpipeid='+msgpipeid+'&userid='+userid,0,0,null,function(){
		
	},null,1,gskey);
}

delmsgpipe=function(msgpipeid,gskey){
	if (!sconfirm('Are you sure you want to remove this list\n and all of its recipients?')) return;
	
	reloadtab('dashmsgpipes','','delmsgpipe',null,'msgpipeid='+msgpipeid,null,gskey);	
}