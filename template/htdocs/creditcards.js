addcreditcard=function(gskey){
	var occname=gid('ccname');
	var occnum=gid('ccnum');
	var occv=gid('ccv');
	
	var valid=1;
	var offender=null;
	
	if (!valstr(occname)) {valid=0;offender=offender||occname;}
	if (!valstr(occnum)) {valid=0;offender=offender||occnum;}
	if (!valstr(occv)) {valid=0;offender=offender||occv;}
	

	if (!valid){
		if (offender) offender.focus();
		return;	
	}
	
	var ccname=encodeHTML(occname.value);
	var ccnum=encodeHTML(occnum.value);
	var ccv=encodeHTML(occv.value);
	
	var expmon=gid('expmon').value;
	var expyear=gid('expyear').value;
	
	var params=[];
	
	params.push('ccname='+ccname); params.push('ccnum='+ccnum); params.push('ccv='+ccv); params.push('expmon='+expmon); params.push('expyear='+expyear);
	
	reloadtab('creditcards','','addcreditcard',null,params.join('&'),null,gskey);
		
}

setdefaultcreditcard=function(cardid,gskey){
	reloadtab('creditcards','','setdefaultcreditcard',null,'cardid='+cardid,null,null,null,gskey);
}

delcreditcard=function(cardid,gskey){
	if (!sconfirm('Are you sure you want to remove this card?')) return;
	reloadtab('creditcards','','delcreditcard',null,'cardid='+cardid,null,null,null,gskey);
}
