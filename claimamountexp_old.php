<?php

session_start();
include 'config.php';

function getClaimType($cid){
	$c=mysql_query("SELECT ClaimName FROM `claimtype` where ClaimId=".$cid);
	$ct=mysql_fetch_assoc($c);
	return strtoupper($ct['ClaimName']);
}
function getUser($u){ 
	$u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$u,$con2);
	$un=mysql_fetch_assoc($u);
	return $un['Fname'].' '.$un['Sname'].' '.$un['Lname'];
}


if($_REQUEST['act']=='resultexp')
{

function moneyFormatIndia($num) {
    $explrestunits = "" ;
    if(strlen($num)>3) {
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i<sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if($i==0) {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash; // writes the final format where $currency is the currency symbol.
}

 if($_REQUEST['uc']=='ALL'){ $xlsN='ALL'; }
 else{ $u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$_REQUEST['uc'],$con2); 
       $un=mysql_fetch_assoc($u); $xlsN=$un['Fname'].'_'.$un['Sname'].'_'.$un['Lname']; }
 if($_REQUEST['ct']=='ALL'){ $xlsT='ALL'; }
 else{ $ct=mysql_query("select ClaimName from claimtype where ClaimId=".$_REQUEST['ct']);
	   $cid=mysql_fetch_assoc($ct); $xlsT=$cid['ClaimName']; } 
	   
 $sqy=mysql_query("select y1,y2 from financialyear where YearId=".$_REQUEST['fy']); $rqy=mysql_fetch_assoc($sqy);
 $y=$rqy['y1'].'-'.$rqy['y2']; 	   
 
 $xls_filename = 'ClaimAmt_'.$y.'_'.$xlsN.'_'.$xlsT.'.xls';
 header("Content-Type: application/xls");
 header("Content-Disposition: attachment; filename=$xls_filename");
 header("Pragma: no-cache"); header("Expires: 0"); $sep = "\t"; 
 echo "Month\t==>";
 
 if($_REQUEST['cs']=='Filled' OR $_REQUEST['cs']=='ALL'){ echo "\tFilled"; } 
 if($_REQUEST['cs']=='Verified' OR $_REQUEST['cs']=='ALL'){ echo "\tVerified"; }
 if($_REQUEST['cs']=='Approved' OR $_REQUEST['cs']=='ALL'){ echo "\tApproved"; }
 if($_REQUEST['cs']=='Financed' OR $_REQUEST['cs']=='ALL'){ echo "\tFinanced"; }
 if($_REQUEST['ct']=='ALL' AND ($_REQUEST['cs']=='Paid' OR $_REQUEST['cs']=='ALL')){ echo "\tPaid"; }
 print("\n");
 
 if($_REQUEST['uc']=='ALL'){ $qryu='1=1'; $qry2u='1=1'; }
 else{ $qryu='CrBy='.$_REQUEST['uc']; $qry2u='EmployeeID='.$_REQUEST['uc']; }
 if($_REQUEST['ct']=='ALL'){ $qryct='1=1'; }else{ $qryct='ClaimId='.$_REQUEST['ct']; }
 
 if($_REQUEST['fy']==2){ $cnt=9; }else{ $cnt=4; }
 for($i=15; $i>=$cnt; $i--){ if($i==15){$j=03;}elseif($i==14){$j=02;}elseif($i==13){$j=01;}else{$j=$i;}
 $FTot=0; $VTot=0; $ATot=0; $FiTot=0; $PTot=0;
 
  $schema_insert = "";
  $schema_insert .= strtoupper(date("F",strtotime(date("Y-".$j."-01")))).$sep;	
  $schema_insert .= ":".$sep;
  
  if($_REQUEST['cs']=='Filled' OR $_REQUEST['cs']=='ALL'){ $stotF=mysql_query("SELECT SUM(FilledTAmt) as FTot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE `ClaimMonth`='".$j."' AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ".$qryct." AND ClaimStatus!='Deactivate' AND FilledBy>0"); $rtotF=mysql_fetch_assoc($stotF); $MonyF=moneyFormatIndia($rtotF['FTot']);
  $schema_insert .= $MonyF.$sep; }
  
  if($_REQUEST['cs']=='Verified' OR $_REQUEST['cs']=='ALL'){ $stotV=mysql_query("SELECT SUM(VerifyTAmt) as VTot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE `ClaimMonth`='".$j."' AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ".$qryct." AND ClaimStatus!='Deactivate' AND VerifyBy>0"); $rtotV=mysql_fetch_assoc($stotV); $MonyV=moneyFormatIndia($rtotV['VTot']); 
  $schema_insert .= $MonyV.$sep; }
  
  if($_REQUEST['cs']=='Verified' OR $_REQUEST['cs']=='ALL'){ $stotA=mysql_query("SELECT SUM(ApprTAmt) as ATot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE `ClaimMonth`='".$j."' AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ".$qryct." AND ClaimStatus!='Deactivate' AND ApprBy>0"); $rtotA=mysql_fetch_assoc($stotA); $MonyA=moneyFormatIndia($rtotA['ATot']); 
  $schema_insert .= $MonyA.$sep; }
  
  if($_REQUEST['cs']=='Financed' OR $_REQUEST['cs']=='ALL'){ $stotFi=mysql_query("SELECT SUM(FinancedTAmt) as FiTot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE `ClaimMonth`='".$j."' AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ".$qryct." AND ClaimStatus!='Deactivate' AND FinancedBy>0"); $rtotFi=mysql_fetch_assoc($stotFi); $MonyFi=moneyFormatIndia($rtotFi['FiTot']); 
  $schema_insert .= $MonyFi.$sep; }
  
  if($_REQUEST['ct']=='ALL' AND ($_REQUEST['cs']=='Paid' OR $_REQUEST['cs']=='ALL')){ $stotP=mysql_query("SELECT sum(Fin_AdvancePay) as sTotA,sum(Fin_PayAmt) as sTotB FROM `y".$_REQUEST['fy']."_monthexpensefinal` WHERE YearId=".$_REQUEST['fy']." AND Month='".$j."' AND ".$qry2u." AND Fin_AppBy>0 AND Fin_AppDate!='0000-00-00' AND Fin_AppDate!='' AND Fin_AppDate!='1970-01-01' AND Fin_PayAmt!='' AND Fin_PayOption!='' AND Fin_PayBy>0"); $rtotP=mysql_fetch_assoc($stotP); $PTot=$rtotP['sTotA']+$rtotP['sTotB']; $MonyP=moneyFormatIndia($PTot);
  $schema_insert .= $MonyP.$sep; }
  
			  
  $schema_insert = str_replace($sep."$", "", $schema_insert);
  $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
  $schema_insert .= "\t";
  print(trim($schema_insert)); print "\n"; 
  
  
  $schema_insert = "";
  $schema_insert .= '=>'.$sep;
  $schema_insert .= 'Claim Type'.$sep;
  if($_REQUEST['cs']=='Filled' OR $_REQUEST['cs']=='ALL'){ $schema_insert .= 'Filled'.$sep; } 
  if($_REQUEST['cs']=='Verified' OR $_REQUEST['cs']=='ALL'){ $schema_insert .= 'Verified'.$sep; }
  if($_REQUEST['cs']=='Approved' OR $_REQUEST['cs']=='ALL'){ $schema_insert .= 'Approved'.$sep; }
  if($_REQUEST['cs']=='Financed' OR $_REQUEST['cs']=='ALL'){ $schema_insert .= 'Financed'.$sep; }
  			  
  $schema_insert = str_replace($sep."$", "", $schema_insert);
  $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
  $schema_insert .= "\t";
  print(trim($schema_insert)); print "\n"; 
  
  if($MonyF>0 OR $MonyV>0 OR $MonyA>0 OR $MonyFi>0)
  {
  
   $c=mysql_query("select * from claimtype where (ClaimStatus='A' OR ClaimStatus='B') order by ClaimName asc");
   while($cid=mysql_fetch_assoc($c))
   {
     $schema_insert = "";
     $schema_insert .= ':'.$sep;
     $schema_insert .= $cid['ClaimName'].$sep;
     if($_REQUEST['cs']=='Filled' OR $_REQUEST['cs']=='ALL'){ $stotF=mysql_query("SELECT SUM(FilledTAmt) as FTot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE `ClaimMonth`='".$j."' AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ClaimId=".$cid['ClaimId']." AND ClaimStatus!='Deactivate' AND FilledBy>0"); $rtotF=mysql_fetch_assoc($stotF); 
	 $FTot=moneyFormatIndia($rtotF['FTot']);
	 $schema_insert .= $FTot.$sep; } 
	
     if($_REQUEST['cs']=='Verified' OR $_REQUEST['cs']=='ALL'){ $stotV=mysql_query("SELECT SUM(VerifyTAmt) as VTot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE `ClaimMonth`='".$j."' AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ClaimId=".$cid['ClaimId']." AND ClaimStatus!='Deactivate' AND VerifyBy>0"); $rtotV=mysql_fetch_assoc($stotV); 
	 $VTot=moneyFormatIndia($rtotV['VTot']);
	 $schema_insert .= $VTot.$sep; }
	
     if($_REQUEST['cs']=='Approved' OR $_REQUEST['cs']=='ALL'){ $stotA=mysql_query("SELECT SUM(ApprTAmt) as ATot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE `ClaimMonth`='".$j."' AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ClaimId=".$cid['ClaimId']." AND ClaimStatus!='Deactivate' AND ApprBy>0"); $rtotA=mysql_fetch_assoc($stotA); 
	 $ATot=moneyFormatIndia($rtotA['ATot']);
	 $schema_insert .= $ATot.$sep; }
	
     if($_REQUEST['cs']=='Financed' OR $_REQUEST['cs']=='ALL'){ $stotFi=mysql_query("SELECT SUM(FinancedTAmt) as FiTot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE `ClaimMonth`='".$j."' AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ClaimId=".$cid['ClaimId']." AND ClaimStatus!='Deactivate' AND FinancedBy>0"); $rtotFi=mysql_fetch_assoc($stotFi);
	 $FiTot=moneyFormatIndia($rtotV['VTot']);
	 $schema_insert .= $FiTot.$sep; }
	
	 $schema_insert = str_replace($sep."$", "", $schema_insert);
     $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
     $schema_insert .= "\t";
     print(trim($schema_insert)); print "\n"; 
  
   }//while
  
  
  $schema_insert = "";
  $schema_insert .= "".$sep;	
			  
  $schema_insert = str_replace($sep."$", "", $schema_insert);
  $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
  $schema_insert .= "\t";
  print(trim($schema_insert)); print "\n";
  
  } //if($MonyF>0 OR $MonyV>0 OR $MonyA>0 OR $MonyFi>0)
 
  
 
 } //for
 
 
 

} //if($_REQUEST['act']=='resultexp')

?>
