<?php
//error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
define('HOST','localhost');
define('USER','vnrseed2_hr'); 
define('PASS','vnrhrims321'); 
define('dbexpro','vnrseed2_expense');
define('dbemp','vnrseed2_hrims');  
define('CHARSET','utf8'); 
$con2=mysql_connect(HOST,USER,PASS) or die(mysql_error());
$empdb=mysql_select_db(dbemp, $con2) or die(mysql_error());
$con=mysql_connect(HOST,USER,PASS,true) or die(mysql_error());
$exprodb=mysql_select_db(dbexpro,$con) or die(mysql_error());
mysql_query("SET NAMES utf8");
date_default_timezone_set('Asia/Kolkata');
?>
