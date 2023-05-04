/*
Nano Ajax Library
(c) Schien Dong, Antradar Software Inc.

License: www.antradar.com/license.php
Documentation: www.antradar.com/docs-nano-ajax-manual

Warning: this copy of Nano Ajax Library is modified for running in Gyroscope. Use the public version for general purpose applications.

ver g5.1
*/

function gid(d){return document.getElementById(d);}

function hb(){var now=new Date(); var hb=now.getTime();return hb;}

function ajxb(u,data,callback,myhb){
	var method='POST';
	if (data==null) method='GET';
	
	if (document.wssid) u=u+'&wssid_='+document.wssid;
	
	var rq=xmlHTTPRequestObject();
	if (myhb==null) myhb=hb();
	rq.open(method, u+'&hb='+myhb,false);
	rq.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	rq.send(data);
	if (callback) callback(rq);
	return rq.responseText;	
}

function ajxnb(rq,u,f,data){
	if (document.wssid) u=u+'&wssid_='+document.wssid;	
	rq.onreadystatechange=f;
	var method='POST'; if (!data) method='GET';
	rq.open(method,u+'&hb='+hb(),true);
	rq.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	rq.send(data);  	
}

function reajxpgn(c,p){
	var ct=gid(c);
	if (ct==null) return;
	if (!ct.reloadparams) {
		if (p) reajxpgn(p); else console.warn('reload params not set');
		return;
	}
	ajxpgn(c,ct.reloadparams.u,ct.reloadparams.d,ct.reloadparams.e,ct.reloadparams.data,ct.reloadparams.callback,ct.reloadparams.slowtimer,ct.reloadparams.runonce);		
}

function cancelgswi(ct){
	if (ct.gswi){
		if (ct.gswi.parentNode) ct.gswi.parentNode.removeChild(ct.gswi);
		ct.style.opacity=''; ct.style.alpha=''; ct.style.color='';
		ct.gswi=null;
	}	
}

/*
runonce modes:
0 - previous requests are cancelled at best effort but no guarantee; the response of the last record is displayed
1 - first request is sent and received; subsequent requests are blocked until the first request finishes
2 - all the requests are sent; display may not be in order
*/

function ajxpgn(c,u,d,e,data,callback,slowtimer,runonce,gskey,creds,headless){
	var ct=gid(c);
	if (ct==null){
		if (headless) ct=document.body;
		else return;
	}
	
	if (runonce==1&&ct.reqobj!=null) return;
	
	ct.reloadparams={u:u,d:d,e:e,data:data,callback:callback,slowtimer:slowtimer,runonce:runonce};
	
	if (document.wssid) u=u+'&wssid_='+document.wssid;
	
  if (gskey!=null) {
	  //rq.setRequestHeader('X-GSREQ-KEY',gskey);
	  if (data==null) data='X-GSREQ-KEY='+gskey;
	  else data+='&X-GSREQ-KEY='+gskey;
	  
  }
			
	var f=function(c){return function(){
		if (rq.readyState==4){
			
    	    var xtatus=creds==null?rq.getResponseHeader('X-STATUS'):200;
		    if (rq.status==403||(xtatus|0)==403){
				if (self.skipconfirm) skipconfirm();
				window.location.href='login.php';
				return;
			}

			if (ct.reqobj!=null){
				ct.reqobj=null;
			}

			if (ct.slowtimer) clearTimeout(ct.slowtimer);
			
			if (!headless) cancelgswi(ct);		
			
			if (rq.status==401||(xtatus|0)==401){
				ajxjs(self.showgssubscription,'gssubscriptions.js');
				showgssubscription();
			    return;
			}
				

			var apperror=creds==null?rq.getResponseHeader('apperror'):null;
			if (apperror!=null&&apperror!=''){
				if (ct.slowtimerorg&&!headless){ct.innerHTML=ct.slowtimerorg;ct.slowtimerorg=null;}
				var errfunc=creds==null?rq.getResponseHeader('errfunc'):null;
				if (callback&&typeof(callback)=='object'&&callback.length>0){
					callback[1](errfunc,decodeURIComponent(apperror),rq);					
				} else {
					if (errfunc!=null&&errfunc!=''&&self[errfunc.toLowerCase()]){
						self[errfunc.toLowerCase()](decodeURIComponent(apperror));
					} else salert(decodeURIComponent(apperror));
				}
				
				return;	
			}

			
			if (ct.abortflag) {ct.abortflag=null;return;}
			
			var ta=0;
			if (document.nanoperf) ta=hb();

			
			if (!headless) {
					ct.innerHTML=rq.responseText;
			}
			
			if (d) ct.style.display='block';
			
			if (e){
				//supposedly deprecated, nothing to eval here
				
				var i;
				var scripts=gid(c).getElementsByTagName('script');
				for (i=0;i<scripts.length;i++) eval(scripts[i].innerHTML);
				scripts=null;				
				
			}
			
			
			if (document.nanoperf){
				var delta=hb()-ta;
				if (document.nanoavg==null) document.nanoavg=0;
				document.nanoavg=document.nanoavg*0.8+delta*0.2;
				if (self.warnsyslow){
					if (document.nanoavg>document.nanoperf) warnsyslow(true); else warnsyslow(false);
				}	
			}			
			
			if (callback){
				if (typeof(callback)=='object'&&callback.length>0) callback[0](rq); else callback(rq);
			}
		}	  
	}}	
	
	
	var rq=xmlHTTPRequestObject();
		
	if (creds) try{rq.withCredentials=true;} catch (e) {}
	
	if (ct.reqobj!=null&&runonce!=2){
		ct.abortflag=1;
		ct.reqobj.abort();
		cancelgswi(ct);
	}
	ct.reqobj=rq;
	if (!slowtimer) slowtimer=800;
	
	if (runonce!=2){
		ct.slowtimer=setTimeout(function(){
	
			if (ct.style.display=='none') ct.style.display='block';
			var first=ct.firstChild;
			if (ct.gswi) return;
			var wi=document.createElement('img'); wi.src='imgs/hourglass.gif'; ct.gswi=wi;
			if (gid('statusc')!=ct) wi.style.margin='10px';
			if (first==null) ct.appendChild(wi); else ct.insertBefore(wi,first);
			ct.style.opacity=0.5; ct.style.filter='alpha(50)'; ct.style.color='#999999';
			//ct.innerHTML='<img src="imgs/hourglass.gif" class="hourglass"><span style="opacity:0.5;filter:alpha(opacity=50);color:#999999;">'+ct.innerHTML+'</span>';
		},slowtimer);
	}

	ajxnb(rq,u,f(c),data);
	
}



