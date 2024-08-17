document.speechdict={
'hello':'Olá %%name%%',
'openrecord':'Registro de abertura Número %%idx%%',
'sorry':'Você pode repetir isso?',
'lookup':'estou olhando para %%target%%',
'lookupclear':'A palavra-chave de pesquisa foi cancelada',
'nosuchnumber':"Sorry there's no record number %%idx%%",
'reading':'Reading %%subject%%.',
'optnotlooking':'Você não está olhando para uma lista de opções',
'nooption':'Você não tem opções',
'oneoption':'Sua única opção é %%option%%',
'topoptions':'Aqui estão as %%count%% melhores opções: ',
'welcomeback':'bem vindo de volta',

'accounts':'tudo bem, estou abrindo algumas contas',
'reports':'ok, estou mostrando os relatórios',
'settings':'claro, mostrando configurações',

'later':'Tchau!'	
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
	var stem=target.replace(/número (\d+)/g,'x');
	if (stem!='x') return;
	
	var idx=parseInt(target.replace(/número /,''),10);

	return idx;	
}


speech_cleanup=function(phrase){
	phrase=phrase.toLowerCase();
	phrase=phrase.replace('abrir','goto').replace('abra','goto');
	phrase=phrase.replace('mostre-me','open').replace('mostre o','open').replace('mostre','open');
	phrase=phrase.replace('look up', 'lookup');
	phrase=phrase.replace('número um','número 1').replace('number two','number 2').replace('number to','number 2').replace('number too','number 2');
	phrase=phrase.replace('number three','number 3').replace('number four','number 4').replace('number for','number 4');
	phrase=phrase.replace('recomeçar','gohome').replace('voltar ao inicio','gohome').replace('voltar ao início','gohome');
	phrase=phrase.replace('quais são as opções','options').replace('que opções eu tenho','options');
	phrase=phrase.replace('tchau','cancel').replace('até mais','cancel').replace('até logo','cancel');

	return phrase;	
}