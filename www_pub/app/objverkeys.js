show_objcache_verkeys=function(){
	addtab('objcache_verkeys','Object Cache Version Keys','show_objcache_verkeys');	
}

objcache_ver_incr=function(verkey,gskey){
	ajxpgn('statusc', document.appsettings.codepage+'?cmd=objcache_ver_incr&verkey='+verkey,0,0,null,function(rq){
		if (gid('objcache_ver_'+verkey)) gid('objcache_ver_'+verkey).innerHTML=rq.getResponseHeader('new_ver'); 	
	},null,null,gskey);	
}
