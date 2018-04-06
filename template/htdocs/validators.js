function valstr(d){
	if (d.value==''){d.style.borderColor='red';return false;}
	d.style.borderColor='#666666';
	return true;
}

function valmonth(d){
	if (d.value.replace(/\d\d\d\d\-\d+/g,'')!=''||d.value==''){d.style.borderColor='red';return false;}
	d.style.borderColor='#666666';
	return true;
}

function valdate(d){
	if (d.value.replace(/\d\d\d\d\-\d+\-\d+/g,'')!=''||d.value==''){d.style.borderColor='red';return false;}
	d.style.borderColor='#666666';
	return true;
}

function valint(d){
	var val=d.value;
	
	if (parseInt(val,10)!=val){d.style.borderColor='red';return false;}
	d.style.borderColor='#666666';
	return true;
}

function valfloat(d){
	var val=d.value;	
	if (parseFloat(val)!=val){d.style.borderColor='red';return false;}
	d.style.borderColor='#666666';
	return true;
}

function valcurrency(d){
	var val=d.value.split(' ').join('');
	if (document.dict&&document.dict.currency_separator_thousands!=null&&document.dict.currency_separator_decimal!=null){
		val=val.split(document.dict.currency_separator_thousands).join('').split(document.dict.currency_separator_decimal).join('.');	
	}
	
	if (parseFloat(val)!=val){d.style.borderColor='red';return false;}
	d.style.borderColor='#666666';
	return true;
}

function valrate(d){
	if (parseFloat(d.value.replace(/%/g,''))!=d.value.replace(/%/g,'')){d.style.borderColor='red';return false;}
	d.style.borderColor='#666666';
	return true;
}

function valemail(d){
	if (d.value=='x'||d.value.replace(/\S+@\S+\.\S+/g,'x')!='x'){d.style.borderColor='red';return false;}
	d.style.borderColor='#666666';
	return true;
}
