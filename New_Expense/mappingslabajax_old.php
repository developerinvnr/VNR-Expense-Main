<?php 
session_start();
include 'config.php';

if($_POST['act']=='MappedSlab' && $_POST['slb']>0 && $_POST['ei']>0)
{ 
 $sql=mysql_query("select * from vehicle_policyslab_employee where EmployeeID=".$_POST['ei']);
 $row=mysql_num_rows($sql);
 if($row==0)
 {
  $upins=mysql_query("insert into vehicle_policyslab_employee(EmployeeID, VPId, CrBy, CrDate, SysDate) values(".$_POST['ei'].", ".$_POST['slb'].", ".$_SESSION['EmployeeID'].", '".date("Y-m-d")."', '".date("Y-m-d")."')");
 }
 else
 {
  $upins=mysql_query("update vehicle_policyslab_employee set VPId=".$_POST['slb'].", CrBy=".$_SESSION['EmployeeID'].", CrDate='".date("Y-m-d")."', SysDate='".date("Y-m-d")."' where EmployeeID=".$_POST['ei']);
 }
 
 if($upins){echo 'Done';}
 
}
?>
