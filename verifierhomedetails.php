<?php
function getUser($u)
{
 include 'config.php';
 $u=mysql_query("SELECT Fname,Sname,Lname,EmpCode FROM `hrm_employee` where EmployeeID=".$u,$con2);
 $un=mysql_fetch_assoc($u);
 return $un['Fname'].' '.$un['Sname'].' '.$un['Lname'].' - '.$un['EmpCode'];
}
if($_REQUEST['v']=='')
{ 
 //if(date("m")==1){$_REQUEST['v']=12;}else{$_REQUEST['v']=date("m")-1;} 
 $_REQUEST['v']=0;
}



/*
$sql=mysql_query("SELECT EmployeeID,Month,YearId FROM `y2_monthexpensefinal` WHERE YearId=2 and `Status`='Closed' order by EmployeeID asc, Month asc"); while($res=mysql_fetch_assoc($sql))
{ 
  $Total_Claim=0; $Claim_Amount=0; $Verified_Amount=0; $Verified_Date='0000-00-00'; 
  $Approved_Amount=0; $Approved_Date='0000-00-00'; 
  
  $st1=mysql_query("SELECT count(*) as TotClm FROM `y2_expenseclaims` e WHERE `ClaimYearId`=2 and `CrBy`='".$res['EmployeeID']."' and ClaimMonth='".$res['Month']."' and (e.ClaimStatus!='Deactivate' and e.ClaimStatus!='Draft' and e.FilledBy>0)"); 
  $rest1=mysql_fetch_assoc($st1); if($rest1['TotClm']>0){$Total_Claim=$rest1['TotClm'];}else{$Total_Claim=0;}
  
  $totpaid=mysql_query("SELECT SUM(FilledTAmt) as FilledAmt FROM `y2_expenseclaims` WHERE `ClaimYearId`=2 and `CrBy`='".$res['EmployeeID']."' and ClaimMonth='".$res['Month']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0"); $clm=mysql_fetch_assoc($totpaid); 
  if($clm['FilledAmt']>0){$Claim_Amount=$clm['FilledAmt'];}else{$Claim_Amount=0;}
  
  $Vpaid=mysql_query("SELECT SUM(VerifyTAmt) as VerifyAmt, MAX(VerifyDate) as VerifyD FROM `y2_expenseclaims` WHERE `ClaimYearId`=2 and `CrBy`='".$res['EmployeeID']."' and ClaimMonth='".$res['Month']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and VerifyBy>0"); $clm2=mysql_fetch_assoc($Vpaid); 
  if($clm2['VerifyAmt']>0){$Verified_Amount=$clm2['VerifyAmt'];}else{$Verified_Amount=0;} 
  if($clm2['VerifyD']!=''){$Verified_Date=$clm2['VerifyD'];}else{$Verified_Date='0000-00-00';}
  
  $Apaid=mysql_query("SELECT SUM(ApprTAmt) as ApprAmt, MAX(ApprDate) as ApprD FROM `y2_expenseclaims` WHERE `ClaimYearId`=2 and `CrBy`='".$res['EmployeeID']."' and ClaimMonth='".$res['Month']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and ApprBy>0"); $clm3=mysql_fetch_assoc($Apaid); 
  if($clm3['ApprAmt']>0){$Approved_Amount=$clm3['ApprAmt'];}else{$Approved_Amount=0;} 
  if($clm3['ApprD']!=''){$Approved_Date=$clm3['ApprD'];}else{$Approved_Date='0000-00-00';}
  
  $Fpaid=mysql_query("SELECT SUM(FinancedTAmt) as FinAmt, MAX(FinancedDate) as FinD FROM `y2_expenseclaims` WHERE `ClaimYearId`=2 and `CrBy`='".$res['EmployeeID']."' and ClaimMonth='".$res['Month']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FinancedBy>0"); $clm4=mysql_fetch_assoc($Fpaid); 
  if($clm4['FinAmt']>0){$Finance_Amount=$clm4['FinAmt'];}else{$Finance_Amount=0;} 
  if($clm4['FinD']!=''){$Finance_Date=$clm4['FinD'];}else{$Finance_Date='0000-00-00';}
  
  $up=mysql_query("update y2_monthexpensefinal set Total_Claim=".$Total_Claim.", Claim_Amount=".$Claim_Amount.", Verified_Amount=".$Verified_Amount.", Verified_Date='".$Verified_Date."', Approved_Amount=".$Approved_Amount.", Approved_Date='".$Approved_Date."', Finance_Amount =".$Finance_Amount.", Finance_Date='".$Finance_Date."' WHERE `EmployeeID`=".$res['EmployeeID']." AND YearId=2 and Month=".$res['Month']);
   
}

*/

