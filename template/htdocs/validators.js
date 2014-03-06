function valstr(d){
  if (d.value==''){
    d.style.borderColor='red';
    return false;
  }

  d.style.borderColor='#666666';
  return true;
}

function valdate(d){
  if (d.value.replace(/\d\d\d\d\-\d+\-\d+/g,'')!=''||d.value==''){
    d.style.borderColor='red';
    return false;
  }

  d.style.borderColor='#666666';
  return true;
}

function valfloat(d){
  if (parseFloat(d.value)!=d.value){
    d.style.borderColor='red';
    return false;
  }

  d.style.borderColor='#666666';
  return true;
}

function valrate(d){
  if (parseFloat(d.value.replace(/%/g,''))!=d.value.replace(/%/g,'')){
    d.style.borderColor='red';
    return false;
  }

  d.style.borderColor='#666666';
  return true;
}

function valemail(d){
  if (d.value=='x'||d.value.replace(/\S+@\S+\.\S+/g,'x')!='x'){
    d.style.borderColor='red';
    return false;
  }

  d.style.borderColor='#666666';
  return true;
}
