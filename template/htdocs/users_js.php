<?php
include 'settings.php';
?>
showuser=function(userid,name){
	addtab('user_'+userid,name,'showuser&userid='+userid,function(){
		if (gid('cardsettings_'+userid)){
			if (!document.smartcard) gid('cardsettings_'+userid).style.display='none';
		}
	},null,{fastlane:1});	
}

_inline_lookupuser=function(d){
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		ajxpgn('userlist',document.appsettings.fastlane+'?cmd=slv_core__users&mode=embed&key='+encodeHTML(d.value));
	},300
	);	
}


adduser=function(){

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
			salert('Password mismatch');	
		}
	}
	
	if (!valid) return;
	
	var newpass=opass.value;

	var login=encodeHTML(ologin.value);
	var dispname=encodeHTML(odispname.value);
	
	var groupnames=['users'];
	if (!virtual){
	<?
	foreach ($userroles as $role=>$label){
	?>
	if (!gid('userrole_<?echo $role;?>_'+suffix)) {salert('Settings outdated; please reload your screen to continue;');return;}
	if (gid('userrole_<?echo $role;?>_'+suffix).checked) groupnames.push('<?echo $role;?>');
	<?	
	}
	?>
	}
	
	groupnames=groupnames.join('|');	
	
	var params=[];
	params.push('login='+login);
	params.push('dispname='+dispname);
	params.push('active='+active);
	params.push('virtual='+virtual);
	params.push('passreset='+passreset);
	params.push('groupnames='+groupnames);	
	
	
	reloadtab('user_new',ologin.value,'adduser&'+params.join('&'),null,newpass);
	
}

loadsmartcard=function(userid){
	if (document.cardreader){
	  document.cardreader.getcert(function(cert){
	  if (cert){
		gid('cardstatus_'+userid).innerHTML=cert.CN;
		gid('cert_'+userid).value=cert.certificateAsHex;
		return true;
	  }//cert
	  });
	} else {//no reader
		salert('Smartcard reader not supported');
		return false;
	}	
}

updateuser=function(userid){
	var suffix=userid;
	var ologin=gid('login_'+suffix);
	var odispname=gid('dispname_'+suffix);
	
	var active=0;
	var virtual=0;
	var needcert=0;

	if (gid('active_'+suffix).checked) active=1;
	if (gid('virtual_'+suffix).checked) virtual=1;
	if (gid('needcert_'+suffix).checked) needcert=1;


	var passreset=0;
	if (gid('passreset_'+suffix).checked) passreset=1;
		

	var newpass=gid('newpass_'+suffix).value;
	var newpass2=gid('newpass2_'+suffix).value;
	
	
	valid=1;
	
	//delete the excessive validate rules
	if (!valstr(ologin)) valid=0;
	if (!valstr(odispname)) valid=0;

	//add more validation rules
	if (!virtual){
		if (newpass!=newpass2){
			valid=0;
			salert('New passwords must match\nOr you may leave them blank');
			return;	
		}
	}
	
	if (!valid) return;
	
	var login=encodeHTML(ologin.value);
	var dispname=encodeHTML(odispname.value);
	
	var groupnames=['users'];

	var certname=encodeHTML(gid('cardstatus_'+userid).innerHTML);
	var cert=encodeHTML(gid('cert_'+userid).value);

	newpass=encodeHTML(newpass);
	
	if (!virtual){
	<?
	foreach ($userroles as $role=>$label){
	?>
	if (!gid('userrole_<?echo $role;?>_'+suffix)) {salert('Settings outdated; please reload your screen to continue;');return;}
	if (gid('userrole_<?echo $role;?>_'+suffix).checked) groupnames.push('<?echo $role;?>');
	<?	
	}
	?>
	}
	
	groupnames=groupnames.join('|');
	
	
	var params=[];
	params.push('login='+login);
	params.push('dispname='+dispname);
	params.push('active='+active);
	params.push('virtual='+virtual);
	params.push('needcert='+needcert);
	params.push('passreset='+passreset);
	params.push('groupnames='+groupnames);
	
	reloadtab('user_'+userid,ologin.value,'updateuser&userid='+userid+'&'+params.join('&'),function(rq){
		if (!document.smartcard) gid('cardsettings_'+userid).style.display='none';
		reloadview('core.users','userlist');
		if (rq.getResponseHeader('newlogin')!=null&&rq.getResponseHeader('newlogin')!='') gid('labellogin').innerHTML=decodeURIComponent(rq.getResponseHeader('newlogin'));
		if (rq.getResponseHeader('newdispname')!=null&&rq.getResponseHeader('newdispname')!='') gid('labeldispname').innerHTML=decodeURIComponent(rq.getResponseHeader('newdispname'));
		flashstatus('User '+ologin.value+' has been updated', 3000);
	},"pass="+newpass+"&needcert="+needcert+"&certname="+certname+"&cert="+cert,{fastlane:1});
	
}


deluser=function(userid){
	if (!sconfirm(document.dict['confirm_user_delete'])) return;
	
	reloadtab('user_'+userid,null,'deluser&userid='+userid,function(){
		closetab('user_'+userid);
		reloadview('core.users','userlist');
	},null,{fastlane:1});
}
