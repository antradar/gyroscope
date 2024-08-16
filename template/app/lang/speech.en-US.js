document.speechdict={
'hello':'Hello %%name%%',
'openrecord':'Opening record number %%idx%%',
'sorry':'Sorry what was that?',
'lookup':'Looking up %%target%%',
'lookupclear':'Lookup keyword has been cleared',
'nosuchnumber':"Sorry there's no record number %%idx%%",
'reading':'Reading %%subject%%.',
'optnotlooking':'You are not looking at a list of options',
'nooption':'You have no options',
'oneoption':'You have one option, which is %%option%%',
'topoptions':'Here are the top %%count%% options: ',
'welcomeback':'Welcome back!',

'accounts':'Okay, opening accounts',
'reports':'Okay, opening reports',
'settings':'Okay, opening settings',

'later':'Talk to you later!'	
}

speech_getcommand=function(phrase){
	var parts=phrase.split(' ');
	var cmd=parts[0];
	var target='';
	
	if (parts.length>10) return;
		
	for (var i=1;i<parts.length;i++){
		if (parts[i]=='') continue;
		target+=' '+parts[i];
	}
	
	target=target.trim();

	return {cmd:cmd,target:target,parts:parts}		
}

speech_parsenumber=function(target){
	var stem=target.replace(/number (\d+)/g,'x');
	if (stem!='x') return;
	
	var idx=parseInt(target.replace(/number /,''),10);

	return idx;	
}


speech_cleanup=function(phrase){
	phrase=phrase.toLowerCase();
	phrase=phrase.replace('go to','goto');
	phrase=phrase.replace('look up', 'lookup');
	phrase=phrase.replace('number one','number 1').replace('number two','number 2').replace('number to','number 2').replace('number too','number 2');
	phrase=phrase.replace('number three','number 3').replace('number four','number 4').replace('number for','number 4');
	phrase=phrase.replace('go home','gohome');
	phrase=phrase.replace('what are my options','options').replace('what are the options','options');
	phrase=phrase.replace('goodbye abby','cancel').replace('goodbye eddie','cancel').replace('goodbye','cancel');

	return phrase;	
}