function ajxcss(f,css,cachekey,killflag){
	if (f==null) {
	  	var csl=document.createElement('link');
		csl.setAttribute('rel','stylesheet');
		csl.setAttribute('type','text/css');
		csl.setAttribute('href',css);
		if (cachekey) csl.setAttribute('id','ajxcss_'+cachekey);
		if (killflag&&gid('ajxcss_'+killflag)){
			gid('ajxcss_'+killflag).parentNode.removeChild(gid('ajxcss_'+killflag));	
		}
		document.getElementsByTagName("head").item(0).appendChild(csl);
	}
}

function xajx(url){
	if (!document.xjs_transport){
		var xjs=document.createElement('div');
		document.body.appendChild(xjs);
		document.xjs_transport=xjs;
	}
	
	var xjs=document.xjs_transport;
	var rq=document.createElement('script');
	rq.setAttribute('src',url);
	xjs.appendChild(rq);
	
	if (xjs.gc) clearTimeout(xjs.gc);
	xjs.gc=setTimeout(function(){
		xjs.innerHTML='';
	},3000);  
}

function ajxjs(f,js){if (f==null) eval(ajxb(js+'?'));} //unsafe

function sajxjs(flag,js){
	if (self[flag]!=null) return;
	var myhb=hb();
	ajxb(js+'?',null,null,myhb);
	xajxjs(flag,js+'?&hb='+myhb,function(){});
}

function xajxjs(strflags,src,callback,defer){
	var flags=strflags.split('.');
	var cur=self[flags[0]];
	var defed=1;
	if (!cur) defed=0;
	for (var i=1;i<flags.length;i++){
		if (cur) cur=cur[flags[i]];
		if (cur==null){defed=0;break;}
	}
	
	if (!defed&&!defer){
		var script=document.createElement('script');
		script.src=src;
		document.body.appendChild(script);
	}
	
	if (!defed) {setTimeout(function(){xajxjs(strflags,src,callback,1);},5);return;}
	callback();
}

function xmlHTTPRequestObject() {
	var obj = false;
	var objs = ["Microsoft.XMLHTTP","Msxml2.XMLHTTP","MSXML2.XMLHTTP.3.0","MSXML2.XMLHTTP.4.0"];
	var success = false;
	for (var i=0; !success && i < objs.length; i++) {
		try {
			obj = new ActiveXObject(objs[i]);
			success = true;
		} catch (e) { obj = false; }
	}

	if (!obj) obj = new XMLHttpRequest();
	return obj;
}

