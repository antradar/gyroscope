registerpayment=function(d,pmid){
  var paid=0;
  if (d.checked) paid=1;
  ajxb('domussrv.php?cmd=rpm&pmid='+pmid+'&paid='+paid);
  d.disabled='disabled';
  if (d.timer) clearTimeout(d.timer);
  d.timer=setTimeout(function(){d.disabled='';},800);
}

showpayments=function(year,mon){
  reloadtab('payment_'+year+'_'+mon,'Payments '+year+'-'+mon,'pms&year='+year+'&mon='+mon);
  addtab('payment_'+year+'_'+mon,'Payments '+year+'-'+mon,'pms&year='+year+'&mon='+mon);
}
