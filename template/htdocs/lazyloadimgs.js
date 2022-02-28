/*
    JavaScript lazyloadimgs
    Copyright (c) 2018 Schien Dong / Antradar Software
    License: http://www.opensource.org/licenses/mit-license.php

    cid - main listing container ID
    classname - class name of the image
    
    use "preloaded" to indicate non-javascript images - useful for static crawling (SEO)
    
    integrating with flex-images: set layoutonly:1
    integrating with msnry: use the onsegload callback to call msnry.layout()
    
    <div id="pics">
    	... <img preloaded class="tileimage" src="images/real_src" data-src="real_src"> ...
    	... <img preloaded class="tileimage" src="images/real_src" data-src="real_src"> ...
    	... <img class="tileimage" src="images/t.gif" data-src="real_src"> ...
    	... <img class="tileimage" src="images/t.gif" data-src="real_src"> ...
    </div>
*/

function lazyloadimgs(cid,classname,onsegload,ret){
	var c=document.getElementById(cid);
	if (!c) return;
	var segsize=10;
	
	if (!ret){
		c.segidx=0;
		var os=c.getElementsByTagName('img');
		var imgs=[];
		var segs=[];
		
		for (var i=0;i<os.length;i++){
			var o=os[i];
			if (o.className!=classname) continue; //needs a matching class
			if (o.attributes['data-src']==null||o.attributes['data-src']=='') continue; //needs the data-src attribute
			if (o.attributes['preloaded']!=null) continue; //skip already loaded images
			imgs.push(o);	
		}
		if (imgs.length==0) return;
		
		var imgloaded=function(im){return function(){
			im.loaded=true;

			if (!document.maximgy) document.maximgy=10; //bootstrap

			if (document.msnry) {
				//in each image tag: onload="this.parentNode.style.height='auto';"
				im.parentNode.style.height='auto';
				if (document.msnry){
					if (document.msnrytimer) clearTimeout(document.msnrytimer);
					document.msnrytimer=setTimeout(function(){
						document.msnry.layout();
					},300);
				}
			}

			var mimgs=c.segs[c.segidx];
			if (mimgs==null) return;
			var allloaded=true;
			for (var k=0;k<mimgs.length;k++){
				if (!mimgs[k].loaded) allloaded=false;
			}
			if (allloaded){
				if (!document.maximgy) document.maximgy=im.parentNode.offsetTop;
				if (document.maximgy<im.parentNode.offsetTop) document.maximgy=im.parentNode.offsetTop;

				//if (!document.deferredscroll) document.deferredscroll=function(){ //uncomment to enable scroll-triggered loading
					if (onsegload!=null) onsegload(c.segidx,c.segs.length);
					c.segidx++;
					lazyloadimgs(cid,classname,onsegload,true);
					return;
				//} //uncomment to enable scroll-triggered loading
/*
add/merge window.onscroll event:
				
window.onscroll=function(){
	var st=document.documentElement.scrollTop;
	if (st<document.body.scrollTop) st=document.body.scrollTop;
	st=st+ch(); //client height
							
	if (document.maximgy!=null&&document.deferredscroll){
		if (st>document.maximgy){
			document.deferredscroll();	
		}
	}
}
function ch(){
  if (window.innerHeight) return window.innerHeight;
  if (document.documentElement.clientHeight) return document.documentElement.clientHeight;
  return document.body.clientHeight;
}					
*/
			}			
		}}		
		
		for (var i=0;i<imgs.length;i++){
			var img=imgs[i];
			img.onload=imgloaded(img);
			img.onerror=img.onload;
			
			var segidx=Math.floor(i/segsize);
			if (segs[segidx]==null) segs[segidx]=[];
			segs[segidx].push(img);
		}
		
		if (window.console&&window.console.log) console.log(imgs.length+' images will be loaded in '+segs.length+' segments');
		
		c.segs=segs;						
		
	}//first time setup
	
	var imgs=c.segs[c.segidx];
	
	if (imgs==null) return;
	
	if (window.console&&window.console.log) console.log('Loading Segment #'+(c.segidx+1)+' of '+c.segs.length);
		
	for (var i=0;i<imgs.length;i++){
		var img=imgs[i];
		img.src=img.attributes['data-src'].value;	
	}
		
}