function tagobjs(parentid,tags){
	var os=[];
	for (var i=0;i<tags.length;i++){
		var tag=tags[i];
		var oos=gid(parentid).getElementsByTagName(tag);
		for (ii=0;ii<oos.length;ii++) os.push(oos[ii]);
	}
	return os;
	
}

/*
Example:

mapobjevents(
	tagobjs('contactlist',['a','span']),
	{
		'anchor~onclick~showrec':['aid','atitle'],
		'deleter~onclick~delrec':['aid']
	}
);
			
*/
mapobjevents=function(os,ems,sel){
	if (sel==null) sel='atype';
	for (var i=0;i<os.length;i++){
		var o=os[i];
		if (!o.attributes||!o.attributes[sel]) continue;
		for (var k in ems){
			var parts=k.split('~');
			var typ=parts[0];
			if (typ!=o.attributes[sel].value) continue;
			var ev=parts[1];
			var func=parts[2];
			var attrs=ems[k];
			var params=[];
			for (var ii=0;ii<attrs.length;ii++) if (o.attributes[attrs[ii]]) params.push(o.attributes[attrs[ii]].value);
			
			self[func+'_']=function(fc,pms){
				return function(){
					if (self[fc]==null) console.log('Missing funciton: '+fc);
					else {
						if (document.debugevents) console.log(fc,pms); //debug
						self[fc].apply(null,pms);
					}
				}
			}
			params.push(o);
			o[ev]=self[func+'_'](func,params);
		}
		
	}	
}

document.debugevents=0;

function updategyroscope(){
	if (self.loadfs) loadfs('Gyroscope Updates','updategyroscope',function(){document.fleetview=null;},function(){document.fleetview=true;});
	else ajxpgn('gyroscope_updater',document.appsettings.codepage+'?cmd=updategyroscope',true,true);	
}

function hdpromote(css){
	if (typeof(document.documentElement.style.backgroundSize)=='string'&&window.devicePixelRatio>1){
		ajxcss(self.bgupgrade,css);
	}	
}

function hddemote(css){
	if (typeof(document.documentElement.style.borderRadius)!='string') ajxcss(self.bgdowngrade,css);
}

function encodeHTML(code){
	ajxjs(self.encodeURIComponent,'uriencode.js');
	return encodeURIComponent(code);
}

function decodeHTML(code){
	ajxjs(self.decodeURIComponent,'uriencode.js');
	return decodeURIComponent(code);
}

function arrayBufferToString(arrayBuffer) {
	return String.fromCharCode.apply(null, new Uint8Array(arrayBuffer));
}

function stringToArrayBuffer(str){
	return Uint8Array.from(str,function(c){return c.charCodeAt(0);}).buffer;
}

function arrayBufferToHex(buf){
	var data=new Uint8Array(buf);
	var cs=[];
	for (var i=0;i<data.length;i++) {
		cs.push(data[i].toString(16).padStart(2,'0'));
	}
	return cs.join('');
}

function base64encode(arrayBuffer){
	if (!arrayBuffer||arrayBuffer.length==0) return null;
	return btoa(String.fromCharCode.apply(null, new Uint8Array(arrayBuffer)));
}


function showhide(id,preopen,trigger){
	var d=gid(id);
	if (!d) return;
	if (preopen&&d.preopen==null) {d.showing=true;d.preopen=true}
	var baseclass='';
	if (trigger&&trigger.className){
		baseclass=trigger.className.replace(/\s+open$/g,'').replace(/\s+close$/g,'');
	}
	if (!d.showing) {
		d.style.display='block';
		d.showing=true;
		if (trigger) trigger.className=baseclass+' open';
	} else {
		d.style.display='none';
		d.showing=null;
		if (trigger) trigger.className=baseclass+' close';
	}
}

if (window.Blob){
	ajxblob=function(url,data,mode,func){
		var rq=xmlHTTPRequestObject();
		
		rq.open('POST',url+'&hb='+hb(),true);
		rq.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
		rq.setRequestHeader('SENDBLOB','1');
		rq.responseType='blob';
		rq.onreadystatechange=function(){
			if (rq.readyState==4){
				var data=rq.response;
				var blob=new Blob([data]);
				func(blob);
			}	  
		}
		rq.send(data);
	}	
}

ajxblobimg=function(imgid,url_get,url_post,data){
	if (!self.ajxblob){
		gid(imgid).src=url_get;
		return;	
	}
	
	ajxblob(url_post,data,'image',function(blob){
		var burl = URL.createObjectURL(blob);
		gid(imgid).src=burl;
		gid(imgid).onload=function(){
			URL.revokeObjectURL(gid(imgid).src);	
		}		
	});
	
}

