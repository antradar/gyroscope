installmods=function(){
	var modids=[];
	var mods=gid('modlist').getElementsByTagName('input');
	for (var i=0;i<mods.length;i++){
		var mod=mods[i];
		if (mod.checked){
			modids.push(mod.value);
		}	
	}
	
	if (!modids.length){
		salert('select at least one module');
		return;	
	}
	
	ajxpgn('modinstaller',document.appsettings.codepage+'?cmd=installmods&modids='+modids.join(','));
}
