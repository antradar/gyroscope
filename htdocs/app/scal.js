scal_init=function(calid,opts,curyear,curmon,st,oh1){

	
	if (curyear==null){
		curyear=parseInt(gid('scal_cur_'+calid).attributes['defyear'].value,10);
		curmon=parseInt(gid('scal_cur_'+calid).attributes['defmon'].value,10);	
	}
	var mons=['','January','February','March','April','May','June','July','August','September','October','November','December'];
	gid('scal_title_'+calid).innerHTML=mons[curmon]+' '+curyear;
	
	var py=curyear;
	var pm=curmon-1;
	
	if (pm==0){
		pm=12;
		py=curyear-1;
	}
	
	var ny=curyear;
	var nm=curmon+1;
	
	if (nm>12){
		nm=1;
		ny=curyear+1;	
	}
	
	
	scal_makemonth(calid,py,pm,'prev',opts);
	scal_makemonth(calid,curyear,curmon,'cur',opts);
	scal_makemonth(calid,ny,nm,'next',opts);
	
	if (st==null){
		gid('scal_view_'+calid).style.height=(gid('scal_cur_'+calid).offsetHeight+gid('scal_cur_'+calid).trimmed+1+10)+'px';
		gid('scal_view_'+calid).scrollTop=gid('scal_prev_'+calid).offsetHeight-gid('scal_cur_'+calid).trimmed;
		if (gid('scal_rotation_indicator_'+calid)) gid('scal_rotation_indicator_'+calid).style.top=(gid('scal_frame_'+calid).offsetHeight)/2+'px';
		scal_loaddata(calid,opts); //load directly from server-side
	} else {
		gid('scal_view_'+calid).scrollTop=gid('scal_prev_'+calid).offsetHeight-gid('scal_cur_'+calid).trimmed-(st-oh1);
		gid('scal_view_'+calid).lastscroll=gid('scal_view_'+calid).scrollTop;
		if (gid('scal_rotation_indicator_'+calid)){
			gid('scal_rotation_indicator_'+calid).innerHTML='Switched to '+mons[curmon]+' '+curyear;
			gid('scal_rotation_indicator_'+calid).style.display='block';
			
			var filter='';
			if (opts.filterfunc) filter=opts.filterfunc();
			
			if (gid('scal_dataloader_'+calid)){
				ajxpgn('scal_dataloader_'+calid,document.appsettings.codepage+'?cmd=scal_'+gid('scal_datafunc_'+calid).value+'&'+filter+'&calid='+calid+'&year='+curyear+'&mon='+curmon,0,0,null,function(){
					scal_loaddata(calid,opts);	
				});
			}
			
			setTimeout(function(){
				gid('scal_rotation_indicator_'+calid).style.display='none';
			},500);
		} else {
			flashsticker('Switched to '+mons[curmon]+' '+curyear,0.5);
		}
	}

	gid('scal_prevlink_'+calid).onclick=function(){
		scal_init(calid,opts,py,pm,gid('scal_cur_'+calid).offsetHeight-gid('scal_prev_'+calid).trimmed,gid('scal_cur_'+calid).offsetHeight-gid('scal_prev_'+calid).trimmed);
	}
		
	gid('scal_nextlink_'+calid).onclick=function(){
		scal_init(calid,opts,ny,nm,gid('scal_cur_'+calid).offsetHeight-gid('scal_prev_'+calid).trimmed,gid('scal_cur_'+calid).offsetHeight-gid('scal_prev_'+calid).trimmed);
	}

	gid('scal_view_'+calid).year=curyear;
	gid('scal_view_'+calid).mon=curmon;
	

	
	gid('scal_view_'+calid).onscroll=scal_scroll(calid,opts);
	

}

scal_loaddata=function(calid,opts){
	var odata=gid('scal_data_'+calid);
	if (!odata) return;
	try{
		var data=eval('('+odata.value+')');
	} catch (ex){
		console.log('malformed calendar data');
		return;
	}
	
	for (daykey in data.days){
		if (!gid('scal_cellc_'+calid+'_'+daykey)) continue;
		
		if (opts!=null&&opts.cellfunc){
			opts.cellfunc(gid('scal_cellc_'+calid+'_'+daykey),data.days[daykey]);
		} else {
			gid('scal_cellc_'+calid+'_'+daykey).innerHTML='<b style="font-size:20px;color:red;">'+data.days[daykey].count+'</b>';
		}
		
	}
		
}

