stripe_init=function(lang,pkey){
	xajxjs('Stripe','https://js.stripe.com/v3/',function(){
		console.log('Stripe library loaded');
		
		var stripe = Stripe(pkey);
		var elements=stripe.elements({locale:lang});
		var style = {
		  base: {
		    color: '#A7202F',
		    lineHeight: '30px',
		    fontFamily: 'Arial, sans-serif',
		    fontSmoothing: 'antialiased',
		    fontSize: '16px',
		    '::placeholder': {
		      color: '#aab7c4'
		    }
		  },
		  invalid: {
		    color: '#fa755a',
		    iconColor: '#fa755a'
		  }
		};
				
		var card = elements.create('card');
		card.mount('#ccnum',{style:style});
		gid('ccnum').ccard=card;			
		document.stripe=stripe;	
			
	});
}


addcreditcard=function(lang,pkey,gskey){
	var occnum=gid('ccnum');
	
	if (!occnum.ccard) return;	
	
	document.stripe.createToken(occnum.ccard).then(function(res){
		
		if (res.error){
			salert(res.error.message);
			
			return;
		} else {
			
			var params=[];
			
			params.push('token='+res.token.id); 
			
			reloadtab('creditcards','','addcreditcard',function(){stripe_init(lang,pkey);},params.join('&'),null,gskey);
				
		}
	});	
	
		
}

setdefaultcreditcard=function(cardid,lang,pkey,gskey){
	reloadtab('creditcards','','setdefaultcreditcard',function(){stripe_init(lang,pkey);},'cardid='+cardid,null,gskey);
}

delcreditcard=function(cardid,lang,pkey,gskey){
	if (!sconfirm('Are you sure you want to remove this card?')) return;
	reloadtab('creditcards','','delcreditcard',function(){stripe_init(lang,pkey);},'cardid='+cardid,null,gskey);
}
