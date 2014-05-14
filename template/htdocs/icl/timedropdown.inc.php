<?php

function timedropdown($start=9,$end=22,$res=15,$h24=0,$seltime=''){

for ($i=$start;$i<=$end;$i++){
  $tail='am';
  for ($j=0;$j<60;$j+=$res){
    if ($i>=12) $tail='pm';
    $pi=$i%12;
    if ($pi==0&&$i>0) $pi=12;
    $pj=$j;
    
    $pi=str_pad($pi,2,'0',STR_PAD_LEFT);
    $pj=str_pad($pj,2,'0',STR_PAD_LEFT);
    $i=str_pad($i,2,'0',STR_PAD_LEFT);
    
    $t=$pi.':'.$pj.$tail;
    
    $val=$t;
    if ($h24) $val=$i.':'.$pj;
    
?>
<option value="<?echo $val;?>" <?if ($val==$seltime) echo 'selected';?>><?echo $t;?></option>
<?
  }
}
	
}