/*
if($_SESSION['EmpRole']=='V')
{
  $eMM=mysql_query("select ExpId from y".$_SESSION['FYearId']."_expenseclaims where ClaimId=7 AND ClaimStatus='Submitted' and VerifyBy=0"); $roweMM=mysql_num_rows($eMM); 
  if($roweMM>0)
  {
    while($reM=mysql_fetch_assoc($eMM))
    {
	 $sAmt=mysql_query("select SUM(FilledTAmt) as TotalAmt from y".$_SESSION['FYearId']."_24_wheeler_entry where Totalkm>0 AND ExpId=".$reM['ExpId']); $rAmt=mysql_fetch_assoc($sAmt); 
	 if($rAmt['TotalAmt']>0)
	 {
	  $qry=mysql_query("update y".$_SESSION['FYearId']."_expenseclaims set FilledTAmt='".$rAmt['TotalAmt']."', VerifyTAmt='".$rAmt['TotalAmt']."', ApprTAmt='".$rAmt['TotalAmt']."', FinancedTAmt='".$rAmt['TotalAmt']."' where ClaimId=7 AND ExpId=".$reM['ExpId']." AND ClaimStatus='Submitted' and VerifyBy=0");
     }
	}
  } 
} //if($_SESSION['EmpRole']=='V') */

?>

<script type="text/javascript">
function isNumberKey(evt)
{
 var charCode = (evt.which) ? evt.which : event.keyCode
 //if (charCode > 31 && (charCode < 48 || charCode > 57))
 if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))  /* For floating*/
	return false;
 return true;
}

function FunChk(v){ $('#chkval').val(v); }

function FselMonth()
{ 
 var chkval=$('#chkval').val(); var SelMonth =$('#SelMonth').val();
 var SelVe =$('#SelVe').val();
 if(SelMonth==''){alert("please select month"); return false; }
 window.location="home.php?action=displayrec&v="+SelMonth+"&chkval="+chkval+"&ve="+SelVe; 
}

function FunPrint(e,m,y,n)
{
 window.open("printdetails.php?e="+e+"&m="+m+"&y="+y+"&n="+n,"PForm","menubar=no,scrollbars=yes,resizable=no,directories=no,width=1200,height=450"); 
}

function ExpAP(v,m,e,y,t)
{
 window.open("verifierhomedetailsexport.php?action=exportdetails&v="+v+"&m="+m+"&e="+e+"&y="+y+"&t="+t,"VerifierForm","menubar=no,scrollbars=yes,resizable=no,directories=no,width=100,height=100"); 
}
</script>

