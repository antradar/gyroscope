
if (navigator&&navigator.mediaDevices&&navigator.mediaDevices.getDisplayMedia&&window.MediaRecorder){
	if (gid('gsreplayicon')){
		gid('gsreplayicon').style.display='inline-block';
	}
	document.gsreplaymode='displaymedia';
	if (gid('gsreplayicon')) gid('gsreplayicon').hint='record screen';
}

_gsreplay_rec_start=function(d){
	ajxjs2('gsreplay_rec_start','gsreplay.js',function(){
		gsreplay_rec_start(d);
	});	
}