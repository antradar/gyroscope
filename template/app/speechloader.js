if (window.webkitSpeechRecognition&&document.dict.speechlang){
	gid('speechstart').style.display='inline';
	
	var recognition=new webkitSpeechRecognition();
	recognition.continuous=false;//true;
	recognition.interimResults=false;
	recognition.lang=document.dict.speechlang;

	recognition.onstart=function(){
		document.recog=true;
		gid('speechstart').style.opacity=0.5;
	}

	recognition.onend=function(){
		if (document.rechold) return;		
		document.recog=false;
		gid('speechstart').style.opacity=1;
		if (!document.recstop) document.recognition.start();
		
	}
	
	recognition.onresult=function(e){
		for (var i=e.resultIndex; i<e.results.length; i++){
			var phrase=e.results[i][0].transcript;
			phrase=phrase.trim();
			var conf=e.results[i][0].confidence;
		
			if (!gid('speechstart').mobile){
				gid('speechstart').style.marginLeft='10px';
				setTimeout(function(){gid('speechstart').style.marginLeft=0;},300);
			}

			speech_process(phrase,conf);
		}
	}

	document.recognition=recognition;	

}

say=function(phrase,noresume){
	if (!document.dict.speechlang) return;
	//if (document.utterlock) return;
	document.utterlock=true;
	
	if (noresume){
		if (document.recognition&&document.recog) {document.rechold=true;document.recognition.stop();gid('speechstart').style.opacity=1;}
	}
	
	if (window.SpeechSynthesisUtterance){
		var utterance = new SpeechSynthesisUtterance(phrase);
		utterance.lang=document.dict.speechlang;
		utterance.onend=function(){
			if (!noresume){
				if (document.recognition&&document.rechold) {document.recognition.start();document.rechold=null;}
			} else {
				gid('speechstart').style.opacity=1;
				document.rechold=null;			
			}
		}
		window.speechSynthesis.speak(utterance);
	}
	
	setTimeout(function(){document.utterlock=null;},500);
}