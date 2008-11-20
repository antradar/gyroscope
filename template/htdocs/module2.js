show_module2_details=function(m2id,m2title){
	addtab('m2_'+m2id,m2title,'dt1&m2id='+m2id);
}

m2_add_to_m1list=function(m1id,m2id,title){

	var listname='m1m2list_'+m1id;
	if (gid(listname)){//only update the view when it's already loaded
	
		gid(listname).innerHTML+="<br><b>"+title+"</b>";
	}	
}