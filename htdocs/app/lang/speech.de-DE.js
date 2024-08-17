document.speechdict={
'hello':'Hallo %%name%%',
'openrecord':'Eröffne Artikelnummer %%idx%%',
'sorry':'Wie bitte?',
'lookup':'schaue nach %%target%%',
'lookupclear':'Schlüsselwort wurde gelöscht',
'nosuchnumber':"Hoppla, es gibt keinen Artikel %%idx%%",
'reading':'Jetzt lese ich %%subject%% aus. ',
'optnotlooking':'du siehst nicht auf eine Liste von Optionen',
'nooption':'es gibt keine verfügbaren Optionen',
'oneoption':'Deine einzige Option ist %%option%%',
'topoptions':'Deine Top %%count%% Optionen sind: ',
'welcomeback':'willkommen zurück',

'accounts':'Okay, die Konten',
'reports':'Gut, die Berichte öffnen',
'settings':'Okay, die Einstellungen öffnen',

'later':'Bis später!'
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
	var stem=target.replace(/nummer (\d+)/g,'x');
	if (stem!='x') return;
	
	var idx=parseInt(target.replace(/nummer /,''),10);

	return idx;	
}

speech_cleanup=function(phrase){
	phrase=phrase.toLowerCase();
	phrase=phrase.replace('offene','goto').replace('öffnen sie','goto').replace('offene sie','goto').replace('öffne','goto');
	phrase=phrase.replace('zeig mir','open').replace('zeigen sie','open').replace('zeig','open');
	phrase=phrase.replace('such', 'lookup').replace('suche','lookup').replace('find','lookup').replace('finden sie','lookup');
	phrase=phrase.replace('lesen sie','read').replace('lese','read').replace('lazer','read');
	phrase=phrase.replace('nummer eins','nummer 1').replace('nummer zwei','nummer 2');
	phrase=phrase.replace('nummer drei','nummer 3').replace('nummer vier','nummer 4').replace('nummer viel','nummer 4');
	phrase=phrase.replace('zurück zum anfang','gohome').replace('zurück zu anfang','gohome').replace('anfang','gohome');
	phrase=phrase.replace('wie lauten die optionen','options').replace('was sind die optionen','options').replace('optionen','options');
	phrase=phrase.replace('auf wiedersehen','cancel').replace('tschüss','cancel').replace('bis später','cancel');

	return phrase;	
}
