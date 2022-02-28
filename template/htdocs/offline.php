<script>

function onlinestatuschanged(){
	if (navigator.onLine){
		//closefs();
		if (gid('gsnotes_online')) gid('gsnotes_online').style.display='block';
	} else {
		if (gid('gsnotes_online')) gid('gsnotes_online').style.display='none';
		
		var rq=xmlHTTPRequestObject();
		rq.open('GET','notes.php?mode=embed',true);
		rq.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
		rq.onreadystatechange=function(){
			if (rq.readyState==4){
				gid('fsview').innerHTML=rq.responseText;
				gid('fstitle').innerHTML='Offline Notes';
				showfs();	
				gsnotes_init();
			}	
		}
		
		rq.send(null);
	}
	if (self.gsnotes_syncindicator) gsnotes_syncindicator();
}

if (window.addEventListener) {
	window.addEventListener('offline',onlinestatuschanged);
	window.addEventListener('online',onlinestatuschanged);
}

setTimeout(onlinestatuschanged,300);



</script>

<script src="gsnotes.js"></script>

<script>
	gsnotes_init(1);
</script>