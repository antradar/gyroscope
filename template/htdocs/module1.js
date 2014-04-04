show_module1_details=function(m1id,m1title){
	addtab('m1_'+m1id,m1title,'dt0&m1id='+m1id);
}

newrecord=function(){

	reloadtab('record_new',null,'addrecord&params',function(req){
		var recordid=req.responseText;
		if (parseInt(recordid,10)!=recordid) return;
		reloadtab('record_new',null,'showrecord&recordid='+recordid,null,null,{newkey:'record_'+recordid});
		if (document.viewindex==1) reajxpgn('recordlistlist','lv1');		
	}	
}

