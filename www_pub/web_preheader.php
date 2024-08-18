<?php
include 'app/lb.php';
include 'app/connect.php';
include 'app/forminput.php';

if (!$gs_public_web){header('Location: app/'); die();}
