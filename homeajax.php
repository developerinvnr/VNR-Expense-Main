<?php
session_start();
include 'config.php';

if($_POST['act']=='expfillok')
{
  $up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET FilledOkay=1,FilOkDenyRemark='".$_POST['remark']."' where ExpId='".$_POST['expid']."'");
 if($up){ echo 'okay'; }


}
elseif($_POST['act']=='expfilldeny')
{
  $up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET FilledOkay=2,FilOkDenyRemark='".$_POST['remark']."' where ExpId='".$_POST['expid']."'");
  if($up){ echo 'okay'; }

	
}
elseif($_POST['act']=='deactivateclaim')
{
  $up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET ClaimStatus='Deactivate' where ExpId='".$_POST['expid']."'");
  if($up){ echo 'deactivated'; }


}
elseif($_POST['act']=='attach')
{
  $up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET AttachTo='".$_POST['attachid']."' where ExpId='".$_POST['expid']."'");
  if($up){ echo 'attached'; }


}
elseif($_POST['act']=='unattach')
{
  $up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET AttachTo='0' where ExpId='".$_POST['expid']."'");
  if($up){ echo 'unattached'; }


}
elseif($_POST['act']=='submitmonthexp')
{
  /*$up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET ClaimStatus='Submitted',ClaimAtStep=2 where CrBy='".$_SESSION['EmployeeID']."' and ClaimMonth='".$_POST['month']."' and ClaimYearId='".$_SESSION['FYearId']."' and ClaimStatus='Saved'");
  if($up){ echo 'submitted'; }*/


}
elseif($_POST['act']=='submitforcheck')
{
  $up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET ClaimAtStep=1 where CrBy='".$_POST['crby']."' and ClaimMonth='".$_POST['month']."' and ClaimYearId='".$_SESSION['FYearId']."' and ClaimStatus='Filled' and FilledBy='".$_SESSION['EmployeeID']."'");
  if($up){ echo 'submitted'; }


}
elseif($_POST['act']=='submitmonthfill')
{

  $closingMonth=(int)$_REQUEST['month'];
  $openingMonth=$closingMonth+1; 
	
  $up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET FilledOkay=1, ClaimAtStep=3, VerifyTAmt=FilledTAmt, ApprTAmt=FilledTAmt, FinancedTAmt=FilledTAmt where CrBy='".$_POST['crby']."' and ClaimMonth='".$closingMonth."' and ClaimYearId='".$_SESSION['FYearId']."' and ClaimStatus='Filled'");
  
  /******** ---- final table update ************/
  $totpaid=mysql_query("SELECT count(*) as TotClaim, SUM(FilledTAmt) as FilledAmt FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_POST['crby']."' and ClaimMonth='".$closingMonth."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0"); 
  $clm=mysql_fetch_assoc($totpaid); 
  if($clm['TotClaim']>0){$TotalClaim=$clm['TotClaim'];}else{$TotalClaim=0;}
  if($clm['FilledAmt']>0){$Filled_Amt=$clm['FilledAmt'];}else{$Filled_Amt=0;}
  mysql_query("UPDATE y".$_SESSION['FYearId']."_monthexpensefinal SET Status='Closed', DateOfSubmit='".date("Y-m-d")."', Total_Claim=".$TotalClaim.", Claim_Amount=".$Filled_Amt." where EmployeeID='".$_POST['crby']."' and Month='".$closingMonth."' and YearId='".$_SESSION['FYearId']."'");
  /******** ---- final table update ************/
	
  $selexp=mysql_query("select ExpId from y".$_SESSION['FYearId']."_expenseclaims where CrBy='".$_POST['crby']."' and ClaimMonth='".$closingMonth."' and ClaimYearId='".$_SESSION['FYearId']."' and ClaimStatus='Filled'");
  while($selexpd=mysql_fetch_assoc($selexp)){ mysql_query("update y".$_SESSION['FYearId']."_expenseclaimsdetails set VerifierEditAmount=Amount where ExpId='".$selexpd['ExpId']."'"); }

  if($up)
  {               
    $isCheckMonthExist=mysql_query("select * from `y".$_SESSION['FYearId']."_monthexpensefinal` where EmployeeID='".$_SESSION['EmployeeID']."' and Month='".$openingMonth."' and YearId='".$_SESSION['FYearId']."' and Status='Open'"); 
	$rowchk=mysql_num_rows($isCheckMonthExist);
    if($rowchk>0){ echo 'submitted'; }
	else{ $insNewMonth=mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_monthexpensefinal`( `EmployeeID`, `Month`, `YearId`, `Status`, `Crdate`) VALUES ('".$_SESSION['EmployeeID']."','".$openingMonth."','".$_SESSION['FYearId']."','Open','".date('Y-m-d')."')"); 
	      if($insNewMonth){ echo 'submitted'; }
        } //else
  
  } //if($up)


}
elseif($_POST['act']=='submittoapprover')
{
   $up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET ClaimAtStep=4, VerifyBy='".$_SESSION['EmployeeID']."' where CrBy='".$_POST['crby']."' and ClaimMonth='".$_POST['month']."' and ClaimYearId='".$_SESSION['FYearId']."' and ClaimStatus='Verified'");
   
   /******** ---- final table update ************/
   $Vpaid=mysql_query("SELECT SUM(VerifyTAmt) as VerifyAmt, MAX(VerifyDate) as VerifyD FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_POST['crby']."' and ClaimMonth='".$_POST['month']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and VerifyBy>0"); 
   $clm2=mysql_fetch_assoc($Vpaid); 
   if($clm2['VerifyAmt']>0){$Verified_Amount=$clm2['VerifyAmt'];}else{$Verified_Amount=0;} 
   if($clm2['VerifyD']!=''){$Verified_Date=$clm2['VerifyD'];}else{$Verified_Date='0000-00-00';}
  
   mysql_query("UPDATE y".$_SESSION['FYearId']."_monthexpensefinal SET Verified_Amount=".$Verified_Amount.", Verified_Date='".$Verified_Date."' where EmployeeID='".$_POST['crby']."' and Month='".$_POST['month']."' and YearId='".$_SESSION['FYearId']."'");
   /******** ---- final table update ************/
   
   $selexp=mysql_query("select ExpId from y".$_SESSION['FYearId']."_expenseclaims where CrBy='".$_POST['crby']."' and ClaimMonth='".$_POST['month']."' and ClaimYearId='".$_SESSION['FYearId']."' and ClaimStatus='Verified'");
   while($selexpd=mysql_fetch_assoc($selexp)){ mysql_query("update y".$_SESSION['FYearId']."_expenseclaimsdetails set ApproverEditAmount=VerifierEditAmount where ExpId='".$selexpd['ExpId']."'"); }
	
    if($up)
	{   $m=$_POST['month'];
	    $srep=mysql_query("select AppraiserId from hrm_employee_reporting where EmployeeID=".$_POST['crby']."",$con2);
        $rrep=mysql_fetch_assoc($srep);
		if($rrep['AppraiserId']>0)
		{
		  $sRn=mysql_query("select EmailId_Vnr from hrm_employee_general where EmployeeID=".$rrep['AppraiserId'],$con2);
		  $rRn=mysql_fetch_assoc($sRn);
		  if($rRn['EmailId_Vnr'])
		  {
		     $sEn=mysql_query("select Fname,Sname,Lname from hrm_employee where EmployeeID=".$_POST['crby'],$con2);
             $rEn=mysql_fetch_assoc($sEn); $Ename=$rEn['Fname'].' '.$rEn['Sname'].' '.$rEn['Lname'];
			 if($m==1){$mnt='January';}elseif($m==2){$mnt='February';}elseif($m==3){$mnt='March';}elseif($m==4){$mnt='April';}elseif($m==5){$mnt='May';}elseif($m==6){$mnt='June';}elseif($m==7){$mnt='July';}elseif($m==8){$mnt='August';}elseif($m==9){$mnt='September';}elseif($m==10){$mnt='October';}elseif($m==11){$mnt='November';}elseif($m==12){$mnt='December';} 
			 
		     $email_to = $rRn['EmailId_Vnr'];
             $email_from='admin@vnrseeds.co.in';
             $email_subject = "Xeasy: Pending for approval (Month - ".$mnt." , Emp - ".$Ename.")";
             $headers = "From: ".$email_from."\r\n"; 
             $semi_randa = md5(time()); 
             $headers .= "MIME-Version: 1.0\r\n";
             $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			 $email_message .="<html><body>Dear Team, <br><br>\r\n\n";
             $email_message .=" Please take necessary action to pending claims (approval level) for your team member ".$Ename.". For details go on xeasy.<br><br>\r\n\n";
			 $email_message .="Regards<br>\r\n";
             $email_message .="Admin (Xeasy)<br>\r\n";
			 $email_message .="</body></html>\n\n";
             $ok = @mail($email_to, $email_subject, $email_message, $headers); 
             
             /**************/
             $email_to2 = 'ajaykumar.dewangan@vnrseeds.in';
             $email_from2='admin@vnrseeds.co.in';
             $email_subject2 = "Xeasy: Pending for approval (Month - ".$mnt." , Emp - ".$Ename.") - Rep".$rRn['EmailId_Vnr'];
             $headers2 = "From: ".$email_from2."\r\n"; 
             $semi_randa2 = md5(time()); 
             $headers2 .= "MIME-Version: 1.0\r\n";
             $headers2 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			 $email_message2 .="<html><body>Dear Team, <br><br>\r\n\n";
             $email_message2 .=" Please take necessary action to pending claims (approval level) for your team member ".$Ename.". For details go on xeasy.<br><br>\r\n\n";
			 $email_message2 .="Regards<br>\r\n";
             $email_message2 .="Admin (Xeasy)<br>\r\n";
			 $email_message2 .="</body></html>\n\n";
             $ok = @mail($email_to2, $email_subject2, $email_message2, $headers2); 
             /**************/
             
		  }
		}
		echo 'submitted';	
	
	} ////if($up)	
   		

}
elseif($_POST['act']=='submittoreturn')
{
  $up=mysql_query("UPDATE y".$_SESSION['FYearId']."_monthexpensefinal SET Status='Open', Verified_Amount=0, Verified_Date='0000-00-00' where EmployeeID='".$_POST['crby']."' and Month='".$_POST['month']."' and YearId='".$_SESSION['FYearId']."'"); 
  if($up){ echo 'Returned'; }
	
	
}
elseif($_POST['act']=='FromVerifierCloseClaimMonth')
{
  $up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET ClaimStatus='Verified', ClaimAtStep=4, VerifyBy='".$_SESSION['EmployeeID']."', `VerifyTAmt`=`FilledTAmt`, `VerifyDate`='".date("Y-m-d")."' where CrBy='".$_POST['crby']."' AND (VerifyBy=0 OR VerifyBy='') AND ClaimMonth='".$_POST['month']."' AND (VerifyDate='0000-00-00' OR VerifyDate='') AND ClaimStatus='Filled' AND ClaimAtStep=3");
  $up=mysql_query("update y".$_SESSION['FYearId']."_expenseclaims set ClaimAtStep=4 where CrBy='".$_POST['crby']."' and ClaimMonth='".$_POST['month']."' and ClaimYearId='".$_SESSION['FYearId']."'");
  
  /******** ---- final table update ************/
   $Vpaid=mysql_query("SELECT SUM(VerifyTAmt) as VerifyAmt, MAX(VerifyDate) as VerifyD FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_POST['crby']."' and ClaimMonth='".$_POST['month']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and VerifyBy>0"); 
   $clm2=mysql_fetch_assoc($Vpaid); 
   if($clm2['VerifyAmt']>0){$Verified_Amount=$clm2['VerifyAmt'];}else{$Verified_Amount=0;} 
   if($clm2['VerifyD']!=''){$Verified_Date=$clm2['VerifyD'];}else{$Verified_Date='0000-00-00';}
  
   mysql_query("UPDATE y".$_SESSION['FYearId']."_monthexpensefinal SET Verified_Amount=".$Verified_Amount.", Verified_Date='".$Verified_Date."' where EmployeeID='".$_POST['crby']."' and Month='".$_POST['month']."' and YearId='".$_SESSION['FYearId']."'");
   /******** ---- final table update ************/
     
  $month=$_POST['month'];
  $month = (int)$month;
  $month++;
  if($up){ echo 'closed'; }

	
}
elseif($_POST['act']=='submittofinance')
{
  $up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET ClaimAtStep=5, ClaimStatus='Approved', ApprBy='".$_SESSION['EmployeeID']."', ApprDate='".date("Y-m-d")."' where CrBy='".$_POST['crby']."' and ClaimMonth='".$_POST['month']."' and ClaimYearId='".$_SESSION['FYearId']."' and (ClaimStatus='Verified' OR ClaimStatus='Approved')"); 

   /******** ---- final table update ************/
   $Apaid=mysql_query("SELECT SUM(ApprTAmt) as ApprAmt, MAX(ApprDate) as ApprD FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE ClaimYearId='".$_SESSION['FYearId']."' and `CrBy`='".$_POST['crby']."' and ClaimMonth='".$_POST['month']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and ApprBy>0"); $clm3=mysql_fetch_assoc($Apaid); 
  if($clm3['ApprAmt']>0){$Approved_Amount=$clm3['ApprAmt'];}else{$Approved_Amount=0;} 
  if($clm3['ApprD']!=''){$Approved_Date=$clm3['ApprD'];}else{$Approved_Date='0000-00-00';}
   mysql_query("UPDATE y".$_SESSION['FYearId']."_monthexpensefinal SET Approved_Amount=".$Approved_Amount.", Approved_Date='".$Approved_Date."' where EmployeeID='".$_POST['crby']."' and Month='".$_POST['month']."' and YearId='".$_SESSION['FYearId']."'");
   /******** ---- final table update ************/
  
  $selexp=mysql_query("select ExpId from y".$_SESSION['FYearId']."_expenseclaims where CrBy='".$_POST['crby']."' and ClaimMonth='".$_POST['month']."' and ClaimYearId='".$_SESSION['FYearId']."' and ClaimStatus='Approved'");
  while($selexpd=mysql_fetch_assoc($selexp)){ mysql_query("update y".$_SESSION['FYearId']."_expenseclaimsdetails set FinanceEditAmount=ApproverEditAmount where ExpId='".$selexpd['ExpId']."'"); }
  if($up){ echo 'submitted'; }


}
elseif($_POST['act']=='submitFtoFreturn')
{
  $up=mysql_query("UPDATE y".$_SESSION['FYearId']."_monthexpensefinal SET Verified_Amount=0, Verified_Date='0000-00-00' where EmployeeID='".$_POST['crby']."' and Month='".$_POST['month']."' and YearId='".$_SESSION['FYearId']."'"); 
  if($up){ echo 'Returned'; }
	
	
}




elseif($_POST['act']=='closeClaimMonth')
{
	
   $up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET ClaimStatus='Financed', ClaimAtStep=5, FinancedBy='".$_SESSION['EmployeeID']."', `FinancedTAmt`=`ApprTAmt`, `FinancedDate`='".date("Y-m-d")."' where CrBy='".$_POST['crby']."' AND (FinancedBy=0 OR FinancedBy='') AND (FinancedDate='0000-00-00' OR FinancedDate='') AND ClaimStatus='Approved'");
   $up=mysql_query("update y".$_SESSION['FYearId']."_expenseclaims set ClaimAtStep=6 where CrBy='".$_POST['crby']."' and ClaimMonth='".$_POST['month']."' and ClaimYearId='".$_SESSION['FYearId']."'");
   
   /******** ---- final table update ************/
   $DocMxID=mysql_query("SELECT Max(DocId) as MxID FROM y".$_SESSION['FYearId']."_monthexpensefinal WHERE `YearId`=".$_SESSION['FYearId']." AND Month<=12 AND Status='Closed' AND Finance_Amount>0 AND Finance_Date!='0000-00-00' AND Finance_Date!='1970-01-01' AND Fin_AppBy>0"); $rMxID=mysql_fetch_assoc($DocMxID);
   $MxD=0;
   if($rMxID['MxID']=='' OR $rMxID['MxID']=='NULL' OR $rMxID['MxID']==0){ $MxD=0; }
   else{ $MxD=$rMxID['MxID']; }
   
   $DocId=$MxD+1; $LEC=strlen($DocId);
   if($LEC==1){ $DocNo='000'.$DocId; } 
   elseif($LEC==2){ $DocNo='00'.$DocId; } 
   elseif($LEC==3){ $DocNo='0'.$DocId; } 
   elseif($LEC>=4){ $DocNo=$DocId; }
   else{ $DocNo=$DocId; }
   
   $Fpaid=mysql_query("SELECT SUM(FinancedTAmt) as FinAmt, MAX(FinancedDate) as FinD FROM y".$_SESSION['FYearId']."_expenseclaims WHERE `ClaimYearId`=".$_SESSION['FYearId']." and `CrBy`='".$_POST['crby']."' and ClaimMonth='".$_POST['month']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FinancedBy>0"); $clm4=mysql_fetch_assoc($Fpaid); 
  if($clm4['FinAmt']>0){$Finance_Amount=$clm4['FinAmt'];}else{$Finance_Amount=0;} 
  if($clm4['FinD']!=''){$Finance_Date=$clm4['FinD'];}else{$Finance_Date='0000-00-00';}
  $up=mysql_query("update y".$_SESSION['FYearId']."_monthexpensefinal set DocId=".$DocId.", DocNo='".$DocNo."', Finance_Amount =".$Finance_Amount.", Finance_Date='".date("Y-m-d")."', Fin_AppBy=".$_SESSION['EmployeeID'].", Fin_AppDate='".date("Y-m-d")."' where EmployeeID='".$_POST['crby']."' and Month='".$_POST['month']."' and YearId='".$_SESSION['FYearId']."'");
   /******** ---- final table update ************/

	$month=$_POST['month'];
	$month = (int)$month;
	$month++;
	if($up){ echo 'closed'; }
	
	
}
elseif($_POST['act']=='PaymentClaimMonth')
{

 $up=mysql_query("update y".$_SESSION['FYearId']."_monthexpensefinal set Fin_AdvancePay='".$_POST['AdvAmt']."', Fin_PayOption='".$_POST['PayOption']."', Fin_PayBy=".$_SESSION['EmployeeID'].", Fin_PayAmt ='".$_POST['PayAmt']."', Fin_PayDate='".date("Y-m-d", strtotime($_POST['PayDate']))."', Fin_PayRemark='".$_POST['PayRemark']."' where EmployeeID='".$_POST['crby']."' and Month='".$_POST['month']."' and YearId='".$_SESSION['FYearId']."'");
 if($up){ echo 'payment'; }
 

}
elseif($_POST['act']=='okayAllFilledClaims')
{

	$emp=$_POST['emp'];
	$month=$_POST['month'];
	$cgid=$_POST['cgid'];

	$claimids="";


	$cg=mysql_query("select ClaimId from claimtype where cgId=".$cgid);
	while($cgd=mysql_fetch_assoc($cg)){
		$claimids.=$cgd['ClaimId'].",";
	}
	$claimids.="123456";

	$up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET FilledOkay=1 where Crby='".$emp."' and ClaimMonth='".$month."' and FilledOkay!=2 and ClaimId IN (".$claimids.") and ClaimStatus='Filled'");

	

	if($up){
		echo 'okay';
	}



}elseif($_POST['act']=='delupimg'){

	$del=mysql_query("DELETE FROM `y1_claimuploads` WHERE FileName='".$_POST['imgname']."'");
	if($del){
		echo 'deleted';
	}

}
elseif($_POST['act']=='DeleteExpId')
{
	$up=mysql_query("update y".$_POST['yi']."_expenseclaims set ClaimStatus='Deactivate' where ExpId='".$_POST['exid']."'");
	if($up){
		echo 'Done';
	}

}





?>