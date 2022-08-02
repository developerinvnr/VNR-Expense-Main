<?php
include "header.php";
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>
<style type="text/css">
div.dataTables_wrapper div.dataTables_length select { width:100; }
div.table-responsive>div.dataTables_wrapper>div.row { background-color:#ccf5ff; padding:10px; width:100%; }
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
<div class="container-fluid ">
 <div class="row  d-flex justify-content-around">
  <div class="col-md-10">
   <div class="row filrow font-weight-bold">
	<input type="hidden" class="form-control" id="userfr" value="0">	
	<input type="hidden" class="form-control" id="claimTypefr" value="0">
	<input type="hidden" class="form-control" id="claimStatusfr" value="Submitted">		
	
	<div class="col-md-4"><br />
	Deactivate Claim List : After Filling Form
	</div>
	<?php
	if(!isset($_REQUEST['f']) && !isset($_REQUEST['t']))
	{
	 $_REQUEST['f']= date("d-m-Y");
	 $_REQUEST['t']= date("d-m-Y");
	}
	?>
	<div class="col-md-2">Filled-From<input id="fromdtfr" class="form-control" value="<?php if(isset($_REQUEST['f'])){echo $_REQUEST['f'];}?>"></div>
	<div class="col-md-2">Filled-To<input id="todtfr" class="form-control" value="<?php if(isset($_REQUEST['t'])){echo $_REQUEST['t'];}?>"></div>
	<div class="col-md-<?php if($_SESSION['EmpRole']=='E'){ echo '4';}else{echo '2';} ?>">
	<br><button class="form-control btn-primary" onclick="filter()">Search</button>
	</div>

   </div>      	
   <div class="row filrow font-weight-bold">
	<div class="table-responsive d-flex justify-content-center align-items-center">
	 <table class="table shadow table-responsive" id="ReportTable" style="width:100%;">
      <thead class="thead-dark">
		<?php if(isset($_REQUEST['u']) && isset($_REQUEST['ct'])){ ?>
		<tr>
		 <td colspan="12">
		 <button class="form-control btn-primary" onclick="ResultExp('<?=$_REQUEST['uc']?>','<?=$_REQUEST['un']?>','<?=$_REQUEST['ct']?>','<?=$_REQUEST['cs']?>',<?=$_REQUEST['fy']?>)" style="cursor:pointer;width:100px;">Export</button>
		 </td>
		</tr>
			
		<?php } ?>
		<tr>
		  <th scope="col" style="width:10px;">Sn</th>
		  <th scope="col" style="width:50px;">Claim ID</th>
		  <th scope="col" style="width:100px;">Claim Type</th>
		  <th scope="col" style="width:250px;">Applied By</th>
		  <th scope="col" style="width:100px;">Upload Date</th>
		  <th scope="col" style="width:100px;">Bill Date</th>
		  <th scope="col" style="width:80px;"><span style="font-size: 9px !important;">Claimed</span><br>Amt</th>
		  <th scope="col" style="width:80px;">Status</th>
		  <th scope="col" style="width:80px;">Detail</th>
		</tr>
	   </thead>
      <tbody>			  	
	  <?php

		if(isset($_REQUEST['f']) && isset($_REQUEST['t']))
		{
		  $f=$_REQUEST['f']!='' ? date("Y-m-d",strtotime($_REQUEST['f'])) : '2019-01-01';
		  $t=$_REQUEST['t']!='' ? date("Y-m-d",strtotime($_REQUEST['t'])) : date("Y-m-d");
	    $seleq=mysql_query("SELECT e.*, c.ClaimName FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c where e.ClaimYearId='".$_SESSION['FYearId']."' and c.ClaimId=e.ClaimId and e.FilledDate between '".$f."' and '".$t."' and e.ClaimStatus='Deactivate' and e.FilledBy=10 and e.FilledTAmt>0 and e.ClaimId!=0 and e.ClaimId!=19 and e.ClaimId!=20 order by e.BillDate ASC");
		$i=1;
		while($exp=mysql_fetch_assoc($seleq)){
		?>
		<tr onclick="showdet('<?=$exp['EmployeeID']?>')">

		  <th scope="row"><?=$i?></th>
		  <td><?=$exp['ExpId']?></td>
		  <td><?=$exp['ClaimName']?></td>
		  
		   <?php $u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$exp['CrBy'], $con2); $un=mysql_fetch_assoc($u); ?>
		  <td><?=$un['Fname'].' '.$un['Sname'].' '.$un['Lname']?></td>
		  
		  <td><?=date("d-m-Y",strtotime($exp['CrDate']))?></td>
		  <td><?=date("d-m-Y",strtotime($exp['BillDate']))?></td>
		  <td><?=$exp['FilledTAmt']?></td>
		  <td><?=$exp['ClaimStatus']?></td>
		  <td>
			<button class="btn btn-sm btn-primary" onclick="showexpdet(<?=$exp['ExpId']?>)">View</button>
		  </td>
		</tr>
		<?php $i++; } //while
		
		} //if(isset($_REQUEST['f']) && isset($_REQUEST['t']))
		?>
	  </tbody>
					  
					</table>
				</div>
			</div>
		</div>
		
		
	</div>
	
</div>

<?php //echo $q;?>


<?php include "footer.php"; ?>

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


function filter()
{
 var u = $('#userfr').val();
 var cs = $('#claimStatusfr').val();
 var ct = $('#claimTypefr').val();
 var f = $('#fromdtfr').val();
 var t = $('#todtfr').val();
 var ff = $('#RdoSelN').val(); 
 window.open("deactiveclaim.php?u="+u+"&ct="+ct+"&cs="+cs+"&f="+f+"&t="+t+"&ff="+ff,"_self");
}

function ResultExp()
{
 var u = $('#userfr').val(); var cs = $('#claimStatusfr').val();
 var ct = $('#claimTypefr').val(); var f = $('#fromdtfr').val();
 var t = $('#todtfr').val(); var ff = $('#RdoSelN').val();
 var win = window.open("deactiveclaimexp.php?act=resultexp&u="+u+"&ct="+ct+"&cs="+cs+"&f="+f+"&t="+t+"&ff="+ff,"ExpForm","menubar=no,scrollbars=yes,resizable=no,directories=no,width=50,height=50");
}


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
