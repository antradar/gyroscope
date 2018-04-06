document.speechdict={
'hello':'%%name%% 你好',
'openrecord':'打开第%%idx%%个记录',
'sorry':'没听清。再说一遍',
'lookup':'查询%%target%%',
'lookupclear':'查询关键字已清空',
'nosuchnumber':"第%%idx%%个选项不存在",
'reading':'阅读%%subject%%。',
'optnotlooking':'选项栏没有打开',
'nooption':'没有可选项',
'oneoption':'唯一的选项是： %%option%%',
'topoptions':'头%%count%%个选项是： ',
'welcomeback':'回到首页',

'accounts':'用户设置',
'reports':'好的， 打开报告',
'settings':'行， 系统设置',

'later':'再见'
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
	var stem=target.replace(/第(\d+)个/g,'x');
	if (stem!='x') return;
	
	var idx=parseInt(target.replace('第','').replace('个',''),10);

	return idx;	
}

speech_cleanup=function(phrase){
	phrase=phrase.toLowerCase();
	phrase=phrase.replace('打开','goto ');
	phrase=phrase.replace('查看','open ').replace('显示','open ');	
	phrase=phrase.replace('阅读','read ').replace('朗读','read ');	
	phrase=phrase.replace('搜寻', 'lookup ').replace('寻找','lookup ').replace('查询','lookup ');
	phrase=phrase.replace('第一个','第1个').replace('第二个','第2个');
	phrase=phrase.replace('第三个','第3个').replace('第四个','第4个');
	phrase=phrase.replace('回到首页','gohome').replace('回到主页','gohome').replace('返回首页','gohome').replace('返回主页','gohome');
	phrase=phrase.replace('我有什么选项','options').replace('选项列表','options');
	phrase=phrase.replace('下次见','cancel').replace('再见','cancel');

	return phrase;	
}
