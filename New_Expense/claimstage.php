<?php include "header.php"; ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>
<style type="text/css">
div.dataTables_wrapper div.dataTables_length select{ width:100; }
div.table-responsive>div.dataTables_wrapper>div.row { background-color: #ccf5ff; padding: 10px; width:100%; }
</style>
<script type="text/javascript">
function filterTD()
{ 
 var f = $('#fromdtfr').val();
 var t = $('#todtfr').val();
 window.location="claimstage.php?f="+f+"&t="+t;
}
</script>
<div class="container-fluid ">
	<div class="row  d-flex justify-content-around">
		<div class="col-md-10">
			<div class="row filrow font-weight-bold">
				
					<div class="col-md-2"><br />Pending Stage:</div>

<?php if(!isset($_REQUEST['f']) && !isset($_REQUEST['t']))
      {
	   if(date("m")==01 OR date("m")==02 OR date("m")==03){ $yf=date("Y")-1; }else{ $yf=date("Y"); } 
	   $_REQUEST['f']= date("01-04-".$yf); $_REQUEST['t']= date("d-m-Y"); 
	  } 
?>
			      	<div class="col-md-2">From<input id="fromdtfr" class="form-control" value="<?php if(isset($_REQUEST['f'])){echo $_REQUEST['f'];}?>"></div>
			      	
					<div class="col-md-2">To<input id="todtfr" class="form-control" value="<?php if(isset($_REQUEST['t'])){echo $_REQUEST['t'];}?>"></div>
					
			      	<div class="col-md-2"><br>
						<button class="form-control btn-primary" onclick="filterTD()">Search</button>
			      	</div>
		      	
	      	</div>
	      	
	      	<div class="row filrow font-weight-bold">
				<div class="table-responsive d-flex justify-content-center align-items-center">
					<table class="table shadow table-responsive" style="width:100%;" id="ReportTable">
					  <thead class="thead-dark">
					    <tr>
						  <th scope="col" style="width:100px;text-align:center;vertical-align:middle;" rowspan="2">Total<br/>Claim</th>
					      <th scope="col" colspan="5" style="text-align:center;">Pending For</th>
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

<?php $stupT=mysql_query("select count(*) as TotupT from `y".$_SESSION['FYearId']."_expenseclaims` where CrDate between '".date('Y-m-d',strtotime($_REQUEST['f']))."' AND '".date('Y-m-d',strtotime($_REQUEST['t']))."' AND ClaimStatus!='Deactivate' "); $rtupT=mysql_fetch_assoc($stupT); ?>						    
			<td style="text-align:center;vertical-align:middle;"><?php if($rtupT['TotupT']>0){echo $rtupT['TotupT']; }?></td>

<?php $stupTF=mysql_query("select count(*) as TotupTF from `y".$_SESSION['FYearId']."_expenseclaims` where CrDate between '".date('Y-m-d',strtotime($_REQUEST['f']))."' AND '".date('Y-m-d',strtotime($_REQUEST['t']))."' AND FilledBy=0 AND (FilledDate='0000-00-00' OR FilledDate='1970-01-01') AND ClaimStatus!='Deactivate'"); $rtupTF=mysql_fetch_assoc($stupTF); ?>	
            <td style="text-align:center;vertical-align:middle;"><span onclick="FUnDetails('TF')" style="color:#000099;cursor:pointer;"><u><?php if($rtupTF['TotupTF']>0){echo $rtupTF['TotupTF']; }?></u></span></td>
			
<!--------------->
<?php $stupTFa=mysql_query("select count(*) as TotupTFa from `y".$_SESSION['FYearId']."_expenseclaims` where CrDate between '".date('Y-m-d',strtotime($_REQUEST['f']))."' AND '".date('Y-m-d',strtotime($_REQUEST['t']))."' AND FilledBy>0 AND FilledDate!='0000-00-00' AND FilledDate!='1970-01-01' AND ClaimStatus!='Deactivate' AND FilledOkay=0"); $rtupTFa=mysql_fetch_assoc($stupTFa); ?>	
            <td style="text-align:center;vertical-align:middle;"><span onclick="FUnDetails('TFa')" style="color:#000099;cursor:pointer;"><u><?php if($rtupTFa['TotupTFa']>0){echo $rtupTFa['TotupTFa']; }?></u></span></td>
<!--------------->			

<?php $stupTV=mysql_query("select count(*) as TotupTV from `y".$_SESSION['FYearId']."_expenseclaims` where CrDate between '".date('Y-m-d',strtotime($_REQUEST['f']))."' AND '".date('Y-m-d',strtotime($_REQUEST['t']))."' AND FilledBy>0 AND FilledDate!='0000-00-00' AND FilledDate!='1970-01-01' AND VerifyBy=0 AND (VerifyDate='0000-00-00' OR VerifyDate='1970-01-01') AND ClaimStatus!='Deactivate' AND FilledOkay=1"); $rtupTV=mysql_fetch_assoc($stupTV); ?>	
            <td style="text-align:center;vertical-align:middle;"><span onclick="FUnDetails('TV')" style="color:#000099;cursor:pointer;"><u><?php if($rtupTV['TotupTV']>0){echo $rtupTV['TotupTV']; }?></u></span></td>
			
<?php $stupTA=mysql_query("select count(*) as TotupTA from `y".$_SESSION['FYearId']."_expenseclaims` where CrDate between '".date('Y-m-d',strtotime($_REQUEST['f']))."' AND '".date('Y-m-d',strtotime($_REQUEST['t']))."' AND VerifyBy>0 AND VerifyDate!='0000-00-00' AND VerifyDate!='1970-01-01' AND ApprBy=0 AND (ApprDate='0000-00-00' OR ApprDate='1970-01-01') AND ClaimStatus!='Deactivate'"); $rtupTA=mysql_fetch_assoc($stupTA); ?>	
            <td style="text-align:center;vertical-align:middle;"><span onclick="FUnDetails('TA')" style="color:#000099;cursor:pointer;"><u><?php if($rtupTA['TotupTA']>0){echo $rtupTA['TotupTA']; }?></u></span></td>	
			
<?php $stupTFn=mysql_query("select count(*) as TotupTFn from `y".$_SESSION['FYearId']."_expenseclaims` where CrDate between '".date('Y-m-d',strtotime($_REQUEST['f']))."' AND '".date('Y-m-d',strtotime($_REQUEST['t']))."' AND ApprBy>0 AND ApprDate!='0000-00-00' AND ApprDate!='1970-01-01' AND FinancedBy=0 AND (FinancedDate='0000-00-00' OR FinancedDate='1970-01-01') AND ClaimStatus!='Deactivate'"); $rtupTFn=mysql_fetch_assoc($stupTFn); ?>	
            <td style="text-align:center;vertical-align:middle;"><span onclick="FUnDetails('TFn')" style="color:#000099;cursor:pointer;"><u><?php if($rtupTFn['TotupTFn']>0){echo $rtupTFn['TotupTFn']; }?></u></span></td>		
			
					    </tr>

<script type="text/javascript">
function FUnDetails(v)
{
 document.getElementById("DivTF").style.display='none'; document.getElementById("DivTFa").style.display='none';
 document.getElementById("DivTV").style.display='none'; document.getElementById("DivTA").style.display='none';
 document.getElementById("DivTFn").style.display='none';
 document.getElementById("Div"+v).style.display='block';
}
</script>

					   <tr>
					    <td colspan="6" style="vertical-align:top;">
<style>.bhd{text-align:center;color:#FFF;font-size:14px;}</style>						
						<div id="DivTF" style="display:none;">
		<table class="table shadow table-responsive" style="width:100%;max-height:400px;overflow:scroll;" id="ReportTable">
					      <tr style="background-color:#5CB900;height:30px;font-weight:bold;">
						   <th scope="col" class="bhd" style="width:50px;text-align:center;">Sn</th>
						   <th scope="col" class="bhd" style="width:250px;">Pending Filling Details</th>
						   <th scope="col" class="bhd" style="width:250px;">Reporting</th>
						   <th scope="col" class="bhd" style="width:100px;">No Of Claim</th>
					     </tr>
						 <?php $stupTF=mysql_query("select CrBy,Count(*) as TotupTF from `y".$_SESSION['FYearId']."_expenseclaims` where CrDate between '".date('Y-m-d',strtotime($_REQUEST['f']))."' AND '".date('Y-m-d',strtotime($_REQUEST['t']))."' AND FilledBy=0 AND (FilledDate='0000-00-00' OR FilledDate='1970-01-01') AND ClaimStatus!='Deactivate' group by CrBy ASC"); $no=1; while($rtupTF=mysql_fetch_assoc($stupTF)){ ?>
					    <tr style="height:22px;">
						  <td style="text-align:center;"><?php echo $no; ?></td>
					      <td><?php $u=mysql_query("SELECT Fname,Sname,Lname,ReportingName,ReportingContactNo FROM `hrm_employee` e inner join hrm_employee_general g on e.EmployeeID=g.EmployeeID where e.EmployeeID=".$rtupTF['CrBy'],$con2); $un=mysql_fetch_assoc($u); echo $un['Fname'].' '.$un['Sname'].' '.$un['Lname']; ?>	</td>
						  <td><?php echo $un['ReportingName'].' - '.$un['ReportingContactNo']; ?></td>
                          <td style="text-align:center;"><?php if($rtupTF['TotupTF']>0){echo $rtupTF['TotupTF']; }?></td>					  
						</tr>
                        <?php $no++; } ?>						
					   </table>
						</div>
						<div id="DivTFa" style="display:none;">
		<table class="table shadow table-responsive" style="width:100%;max-height:400px;overflow:scroll;" id="ReportTable">
					      <tr style="background-color:#5CB900; height:30px; font-weight:bold;">
						   <th scope="col" class="bhd" style="width:50px;text-align:center;">Sn</th>
						   <th scope="col" class="bhd" style="width:300px;">Pending Claimer Approval Details</th>
						   <th scope="col" class="bhd" style="width:250px;">Reporting</th>
						   <th scope="col" class="bhd" style="width:100px;">Month</th>
						   <th scope="col" class="bhd" style="width:100px;">No Of Claim</th>
					     </tr>
						 <?php $stupTFa=mysql_query("select CrBy,ClaimMonth,count(*) as TotupTFa from `y".$_SESSION['FYearId']."_expenseclaims` where CrDate between '".date('Y-m-d',strtotime($_REQUEST['f']))."' AND '".date('Y-m-d',strtotime($_REQUEST['t']))."' AND FilledBy>0 AND FilledDate!='0000-00-00' AND FilledDate!='1970-01-01' AND ClaimStatus!='Deactivate' AND FilledOkay=0 group by CrBy,ClaimMonth ASC"); $no2=1; while($rtupTFa=mysql_fetch_assoc($stupTFa)){ ?>
					    <tr style="height:22px;background-color:<?php //if($no%2==0){echo '#FFF';} ?>;">
					      <td style="text-align:center;"><?php echo $no2; ?></td>
						  <td><?php $u=mysql_query("SELECT Fname,Sname,Lname,ReportingName,ReportingContactNo FROM `hrm_employee` e inner join hrm_employee_general g on e.EmployeeID=g.EmployeeID where e.EmployeeID=".$rtupTFa['CrBy'],$con2); $un=mysql_fetch_assoc($u); echo $un['Fname'].' '.$un['Sname'].' '.$un['Lname']; ?>	</td>
						  <td><?php echo $un['ReportingName'].' - '.$un['ReportingContactNo']; ?></td>
						  <td style="text-align:center;"><?php echo date("F",strtotime(date("Y-".$rtupTFa['ClaimMonth']."-d")))?></td>	
                          <td style="text-align:center;"><?php if($rtupTFa['TotupTFa']>0){echo $rtupTFa['TotupTFa']; }?></td>					
						</tr>
                        <?php $no2++; } ?>						
					   </table>
						</div>
						<div id="DivTV" style="display:none;">
		<table class="table shadow table-responsive" style="width:100%;max-height:400px;overflow:scroll;" id="ReportTable">
					      <tr style="background-color:#5CB900; height:30px; font-weight:bold;">
						   <th scope="col" class="bhd" style="width:50px;text-align:center;">Sn</th>
						   <th scope="col" class="bhd" style="width:300px;">Pending Verify Approval Details</th>
						   <th scope="col" class="bhd" style="width:250px;">Reporting</th>                           
                           <th scope="col" class="bhd" style="width:100px;">Month</th>
						   <th scope="col" class="bhd" style="width:100px;">No Of Claim</th>
					     </tr>
						 <?php $stupTV=mysql_query("select CrBy,ClaimMonth,count(*) as TotupTV from `y".$_SESSION['FYearId']."_expenseclaims` where CrDate between '".date('Y-m-d',strtotime($_REQUEST['f']))."' AND '".date('Y-m-d',strtotime($_REQUEST['t']))."' AND FilledBy>0 AND FilledDate!='0000-00-00' AND FilledDate!='1970-01-01' AND VerifyBy=0 AND (VerifyDate='0000-00-00' OR VerifyDate='1970-01-01') AND ClaimStatus!='Deactivate' AND FilledOkay=1 group by CrBy,ClaimMonth ASC"); $no3=1; while($rtupTV=mysql_fetch_assoc($stupTV)){ ?>
					    <tr style="height:22px;">
						  <td style="text-align:center;"><?php echo $no3; ?></td>
					      <td><?php $u=mysql_query("SELECT Fname,Sname,Lname,ReportingName,ReportingContactNo FROM `hrm_employee` e inner join hrm_employee_general g on e.EmployeeID=g.EmployeeID where e.EmployeeID=".$rtupTV['CrBy'],$con2); $un=mysql_fetch_assoc($u); echo $un['Fname'].' '.$un['Sname'].' '.$un['Lname']; ?>	</td>
						  <td><?php echo $un['ReportingName'].' - '.$un['ReportingContactNo']; ?></td>
						  <td style="text-align:center;"><?php echo date("F",strtotime(date("Y-".$rtupTV['ClaimMonth']."-d")))?></td>	
                          <td style="text-align:center;"><?php if($rtupTV['TotupTV']>0){echo $rtupTV['TotupTV']; }?></td>					
						</tr>
                        <?php $no3++; } ?>						
					    </table>
						</div>
						<div id="DivTA" style="display:none;">
		<table class="table shadow table-responsive" style="width:100%;max-height:400px;overflow:scroll;" id="ReportTable">
					      <tr style="background-color:#5CB900; height:30px; font-weight:bold;">
						  <th scope="col" class="bhd" style="width:50px;text-align:center;">Sn</th>
						   <th scope="col" class="bhd" style="width:300px;">Pending Reporting Approval Details</th>
						   <th scope="col" class="bhd" style="width:250px;">Reporting</th>                           
						   <th scope="col" class="bhd" style="width:100px;">Month</th>
						   <th scope="col" class="bhd" style="width:100px;">No Of Claim</th>
					     </tr>
						 <?php $stupTA=mysql_query("select CrBy,ClaimMonth,count(*) as TotupTA from `y".$_SESSION['FYearId']."_expenseclaims` where CrDate between '".date('Y-m-d',strtotime($_REQUEST['f']))."' AND '".date('Y-m-d',strtotime($_REQUEST['t']))."' AND VerifyBy>0 AND VerifyDate!='0000-00-00' AND VerifyDate!='1970-01-01' AND ApprBy=0 AND (ApprDate='0000-00-00' OR ApprDate='1970-01-01') AND ClaimStatus!='Deactivate' group by CrBy,ClaimMonth ASC"); $no4=1; while($rtupTA=mysql_fetch_assoc($stupTA)){ ?>	
					    <tr style="height:22px;">
						  <td style="text-align:center;"><?php echo $no4; ?></td>
					      <td><?php $u=mysql_query("SELECT Fname,Sname,Lname,ReportingName,ReportingContactNo FROM `hrm_employee` e inner join hrm_employee_general g on e.EmployeeID=g.EmployeeID where e.EmployeeID=".$rtupTA['CrBy'],$con2); $un=mysql_fetch_assoc($u); echo $un['Fname'].' '.$un['Sname'].' '.$un['Lname']; ?>	</td>
						  <td><?php echo $un['ReportingName'].' - '.$un['ReportingContactNo']; ?></td>
						  <td style="text-align:center;"><?php echo date("F",strtotime(date("Y-".$rtupTA['ClaimMonth']."-d")))?></td>	
                          <td style="text-align:center;"><?php if($rtupTA['TotupTA']>0){echo $rtupTA['TotupTA']; }?></td>					
						</tr>
                        <?php $no4++; } ?>						
					    </table>
						</div>
						<div id="DivTFn" style="display:none;">
		<table class="table shadow table-responsive" style="width:100%;max-height:400px;overflow:scroll;" id="ReportTable">
					      <tr style="background-color:#5CB900; height:30px; font-weight:bold;">
						  <th scope="col" class="bhd" style="width:50px;text-align:center;">Sn</th>
						   <th scope="col" class="bhd" style="width:300px;">Pending Finance Approval Details</th>
						   <th scope="col" class="bhd" style="width:250px;">Reporting</th>                           
						   <th scope="col" class="bhd" style="width:100px;">Month</th>
						   <th scope="col" class="bhd" style="width:100px;">No Of Claim</th>
					     </tr>
						 <?php $stupTFn=mysql_query("select CrBy,ClaimMonth,count(*) as TotupTFn from `y".$_SESSION['FYearId']."_expenseclaims` where CrDate between '".date('Y-m-d',strtotime($_REQUEST['f']))."' AND '".date('Y-m-d',strtotime($_REQUEST['t']))."' AND ApprBy>0 AND ApprDate!='0000-00-00' AND ApprDate!='1970-01-01' AND FinancedBy=0 AND (FinancedDate='0000-00-00' OR FinancedDate='1970-01-01') AND ClaimStatus!='Deactivate' group by CrBy,ClaimMonth ASC"); $no5=1; while($rtupTFn=mysql_fetch_assoc($stupTFn)){ ?>
					    <tr style="height:22px;">
						  <td style="text-align:center;"><?php echo $no5; ?></td>
					      <td><?php $u=mysql_query("SELECT Fname,Sname,Lname,ReportingName,ReportingContactNo FROM `hrm_employee` e inner join hrm_employee_general g on e.EmployeeID=g.EmployeeID where e.EmployeeID=".$rtupTFn['CrBy'],$con2); $un=mysql_fetch_assoc($u); echo $un['Fname'].' '.$un['Sname'].' '.$un['Lname']; ?>	</td>
						  <td><?php echo $un['ReportingName'].' - '.$un['ReportingContactNo']; ?></td>
						  <td style="text-align:center;"><?php echo date("F",strtotime(date("Y-".$rtupTFn['ClaimMonth']."-d")))?></td>	
                          <td style="text-align:center;"><?php if($rtupTFn['TotupTFn']>0){echo $rtupTFn['TotupTFn']; }?></td>					
						</tr>
                        <?php $no5++; } ?>						
					    </table>
						</div>
						
						</td>
					   </tr> 
					</table>
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










