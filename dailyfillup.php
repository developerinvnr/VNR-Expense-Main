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
 window.location="dailyfillup.php?act=rst&f="+f+"&t="+t;
}
</script>
<div class="container-fluid ">
	<div class="row  d-flex justify-content-around">
		<div class="col-md-10">
			<div class="row filrow font-weight-bold">
				
					<div class="col-md-2"><br />Daily Activity:</div>

<?php if(!isset($_REQUEST['f']) && !isset($_REQUEST['t']))
      { $_REQUEST['f']= date("d-m-Y"); $_REQUEST['t']= date("d-m-Y"); } ?>
			      	<div class="col-md-2">From<input id="fromdtfr" class="form-control" value="<?php if(isset($_REQUEST['f'])){echo $_REQUEST['f'];}?>"></div>
			      	
					<div class="col-md-2">To<input id="todtfr" class="form-control" value="<?php if(isset($_REQUEST['t'])){echo $_REQUEST['t'];}?>"></div>
					
			      	<div class="col-md-2"><br>
						<button class="form-control btn-primary" onclick="filterTD()">Search</button>
			      	</div>
		      	
	      	</div>
	      	
	      	<div class="row filrow font-weight-bold">
				<div class="table-responsive d-flex justify-content-center align-items-center">
<?php if($_REQUEST['act']=='rst'){ ?>				
					<table class="table shadow table-responsive" style="width:100%;" id="ReportTable">
					  <thead class="thead-dark">
					    <tr>
					      <th scope="col" style="width:100px;">Date</th>
					      <th scope="col" style="width:80px;">Total Claim</th>
						  <?php $u=mysql_query("select EmpCode from hrm_user where EmpRole='M' AND EmpStatus='A' AND EmpCode!='mediator' AND EmpCode!='mediator2' AND EmpCode!='Tmediator' AND EmpCode!='Tmediator2' order by EmpCode asc"); while($us=mysql_fetch_assoc($u)){ ?>
						  <th scope="col" style="width:120px;">Filled to<br /><font color="#F1B80C"><?=strtoupper($us['EmpCode']);?></font></th>
						  <?php } ?>
						  <th scope="col" style="width:80px;">Total Filled</th>
					    </tr>
					  </thead>
					  <tbody>	
					   <tr style="background-color:#FFFF9F;">
					      <td style="text-align:right;"><b>Total:&nbsp;</b></td>
<?php $stupT=mysql_query("select count(*) as TotupT from `y".$_SESSION['FYearId']."_expenseclaims` where CrDate between '".date('Y-m-d',strtotime($_REQUEST['f']))."' AND '".date('Y-m-d',strtotime($_REQUEST['t']))."' AND ClaimId!=19 AND ClaimId!=20 AND ClaimId!=21 AND ClaimStatus!='Deactivate' "); $rtupT=mysql_fetch_assoc($stupT); ?>						    
					      <td style="text-align:center;"><b><?=$rtupT['TotupT'];?></b></td>

<?php $u=mysql_query("select EmployeeID from hrm_user where EmpRole='M' AND EmpStatus='A' AND EmpCode!='mediator' AND EmpCode!='mediator2' AND EmpCode!='Tmediator' AND EmpCode!='Tmediator2' order by EmpCode asc"); while($us=mysql_fetch_assoc($u)){ ?>

<?php $stfit=mysql_query("select count(*) as Totfit from `y".$_SESSION['FYearId']."_expenseclaims` where FilledDate between '".date('Y-m-d',strtotime($_REQUEST['f']))."' AND '".date('Y-m-d',strtotime($_REQUEST['t']))."' AND ClaimId!=19 AND ClaimId!=20 AND ClaimId!=21 AND ClaimStatus!='Deactivate' AND FilledBy=".$us['EmployeeID']); $rtfit=mysql_fetch_assoc($stfit); ?>
                          <td style="text-align:center;"><b><?php if($rtfit['Totfit']>0){echo $rtfit['Totfit']; }?></b></td>
<?php } ?>

<?php $stfiTT=mysql_query("select count(*) as TotfiTT from `y".$_SESSION['FYearId']."_expenseclaims` e inner join hrm_user u on e.FilledBy=u.EmployeeID where FilledDate between '".date('Y-m-d',strtotime($_REQUEST['f']))."' AND '".date('Y-m-d',strtotime($_REQUEST['t']))."' AND e.ClaimId!=19 AND e.ClaimId!=20 AND e.ClaimId!=21 AND u.EmpRole='M' AND e.ClaimStatus!='Deactivate'"); $rtfiTT=mysql_fetch_assoc($stfiTT); ?>	  
					      <td style="text-align:center;"><b><?php if($rtfiTT['TotfiTT']>0){echo $rtfiTT['TotfiTT']; }?></b></td>
					    </tr>
				  
<?php $array = array(); // Declare an empty array  
$Date1 = date("d-m-Y",strtotime($_REQUEST['f'])); 
$Date2 = date("d-m-Y",strtotime($_REQUEST['t'])); 
$Variable1 = strtotime($Date1); 
$Variable2 = strtotime($Date2); // Use strtotime function 				       
// Use for loop to store dates into array  // 86400 sec = 24 hrs = 60*60*24 = 1 day 
for($cDate=$Variable1; $cDate<=$Variable2; $cDate +=(86400))
{                                       
?>

<?php $stfChk1=mysql_query("select count(*) as TotfiiChk1 from `y".$_SESSION['FYearId']."_expenseclaims` where CrDate='".date('Y-m-d',$cDate)."' AND CrBy>0 AND ClaimId!=19 AND ClaimId!=20 AND ClaimId!=21"); $rtfChk1=mysql_fetch_assoc($stfChk1); 
$stfChk2=mysql_query("select count(*) as TotfiiChk2 from `y".$_SESSION['FYearId']."_expenseclaims` e inner join hrm_user u on e.FilledBy=u.EmployeeID where FilledDate='".date('Y-m-d',$cDate)."' AND u.EmpRole='M' AND e.ClaimStatus!='Deactivate' AND e.ClaimId!=19 AND e.ClaimId!=20 AND e.ClaimId!=21"); $rtfChk2=mysql_fetch_assoc($stfChk2); $totchk=$rtfChk1['TotfiiChk1']+$rtfChk2['TotfiiChk2']; if($totchk>0){ ?>

					    <tr>
					      <td style="text-align:center;"><?=date('d-m-Y',$cDate); ?></td>
<?php $stup=mysql_query("select count(*) as Totup from `y".$_SESSION['FYearId']."_expenseclaims` where CrDate='".date('Y-m-d',$cDate)."' AND ClaimStatus!='Deactivate' and CrBy>0 AND ClaimId!=19 AND ClaimId!=20 AND ClaimId!=21"); $rtup=mysql_fetch_assoc($stup); ?>						    
					      <td style="text-align:center;"><?=$rtup['Totup'];?></td>

<?php $u=mysql_query("select EmployeeID from hrm_user where EmpRole='M' AND EmpStatus='A' AND EmpCode!='mediator' AND EmpCode!='mediator2' AND EmpCode!='Tmediator' AND EmpCode!='Tmediator2' order by EmpCode asc"); while($us=mysql_fetch_assoc($u)){ ?>

<?php $stfi=mysql_query("select count(*) as Totfi from `y".$_SESSION['FYearId']."_expenseclaims` where FilledDate='".date('Y-m-d',$cDate)."' AND ClaimStatus!='Deactivate' AND ClaimId!=19 AND ClaimId!=20 AND ClaimId!=21 AND FilledBy=".$us['EmployeeID']); $rtfi=mysql_fetch_assoc($stfi); ?>
                          <td style="text-align:center;"><?php if($rtfi['Totfi']>0){echo $rtfi['Totfi']; }?></td>
<?php } ?>

<?php $stfiT=mysql_query("select count(*) as TotfiT from `y".$_SESSION['FYearId']."_expenseclaims` e inner join hrm_user u on e.FilledBy=u.EmployeeID where FilledDate='".date('Y-m-d',$cDate)."' AND u.EmpRole='M' AND e.ClaimStatus!='Deactivate' AND e.ClaimId!=19 AND e.ClaimId!=20 AND e.ClaimId!=21"); $rtfiT=mysql_fetch_assoc($stfiT); ?>	  
					      <td style="text-align:center;background-color:#9393FF;"><b><?php if($rtfiT['TotfiT']>0){echo $rtfiT['TotfiT']; }?></b></td>
					    </tr>
					    <?php } //if($totchk>0)
						 } //for ?>
					  </tbody>
					  
					</table>
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










