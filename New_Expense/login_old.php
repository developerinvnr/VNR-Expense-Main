<?php
date_default_timezone_set('asia/calcutta');
session_start();
include "config.php";
include('../hrims/AdminUser/codeEncDec.php');
$tm=time();
$dat=date('Y-m-d',$tm);
$empcode=$_POST['empcode'];
 
if($_POST['empcode']>0 AND (is_numeric($_POST['empcode'])))
{
 $emppass=$_POST['emppass'];
 $run_qry=mysql_query("select * from hrm_employee where EmpCode='$empcode' and CompanyId=1 and EmpStatus='A'",$con2);
 $num=mysql_num_rows($run_qry);
 if($num>0)
 {
  $info=mysql_fetch_array($run_qry);
  $emppass1=decrypt($info['EmpPass']); $emppass2=decrypt($info['EmpPass_2']);
  
  if($emppass=='$emppass1' OR '$emppass==$emppass2')
  {
   $_SESSION['login']=true;
   $_SESSION['EmployeeID']=$info['EmployeeID'];
   $_SESSION['EmpCode']=$info['EmpCode'];
   $_SESSION['Fname']=$info['Fname'];


   $apprsel=mysql_query("select * from hrm_employee_reporting where AppraiserId='".$info['EmployeeID']."'",$con2);
   // echo mysql_num_rows($apprsel); die();
   if(mysql_num_rows($apprsel)>0){ $_SESSION['EmpRole']='A'; }else{ $_SESSION['EmpRole']='E'; }
   $_SESSION['FYearId']=$_POST['FYear'];

   $y=mysql_query("SELECT Year FROM `financialyear` where YearId='".$_POST['FYear']."'",$con); $year=mysql_fetch_assoc($y);
   $_SESSION['FYear']=$year['Year'];
   $_SESSION['todayDate']=date("Y-m-d");

   $m=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and YearId=".$_SESSION['FYearId']." and `Status`='Open' limit 1");
   if(mysql_num_rows($m)>0){ $ms=mysql_fetch_assoc($m); $_SESSION['todayMonth']=date("m",strtotime('2019-'.$ms['Month'].'-01')); }else{ $_SESSION['todayMonth']=4; }


   setcookie("login", $_SESSION['login'], time() + (86400 * 30), "/");
   setcookie("EmployeeID", $_SESSION['EmployeeID'], time() + (86400 * 30), "/");
   setcookie("EmpCode", $_SESSION['EmpCode'], time() + (86400 * 30), "/");
   setcookie("Fname", $_SESSION['Fname'], time() + (86400 * 30), "/");

   setcookie("EmpRole", $_SESSION['EmpRole'], time() + (86400 * 30), "/");
   setcookie("FYearId", $_SESSION['FYearId'], time() + (86400 * 30), "/");
   setcookie("FYear", $_SESSION['FYear'], time() + (86400 * 30), "/");
   setcookie("todayDate", $_SESSION['todayDate'], time() + (86400 * 30), "/");
   setcookie("todayMonth", $_SESSION['todayMonth'], time() + (86400 * 30), "/");

    echo $msg="Login Successfull";
  
  //header('location:home.php?msg='.$msg.''); 
  ?>
  <script>window.location.href = 'home.php?msg=<?=$msg?>';</script>
  <?php
  }
  else{ $msg="Something went wrong"; ?> <script>window.location.href = 'index.php?msg=<?=$msg?>&msgcolor=danger';</script> <?php }
 }
 else{ $msg="Something went wrong"; 
 // header('location:index.php?msg='.$msg.'&msgcolor=danger');
 ?>
 <script>window.location.href = 'index.php?msg=<?=$msg?>&msgcolor=danger';</script>
 <?php
}
 
}
else
{
 $emppass=md5($_POST['emppass']);
 $run_qry=mysql_query("select * from hrm_user where EmpCode='".$empcode."' and EmpPass='".$emppass."' and EmpStatus='A'",$con);
 $num=mysql_num_rows($run_qry);
 if($num>0)
 {
  $info=mysql_fetch_array($run_qry);
  $_SESSION['login']=true;
  $_SESSION['EmployeeID']=$info['EmployeeID'];
  $_SESSION['EmpCode']=$info['EmpCode'];
  $_SESSION['Fname']=$info['Fname'];
  $_SESSION['EmpRole']=$info['EmpRole'];
  $_SESSION['FYearId']=$_POST['FYear'];

  $y=mysql_query("SELECT Year FROM `financialyear` where YearId='".$_POST['FYear']."'",$con);
  $year=mysql_fetch_assoc($y);
  $_SESSION['FYear']=$year['Year'];

  // $_SESSION['todayDate']=date("Y-m-d");



  // $m=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and YearId=".$_SESSION['FYearId']." and `Status`='Open' limit 1");
  // $ms=mysql_fetch_assoc($m);
  // $_SESSION['todayMonth']=date("m",strtotime('2019-'.$ms['Month'].'-01'));


  setcookie("login", $_SESSION['login'], time() + (86400 * 30), "/");
  setcookie("EmployeeID", $_SESSION['EmployeeID'], time() + (86400 * 30), "/");
  setcookie("EmpCode", $_SESSION['EmpCode'], time() + (86400 * 30), "/");
  setcookie("Fname", $_SESSION['Fname'], time() + (86400 * 30), "/");
  
  setcookie("EmpRole", $_SESSION['EmpRole'], time() + (86400 * 30), "/");
  setcookie("FYearId", $_SESSION['FYearId'], time() + (86400 * 30), "/");
  setcookie("FYear", $_SESSION['FYear'], time() + (86400 * 30), "/");
  setcookie("todayDate", $_SESSION['todayDate'], time() + (86400 * 30), "/");
  setcookie("todayMonth", $_SESSION['todayMonth'], time() + (86400 * 30), "/");

  $msg="Login Successfull";
  // header('location:home.php?msg='.$msg.''); 
  ?>
  <script>
    window.location.href = 'home.php?msg=<?=$msg?>';
  </script>
  <?php
  
 } 
 else{ $msg="Something went wrong";
  // header('location:index.php?msg='.$msg.'&msgcolor=danger');  
  ?>
  <script>
    window.location.href = 'index.php?msg=<?=$msg?>&msgcolor=danger';
  </script>
  <?php
}
}





?>