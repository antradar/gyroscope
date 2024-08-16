speech_startstop=function(mobile){
	gid('speechstart').mobile=mobile;
	ajxjs(self.speech_getcommand,'lang/speech.'+document.dict.speechlang+'.js');

	if (window.speechSynthesis&&window.speechSynthesis.speaking) {
		window.speechSynthesis.cancel();
		return;
	}
		
	if (!document.recog){
		document.recstop=null;
		document.voicetyping=null;
		document.recognition.continuous=false;		
		document.recognition.start();
		
		setTimeout(function(){
			var dispame='';
			if (gid('labeldispname')) dispname=gid('labeldispname').innerHTML.split(' ')[0];
			say(document.speechdict.hello.replace('%%name%%',dispname));
		},800);
				
		if (self.setnosleep) setnosleep(true);
		
	} else {
		document.recog=null;
		document.recstop=true;
		document.recognition.stop();
		if (self.setnosleep) setnosleep(false);
	}
}




speech_process=function(phrase,conf){
	//console.log(phrase,conf);
	if (conf<0.2) return;
	var ophrase=phrase;
	phrase=speech_cleanup(phrase);

	var res=speech_getcommand(phrase);
	if (res==null&&!document.voicetyping) return;
	if (res==null) res={cmd:'',target:'',parts:''};
	
	var cmd=res.cmd;
	var target=res.target;
	var parts=res.parts;
		
	/* enter look up keys here, activated by "Goto" voice command */
	var lookupkeys={
		'lvcore.users':'userkey',
		'lvcore.reports':'reportkey'
	};
		
	if (phrase=='start typing'||phrase=='starts typing') {document.recognition.continuous=true;document.voicetyping=true;say("Ready.");return;}
	if (phrase=='stop typing') {document.recognition.continuous=false;document.voicetyping=null;say("okay, I'm done typing for you.");return;}

	if (document.voicetyping&&ophrase!=''){
		if (document.hotspot!=null||(self.tinyMCE&&tinyMCE.activeEditor)){
			//console.log('typing '+ophrase);
			if (self.tinyMCE&&tinyMCE.activeEditor) tinyMCE.activeEditor.selection.setContent(ophrase+'. ');
			else {
				if (document.hotspot) pastetotextarea(document.hotspot.id,ophrase+'. ');
			}

		} else {
			say("I don't have a place to type into.");
			document.voicetyping=null;
			document.recognition.continuous=false;
		}

		return;
	}
	
	switch (cmd){
		case 'cancel': case 'cancer':	
			if (parts.length==1||document.dict.speechlang!='en-US') {document.recstop=true;document.recognition.stop();say(document.speechdict.later,1);}
			else {say("I'm not "+parts[1]+". I am Abby");}		
		break;
		case 'goto':
			switch(target){
				case 'account': case 'accounts':
				case 'die konto': case 'die konten': case 'die karte':
				case '????':
					ajxjs(self.showuser,'users_js.php');showview('core.users',null,1); say(document.speechdict.accounts); 
				break;
				case 'report': case 'reports': 
				case 'berichte': case 'die berichte':
				case 'os relatórios':  case 'o relatório':
				case '??':
					showview('core.reports',null,1); say(document.speechdict.reports); 
				break;
				case 'setting': case 'settings': 
				case 'as configurações': case 'a configuração':
				case '????':
				case 'die einstellungen': case 'die einstellung': 
					showview('core.settings',null,1); say(document.speechdict.settings); 
				break;
				default: console.log('Unknown target: '+target); say(document.speechdict.sorry);	
			}			
		break;
		case 'lookup':
			var lookup=lookupkeys['lv'+document.viewindex];
			if (lookup==null) return;
			
			if (target!='') say(document.speechdict.lookup.replace('%%target%%',target)); else say(document.speechdict.lookupclear);
			
			//if (target!='') target=target+'?'; //uncomment for soundex search
			gid(lookup).value=target;
			gid(lookup).soundex=true;
			var event=document.createEvent('Events');
			event.initEvent('keyup',true,false);
			gid(lookup).dispatchEvent(event);
			setTimeout(function(){gid(lookup).soundex=null},1000);
		
		break;
		
		case 'open':
			var idx=speech_parsenumber(target);
			
			var oidx=0;
			
			if (document.viewindex==null||!gid('lv'+document.viewindex)) break;
			var lv=gid('lv'+document.viewindex);
			var os=lv.getElementsByTagName('div');
			for (var i=0;i<os.length;i++){
				var o=os[i];
				if (o.className&&o.className=='listitem'||o.attributes.pickable){
					oidx++;
					if (idx==oidx){
						var event=document.createEvent('Events');
						event.initEvent('click',true,false);
						o.getElementsByTagName('a')[0].dispatchEvent(event);
						say(document.speechdict.openrecord.replace('%%idx%%',idx));
						return;
						
					}	
				}
			}
			if (parseInt(idx,10)==idx) say(document.speechdict.nosuchnumber.replace('%%idx%%',idx));
			console.log(idx);
		break;
		case 'options':
			
			if (document.viewindex==null||!gid('lv'+document.viewindex)) {
				say(document.speechdict.optnotlooking);	
				break;
			}
			var lv=gid('lv'+document.viewindex);
			var os=lv.getElementsByTagName('div');
			var osl=0;
			
			var options='';
			var firstoption='';
			
			for (var i=0;i<os.length;i++) {
				var o=os[i];
				if (o.className&&o.className=='listitem'||o.attributes.pickable) {
					var option=strip_tags(o.getElementsByTagName('a')[0].innerHTML);
					if (osl==0) firstoption=option;
					osl++;
					if (osl<=5) options+=osl+': '+option+'; ';
					
				}
			}
			
			switch (osl){
				case 0: say(document.speechdict.nooption); break;
				case 1: say(document.speechdict.oneoption.replace('%%option%%',firstoption)); break;
				default: say(document.speechdict.topoptions.replace('%%count%%',(osl>5?5:osl))+' '+options);
			}
			
			/*
			for (var i=0;i<os.length;i++){
				var o=os[i];
				if (o.className&&o.className=='listitem'||o.attributes.pickable){
					say(strip_tags(o.getElementsByTagName('a')[0].innerHTML));
				}
			}
			*/
			
		break;
		
		//add "ttstags" attribute to detail view textarea fields, comma separated
		case 'read': case 'weed': case 'weet': case 'reid': case "reid's": case 'grief': case 'reach': 
			if (document.currenttab!=null&&document.tabviews[document.currenttab]!=null){
				var os=document.tabviews[document.currenttab].getElementsByTagName('textarea');
				for (var i=0;i<os.length;i++){
					var o=os[i];
					if (o.getAttribute('ttstags')!=null){
						var ttstags=o.getAttribute('ttstags').split(',');
						for (var j=0;j<ttstags.length;j++){
							if (ttstags[j].toLowerCase().trim()==target.toLowerCase().trim()){
								say(document.speechdict.reading.replace('%%subject%%',ttstags[j])+": "+strip_tags(o.value));
								return;	
							}	
						}
					}	
				}	
			}
		break;		
		case 'gohome': say(document.speechdict.welcomeback); reloadtab('welcome',null,'wk',null,null,{noclose:1}); showtab('welcome');  break;	
					
	}
}


strip_tags=function(str, allow) {
  // making sure the allow arg is a string containing only tags in lowercase (<a><b><c>)
  allow = (((allow || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join('');

  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi;
  var commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
  return str.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
    return allow.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
  });
}


