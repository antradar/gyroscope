setaccountpass=function(lastdarkmode){
	var ooldpass=gid('accountpass');
	var opass1=gid('accountpass1');
	var opass2=gid('accountpass2');
	
	var osmscell=gid('myaccount_smscell');
		
	if (opass1.value!=''||opass2.value!=''){
		if (!valstr(ooldpass)) return;
		if (!valstr(opass1)) return;
		if (!valstr(opass2)) return;		
	}

	var oldpass=encodeHTML(ooldpass.value);
	var pass1=encodeHTML(opass1.value);
	var pass2=encodeHTML(opass2.value);
	
	var needkeyfile=0;
	if (gid('myaccount_needkeyfile').checked) needkeyfile=1;
	
	var usesms=0;
	if (gid('myaccount_usesms').checked) usesms=1;
	
	var usega=0;
	if (gid('myaccount_usega').checked) usega=1;
	
	var useyubi=0;
	if (gid('myaccount_useyubi').checked) useyubi=1;
	var yubimode=0;
	if (gid('myaccount_yubimode')&&gid('myaccount_yubimode').checked) yubimode=2;
	
	var usegamepad=0;
	if (gid('myaccount_usegamepad').checked) usegamepad=1;
	
	if (pass1!=''&&pass1!=pass2){
		salert(document.dict['mismatching_password']);
		return;
	}
	
	if (usesms&&!valstr(osmscell)) return;
	
	var smscell=encodeHTML(osmscell.value);
	
	var quicklist=gid('myaccount_quicklist').value;
	var darkmode=gid('myaccount_darkmode').value;
	var dowoffset=gid('myaccount_dowoffset').value;
	
	if (!lastdarkmode) lastdarkmode=0;
	
	var rq=xmlHTTPRequestObject();
	rq.open('POST',document.appsettings.fastlane+'?cmd=setaccount&needkeyfile='+needkeyfile+'&usesms='+usesms+'&smscell='+smscell+'&usega='+usega+'&usegamepad='+usegamepad+'&useyubi='+useyubi+'&yubimode='+yubimode+'&quicklist='+quicklist+'&darkmode='+darkmode+'&dowoffset='+dowoffset,true);
	rq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	rq.onreadystatechange=function(){
		if (rq.readyState==4){
			document.appsettings.quicklist=quicklist=='1'?true:false;
			refreshtab('account',1);
			setTimeout(function(){marktabsaved('account',rq.responseText);},100);
			
			if (usegamepad) ajxjs(self.gamepad_register,'gamepad.js');
			
			if (lastdarkmode!=darkmode && self.resetdarkmode) resetdarkmode(darkmode);
			
		}	
	}
	
	rq.send('oldpass='+oldpass+'&pass='+pass1);
}

testgapin=function(){
	var opin=gid('myaccount_gatestpin');
	opin.value=opin.value.replace(/[^\d]/g,'',opin.value);
	if (!valint(opin)) return;
	ajxpgn('statusc',document.appsettings.codepage+'?cmd=testgapin',0,0,'pin='+opin.value,function(rq){
		salert(decodeURIComponent(rq.getResponseHeader('pinres')));	
	});	
}

resetgakey=function(gskey){
	if (!sconfirm('Are you sure you want to reset the authenticator?\nResetting this code will nullify your existing authenticator accounts.\nMake sure you sync up again.')) return;
	reloadtab('account','','resetgakey',null,null,null,gskey);	
}

trackkeyfilepad=function(d,container){
	var ta=gid('keyfileinfo_'+container);
	var pad=gid('keyfilepad_'+container);
	var w=pad.offsetWidth;
	var h=pad.offsetHeight;
	var box=gid('keyfilebox_'+container);
	
	if (box.stopped) {
		d.onmousemove=null;
		return;
	}
	
	if (!d.inited){
		d.inited=1;
		d.lastx=0; d.lasty=0;
		box.style.left='100px';
		box.idx=1;
		
		d.onmousemove=function(e){
			var x,y; if (e){x=e.clientX; y=e.clientY;} else {x=event.clientX;y=event.clientY;}
			if (Math.abs(d.lastx-x)>5&&Math.abs(d.lasty-y)>5){
				 ta.value=ta.value+x+','+y+',';
				 d.lastx=x;
				 d.lasty=y;
			}//abs
		}	
	}
}

