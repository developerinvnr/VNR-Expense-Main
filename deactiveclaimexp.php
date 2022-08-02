<?php
session_start();
include 'config.php';

if($_REQUEST['act']=='resultexp')
{
  
 $xls_filename = 'Deactivate_Claim_'.$_REQUEST['f'].'_'.$_REQUEST['t'].'.xls';
 header("Content-Type: application/xls");
 header("Content-Disposition: attachment; filename=$xls_filename");
 header("Pragma: no-cache"); header("Expires: 0"); $sep = "\t"; 
 echo "tSn\tClaim ID\tClaim Type\tApplied By\tUpload Date\tBill Date\tClaimed Amt\tClaim Status";
 print("\n");
 
 
  $f=$_REQUEST['f']!='' ? date("Y-m-d",strtotime($_REQUEST['f'])) : '2019-01-01';
  $t=$_REQUEST['t']!='' ? date("Y-m-d",strtotime($_REQUEST['t'])) : date("Y-m-d");
  $seleq=mysql_query("SELECT e.*, c.ClaimName FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c where e.ClaimYearId='".$_SESSION['FYearId']."' and c.ClaimId=e.ClaimId and e.FilledDate between '".$f."' and '".$t."' and e.ClaimStatus='Deactivate' and e.FilledBy=10 and e.FilledTAmt>0 and e.ClaimId!=0 and e.ClaimId!=19 and e.ClaimId!=20 order by e.BillDate ASC");
  $i=1;
  while($exp=mysql_fetch_assoc($seleq))
  {
 
  $schema_insert = "";
  $schema_insert .= $i.$sep;	
  $schema_insert .= $exp['ExpId'].$sep;
  $schema_insert .= $exp['ClaimName'].$sep;
  
  $u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$exp['CrBy'], $con2); $un=mysql_fetch_assoc($u);
  $schema_insert .= $un['Fname'].' '.$un['Sname'].' '.$un['Lname'].$sep;
  
  $schema_insert .= $exp['CrDate'].$sep;
  $schema_insert .= $exp['BillDate'].$sep;
  $schema_insert .= $exp['FilledTAmt'].$sep;
  $schema_insert .= $exp['ClaimStatus'].$sep;
  
  $schema_insert = str_replace($sep."$", "", $schema_insert);
  $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
  $schema_insert .= "\t";
  print(trim($schema_insert)); print "\n"; 
  
  $i++;
  } //while
 
 
 

} //if($_REQUEST['act']=='resultexp')

?>