scal_scroll=function(calid,opts){
	return function(e){
		var d=gid('scal_view_'+calid);
		if (!d) return;
		var st=d.scrollTop;
		if (d.lastscroll==null) d.lastscroll=st;
		
		var comp_next=gid('scal_prev_'+calid).offsetHeight+(gid('scal_cur_'+calid).offsetHeight-gid('scal_next_'+calid).trimmed)*0.5;
		var comp_prev=(gid('scal_prev_'+calid).offsetHeight-gid('scal_prev_'+calid).trimmed)*0.2;
		
		var ny=d.year; var nm=d.mon+1;
		if (nm>12){nm=1;ny=ny+1;}
		
		var py=d.year; var pm=d.mon-1;
		if (pm<1){pm=12;py=py-1;}		

		if ( (st-d.lastscroll>20) && (st>comp_next)){
			//console.log('scroll forward');
			d.lastscroll=null;
			scal_init(calid,opts,ny,nm,st,gid('scal_prev_'+calid).offsetHeight);
			return;
		}
		
		if ( (d.lastscroll-st>20) && (st<comp_prev)){
			//console.log('scroll backward');
			d.lastscroll=null;
			scal_init(calid,opts,py,pm,st,gid('scal_cur_'+calid).trimmed);
			return;
		}		
	}	
}

scal_makemonth=function(calid,year,mon,paneltype,opts){
	var html=[];

	var woffset=parseInt(gid('scal_woffset_'+calid).value,10);
		
	var fd=new Date(year,mon-1,1).getDay();
	var ld=new Date(year,mon,0).getDate();
	
	var ofd=fd;
	fd=(fd-woffset+7)%7;
	
	var py=year;
	var pm=mon-1;
	
	if (pm==0){
		pm=12;
		py=year-1;
	}
	
	var ny=year;
	var nm=mon+1;
	
	if (nm>12){
		nm=1;
		ny=year+1;	
	}	
	
	var nfd=new Date(ny,nm,1).getDay()+1;


	var initday=1;
	if (fd>0) initday=7-fd+1;
	
	var postdays=7-ld%7-fd;
	if (postdays<0) postdays=7+postdays;
	
	var ldx=ld+postdays;
	
	//console.log(paneltype,year,mon,fd,initday,'fd',fd,ld,ldx);
	
	var today=gid('scal_today_'+calid).value;
	for (var i=initday;i<=ldx;i++){
		var xstyle='';
		var di=i;
		var daykey=year+'-'+mon+'-'+i;
		if (i>ld){
			di=i-ld;
			daykey=ny+'-'+nm+'-'+di;	
		}
		
		var dow=(i+ofd-1)%7;
		
		var classname='scal_cell dow_'+dow;
		if (daykey==today) classname=classname+' today';
		
		if ((paneltype=='prev'&&i<=ld)||(paneltype=='cur'&&i>ld)||paneltype=='next') xstyle='opacity:0.5;filter:blur(1px);';
		
		html.push('<div class="'+classname+'" id="scal_cell_'+calid+'_'+daykey+'" onclick="alert(\'no opts.cellclick for '+daykey+'\');" style="'+xstyle+'cursor:pointer;float:left;overflow:hidden;box-sizing:border-box;text-align:center;aspect-ratio:1;">'+di+'<div id="scal_cellc_'+calid+'_'+daykey+'"></div></div>');	
	}
	
	html.push('<div class="clear"></div>');
	
	
	gid('scal_'+paneltype+'_'+calid).innerHTML=html.join('');
	
	if (opts!=null&&opts.cellclick!=null){
		for (var i=initday;i<=ldx;i++){
			var xstyle='';
			var di=i;
			var daykey=year+'-'+mon+'-'+i;
			if (i>ld){
				di=i-ld;
				daykey=ny+'-'+nm+'-'+di;	
			}
			
			gid('scal_cell_'+calid+'_'+daykey).onclick=opts.cellclick(gid('scal_cell_'+calid+'_'+daykey),daykey);
		}//for
			
	}
	
	var cellheight=gid('scal_cell_'+calid+'_'+year+'-'+mon+'-'+initday).offsetHeight;	
	gid('scal_'+paneltype+'_'+calid).trimmed=fd>0?cellheight:0;
	gid('scal_view_'+calid).cellheight=cellheight;
	
}