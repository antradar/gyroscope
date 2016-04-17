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
		ajxpgn('userlist',document.appsettings.fastlane+'?cmd=slv1&mode=embed&key='+encodeHTML(d.value));
	},300
	);	
}


adduser=function(){

	var suffix='new';
	var ologin=gid('login_'+suffix);

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
	
	if (!virtual){
		if (!valstr(opass)) valid=0;
		if (!valstr(opass2)) valid=0;

		//add more validation rules
		
		if (opass.value!=opass2.value){
			valid=0;
			alert('Password mismatch');	
		}
	}
	
	if (!valid) return;
	
	var newpass=opass.value;

	var login=encodeHTML(ologin.value);
	
	var groupnames=['users'];
	if (!virtual){
	<?
	foreach ($userroles as $role=>$label){
	?>
	if (!gid('userrole_<?echo $role;?>_'+suffix)) {alert('Settings outdates; please reload your screen to continue;');return;}
	if (gid('userrole_<?echo $role;?>_'+suffix).checked) groupnames.push('<?echo $role;?>');
	<?	
	}
	?>
	}
	
	groupnames=groupnames.join('|');	
	
	var params=[];
	params.push('login='+login);
	params.push('active='+active);
	params.push('virtual='+virtual);
	params.push('passreset='+passreset);
	params.push('groupnames='+groupnames);	
	
	reloadtab('user_new',ologin.value,'adduser&'+params.join('&'),function(req){
		var userid=req.getResponseHeader('newrecid');
		if (!document.smartcard) gid('cardsettings_'+userid).style.display='none';		
		reloadview(1,'userlist');
	},newpass);
	
}

loadsmartcard=function(userid){
	if (document.cardreader){
	  cert=document.cardreader.getcert();
	  if (cert){
		gid('cardstatus_'+userid).innerHTML=cert.CN;
		gid('cert_'+userid).value=cert.certificateAsHex;
		return true;
	  }//cert
	} else {//no reader
		alert('Smartcard reader not supported');
		return false;
	}	
}

updateuser=function(userid){
	var suffix=userid;
	var ologin=gid('login_'+suffix);
	
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

	//add more validation rules
	if (!virtual){
		if (newpass!=newpass2){
			valid=0;
			alert('New passwords must match\nOr you may leave them blank');
			return;	
		}
	}
	
	if (!valid) return;
	
	var login=encodeHTML(ologin.value);
	
	var groupnames=['users'];

	var certname=encodeHTML(gid('cardstatus_'+userid).innerHTML);
	var cert=encodeHTML(gid('cert_'+userid).value);

	newpass=encodeHTML(newpass);
	
	if (!virtual){
	<?
	foreach ($userroles as $role=>$label){
	?>
	if (!gid('userrole_<?echo $role;?>_'+suffix)) {alert('Settings outdates; please reload your screen to continue;');return;}
	if (gid('userrole_<?echo $role;?>_'+suffix).checked) groupnames.push('<?echo $role;?>');
	<?	
	}
	?>
	}
	
	groupnames=groupnames.join('|');
	
	
	var params=[];
	params.push('login='+login);
	params.push('active='+active);
	params.push('virtual='+virtual);
	params.push('needcert='+needcert);
	params.push('passreset='+passreset);
	params.push('groupnames='+groupnames);
	
	reloadtab('user_'+userid,ologin.value,'updateuser&userid='+userid+'&'+params.join('&'),function(){
		if (!document.smartcard) gid('cardsettings_'+userid).style.display='none';
		reloadview(1,'userlist');
		flashstatus('User '+login+' has been updated', 3000);
	},"pass="+newpass+"&needcert="+needcert+"&certname="+certname+"&cert="+cert,{fastlane:1});
	
}


deluser=function(userid){
	if (!confirm('Are you sure you want to remove this user?')) return;
	
	reloadtab('user_'+userid,null,'deluser&userid='+userid,function(){
		closetab('user_'+userid);
		reloadview(1,'userlist');
	},null,{fastlane:1});
}
