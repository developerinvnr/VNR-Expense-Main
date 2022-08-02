<?php include 'config.php'; 

function getClaimType($cid){
	include "config.php";
	$c=mysql_query("SELECT ClaimName FROM `claimtype` where ClaimId=".$cid);
	$ct=mysql_fetch_assoc($c);
	return $ct['ClaimName'];
}
function getUser($u){
	include "config.php";
	$u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$u, $con2);
	$un=mysql_fetch_assoc($u);
	return $un['Fname'].' '.$un['Sname'].' '.$un['Lname'];
}
function getCode($u){
	include "config.php";
	$uc=mysql_query("SELECT EmpCode FROM `hrm_employee` where EmployeeID=".$u, $con2);
	$uc=mysql_fetch_assoc($uc);
	return $uc['EmpCode'];
}


if($_REQUEST['act']=='exportPaydetails')
{
 if($_REQUEST['n']==0)
 {
  
  $xls_filename = 'PaymentStatus_Pending.xls';
  header("Content-Type: application/xls");
  header("Content-Disposition: attachment; filename=$xls_filename");
  header("Pragma: no-cache"); header("Expires: 0"); $sep = "\t"; 
  echo "Sn\tEmpCode\tName\tMonth\tTotal Claims\tLate_For_Submission\tSubmit Date\tVerify_Day\tVerify Date\tApproved_Day\tApproved Date\tFinanced_Day\tFinanced Date\tPayment Amount\tAdvance Amount\tPaid Amount\tDiff. Amount\tPayment Option\tPayment Date\tAny Remark";
  print("\n");
  
  $Day=date("t",strtotime(date("Y-".$_REQUEST['v']."-d")));
  $MDay=date("Y-m-d",strtotime(date("Y-".$_REQUEST['v']."-".$Day)));

  if($_REQUEST['v']=='' || $_REQUEST['v']==0){ $cond='1=1'; }else{ $cond='Month='.$_REQUEST['v']; }
  $sql = mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE YearId=".$_SESSION['FYearId']." and `Status`='Closed' and Total_Claim>0 and Finance_Amount>0 and Finance_Date!='0000-00-00' and Fin_AppBy>0 and Fin_AppDate!='0000-00-00' and Fin_AppDate!='' and Fin_AppDate!='1970-01-01' and Fin_PayAmt='' and Fin_PayOption='' and Fin_PayBy=0 and ".$cond." order by Month asc, EmployeeID asc"); 

  $no=1;
  while($mlist=mysql_fetch_array($sql))
  {
    $schema_insert = "";
    $schema_insert .= $no.$sep;	
	$schema_insert .= getCode($mlist['EmployeeID']).$sep;	
	$schema_insert .= getUser($mlist['EmployeeID']).$sep;
	$schema_insert .= date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y'))).$sep;	
	$schema_insert .= $mlist['Total_Claim'].$sep;	
	
	if(date($MDay)<date($mlist['DateOfSubmit'])) 
	{
	 if($mlist['DateOfSubmit']!='0000-00-00' && $mlist['DateOfSubmit']!='1970-01-01'){ $earlier = new DateTime($MDay); $later = new DateTime($mlist['DateOfSubmit']); $nNoOdDay = $later->diff($earlier)->format("%a"); }else{ $nNoOdDay=''; }
	$schema_insert .= "+".$nNoOdDay.$sep;
	}
	elseif(date($MDay)>date($mlist['DateOfSubmit']))
	{
	 if($mlist['DateOfSubmit']!='0000-00-00' && $mlist['DateOfSubmit']!='1970-01-01'){ $earlier = new DateTime($mlist['DateOfSubmit']); $later = new DateTime($MDay); $nNoOdDay = $later->diff($earlier)->format("%a"); }else{ $nNoOdDay=''; }
	$schema_insert .= "-".$nNoOdDay.$sep;
	}
	else { $schema_insert .= "0".$sep; }
	
	$schema_insert .= $mlist['DateOfSubmit'].$sep;
		
	
	if($mlist['DateOfSubmit']!='0000-00-00' && $mlist['DateOfSubmit']!='1970-01-01' && $mlist['Verified_Date']!='0000-00-00' && $mlist['Verified_Date']!='1970-01-01'){ $earlier = new DateTime($mlist['DateOfSubmit']); $later = new DateTime($mlist['Verified_Date']); $noOdDay = $later->diff($earlier)->format("%a"); }else{ $noOdDay=''; }
	$schema_insert .= $noOdDay.$sep;
	
	$schema_insert .= $mlist['Verified_Date'].$sep;	
	
	if($mlist['Verified_Date']!='0000-00-00' && $mlist['Verified_Date']!='1970-01-01' && $mlist['Approved_Date']!='0000-00-00' && $mlist['Approved_Date']!='1970-01-01'){ $earlier = new DateTime($mlist['Verified_Date']); $later = new DateTime($mlist['Approved_Date']); $no2OdDay = $later->diff($earlier)->format("%a"); }else{ $no2OdDay=''; }
	$schema_insert .= $no2OdDay.$sep;
	
	$schema_insert .= $mlist['Approved_Date'].$sep;
	
	if($mlist['Approved_Date']!='0000-00-00' && $mlist['Approved_Date']!='1970-01-01' && $mlist['Finance_Date']!='0000-00-00' && $mlist['Finance_Date']!='1970-01-01'){ $earlier = new DateTime($mlist['Approved_Date']); $later = new DateTime($mlist['Finance_Date']); $no3OdDay = $later->diff($earlier)->format("%a"); }else{ $no3OdDay=''; }
	$schema_insert .= $no3OdDay.$sep;
		
	$schema_insert .= $mlist['Finance_Date'].$sep;
	
	$schema_insert .= $mlist['Finance_Amount'].$sep;	
	$schema_insert .= ''.$sep;	
	$schema_insert .= ''.$sep;	
	$schema_insert .= ''.$sep;	
	$schema_insert .= ''.$sep;	
	$schema_insert .= ''.$sep;	
	$schema_insert .= ''.$sep;
		  
    $schema_insert = str_replace($sep."$", "", $schema_insert);
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= "\t";
    print(trim($schema_insert)); print "\n"; 
    $no++;
   }
  
 }
 elseif($_REQUEST['n']==1)
 {
   
   $xls_filename = 'PaymentStatus_Paid.xls';
   header("Content-Type: application/xls");
   header("Content-Disposition: attachment; filename=$xls_filename");
   header("Pragma: no-cache"); header("Expires: 0"); $sep = "\t"; 
   echo "Sn\tEmpCode\tName\tMonth\tTotal Claims\tLate_For_Submission\tSubmit Date\tVerify_Day\tVerify Date\tApproved_Day\tApproved Date\tFinanced_Day\tFinanced Date\tPayment Amount\tAdvance Amount\tPaid Amount\tDiff. Amount\tPayment Option\tPaid_Day\tPayment Date\tAny Remark";
   print("\n");
   
   $Day=date("t",strtotime(date("Y-".$_REQUEST['v']."-d")));
   $MDay=date("Y-m-d",strtotime(date("Y-".$_REQUEST['v']."-".$Day)));
   
   if($_REQUEST['v']=='' || $_REQUEST['v']==0){ $cond='1=1'; }else{ $cond='Month='.$_REQUEST['v']; } 
   $sql = mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE YearId=".$_SESSION['FYearId']." and `Status`='Closed' and Total_Claim>0 and Finance_Amount>0 and Finance_Date!='0000-00-00' and Fin_AppBy>0 and Fin_AppDate!='0000-00-00' and Fin_AppDate!='' and Fin_AppDate!='1970-01-01' and Fin_PayAmt!='' and Fin_PayOption!='' and Fin_PayBy>0 and ".$cond." order by Month asc, EmployeeID asc"); 

   $no=1;
   while($mlist=mysql_fetch_array($sql))
   {
    $schema_insert = "";
    $schema_insert .= $no.$sep;	
	$schema_insert .= getCode($mlist['EmployeeID']).$sep;	
	$schema_insert .= getUser($mlist['EmployeeID']).$sep;
	$schema_insert .= date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y'))).$sep;	
	$schema_insert .= $mlist['Total_Claim'].$sep;
	
	if(date($MDay)<date($mlist['DateOfSubmit'])) 
	{
	 if($mlist['DateOfSubmit']!='0000-00-00' && $mlist['DateOfSubmit']!='1970-01-01'){ $earlier = new DateTime($MDay); $later = new DateTime($mlist['DateOfSubmit']); $nNoOdDay = $later->diff($earlier)->format("%a"); }else{ $nNoOdDay=''; }
	$schema_insert .= "+".$nNoOdDay.$sep;
	}
	elseif(date($MDay)>date($mlist['DateOfSubmit']))
	{
	 if($mlist['DateOfSubmit']!='0000-00-00' && $mlist['DateOfSubmit']!='1970-01-01'){ $earlier = new DateTime($mlist['DateOfSubmit']); $later = new DateTime($MDay); $nNoOdDay = $later->diff($earlier)->format("%a"); }else{ $nNoOdDay=''; }
	$schema_insert .= "-".$nNoOdDay.$sep;
	}
	else { $schema_insert .= "0".$sep; }
	
	 
	$schema_insert .= $mlist['DateOfSubmit'].$sep;
		
	
	if($mlist['DateOfSubmit']!='0000-00-00' && $mlist['DateOfSubmit']!='1970-01-01' && $mlist['Verified_Date']!='0000-00-00' && $mlist['Verified_Date']!='1970-01-01'){ $earlier = new DateTime($mlist['DateOfSubmit']); $later = new DateTime($mlist['Verified_Date']); $noOdDay = $later->diff($earlier)->format("%a"); }else{ $noOdDay=''; }
	$schema_insert .= $noOdDay.$sep;
	
	$schema_insert .= $mlist['Verified_Date'].$sep;	
	
	if($mlist['Verified_Date']!='0000-00-00' && $mlist['Verified_Date']!='1970-01-01' && $mlist['Approved_Date']!='0000-00-00' && $mlist['Approved_Date']!='1970-01-01'){ $earlier = new DateTime($mlist['Verified_Date']); $later = new DateTime($mlist['Approved_Date']); $no2OdDay = $later->diff($earlier)->format("%a"); }else{ $no2OdDay=''; }
	$schema_insert .= $no2OdDay.$sep;
	
	$schema_insert .= $mlist['Approved_Date'].$sep;
	
	if($mlist['Approved_Date']!='0000-00-00' && $mlist['Approved_Date']!='1970-01-01' && $mlist['Finance_Date']!='0000-00-00' && $mlist['Finance_Date']!='1970-01-01'){ $earlier = new DateTime($mlist['Approved_Date']); $later = new DateTime($mlist['Finance_Date']); $no3OdDay = $later->diff($earlier)->format("%a"); }else{ $no3OdDay=''; }
	$schema_insert .= $no3OdDay.$sep;
		
	$schema_insert .= $mlist['Finance_Date'].$sep;
	
	$schema_insert .= $mlist['Finance_Amount'].$sep;
		
	$schema_insert .= $mlist['Fin_AdvancePay'].$sep;	
	$schema_insert .= $mlist['Fin_PayAmt'].$sep;	
	$schema_insert .= $mlist['Fin_AdvancePay']+$mlist['Fin_PayAmt'].$sep;	
	$schema_insert .= $mlist['Fin_PayOption'].$sep;	
	
	if($mlist['Finance_Date']!='0000-00-00' && $mlist['Finance_Date']!='1970-01-01' && $mlist['Fin_PayDate']!='0000-00-00' && $mlist['Fin_PayDate']!='1970-01-01'){ $earlier = new DateTime($mlist['Finance_Date']); $later = new DateTime($mlist['Fin_PayDate']); $no4OdDay = $later->diff($earlier)->format("%a"); }else{ $no4OdDay=''; }
	$schema_insert .= $no4OdDay.$sep;
	
	$schema_insert .= $mlist['Fin_PayDate'].$sep;	
	$schema_insert .= $mlist['Fin_PayRemark'].$sep;
		  
    $schema_insert = str_replace($sep."$", "", $schema_insert);
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= "\t";
    print(trim($schema_insert)); print "\n"; 
    $no++;
   }
  
 }
}





