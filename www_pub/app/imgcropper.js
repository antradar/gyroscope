/*
Image Cropper

(c) Schien Dong, 2016

*/
cropper_init=function(id,opts){
	var c=document.getElementById(id);
	
	if (!opts){
		opts={ratio:null,minw:20,minh:20};
	}
	
	
	if (!c) {alert('Cropper ID not found');return;}
	
	var os=c.getElementsByTagName('img');

	if (os.length!=1) {alert('The cropper container must contain exactly one image element');return;}
	
	var img=os[0];	
	c.img=img;

	if (!c.loadcount) c.loadcount=0;

	if (img.naturalHeight==null&&c.loadcount>5){//IE
		img.naturalHeight=img.offsetHeight;
		img.naturalWidth=img.offsetWidth;
		img.dirtyratio=1;
	}

	if (!img.naturalHeight){
		c.loadcount++;
		setTimeout(function(){cropper_init(id,opts);},300); //wait until image is fully loaded
		return;
	}

	if (opts.minw) opts.minw=opts.minw*img.offsetWidth/img.naturalWidth;
	if (opts.minh) opts.minh=opts.minh*img.offsetHeight/img.naturalHeight;
		
	var mask=document.createElement('div');
	mask.style.position='absolute'; mask.style.top=0; mask.style.left=0; mask.style.background='#000000';
	mask.style.opacity=0.2; mask.style.width='100%'; mask.style.height='100%'; mask.style.filter='alpha(opacity=20)';
	if (!opts.transparent){
		img.style.filter='blur(1px)';
		img.style.WebkitFilter='blur(1px)';
	}
	
	c.appendChild(mask);
	c.mask=mask;
	
	var cropper=document.createElement('div');
	cropper.style.position='absolute';
	cropper.style.border='dashed 1px #dedede';
	cropper.style.cursor='move';
	cropper.style.overflow='hidden';
	
	var cimg=document.createElement('img');
	cimg.style.display='block';
	cimg.style.position='absolute';
	
	c.style.MozUserSelect='none';
	c.style.WebkitUserSelect='none';
	c.style.UserSelect='none';
	cropper.style.MozUserSelect='none';
	cropper.style.WebkitUserSelect='none';
	cropper.style.UserSelect='none';
	cimg.style.MozUserSelect='none';
	cimg.style.WebkitUserSelect='none';
	cimg.style.UserSelect='none';
	
	if (!opts.transparent) {
		cimg.src=img.src;
	} else {
		cimg.src='imgs/t.gif';
		cropper.style.background='#ffffff';
		cropper.style.opacity=0.4;
	}
	
	
	
	cropper.appendChild(cimg);
	cropper.cimg=cimg;
		
	c.appendChild(cropper);
	c.cropper=cropper;
	
	var w=img.offsetWidth; var h=img.offsetHeight;
	
	cimg.style.width=w+'px'; cimg.style.height=h+'px';
	
	var cpw=w*0.8; var cph=h*0.8;
	if (opts.minw&&cpw<opts.minw) cpw=opts.minw; if (cpw>w) cpw=w;
	if (opts.minh&&cph<opts.minh) cph=opts.minh; if (cpw>w) cpw=w;
	
	
	if (opts.ratio) {
		var tcph=cpw/opts.ratio;
		if (tcph>cph) {
			cpw=cph*opts.ratio;	
		} else {
			cph=tcph;	
		}
	}
	

	var cpleft=(w-cpw)/2; var cptop=(h-cph)/2;
		
	cropper.style.top=cptop+'px'; cropper.style.left=cpleft+'px'; cropper.style.width=cpw+'px'; cropper.style.height=cph+'px';

	cimg.style.left=-1*cpleft+'px';
	cimg.style.top=-1*cptop+'px';	
	
	cimg.bounds={x1:0,y1:0,x2:w-cpw, y2:h-cph};
	
	cimg.onmousedown=cropper_drag(cimg,cropper,function(dx,dy){
		cimg.style.left=-1*cropper.offsetLeft+'px';
		cimg.style.top=-1*cropper.offsetTop+'px';
		
		cropper_cps(c,cropper);
		
		return {res:1,dx:dx,dy:dy};
	});

	cimg.ontouchstart=cimg.onmousedown;		
	
	var cps=[
		{idx:0,x:cpleft,y:cptop,rlock:0}, //0 top left corner
		{idx:1,x:cpleft+cpw/2,y:cptop,rlock:1}, //1 top side
		{idx:2,x:cpleft+cpw,y:cptop,rlock:0}, //2 top right corner
		{idx:3,x:cpleft+cpw,y:cptop+cph/2,rlock:1}, //3 right side
		{idx:4,x:cpleft+cpw,y:cptop+cph,rlock:0}, //4 bottom right corner
		{idx:5,x:cpleft+cpw/2,y:cptop+cph,rlock:1}, //5 bottom side
		{idx:6,x:cpleft,y:cptop+cph,rlock:0}, //6 bottom left corner
		{idx:7,x:cpleft,y:cptop+cph/2,rlock:1} //7 left side
	];
		
	for (var i=0;i<cps.length;i++){
		var cp=document.createElement('div');
		cp.style.fontSize='1px'; cp.style.width='16px'; cp.style.height='16px'; cp.style.position='absolute';
		cp.style.background='#ffffff'; cp.style.border='solid 1px #444444';
		cp.style.left=cps[i].x-8+'px'; cp.style.top=cps[i].y-8+'px'; cp.style.opacity=0.4; cp.style.cursor='pointer';
		cp.idx=cps[i].idx;
		
		if (opts.ratio&&cps[i].rlock) cp.style.display='none';
		cp.bounds={x1:-8, y1:-8, x2:w-8, y2:h-8};
		
		cp.onmousedown=cropper_drag(cp,cp,function(dx,dy,d,t){
			
			
			
			if (opts.ratio>0&&typeof(dx)=='number'){
				
			if (opts.ratio>img.offsetWidth/img.offsetHeight){
				switch(d.idx){
				case 0: case 4: dy=dx*img.offsetWidth/img.offsetHeight; break;
				case 2: case 6: dy=-1*dx*img.offsetWidth/img.offsetHeight; break;
				}
			} else {

				switch(d.idx){
				case 0: case 4: dx=dy*img.offsetHeight/img.offsetWidth; break;
				case 2: case 6: dx=-1*dy*img.offsetHeight/img.offsetWidth; break;
				}
			}

			var sign=1;
			if (d.idx==2||d.idx==6) sign=-1;

			if (opts.ratio<1){var t=dy; dy=dx; dx=t;}

			if (opts.ratio==1) dy=sign*dx;

			}
			

			switch(d.idx){
				case 0:
					if (opts.minw) d.bounds.x2=c.cps[4].cp.offsetLeft-opts.minw;
					if (opts.minh) d.bounds.y2=c.cps[4].cp.offsetTop-opts.minh;
					
					cropper.style.left=d.offsetLeft+8+'px';
					cropper.style.width=c.cps[4].cp.offsetLeft-d.offsetLeft+'px';
					cropper.style.top=c.cps[0].cp.offsetTop+8+'px';
					cropper.style.height=c.cps[4].cp.offsetTop-d.offsetTop+'px';
					
					cimg.style.left=-1*cropper.offsetLeft+'px';
					cimg.style.top=-1*cropper.offsetTop+'px';
					
					cropper_cps(c,cropper,4);
		
				break;	

				case 2:
					if (opts.minw) d.bounds.x1=c.cps[6].cp.offsetLeft+opts.minw;
					if (opts.minh) d.bounds.y2=c.cps[6].cp.offsetTop-opts.minh;
				
					cropper.style.width=d.offsetLeft-c.cps[6].cp.offsetLeft+'px';
					cropper.style.top=d.offsetTop+8+'px';
					cropper.style.height=c.cps[6].cp.offsetTop-d.offsetTop+'px';
					
					cimg.style.top=-1*cropper.offsetTop+'px';
					
					cropper_cps(c,cropper,6);
		
				break;	
				
				case 6:
					if (opts.minw) d.bounds.x2=c.cps[2].cp.offsetLeft-opts.minw;
					if (opts.minh) d.bounds.y1=c.cps[2].cp.offsetTop+opts.minh;
					
					cropper.style.left=d.offsetLeft+8+'px';
					cropper.style.width=c.cps[2].cp.offsetLeft-d.offsetLeft+'px';
					cropper.style.height=d.offsetTop-c.cps[2].cp.offsetTop+'px';
					
					cimg.style.left=-1*cropper.offsetLeft+'px';
					
					cropper_cps(c,cropper,2);
		
				break;					
								
				case 4:
					if (opts.minw) d.bounds.x1=c.cps[0].cp.offsetLeft+opts.minw;
					if (opts.minh) d.bounds.y1=c.cps[0].cp.offsetTop+opts.minh;
					
					cropper.style.width=d.offsetLeft-c.cps[0].cp.offsetLeft+'px';
					cropper.style.height=d.offsetTop-c.cps[0].cp.offsetTop+'px';

					
					cropper_cps(c,cropper);

				break;	
				
				case 1:
					dx=0;
					if (opts.minh) d.bounds.y2=c.cps[4].cp.offsetTop-opts.minh;
					
					cropper.style.top=d.offsetTop+8+'px';
					cropper.style.height=c.cps[6].cp.offsetTop-d.offsetTop+'px';
					cimg.style.top=-1*cropper.offsetTop+'px';
					
					cropper_cps(c,cropper,6);

				break;	
				
				case 5:
					dx=0;
					if (opts.minh) d.bounds.y1=c.cps[0].cp.offsetTop+opts.minh;
					
					cropper.style.height=d.offsetTop-c.cps[0].cp.offsetTop+'px';
					cropper_cps(c,cropper);
				break;								
				
				case 7:
					dy=0;
					if (opts.minw) d.bounds.x2=c.cps[4].cp.offsetLeft-opts.minw;
					cropper.style.left=d.offsetLeft+8+'px';
					cropper.style.width=c.cps[4].cp.offsetLeft-d.offsetLeft+'px';
					cimg.style.left=-1*cropper.offsetLeft+'px';
					cropper_cps(c,cropper,4);

				break;	
				
				case 3:
					dy=0;
					if (opts.minw) d.bounds.x1=c.cps[0].cp.offsetLeft+opts.minw;
					
					cropper.style.width=d.offsetLeft-c.cps[0].cp.offsetLeft+'px';
					cropper_cps(c,cropper);
				break;								
				
			}
				
			return {res:1,dx:dx,dy:dy};
		});
		
		cp.ontouchstart=cp.onmousedown;

		c.appendChild(cp);
		cps[i].cp=cp;	
		
	}
	c.cps=cps;
}

