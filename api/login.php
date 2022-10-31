<?php
date_default_timezone_set('Asia/Kolkata');
$con2=mysqli_connect('localhost','hrims_user','hrims@192');
$db=mysqli_select_db($con2, 'hrims');
include('../codeEncDec.php');

if($_REQUEST['value'] == ''){ echo json_encode(array("msg" => "Parameter Missing!") ); }
 
//Login
elseif($_REQUEST['value']=='login' && $_REQUEST['ComId']>0 && $_REQUEST['empcode']>0 && $_REQUEST['emppass']!='' && $_REQUEST['yearid']!='')
{
  $empcode=$_REQUEST['empcode'];
  $emppass=$_REQUEST['emppass'];
  
/* -------------------------------------------------------- */
/* -------------------------------------------------------- */

 
 /********************************************************/
 $qry=mysqli_query($con2,"select EmpStatus,DateOfSepration from hrm_employee where EmpCode='$empcode' and EmpStatus!='De' and CompanyId=".$_REQUEST['ComId']); $rqry=mysqli_fetch_assoc($qry);
 $DatAcc=date("Y-m-d",strtotime($rqry['DateOfSepration'].'+15 day'));
 
 if($rqry['EmpStatus']=='A')
 {  
  $run_qry=mysqli_query($con2,"select * from hrm_employee where EmpCode=".$empcode." and CompanyId=".$_REQUEST['ComId']." and EmpStatus='A'"); 
 }
 elseif($rqry['EmpStatus']=='D' AND $DatAcc!='0000-00-00' && $DatAcc!='1970-01-01')
 {
  $run_qry=mysqli_query($con2,"select * from hrm_employee where EmpCode='$empcode' and CompanyId=".$_REQUEST['ComId']." and EmpStatus='D' AND '".date("Y-m-d")."'<='".$DatAcc."'");  
 }
 else{ $num=0; }
 /********************************************************/
 
 $num=mysqli_num_rows($run_qry);
 
 if($num>0)
 {
 
  $info=mysqli_fetch_array($run_qry);
  $emppass1=decrypt($info['EmpPass']); 
  if($emppass==$emppass1)
  {
  
   $apprsel=mysqli_query($con2,"select * from hrm_employee_reporting where AppraiserId=".$info['EmployeeID']);
   if(mysqli_num_rows($apprsel)>0){ $_SESSION['EmpRole']='A'; }else{ $_SESSION['EmpRole']='E'; }
 
   echo json_encode(array("Code" => "300", "login" => 'true', "EmployeeID" => $info['EmployeeID'], "EmpCode" => $info['EmpCode'], "EmpName" => $info['Fname'].' '.$info['Sname'].' '.$info['Lname'], "ComId" => $info['CompanyId'], "YearId" => $_REQUEST['yearid'], "Role" => $_SESSION['EmpRole'], "Status" => $info['EmpStatus'], "Msg" => 'Login successfully') ); 
  }
  else
  { 
   echo json_encode(array("Code" => "100", "login" => 'false', "Msg" => 'Login failed') );   
  }
  
 } //if($num>0)
 else{ echo json_encode(array("Code" => "100", "login" => 'false', "Msg" => 'Something went wrong, Pls check login-id & password') ); }
 
/* -------------------------------------------------------- */
/* -------------------------------------------------------- */ 
  
}


//Last
else
{
 echo json_encode(array("Code" => "100", "msg" => "Invalid value!") );
}


?>