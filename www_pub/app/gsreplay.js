
showgsreplay=function(gsreplayid,name,bookmark){
	if (name==null) name='';
	addtab('gsreplayview_'+gsreplayid,'Replay #'+gsreplayid+' '+name,'showgsreplay&gsreplayid='+gsreplayid,function(){
		var frames=eval('('+gid('gsreplayinfo_'+gsreplayid).value+')');
		gid('gsreplay_'+gsreplayid).frames=frames;
		var ff=function(){
			gid('replayindicator_'+gsreplayid).style.visibility='visible';
		}
		gid('gsreplay_'+gsreplayid).ff=ff;
		gsreplay_play('gsreplay_'+gsreplayid,frames,0,0,ff);
			
	},null,{bookmark:bookmark});	
}

_inline_lookupgsreplay=function(d){
	var soundex='';
	if (d.soundex) soundex='&soundex=1';
	
	if (!d.oclassname) d.oclassname=d.className;

	if (d.lastkey!=null&&d.lastkey==d.value) {
		lookupentity_completed(d);
		return;
	}
	d.lastkey=d.value;
			
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		d.className=d.oclassname+' busy';
		ajxpgn('gsreplaylist',document.appsettings.codepage+'?cmd=slv_core__gsreplays&mode=embed&key='+encodeHTML(d.value)+soundex,0,0,null,function(){
			lookupentity_completed(d);
		});
	},400
	);	
}

delgsreplay=function(gsreplayid){
	if (!sconfirm('Are you sure you want to remove this replay clip?')) return;
	reloadtab('gsreplayview_'+gsreplayid,'','delgsreplay&gsreplayid='+gsreplayid,function(){
		closetab('gsreplayview_'+gsreplayid);
		reloadview('core.gsreplays','gsreplaylist');
	});
}

updategsreplay=function(gsreplayid,gskey){
	var ogsreplaytitle=gid('gsreplaytitle_'+gsreplayid);
	var ogsreplaydesc=gid('gsreplaydesc_'+gsreplayid);

	var gsreplaytitle=encodeHTML(ogsreplaytitle.value);
	var gsreplaydesc=encodeHTML(ogsreplaydesc.value);
	
	var params=[];
	params.push('gsreplaytitle='+gsreplaytitle);
	params.push('gsreplaydesc='+gsreplaydesc);
	
	ajxpgn('statusc',document.appsettings.codepage+'?cmd=updategsreplay&gsreplayid='+gsreplayid,0,0,params.join('&'),function(){
		refreshtab('gsreplayview_'+gsreplayid,1);
		reloadview('core.gsreplays','gsreplaylist');
	},null,null,gskey);
	
}


_inline_lookupplugingsreplay=function(d){
	
	if (d.timer) clearTimeout(d.timer);
	d.timer=setTimeout(function(){
		ajxpgn('plugingsreplaylist',document.appsettings.codepage+'?cmd=lookupplugingsreplay&mode=embed&key='+encodeHTML(d.value));
	},300
	);	
}


/////

gsreplay_rec_stop=function(){
	var eventtypes=['click','scroll','keyup','keydown'];
	for (var i=0;i<eventtypes.length;i++){
		window.removeEventListener(eventtypes[i],gsreplay_rec_keyframe,true);
	}
	
	if (document.gsreplay.frametimer) clearTimeout(document.gsreplay.frametimer);

	gid('gsreplayicon').style.filter='saturate(0.3)';
	document.gsreplay.recorder=null;
	document.gsreplay.basetime=null;
	
	gsreplay_preview_frames();
	
}

gsreplay_preview_frames=function(){
	if (!document.gsreplay.frames||document.gsreplay.frames.length==0) return;
	
	loadfs('Screen Capture Preview','gsreplay_fspreview',
	function(){
		document.gsreplay=null;
		gid('fsview').innerHTML='';
		gid('fstitle').innerHTML='';
	},
	function(){
		gsreplay_play('gsreplay_preview',document.gsreplay.frames,0,1);
	});	
}

gsreplay_play_frame=function(id,timemode,loop,onfinished){
	//time mode: 0 - condense, 1 - real, 2 - fixed 500ms

	var player=gid(id);
	if (!player) return;
	
	var frame=player.frames[player.idx];
	
	player.src=frame.frame;
	
	if (player.frames.length<2) return;
	
	var wrapped=0;
	
	var nidx=player.idx+1;
	if (nidx>=player.frames.length) {
		if (onfinished) onfinished();
		if (!loop) return;
		nidx=0;
		player.toffset=0;
		wrapped=1;
	}
	
	player.idx=nidx;
	var nframe=player.frames[nidx];
	var delta=nframe.toffset-player.toffset;
	player.toffset=nframe.toffset;
	
	if (timemode==0&&delta>3000) delta=3000;
	if (wrapped) delta=5000;
	
	setTimeout(function(){
		//console.log('next frame',nidx,delta);
		gsreplay_play_frame(id,timemode,loop,onfinished);
	},delta);
	
}

