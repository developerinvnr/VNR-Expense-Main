<?php 

session_start();

if($_SESSION['CompanyId']==1){ $DbName='expense'; }
elseif($_SESSION['CompanyId']==3){ $DbName='expense_nr'; }
elseif($_SESSION['CompanyId']==4){ $DbName='expense_tl'; }
       
define('HOST','localhost');
define('USER','expense_user'); 
define('PASS','expense@192'); 
define('dbexpro',$DbName);
define('dbemp','hrims');  

define('USER2','hrims_user'); 
define('PASS2','hrims@192');
define('CHARSET','utf8'); 
$con2=mysql_connect(HOST,USER2,PASS2) or die(mysql_error());
$empdb=mysql_select_db(dbemp, $con2) or die(mysql_error());
$con=mysql_connect(HOST,USER,PASS,true) or die(mysql_error());
$exprodb=mysql_select_db(dbexpro,$con) or die(mysql_error());
mysql_query("SET NAMES utf8");
date_default_timezone_set('Asia/Kolkata');
?>