<div class="col-md-11 h-100" style="border-left:5px solid #d9d9d9;">
  <br>
  <font style="font-size:14px;">Pending : <input type="radio" id="c2" name="chkp" onclick="FunChk(2)" <?php if($_REQUEST['chkval']==2){echo 'checked';} ?>/></font>
  &nbsp;
  <font style="font-size:14px;">Approved : <input type="radio" id="c1" name="chkp" onclick="FunChk(1)" <?php if($_REQUEST['chkval']==1){echo 'checked';} ?>/></font>
  <input type="hidden" id="chkval" value="<?php if($_REQUEST['chkval']!=''){echo $_REQUEST['chkval'];}else{echo 2;} ?>" />
  &nbsp;&nbsp;
  <font style="font-size:14px;">Month :</font>&nbsp;
   <select style="font-size:14px;" id="SelMonth">
	  <option value="0" <?php if($_REQUEST['v']=='' || $_REQUEST['v']==0){echo 'selected';}?>>All</option>
	  <option value="03" <?php if($_REQUEST['v']==3){echo 'selected';}?>>March</option>
	  <option value="02" <?php if($_REQUEST['v']==2){echo 'selected';}?>>February</option>
	  <option value="01" <?php if($_REQUEST['v']==1){echo 'selected';}?>>January</option>
	  <option value="12" <?php if($_REQUEST['v']==12){echo 'selected';}?>>December</option>
	  <option value="11" <?php if($_REQUEST['v']==11){echo 'selected';}?>>November</option>
	  <option value="10" <?php if($_REQUEST['v']==10){echo 'selected';}?>>October</option>
	  <option value="09" <?php if($_REQUEST['v']==9){echo 'selected';}?>>September</option>
	  <option value="08" <?php if($_REQUEST['v']==8){echo 'selected';}?>>August</option>
	  <option value="07" <?php if($_REQUEST['v']==7){echo 'selected';}?>>July</option>
	  <option value="06" <?php if($_REQUEST['v']==6){echo 'selected';}?>>June</option>
	  <option value="05" <?php if($_REQUEST['v']==5){echo 'selected';}?>>May</option>
	  <option value="04" <?php if($_REQUEST['v']==4){echo 'selected';}?>>April</option>
   </select>
   &nbsp;
   <select style="font-size:14px; width:180px;" id="SelVe" >
  <option value="0" <?php if($_REQUEST['ve']=='' || $_REQUEST['ve']==0){echo 'selected';}?>>All Employee</option>			  
  <?php  
  $seleqe=mysql_query("SELECT EmployeeID as CrBy FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE YearId=".$_SESSION['FYearId']." and `Status`='Closed' and Total_Claim>0 and (Verified_Amount=0 OR Verified_Date='0000-00-00') group by EmployeeID order by CrBy ASC"); //and g.CostCenter in (".$state_data.")

  while($expe=mysql_fetch_assoc($seleqe))
  { 

   $senn=mysql_query("select EmpCode, Fname, Sname, Lname from hrm_employee where EmployeeID=".$expe['CrBy'],$con2); 
   $expnn=mysql_fetch_assoc($senn);
  ?>
  <option value="<?=$expe['CrBy']?>" <?php if($_REQUEST['ve']==$expe['CrBy']){echo 'selected';}?>><?=$expnn['EmpCode'].' - '.$expnn['Fname'].' '.$expnn['Sname'].' '.$expnn['Lname']?></option>
  <?php } //while ?>								  
 </select>
 &nbsp; 
   
   
   <a class="btn btn-sm btn-primary" onclick="FselMonth()"><i class="fa fa-btn" aria-hidden="true"></i><span style="color:#FFFFFF; width:80px;">&nbsp;&nbsp;&nbsp;Click&nbsp;&nbsp;&nbsp;</span></a>   
   &nbsp;&nbsp;&nbsp; 
   <a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
   <div class="row">
    <?php if($_REQUEST['action']=='displayrec' && $_REQUEST['chkval']==2){ ?>		
    <div class="col-lg-11 shadow">
	 <br>
	  <h5><small class="font-weight-bold text-muted">Pending Claims</small>
	  &nbsp;&nbsp;
	  <font style="font-size:14px;cursor:pointer;color:#0000FF;"><span onclick="ExpAP('A',<?=$_REQUEST['v'].','.$_SESSION['EmployeeID'].','.$_SESSION['FYearId'].','.$_REQUEST['chkval']?>)"><u>Export All</u></span></font> </h5>
							
	  <table class="estable table shadow ">
	  <thead class="thead-dark">
		<tr>
		  <th scope="col" style="width:50px;vertical-align:middle;">Code</th>
		  <th scope="col" style="width:200px;vertical-align:middle;">Claimer</th>
		  <th scope="col" style="width:80px;vertical-align:middle;">Month</th>
		  <th scope="col" style="width:80px;vertical-align:middle;">Submission<br />Date</th>
		  <th scope="col" style="width:60px;vertical-align:middle;">Claims</th>
		  <th scope="col" style="width:60px;vertical-align:middle;">Amount</th>
		  <th scope="col" style="vertical-align:middle;">Action</th>
		  <th scope="col" style="width:100px;vertical-align:middle;">Courier_Detail</th> 
		</tr>
	  </thead>
      <tbody>
	  
<?php if($_REQUEST['v']=='' || $_REQUEST['v']==0){ $cond='1=1'; }else{ $cond='Month='.$_REQUEST['v']; } 
if($_REQUEST['ve']>0){ $empQ="EmployeeID=".$_REQUEST['ve']; }
else{ $empQ="1=1"; }
				
	  $sql_statement=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE YearId=".$_SESSION['FYearId']." and `Status`='Closed' and Total_Claim>0 and (Verified_Amount=0 OR Verified_Date='0000-00-00') and ".$empQ." and ".$cond." order by Month asc, EmployeeID asc"); //and DocateNo!=''			
					
$total_records = mysql_num_rows($sql_statement);
if(isset($_GET['page']))
$page = $_GET['page'];
else
$page = 1;
$offset = 15;
if ($page){
$from = ($page * $offset) - $offset;
}else{
$from = 0;
}					
	  			//and DocateNo!=''
      $m=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE YearId=".$_SESSION['FYearId']." and `Status`='Closed' and Total_Claim>0 and (Verified_Amount=0 OR Verified_Date='0000-00-00') and ".$empQ." and ".$cond." order by Month asc, EmployeeID asc LIMIT ".$from.",".$offset);  
      $sn=1;
	  while($mlist=mysql_fetch_assoc($m))
      {			
		$u=mysql_query("SELECT Fname,Sname,Lname,EmpCode FROM `hrm_employee` where EmployeeID=".$mlist['EmployeeID'],$con2); 
		$un=mysql_fetch_assoc($u);
?>
       <tr>
		<td><?php echo $un['EmpCode']; ?></td>
		<td style="text-align:left;"><?php echo $un['Fname'].' '.$un['Sname'].' '.$un['Lname']; ?></td>
		<td><span onclick="showmonthdet('<?=$mlist['Month']?>','Open','<?=$mlist['EmployeeID']?>','Filled',<?=$mlist['id']?>)" style="cursor:pointer; color:#0033CC;"><u><?=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y')));?></u></span></td>
		<td><span onclick="showmonthdet('<?=$mlist['Month']?>','Open','<?=$mlist['EmployeeID']?>','Filled',<?=$mlist['id']?>)" style="cursor:pointer; color:#0033CC;"><u><?=date("d-m-Y",strtotime($mlist['DateOfSubmit']));?></u></span></td>
		<td>
		 <?php if($mlist['Total_Claim']>0){ ?>	
		 <span class="btn btn-sm btn-outline-primary font-weight-bold">Total: <?=$mlist['Total_Claim']?></span>
		 <input type="hidden" id="sts<?=$mlist['EmployeeID']?><?=$mlist['Month']?>Filled" value="close">
		 <?php } ?>
		</td>
		<td style="text-align:right;"><?php echo $mlist['Claim_Amount']; ?>&nbsp; </td>
		<td>
		    <?php $Vchk=mysql_query("SELECT count(*) as totVfy FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE CrBy=".$mlist['EmployeeID']." AND ClaimMonth=".$mlist['Month']." AND VerifyBy>0 AND VerifyTAmt>0 AND ClaimStatus!='Draft' AND ClaimStatus!='Deactivate'"); $rVchk=mysql_fetch_assoc($Vchk); ?>
		    
		    <button type="button" id="Btn<?=$mlist['id']?>" class="btn btn-sm btn-primary" onclick="submittoapprover('<?=$mlist['Month']?>','<?=$mlist['EmployeeID']?>')" <?php //if($rVchk['totVfy']==0 OR $mlist['Status']=='Open'){echo 'disabled';}?>>Submit To Approver</button>
		    &nbsp;&nbsp;
			<button type="button" class="btn btn-sm btn-warning" onclick="submittoreturn('<?=$mlist['Month']?>','<?=$mlist['EmployeeID']?>')" <?php if($mlist['Status']=='Open'){echo 'disabled';}?>>Return</button>
		    &nbsp;&nbsp;
			<button type="button" class="btn btn-sm btn-success" onclick="submittodetails('<?=$mlist['Month']?>','<?=$mlist['EmployeeID']?>')">E-Home</button>
		    &nbsp;&nbsp;
		    <?php $name=getUser($mlist['EmployeeID']); $month=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y'))); ?>
		    
		    <button type="button" class="btn btn-sm btn-Danger" onclick="closeClaimMonth('<?=$mlist['Month']?>','<?=$mlist['EmployeeID']?>','<?=$name?>','<?=$month?>')">Approval All Claim</button>	    
		
		</td>
		<td style="vertical-align:middle;cursor:pointer;text-decoration:underline;">
		  <span onclick="FUnOPen(<?=$sn?>)">
		   <?php if($mlist['RecevingDate']=='0000-00-00'){ //$mlist['PostDate']!='0000-00-00' && ?>Click
		   <?php }elseif($mlist['RecevingDate']!='0000-00-00'){ //$mlist['PostDate']!='0000-00-00' &&?>Recieved<?php } ?>
		  </span>
		</td>
      </tr>
	  <tr id="<?=$mlist['EmployeeID']?><?=$mlist['Month']?>Filled"></tr>
					  					  	
<?php /**************************** Open *************/ ?>
<?php /**************************** Open *************/ ?>
  <tr>
    <td colspan="7" style="width:100%;text-align:right;">
	 <div id="Div<?=$sn?>" style="display:none;">
	  <table style="width:100%; vertical-align:top;" cellspacing="0">
	   <tr>
	    <td style="width:350px;text-align:right;">
	<font style="float:left;">&nbsp;<b>Post Date:</b>&nbsp;<?php echo date("d-m-Y",strtotime($mlist['PostDate'])); ?></font>
	<font style="float:left;">&nbsp;&nbsp;<b>DocateNo:</b>&nbsp;<?=$mlist['DocateNo']?></font>	 
	<b>Recieving Date</b></td>
	    <td style="width:200px;"><div class="input-group date form_date col-md-12" data-date="" data-date-format="dd-mm-yyyy" data-link-field="RecevingDate<?=$sn?>" data-link-format="dd-mm-yyyy" style="padding:0px;"><input id="RecevingDate<?=$sn?>" style="font-family:Georgia;font-size:12px;width:100%;text-align:center;text-align:left;" value="<?php if($mlist['RecevingDate']!='0000-00-00'){echo date("d-m-Y",strtotime($mlist['RecevingDate'])); }else{echo date("d-m-Y"); }?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div></td>
	   </tr>
	   <tr>
	     <td style="text-align:right;">
		 <font style="float:left;">&nbsp;<b>Agency:</b>&nbsp;<?php echo $mlist['Agency']; ?></font>
		 <?php /*<b>Verify Date</b>*/ ?></td>
	     <td><?php /*<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd-mm-yyyy" data-link-field="VerifDate<?=$sn?>" data-link-format="dd-mm-yyyy" style="padding:0px;"><input id="VerifDate<?=$sn?>" style="font-family:Georgia;font-size:12px;width:100%; text-align:left;" value="<?php if($mlist['VerifDate']!='0000-00-00'){echo date("d-m-Y",strtotime($mlist['VerifDate'])); }else{echo date("d-m-Y"); }?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>*/?></td>
	   </tr>
	   <tr>  
		<td style="text-align:right;"><b>Any Remark</b></td>
	    <td><input id="DocRmk<?=$sn?>" style="font-family:Georgia;font-size:12px;width:100%;" value="<?=$mlist['DocRmk']?>"></td>
	   </tr>
	   <tr style="background-color:#FFFFFF;height:24px;">
	    <td colspan="2" style="text-align:right;">
	<?php if($mlist['RecevingDate']=='0000-00-00' OR $mlist['RecevingDate']=='1970-01-01' OR $mlist['RecevingDate']==''){ ?>
		<button class="btn-primary btn-sm" style="font-size:10px;" onclick="FunSave(<?=$sn.','.$mlist['EmployeeID'].','.$mlist['Month'].','.$_SESSION['FYearId']?>)">save</button><?php } ?>
		<button class="btn-primary btn-sm" style="font-size:10px;" onclick="FunClose(<?=$sn?>)">close</button>
		</td>
	   </tr>	
	  </table>
	 </div>
	</td>
   <tr>
<?php /****************************** Close ***********/ ?>
<?php /****************************** Close ***********/ ?>					  	
					  	
<?php 
      $sn++; 
	  } //while
?>
										
<tr>
 <td align="center" colspan="10" style="font-family:Times New Roman;font-size:15px;font-weight:bold;">
<?PHP doPages($offset, 'home.php', '', $total_records); ?></td>
</tr>
					
				  </tbody>
				</table>
				
			</div>
<?php } // if($_REQUEST['action']=='displayrec')?>	
		</div>

		
		<div class="row">
<?php if($_REQUEST['action']=='displayrec' && $_REQUEST['chkval']==1){ ?>			
			<div class="col-lg-11 shadow">
			<br>
			 <h5><small class="font-weight-bold text-muted">Approved Claim</small> 
			 &nbsp;&nbsp;
			 <font style="font-size:14px;cursor:pointer;color:#0000FF;"><span onclick="ExpAP('A',<?=$_REQUEST['v'].','.$_SESSION['EmployeeID'].','.$_SESSION['FYearId'].','.$_REQUEST['chkval']?>)"><u>Export All</u></span></font></h5> 
			  <table class="estable table shadow ">
			  <thead class="thead-dark">
			    <tr>
				 <th scope="col" style="width:50px;vertical-align:middle;">Code</th>
				 <th scope="col" style="width:200px;vertical-align:middle;">Claimer</th>
				 <th scope="col" style="width:100px;vertical-align:middle;">Month</th>
				 <th scope="col" style="width:60px;vertical-align:middle;">Cliam Amount</th>
			     <th scope="col" style="width:60px;vertical-align:middle;">Verify Amount</th>
				 <th scope="col" style="vertical-align:middle;">Claims</th>
				 <th scope="col" style="width:100px;vertical-align:middle;">Courier_Detail</th>
				</tr>
			   </thead>
			   <tbody>
<?php if($_REQUEST['v']=='' || $_REQUEST['v']==0){ $cond='1=1'; }else{ $cond='Month='.$_REQUEST['v']; } 
	  $sql_statement=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE YearId=".$_SESSION['FYearId']." and `Status`='Closed' and Total_Claim>0 and Verified_Amount>0 and Verified_Date!='0000-00-00' and Verified_Date!='1970-01-01' and ".$cond." order by Month asc, EmployeeID asc");
			   									
$total_records = mysql_num_rows($sql_statement);
if(isset($_GET['page']))
$page = $_GET['page'];
else
$page = 1;
$offset = 15;
if ($page){
$from = ($page * $offset) - $offset;
}else{
$from = 0;
}						
					
	  $m=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE YearId=".$_SESSION['FYearId']." and `Status`='Closed' and Total_Claim>0 and Verified_Amount>0 and Verified_Date!='0000-00-00' and Verified_Date!='1970-01-01' and ".$cond." order by Month asc, EmployeeID asc LIMIT ".$from.",".$offset);			
      $sn=1;
	  while($mlist=mysql_fetch_assoc($m))
	  {
		$u=mysql_query("SELECT Fname,Sname,Lname,EmpCode FROM `hrm_employee` where EmployeeID=".$mlist['EmployeeID'],$con2);
		$un=mysql_fetch_assoc($u);
?>
      <tr>
		<td><?php echo $un['EmpCode']; ?></td>
		<td style="text-align:left;"><?php echo $un['Fname'].' '.$un['Sname'].' '.$un['Lname']; ?></td>
		<td><a href="#" onclick="showmonthdet('<?=$mlist['Month']?>','Open','<?=$mlist['EmployeeID']?>','Verified')">
			<?=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y')));?>	
			</a>
		</td>
		<td style="text-align:right;"><?php echo $mlist['Claim_Amount']; ?></td>
		<td style="text-align:right;"><?php echo $mlist['Verified_Amount']; ?></td>
		<td><input type="hidden" id="sts<?=$mlist['EmployeeID']?><?=$mlist['Month']?>Verified" value="close">
			<button type="button" class="btn btn-sm btn-success" onclick="submittodetails('<?=$mlist['Month']?>','<?=$mlist['EmployeeID']?>')">E-Home</button></td>
		<td style="vertical-align:middle;cursor:pointer;text-decoration:underline;">
		  <span onclick="FUnOPen(<?=$sn?>)">
		   <?php if($mlist['PostDate']!='0000-00-00' && $mlist['RecevingDate']=='0000-00-00'){ ?>Click
		   <?php }elseif($mlist['PostDate']!='0000-00-00' && $mlist['RecevingDate']!='0000-00-00'){ ?>Recieved<?php } ?>
		  </span>
		</td>
	   </tr>
	  <tr id="<?=$mlist['EmployeeID']?><?=$mlist['Month']?>Verified"></tr>
	
<?php /**************** Open *************************/ ?>
<?php /**************** Open *************************/ ?>
      <tr>
       <td colspan="6" style="width:100%;text-align:right;">
       <div id="Div<?=$sn?>" style="display:none;">
        <table style="width:100%; vertical-align:top;" cellspacing="0">
         <tr>
          <td style="width:350px;text-align:right;">
   <font style="float:left;">&nbsp;<b>Post Date:</b>&nbsp;<?php echo date("d-m-Y",strtotime($mlist['PostDate'])); ?></font>
   <font style="float:left;">&nbsp;&nbsp;<b>DocateNo:</b>&nbsp;<?=$mlist['DocateNo']?></font><b>Recieving Date</b>
         </td>
         <td style="width:200px;"><div class="input-group date form_date col-md-12" data-date="" data-date-format="dd-mm-yyyy" data-link-field="RecevingDate<?=$sn?>" data-link-format="dd-mm-yyyy" style="padding:0px;"><input id="RecevingDate<?=$sn?>" style="font-family:Georgia;font-size:12px;width:100%;text-align:center;text-align:left;" value="<?php if($mlist['RecevingDate']!='0000-00-00'){echo date("d-m-Y",strtotime($mlist['RecevingDate'])); }else{echo date("d-m-Y"); }?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div></td>
        </tr>
        <tr>
         <td style="text-align:right;">
          <font style="float:left;">&nbsp;<b>Agency:</b>&nbsp;<?php echo $mlist['Agency']; ?></font>
          <?php /*<b>Verify Date*/ ?></b>
         </td>
         <td><?php /*<div class="input-group date form_date col-md-12" data-date="" data-date-format="dd-mm-yyyy" data-link-field="VerifDate<?=$sn?>" data-link-format="dd-mm-yyyy" style="padding:0px;"><input id="VerifDate<?=$sn?>" style="font-family:Georgia;font-size:12px;width:100%; text-align:left;" value="<?php if($mlist['VerifDate']!='0000-00-00'){echo date("d-m-Y",strtotime($mlist['VerifDate'])); }?>" readonly><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>*/ ?></td>
        </tr>
        <tr>  
         <td style="text-align:right;"><b>Any Remark</b></td>
    <td><input id="DocRmk<?=$sn?>" style="font-family:Georgia;font-size:12px;width:100%;" value="<?=$mlist['DocRmk']?>"></td>
        </tr>
        <tr style="background-color:#FFFFFF;height:24px;">
         <td colspan="2" style="text-align:right;">
<?php if($mlist['RecevingDate']=='0000-00-00' OR $mlist['RecevingDate']=='1970-01-01' OR $mlist['RecevingDate']==''){ ?>
<button class="btn-primary btn-sm" style="font-size:10px;" onclick="FunSave(<?=$sn.','.$mlist['EmployeeID'].','.$mlist['Month'].','.$_SESSION['FYearId']?>)">save</button><?php } ?>
<button class="btn-primary btn-sm" style="font-size:10px;" onclick="FunClose(<?=$sn?>)">close</button>
</td>
</tr>	
</table>
</div>
</td>
<tr>
<?php /****************** Close ***********************/ ?>
<?php /****************** Close ***********************/ ?>					  	
					  	
					  	
<?php
       $sn++; 
	 } //while
?>
					
<tr>
 <td align="center" colspan="10" style="font-family:Times New Roman;font-size:15px;font-weight:bold;">
<?PHP doPages($offset, 'home.php', '', $total_records); ?></td>
</tr>					
					
				  </tbody>					  
				  
				</table>
				
			</div>
<?php } //if($_REQUEST['action']=='displayrec') ?>			
		</div>
		<br>

	
		
	
</div>


<script type="text/javascript" src="dt_jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
<!--<script type="text/javascript" src="dt_bootstrap/js/bootstrap.min.js"></script>-->
<script type="text/javascript" src="dt_js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="dt_js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<script type="text/javascript">
	$('.form_date').datetimepicker({
        language:  'us',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
    });
</script>

<script type="text/javascript">

function FUnOPen(sn){ document.getElementById("Div"+sn).style.display='block'; }
function FunClose(sn){ document.getElementById("Div"+sn).style.display='none'; }
function FunSave(sn,eid,m,yid)
{  
   if(confirm('Are you sure?'))
   { 
     var rd=$('#RecevingDate'+sn).val(); 
	 //var vd=$('#VerifDate'+sn).val();
	 var rmk=$('#DocRmk'+sn).val();
	 $.post("courierajax.php",{act:"VerifyCourierDetails",sn:sn,eid:eid,m:m,yid:yid,rd:rd,rmk:rmk},function(data){
	 if(data.includes('done')){ alert('Updated Successfully'); }else{ alert("Error"); }	});
   }
}

	function showmonthdet(month,sts,emp,csts){

		var status=document.getElementById('sts'+emp+month+csts);
		var modal = document.getElementById(emp+month+csts);
		if(status.value=='close'){
			$.post("claimlistajax.php",{act:"monthdettoverifier",month:month,sts:sts,csts:csts,emp:emp},function(data){
				modal.innerHTML = data;
			});
			status.value="open";
		}else if(status.value=='open'){
			modal.innerHTML = '';
			status.value="close";
		}
	}
	
	function submittoapprover(month,crby){
		if (confirm('Are you sure to Final Submit this month claims to Approver?')){
			$.post("homeajax.php",{act:"submittoapprover",month:month,crby:crby},function(data){
				if(data.includes('submitted')){
					alert('Submitted to Approver Successfully');
					location.reload();
				}
			});
		}
	}
	
	
	function submittoreturn(month,crby){
		if (confirm('Are you sure to return this month claim from verifier level?')){
			$.post("homeajax.php",{act:"submittoreturn",month:month,crby:crby},function(data){ alert(data);
				if(data.includes('Returned')){
					alert('Returned to Employee Successfully');
					location.reload();
				}
			});
		}
	}
	
	function submittodetails(month,crby){
		window.open("EvhomeDetails.php?view=verifier&mnt="+month+"&ei="+crby,"Home","menubar=no,scrollbars=yes,resizable=no,directories=no,width=800,height=500"); 
	}
	
	
	function closeClaimMonth(month,crby,username,monthname){
	
	
	var agree=confirm('Are you sure to Final Close '+username+'\'s '+monthname+' Claim\'s?');
		
	if(agree){
			$.post("homeajax.php",{act:"FromVerifierCloseClaimMonth",month:month,crby:crby},function(data){
				console.log(data);
				if(data.includes('closed')){
					alert( username+'\'s '+monthname+' month Closed Successfully');
					location.reload();
				}

			});
		}
		else { return false; }

	}
	

	function showexpdet(expid){
		var modal = document.getElementById('myModal');
		modal.style.display = "block";
		document.getElementById('claimlistfr').src="showclaim.php?expid="+expid;
	}

	function verifyClaim(expid,id){
		var v=parseInt(document.getElementById(expid+'verifiedtamt').value);
		var r=document.getElementById(expid+'verifiedtremark').value;
		
		$.post("claimajax.php",{act:"verifyClaim",expid:expid,vtamt:v,verifiedtremark:r},function(data){
			
			if(data.includes('verified')){
				document.getElementById(expid+'Status').innerHTML='Verified'; 
				document.getElementById(expid+'btn').innerHTML='View'; 
				alert('Verified Successfully');
				document.getElementById('Btn'+id).disabled=false;
				document.getElementById(expid+'verifyaction').innerHTML=''; 
			}
		});
	}

	function showbtn(chk,expid){
		if (chk.checked) {
           $('#'+expid+'verifybtn').prop('disabled', false);
        }else{
        	$('#'+expid+'verifybtn').prop('disabled', true);
        }
	}

	function checkrange(thisamt,mainamt,expid){
    
	    var t=parseInt(thisamt.value);
	    var m=parseInt(mainamt);
	    if(t>m)
		{ 
		 alert("Please check verified amount"); 
		 $('#'+expid+'verifiedtamt').val(mainamt); 
		}
	    
	    
	}
	function isNumber(evt) {
	    evt = (evt) ? evt : window.event;
	    var charCode = (evt.which) ? evt.which : evt.keyCode;
	    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
	        return false;
	    }
	    return true;
	}
