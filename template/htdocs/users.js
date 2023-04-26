showuser=function(userid,name,bookmark){
	addtab('user_'+userid,'<img src="imgs/t.gif" class="ico-user">'+name,'showuser&userid='+userid,function(){
		if (gid('cardsettings_'+userid)){
			if (!document.smartcard){
				if (!gid('needcert_'+userid).checked) gid('cardsettings_'+userid).style.display='none';
				gid('smartcardloader_'+userid).style.display='none';
			}
		}
	},null,{fastlane:1,bookmark:bookmark});	
}

_inline_lookupuser=function(d){
	if (d.lastkey!=null&&d.lastkey==d.value) return;
	d.lastkey=d.value;	
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		ajxpgn('userlist',document.appsettings.fastlane+'?cmd=slv_core__users&mode=embed&key='+encodeHTML(d.value)+gid('searchfilter_user').value);
	},200
	);	
}

adduser=function(roles,gskey){

	var suffix='new';
	var ologin=gid('login_'+suffix);
	var odispname=gid('dispname_'+suffix);

	var active=0;
	var virtual=0;
	if (gid('active_'+suffix).checked) active=1;
	if (gid('virtual_'+suffix).checked) virtual=1;

	var passreset=0;
	
	if (gid('passreset_'+suffix).checked) passreset=1;	
	
	valid=1;
	
	var opass=gid('newpass_'+suffix);
	var opass2=gid('newpass2_'+suffix);
	
	//delete the excessive validate rules
	if (!valstr(ologin)) valid=0;
	if (!valstr(odispname)) valid=0;
	
	if (!virtual){
		if (!valstr(opass)) valid=0;
		if (!valstr(opass2)) valid=0;

		//add more validation rules
		
		if (opass.value!=opass2.value){
			valid=0;
			salert(document.dict.mismatching_password);	
		}
	}
	
	if (!valid) return;
	
	var newpass=encodeHTML(opass.value);

	var login=encodeHTML(ologin.value);
	var dispname=encodeHTML(odispname.value);
	
	var groupnames=['users'];
	if (!virtual){
		for (var i=0;i<roles.length;i++){
			if (!gid('userrole_'+roles[i]+'_'+suffix)) {salert('Settings outdated; please reload your screen to continue;');return;}
			if (gid('userrole_'+roles[i]+'_'+suffix).checked) groupnames.push(roles[i]);
		}	
	}
	
	groupnames=groupnames.join('|');	
	
	var params=[];
	params.push('login='+login);
	params.push('dispname='+dispname);
	params.push('active='+active);
	params.push('virtual='+virtual);
	params.push('passreset='+passreset);
	params.push('groupnames='+groupnames);	
	
	
	reloadtab('user_new','','adduser&'+params.join('&'),null,'newpass='+newpass,null,gskey);
	
}

loadsmartcard=function(userid){
	if (document.cardreader){
	  document.cardreader.getcert(function(cert){
	  if (cert){
		gid('cardstatus_'+userid).innerHTML=cert.CN;
		gid('cert_'+userid).value=cert.certificateAsHex;
		marktabchanged('user_'+userid);
		return true;
	  }//cert
	  });
	} else {//no reader
		salert('Smartcard reader not supported');
		return false;
	}	
}

updateuser=function(userid,roles,gskey){
	var suffix=userid;
	var ologin=gid('login_'+suffix);
	var odispname=gid('dispname_'+suffix);
	var osmscell=gid('smscell_'+suffix);
	
	var active=0;
	var virtual=0;
	var needcert=0;
	var needkeyfile=0;
	var usesms=0;
	var usegamepad=0;
	
	var unlockga=0;
	if (gid('unlockga_'+suffix)&&gid('unlockga_'+suffix).checked) unlockga=1;

	if (gid('active_'+suffix).checked) active=1;
	if (gid('virtual_'+suffix).checked) virtual=1;
	if (gid('needcert_'+suffix).checked) needcert=1;
	if (gid('userneedkeyfile_'+suffix).checked) needkeyfile=1;
	if (gid('usesms_'+suffix).checked) usesms=1;
	if (gid('usegamepad_'+suffix).checked) usegamepad=1;

	//vendor auth 1

	var passreset=0;
	if (gid('passreset_'+suffix).checked) passreset=1;
		

	var newpass=gid('newpass_'+suffix).value;
	var newpass2=gid('newpass2_'+suffix).value;
	
	
	valid=1;
	var offender=null;
	
	//delete the excessive validate rules
	if (!valstr(ologin)) {valid=0;offender=offender||ologin;}
	if (!valstr(odispname)) {valid=0;offender=offender||odispname;}
	
	//vendor auth 2

	//add more validation rules
	if (!virtual){
		if (newpass!=newpass2){
			valid=0;
			salert('New passwords must match\nOr you may leave them blank');
			return;	
		}
	}
	
	if (usesms&&!valstr(osmscell)) {valid=0;offender=offender||osmscell;}
	
	
	if (!valid) {
		if (offender&&offender.focus) offender.focus();
		return;
	}
	
	//vendor auth 3
	
	var login=encodeHTML(ologin.value);
	var dispname=encodeHTML(odispname.value);
	
	var groupnames=['users'];

	var certname=encodeHTML(gid('cardstatus_'+userid).innerHTML);
	var cert=encodeHTML(gid('cert_'+userid).value);

	newpass=encodeHTML(newpass);

	var smscell=encodeHTML(osmscell.value);
		
	if (!virtual){
		for (var i=0;i<roles.length;i++){
			if (!gid('userrole_'+roles[i]+'_'+suffix)) {salert('Settings outdated; please reload your screen to continue;');return;}
			if (gid('userrole_'+roles[i]+'_'+suffix).checked) groupnames.push(roles[i]);
		}
	}
	
	groupnames=groupnames.join('|');
	
	
	var params=[];
	params.push('login='+login);
	params.push('dispname='+dispname);
	params.push('active='+active);
	params.push('virtual='+virtual);
	params.push('needcert='+needcert);
	params.push('needkeyfile='+needkeyfile);
	params.push('passreset='+passreset);
	params.push('groupnames='+groupnames);
	params.push('usesms='+usesms);
	params.push('usegamepad='+usegamepad);
	params.push('smscell='+smscell);
	params.push('unlockga='+unlockga);
	
	//vendor auth 4
	
	reloadtab('user_'+userid,ologin.value,'updateuser&userid='+userid+'&'+params.join('&'),function(rq){
		if (!document.smartcard) gid('cardsettings_'+userid).style.display='none';
		reloadview('core.users','userlist',true);
		if (rq.getResponseHeader('newlogin')!=null&&rq.getResponseHeader('newlogin')!='') gid('labellogin').innerHTML=decodeURIComponent(rq.getResponseHeader('newdispname'));
		if (rq.getResponseHeader('newdispname')!=null&&rq.getResponseHeader('newdispname')!='') gid('labeldispname').innerHTML=decodeURIComponent(rq.getResponseHeader('newdispname'));
		flashstatus('User '+ologin.value+' has been updated', 2000);
	},"pass="+newpass+"&needcert="+needcert+"&certname="+certname+"&cert="+cert,{fastlane:1},gskey);
	
}


deluser=function(userid,gskey){
	if (!sconfirm(document.dict['confirm_user_delete'])) return;
	
	reloadtab('user_'+userid,null,'deluser&userid='+userid,function(rq){
		//vendor auth

		closetab('user_'+userid);
		reloadview('core.users','userlist',true);
	},null,{fastlane:1},gskey);
}
