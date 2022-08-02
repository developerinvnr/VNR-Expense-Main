<?php
include "header.php";
?>

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


<?php
	if($_SESSION['EmpRole']=='E'){
		$crcond="h.EmployeeID=".$_SESSION['EmployeeID'];
	}elseif($_SESSION['EmpRole']=='A'){
		$crcond="(her.EmployeeID=".$_SESSION['EmployeeID']." or her.AppraiserId=".$_SESSION['EmployeeID'].") and h.EmployeeID=her.EmployeeID";
	}elseif($_SESSION['EmpRole']=='M'){

		$selr=mysql_query("SELECT rEmployeeID FROM `dataentry_reporting` where uEmployeeID=".$_SESSION['EmployeeID']);

		if(mysql_num_rows($selr)>0){
			$selrd=mysql_fetch_assoc($selr);
			$reportingNo=$selrd['rEmployeeID'];
			$empshowcond=" ".$reportingNo." IN (rl.EmployeeID,rl.`R1`, rl.`R2`, rl.`R3`, rl.`R4`, rl.`R5`)";

		}else{

			$data= mysql_query("SELECT rEmployeeID FROM dataentry_reporting GROUP BY rEmployeeID");
			while($rec=mysql_fetch_array($data)){ $array_data[]=$rec['rEmployeeID']; }
			$str_data = implode(',', $array_data);

			$empshowcond=" rl.`EmployeeID` NOT IN (".$str_data.") AND rl.`R1` NOT IN (".$str_data.") AND rl.`R2` NOT IN (".$str_data.") AND rl.`R3` NOT IN (".$str_data.") AND rl.`R4` NOT IN (".$str_data.") AND rl.`R5` NOT IN (".$str_data.")";
			

		}

		//$crcond="h.EmployeeID=rl.EmployeeID  and ".$empshowcond." ";
		$crcond="h.EmployeeID=rl.EmployeeID";
	}else{
		$crcond="EmpType='E'";
	}
