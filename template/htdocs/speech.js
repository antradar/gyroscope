speech_startstop=function(mobile){
	gid('speechstart').mobile=mobile;

	if (window.speechSynthesis&&window.speechSynthesis.speaking) {
		window.speechSynthesis.cancel();
		return;
	}
		
	if (!document.recog){
		document.recstop=null;
				
		document.recognition.start();
		
		setTimeout(function(){
			var dispame='';
			if (gid('labeldispname')) dispname=gid('labeldispname').innerHTML.split(' ')[0];
			say('Hello '+dispname);
		},800);
				
		if (self.setnosleep) setnosleep(true);
		
	} else {
		
		document.recstop=true;
		document.recognition.stop();
		
		if (self.setnosleep) setnosleep(false);
	}
}




speech_process=function(phrase,conf){
	console.log(phrase,conf);
	if (conf<0.2) return;
	
	phrase=phrase.toLowerCase();
	phrase=phrase.replace('go to','goto');
	phrase=phrase.replace('look up', 'lookup');
	phrase=phrase.replace('number one','number 1').replace('number two','number 2').replace('number to','number 2').replace('number too','number 2');
	phrase=phrase.replace('number three','number 3').replace('number four','number 4').replace('number for','number 4');
	phrase=phrase.replace('go home','gohome');
	phrase=phrase.replace('what are my options','options').replace('what are the options','options');
	phrase=phrase.replace('goodbye abby','cancel').replace('goodbye eddie','cancel').replace('goodbye','cancel');

	var parts=phrase.split(' ');
	var cmd=parts[0];
	var target='';
	
	if (parts.length>10) return;
		
	for (var i=1;i<parts.length;i++){
		if (parts[i]=='') continue;
		target+=' '+parts[i];
	}
	
	target=target.trim();
	
	/* enter look up keys here, activated by "Goto" voice command */
	var lookupkeys={
		'lvcore.users':'userkey',
		'lvcore.reports':'reportkey'
	};
	
	switch (cmd){
		case 'cancel': case 'cancer':	
			if (parts.length==1) {document.recstop=true;document.recognition.stop();say('Talk to you later!',1);}
			else {say("I'm not "+parts[1]+". I am Abby");}		
		break;
		case 'goto':
			switch(target){
				case 'account': case 'accounts': ajxjs(self.showuser,'users_js.php');showview('core.users',null,1); say('Okay, opening accounts'); break;
				case 'report': case 'reports': showview('core.reports',null,1); say('Okay, opening reports'); break;
				case 'setting': case 'settings': showview('core.settings',null,1); say('Okay, opening settings'); break;
				default: console.log('Unknown target: '+target); say('Sorry what was that?');	
			}			
		break;
		case 'lookup':
			var lookup=lookupkeys['lv'+document.viewindex];
			if (lookup==null) return;
			
			if (target!='') say('Looking up '+target); else say('Lookup keyword has been cleared');
			
			//if (target!='') target=target+'?'; //uncomment for soundex search
			gid(lookup).value=target;
			gid(lookup).soundex=true;
			var event=document.createEvent('Events');
			event.initEvent('keyup',true,false);
			gid(lookup).dispatchEvent(event);
			setTimeout(function(){gid(lookup).soundex=null},1000);
		
		break;
		
		case 'open':
			var stem=target.replace(/number (\d+)/g,'x');
			if (stem!='x') return;
			
			var idx=parseInt(target.replace(/number /,''),10);
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
						say('Opening record number '+idx);
						return;
						
					}	
				}
			}
			say("Sorry there's no record number "+idx);
			console.log(idx);
		break;
		case 'options':
			
			if (document.viewindex==null||!gid('lv'+document.viewindex)) {
				say('You are not looking at a list of options');	
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
				case 0: say('You have no options'); break;
				case 1: say('You have one option, which is '+firstoption); break;
				default: say('Here are the top '+(osl>5?5:osl)+' options: '+options);
			}
			
			for (var i=0;i<os.length;i++){
				var o=os[i];
				if (o.className&&o.className=='listitem'||o.attributes.pickable){
					say(strip_tags(o.getElementsByTagName('a')[0].innerHTML));
				}
			}
		break;
		
		case 'read': case 'weed': case 'weet': case 'reid': case "reid's": case 'grief': case 'reach': //add "ttstags" attribute to detail view textarea fields, comma separated
			if (document.currenttab!=null&&document.tabviews[document.currenttab]!=null){
				var os=document.tabviews[document.currenttab].getElementsByTagName('textarea');
				for (var i=0;i<os.length;i++){
					var o=os[i];
					if (o.getAttribute('ttstags')!=null){
						var ttstags=o.getAttribute('ttstags').split(',');
						for (var j=0;j<ttstags.length;j++){
							if (ttstags[j].toLowerCase().trim()==target.toLowerCase().trim()){
								say("Reading "+ttstags[0]+": "+strip_tags(o.value));
								return;	
							}	
						}
					}	
				}	
			}
		break;		
		case 'gohome': say('Welcome back!'); reloadtab('welcome',null,'wk',null,null,{noclose:1}); showtab('welcome');  break;	
					
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


