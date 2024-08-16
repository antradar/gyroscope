gsnotes_init=function(silent){
	if (!window.localStorage){
		gid('gsnotes_unsupported').style.display='block';
		return;	
	}
	
	gsnotes_loadnotes(silent);
	
	gsnotes_syncindicator();
	
}

gsnotes_syncindicator=function(){
	if (gid('gsnotesclip')){
		if (document.gsnotes.length>0||(navigator.onLine!=null&&!navigator.onLine)) gid('gsnotesclip').style.display='inline';
		else gid('gsnotesclip').style.display='none';
	}
	
	if (gid('gsnotesclipicon')){
		if (document.gsnotes.length>0||(navigator.onLine!=null&&!navigator.onLine)) {
			gid('gsnotesclipicon').style.display='inline';
			
		} else {
			gid('gsnotesclipicon').style.display='none';
		}

		var iconcount=0;
		var os=gid('toollist').getElementsByTagName('a');
		for (var i=0;i<os.length;i++) if (os[i].offsetWidth>10) iconcount++;

		gid('toollistcontent').style.width=(52*(iconcount+1))+'px';

	}	
}

gsnotes_loadnotes=function(silent){
	var strnotes=window.localStorage.getItem('gsnotes');
	if (null==strnotes) document.gsnotes=[];
	else {
		document.gsnotes=eval('('+strnotes+')');
	}
	
	if (!silent) gsnotes_renderlist();
}

gsnotes_savenotes=function(){
	if (null==document.gsnotes) document.gsnotes=[];
	
	window.localStorage.setItem('gsnotes',JSON.stringify(document.gsnotes));
	gsnotes_syncindicator();
}

gsnotes_addnote=function(notetype,noteobj){
	var notedate=hb();
	gsnotes_loadnotes(1);

	var idx=document.gsnotes.length;
	document.gsnotes[idx]={notedate:notedate,notetype:notetype,noteobj:noteobj}
	gsnotes_savenotes();
	
	gid('gsnotes_form').innerHTML='';
	
	gsnotes_renderlist();

	gsnotes_setnotetype(notetype); //show the same form again
}

gsnotes_delnote=function(idx,silent){
	if (silent==null&&!confirm('Are you sure you want to remove this entry from the offline clipboard?')) return;
	
	gsnotes_loadnotes(1);
	
	delete(document.gsnotes[idx]);
	document.gsnotes.splice(idx,1);
	gsnotes_savenotes();
	gsnotes_renderlist();	
}

gsnotes_setnotetype=function(notetype){

	if (notetype==''){
		gid('gsnotes_form').innerHTML='';
		return false;	
	}
	
	var func='gsnoteform_'+notetype;
	if (!self[func]){
		alert(func+' is not implemented');
		return false;	
	}
	
	self[func]();
	

}


gsnotes_renderlist=function(){
	var html=[];
	for (var i=0;i<document.gsnotes.length;i++){
		var obj=document.gsnotes[i];
		if (obj==null) continue;
		var notetype=obj.notetype;
		var noteobj=obj.noteobj;
		var notedesc=notetype;
		var func='gsnotes_preview_'+notetype;
		if (self[func]!=null) notedesc=self[func](noteobj);
		html.push('<div class="gsnoteitem"><span class="gsnotename">#'+(i+1)+': '+notedesc+'</span><span class="gsnotedel"><a onclick="gsnotes_delnote('+i+');">&times;</a></span>');
		html.push('<div style="clear:both;"></div>');
		html.push('</div>');
	}//for
	//console.dir(document.gsnotes);	
	
	
	if (gid('gsnotes_list')) gid('gsnotes_list').innerHTML=html.join('');
	if (gid('lkvt')) {
		if (document.gsnotes.length>0) gsnotes_listclips();
		else hidelookup();
	}
}

