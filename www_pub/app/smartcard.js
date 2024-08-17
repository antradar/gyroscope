/*
Smartcard Library for Signing with Estonian ID Cards
(c) Schien Dong, Antradar Software, 2015

Tested against plugin version 3.8.1.1116

Documentation: http://www.antradar.com/doc-smartcard-js
Plugin Installer: https://installer.id.ee/?lang=eng
Chrome Extension: https://chrome.google.com/webstore/detail/ckjefchnfjhjfedoccjbhjpbncimppeg


Quick Start:

smartcard_init('reader');
if (document.reader){
  cert=document.reader.getcert();
  if (cert){
    if (document.reader.sign(cert.id,'hashstring')){
      alert('Signed');
    }//sign
  }//cert
}//reader


Certificate Info: {id, certificateAsHex, CN, issuerCN, validFrom, validTo}

*/

function smartcard_init(objname,funcs){

	if (!funcs){
		funcs={
			'noplugin':function(){alert('Smartcard reader plugin not installed.');},
			'nohttps':function(){alert('HTTPS must be enabled');},
			'inited':function(){}			
		}
	}


	if (document.location.href.indexOf("https://")==-1){funcs.nohttps(); return;}

	if (!window.TokenSigning) {

		funcs.noplugin(); return;	
	}	

	var digidoc=new window.TokenSigning();
		


	digidoc.getcert=function(callback){
		if (document.getElementById('cardreader_'+objname)){
			try{
				callback(document.getElementById('cardreader_'+objname).getCertificate());
			} catch (e){callback(null);return;}
		} else {
			digidoc.getCertificate({lang:'en'}).then(function(res){callback({'certificateAsHex':res.hex,'CN':'(ID Card Holder)'});},function(res){callback(null);});
		}
	}

	digidoc.signdoc=function(certid,hash){
		try{
			return digidoc.sign(certid,hash,'');
		} catch (e){return null;}
	}
	
	if (funcs.inited) funcs.inited();

	document[objname]=digidoc;

}


