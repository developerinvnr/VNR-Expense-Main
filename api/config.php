<?php 
date_default_timezone_set('Asia/Kolkata');
if($_REQUEST['ComId']==1){ $DbName='expense'; }
elseif($_REQUEST['ComId']==3){ $DbName='expense_nr'; }
elseif($_REQUEST['ComId']==4){ $DbName='expense_tl'; }

$con=mysqli_connect('localhost','expense_user','expense@192');
$db=mysqli_select_db($con, $DbName);

$con2=mysqli_connect('localhost','hrims_user','hrims@192');
$db=mysqli_select_db($con2, 'hrims');
?>
