<?php
session_start();

include 'config.php';
?>
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="css/jquery.datetimepicker.css">
<link rel="stylesheet" type="text/css" href="css/style.css">


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>
 

<style type="text/css">
	div.dataTables_wrapper div.dataTables_length select {
	    width: 100;
	    
	}
	div.table-responsive>div.dataTables_wrapper>div.row {
	    
	    background-color: #ccf5ff;
	    padding: 10px;
	    width:100%;
	}
</style>

<?php

function getClaimType($cid){
	$c=mysql_query("SELECT ClaimName FROM `claimtype` where ClaimId=".$cid);
	$ct=mysql_fetch_assoc($c);
	return $ct['ClaimName'];
}
function getUser($u){
	$u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$u);
	$un=mysql_fetch_assoc($u);
	return $un['Fname'].' '.$un['Sname'].' '.$un['Lname'];
}
?>
<div class="">
	<div class="row  d-flex justify-content-around">
		<div class="col-md-10">
			<div class="row filrow font-weight-bold">
				
		      	
	      	</div>
			<div class="table-responsive">
				<table class="table shadow">
				  <thead class="thead-dark">
				    <tr>
				      <th scope="col" style="width: 30px;">S.No</th>
				      <th scope="col" style="width: 50px;">Claim ID</th>
				      <th scope="col" style="width: 150px;">Claim</th>
				      <th scope="col">Claim Type</th>
				      <th scope="col">Applied By</th>
				      <th scope="col">Applied Date</th>
				      <th scope="col">Claim Status</th>
				      <?php if(!isset($_REQUEST['csts']) || $_REQUEST['csts']!='Filled'){?>
				      <th scope="col">Action</th>
					  	
					  <?php } ?>

				    </tr>
				  </thead>
				  <tbody>
				  	
				  	<?php
				  	
				  	
				  	if(isset($_REQUEST['action']) && $_REQUEST['action']=='mediator'){
				  		$stepcond="e.ClaimAtStep IN (1,2) and e.ClaimStatus='".$_REQUEST['csts']."'";
				  	}elseif(isset($_REQUEST['action']) && $_REQUEST['action']=='verify'){
				  		$stepcond="e.ClaimAtStep=3";
				  	}elseif(isset($_REQUEST['action']) && $_REQUEST['action']=='approve'){
				  		$stepcond="e.ClaimAtStep=4";
				  	}elseif(isset($_REQUEST['action']) && $_REQUEST['action']=='finance'){
				  		$stepcond="e.ClaimAtStep=5";
				  	}else{
				  		$stepcond="1=1";
				  	}

				  	
				  	if($_SESSION['EmpRole']!='S'){
				  		$crcond="e.CrBy=".$_SESSION['EmployeeID'];
				  	}else{
				  		$crcond="1=1";
				  	}



				  	$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims`e, claimtype c, hrm_employee h where c.ClaimId=e.ClaimId and h.EmployeeID=e.CrBy and e.ClaimMonth='".$_REQUEST['month']."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_REQUEST['emp']." and ".$stepcond;

				  	$seleq=mysql_query($q);

					$i=1;
				  	while($exp=mysql_fetch_assoc($seleq)){
				  	?>
				    <tr onclick="showdet('<?=$exp['EmployeeID']?>')">

				      <th scope="row"><?=$i?></th>
				      <td><?=$exp['ExpId']?></td>
				      <td><?=$exp['ExpenseName']?></td>
				      <td><?=$exp['ClaimName']?></td>
				      <td><?=$exp['Fname'].' '.$exp['Sname'].' '.$exp['Lname']?></td>
				      <td><?=date("d-m-Y",strtotime($exp['CrDate']))?></td>
				      <td>
				      	<?php
				      	$c='outline-secondary';
				      	$s=$exp['ClaimStatus'];

				      	

				      	?>
				      	<div class="btn btn-sm btn-<?=$c?> font-weight-bold"><?=$s?></div>
				      	
				      </td>
				      <?php if(!isset($_REQUEST['csts']) || $_REQUEST['csts']!='Filled'){?>

				      <td class="">
				      	<?php

				      	switch ($_SESSION['EmpRole']) {
						    case 'E':
						        ?><button class="btn btn-sm btn-primary" onclick="showexpdet('<?=$exp['ExpId']?>')">View</button><?php
						        break;
						    case 'M':
						        ?><button class="btn btn-sm btn-primary" onclick="fillclaim('<?=$exp['ExpId']?>')"> Fill </button><?php
						        break;

						    case 'V':
						       	$btn='Verify';
								if($s=='Verified' && ($exp['ClaimAtStep']==3 || $exp['ClaimAtStep']==4)){$c='success'; $btn='View';}
								
								?>
								<button class="btn btn-sm btn-primary" onclick="openprevupload('<?=$exp['ExpId']?>')"><?=$btn?></button>
								<?php
								
						        break;
						    case 'A':
						    	$btn='Approve';
								if($s=='Approved' && ($exp['ClaimAtStep']==4 || $exp['ClaimAtStep']==5)){$c='success'; $btn='View';}
						        ?><button class="btn btn-sm btn-primary" onclick="openprevupload('<?=$exp['ExpId']?>')"><?=$btn?></button><?php
						        break;
						    case 'F':
							    $btn='Finance';
								if($s=='Financed' && $exp['ClaimAtStep']==5){$c='success'; $btn='View';}
						        ?><button class="btn btn-sm btn-primary" onclick="openprevupload('<?=$exp['ExpId']?>')"><?=$btn?></button><?php
						        break;
						        
						}

						
						
						
				      	?>
				      	
				      </td>
					  <?php } ?>
				     

				    </tr>
				    <?php
				    $i++;
					}
					?>
				  </tbody>
				</table>
			</div>
			
		</div>
		
		
	</div>
	
</div>




<?php
include "footer.php";
?>

<script type="text/javascript" src="js/reports.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>


<script type="text/javascript">
	function fillclaim(expid){
		var w=1200;
		var h=600;
		var left = (screen.width/2)-(h/2);
		var top = (screen.height/2)-(w/2);
		var win =window.open("showclaim.php?expid="+expid+"&action=edit","AttachForm","scrollbars=yes,resizable=no,width="+w+",height="+h+",top="+top+", left="+left);
		win.focus();
		
	}
</script>