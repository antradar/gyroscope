showactor=function(actorid,name){
	addtab('actor_'+actorid,name,'showactor&actorid='+actorid);	
}

_inline_lookupactor=function(d){
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		ajxpgn('actorlist',document.appsettings.codepage+'?cmd=slv1&mode=embed&key='+encodeHTML(d.value));
	},300
	);	
}

newactor=function(){
	
	//grab the original objects
	var ofname=gid('actor_fname_new');
	var olname=gid('actor_lname_new');
	
	//validate input
	if (!valstr(ofname)) return;
	if (!valstr(olname)) return;
	
	//prepare encoded arguments
	var fname=encodeHTML(ofname.value);
	var lname=encodeHTML(olname.value);
	
	//pack the query parameters
	params=[];
	params.push('fname='+fname); params.push('lname='+lname);
	
	//use the current tab to stage record insertion
	reloadtab('actor_new',null,'addactor&'+params.join('&'),function(req){
		var actorid=req.responseText; //retrieve the raw XHR object response
		if (parseInt(actorid,10)!=actorid) return; //validate result; the tab stays open upon error
		
		//do not close this tab; reload with new tab title, tab content and tab key
		reloadtab(
			'actor_new', //target tab
			ofname.value+' '+olname.value, //new tab title, using original, unencoded values
			'showactor&actorid='+actorid, //request to get the tab content
			null, //no callback function
			null, //no POST data
			{newkey:'actor_'+actorid} //new tab key
			);
		
		//update the actor list in parallel, if the actor list view is active		
		reloadview(1,'actorlist');	
	});
		
}

updateactor=function(actorid){
	//get the original objects
	var ofname=gid('actor_fname_'+actorid);
	var olname=gid('actor_lname_'+actorid);
	
	//validate that names have to be non-empty strings	
	if (!valstr(ofname)) return;
	if (!valstr(olname)) return;
	
	//url-encode the values
	var fname=encodeHTML(ofname.value);
	var lname=encodeHTML(olname.value);
	
	//prepare the query
	var params=[];
	params.push('actorid='+actorid); //don't miss this line!
	params.push('fname='+fname); params.push('lname='+lname);
	
	//update the current tab with new title and content, keep the tab key
	reloadtab('actor_'+actorid, ofname.value+' '+olname.value, 'updateactor&'+params.join('&'),function(){
		//update the actor list on the left as well
		reloadview(1,'actorlist');	
	});
	
}