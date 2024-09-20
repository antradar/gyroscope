<?php

function gsdb_showquerysummary(){
	$user=userinfo();
	if (!isset($user['groups']['dbadmin'])) apperror('Access denied');
	
    global $db_profiler;
    if (!file_exists($db_profiler)) return;
    
    global $db;

    $f=fopen($db_profiler,'r');

    
?>
<style>
    .gridrow.nokey, .qlog_subrow.nokey{box-sizing:border-box;border:solid 2px #ab0200;}
    .qlog_subrow.nokey{padding:10px 0;}

    .qlogcol0, .qlogcol1, .qlogcol2, .qlogcol3, .qlogcol4{float:left;margin-right:1%;}
    .qlogcol0{width:19%;}
    .qlogcol1{width:14%;text-align:right;}
    .qlogcol2{width:14%;text-align:right;}
    .qlogcol3{width:11%;text-align:right;margin-right:3%;}
    .qlogcol4{width:35%;margin-right:0;}

    .qlog_queries{font-size:12px;padding:10px 0;display:none;}
    .qlog_subrow{margin-bottom:5px;}
    .qslogcol0, .qslogcol1, .qslogcol2{float:left;margin-right:1%;}
    .qslogcol0{margin-left:15%;width:9%;}
    .qslogcol1{width:9%;text-align:right;}
    .qslogcol2{margin-left:29%;width:14%;}

</style>

<div class="section">
    <div class="sectiontitle">Query Summary</div>

    <div class="stable">
    <div class="grid">
    <div class="gridrow">
        <div class="qlogcol0">Command</div>
        <div class="qlogcol1">Avg.</div>
        <div class="qlogcol2">mDev.</div>
        <div class="qlogcol3">Uniq.</div>
        <div class="qlogcol4">Key</div>
        <div class="clear"></div>
    </div>
<?php    
    $cmds=array();
    while ($query=fgets($f)){
        if (trim($query)=='') continue;
        $obj=json_decode($query,1);
        $cmd=$obj['cmd']??'';
        if ($cmd=='') continue;
        if ($cmd=='gsdb_showquerysummary') continue;
        if ($cmd=='gsdb_showcmdqueries') continue;
        
        if (!isset($cmds[$cmd])) $cmds[$cmd]=array('count'=>0,'cost'=>0,'nokey'=>0,'queries'=>array());

        $q=$obj['query'];
        $qkey=md5($q);
        if (!isset($cmds[$cmd]['queries'][$qkey])) $cmds[$cmd]['queries'][$qkey]=array('count'=>0,'cost'=>0,'nokey'=>0,'query'=>$q);

        $params=$obj['params'];

        $exq="explain format=json $q";

        ob_start();
        $rs=@sql_prep($exq,$db,$params);
        if (!$myrow=sql_fetch_assoc($rs)) {
	        ob_end_clean();
	        continue;
        }
        ob_end_clean();
        
        $res=json_decode($myrow['EXPLAIN'],1);
        if (!isset($res)) continue;

        if (!isset($res['query_block']['cost_info'])) continue;
        
        $cost=floatval($res['query_block']['cost_info']['query_cost']);

        $cmds[$cmd]['queries'][$qkey]['count']++;
        $cmds[$cmd]['queries'][$qkey]['cost']+=$cost;

        //echo '<pre>'; print_r($res); echo '</pre>';
        $looproot=&$res['query_block']['nested_loop'];
        if (!isset($looproot)) $looproot=&$res['query_block']['ordering_operation']['nested_loop'];
        if (isset($looproot)&&count($looproot)==0&&isset($res['query_block']['grouping_operation'])){
            $looproot=&$res['query_block']['grouping_operation']['nested_loop'];
        }
        if (isset($looproot)){
            foreach ($looproot as $nloop){
                //echo '<pre>'; print_r($nloop); echo '</pre>';
                if (!isset($nloop['table'])) continue;
                if (!isset($nloop['table']['possible_keys'])||count($nloop['table']['possible_keys'])==0) {
	                if (!isset($nloop['table']['key'])){
                   	 $cmds[$cmd]['queries'][$qkey]['nokey']=1;
                   	 $cmds[$cmd]['nokey']=1;
                	}
                }
            }
        }   

        $cmds[$cmd]['cost']+=$cost;
        $cmds[$cmd]['count']++;
    }
    fclose($f);

    foreach ($cmds as $idx=>$cmd){
        $avg=$cmd['count']>0?round($cmd['cost']/$cmd['count'],2):0;
        $cmds[$idx]['avg']=$avg;
        $mdev=0;
        foreach ($cmd['queries'] as $q){
            $mdev+=abs($q['cost']-$avg);
        }
        $mdev=$cmd['count']>0?round($mdev/$cmd['count'],2):0;
        $cmds[$idx]['mdev']=$mdev;
    }

    uasort($cmds,function($a,$b){
        return $a['avg']>$b['avg']?-1:1;
    });

   // echo '<pre>'; print_r($cmds); echo '</pre>';

   foreach ($cmds as $cmdkey=>$cmd){
	   if (trim($cmdkey)=='') continue;
	   if ($cmd['count']==0) continue;

    ?>
    <div class="gridrow<?php if ($cmd['nokey']) echo ' nokey';?>">
        <div class="qlogcol0"><a class="hovlink" onclick="showhide('qlog_queries_<?php echo $cmdkey;?>');"><?php echo $cmdkey;?></a></div>
        <div class="qlogcol1"><?php echo number_format($cmd['avg'],2);?></div>
        <div class="qlogcol2"><?php echo $cmd['mdev'];?></div>
        <div class="qlogcol3"><?php echo count($cmd['queries']);?></div>
        <div class="qlogcol4">
            <?php
            if ($cmd['nokey']) echo '<span style="color:#ab0200">NO</span>';
            else echo 'OK';
            ?>
        </div>

        <div class="clear"></div>

        <div class="qlog_queries" id="qlog_queries_<?php echo $cmdkey;?>">
            <?php foreach ($cmd['queries'] as $qkey=>$q){
				if ($q['count']==0) continue;
            ?>
            <div class="qlog_subrow<?php if ($q['nokey']) echo ' nokey';?>">
                <div class="qslogcol0">
                    <a class="hovlink" onclick="addtab('gsdb_<?php echo $cmdkey;?>_<?php echo $qkey;?>','QS: <?php echo $cmdkey;?>','gsdb_showcmdqueries&cmdkey=<?php echo $cmdkey;?>&qkey=<?php echo $qkey;?>');">
                        <?php echo substr($qkey,0,6).'...';?>
                    </a>
                </div>
                <div class="qslogcol1"><?php echo number_format($q['cost']/$q['count'],2);?></div>
                <div class="qslogcol2">
                <?php
                if ($q['nokey']) echo '<span style="color:#ab0200">NO</span>';
                else echo 'OK';
                ?>
                </div>
                <div class="clear"></div>
            </div>
            <?php } ?>
        </div>

    </div>

    <?php

   }

?>

    </div><!-- grid -->
    </div><!-- stable -->
    
</div><!--section-->
<?php
}