gsreplay_play=function(id,frames,timemode,loop,onfinished){
	var player=gid(id);
	if (!player) return;
	
	if (player.itv) clearTimeout(player.itv);
	
	player.idx=0;
	player.toffset=0;
	player.frames=frames;

	gsreplay_play_frame(id,timemode,loop,onfinished);	
	
}

gsreplay_rec_keyframe=function(e,itr){
	if (document.gsreplay.frametimer) clearTimeout(document.gsreplay.frametimer);
	document.gsreplay.frametimer=setTimeout(function(){
		
		if (itr==null) itr=0;
		//console.log('Capture #'+itr);
		var ctx=document.gsreplay.canvas.getContext('2d');
		ctx.drawImage(document.gsreplay.video,0,0,document.gsreplay.width,document.gsreplay.height);

		if (!document.gsreplay.frames) document.gsreplay.frames=[];
		
		if (!document.gsreplay.basetime) document.gsreplay.basetime=hb();
		
		var toffset=hb()-document.gsreplay.basetime;
				
		
		document.gsreplay.canvas.toBlob(function(blob){
			var frame=URL.createObjectURL(blob);
			document.gsreplay.frames.push({toffset:toffset, file:blob, frame:frame, itr:itr});
		});
		
		
		//console.log(toffset,frame);
		
		
		document.gsreplay.frametimer=null;
		
		if (itr<3){
			if (document.gsreplay.frametimer) clearTimeout(document.gsreplay.frametimer);
			document.gsreplay.frametimer=setTimeout(function(){gsreplay_rec_keyframe(e,itr+1);},400);			
		}
		
	},30);
}

gsreplay_rec_addevents=function(){
	var eventtypes=['click','scroll','keyup','keydown'];
	for (var i=0;i<eventtypes.length;i++){
		window.addEventListener(eventtypes[i],gsreplay_rec_keyframe,true);
	}
}	

gsreplay_rec_start=function(d){
	
	if (document.gsreplay&&document.gsreplay.recorder){
		console.log('The screen is already being recorded',1);		
		return;	
	}
		
	var opts={
		video:{displaySurface:'window'},
		audio:false,
		selfBrowserSurface: "include"
	};
	
	
	if (document.gsreplaystream) return;
		
	navigator.mediaDevices.getDisplayMedia(opts).then(function(mstream){
		ajxjs2('cropper_init','imgcropper.js');		
		document.gsreplay={
			stream:mstream,
			width:window.screen.width, //cw(),
			height:window.screen.height, //ch(),
			recorder:new MediaRecorder(mstream),
			video:document.createElement('video'),
			canvas:document.createElement('canvas')
		}
		
		gsreplay_rec_addevents();
		
		//document.gsreplay.video.style.width=document.gsreplay.width+'px';
		//document.gsreplay.video.style.height=document.gsreplay.height+'px';

				
		document.gsreplay.video.srcObject=document.gsreplay.stream;
		document.gsreplay.video.onloadedmetadata=function(){
			document.gsreplay.width=document.gsreplay.video.videoWidth;
			document.gsreplay.height=document.gsreplay.video.videoHeight;
			document.gsreplay.canvas.width=document.gsreplay.width;
			document.gsreplay.canvas.height=document.gsreplay.height;
			document.gsreplay.video.play();
		}
		
		
		//document.body.appendChild(document.gsreplay.canvas);
				
				
		document.gsreplay.recorder.onstop=function(e){
			gsreplay_rec_stop();	
		}
		
		document.gsreplay.recorder.start();
		gid('gsreplayicon').style.filter='saturate(1)';
	}).catch(function(err){
		//permission denied
		//console.log(err);
	});
}

gsreplay_togglecrop=function(d,cropperid){
	if (!d.cropping){
		d.cropping=true;
		if (!d.cropperx) {
			d.cropperx=cropper_init(cropperid,{transparent:1});
			//gid(cropperid).img.style.filter='';
			//gid(cropperid).cropper.style.background='#ffffff';
			//gid(cropperid).cropper.style.opacity='0.4';
			//gid(cropperid).cropper.cimg.style.visibility='hidden';
		}
	} else {
		cropper_free(cropperid);
		d.cropping=null;
		d.cropperx=null;	
	}
}

