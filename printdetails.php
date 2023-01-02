<?php
session_start();
include 'config.php';

function getClaimType($cid)
{   include 'config.php';
	$c=mysql_query("SELECT ClaimName FROM `claimtype` where ClaimId=".$cid);
	$ct=mysql_fetch_assoc($c);
	return $ct['ClaimName'];
}
function getUser($u)
{   include 'config.php';
	$u=mysql_query("SELECT EmpCode,Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$u, $con2);
	$un=mysql_fetch_assoc($u);
	$LEC=strlen($un['EmpCode']); if($LEC==1){$EC='000'.$un['EmpCode'];} if($LEC==2){$EC='00'.$un['EmpCode'];} if($LEC==3){$EC='0'.$un['EmpCode'];} if($LEC>=4){$EC=$un['EmpCode'];}
	return $EC.'-'.$un['Fname'].' '.$un['Sname'].' '.$un['Lname'];
}

?>
<script type="text/javascript">
function printp(){window.print(); document.getElementById("PrtDiv").style.display='none'; }
</script>
<?php 
$m=mysql_query("SELECT * FROM `y".$_REQUEST['y']."_monthexpensefinal` WHERE YearId=".$_REQUEST['y']." AND Month=".$_REQUEST['m']." AND EmployeeID=".$_REQUEST['e']); $mlist=mysql_fetch_assoc($m); 

$ed=mysql_query("SELECT g.DepartmentId,g.HqId,DepartmentCode,StateName,EmpVertical,HqName FROM `hrm_employee` e inner join hrm_employee_general g on e.EmployeeID=g.EmployeeID inner join hrm_department d on g.DepartmentId=d.DepartmentId inner join hrm_state s on g.CostCenter=s.StateId inner join hrm_headquater hq on g.HqId=hq.HqId where e.EmployeeID=".$_REQUEST['e'], $con2); $red=mysql_fetch_assoc($ed);
if($red['EmpVertical']>0){ $sV=mysql_query("select VerticalName from hrm_department_vertical where VerticalId=".$red['EmpVertical'],$con2);$rV=mysql_fetch_assoc($sV);  }

$sy=mysql_query("select Year from financialyear where YearId=".$_REQUEST['y']); $ry=mysql_fetch_assoc($sy);?>
<table style="width:100%;">
<tr>
 <td style="width:100%;text-align:center; font-size:20px;">
 <font style="font-size:18px;color:#F20000;"><b>Expense Details ( <?=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y'))).'/'.$ry['Year'].'';?> )</b></font><br />
 <b>Claimer:</b>&nbsp;<?=getUser($mlist['EmployeeID'])?><br />
 <b>(<?=ucwords(strtolower($red['DepartmentCode'])); ?><?php if($rV['VerticalName']!=''){ echo ' - '.$rV['VerticalName']; }?>)</b><br>
 
 <?php $sqlRId=mysql_query("select RegionId from hrm_sales_verhq where HqId=".$red['HqId']." AND Vertical=".$red['EmpVertical']." AND DeptId=".$red['DepartmentId'], $con2); $resRId=mysql_fetch_assoc($sqlRId);
$sqlRR=mysql_query("select RegionName,ZoneId from hrm_sales_region where RegionId=".$resRId['RegionId'], $con2); $resRR=mysql_fetch_assoc($sqlRR);
$sqlZZ=mysql_query("select ZoneName from hrm_sales_zone where ZoneId=".$resRR['ZoneId'], $con2); $resZZ=mysql_fetch_assoc($sqlZZ); ?>
  
  <b>HQ:&nbsp;</b><?=ucwords(strtolower($red['HqName']))?>&nbsp;,&nbsp;<b>State:&nbsp;</b><?=ucwords(strtolower($red['StateName']))?><?php if($red['DepartmentId']==6){?>&nbsp;,&nbsp;<b>Region:&nbsp;</b><?=ucwords(strtolower($resRR['RegionName']))?>&nbsp;,&nbsp;<b>Zone:&nbsp;</b><?=ucwords(strtolower($resZZ['ZoneName']))?><br><?php } ?>
  
  
  <?php /*<b>Total Amount:&nbsp;</b><?=floatval($mlist['Claim_Amount']).'/-';?>
 <?php /*?>&nbsp;&nbsp;<b>Total Claim</b>:&nbsp;<?=$rTot['TotClaim'];?><?php */?>
 </td>
 
 <td style="width:5%;text-align:right;">
 <div id="PrtDiv" style="display:block;">
  <a href="#" onClick="printp()">print</a>
 </div>
 </td>
</tr>
</table>


<?php if($_REQUEST['n']==1){ ?>

<table style="width:100%;" border="1" cellspacing="0">
  <thead class="thead-dark">
   <tr style="background-color:#005353; color:#FFFFFF;height:25px; font-size:12px;">
    <th scope="col" style="width:250px;">Claimer - Code</th>
    <th scope="col" style="width:60px;">Total<br />Claims</th>
    <th scope="col" style="width:60px;">Submited<br />Date</th>
    <th scope="col" style="width:80px;">Claimed<br>Amt</th>
	<th scope="col" style="width:80px;">Verified<br>Amt</th>
	<th scope="col" style="width:80px;">Approver<br>Amt</th>
    
    <th scope="col" style="width:80px;">Payment<br />Amount</th>
	<th scope="col" style="width:80px;">Advance<br />Amount</th>
	<th scope="col" style="width:80px;">Paid<br />Amount</th>
	<th scope="col" style="width:80px;">Paid<br />Option</th>
    <th scope="col" style="width:100px;">Payment<br />Date</th>
    <th scope="col" style="width:200px;">Payment Remark</th>
   </tr>
  </thead>				  
  <tbody>
<?php $m=mysql_query("SELECT * FROM `y".$_REQUEST['y']."_monthexpensefinal` WHERE YearId=".$_REQUEST['y']." AND Month=".$_REQUEST['m']." AND EmployeeID=".$_REQUEST['e']); $mlist=mysql_fetch_assoc($m); 
	
$sTot=mysql_query("SELECT count(*) as TotClaim FROM `y".$_REQUEST['y']."_expenseclaims` WHERE  `ClaimAtStep`=6 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'"); $rTot=mysql_fetch_assoc($sTot);

?>
   <tr style="font-size:12px; height:25px; background-color:#FFFFB3;">
    <td style="text-align:left;">&nbsp;<?=getUser($mlist['EmployeeID'])?></td>
    <td style="text-align:center;"><?=$rTot['TotClaim'];?></td>
    <td style="text-align:center;"><?php if($mlist['DateOfSubmit']!='0000-00-00' && $mlist['DateOfSubmit']!='' && $mlist['DateOfSubmit']!='1970-01-01'){ echo date("d-m-Y",strtotime($mlist['DateOfSubmit'])); } ?></td>
    <td style="text-align:right;"><?=$mlist['Claim_Amount'];?>&nbsp;</td>
	<td style="text-align:right;"><?=$mlist['Verified_Amount'];?>&nbsp;</td>
	<td style="text-align:right;"><?=$mlist['Approved_Amount'];?>&nbsp;</td>
    
	<td style="text-align:right;"><?=$mlist['Finance_Amount'];?>&nbsp;</td>	
	<td style="text-align:right;"><?=$mlist['Fin_AdvancePay'];?>&nbsp;</td>	
	<td style="text-align:right;"><?=$mlist['Fin_PayAmt'];?>&nbsp;</td>														
    <td style="text-align:left;"><?=$mlist['Fin_PayOption'];?></td>
    <td style="text-align:center;"><?php if($mlist['Fin_PayDate']!='0000-00-00' && $mlist['Fin_PayDate']!='1970-01-01' && $mlist['Fin_PayDate']!=''){ echo date("d-m-Y",strtotime($mlist['Fin_PayDate'])); }?></td>
    <td style="text-align:left;"><?=$mlist['Fin_PayRemark'];?></td>
   </tr>
   <tr>
    <td colspan="12" style="text-align:center; vertical-align:top; margin-top:0px;">
<?php /****************************************************/ ?>
<?php /****************************************************/ ?>
	<table border="1" cellspacing="0" style="width:100%;">
	  <thead class="thead-dark">
	    <tr style="font-size:12px;background-color:#0062C4;color:#FFFFFF;">
	      <th style="width:30px;">Sn</th>
	      <th style="width:50px;">Claim ID</th>
	      <th style="width:150px;">Claim Type</th>
	      <th style="width:80px;">Upload Date</th>
	      <th style="width:80px;">Bill Date</th>
	      <th style="width:80px;">Claimed<br>Amt</th>
		  <th style="width:80px;">Verified<br>Amt</th>
		  <th style="width:80px;">Approver<br>Amt</th>
		  <th style="width:80px;">Paid<br>Amt</th>
	    </tr>
	  </thead>
	  <tbody>
        <tr class="totalrow" style="background-color:#FFFFD2;">
         <th scope="row" colspan="5" style="font-size:12px;text-align:right;">Total:&nbsp;</th>
         <td style="text-align:right;"><font style="font-size:12px; font-weight:bold;"><?php /*$totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_REQUEST['y']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['m']."' and `ClaimYearId`='".$_REQUEST['y']."'and e.CrBy=".$_REQUEST['e']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0"); $clm=mysql_fetch_assoc($totpaid);*/ echo floatval($mlist['Claim_Amount']); ?></font>&nbsp;</td>
         <td style="text-align:right;"><font style="font-size:12px;font-weight:bold;"><?php /*$totpaid2=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_REQUEST['y']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['m']."' and `ClaimYearId`='".$_REQUEST['y']."' and e.CrBy=".$_REQUEST['e']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and VerifyBy>0"); $clm2=mysql_fetch_assoc($totpaid2);*/ echo floatval($mlist['Verified_Amount']); ?></font>&nbsp;</td>
         <td style="text-align:right;"><font style="font-size:12px;font-weight:bold;"><?php /*$totpaid3=mysql_query("SELECT SUM(ApprTAmt) as paid FROM `y".$_REQUEST['y']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['m']."' and `ClaimYearId`='".$_REQUEST['y']."' and e.CrBy=".$_REQUEST['e']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and ApprBy>0"); $clm3=mysql_fetch_assoc($totpaid3);*/ echo floatval($mlist['Approved_Amount']); ?></font>&nbsp;</td>
         <td style="text-align:right;"><font style="font-size:12px;font-weight:bold;"><?php /*$totpaid4=mysql_query("SELECT SUM(FinancedTAmt) as paid FROM `y".$_REQUEST['y']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['m']."' and `ClaimYearId`='".$_REQUEST['y']."' and e.CrBy=".$_REQUEST['e']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FinancedBy>0"); $clm4=mysql_fetch_assoc($totpaid4);*/ echo floatval($mlist['Finance_Amount']); ?></font>&nbsp;</td>
        </tr>

	  	<?php $seleq=mysql_query("SELECT e.*, c.ClaimName FROM `y".$_REQUEST['y']."_expenseclaims` e, claimtype c where c.ClaimId=e.ClaimId and e.ClaimMonth='".$_REQUEST['m']."' and e.ClaimYearId='".$_REQUEST['y']."' and e.CrBy=".$_REQUEST['e']." and e.ClaimAtStep>=6 and e.FilledOkay=1 and e.ClaimStatus!='Deactivate' and e.ClaimStatus!='Draft' order by e.BillDate ASC");
		$i=1; while($exp=mysql_fetch_assoc($seleq)){ ?>
	    <tr style="font-size:12px;">
	     <th style="text-align:center;"><?=$i;?></th>
	     <td style="text-align:center;"><?=$exp['ExpId'];?></td>
	     <td><?=$exp['ClaimName'];?></td> 
	     <td style="text-align:center;"><?=date("d-m-Y",strtotime($exp['CrDate']));?></td>
	     <td style="text-align:center;"><?=date("d-m-Y",strtotime($exp['BillDate']));?></td>
	     <td style="text-align:right;"><?=$exp['FilledTAmt'];?>&nbsp;</td>
		 <td style="text-align:right;"><?=$exp['VerifyTAmt'];?>&nbsp;</td>
		 <td style="text-align:right;"><?=$exp['ApprTAmt'];?>&nbsp;</td>
		 <td style="text-align:right;"><?=$exp['FinancedTAmt'];?>&nbsp;</td> 
	    </tr>
	    <?php $i++; } ?>
		
        <tr class="totalrow" style="background-color:#FFFFD2;">
         <th scope="row" colspan="5" style="font-size:12px;text-align:right;">Total:&nbsp;</th>
         <td style="text-align:right;"><font style="font-size:12px;font-weight:bold;"><?php /*$totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_REQUEST['y']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['m']."' and `ClaimYearId`='".$_REQUEST['y']."'and e.CrBy=".$_REQUEST['e']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0"); $clm=mysql_fetch_assoc($totpaid);*/ echo floatval($mlist['Claim_Amount']); ?></font>&nbsp;</td>
         <td style="text-align:right;"><font style="font-size:12px;font-weight:bold;"><?php /*$totpaid2=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_REQUEST['y']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['m']."' and `ClaimYearId`='".$_REQUEST['y']."' and e.CrBy=".$_REQUEST['e']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and VerifyBy>0"); $clm2=mysql_fetch_assoc($totpaid2);*/ echo floatval($mlist['Verified_Amount']); ?></font>&nbsp;</td>
         <td style="text-align:right;"><font style="font-size:12px;font-weight:bold;"><?php /*$totpaid3=mysql_query("SELECT SUM(ApprTAmt) as paid FROM `y".$_REQUEST['y']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['m']."' and `ClaimYearId`='".$_REQUEST['y']."' and e.CrBy=".$_REQUEST['e']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and ApprBy>0"); $clm3=mysql_fetch_assoc($totpaid3);*/ echo floatval($mlist['Approved_Amount']); ?></font>&nbsp;</td>
         <td style="text-align:right;"><font style="font-size:12px;font-weight:bold;"><?php /*$totpaid4=mysql_query("SELECT SUM(FinancedTAmt) as paid FROM `y".$_REQUEST['y']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['m']."' and `ClaimYearId`='".$_REQUEST['y']."' and e.CrBy=".$_REQUEST['e']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FinancedBy>0"); $clm4=mysql_fetch_assoc($totpaid4);*/ echo floatval($mlist['Finance_Amount']); ?></font>&nbsp;</td>
        </tr>
		
	  </tbody>
	</table>
<?php /****************************************************/ ?>	
<?php /****************************************************/ ?>	
	</td>
   </tr>
  </tbody>			  
</table>

<?php } ?>

<?php if($_REQUEST['n']==2){ ?>

<table style="width:100%;" border="1" cellspacing="0">
  <thead class="thead-dark">
   <tr style="background-color:#005353; color:#FFFFFF;height:25px; font-size:12px;">
    <th scope="col" style="width:250px;">Claimer - Code</th>
    <th scope="col" style="width:60px;">Total<br />Claims</th>
    <th scope="col" style="width:60px;">Submited<br />Date</th>
    <th scope="col" style="width:80px;">Claimed<br>Amt</th>
	<th scope="col" style="width:80px;">Verified<br>Amt</th>
	<th scope="col" style="width:80px;">Approver<br>Amt</th>
    <th scope="col" style="width:80px;">Payment<br />Amount</th>
	<th scope="col" style="width:80px;">Advance<br />Amount</th>
	<th scope="col" style="width:80px;">Paid<br />Amount</th>
	<th scope="col" style="width:80px;">Paid<br />Option</th>
    <th scope="col" style="width:100px;">Payment<br />Date</th>
    <th scope="col" style="width:200px;">Payment Remark</th>
   </tr>
  </thead>				  
  <tbody>
<?php $m=mysql_query("SELECT * FROM `y".$_REQUEST['y']."_monthexpensefinal` WHERE YearId=".$_REQUEST['y']." AND Month=".$_REQUEST['m']." AND EmployeeID=".$_REQUEST['e']); $mlist=mysql_fetch_assoc($m); 

$sTot=mysql_query("SELECT count(*) as TotClaim FROM `y".$_REQUEST['y']."_expenseclaims` WHERE  `ClaimAtStep`=6 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'"); $rTot=mysql_fetch_assoc($sTot);
	
//$sTot=mysql_query("SELECT count(*) as TotClaim, SUM(FilledTAmt) as TotFill, SUM(VerifyTAmt) as TotVeri, SUM(ApprTAmt) as TotAppr, SUM(FinancedTAmt) as TotFin FROM `y".$_REQUEST['y']."_expenseclaims` WHERE  `ClaimAtStep`=6 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'"); $rTot=mysql_fetch_assoc($sTot);
?>
   <tr style="font-size:12px; height:25px; background-color:#FFFFB3;">
    <td style="text-align:left;">&nbsp;<?=getUser($mlist['EmployeeID'])?></td>
    <td style="text-align:center;"><?=$rTot['TotClaim'];?></td>
    <td style="text-align:center;"><?php if($mlist['DateOfSubmit']!='0000-00-00' && $mlist['DateOfSubmit']!='' && $mlist['DateOfSubmit']!='1970-01-01'){ echo date("d-m-Y",strtotime($mlist['DateOfSubmit'])); } ?></td>
    <td style="text-align:right;"><?=$mlist['Claim_Amount'];?>&nbsp;</td>
	<td style="text-align:right;"><?=$mlist['Verified_Amount'];?>&nbsp;</td>
	<td style="text-align:right;"><?=$mlist['Approved_Amount'];?>&nbsp;</td>
    
	<td style="text-align:right;"><?=$mlist['Finance_Amount'];?>&nbsp;</td>	
	<td style="text-align:right;"><?=$mlist['Fin_AdvancePay'];?>&nbsp;</td>	
	<td style="text-align:right;"><?=$mlist['Fin_PayAmt'];?>&nbsp;</td>														
    <td style="text-align:left;"><?=$mlist['Fin_PayOption'];?></td>
    <td style="text-align:center;"><?php if($mlist['Fin_PayDate']!='0000-00-00' && $mlist['Fin_PayDate']!='1970-01-01' && $mlist['Fin_PayDate']!=''){ echo date("d-m-Y",strtotime($mlist['Fin_PayDate'])); }?></td>
    <td style="text-align:left;"><?=$mlist['Fin_PayRemark'];?></td>
   </tr>
   <tr>
    <td colspan="12" style="text-align:center;vertical-align:top; margin-top:0px;">
<?php /****************************************************/ ?>
<?php /****************************************************/ ?>
	<?php $sGp=mysql_query("select * from claimgroup order by cgId asc"); while($rGp=mysql_fetch_assoc($sGp)){ 
	$sCtyp=mysql_query("select * from claimtype where cgId=".$rGp['cgId']." and (ClaimStatus='A' OR ClaimStatus='B') order by ClaimCode asc");
	while($rCtyp=mysql_fetch_assoc($sCtyp)){ $arr_ctyp[]=$rCtyp['ClaimId']; $ctype = implode(',', $arr_ctyp); }
	
	if($rGp['cgId']==1)
	{
	 $sCtyp=mysql_query("select * from claimtype where cgId=".$rGp['cgId']." and (ClaimStatus='A' OR ClaimStatus='B') order by ClaimCode asc"); 
	 while($rCtyp=mysql_fetch_assoc($sCtyp)){ $arr_ctyp[]=$rCtyp['ClaimId']; $ctype = implode(',', $arr_ctyp); }
	 $qry=$ctype;
	}
	elseif($rGp['cgId']==2){$qry=10;}
	elseif($rGp['cgId']==3){$qry=12;}
	elseif($rGp['cgId']==4){$qry=11;}
	elseif($rGp['cgId']==5){$qry=13;}
	
	$sChk=mysql_query("SELECT * FROM `y".$_REQUEST['y']."_expenseclaims` WHERE ClaimId in (".$qry.") and `ClaimAtStep`=6 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'");
	$rowChk=mysql_num_rows($sChk); 
	if($rowChk>0)
	{
	?>
	<table border="1" cellspacing="0" style="width:100%;">
	  <thead class="thead-dark">
	    <tr style="font-size:12px; background-color:#0062C4;color:#FFFFFF;width:100%;">
	      <th style="text-align:left; height:25px;"><b>
		   &nbsp;EXPENSE TYPE : &nbsp;<?php echo strtoupper($rGp['cgName'].' ('.$rGp['cgCode'].')'); ?></b></th>
	    </tr>
		<tr>
		 <td style="width:100%; vertical-align:top;" cellspacing="0">
		  <table style="width:100%;" border="1" cellspacing="0">
		   <tr style="height:25px;background-color:#0062C4;color:#FFFFFF;">
		    <th scope="col" style="width:50px;font-size:12px;">Sn</th>
			<th scope="col" style="width:100px;font-size:12px;">Date</th>
			<th scope="col" style="width:250px;font-size:12px;">Remark</th>
		    <?php $sCtyp=mysql_query("select * from claimtype where cgId=".$rGp['cgId']." and (ClaimStatus='A' OR ClaimStatus='B') order by ClaimCode asc"); while($rCtyp=mysql_fetch_assoc($sCtyp)){ ?>
			
			<?php if($rCtyp['ClaimId']==7){?>
			<th scope="col" style="width:60px;font-size:12px;">2W</th>
			<th scope="col" style="width:60px;font-size:12px;">4W</th>
			<?php }else{ 
			
			//$sChkk=mysql_query("SELECT * FROM `y".$_REQUEST['y']."_expenseclaims` WHERE ClaimId in (".$rCtyp['ClaimId'].") and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m'].""); 
			$sChkk=mysql_query("SELECT count(*) as TotRow FROM `y".$_REQUEST['y']."_expenseclaims` WHERE ClaimId in (".$rCtyp['ClaimId'].") and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m'].""); 
			$rowChkk=mysql_fetch_assoc($sChkk);
			if($rowChkk['TotRow']>0)
			{
			?>
		    <th scope="col" style="width:60px;font-size:12px;"><?php echo strtoupper($rCtyp['ClaimCode']); ?></th>
		    <?php 
			} //if($rowChkk>0)
			
			} ?>
			
		    <?php } ?>	
			<th scope="col" style="width:80px;font-size:12px;">Total/Fin</th>
		   </tr>
		   <?php $sDetl=mysql_query("SELECT BillDate,Remark,SUM(FinancedTAmt) as Tot2Amt FROM `y".$_REQUEST['y']."_expenseclaims` WHERE `ClaimAtStep`=6 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m']." and ClaimId in (".$qry.") and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' group by BillDate order by BillDate asc"); 
		   $sn=1; while($rDetl=mysql_fetch_assoc($sDetl)){ ?>
		   <tr style="height:25px;">
		    <td align="center" style="font-size:12px;"><?php echo $sn; ?></td>
			<td align="center" style="font-size:12px;"><?php echo date("d-m-Y",strtotime($rDetl['BillDate'])); ?></td>
			
			<?php if($rGp['cgId']==2 OR $rGp['cgId']==3 OR $rGp['cgId']==4 OR $rGp['cgId']==5){
			$sgp=mysql_query("select * from y".$_REQUEST['y']."_g".$rGp['cgId']."_expensefilldata where ExpId=(select ExpId from `y".$_REQUEST['y']."_expenseclaims` where BillDate='".$rDetl['BillDate']."' and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m']." and (ClaimId=10 OR ClaimId=11 OR ClaimId=12 OR ClaimId=13))"); $rgp=mysql_fetch_assoc($sgp);?>
			<td align="left" style="font-size:12px;">
			<?php if($rgp['Crop']!=''){ echo '<b>Crop:</b>'.$rgp['Crop'].' '; } ?>
			<?php if($rgp['Vegetable']!=''){ echo '<b>Crop:</b>'.$rgp['Vegetable'].' '; } ?>
			<?php if($rgp['CropDetails']!=''){ echo '<b>Details:</b>'.$rgp['Vegetable'].' '; } ?>
			<?php if($rgp['fms']!='' && $rgp['fms']!=0){ echo '<b>No of Farmers :</b>'.$rgp['fms'].' '; } ?>
			<?php if($rgp['dtp']!='' && $rgp['dtp']!=0){ echo '<b>For:</b>'.$rgp['dtp'].' '; } ?>
			<?php if($rDetl['Remark']!=''){ echo ', '.substr_replace(ucfirst(strtolower($rDetl['Remark'])), '', 30).''; echo '...'; }  ?></td>
			<?php }else{ ?>
			<td align="left" style="font-size:12px;"><?php echo substr_replace(ucfirst(strtolower($rDetl['Remark'])), '', 30).''; if($rDetl['Remark']!=''){echo '...';} ?></td>
			<?php } ?>
			
			<?php $sCtyp=mysql_query("select ClaimId from claimtype where cgId=".$rGp['cgId']." and (ClaimStatus='A' OR ClaimStatus='B') order by ClaimCode asc"); while($rCtyp=mysql_fetch_assoc($sCtyp)){ ?>
			
			
			<?php if($rCtyp['ClaimId']==7){ $tot1=0; $tot2=0;
			
			$stot=mysql_query("select SUM(FinancedTAmt) as TotAmt from `y".$_REQUEST['y']."_expenseclaims` where ClaimId=".$rCtyp['ClaimId']." and BillDate='".$rDetl['BillDate']."' and `ClaimAtStep`=6 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' "); $rtot=mysql_fetch_assoc($stot);
			
			if($rtot['TotAmt']>0)
			{
			$s2tot=mysql_query("select SUM(FinancedTAmt) as TotAmt from `y".$_REQUEST['y']."_expenseclaims` clm inner join `y".$_REQUEST['y']."_g1_expensefilldata` gd on clm.ExpId=gd.ExpId where clm.ClaimId=".$rCtyp['ClaimId']." and clm.BillDate='".$rDetl['BillDate']."' and clm.FilledOkay=1 and clm.ClaimStatus!='Deactivate' and clm.ClaimStatus!='Draft' and clm.FilledBy>0 and clm.CrBy=".$_REQUEST['e']." and clm.ClaimYearId=".$_REQUEST['y']." and clm.ClaimMonth=".$_REQUEST['m']." and gd.VehicleType=2"); 
			$r2tot=mysql_fetch_assoc($s2tot); $tot1=$r2tot['TotAmt'];
			$s4tot=mysql_query("select SUM(FinancedTAmt) as TotAmt from `y".$_REQUEST['y']."_expenseclaims` clm inner join `y".$_REQUEST['y']."_g1_expensefilldata` gd on clm.ExpId=gd.ExpId where clm.ClaimId=".$rCtyp['ClaimId']." and clm.BillDate='".$rDetl['BillDate']."' and clm.FilledOkay=1 and clm.ClaimStatus!='Deactivate' and clm.ClaimStatus!='Draft' and clm.FilledBy>0 and clm.CrBy=".$_REQUEST['e']." and clm.ClaimYearId=".$_REQUEST['y']." and clm.ClaimMonth=".$_REQUEST['m']." and gd.VehicleType=4"); 
			$r4tot=mysql_fetch_assoc($s4tot); $tot2=$r4tot['TotAmt'];
			}
			?>
			<td align="right" style="font-size:12px;"><?php if($tot1>0){echo $tot1;}else{echo '';}  ?>&nbsp;</td>
			<td align="right" style="font-size:12px;"><?php if($tot2>0){echo $tot2;}else{echo '';}  ?>&nbsp;</td>
			<?php }else{ 
			
			//$sChkk=mysql_query("SELECT * FROM `y".$_REQUEST['y']."_expenseclaims` WHERE ClaimId in (".$rCtyp['ClaimId'].") and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m'].""); 
			$sChkk=mysql_query("SELECT count(*) as TotRow FROM `y".$_REQUEST['y']."_expenseclaims` WHERE ClaimId in (".$rCtyp['ClaimId'].") and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m'].""); 
			$rowChkk=mysql_fetch_assoc($sChkk);
			if($rowChkk['TotRow']>0)
			{
			
			$stot=mysql_query("select SUM(FinancedTAmt) as TotAmt from `y".$_REQUEST['y']."_expenseclaims` where ClaimId=".$rCtyp['ClaimId']." and BillDate='".$rDetl['BillDate']."' and `ClaimAtStep`=6 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' "); $rtot=mysql_fetch_assoc($stot);
			
			?>
		    <td align="right" style="font-size:12px;"><?php if($rtot['TotAmt']>0){echo $rtot['TotAmt'];}else{echo '';}  ?>&nbsp;</td>
		    <?php 
			} //if($rowChkk>0)
			
			} ?>
			
			<?php } ?>
			
			<td align="right" style="font-size:12px;background-color:#FFFFB3;"><b><?php /*$stot2=mysql_query("select SUM(FinancedTAmt) as Tot2Amt from `y".$_REQUEST['y']."_expenseclaims` where BillDate='".$rDetl['BillDate']."' and `ClaimAtStep`=6 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m']." and ClaimId in (".$qry.") and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'"); $rtot2=mysql_fetch_assoc($stot2);*/ if($rDetl['Tot2Amt']>0){echo $rDetl['Tot2Amt'];}else{echo '';} ?>&nbsp;</b></td>
		   </tr>
		   <?php $sn++; } //while($rDetl=mysql_fetch_assoc($sDetl))?>
		   <tr style="background-color:#FFFFB3;height:25px;">
		    <td colspan="3" align="right" style="font-size:12px;"><b>Total:&nbsp;</b></td>
			<?php $sCtyp2=mysql_query("select ClaimId from claimtype where cgId=".$rGp['cgId']." and (ClaimStatus='A' OR ClaimStatus='B') order by ClaimCode asc"); while($rCtyp2=mysql_fetch_assoc($sCtyp2)){ 
			?>
			
			
			<?php if($rCtyp2['ClaimId']==7){
			
			$stot3=mysql_query("select SUM(FinancedTAmt) as Tot3Amt from `y".$_REQUEST['y']."_expenseclaims` where ClaimId=".$rCtyp2['ClaimId']." and `ClaimAtStep`=6 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m']." and ClaimId in (".$qry.") and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'"); $rtot3=mysql_fetch_assoc($stot3);
			
			if($rtot3['Tot3Amt']>0)
			{
			
			$s2tot3=mysql_query("select SUM(FinancedTAmt) as Tot3Amt from `y".$_REQUEST['y']."_expenseclaims` clm inner join `y".$_REQUEST['y']."_g1_expensefilldata` gd on clm.ExpId=gd.ExpId where clm.ClaimId=".$rCtyp2['ClaimId']." and clm.FilledOkay=1 and clm.ClaimStatus!='Deactivate' and clm.ClaimStatus!='Draft' and clm.FilledBy>0 and clm.CrBy=".$_REQUEST['e']." and clm.ClaimYearId=".$_REQUEST['y']." and clm.ClaimMonth=".$_REQUEST['m']." and gd.VehicleType=2"); 
			$r2tot3=mysql_fetch_assoc($s2tot3);
			$s4tot3=mysql_query("select SUM(FinancedTAmt) as Tot3Amt from `y".$_REQUEST['y']."_expenseclaims` clm inner join `y".$_REQUEST['y']."_g1_expensefilldata` gd on clm.ExpId=gd.ExpId where clm.ClaimId=".$rCtyp2['ClaimId']." and clm.FilledOkay=1 and clm.ClaimStatus!='Deactivate' and clm.ClaimStatus!='Draft' and clm.FilledBy>0 and clm.CrBy=".$_REQUEST['e']." and clm.ClaimYearId=".$_REQUEST['y']." and clm.ClaimMonth=".$_REQUEST['m']." and gd.VehicleType=4"); 
			$r4tot3=mysql_fetch_assoc($s4tot3);
			
			}
			?>
			<td align="right" style="font-size:12px;"><b><?php if($r2tot3['Tot3Amt']>0){echo $r2tot3['Tot3Amt'];}else{echo '';}  ?>&nbsp;</b></td>
			<td align="right" style="font-size:12px;"><b><?php if($r4tot3['Tot3Amt']>0){echo $r4tot3['Tot3Amt'];}else{echo '';}  ?>&nbsp;</b></td>
			<?php }else{ 
			
			//$sChkk2=mysql_query("SELECT * FROM `y".$_REQUEST['y']."_expenseclaims` WHERE ClaimId in (".$rCtyp2['ClaimId'].") and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m'].""); 
			$sChkk2=mysql_query("SELECT count(*) as TotRoww FROM `y".$_REQUEST['y']."_expenseclaims` WHERE ClaimId in (".$rCtyp2['ClaimId'].") and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m']."");
			$rowChkk2=mysql_fetch_assoc($sChkk2);
			if($rowChkk2['TotRoww']>0)
			{
			
			$stot3=mysql_query("select SUM(FinancedTAmt) as Tot3Amt from `y".$_REQUEST['y']."_expenseclaims` where ClaimId=".$rCtyp2['ClaimId']." and `ClaimAtStep`=6 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m']." and ClaimId in (".$qry.") and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'"); $rtot3=mysql_fetch_assoc($stot3);
			
			?>
		    <td align="right" style="font-size:12px;"><b><?php if($rtot3['Tot3Amt']>0){echo $rtot3['Tot3Amt'];}else{echo '';}  ?>&nbsp;</b></td>
		    <?php 
			} //if($rowChkk2>0)
			
			} ?>
			
			<?php } ?>
			
			<td align="right" style="font-size:12px;"><b><?php $stot4=mysql_query("select SUM(FinancedTAmt) as Tot4Amt from `y".$_REQUEST['y']."_expenseclaims` where `ClaimAtStep`=6 and CrBy=".$_REQUEST['e']." and ClaimYearId=".$_REQUEST['y']." and ClaimMonth=".$_REQUEST['m']." and ClaimId in (".$qry.") and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'"); $rtot4=mysql_fetch_assoc($stot4); if($rtot4['Tot4Amt']>0){echo $rtot4['Tot4Amt'];}else{echo '';} ?>&nbsp;</b></td>
		   </tr>
		  </table>
		 </td>
		</tr>
	  </thead>
	  <tbody>	
	  </tbody>
	</table>
	<br /><br />
	<?php } //if($rowChk>0) ?>
	
	<?php } //while($rGp=mysql_fetch_assoc($sGp)) ?>
<?php /****************************************************/ ?>	
<?php /****************************************************/ ?>	
	</td>
   </tr>
  </tbody>			  
</table>

<?php } ?>

<table style="width:100%;">
<tr>
 <td style="width:100%;text-align:center; font-size:20px;">&nbsp;</td>
</tr>
</table>

				