cropper_free=function(id){
	var c=gid(id);
	if (!c) return;

	var div=document.createElement('div');
	var img=c.getElementsByTagName('img')[0];
	if (!img) return;
	
	img.style.filter='';
	
	div.appendChild(img.parentNode.removeChild(img));

	c.innerHTML='';
	c.appendChild(img);
		
}

cropper_cps=function(c,cropper,skipid){
	
		var cpps=[
			{x:cropper.offsetLeft,y:cropper.offsetTop}, //0 top left corner
			{x:cropper.offsetLeft+cropper.offsetWidth/2,y:cropper.offsetTop}, //1 top side
			{x:cropper.offsetLeft+cropper.offsetWidth,y:cropper.offsetTop}, //2 top right corner
			{x:cropper.offsetLeft+cropper.offsetWidth,y:cropper.offsetTop+cropper.offsetHeight/2}, //3 right side
			{x:cropper.offsetLeft+cropper.offsetWidth,y:cropper.offsetTop+cropper.offsetHeight}, //4 bottom right corner
			{x:cropper.offsetLeft+cropper.offsetWidth/2,y:cropper.offsetTop+cropper.offsetHeight}, //5 bottom side
			{x:cropper.offsetLeft,y:cropper.offsetTop+cropper.offsetHeight}, //6 bottom left corner
			{x:cropper.offsetLeft,y:cropper.offsetTop+cropper.offsetHeight/2} //7 left side		
		]
		
		for (var i=0;i<cpps.length;i++) {
			if (skipid==i) continue;
			c.cps[i].cp.style.left=cpps[i].x-8+'px';
			c.cps[i].cp.style.top=cpps[i].y-8+'px';
		}
		
		cropper.cimg.bounds={x1:0,y1:0,x2:c.offsetWidth-cropper.offsetWidth,y2:c.offsetHeight-cropper.offsetHeight}
	
}

