
if (navigator&&navigator.mediaDevices&&navigator.mediaDevices.getDisplayMedia&&window.MediaRecorder){
	if (gid('gsreplayicon')){
		gid('gsreplayicon').style.display='inline-block';
	}
}

_gsreplay_rec_start=function(d){
	ajxjs2('gsreplay_rec_start','gsreplay.js',function(){
		if (gid('toollist')) gid('toollist').scrollLeft=0;
		gsreplay_rec_start(d);
	});	
}