keyfileboxover=function(d,container){
	var ta=gid('keyfileinfo_'+container);
	if (d.stopped) {
		d.onmouseout=null;
		return;
	}
	
	d.style.backgroundColor='#ffab00';
	d.onmouseout=function(e){
		d.style.backgroundColor='#848cf7';
			var x,y; if (e){x=e.clientX; y=e.clientY;} else {x=event.clientX;y=event.clientY;}
			ta.value=ta.value+'H'+hb()+'-'+x+','+y+',';			
	}
}

keyfileboxclick=function(d,container){
	var ta=gid('keyfileinfo_'+container);
	var pad=gid('keyfilepad_'+container);
	var w=pad.offsetWidth;
	var h=pad.offsetHeight;
	
	if (d.stopped) return;
	
	if (d.idx==null) d.idx=1;
	d.idx++;
	
	if (d.idx>5){
		pad.style.backgroundColor='#efffef';
		d.stopped=1;
		gid('keyfilepadview_'+container).style.display='none';
		gid('keyfiledownloader_'+container).style.display='block';
		return;
	}
	
	ta.value=ta.value+'X'+hb();
		
	d.style.backgroundColor='#848cf7';
	d.innerHTML=d.idx;
	
	d.style.left=Math.floor(Math.random()*(w-40))+'px';
	d.style.top=Math.floor(Math.random()*(h-40))+'px';
		
}

resethelpspots=function(userid,gskey){
	if (!sconfirm('Are you sure you want to reset the help tips?')) return;
	ajxpgn('userhelptopics_'+userid,document.appsettings.codepage+'?cmd=resethelpspots',0,0,null,null,null,null,gskey);	
	ajxpgn('muserhelptopics_'+userid,document.appsettings.codepage+'?cmd=resethelpspots',0,0,null,null,null,null,gskey);	
}

_checkpass=function(d,warnid){
	if (d.timer) clearTimeout(d.timer);
	if (d.value==''){
		d.style.background='#ffffff';
		gid(warnid).innerHTML='';
		return;	
	}
	d.timer=setTimeout(function(){
		checkpass(d,warnid);
	},300);
}

checkpass=function(d,warnid){
	if (d.value==''){
		d.style.background='#ffffff';
		gid(warnid).innerHTML='';
		return;				
	}
	
	ajxpgn(warnid,document.appsettings.codepage+'?cmd=checkpass',0,0,'pass='+encodeHTML(d.value),function(rq){
		var color=rq.getResponseHeader('passcolor');
		d.style.background=color;	
	});
}

msconnected=function(){
	if (gid('msconnector')) gid('msconnector').innerHTML='<br>Microsoft Account Connected';	
}

msgraphdisconnect=function(){
	if (!sconfirm('Are you sure you want to disconnect from your Microsoft account?')) return;
	reloadtab('account','','msgraphdisconnect');	
}

showuserprofile=function(userid,msg){
	if (msg!=null&&msg!=''){
		salert(msg);
		return;
	}

	ajxpgn('userprofile_'+userid,document.appsettings.codepage+'?cmd=showuserprofile&userid='+userid,0,0,null,function(){
		gid('mainuserprofile').src=document.appsettings.codepage+'?cmd=imguserprofile&thumb=1&hb='+hb();
		gid('mainuserprofile').className='';
		
		var classname='bigprofile';
		
		if (document.appsettings.uiconfig.toolbar_position=='left') classname+=' moveup';
		if (document.appsettings.uiconfig.enable_master_search) classname+=' hassearch';
		
		gid('logoutlink').className=classname;
	});
}

removeuserprofilepic=function(userid,gskey){
	if (!sconfirm('Are you sure you want to remove this profile picture?')) return;
	ajxpgn('userprofile_'+userid,
	document.appsettings.codepage+'?cmd=removeuserprofilepic&userid='+userid,0,0,null,function(){
		gid('mainuserprofile').src='imgs/t.gif';
		gid('mainuserprofile').className='admin-user';

		var classname='';
		
		if (document.appsettings.uiconfig.toolbar_position=='left') classname+=' moveup';
		if (document.appsettings.uiconfig.enable_master_search) classname+=' hassearch';
		
		gid('logoutlink').className=classname;
				
	},null,null,gskey);
}