gsreplay_submit=function(tcframes){
	if (!document.gsreplay||!document.gsreplay.frames) return;
	
	var cframes=[];

	var crop=0;
	
	var imgfunc=function(img,x,y,width,height){
		return function(){
			var ctx=canvas.getContext('2d');
			ctx.drawImage(img,x,y,width,height,0,0,width,height);
			canvas.toBlob(function(blob){
				cframes.push({file:blob});
				if (cframes.length==document.gsreplay.frames.length){
					gsreplay_submit(cframes);	
				}
				//console.log(blob,x,y,width,height);
			});
		}		
	}
	
	var width=document.gsreplay.width;
	var height=document.gsreplay.height;
	
	if (gid('gsreplay_croptrigger')&&gid('gsreplay_croptrigger').checked){
		crop=1;

		var coords=cropper_coords(gid('gsreplay_cropper'));
		width=Math.abs(coords.sx2-coords.sx1);
		height=Math.abs(coords.sy2-coords.sy1);
				
		if (!tcframes){
			
		var x=coords.sx1;
		var y=coords.sy1;
		
		var canvas=document.createElement('canvas');
		canvas.width=width;
		canvas.height=height;
		
		for (var i=0;i<document.gsreplay.frames.length;i++){
			var img=new Image();
			img.src=document.gsreplay.frames[i].frame;
			img.onload=imgfunc(img,x,y,width,height);
				
		}//for
		
		}//tcframes
	}
	
	if (crop&&!tcframes) return;
			
	var fd=new FormData;
	fd.append('width',width);
	fd.append('height',height);
	
	var toffsets=[];
	var itrs=[];
	
	for (var i=0;i<document.gsreplay.frames.length;i++){
		
		var fs=document.gsreplay.frames[i].file.size;
		if (fs==0){
			console.log('skipped empty frame #'+i);
			continue;	
		}
		
		toffsets.push(document.gsreplay.frames[i].toffset);
		itrs.push(document.gsreplay.frames[i].itr);
		
	}//for
	
	
	var useframes;
	if (crop) useframes=tcframes;
	else useframes=document.gsreplay.frames;
	
	var rq=xmlHTTPRequestObject();
	rq.open('POST',document.appsettings.codepage+'?cmd=gsreplay_submit',true);
	rq.onreadystatechange=function(){
		if (rq.readyState==4){
			gid('gsreplay_saver').innerHTML=rq.responseText;
			reloadview('core.gsreplays','gsreplaylist');
			var gsreplayid=parseInt(rq.getResponseHeader('gsreplayid'),10);
			if (!gsreplayid){
				salert('Failed to submit clip');
				return;	
			}
			
			gsreplay_submit_frame(gsreplayid,0,useframes,toffsets,itrs);
			
		}		
	}
	rq.send(fd);
	
	
}

gsreplay_submit_frame=function(gsreplayid,idx,useframes,toffsets,itrs,pctid){
	var maxframe=useframes.length-1;
	
	var frame=useframes[idx];
	var toffset=toffsets[idx];
	var itr=itrs[idx];
	
	var fd=new FormData;
	fd.append('gsreplayid',gsreplayid);
	fd.append('toffset',toffset);
	fd.append('itr',itr);
	if (frame.binstr) fd.append('binstr',frame.binstr);
	else fd.append('frame',frame.file);
	
	var pct=Math.round(idx*100*10/maxframe)/10;
	
	if (pctid==null) pctid='gsreplay_upload_progress_'+gsreplayid;
	
	if (gid(pctid)) {
		if (idx==0) gid(pctid).style.background='#ffff00';
		if (idx==maxframe) {
			gid(pctid).style.background='#44ff44';
			reloadview('core.gsreplays','gsreplaylist');
		}
		gid(pctid).style.width=pct+'%';
	}
	
	var rq=xmlHTTPRequestObject();
	rq.open('POST',document.appsettings.codepage+'?cmd=gsreplay_submit_frame',true);
	rq.onreadystatechange=function(){
		if (rq.readyState==4){
			if (idx<maxframe){
				setTimeout(function(){
					gsreplay_submit_frame(gsreplayid,idx+1,useframes,toffsets,itrs,pctid);
				},0);	
			}
		}
	}
	
	rq.send(fd);
		
}

gsreplay_import=function(importerid){
	if (gid(importerid).files.length==0) return;
	var file=gid(importerid).files[0];
	var reader=new FileReader();
	reader.onloadend=function(e){
		var content=e.target.result;
		document.debug=content;
		var sptr="\r\n";
		var parts=content.split(sptr);
		var dims=parts[0].split('x');
		var width=dims[0];
		var height=dims[1];
		var title=eval('('+parts[1]+')');
		var desc=eval('('+parts[2]+')');
		var toffsets=parts[3].split(',');
		var itrs=parts[4].split(',');

		var fd=new FormData;
		fd.append('width',width);
		fd.append('height',height);
		fd.append('title',title);
		fd.append('desc',desc);

		var frames=[];
		for (var i=0;i<toffsets.length;i++){
			frames.push({binstr:parts[i+5],toffset:toffsets[i],itr:itrs[i]});	
		}		
		
		var pctid=importerid+'_pct';
		if (gid(pctid)) gid(pctid).parentNode.style.display='block';
				
		var rq=xmlHTTPRequestObject();
		rq.open('POST',document.appsettings.codepage+'?cmd=gsreplay_submit',true);
		rq.onreadystatechange=function(){
			if (rq.readyState==4){
				var gsreplayid=parseInt(rq.getResponseHeader('gsreplayid'),10);
				if (!gsreplayid){
					salert('Failed to submit clip');
					return;	
				}
				
				gsreplay_submit_frame(gsreplayid,0,frames,toffsets,itrs,pctid);
				
			}		
		}
		rq.send(fd);
		
		
				
	}//reader loaded
	reader.readAsBinaryString(file);
	
}
