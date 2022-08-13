function valstr(d,cp){
	var ocp=cp;
	if (cp==null) cp=d;
	if (d.value==''){cp.style.borderColor='red';cp.style.outline=0;if (ocp!=null) cp.style.color='red';return false;}
	cp.style.borderColor='#999999';
	cp.style.outline='';
	if (ocp!=null) cp.style.color='';
	return true;
}
function valmonth(d){
	if (d.value.replace(/\d\d\d\d\-\d+/g,'')!=''||d.value==''){d.style.borderColor='red';d.style.outline=0;return false;}
	d.style.borderColor='#999999';
	d.style.outline='';
	return true;
}

function valdate(d){
	if (d.value.replace(/\d\d\d\d\-\d+\-\d+/g,'')!=''||d.value==''){d.style.borderColor='red';d.style.outline=0;return false;}
	d.style.borderColor='#999999';
	d.style.outline='';
	return true;
}

function valssn(d){
	var t=d.value.replace(/[^\d\-\s]/g,'');
	if (t==''||t!=d.value){d.style.borderColor='red';d.style.outline=0;return false;}
	d.style.borderColor='#999999';
	d.style.outline='';
	return true;
}

function valint(d,opt){
	var val=d.value;
	var valid=1;
	var rval=parseInt(val,10);
	if (rval!=val) valid=0;
	if (opt!=null){
		if (opt.min!=null&&rval<opt.min) valid=0;
		if (opt.max!=null&&rval>opt.max) valid=0;
	}
	if (!valid) {d.style.borderColor='red';d.style.outline=0;return false;}
	d.style.borderColor='#999999';
	d.style.outline='';
	return true;
}

function valfloat(d,opt){
	var val=d.value;
	var valid=1;
	var rval=parseFloat(val);
	if (rval!=val) valid=0;
	if (opt!=null){
		if (opt.min!=null&&rval<opt.min) valid=0;
		if (opt.max!=null&&rval>opt.max) valid=0;
	}
	if (!valid) {d.style.borderColor='red';d.style.outline=0;return false;}
	d.style.borderColor='#999999';
	d.style.outline='';
	return true;
}

function currencyvalue(val){
	var val=val+'';
	val=val.split(' ').join('');
	if (document.dict&&document.dict.currency_separator_thousands!=null&&document.dict.currency_separator_decimal!=null){
		val=val.split(document.dict.currency_separator_thousands).join('').split(document.dict.currency_separator_decimal).join('.');	
	}
	if (parseFloat(val)!=val) return '';
	return parseFloat(val);
}

function valcurrency(d){
	var val=currencyvalue(d.value);
	if (parseFloat(val)!=val){d.style.borderColor='red';d.style.outline=0;return false;}
	d.style.borderColor='#999999';
	d.style.outline='';
	return true;
}

function valrate(d){
	if (parseFloat(d.value.replace(/%/g,''))!=d.value.replace(/%/g,'')){d.style.borderColor='red';d.style.outline=0;return false;}
	d.style.borderColor='#999999';
	d.style.outline='';
	return true;
}

function valemail(d){
	if (d.value.indexOf(',')!=-1||d.value.indexOf(';')!=-1||d.value=='x'||d.value.replace(/\S+@\S+\.\S+/g,'x')!='x'){d.style.borderColor='red';d.style.outline=0;return false;}
	d.style.borderColor='#999999';
	d.style.outline='';
	return true;
}
