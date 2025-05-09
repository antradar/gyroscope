function img_retry(d,max_retry,itr){
	if (max_retry==null) max_retry=10;
	if (d.itr==null) d.itr=0;
	
	if (d.itr>max_retry) return;
	
	var wait=20;
	var waits=[3,5,5,8,8,15,15];
	if (d.itr<waits.length) wait=waits[d.itr];
	
	d.itr++;
	
	console.log('reloading image in '+wait+' secs');
	
	setTimeout(function(){
		var srcs=d.src.split('?');
		var src=srcs[0]+'?';
		if (srcs.length>1) src+=srcs[1];
		src=src+'&hb='+hb()+'&retry='+d.itr;
		d.src=src;
	},wait*1000);
	
}