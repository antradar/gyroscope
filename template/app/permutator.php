<?php
/*
Variable Radix Permutation Generator 
(c) Schien Dong, Antradar Software Inc.

Sample use:

$tops=array(5,2,4); //mixing three groups, each has 5, 2, 4 options respectively
permutator_generate($tops); //returns a collection of positions, e.g. (0,0,0), (0,0,1), ... , (4,1,3) - all 40 permutations

*/

function permutator_generate($tops){
	$max=1;
	$cas=array();
	foreach ($tops as $top) {$max=$max*$top;array_push($cas,0);}
	
	$res=array();
	
	array_push($res,$cas);
	
	for ($i=0;$i<$max-1;$i++){
		$cas[0]++;
		permutator_overflow($cas,$tops);
		array_push($res,$cas);
		
	}
	
	return $res;
	
}

function permutator_overflow(&$cas,$tops){
	$ptr=0;
	$overflow=$cas[$ptr]>=$tops[$ptr];
	while ($overflow&&$ptr<count($tops)){
		$cas[$ptr]=0;
		$ptr++;
		$cas[$ptr]++;
		$overflow=$cas[$ptr]>=$tops[$ptr];	
	}	
}
