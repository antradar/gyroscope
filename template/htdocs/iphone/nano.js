function gid(d){
return document.getElementById(d);
}
function hb(){
	var now=new Date(); var hb=now.getTime();
	return hb;
}
function ajxb(u){
  var rq=xmlHTTPRequestObject();
  rq.open('GET',u+'&hb='+hb(),false);
  rq.send(null);
  return rq.responseText;	
}
function ajxnb(rq,u,f){
  rq.onreadystatechange=f;
  rq.open('GET',u+'&hb='+hb(),true);
  rq.send(null);  	
}

function ajxpgn(c,u,d,e){
  var ct=gid(c);
  if (!ct) ct=gid('views');
  var f=function(c){
    return function(){
     if (rq.readyState==4){
      if (ct.reqobj!=null){
	ct.reqobj=null;
      }
      ct.innerHTML=rq.responseText;
      if (d) ct.style.display='block';
      if (e){
	var i;
	var scripts=gid(c).getElementsByTagName('script');
	for (i=0;i<scripts.length;i++) eval(scripts[i].innerHTML);
      }
     }else ct.innerHTML='<img src="imgs/hourglass.gif" style="margin:5px;"><span style="opacity:0.5;">'+ct.innerHTML+'</span>';
     
     }	  
  }	

  var rq=xmlHTTPRequestObject();

  if (ct.reqobj!=null) ct.reqobj.abort();
  ct.reqobj=rq;
  ajxnb(rq,u,f(c));

}


function ajxjs(f,js){
	if (f==null) {
	  	eval(ajxb(js+'?'));
	}
}
function ajxcss(f,css){
	if (f==null) {
	  	csl=document.createElement('link');
		csl.setAttribute('rel','stylesheet');
		csl.setAttribute('type','text/css');
		csl.setAttribute('href',css);
		document.getElementsByTagName("head").item(0).appendChild(csl);
	}
}

function xmlHTTPRequestObject() {
	var obj = false;
	var objectIDs = new Array("Microsoft.XMLHTTP","Msxml2.XMLHTTP","MSXML2.XMLHTTP.3.0","MSXML2.XMLHTTP.4.0");
	var success = false;
	var i=0;
	for (i=0; !success && i < objectIDs.length; i++) {
		try {
			obj = new ActiveXObject(objectIDs[i]);
			success = true;
		} catch (e) { obj = false; }
	}

	if (!obj) obj = new XMLHttpRequest();
	return obj;
}
