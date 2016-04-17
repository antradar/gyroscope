/*
Smartcard Library for Signing with Estonian ID Cards
(c) Schien Dong, Antradar Software, 2015

Tested against plugin version 3.8.1.1116

Documentation: http://www.antradar.com/doc-smartcard-js
Plugin Installer: https://installer.id.ee/?lang=eng


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
			'nohttps':function(){alert('HTTPS must be enabled');}			
		}
	}


	if (document.location.href.indexOf("https://")==-1){funcs.nohttps(); return;}

	var div=document.createElement('div');
	div.style.visibility='hidden';
	div.innerHTML='<object id="cardreader_'+objname+'" type="application/x-digidoc" style="width: 1px; height: 1px; visibility: hidden;"></object>';

	document.body.appendChild(div);

	var digidoc=document.getElementById('cardreader_'+objname);

	if (!digidoc.version) {funcs.noplugin();return;}

	digidoc.getcert=function(){
		try{
			return document.getElementById('cardreader_'+objname).getCertificate();
		} catch (e){return null;}
	}

	digidoc.signdoc=function(certid,hash){
		try{
			return digidoc.sign(certid,hash,'');
		} catch (e){return null;}
	}

	document[objname]=digidoc;

}


