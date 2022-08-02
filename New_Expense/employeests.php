<?php include "header.php"; ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>
<style type="text/css">
div.dataTables_wrapper div.dataTables_length select{ width:100; }
div.table-responsive>div.dataTables_wrapper>div.row { background-color: #ccf5ff; padding: 10px; width:100%; }
</style>
<script type="text/javascript">
function FunEmp(ei,t)
{
 window.location="employeests.php?ei="+ei+"&t="+t;
}
</script>
<div class="container-fluid ">
	<div class="row  d-flex justify-content-around">
		<div class="col-md-10">
			<div class="row filrow font-weight-bold">
				
					<div class="col-md-2"><br />Employee Status:</div>

			      	<div class="col-md-3">Select Employee
					<select id="empid" class="form-control" onChange="FunEmp(this.value,'E')" style="width:100%;">
					 <?php $semp=mysql_query("SELECT e.EmployeeID,EmpCode,Fname,Sname,Lname,DepartmentCode FROM `hrm_employee` e inner join hrm_employee_general g on e.EmployeeID=g.EmployeeID inner join hrm_department d on g.DepartmentId=d.DepartmentId where e.EmpStatus!='De' AND e.CompanyId=".$_SESSION['CompanyId']." order by e.EmpCode ASC",$con2); while($remp=mysql_fetch_assoc($semp)){ ?>
					 <option value="<?=$remp['EmployeeID']?>" <?php if($_REQUEST['ei']==$remp['EmployeeID']){echo 'selected';} ?>><?=$remp['EmpCode'].' / '.$remp['Fname'].' '.$remp['Sname'].' '.$remp['Lname'].' / '.$remp['DepartmentCode']; ?></option>
					 <?php } ?>
					 <option value="0" <?php if($_REQUEST['ei']==0){echo 'selected';} ?>>All Employee</option>
					</select>
					
					</div>
			      	
					
			      	<div class="col-md-5">
					<?php if($_REQUEST['ei']>0){ $u=mysql_query("SELECT ReportingName,ReportingContactNo FROM hrm_employee_general where EmployeeID=".$_REQUEST['ei'],$con2); $un=mysql_fetch_assoc($u); ?>
					<b>Reporting</b><br><?php echo $un['ReportingName'].' - '.$un['ReportingContactNo']; } ?>
					
						<!--<button class="form-control btn-primary" onclick="filterTD()">Search</button>-->
			      	</div>
		      	
	      	</div>
	      	
	      	<div class="row filrow font-weight-bold">
				<div class="table-responsive d-flex justify-content-center align-items-center" id="EmpDiv">
<?php if($_REQUEST['ei']>0){ ?>				
					<table class="table shadow table-responsive" style="width:100%;" id="ReportTable">
					  <thead class="thead-dark">
					    <tr>
						  <th scope="col" style="width:100px;text-align:center;vertical-align:middle;" rowspan="2">Claim<br/>Month</th>
						  <th scope="col" style="width:100px;text-align:center;vertical-align:middle;" rowspan="2">Total<br/>Claim</th>
					      <th scope="col" colspan="5" style="text-align:center;">Pending For</th>
						  <th scope="col" rowspan="2" style="text-align:center;">View<br>Home</th>
						  <th scope="col" rowspan="2" style="text-align:center;">Return<br>Claim</th>
					    </tr>
					    <tr>  
					      <th scope="col" style="width:100px;text-align:center;">Filling</th>
						  <th scope="col" style="width:100px;text-align:center;">Claimer<br/>Approval</th>
						  <th scope="col" style="width:100px;text-align:center;">Verify<br/>Approval</th>
						  <th scope="col" style="width:100px;text-align:center;">Reporting<br/>Approval</th>
						  <th scope="col" style="width:100px;text-align:center;">Finance<br/>Approval</th>
					    </tr>
					  </thead>					  
					  <tbody>
					  
					  <tr style="background-color:#FFFF9F; height:30px; font-weight:bold;">
                       <td style="text-align:center;vertical-align:middle;">Draft</td>       

<?php $stupDrf=mysql_query("select count(*) as TotupDrf from `y".$_SESSION['FYearId']."_expenseclaims` where CrBy=".$_REQUEST['ei']." AND ClaimMonth=0 AND ClaimStatus!='Deactivate' and AttachTo=0"); $rtupDrf=mysql_fetch_assoc($stupDrf); ?>						
			<td style="text-align:center;vertical-align:middle;"><?php if($rtupDrf['TotupDrf']>0){echo $rtupDrf['TotupDrf']; }?></td>
			           <td colspan="7" style="background-color:#FFFFFF;">&nbsp;</td>
			          </tr>
					  
					  
					  
					  <?php $selym=mysql_query("select Month,Status from `y".$_SESSION['FYearId']."_monthexpensefinal` where EmployeeID=".$_REQUEST['ei']." group by Month order by Month ASC",$con); $sn=1; while($resym=mysql_fetch_assoc($selym)){ ?>
					  
					  	
					   <tr style="background-color:#FFFF9F; height:30px; font-weight:bold;">

            <td style="text-align:center;vertical-align:middle;"><?php echo date("M",strtotime(date("Y-".$resym['Month']."-25"))); ?></td>       

<?php $stupT=mysql_query("select count(*) as TotupT from `y".$_SESSION['FYearId']."_expenseclaims` where CrBy=".$_REQUEST['ei']." AND ClaimMonth=".$resym['Month']." AND ClaimStatus!='Deactivate' "); $rtupT=mysql_fetch_assoc($stupT); ?>						
			<td style="text-align:center;vertical-align:middle;"><?php if($rtupT['TotupT']>0){echo $rtupT['TotupT']; }?></td>

<?php $stupTF=mysql_query("select count(*) as TotupTF from `y".$_SESSION['FYearId']."_expenseclaims` where FilledBy=0 AND (FilledDate='0000-00-00' OR FilledDate='1970-01-01') AND CrBy=".$_REQUEST['ei']." AND ClaimMonth=".$resym['Month']." AND ClaimStatus!='Deactivate'"); $rtupTF=mysql_fetch_assoc($stupTF); ?>	
            <td style="text-align:center;vertical-align:middle;"><span <?php /*?>onclick="FUnDetails('TF',<?=$resym['Month']?>)"<?php */?> style="color:#000099;cursor:pointer;"><u><?php if($rtupTF['TotupTF']>0){echo $rtupTF['TotupTF']; }?></u></span></td>
			
<!--------------->
<?php $stupTFa=mysql_query("select count(*) as TotupTFa from `y".$_SESSION['FYearId']."_expenseclaims` where FilledBy>0 AND FilledDate!='0000-00-00' AND FilledDate!='1970-01-01' AND CrBy=".$_REQUEST['ei']." AND ClaimMonth=".$resym['Month']." AND FilledOkay=0 AND ClaimStatus!='Deactivate'"); $rtupTFa=mysql_fetch_assoc($stupTFa); ?>	
            <td style="text-align:center;vertical-align:middle;"><span onclick="FUnDetails('TFa',<?=$resym['Month']?>)" style="color:#000099;cursor:pointer;"><u><?php if($rtupTFa['TotupTFa']>0){echo $rtupTFa['TotupTFa']; }?></u></span></td>
<!--------------->			
						
<?php $stupTV=mysql_query("select count(*) as TotupTV from `y".$_SESSION['FYearId']."_expenseclaims` where FilledBy>0 AND FilledDate!='0000-00-00' AND FilledDate!='1970-01-01' AND VerifyBy=0 AND (VerifyDate='0000-00-00' OR VerifyDate='1970-01-01') AND CrBy=".$_REQUEST['ei']." AND ClaimMonth=".$resym['Month']." AND FilledOkay=1 AND ClaimStatus!='Deactivate'"); $rtupTV=mysql_fetch_assoc($stupTV); ?>	
            <td style="text-align:center;vertical-align:middle;"><span onclick="FUnDetails('TV',<?=$resym['Month']?>)" style="color:#000099;cursor:pointer;"><u><?php if($rtupTV['TotupTV']>0){echo $rtupTV['TotupTV']; }?></u></span></td>
			
<?php $stupTA=mysql_query("select count(*) as TotupTA from `y".$_SESSION['FYearId']."_expenseclaims` where VerifyBy>0 AND VerifyDate!='0000-00-00' AND VerifyDate!='1970-01-01' AND ApprBy=0 AND (ApprDate='0000-00-00' OR ApprDate='1970-01-01') AND CrBy=".$_REQUEST['ei']." AND ClaimMonth=".$resym['Month']." AND ClaimStatus!='Deactivate'"); $rtupTA=mysql_fetch_assoc($stupTA); ?>	
            <td style="text-align:center;vertical-align:middle;"><span onclick="FUnDetails('TA',<?=$resym['Month']?>)" style="color:#000099;cursor:pointer;"><u><?php if($rtupTA['TotupTA']>0){echo $rtupTA['TotupTA']; }?></u></span></td>	
			
<?php $stupTFn=mysql_query("select count(*) as TotupTFn from `y".$_SESSION['FYearId']."_expenseclaims` where ApprBy>0 AND ApprDate!='0000-00-00' AND ApprDate!='1970-01-01' AND FinancedBy=0 AND (FinancedDate='0000-00-00' OR FinancedDate='1970-01-01') AND CrBy=".$_REQUEST['ei']." AND ClaimMonth=".$resym['Month']." AND ClaimStatus!='Deactivate'"); $rtupTFn=mysql_fetch_assoc($stupTFn); ?>	
            <td style="text-align:center;vertical-align:middle;"><span onclick="FUnDetails('TFn',<?=$resym['Month']?>)" style="color:#000099;cursor:pointer;"><u><?php if($rtupTFn['TotupTFn']>0){echo $rtupTFn['TotupTFn']; }?></u></span></td>
			
			<td style="text-align:center;vertical-align:middle;"><button type="button" class="btn btn-sm btn-success" onclick="submittodetails('<?=$resym['Month']?>','<?=$_REQUEST['ei']?>')">E-Home</button></td>
			
			<td style="text-align:center;vertical-align:middle;"><button type="button" class="btn btn-sm btn-warning" onclick="submittoreturn('<?=$resym['Month']?>','<?=$_REQUEST['ei']?>')" <?php if($rtupTA['TotupTA']>0 OR $rtupTFn['TotupTFn']>0){echo 'disabled';}?>>Return</button></td>		
			
			
			
					    </tr>

<script type="text/javascript">
function FUnDetails(v,m)
{
 document.getElementById("DivTF_"+m).style.display='none'; document.getElementById("DivTFa_"+m).style.display='none';
 document.getElementById("DivTV_"+m).style.display='none'; document.getElementById("DivTA_"+m).style.display='none';
 document.getElementById("DivTFn_"+m).style.display='none';
 document.getElementById("Div"+v+"_"+m).style.display='block';
}

function FUnClose(v,m)
{
 document.getElementById("Div"+v+"_"+m).style.display='none';
}


function submittodetails(month,crby){
		window.open("EvhomeDetails.php?view=verifier&mnt="+month+"&ei="+crby,"Home","menubar=no,scrollbars=yes,resizable=no,directories=no,width=800,height=500"); 
	}
	
function submittoreturn(month,crby){
		if (confirm('Are you sure to return this employee month claim?')){
			$.post("homeajax.php",{act:"submittoreturn",month:month,crby:crby},function(data){
				if(data.includes('Returned')){
					alert('Returned to Approver Successfully');
					location.reload();
				}
			});
		}
	}	
	
function DeleExpC(exid,yi)
{

   if (confirm('Are you sure, you want to delete claim?')){
			$.post("homeajax.php",{act:"DeleteExpId",exid:exid,yi:yi},function(data){ 
				if(data.includes('Done')){
					alert('Claim Deleted Successfully');
					location.reload();
				}
			});
		}

}	
	
</script>

					   <tr>
					    <td colspan="9" style="vertical-align:top;">
<style>.bhd{text-align:center;color:#FFF;font-size:14px;}</style>						
						<div id="DivTF_<?=$resym['Month']?>" style="display:none;">
		<table class="table shadow table-responsive" style="width:100%;max-height:400px;overflow:scroll;" id="ReportTable">
					      <tr style="background-color:#5CB900;height:30px;font-weight:bold;">
						   <th scope="col" class="bhd" style="width:300px;">Pending Filling Details</th>
						   <th scope="col" class="bhd" style="width:100px;">No Of Claim</th>
					     </tr>
						 <?php $stupTF=mysql_query("select CrBy,Count(*) as TotupTF from `y".$_SESSION['FYearId']."_expenseclaims` where FilledBy=0 AND (FilledDate='0000-00-00' OR FilledDate='1970-01-01') AND CrBy=".$_REQUEST['ei']." AND ClaimMonth=".$resym['Month']." AND ClaimStatus!='Deactivate' group by CrBy ASC"); $no=1; while($rtupTF=mysql_fetch_assoc($stupTF)){ ?>
					    <tr style="height:22px;">
					      <td><?php $u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$rtupTF['CrBy'],$con2); $un=mysql_fetch_assoc($u); echo $un['Fname'].' '.$un['Sname'].' '.$un['Lname']; ?>	</td>
                          <td style="text-align:center;"><?php if($rtupTF['TotupTF']>0){echo $rtupTF['TotupTF']; }?></td>					  
						</tr>
                        <?php $no++; } ?>						
					   </table>
						</div>
						
						
						<div id="DivTFa_<?=$resym['Month']?>" style="display:none;">
		<table class="table shadow table-responsive" style="width:100%;max-height:400px;overflow:scroll;" id="ReportTable">
					      <tr style="background-color:#5CB900; height:30px;font-weight:bold;">
						   <th scope="col" class="bhd" style="width:50px;text-align:center;">Sn</th>
						   <th scope="col" class="bhd" style="width:200px;">Claim Type</th>                           
						   <th scope="col" class="bhd" style="width:100px;">Bill Date</th>
						   <th scope="col" class="bhd" style="width:100px;">Filled Amt</th>
						   <?php if($_SESSION['EmpRole']=='S'){?>
						   <th scope="col" class="bhd" style="width:50px;">Delete</th>
						   <?php } ?>
						   <th scope="col" class="bhd" style="width:100px;"><span style="color:#FFFFFF;cursor:pointer;" onclick="FUnClose('TFa',<?=$resym['Month']?>)"><u>Close</u></span>
						   </th>
						   
					     </tr>
						 <?php $stupTFa=mysql_query("select ExpId,ClaimName,BillDate,FilledTAmt from `y".$_SESSION['FYearId']."_expenseclaims` c inner join claimtype ct on c.ClaimId=ct.ClaimId where c.FilledBy>0 AND c.FilledDate!='0000-00-00' AND c.FilledDate!='1970-01-01' AND c.CrBy=".$_REQUEST['ei']." AND c.ClaimMonth=".$resym['Month']." AND c.FilledOkay=0 AND c.ClaimStatus!='Deactivate' order by c.BillDate ASC"); $no=1; while($rtupTFa=mysql_fetch_assoc($stupTFa)){ ?>
					    <tr style="height:22px;background-color:<?php //if($no%2==0){echo '#FFF';} ?>;">
					      <td><?php echo $no; ?></td>
						  <td><?php echo $rtupTFa['ClaimName']; ?>	</td>
						  <td style="text-align:center;"><?php echo date("d-m-Y",strtotime($rtupTFa['BillDate']));?></td>	
                          <td style="text-align:center;"><?php echo floatval($rtupTFa['FilledTAmt']);?></td>					
						  <?php if($_SESSION['EmpRole']=='S'){?> 
						  <td style="text-align:center;cursor:pointer;"><img src="images/delete.png" onclick="DeleExpC(<?=$rtupTFa['ExpId'].','.$_SESSION['FYearId']?>)"/></td>
						  <?php } ?>
						</tr>
                        <?php $no++; }?>
						<?php $stupTFaT=mysql_query("select sum(FilledTAmt) as sFilledTAmt from `y".$_SESSION['FYearId']."_expenseclaims` c inner join claimtype ct on c.ClaimId=ct.ClaimId where c.FilledBy>0 AND c.FilledDate!='0000-00-00' AND c.FilledDate!='1970-01-01' AND c.CrBy=".$_REQUEST['ei']." AND c.ClaimMonth=".$resym['Month']." AND c.FilledOkay=0 AND c.ClaimStatus!='Deactivate' order by c.BillDate ASC"); $rtupTFaT=mysql_fetch_assoc($stupTFaT); ?>	
						<tr style="height:22px;">
					      <td colspan="3" style="text-align:right;"><b>Total</b>&nbsp;</td>	
                        <td style="text-align:center;font-weight:bold;"><?php echo floatval($rtupTFaT['sFilledTAmt']);?></td>					
						</tr>
											
					   </table>
						</div>
						<div id="DivTV_<?=$resym['Month']?>" style="display:none;">
		<table class="table shadow table-responsive" style="width:100%;max-height:400px;overflow:scroll;" id="ReportTable">
					      <tr style="background-color:#5CB900; height:30px; font-weight:bold;">
						   <th scope="col" class="bhd" style="width:50px;text-align:center;">Sn</th>
						   <th scope="col" class="bhd" style="width:200px;">Claim Type</th>                           
						   <th scope="col" class="bhd" style="width:100px;">Bill Date</th>
						   <th scope="col" class="bhd" style="width:100px;">Filled Amt</th>
						   <?php if($_SESSION['EmpRole']=='S'){?>
						   <th scope="col" class="bhd" style="width:50px;">Delete</th>
						   <?php } ?>
						   <th scope="col" class="bhd" style="width:100px;"><span style="color:#FFFFFF;cursor:pointer;" onclick="FUnClose('TV',<?=$resym['Month']?>)"><u>Close</u></span>
					     </tr>
						 <?php $stupTV=mysql_query("select ExpId,ClaimName,BillDate,FilledTAmt from `y".$_SESSION['FYearId']."_expenseclaims` c inner join claimtype ct on c.ClaimId=ct.ClaimId where FilledBy>0 AND FilledDate!='0000-00-00' AND FilledDate!='1970-01-01' AND VerifyBy=0 AND (VerifyDate='0000-00-00' OR VerifyDate='1970-01-01') AND CrBy=".$_REQUEST['ei']." AND ClaimMonth=".$resym['Month']." AND c.ClaimStatus!='Deactivate' AND FilledOkay=1 order by c.BillDate ASC"); $no2=1; while($rtupTV=mysql_fetch_assoc($stupTV)){ ?>
					    <tr style="height:22px;">
						  <td><?php echo $no2; ?></td>
					      <td><?php echo $rtupTV['ClaimName']; ?></td>
						  <td style="text-align:center;"><?php echo date("d-m-Y",strtotime($rtupTV['BillDate']));?></td>	
                          <td style="text-align:center;"><?php echo floatval($rtupTV['FilledTAmt']);?></td>					
						  <?php if($_SESSION['EmpRole']=='S'){?> 
						  <td style="text-align:center;cursor:pointer;"><img src="images/delete.png" onclick="DeleExpC(<?=$rtupTV['ExpId'].','.$_SESSION['FYearId']?>)"/></td>
						  <?php } ?>
						</tr>
                        <?php $no2++; } ?>	
						<?php $stupTVT=mysql_query("select sum(FilledTAmt) as sFilledTAmt from `y".$_SESSION['FYearId']."_expenseclaims` c inner join claimtype ct on c.ClaimId=ct.ClaimId where FilledBy>0 AND FilledDate!='0000-00-00' AND FilledDate!='1970-01-01' AND VerifyBy=0 AND (VerifyDate='0000-00-00' OR VerifyDate='1970-01-01') AND CrBy=".$_REQUEST['ei']." AND ClaimMonth=".$resym['Month']." AND c.ClaimStatus!='Deactivate' AND FilledOkay=1 order by c.BillDate ASC"); $rtupTVT=mysql_fetch_assoc($stupTVT); ?>
						 <tr style="height:22px;">
					      <td colspan="3" style="text-align:right;"><b>Total</b>&nbsp;</td>	
                         <td style="text-align:center;font-weight:bold;"><?php echo floatval($rtupTVT['sFilledTAmt']);?></td>					
						</tr>					
					    </table>
						</div>
						<div id="DivTA_<?=$resym['Month']?>" style="display:none;">
		<table class="table shadow table-responsive" style="width:100%;max-height:400px;overflow:scroll;" id="ReportTable">
					      <tr style="background-color:#5CB900; height:30px; font-weight:bold;">
						   <th scope="col" class="bhd" style="width:50px;text-align:center;">Sn</th>
						   <th scope="col" class="bhd" style="width:200px;">Claim Type</th>                           
						   <th scope="col" class="bhd" style="width:100px;">Bill Date</th>
						   <th scope="col" class="bhd" style="width:100px;">Filled Amt</th>
						   <th scope="col" class="bhd" style="width:100px;">Verified Amt</th>
						   <th scope="col" class="bhd" style="width:100px;"><span style="color:#FFFFFF;cursor:pointer;" onclick="FUnClose('TA',<?=$resym['Month']?>)"><u>Close</u></span>
					     </tr>
						 <?php $stupTA=mysql_query("select ClaimName,BillDate,FilledTAmt,VerifyTAmt from `y".$_SESSION['FYearId']."_expenseclaims` c inner join claimtype ct on c.ClaimId=ct.ClaimId where VerifyBy>0 AND VerifyDate!='0000-00-00' AND VerifyDate!='1970-01-01' AND ApprBy=0 AND (ApprDate='0000-00-00' OR ApprDate='1970-01-01') AND CrBy=".$_REQUEST['ei']." AND ClaimMonth=".$resym['Month']." AND c.ClaimStatus!='Deactivate' order by c.BillDate ASC"); $no3=1; while($rtupTA=mysql_fetch_assoc($stupTA)){ ?>	
					    <tr style="height:22px;">
						  <td><?php echo $no3; ?></td>
					      <td><?php echo $rtupTA['ClaimName']; ?>	</td>
						  <td style="text-align:center;"><?php echo date("d-m-Y",strtotime($rtupTA['BillDate']));?></td>	
                          <td style="text-align:center;"><?php echo floatval($rtupTA['FilledTAmt']);?></td>
						  <td style="text-align:center;"><?php echo floatval($rtupTA['VerifyTAmt']);?></td>					
						</tr>
                        <?php $no3++; } ?>
						<?php $stupTAT=mysql_query("select sum(FilledTAmt) as sFilledTAmt,sum(VerifyTAmt) as sVerifyTAmt from `y".$_SESSION['FYearId']."_expenseclaims` c inner join claimtype ct on c.ClaimId=ct.ClaimId where VerifyBy>0 AND VerifyDate!='0000-00-00' AND VerifyDate!='1970-01-01' AND ApprBy=0 AND (ApprDate='0000-00-00' OR ApprDate='1970-01-01') AND CrBy=".$_REQUEST['ei']." AND ClaimMonth=".$resym['Month']." AND c.ClaimStatus!='Deactivate' order by c.BillDate ASC"); $rtupTAT=mysql_fetch_assoc($stupTAT); ?>
						 <tr style="height:22px;">
					      <td colspan="3" style="text-align:right;"><b>Total</b>&nbsp;</td>	
                         <td style="text-align:center;font-weight:bold;"><?php echo floatval($rtupTAT['sFilledTAmt']);?></td>	
						 <td style="text-align:center;font-weight:bold;"><?php echo floatval($rtupTAT['sVerifyTAmt']);?></td>					
						</tr>						
					    </table>
						</div>
						<div id="DivTFn_<?=$resym['Month']?>" style="display:none;">
		<table class="table shadow table-responsive" style="width:100%;max-height:400px;overflow:scroll;" id="ReportTable">
					      <tr style="background-color:#5CB900; height:30px; font-weight:bold;">
						   <th scope="col" class="bhd" style="width:50px;text-align:center;">Sn</th> 
						   <th scope="col" class="bhd" style="width:200px;">Claim Type</th>                           
						   <th scope="col" class="bhd" style="width:100px;">Bill Date</th>
						   <th scope="col" class="bhd" style="width:100px;">Filled Amt</th>
						   <th scope="col" class="bhd" style="width:100px;">Verified Amt</th>
						   <th scope="col" class="bhd" style="width:100px;">Approval Amt</th>
						   <th scope="col" class="bhd" style="width:100px;"><span style="color:#FFFFFF;cursor:pointer;" onclick="FUnClose('TFn',<?=$resym['Month']?>)"><u>Close</u></span>
					     </tr>
						 <?php $stupTFn=mysql_query("select ClaimName,BillDate,FilledTAmt,VerifyTAmt,ApprTAmt from `y".$_SESSION['FYearId']."_expenseclaims` c inner join claimtype ct on c.ClaimId=ct.ClaimId where ApprBy>0 AND ApprDate!='0000-00-00' AND ApprDate!='1970-01-01' AND FinancedBy=0 AND (FinancedDate='0000-00-00' OR FinancedDate='1970-01-01') AND CrBy=".$_REQUEST['ei']." AND c.ClaimStatus!='Deactivate' AND ClaimMonth=".$resym['Month']." order by c.BillDate ASC"); $no4=1; while($rtupTFn=mysql_fetch_assoc($stupTFn)){ ?>
					    <tr style="height:22px;">
						  <td><?php echo $no4; ?></td>
					      <td><?php echo $rtupTFn['ClaimName']; ?>	</td>
						  <td style="text-align:center;"><?php echo date("d-m-Y",strtotime($rtupTFn['BillDate']));?></td>	
                          <td style="text-align:center;"><?php echo floatval($rtupTFn['FilledTAmt']);?></td>
						  <td style="text-align:center;"><?php echo floatval($rtupTFn['VerifyTAmt']);?></td>	
						  <td style="text-align:center;"><?php echo floatval($rtupTFn['ApprTAmt']);?></td>						
						</tr>
                        <?php $no4++; } ?>	
						<?php $stupTFnT=mysql_query("select sum(FilledTAmt) as sFilledTAmt,sum(VerifyTAmt) as sVerifyTAmt,sum(ApprTAmt) as sApprTAmt from `y".$_SESSION['FYearId']."_expenseclaims` c inner join claimtype ct on c.ClaimId=ct.ClaimId where ApprBy>0 AND ApprDate!='0000-00-00' AND ApprDate!='1970-01-01' AND FinancedBy=0 AND (FinancedDate='0000-00-00' OR FinancedDate='1970-01-01') AND CrBy=".$_REQUEST['ei']." AND c.ClaimStatus!='Deactivate' AND ClaimMonth=".$resym['Month']." order by c.BillDate ASC"); $rtupTFnT=mysql_fetch_assoc($stupTFnT); ?>
						 <tr style="height:22px;">
					      <td colspan="3" style="text-align:right;"><b>Total</b>&nbsp;</td>	
                        <td style="text-align:center;font-weight:bold;"><?php echo floatval($rtupTFnT['sFilledTAmt']);?></td>	
						<td style="text-align:center;font-weight:bold;"><?php echo floatval($rtupTFnT['sVerifyTAmt']);?></td>	
						<td style="text-align:center;font-weight:bold;"><?php echo floatval($rtupTFnT['sApprTAmt']);?></td>					
						</tr>					
					    </table>
						</div>
						
						</td>
					   </tr> 
					   <?php $sn++; } ?>
					   </tbody>	
				   
					</table>	
					
<?php } // if($_REQUEST['ei']>0)?>
			   </div>
			   
			   <div>
			       <?php if($_REQUEST['ei']>0){ ?>
			       <table>
					 <tr>
					  <td>
					   <div class="col-md-12">
					   <?php  
					    $ut=mysql_query("SELECT e.EmployeeID,EmpCode,Fname,Sname,Lname FROM hrm_employee_general g inner join hrm_employee e on g.EmployeeID=e.EmployeeID where g.RepEmployeeID=".$_REQUEST['ei']." AND e.EmpStatus='A' group by e.EmployeeID order by EmpCode",$con2); $rowtn=mysql_num_rows($ut); 
					    if($rowtn>0)
					    {
					     echo '<b>Team</b> : '; $nt=1;
					     while($utn=mysql_fetch_assoc($ut))
						 { 
						  echo '<span onclick="FunTmDetail('.$utn['EmployeeID'].')" style="cursor:pointer;"><b><u>'.$utn['EmpCode']."</b>-".ucwords(strtolower($utn['Fname'].' '.$utn['Sname'].' '.$utn['Lname'])).'</u> &nbsp; &nbsp;</span>';  if($nt==5 OR $nt==10 OR $nt==15 OR $nt==20 OR $nt==25 OR $nt==30){echo '<br>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ';}
						 $nt++;} 
			      	    } //if($rowtn>0)
					   
					   ?>
					   </div>
					 </tr>
					</table>
					
					 <script type="text/javascript">
                     function FunTmDetail(ei)
                     {  
					   window.open("employeeststeam.php?ei="+ei, '_blank'); window.focus();
                     }
                     </script>
			        <?php } ?>
			   
			   </div>
			   					
			</div>
		</div>
		
		
	</div>
	
</div>

<?php //echo $q;?>


<?php
include "footer.php";
?>
<script type="text/javascript" src="js/reports.js"></script>