</script>



<?php
function check_integer($which) {
if(isset($_REQUEST[$which])){
if (intval($_REQUEST[$which])>0) {
return intval($_REQUEST[$which]);
} else {
return false;
}
}
return false;
}
function get_current_page() {
if(($var=check_integer('page'))) {
return $var;
} else {
//return 1, if it wasnt set before, page=1
return 1;
}
}
function doPages($page_size, $thepage, $query_string, $total=0) {
$index_limit = 10;
$query='';
if(strlen($query_string)>0){
$query = "&amp;".$query_string;
}
$current = get_current_page();
$total_pages=ceil($total/$page_size);
$start=max($current-intval($index_limit/2), 1);
$end=$start+$index_limit-1;
echo '<div class="paging">';
if($current==1) {
echo '<span class="prn">&lt; Previous</span>&nbsp;';
} else {
$i = $current-1;
echo '<a href="'.$thepage.'?page='.$i.$query.'&action='.$_REQUEST['action'].'&v='.$_REQUEST['v'].'&chkval='.$_REQUEST['chkval'].'&yi='.$_SESSION['FYearId'].'&ee=we23&er=1013&rr=wew101" class="prn" rel="nofollow" title="go to page '.$i.'">&lt; Previous</a>&nbsp;';
echo '<span class="prn">...</span>&nbsp;';
}
if($start > 1) {
$i = 1;
echo '<a href="'.$thepage.'?page='.$i.$query.'&action='.$_REQUEST['action'].'&v='.$_REQUEST['v'].'&chkval='.$_REQUEST['chkval'].'&yi='.$_SESSION['FYearId'].'&ee=we23&er=1013&rr=wew101" title="go to page '.$i.'">'.$i.'</a>&nbsp;';
}
for ($i = $start; $i <= $end && $i <= $total_pages; $i++){
if($i==$current) {
echo '<span>'.$i.'</span>&nbsp;';
} else {
echo '<a href="'.$thepage.'?page='.$i.$query.'&action='.$_REQUEST['action'].'&v='.$_REQUEST['v'].'&chkval='.$_REQUEST['chkval'].'&yi='.$_SESSION['FYearId'].'&ee=we23&er=1013&rr=wew101" title="go to page '.$i.'">'.$i.'</a>&nbsp;';
}
}
if($total_pages > $end){
$i = $total_pages;
echo '<a href="'.$thepage.'?page='.$i.$query.'&action='.$_REQUEST['action'].'&v='.$_REQUEST['v'].'&chkval='.$_REQUEST['chkval'].'&yi='.$_SESSION['FYearId'].'&ee=we23&er=1013&rr=wew101" title="go to page '.$i.'">'.$i.'</a>&nbsp;';
}
if($current < $total_pages) {
$i = $current+1;
echo '<span class="prn">...</span>&nbsp;';
echo '<a href="'.$thepage.'?page='.$i.$query.'&action='.$_REQUEST['action'].'&v='.$_REQUEST['v'].'&chkval='.$_REQUEST['chkval'].'&yi='.$_SESSION['FYearId'].'&ee=we23&er=1013&rr=wew101" class="prn" rel="nofollow" title="go to page '.$i.'">Next &gt;</a>&nbsp;';
} else {
echo '<span class="prn">Next &gt;</span>&nbsp;';
}
if ($total != 0){
//prints the total result count just below the paging
echo '&nbsp;&nbsp;&nbsp;&nbsp;<font color="#ee4545"<h4>(Total '.$total.' Records)</h></div>';
}
}
?>