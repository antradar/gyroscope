/*
Nano Ajax Library
(c) Schien Dong, Antradar Software Inc.

License: www.antradar.com/license.php
Documentation: www.antradar.com/docs-nano-ajax-manual

Warning: this copy of Nano Ajax Library is modified for running in Gyroscope. Use the public version for general purpose applications.
*/

function gid(d){return document.getElementById(d);}

function hb(){var now=new Date(); var hb=now.getTime();return hb;}

function ajxb(u,data){
	var method='POST';
	if (data==null) method='GET';
	
	if (document.wssid) u=u+'&wssid_='+document.wssid;
	
	var rq=xmlHTTPRequestObject();
	rq.open(method, u+'&hb='+hb(),false);
	rq.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
	rq.send(data);
	return rq.responseText;	
}

function ajxnb(rq,u,f,data){
	if (document.wssid) u=u+'&wssid_='+document.wssid;	
	rq.onreadystatechange=f;
	rq.open('POST',u+'&hb='+hb(),true);
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
	ajxpgn(c,ct.reloadparams.u,ct.reloadparams.d,ct.reloadparams.e,ct.reloadparams.data,ct.reloadparams.callack,ct.reloadparams.slowtimer);		
}

function ajxpgn(c,u,d,e,data,callback,slowtimer){
	var ct=gid(c);
	if (ct==null) return;
	
	ct.reloadparams={u:u,d:d,e:e,data:data,callback:callback,slowtimer:slowtimer};
	
	if (document.wssid) u=u+'&wssid_='+document.wssid;
			
	var f=function(c){return function(){
		if (rq.readyState==4){
			
    	    var xtatus=rq.getResponseHeader('X-STATUS');
		    if (rq.status==403||(xtatus|0)==403){
				if (self.skipconfirm) skipconfirm(); 
				window.location.href='login.php';
				return;
			}

			if (ct.reqobj!=null){
				ct.reqobj=null;
			}

			if (ct.slowtimer) clearTimeout(ct.slowtimer);
			
			var apperror=rq.getResponseHeader('apperror');
			if (apperror!=null&&apperror!=''){
				alert('Error: '+apperror);
				
				return;	
			}
			
			
			ct.innerHTML=rq.responseText;
			
			if (d) ct.style.display='block';
			
			if (e){
				var i;
				var scripts=gid(c).getElementsByTagName('script');
				for (i=0;i<scripts.length;i++) eval(scripts[i].innerHTML);
				scripts=null;
			}
			
			
			if (callback) callback(rq);
		}	  
	}}	
	
	var rq=xmlHTTPRequestObject();
	
	if (ct.reqobj!=null) ct.reqobj.abort();
	ct.reqobj=rq;
	if (!slowtimer) slowtimer=800;
	
	ct.slowtimer=setTimeout(function(){
		if (ct.style.display=='none') ct.style.display='block';
		ct.innerHTML='<img src="imgs/hourglass.gif" class="hourglass" style="margin:5px;"><span style="opacity:0.5;filter:alpha(opacity=50);color:#999999;">'+ct.innerHTML+'</span>';
	},slowtimer);

	ajxnb(rq,u,f(c),data);
}


function ajxjs(f,js){if (f==null) eval(ajxb(js+'?'));}

function ajxcss(f,css,cachekey){
	if (f==null) {
	  	csl=document.createElement('link');
		csl.setAttribute('rel','stylesheet');
		csl.setAttribute('type','text/css');
		csl.setAttribute('href',css);
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
	xjs.innerHTML='';
	var rq=document.createElement('script');
	rq.setAttribute('src',url);
	xjs.appendChild(rq);  
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

function updategyroscope(){
	if (self.loadfs) loadfs('Gyroscope Updates','updategyroscope');
	else ajxpgn('gyroscope_updater',document.appsettings.codepage+'?cmd=updategyroscope',true,true);	
}

function hdpromote(css){
	if (typeof(document.documentElement.style.backgroundSize)=='string'&&window.devicePixelRatio>1){
		ajxcss(self.bgupgrade,css);
	}	
}

function encodeHTML(code){
	code=escape(code);
	code=code.replace(/\//g,"%2F");
	code=code.replace(/\?/g,"%3F");
	code=code.replace(/=/g,"%3D");
	code=code.replace(/&/g,"%26");
	code=code.replace(/@/g,"%40");
	code=code.replace(/\+/g,"%2B");
	return code;
}

function showhide(id){
	var d=gid(id);
	if (!d) return;
	if (!d.showing) {
		d.style.display='block';
		d.showing=true;	
	} else {
		d.style.display='none';
		d.showing=null;	
	}
}
