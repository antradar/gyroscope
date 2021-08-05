<?php
include 'lb.php';
include 'auth.php';

$csrfkey=sha1($salt.'csrf'.$_SERVER['REMOTE_ADDR'].'-'.$_SERVER['O_IP'].date('Y-m-j-g'));
echo $csrfkey; die();