cropper_drag=function(d,t,callback){return function(e){
	
	var ox; var oy;
	var bounds=d.bounds;
	
	if (e){ox=e.clientX;oy=e.clientY;} else {ox=event.clientX;oy=event.clientY;}

	if (self.event&&event.touches) event.preventDefault();

	if (e&&e.touches){
		for (var i=0;i<event.touches.length;i++){
			if (e.touches[i].target==d) {
				ox=event.touches[i].clientX;
				oy=event.touches[i].clientY;
			}
		}

	}
	
	var posx=t.offsetLeft; var posy=t.offsetTop;
	
	d.ondragstart=function(e){
		return false;
	}
	
	d.onmousemove=function(e){
		var x; var y;
		if (e){x=e.clientX;y=e.clientY;} else {x=event.clientX;y=event.clientY;}

		if (e&&e.touches){
			for (var i=0;i<event.touches.length;i++){
				if (e.touches[i].target==d) {
					x=event.touches[i].clientX;
					y=event.touches[i].clientY;
				}
			}

		}

		var newx=posx+x-ox;
		var newy=posy+y-oy;

		if (x<ox&&newx<bounds.x1) newx=bounds.x1;
		if (x>ox&&newx>bounds.x2) newx=bounds.x2;
		if (y<oy&&newy<bounds.y1) newy=bounds.y1;
		if (y>oy&&newy>bounds.y2) newy=bounds.y2;
		var res=1;
		
		if (callback) var cres=callback(newx-posx, newy-posy,d,t);
		
		if (cres.res){
			t.style.left=posx+cres.dx+'px';
			t.style.top=posy+cres.dy+'px';
		}
		
	}
	
	d.onmouseup=function(e){
		d.onmousemove(e);
		d.onmousemove=function(){return false;}	
		document.onmousemove=function(){return false;}
		d.ontouchmove=function(){return false;}
	}

	d.ontouchmove=d.onmousemove;

	document.onmousemove=d.onmousemove;	
	document.onmouseup=d.onmouseup;

	d.ontouchend=d.onmouseup;
	
}}

cropper_coords=function(cropper){

	if (typeof(cropper)=='string') {
		var cmain=document.getElementById(cropper);
		cropper=cmain.cropper;
	} else {
		var cmain=cropper;
		cropper=cmain.cropper;	
	}
	
	if (cmain.img.naturalWidth==null){
		var timg=new Image();
		timg.src=cmain.img.src;
		cmain.img.naturalWidth=timg.width;
	}
	var scale=cmain.img.naturalWidth/cmain.img.offsetWidth;
	var dims={
		x1:cropper.offsetLeft, y1:cropper.offsetTop, x2:cropper.offsetLeft+cropper.offsetWidth, y2:cropper.offsetTop+cropper.offsetHeight,
		scale:scale,
		sx1:Math.round(scale*(cropper.offsetLeft)), sy1:Math.round(scale*cropper.offsetTop), 
		sx2:Math.round(scale*(cropper.offsetLeft+cropper.offsetWidth)), sy2:Math.round(scale*(cropper.offsetTop+cropper.offsetHeight))
	}
	
	var str=[];
	for (k in dims) str.push(k+'='+dims[k]);
	dims.str=str.join('&');

	return dims;
}