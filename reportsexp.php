<?php
session_start();
include 'config.php';

if($_REQUEST['act']=='resultexp')
{
  
 
 $xls_filename = 'ClaimReports_'.$_REQUEST['f'].'_'.$_REQUEST['t'].'.xls';
 header("Content-Type: application/xls");
 header("Content-Disposition: attachment; filename=$xls_filename");
 header("Pragma: no-cache"); header("Expires: 0"); $sep = "\t"; 
 echo "tSn\tClaim ID\tClaim Type\tApplied By\tUpload Date\tBill Date\tFilled Date\tClaimed\tVerified\tApproved\tPaid\tClaim Status";
 print("\n");
 
 if(isset($_REQUEST['u']) && $_REQUEST['u']!='' && $_REQUEST['u']!='ALL')
 { $ucond="e.CrBy=".$_REQUEST['u'];}else{ $ucond="1=1"; }
 if(isset($_REQUEST['ct']) && $_REQUEST['ct']!='' && $_REQUEST['ct']!='ALL')
 { $ctcond="e.ClaimId='".$_REQUEST['ct']."'"; }else{ $ctcond="1=1"; }
 if(isset($_REQUEST['cs']) && $_REQUEST['cs']!='' && $_REQUEST['cs']!='ALL')
 {
  if($_REQUEST['cs']=='Submitted'){ $cscond="(e.ClaimStatus='Submitted' OR e.ClaimStatus='Filled' OR e.ClaimStatus='Verified' OR e.ClaimStatus='Approved' OR e.ClaimStatus='Financed')"; }
  elseif($_REQUEST['cs']=='Filled'){ $cscond="(e.ClaimStatus='Filled' OR e.ClaimStatus='Verified' OR e.ClaimStatus='Approved' OR e.ClaimStatus='Financed')";	}
  elseif($_REQUEST['cs']=='Verified'){ $cscond="(e.ClaimStatus='Verified' OR e.ClaimStatus='Approved' OR e.ClaimStatus='Financed')"; }
  elseif($_REQUEST['cs']=='Approved'){ $cscond="(e.ClaimStatus='Approved' OR e.ClaimStatus='Financed')"; }
  elseif($_REQUEST['cs']=='Financed'){ $cscond="e.ClaimStatus='Financed'"; }	
 }else{ $cscond="1=1"; }
 
 if(isset($_REQUEST['f']) && isset($_REQUEST['t']))
 { $f=$_REQUEST['f']!='' ? date("Y-m-d",strtotime($_REQUEST['f'])) : '2020-09-01';
   $t=$_REQUEST['t']!='' ? date("Y-m-d",strtotime($_REQUEST['t'])) : date("Y-m-d");
   
   
   if($_REQUEST['frf']=='B'){ $dtcond="e.BillDate between '".$f."' and '".$t."'"; }
   else if($_REQUEST['frf']=='U'){ $dtcond="e.CrDate between '".$f."' and '".$t."'"; }
   else if($_REQUEST['frf']=='F'){ $dtcond="e.FilledDate between '".$f."' and '".$t."' and CrBy!=FilledBy"; }
   else{ $dtcond="e.BillDate between '".$f."' and '".$t."'"; }
   
   //$dtcond="e.BillDate between '".$f."' and '".$t."'";
 }else{ $dtcond="1=1"; }
 
 
 $q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and e.ClaimYearId='".$_SESSION['FYearId']."' and (c.ClaimId=e.ClaimId or e.ClaimId=0) and ".$ucond." and ".$ctcond." and e.ClaimStatus!='Deactivate' and ".$cscond." and ".$dtcond." group by e.ExpId order by e.BillDate ASC";	$seleq=mysql_query($q);
 //echo $q; die;
 
  $i=1;
  while($exp=mysql_fetch_assoc($seleq))
  {
 
  $schema_insert = "";
  $schema_insert .= $i.$sep;	
  $schema_insert .= $exp['ExpId'].$sep;
  $schema_insert .= $exp['ClaimName'].$sep;
  $schema_insert .= $exp['Fname'].' '.$exp['Sname'].' '.$exp['Lname'].$sep;
  $schema_insert .= $exp['CrDate'].$sep;
  $schema_insert .= $exp['BillDate'].$sep;
  $schema_insert .= $exp['FilledDate'].$sep;
  
  $ff=''; if($exp['FilledTAmt']!=0 && $exp['FilledBy']>0 && $exp['FilledDate']!='0000-00-00'){ $ff=$exp['FilledTAmt']; }
  $schema_insert .= $ff.$sep;
  $gg=''; if($exp['VerifyTAmt']!=0 && $exp['VerifyTAmt']>0 && $exp['VerifyTAmt']!='0000-00-00'){ $gg=$exp['VerifyTAmt']; }
  $schema_insert .= $gg.$sep;
  $hh=''; if($exp['ApprTAmt']!=0 && $exp['ApprTAmt']>0 && $exp['ApprTAmt']!='0000-00-00'){ $hh=$exp['ApprTAmt']; }
  $schema_insert .= $hh.$sep;
  $ii=''; if($exp['FinancedTAmt']!=0 && $exp['FinancedTAmt']>0 && $exp['FinancedTAmt']!='0000-00-00'){ $ii=$exp['FinancedTAmt']; }
  $schema_insert .= $ii.$sep;
  
  if($exp['ClaimStatus']=='Financed'){ $sts='Paid'; }else{ $sts=$exp['ClaimStatus']; }
  $schema_insert .= $sts.$sep;
  
   
  $schema_insert = str_replace($sep."$", "", $schema_insert);
  $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
  $schema_insert .= "\t";
  print(trim($schema_insert)); print "\n"; 
  
  $i++;
  } //while
 
 
 

} //if($_REQUEST['act']=='resultexp')

?>
