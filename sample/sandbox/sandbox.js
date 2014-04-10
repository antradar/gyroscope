resetsandbox=function(){
	if (!confirm('Are you sure you want to reset the sandbox?')) return;
	reloadtab('welcome',null,'resetsandbox');
}