gsnotes_esc=function(str){
	return (str+"").replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

gsnotes_apply=function(idx){
	if (!document.gsnotes) return;
	
	var obj=document.gsnotes[idx];
	var notetype=obj.notetype;
	var noteobj=obj.noteobj;
	
	var func='gsnotes_apply_'+notetype;
	if (self[func]) self[func](noteobj, idx);
	else {
		salert('The behavior of this data type is undefined');	
	}
}

gsnotes_listclips=function(keyfilter){
	
	gsnotes_loadnotes(1);
	
	var truelength=0;
	

	
	gid('lkvt').innerHTML='Offline Clipboard';
	
	var html=[];
	html.push('<div class="section">');
	
	for (var i=0;i<document.gsnotes.length;i++){
		var obj=document.gsnotes[i];
		if (obj==null) continue;
		var notetype=obj.notetype;
		if (keyfilter!=null&&notetype!=keyfilter) continue;
		var noteobj=obj.noteobj;
		var notedesc=notetype;
		var func='gsnotes_preview_'+notetype;
		if (self[func]!=null) notedesc=self[func](noteobj);
		html.push('<div class="listitem"><a onclick="gsnotes_apply('+i+');">#'+(i+1)+': '+notetype+'<br>'+notedesc+'</a> &nbsp; <a onclick="gsnotes_delnote('+i+');"><img src="imgs/t.gif" class="img-del"></a>');
		html.push('<div style="clear:both;"></div>');
		html.push('</div>');
		truelength++;
	}
	
	if (truelength==0) return;	
	
	html.push('</div>');
	html.push('<textarea id="gsnotes_clipboard" style="display:none;"></textarea>');
	
	if (document.iphone_portrait==1&&navigator.onLine){
		gid('fsview').innerHTML=html.join('');
		gid('fstitle').innerHTML='Offline Clipboard';
		showfs();
		return;	
	}
			
	gid('lkvc').innerHTML=html.join('');
	showlookup();
}

//// application specific plugins are implmented below:

gsnoteform_plain=function(){
	var html=[];
	
	html.push('<textarea id="gsnotes_plaintext" style="width:80%;height:140px;" placeholder="max: 140 chars" maxlength="140"></textarea>');
	html.push('<div><button onclick="gsnotes_add_plain();">Add</button></div>');
	
	gid('gsnotes_form').innerHTML=html.join('');
}

gsnotes_add_plain=function(){
	var plainnotes=gid('gsnotes_plaintext');
	if (plainnotes.value=='') return;
	gsnotes_addnote('plain',{text:plainnotes.value});	
}

gsnotes_preview_plain=function(obj){
	return '<span style="color:#848cf7;">"</span>'+gsnotes_esc(obj.text)+'<span style="color:#848cf7;">"</span>';	
}

gsnotes_apply_plain=function(obj, idx){
	//if (document.hotspot){
	//	document.hotspot.value=obj.text;
	//} else {
		gid('gsnotes_clipboard').style.display='inline';
		gid('gsnotes_clipboard').value=obj.text;
		gid('gsnotes_clipboard').select();
		document.execCommand('copy');
		gid('gsnotes_clipboard').style.display='none';

		salert('The text is now copied to the clipboard');
	//}	
}

/////

gsnoteform_user=function(){
	var html=[];
	html.push('<div class="gsnotes_inputrow">');
	html.push('<div class="gsnotes_formlabel">User Name:</div>');
	html.push('<div><input id="gsnotes_userlogin"></div>');
	html.push('</div>');
	html.push('<div class="gsnotes_inputrow">');
	html.push('<div class="gsnotes_formlabel">Display Name:</div>');
	html.push('<div><input id="gsnotes_userdispname"></div>');
	html.push('</div>');
	html.push('<div><button onclick="gsnotes_add_user();">Add</button></div>');
	
	gid('gsnotes_form').innerHTML=html.join('');

}

gsnotes_add_user=function(){
	var login=gid('gsnotes_userlogin').value;
	var dispname=gid('gsnotes_userdispname').value;
	if (login==''||dispname=='') return;
	
	gsnotes_addnote('user',{login:login,dispname:dispname});	
}

gsnotes_preview_user=function(obj){
	return "add user: "+gsnotes_esc(obj.login)+" <em>("+gsnotes_esc(obj.dispname)+")</em> ";
}

gsnotes_apply_user=function(obj, idx){
	closetab('user_new');
	addtab('user_new','<img src="imgs/t.gif" class="ico-user">New User','newuser',function(){
		gid('login_new').value=obj.login;
		gid('dispname_new').value=obj.dispname;	
	});
}