?>
<div class="container-fluid ">
	<div class="row  d-flex justify-content-around">
		<div class="col-md-10">
			<div class="row filrow font-weight-bold">
				

					<div class="col-md-2" <?php if($_SESSION['EmpRole']=='E'){ echo 'style="display:none;"';} ?>>
						
						Users:
						<select class="form-control" id="userfr">
				      		
				      		<?php
				      		$u=mysql_query("select h.EmployeeID,h.Fname,h.Sname,h.Lname from `y".$_SESSION['FYearId']."_monthexpensefinal` e, ".dbemp.".hrm_employee h, ".dbemp.".hrm_employee_reporting her, ".dbemp.".hrm_sales_reporting_level rl where h.EmpStatus='A' and ".$crcond." and h.EmployeeID=e.EmployeeID group by h.EmployeeID order by h.Fname asc");
				      		//$u=mysql_query("select h.EmployeeID,h.Fname,h.Sname,h.Lname from `y".$_SESSION['FYearId']."_expenseclaims` e, ".dbemp.".hrm_employee h, ".dbemp.".hrm_employee_reporting her, ".dbemp.".hrm_sales_reporting_level rl where h.EmpStatus='A' and ".$crcond." and h.EmployeeID=e.CrBy group by h.EmployeeID order by h.Fname asc");
				      		if(mysql_num_rows($u)>1){
				      		?>
				      		<option value="ALL">ALL</option>
				      		<?php
				      		}
				      		while($us=mysql_fetch_assoc($u)){
				      		?>
				      		<option value="<?=$us['EmployeeID']?>" <?php if(isset($_REQUEST['u']) && $_REQUEST['u']==$us['EmployeeID']){echo 'selected';} ?>><?=$us['Fname'].' '.$us['Sname'].' '.$us['Lname']?></option>
				      		<?php
					      	}
				      		?>
				      	</select>
			      	</div>
			      	


			      	<div class="col-md-2">
						Claim Type:
						<?php
						if($_SESSION['EmpRole']=='E'){$ecaCond="eca.EmployeeID=".$_SESSION['EmployeeID']; }
						else{$ecaCond="1=1"; }
						?>
						<select class="form-control" id="claimTypefr">
				      		<option value="ALL">ALL</option>
				      		<?php
				      		
				      		$c=mysql_query("select ct.ClaimId,ct.ClaimName from claimtype ct, y".$_SESSION['FYearId']."_empclaimassign eca where ct.ClaimStatus='A' and ct.ClaimId=eca.ClaimId and ".$ecaCond." group by ct.ClaimId");

				      		while($cid=mysql_fetch_assoc($c)){
				      		?>
				      		<option value="<?=$cid['ClaimId']?>" <?php if(isset($_REQUEST['ct']) && $_REQUEST['ct']==$cid['ClaimId']){echo 'selected';} ?>><?=$cid['ClaimName']?></option>
				      		<?php
					      	}
				      		?>
				      	</select>
			      	</div>
			      	<div class="col-md-2">
						Claim Status:

						<select class="form-control" id="claimStatusfr">
				      		<option value="ALL">ALL</option>
				      		
				      		<!-- <option value="Saved" <?php //if(isset($_REQUEST['cs']) && $_REQUEST['cs']=='Saved'){echo 'selected';} ?>>Saved</option> -->
				      		<option value="Submitted" <?php if(isset($_REQUEST['cs']) && $_REQUEST['cs']=='Submitted'){echo 'selected';} ?>>Submitted</option>
				      		<option value="Filled" <?php if(isset($_REQUEST['cs']) && $_REQUEST['cs']=='Filled'){echo 'selected';} ?>>Filled</option>
				      		<?php if($_SESSION['EmpRole']!='M'){ ?> 

				      		<option value="Verified" <?php if(isset($_REQUEST['cs']) && $_REQUEST['cs']=='Verified'){echo 'selected';} ?>>Verified</option>
				      		<option value="Approved" <?php if(isset($_REQUEST['cs']) && $_REQUEST['cs']=='Approved'){echo 'selected';} ?>>Approved</option>
				      		<option value="Financed" <?php if(isset($_REQUEST['cs']) && $_REQUEST['cs']=='Financed'){echo 'selected';} ?>>Paid</option>

					      	<?php } ?> 
				      	</select>
			      	</div>


			      	<?php
			      	//here setting bu default from and to date to current date if the dates not set
					if(!isset($_REQUEST['f']) && !isset($_REQUEST['t'])){
						$_REQUEST['f']= date("d-m-Y");
						$_REQUEST['t']= date("d-m-Y");
					}
			      	?>
			      	<div class="col-md-2">
						From
						<input id="fromdtfr" class="form-control" value="<?php if(isset($_REQUEST['f'])){echo $_REQUEST['f'];}?>">
						
			      	</div>
			      	<div class="col-md-2">
						To
						<input id="todtfr" class="form-control" value="<?php if(isset($_REQUEST['t'])){echo $_REQUEST['t'];}?>">
						
			      	</div>
			      	<div class="col-md-<?php if($_SESSION['EmpRole']=='E'){ echo '4';}else{echo '2';} ?>">
			      		<br>
						<button class="form-control btn-primary" onclick="filter()">Search</button>
						
			      	</div>
		      	
	      	</div>
	      	
	      	<div class="row filrow font-weight-bold">
				<div class="table-responsive d-flex justify-content-center align-items-center">
					<table class="table shadow table-responsive" id="ReportTable">
					  <thead class="thead-dark">
					    <tr>
					      <th scope="col" style="width: 10px;">Sn</th>
					      <th scope="col" style="width: 50px;">Claim ID</th>
					      <!-- <th scope="col" style="width: 150px;">Claim</th> -->
					      <th scope="col">Claim Type</th>
					      <th scope="col">Applied By</th>
					      <th scope="col">Upload Date</th>
						  <th scope="col">Bill Date</th>
					      <th scope="col"><span style="font-size: 9px !important;">Claimed</span><br>Amt</th>
					      <th scope="col"><span style="font-size: 9px !important;">Verified</span><br>Amt</th>
					      <th scope="col"><span style="font-size: 9px !important;">Approved</span><br>Amt</th>
					      <th scope="col"><span style="font-size: 9px !important;">Paid</span><br>Amt</th>
					      <th scope="col">Claim Status</th>
					      <th scope="col">Detail</th>
					    </tr>
					  </thead>
					  <tbody>
					  	
					  	<?php

					  	//filters user,claim status,fromdate,todate condition setting start//////////////
					  	
					  	if(isset($_REQUEST['u']) && $_REQUEST['u']!='' && $_REQUEST['u']!='ALL'){
					  		$ucond="e.CrBy=".$_REQUEST['u'];
					  	}else{ $ucond="1=1"; }

					  	if(isset($_REQUEST['ct']) && $_REQUEST['ct']!='' && $_REQUEST['ct']!='ALL'){
					  		$ctcond="e.ClaimId='".$_REQUEST['ct']."'";
					  	}else{ $ctcond="1=1"; }

					  	if(isset($_REQUEST['cs']) && $_REQUEST['cs']!='' && $_REQUEST['cs']!='ALL'){
					  		$cscond="e.ClaimStatus='".$_REQUEST['cs']."'";
					  	}else{ $cscond="1=1"; }

					  	if(isset($_REQUEST['f']) && isset($_REQUEST['t'])){
					  		$f=$_REQUEST['f']!='' ? date("Y-m-d",strtotime($_REQUEST['f'])) : '2018-01-01';
					  		//here by default given 2018-01-01 because the site started from 2018 so claims must be uploaded after that
					  		$t=$_REQUEST['t']!='' ? date("Y-m-d",strtotime($_REQUEST['t'])) : date("Y-m-d");
					  		//here by default given current date because claims must be uploaded till today only
					  		//$dtcond="e.CrDate between '".$f."' and '".$t."'";
					  		$dtcond="e.BillDate between '".$f."' and '".$t."'";
					  	}else{ $dtcond="1=1"; }

					

	                   $q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c, ".dbemp.".hrm_employee h, ".dbemp.".hrm_employee_reporting her, ".dbemp.".hrm_sales_reporting_level rl where h.EmployeeID=e.CrBy and e.ClaimYearId='".$_SESSION['FYearId']."' and (c.ClaimId=e.ClaimId or e.ClaimId=0) and ".$ucond." and ".$ctcond." and e.ClaimStatus!='Deactivate' and ".$cscond." and ".$dtcond." and ".$crcond." group by e.ExpId order by e.BillDate ASC";

	                   
					  	$seleq=mysql_query($q);

						$i=1;
					  	while($exp=mysql_fetch_assoc($seleq)){
					  	?>
					    <tr onclick="showdet('<?=$exp['EmployeeID']?>')">

					      <th scope="row"><?=$i?></th>
					      <td><?=$exp['ExpId']?></td>
					      <!-- <td><?=$exp['ExpenseName']?></td> -->
					      <td><?=$exp['ClaimName']?></td>
					      <td><?=$exp['Fname'].' '.$exp['Sname'].' '.$exp['Lname']?></td>
					      <td><?=date("d-m-Y",strtotime($exp['CrDate']))?></td>
					      <td><?=date("d-m-Y",strtotime($exp['BillDate']))?></td>
					      <td><?php if($exp['FilledTAmt']!=0 && $exp['FilledBy']>0 && $exp['FilledDate']!='0000-00-00'){echo $exp['FilledTAmt'];}?></td>
					      <td><?php if($exp['VerifyTAmt']!=0 && $exp['VerifyBy']>0 && $exp['VerifyDate']!='0000-00-00'){echo $exp['VerifyTAmt'];}?></td>
					      <td><?php if($exp['ApprTAmt']!=0 && $exp['ApprBy']>0 && $exp['ApprDate']!='0000-00-00'){echo $exp['ApprTAmt'];}?></td>
					      <td><?php if($exp['FinancedTAmt']!=0 && $exp['FinancedBy']>0 && $exp['FinancedDate']!='0000-00-00'){echo $exp['FinancedTAmt'];}?></td>
					     
					     
					     <?php /*
					      <td><?php if($exp['FilledTAmt']!=0){echo $exp['FilledTAmt'];}?></td>
					      <td><?php if($exp['VerifyTAmt']!=0){echo $exp['VerifyTAmt'];}?></td>
					      <td><?php if($exp['ApprTAmt']!=0){echo $exp['ApprTAmt'];}?></td>
					      <td><?php if($exp['FinancedTAmt']!=0){echo $exp['FinancedTAmt'];}?></td>
					      */ ?>
					      <td>
					      	<?php
					      	if($exp['ClaimStatus']=='Financed'){
					      		?><div class="btn btn-sm btn-success">Paid</div><?php
					      	}else{
					      		?><div class="btn btn-sm btn-warning"><?=$exp['ClaimStatus']?></div><?php
					      	}
					      	?>
					      </td>
					      <td>
					      	<button class="btn btn-sm btn-primary" onclick="showexpdet(<?=$exp['ExpId']?>)">View</button>
					      </td>
					     

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
	
</div>

<?php //echo $q;?>


<?php
include "footer.php";
?>

<script type="text/javascript" src="js/reports.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
<script type="text/javascript">
$(document).ready( function () {
    $('#ReportTable').DataTable({
    	"language": {
    "search": "Search By Keyword:"
  }
    }).responsive.recalc().columns.adjust();
} );


function showexpdet(expid){
	
	var modal = document.getElementById('myModal'); 
	modal.style.display = "block"; 
	document.getElementById('claimlistfr').src="showclaim.php?expid="+expid;
}
</script>


<!-- from here the style, div and script all are for displaying modal on page view click -->

<style>


/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  position: relative;
  background-color: #fefefe;
  margin: auto;
  padding: 0;
  border: 1px solid #888;
  width: 90%;
  height: 95%;
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
  -webkit-animation-name: animatetop;
  -webkit-animation-duration: 0.4s;
  animation-name: animatetop;
  animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
  from {top:-300px; opacity:0} 
  to {top:0; opacity:1}
}

@keyframes animatetop {
  from {top:-300px; opacity:0}
  to {top:0; opacity:1}
}

/* The Close Button */
.close {
  color: white;
  float: right;
  font-size: 28px;
  font-weight: bold;
  display: none;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.modal-header {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}

.modal-body {padding: 2px 16px;}

.modal-footer {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}

.lbl{cursor: pointer;}
</style>


<div id="detailbox" style="display:none;position: absolute;height: 98%;width: 98%;">
  <iframe id="detailfr" src="" style="width:100%;height: 100%;"></iframe>
</div>




<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    
      <span class="close" >&times;</span>
      
    <div class="modal-body d-flex justify-content-center align-items-center">
    	
	    <div style="position: absolute;margin:0 auto;height: 98%;width: 98%;">
			<iframe id="claimlistfr" src="" style="width:100%;height: 100%;"></iframe>
		</div>
      
      
      
      
    </div>
    
  </div>

</div>

<script>
// Get the modal
var modal = document.getElementById('myModal');

var span = document.getElementsByClassName("close")[0];

span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
