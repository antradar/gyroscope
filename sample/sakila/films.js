showfilm=function(filmid,title){
	//opens film view in a new tab; if the tab is already open it is switched to
	addtab('film_'+filmid,title,'showfilm&filmid='+filmid);	
}

_inline_lookupfilm=function(d){
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		ajxpgn('filmlist',document.appsettings.codepage+'?cmd=slv2&mode=embed&key='+encodeHTML(d.value));
	},300
	);	
}

lookuplanguage=function(d,usekey){
	var key=encodeHTML(d.value);
	if (!usekey) key=''; //direct focus won't add the keyword filter
	listlookup(d,'Languages','lookuplanguage&key='+key);	
}

_lookuplanguage=function(d){
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		lookuplanguage(d,true); //use the key phrase
	},100);	
}

lookupactor=function(d){
	listlookup(d,'Actors','lookupactor&key='+encodeHTML(d.value));	
}

_lookupactor=function(d){
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		lookupactor(d);
	},300);	
}

addfilmactor=function(filmid){
	var actorid=gid('filmactor_'+filmid).value2;
	if (!actorid){
		alert('An actor must be selected from the list.');
		return;	
	}	
	
	//the addfilmactor handler piggybacks the content of the updated list, which is injected to the filmactor container
	ajxpgn('filmactors_'+filmid,document.appsettings.codepage+'?cmd=addfilmactor&filmid='+filmid+'&actorid='+actorid,true,true,'',function(){

		//now that the film is added, reload the actor-film list, if it's already in an existing tab
		ajxpgn('actorfilms_'+actorid,document.appsettings.codepage+'?cmd=listactorfilms&actorid='+actorid);	
	});
}

delfilmactor=function(actorid,filmid){
	if (!confirm('Are you sure you want to remove this actor from the film?')) return;
	
	//the delfilmactor handler calls listfilmactors on the server side, piggybacking the updated content for the container
	ajxpgn('filmactors_'+filmid,document.appsettings.codepage+'?cmd=delfilmactor&filmid='+filmid+'&actorid='+actorid,true,true,'',function(){
		
		//content in the other tab is updated only if the container already exists
		ajxpgn('actorfilms_'+actorid,document.appsettings.codepage+'?cmd=listactorfilms&actorid='+actorid);	
	});
}

updatefilm=function(filmid){
	var otitle=gid('filmtitle_'+filmid);
	var oyear=gid('filmyear_'+filmid);
	
	if (!valstr(otitle)) return;
	if (!valfloat(oyear)) return;

	var title=encodeHTML(otitle.value);
	var year=oyear.value;
		
	var languageid=gid('filmlanguage_'+filmid).value2;
	if (!languageid) languageid=0; //will be ignored by the server handler
	var params=[];
	params.push('filmid='+filmid); params.push('title='+title); params.push('year='+year);
	params.push('languageid='+languageid);
	
	var description=gid('filmdescription_'+filmid).value; //do not encode, send as raw POST	
	
	reloadtab('film_'+filmid,otitle.value,'updatefilm&'+params.join('&'),function(){
		if (document.viewindex==2) reajxpgn('filmlist','lv1');	
	}, description); //send descriptoin as raw POST
	
}

delfilm=function(filmid){
	//this is an example of removing the entire record and its containing tab
	
	if (!confirm('Are you sure you want to remove this film and all its related records?')) return;
	
	reloadtab(
		'film_'+filmid, //reuse the containing tab
		null, //keep the tab title unchanged
		'delfilm&filmid='+filmid, //request to delete the film
		function(){ //close the tab and update the left view upon database update
			closetab('film_'+filmid); //or close after a 1-second delay: setTimeout(function(){closetab('film_'+filmid);},1000);
			if (document.viewindex==2) reajxpgn('filmlist','lv1');
		}
	);	
}