<?php

function xsscheck($easy=0,$ctx=null){
	
	if (isset($ctx)) $server=$ctx->server; else {global $_SERVER; $server=$_SERVER;}
	
	gs_header($ctx, 'X-Frame-Options', 'SAMEORIGIN');
	gs_header($ctx, 'X-XSS-Protection', '1; mode=block');
	gs_header($ctx, 'X-Content-Type-Options', 'nosniff');
	
	//header("Content-Security-Policy: child-src 'self'");

	gs_header($ctx,"Content-Security-Policy", "child-src 'self' *.stripe.com");
		
	//header("Content-Security-Policy: default-src 'self'; child-src 'self';");
			
	if (!$easy||true){ //comment out ||true to relax cross-site signon
		$referer=isset($server['HTTP_REFERER'])?$server['HTTP_REFERER']:'';
		$referer=str_replace('http://','',$referer);
		$referer=str_replace('https://','',$referer);
		$host=preg_quote($server['HTTP_HOST']);
		$pattern='/^'.$host.'/';
		
		$fedbypass=0;
	
		//federated signon
		
		//if (preg_match('/^foreign-source-site\.com\/login\.php/',$referer)&&$referer!='') $fedbypass=1;
	
		if (!$fedbypass){
		
			if (!preg_match($pattern,$referer)&&$referer!='') {
				if (isset($ctx)) $ctx->response->status(403); 
				else header('HTTP/1.0 403 Forbidden');
				
				gs_header($ctx,'X-STATUS', '403');
				
				if (isset($ctx)){
					$ctx->response->_ended=true;
					$ctx->response->end();
				} else die();
			}
		}
	}

}
