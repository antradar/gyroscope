<?php

include 'pretty_array.php';

function gsdb_showcmdqueries(){
	$user=userinfo();
	if (!isset($user['groups']['dbadmin'])) apperror('Access denied');
	
    global $db_profiler;
    if (!file_exists($db_profiler)) return;
    
    global $db;


    $mycmd=SGET('cmdkey');
    if ($mycmd=='') return;

    $f=fopen($db_profiler,'r');
    
    $myqkey=SGET('qkey');

    global $db;
   
?>

<div class="section">
    <div class="sectiontitle"><?php echo $mycmd;?> / <?php echo $myqkey;?></div>

<?php   
    $qobj=null;

    while ($query=fgets($f)){

        if (trim($query)=='') continue;
        $obj=json_decode($query,1);
        if (!isset($obj)) continue;
        $cmd=$obj['cmd'];
        $q=$obj['query'];
        $qkey=md5($q);
        if ($cmd!=$mycmd||$qkey!=$myqkey) {
            continue;
        } else {
            $qobj=$obj;
            break;
        }
    }//foreach

    fclose($f);

    if (!isset($qobj)) return;
    
    $params=$qobj['params'];

    $exq="explain format=json $q";

    $rs=@sql_prep($exq,$db,$params);
    $myrow=sql_fetch_assoc($rs);

    $res=json_decode($myrow['EXPLAIN'],1);

    $badtables=array();

    $looproot=&$res['query_block']['nested_loop'];
    if (!isset($looproot)) $looproot=&$res['query_block']['ordering_operation']['nested_loop'];
    if (isset($looproot)&&count($looproot)==0&&isset($res['query_block']['grouping_operation'])){
        $looproot=&$res['query_block']['grouping_operation']['nested_loop'];
    }

    if (isset($looproot)){
	    foreach ($looproot as $nloop){
	        if (!isset($nloop['table'])) continue;
	        if (!isset($nloop['table']['possible_keys'])||count($nloop['table']['possible_keys'])==0) {
	            array_push($badtables,$nloop['table']['table_name']);
	        }
	    }
	}

    ?>
    <textarea class="inplong" name="_"><?php echo htmlspecialchars($qobj['query']);?></textarea>
    <?php if (isset($qobj['params'])&&count($qobj['params'])>0){?>
        <div><b>Params:</b></div>
        <?php foreach ($qobj['params'] as $k=>$v){?>
            <div class="listitem"><?php echo $k;?> => <?php echo htmlspecialchars($v);?></div>  
        <?php } ?>
    <?php } ?>

    <?php
    //echo '<pre>'; print_r($res); echo '</pre>';

    if (count($badtables)>0){
    ?>
    <div class="warnbox">
        The following table<?php echo count($badtables)==1?' has':'s have';?> no possible keys for the above query:<br>
        <?php foreach ($badtables as $badtable) echo "<nobr><u>".$badtable.'</u></nobr> &nbsp; ';?>
    </div>
    <?php    
    }

    pretty_array($res['query_block'],$mycmd.'_'.$qkey);

?>
</div><!--section-->
<?php
}