elseif($_REQUEST['act']=='Stageexportdetails')
{
 
  
  $xls_filename = 'ClaimStages_reports.xls';
  header("Content-Type: application/xls");
  header("Content-Disposition: attachment; filename=$xls_filename");
  header("Pragma: no-cache"); header("Expires: 0"); $sep = "\t"; 
  
  if($_REQUEST['n']==1)
  {
   echo "Sn\tEmpCode\tName\tMonth\tLate_For_Submission\tUpload Date\tNoOfDay\tFilled Date\tNoOfDay\tSubmit Date\tNoOfDay\tVerify Date\tNoOfDay\tApproved Date\tNoOfDay\tFinance Date\tNoOfDay\tPaid Date";
  }
  elseif($_REQUEST['n']==2)
  {
   echo "Sn\tEmpCode\tName\tMonth\tSubmit Date\tNoOfDay\tVerify Date\tNoOfDay\tApproved Date\tNoOfDay\tFinance Date\tNoOfDay\tPaid Date";
  }
  elseif($_REQUEST['n']==3)
  {
   echo "Sn\tEmpCode\tName\tMonth\tLate_For_Submission\tUpload Date\tNoOfDay\tFilled Date\tNoOfDay\tSubmit Date\tNoOfDay\tApproved Date\tNoOfDay\tPaid Date\tNoOfDay\tVerify Date";
  }
  elseif($_REQUEST['n']==4)
  {
   echo "Sn\tEmpCode\tName\tMonth\tSubmit Date\tNoOfDay\tApproved Date\tNoOfDay\tPaid Date\tNoOfDay\tVerify Date";
  }
  
  print("\n");
  
  $Day=date("t",strtotime(date("Y-".$_REQUEST['v']."-d")));
  $MDay=date("Y-m-d",strtotime(date("Y-".$_REQUEST['v']."-".$Day)));
  
  if($_REQUEST['n']==1 OR $_REQUEST['n']==3)
  {
   $sql = mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and ClaimMonth=".$_REQUEST['v']." order by CrBy asc, CrDate asc"); 
  }
  elseif($_REQUEST['n']==2 OR $_REQUEST['n']==4)
  {
   $sql = mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE Status='Closed' and Month=".$_REQUEST['v']." order by EmployeeID asc, DateOfSubmit asc"); 
  }
  
  $no=1;
  while($mlist=mysql_fetch_array($sql))
  {
    $schema_insert = "";
    $schema_insert .= $no.$sep;	

/******************************* 111111111111111111111 **********************************/
/******************************* 111111111111111111111 **********************************/		
  if($_REQUEST['n']==1)
  {	
	$schema_insert .= getCode($mlist['CrBy']).$sep;	
	$schema_insert .= getUser($mlist['CrBy']).$sep;
	$schema_insert .= date('F', mktime(0,0,0,$mlist['ClaimMonth'], 1, date('Y'))).$sep;	
	//$schema_insert .= $mlist['ClaimId'].$sep;	
	
	if(date($MDay)<date($mlist['CrDate'])) 
	{
	 if($mlist['CrDate']!='0000-00-00' && $mlist['CrDate']!='1970-01-01'){ $earlier = new DateTime($MDay); $later = new DateTime($mlist['CrDate']); $nNoOdDay = $later->diff($earlier)->format("%a"); }else{ $nNoOdDay=''; }
	$schema_insert .= "+".$nNoOdDay.$sep;
	}
	elseif(date($MDay)>date($mlist['CrDate']))
	{
	 if($mlist['CrDate']!='0000-00-00' && $mlist['CrDate']!='1970-01-01'){ $earlier = new DateTime($mlist['CrDate']); $later = new DateTime($MDay); $nNoOdDay = $later->diff($earlier)->format("%a"); }else{ $nNoOdDay=''; }
	$schema_insert .= "-".$nNoOdDay.$sep;
	}
	else { $schema_insert .= "0".$sep; }
	
	$schema_insert .= $mlist['CrDate'].$sep;
	
	if($mlist['CrDate']!='0000-00-00' && $mlist['CrDate']!='1970-01-01' && $mlist['FilledDate']!='0000-00-00' && $mlist['FilledDate']!='1970-01-01'){ $earlier = new DateTime($mlist['CrDate']); $later = new DateTime($mlist['FilledDate']);
                              $noOdDay = $later->diff($earlier)->format("%a"); }else{ $noOdDay=''; }
	$schema_insert .= $noOdDay.$sep;
	$schema_insert .= $mlist['FilledDate'].$sep;
	
	$sql2=mysql_query("select DateOfSubmit, Fin_PayDate from `y".$_SESSION['FYearId']."_monthexpensefinal` where EmployeeID=".$mlist['CrBy']." and Month=".$mlist['ClaimMonth'].""); $mlist2=mysql_fetch_array($sql2);
	
	if($mlist['FilledDate']!='0000-00-00' && $mlist['FilledDate']!='1970-01-01' && $mlist2['DateOfSubmit']!='0000-00-00' && $mlist2['DateOfSubmit']!='1970-01-01'){ $earlier1 = new DateTime($mlist['FilledDate']); $later1 = new DateTime($mlist2['DateOfSubmit']); $no1OdDay = $later1->diff($earlier1)->format("%a"); }else{ $no1OdDay=''; }
	$schema_insert .= $no1OdDay.$sep;
	$schema_insert .= $mlist2['DateOfSubmit'].$sep;
	
	if($mlist['VerifyDate']>=$mlist2['DateOfSubmit'])
	{
	if($mlist2['DateOfSubmit']!='0000-00-00' && $mlist2['DateOfSubmit']!='1970-01-01' && $mlist['VerifyDate']!='0000-00-00' && $mlist['VerifyDate']!='1970-01-01'){ $earlier2 = new DateTime($mlist2['DateOfSubmit']); $later2 = new DateTime($mlist['VerifyDate']); $no2OdDay = $later2->diff($earlier2)->format("%a"); }else{ $no2OdDay=''; }
	$schema_insert .= $no2OdDay.$sep;
	$schema_insert .= $mlist['VerifyDate'].$sep;
	}else{ $schema_insert .= ' '.$sep; $schema_insert .= ' '.$sep; }
	
	
	if($mlist['ApprDate']>=$mlist['VerifyDate'])
	{
	if($mlist['VerifyDate']!='0000-00-00' && $mlist['VerifyDate']!='1970-01-01' && $mlist['ApprDate']!='0000-00-00' && $mlist['ApprDate']!='1970-01-01'){ $earlier3 = new DateTime($mlist['VerifyDate']); $later3 = new DateTime($mlist['ApprDate']); $no3OdDay = $later3->diff($earlier3)->format("%a"); }else{ $no3OdDay=''; }
	$schema_insert .= $no3OdDay.$sep;
	$schema_insert .= $mlist['ApprDate'].$sep;
	}else { $schema_insert .= ' '.$sep; $schema_insert .= ' '.$sep; }
	
	
	if($mlist['FinancedDate']>=$mlist['ApprDate'])
	{
	if($mlist['ApprDate']!='0000-00-00' && $mlist['ApprDate']!='1970-01-01' && $mlist['FinancedDate']!='0000-00-00' && $mlist['FinancedDate']!='1970-01-01'){ $earlier4 = new DateTime($mlist['ApprDate']); $later4 = new DateTime($mlist['FinancedDate']); $no4OdDay = $later4->diff($earlier4)->format("%a"); }else{ $no4OdDay=''; }
	$schema_insert .= $no4OdDay.$sep;
	$schema_insert .= $mlist['FinancedDate'].$sep;
	}else { $schema_insert .= ' '.$sep; $schema_insert .= ' '.$sep; }
	
	
	if($mlist2['Fin_PayDate']>=$mlist['FinancedDate'])
	{
	if($mlist['FinancedDate']!='0000-00-00' && $mlist['FinancedDate']!='1970-01-01' && $mlist2['Fin_PayDate']!='0000-00-00' && $mlist2['Fin_PayDate']!='1970-01-01'){ $earlier5 = new DateTime($mlist['FinancedDate']); $later5 = new DateTime($mlist2['Fin_PayDate']); $no5OdDay = $later5->diff($earlier5)->format("%a"); }else{ $no5OdDay=''; }
	$schema_insert .= $no5OdDay.$sep;
	$schema_insert .= $mlist2['Fin_PayDate'].$sep;		
	}else { $schema_insert .= ' '.$sep; $schema_insert .= ' '.$sep; }
  }
  
/******************************* 222222222222222222222 **********************************/
/******************************* 222222222222222222222 **********************************/	  
  elseif($_REQUEST['n']==2)
  {
  
    $schema_insert .= getCode($mlist['EmployeeID']).$sep;	
	$schema_insert .= getUser($mlist['EmployeeID']).$sep;
	$schema_insert .= date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y'))).$sep;	
	//$schema_insert .= $mlist['ClaimId'].$sep;	
	  
	$schema_insert .= $mlist['DateOfSubmit'].$sep;
	
	if($mlist['Verified_Date']>=$mlist['DateOfSubmit'])
	{
	if($mlist['DateOfSubmit']!='0000-00-00' && $mlist['DateOfSubmit']!='1970-01-01' && $mlist['Verified_Date']!='0000-00-00' && $mlist['Verified_Date']!='1970-01-01'){ $earlier2 = new DateTime($mlist['DateOfSubmit']); $later2 = new DateTime($mlist['Verified_Date']); $no2OdDay = $later2->diff($earlier2)->format("%a"); }else{ $no2OdDay=''; }
	$schema_insert .= $no2OdDay.$sep;
	$schema_insert .= $mlist['Verified_Date'].$sep;
	}
	else { $schema_insert .= ' '.$sep; $schema_insert .= ' '.$sep; }
	
	if($mlist['Approved_Date']>=$mlist['Verified_Date'])
	{
	if($mlist['Verified_Date']!='0000-00-00' && $mlist['Verified_Date']!='1970-01-01' && $mlist['Approved_Date']!='0000-00-00' && $mlist['Approved_Date']!='1970-01-01'){ $earlier3 = new DateTime($mlist['Verified_Date']); $later3 = new DateTime($mlist['Approved_Date']); $no3OdDay = $later3->diff($earlier3)->format("%a"); }else{ $no3OdDay=''; }
	$schema_insert .= $no3OdDay.$sep;
	$schema_insert .= $mlist['Approved_Date'].$sep;
	}
	else { $schema_insert .= ' '.$sep; $schema_insert .= ' '.$sep; }
	
	if($mlist['Finance_Date']>=$mlist['Approved_Date'])
	{
	if($mlist['Approved_Date']!='0000-00-00' && $mlist['Approved_Date']!='1970-01-01' && $mlist['Finance_Date']!='0000-00-00' && $mlist['Finance_Date']!='1970-01-01'){ $earlier4 = new DateTime($mlist['Approved_Date']); $later4 = new DateTime($mlist['Finance_Date']); $no4OdDay = $later4->diff($earlier4)->format("%a"); }else{ $no4OdDay=''; }
	$schema_insert .= $no4OdDay.$sep;
	$schema_insert .= $mlist['Finance_Date'].$sep;
	}
	else { $schema_insert .= ' '.$sep; $schema_insert .= ' '.$sep; }
	
	if($mlist['Fin_PayDate']>=$mlist['Finance_Date'])
	{
	if($mlist['Finance_Date']!='0000-00-00' && $mlist['Finance_Date']!='1970-01-01' && $mlist['Fin_PayDate']!='0000-00-00' && $mlist['Fin_PayDate']!='1970-01-01'){ $earlier5 = new DateTime($mlist['Finance_Date']); $later5 = new DateTime($mlist['Fin_PayDate']); $no5OdDay = $later5->diff($earlier5)->format("%a"); }else{ $no5OdDay=''; }
	$schema_insert .= $no5OdDay.$sep;
	$schema_insert .= $mlist['Fin_PayDate'].$sep;	
    }
	else { $schema_insert .= ' '.$sep; $schema_insert .= ' '.$sep; }
   
   
  }
  
/******************************* 333333333333333333333 **********************************/
/******************************* 333333333333333333333 **********************************/	
  elseif($_REQUEST['n']==3)
  {	
	$schema_insert .= getCode($mlist['CrBy']).$sep;	
	$schema_insert .= getUser($mlist['CrBy']).$sep;
	$schema_insert .= date('F', mktime(0,0,0,$mlist['ClaimMonth'], 1, date('Y'))).$sep;	
	//$schema_insert .= $mlist['ClaimId'].$sep;	
	
	if(date($MDay)<date($mlist['CrDate'])) 
	{
	 if($mlist['CrDate']!='0000-00-00' && $mlist['CrDate']!='1970-01-01'){ $earlier = new DateTime($MDay); $later = new DateTime($mlist['CrDate']); $nNoOdDay = $later->diff($earlier)->format("%a"); }else{ $nNoOdDay=''; }
	$schema_insert .= "+".$nNoOdDay.$sep;
	}
	elseif(date($MDay)>date($mlist['CrDate']))
	{
	 if($mlist['CrDate']!='0000-00-00' && $mlist['CrDate']!='1970-01-01'){ $earlier = new DateTime($mlist['CrDate']); $later = new DateTime($MDay); $nNoOdDay = $later->diff($earlier)->format("%a"); }else{ $nNoOdDay=''; }
	$schema_insert .= "-".$nNoOdDay.$sep;
	}
	else { $schema_insert .= "0".$sep; }
	
	$schema_insert .= $mlist['CrDate'].$sep;
	
	if($mlist['CrDate']!='0000-00-00' && $mlist['CrDate']!='1970-01-01' && $mlist['FilledDate']!='0000-00-00' && $mlist['FilledDate']!='1970-01-01'){ $earlier = new DateTime($mlist['CrDate']); $later = new DateTime($mlist['FilledDate']);
                              $noOdDay = $later->diff($earlier)->format("%a"); }else{ $noOdDay=''; }
	$schema_insert .= $noOdDay.$sep;
	$schema_insert .= $mlist['FilledDate'].$sep;
	
	$sql2=mysql_query("select DateOfSubmit, Fin_PayDate from `y".$_SESSION['FYearId']."_monthexpensefinal` where EmployeeID=".$mlist['CrBy']." and Month=".$mlist['ClaimMonth'].""); $mlist2=mysql_fetch_array($sql2);
	
	if($mlist['FilledDate']!='0000-00-00' && $mlist['FilledDate']!='1970-01-01' && $mlist2['DateOfSubmit']!='0000-00-00' && $mlist2['DateOfSubmit']!='1970-01-01'){ $earlier1 = new DateTime($mlist['FilledDate']); $later1 = new DateTime($mlist2['DateOfSubmit']); $no1OdDay = $later1->diff($earlier1)->format("%a"); }else{ $no1OdDay=''; }
	$schema_insert .= $no1OdDay.$sep;
	$schema_insert .= $mlist2['DateOfSubmit'].$sep;
	
	
	if($mlist['ApprDate']>=$mlist2['DateOfSubmit'])
	{
	if($mlist2['DateOfSubmit']!='0000-00-00' && $mlist2['DateOfSubmit']!='1970-01-01' && $mlist['ApprDate']!='0000-00-00' && $mlist['ApprDate']!='1970-01-01'){ $earlier2 = new DateTime($mlist2['DateOfSubmit']); $later2 = new DateTime($mlist['ApprDate']); $no2OdDay = $later2->diff($earlier2)->format("%a"); }else{ $no2OdDay=''; }
	$schema_insert .= $no2OdDay.$sep;
	$schema_insert .= $mlist['ApprDate'].$sep;
	}else{ $schema_insert .= ' '.$sep; $schema_insert .= ' '.$sep; }
	
	
	if($mlist2['Fin_PayDate']>=$mlist['ApprDate'])
	{
	if($mlist['ApprDate']!='0000-00-00' && $mlist['ApprDate']!='1970-01-01' && $mlist2['Fin_PayDate']!='0000-00-00' && $mlist2['Fin_PayDate']!='1970-01-01'){ $earlier5 = new DateTime($mlist['ApprDate']); $later5 = new DateTime($mlist2['Fin_PayDate']); $no5OdDay = $later5->diff($earlier5)->format("%a"); }else{ $no5OdDay=''; }
	$schema_insert .= $no5OdDay.$sep;
	$schema_insert .= $mlist2['Fin_PayDate'].$sep;		
	}else { $schema_insert .= ' '.$sep; $schema_insert .= ' '.$sep; }
	
	
	if($mlist['VerifyDate']>=$mlist2['Fin_PayDate'])
	{
	if($mlist2['Fin_PayDate']!='0000-00-00' && $mlist2['Fin_PayDate']!='1970-01-01' && $mlist['VerifyDate']!='0000-00-00' && $mlist['VerifyDate']!='1970-01-01'){ $earlier3 = new DateTime($mlist2['Fin_PayDate']); $later3 = new DateTime($mlist['VerifyDate']); $no3OdDay = $later3->diff($earlier3)->format("%a"); }else{ $no3OdDay=''; }
	$schema_insert .= $no3OdDay.$sep;
	$schema_insert .= $mlist['VerifyDate'].$sep;
	}else { $schema_insert .= ' '.$sep; $schema_insert .= ' '.$sep; }
	
  }  

/******************************* 444444444444444444444 **********************************/
/******************************* 444444444444444444444 **********************************/	  
  elseif($_REQUEST['n']==4)
  {
  
    $schema_insert .= getCode($mlist['EmployeeID']).$sep;	
	$schema_insert .= getUser($mlist['EmployeeID']).$sep;
	$schema_insert .= date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y'))).$sep;	
	//$schema_insert .= $mlist['ClaimId'].$sep;	
	
	$schema_insert .= $mlist['DateOfSubmit'].$sep;
	
	if($mlist['Approved_Date']>=$mlist['DateOfSubmit'])
	{
	if($mlist['DateOfSubmit']!='0000-00-00' && $mlist['DateOfSubmit']!='1970-01-01' && $mlist['Approved_Date']!='0000-00-00' && $mlist['Approved_Date']!='1970-01-01'){ $earlier3 = new DateTime($mlist['DateOfSubmit']); $later3 = new DateTime($mlist['Approved_Date']); $no3OdDay = $later3->diff($earlier3)->format("%a"); }else{ $no3OdDay=''; }
	$schema_insert .= $no3OdDay.$sep;
	$schema_insert .= $mlist['Approved_Date'].$sep;
	}
	else { $schema_insert .= ' '.$sep; $schema_insert .= ' '.$sep; }
	
	
	if($mlist['Fin_PayDate']>=$mlist['Approved_Date'])
	{
	if($mlist['Approved_Date']!='0000-00-00' && $mlist['Approved_Date']!='1970-01-01' && $mlist['Fin_PayDate']!='0000-00-00' && $mlist['Fin_PayDate']!='1970-01-01'){ $earlier5 = new DateTime($mlist['Approved_Date']); $later5 = new DateTime($mlist['Fin_PayDate']); $no5OdDay = $later5->diff($earlier5)->format("%a"); }else{ $no5OdDay=''; }
	$schema_insert .= $no5OdDay.$sep;
	$schema_insert .= $mlist['Fin_PayDate'].$sep;	
    }
	else { $schema_insert .= ' '.$sep; $schema_insert .= ' '.$sep; }
	
	
	if($mlist['Verified_Date']>=$mlist['Fin_PayDate'])
	{
	if($mlist['Fin_PayDate']!='0000-00-00' && $mlist['Fin_PayDate']!='1970-01-01' && $mlist['Verified_Date']!='0000-00-00' && $mlist['Verified_Date']!='1970-01-01'){ $earlier2 = new DateTime($mlist['Fin_PayDate']); $later2 = new DateTime($mlist['Verified_Date']); $no2OdDay = $later2->diff($earlier2)->format("%a"); }else{ $no2OdDay=''; }
	$schema_insert .= $no2OdDay.$sep;
	$schema_insert .= $mlist['Verified_Date'].$sep;
	}
	else { $schema_insert .= ' '.$sep; $schema_insert .= ' '.$sep; }
   
  }
  
    	
		  
    $schema_insert = str_replace($sep."$", "", $schema_insert);
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= "\t";
    print(trim($schema_insert)); print "\n"; 
    $no++;
   }
  

}





?>