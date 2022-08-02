<?php include 'config.php'; 

if($_REQUEST['action']=='exportdetails')
{
 if($_REQUEST['v']=='P')
 {
  
$xls_filename = 'ClaimDetails_Pending_Verify.xls';
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$xls_filename");
header("Pragma: no-cache"); header("Expires: 0"); $sep = "\t"; 
echo "Sn\tEmpCode\tName\tMonth\tTotal Claims\tCliam Amount";
print("\n");

if($_REQUEST['m']=='' || $_REQUEST['m']==0){ $cond='1=1'; }else{ $cond='ClaimMonth='.$_REQUEST['m']; }
$sql = mysql_query("SELECT `ClaimMonth`,`CrBy` FROM `y".$_REQUEST['y']."_expenseclaims` WHERE `ClaimAtStep`=3 AND FilledOkay=1 and ClaimYearId=".$_REQUEST['y']." and ".$cond." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' group by ClaimMonth,CrBy order by CrBy asc, ClaimMonth asc"); 

$no=1;
while($mlist=mysql_fetch_array($sql))
{

 $u=mysql_query("SELECT Fname,Sname,Lname,EmpCode FROM `hrm_employee` where EmployeeID=".$mlist['CrBy'],$con2); 
 $un=mysql_fetch_assoc($u);
					
 $chkM=mysql_query("select Status,Month,PostDate,DocateNo,Agency,RecevingDate,VerifDate,DocRmk from y".$_REQUEST['y']."_monthexpensefinal where EmployeeID='".$mlist['CrBy']."' and Month='".$mlist['ClaimMonth']."' and YearId='".$_REQUEST['y']."'"); $chkM=mysql_fetch_assoc($chkM);
  if($chkM['Status']=='Closed')
  {
  
   $e=mysql_query("SELECT * FROM `y".$_REQUEST['y']."_expenseclaims` WHERE  `CrBy`='".$mlist['CrBy']."' and `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_REQUEST['y']."' and `ClaimAtStep`=3 and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' order by ExpId asc");
   $enum=mysql_num_rows($e);
   if($enum>0)
   {

    $schema_insert = "";
    $schema_insert .= $no.$sep;	
	$schema_insert .= $un['EmpCode'].$sep;	
	$schema_insert .= $un['Fname'].' '.$un['Sname'].' '.$un['Lname'].$sep;
	$schema_insert .= date('F', mktime(0,0,0,$mlist['ClaimMonth'], 1, date('Y'))).$sep;	
	
	$tv=mysql_query("SELECT * FROM `y".$_REQUEST['y']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_REQUEST['y']."' and `CrBy`='".$mlist['CrBy']."' and `ClaimAtStep`=3 and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'"); $tv=mysql_num_rows($tv);
	$schema_insert .= $tv.$sep;	
	
	$totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_REQUEST['y']."_expenseclaims` WHERE `ClaimYearId`='".$_REQUEST['y']."' and `CrBy`='".$mlist['CrBy']."' and ClaimMonth='".$mlist['ClaimMonth']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0"); $clm=mysql_fetch_assoc($totpaid);
	$schema_insert .= $clm['paid'].$sep;	
			  
    $schema_insert = str_replace($sep."$", "", $schema_insert);
    $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
    $schema_insert .= "\t";
    print(trim($schema_insert)); print "\n"; 
    $no++;
   
   } //if($enum>0)
  } //if($chkM['Status']=='Closed')
}
  
 }
 elseif($_REQUEST['v']=='A')
 {
 
$xls_filename = 'ClaimDetails_Approved_Verify.xls';
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$xls_filename");
header("Pragma: no-cache"); header("Expires: 0"); $sep = "\t"; 
echo "Sn\tEmpCode\tName\tMonth\tTotal Claim Amount\tTotal Verified Amount\tTotal Claim Action";
print("\n");

$sql = mysql_query("SELECT `ClaimMonth`,`CrBy` FROM `y".$_REQUEST['y']."_expenseclaims` WHERE `VerifyBy`='".$_REQUEST['e']."' and `ClaimAtStep`>=4 and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' group by ClaimMonth,CrBy order by CrBy asc, ClaimMonth asc"); $no=1;
while($mlist=mysql_fetch_array($sql))
{
 
  $u=mysql_query("SELECT Fname,Sname,Lname,EmpCode FROM `hrm_employee` where EmployeeID=".$mlist['CrBy'],$con2); 
  $un=mysql_fetch_assoc($u);
					
  $chkM=mysql_query("select Status,Month,PostDate,DocateNo,Agency,RecevingDate,VerifDate,DocRmk from y".$_REQUEST['y']."_monthexpensefinal where EmployeeID='".$mlist['CrBy']."' and Month='".$mlist['ClaimMonth']."' and YearId='".$_REQUEST['y']."'"); $chkM=mysql_fetch_assoc($chkM);
   if($chkM['Status']=='Closed')
   {					

	$e=mysql_query("SELECT * FROM `y".$_REQUEST['y']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_REQUEST['y']."' and `ClaimAtStep`>=4 and `CrBy`='".$mlist['CrBy']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' order by ExpId asc"); $enum=mysql_num_rows($e);
	if($enum>0)
	{

      $schema_insert = "";
      $schema_insert .= $no.$sep;
	  $schema_insert .= $un['EmpCode'].$sep;	
	  $schema_insert .= $un['Fname'].' '.$un['Sname'].' '.$un['Lname'].$sep;
	  $schema_insert .= date('F', mktime(0,0,0,$mlist['ClaimMonth'], 1, date('Y'))).$sep;	
		
	  $totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_REQUEST['y']."_expenseclaims` WHERE `ClaimYearId`='".$_REQUEST['y']."' and `CrBy`='".$mlist['CrBy']."' and ClaimMonth='".$mlist['ClaimMonth']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0");$clm=mysql_fetch_assoc($totpaid);	
	  $schema_insert .= $clm['paid'].$sep;
	  
	  $totpaid=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_REQUEST['y']."_expenseclaims` WHERE `ClaimYearId`='".$_REQUEST['y']."' and `CrBy`='".$mlist['CrBy']."' and ClaimMonth='".$mlist['ClaimMonth']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and VerifyBy>0"); $clm=mysql_fetch_assoc($totpaid);	
	  $schema_insert .= $clm['paid'].$sep;
	  
	  $tv=mysql_query("SELECT * FROM `y".$_REQUEST['y']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_REQUEST['y']."' and `CrBy`='".$mlist['CrBy']."' and `ClaimAtStep`>=4 and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'"); $tv=mysql_num_rows($tv);
	  $schema_insert .= $tv.$sep;
	 
	  		  
      $schema_insert = str_replace($sep."$", "", $schema_insert);
      $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
      $schema_insert .= "\t";
      print(trim($schema_insert)); print "\n"; 
      $no++;
	  
	} //if($enum>0)
   } //if($chkM['Status']=='Closed')  	  
  } 

 }
}
?>