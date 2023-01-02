<?php
session_start();
include 'config.php';

if($_POST['act']=='upcourierdetails')
{ 
  $up=mysql_query("update y".$_POST['yid']."_monthexpensefinal set PostDate='".date("Y-m-d",strtotime($_POST['pd']))."', DocateNo='".$_POST['dn']."', Agency='".$_POST['ag']."' where EmployeeID=".$_POST['eid']." AND Month=".$_POST['m']." AND YearId=".$_POST['yid'],$con);
  if($up){echo 'done';}else{echo 'error';}
}

elseif($_POST['act']=='VerifyCourierDetails')
{ 
  $up=mysql_query("update y".$_POST['yid']."_monthexpensefinal set RecevingDate='".date("Y-m-d",strtotime($_POST['rd']))."',  DocRmk='".$_POST['rmk']."' where EmployeeID=".$_POST['eid']." AND Month=".$_POST['m']." AND YearId=".$_POST['yid'],$con);
  if($up){echo 'done';}else{echo 'error';}
}

//VerifDate='".date("Y-m-d",strtotime($_POST['vd']))."',
?>