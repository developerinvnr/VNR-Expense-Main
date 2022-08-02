<?php include "header.php"; ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>
<style type="text/css">
div.dataTables_wrapper div.dataTables_length select{ width:100; }
div.table-responsive>div.dataTables_wrapper>div.row { background-color: #ccf5ff; padding: 10px; width:100%; }
</style>

<?php

function getClaimType($cid){
	include "config.php";
	$c=mysql_query("SELECT ClaimName FROM `claimtype` where ClaimId=".$cid);
	$ct=mysql_fetch_assoc($c);
	return $ct['ClaimName'];
}
function getUser($u){
	include "config.php";
	$u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$u, $con2);
	$un=mysql_fetch_assoc($u);
	return $un['Fname'].' '.$un['Sname'].' '.$un['Lname'];
}
function getCode($u){
	include "config.php";
	$uc=mysql_query("SELECT EmpCode FROM `hrm_employee` where EmployeeID=".$u, $con2);
	$uc=mysql_fetch_assoc($uc);
	return $uc['EmpCode'];
}
?>
<script type="text/javascript">


function FunChk(v)
{
 $('#chkval').val(v);
}

function FselMonth()
{ 
 var SelMonth=document.getElementById('SelMonth').value; 
 window.location="claim2stage.php?action=displayrec&v="+SelMonth;
}

function ExpRep(n,v)
{ 
  window.open("PayStsExp.php?act=Stageexportdetails&v="+v+"&n="+n,"QForm","menubar=no,scrollbars=yes,resizable=no,directories=no,width=50,height=50");
}
	
</script>
<div class="col-md-11 h-100" style="border-left:5px solid #d9d9d9;">
<!--<link href="dt_bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">-->
<link href="dt_css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
 <br>
 
				
 <font style="font-size:14px;">Month :</font>&nbsp;
 <select style="font-size:14px;" id="SelMonth">
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
 <a class="btn btn-sm btn-primary" onclick="FselMonth()"><i class="fa fa-btn" aria-hidden="true"></i><span style="color:#FFFFFF; width:80px;">&nbsp;&nbsp;&nbsp;Click&nbsp;&nbsp;&nbsp;</span></a>&nbsp;&nbsp;&nbsp;
 <a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
				
 <div class="row">
 <?php if($_REQUEST['v']>0){ ?>		
 <div class="col-lg-12 shadow">
 <br>
 <h5>
  <small class="font-weight-bold text-muted">Work Stage</small>
 </h5> 
 		
 <table class="estable table shadow">
  <thead class="thead-dark">
	<tr>
	  <th rowspan="2" scope="col" style="width:10px;vertical-align:middle;">S.No</th>
	  <th rowspan="2" scope="col" style="width:300px;vertical-align:middle;">Claimer</th>
	  <th rowspan="2" scope="col" style="width:50px;vertical-align:middle;">EmpCode</th>
	  <th rowspan="2" scope="col" style="width:50px;vertical-align:middle;">Month</th>
	  <th rowspan="2" scope="col" style="width:60px;vertical-align:middle;">Claim Type</th>
	  <th colspan="7" scope="col" style="width:80px;vertical-align:middle;">Date</th>
	</tr>
	<tr>
	  <th scope="col" style="width:80px;vertical-align:middle;">Upload</th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Filled</th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Submit</th>
	  <th scope="col" style="width:80px;vertical-align:middle;">Verify</th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Approved</th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Finance</th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Paid</th> 
	</tr>
  </thead>
  <tbody>
				  
<?php	  			
  $m=mysql_query("SELECT count(*) as tot FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE  ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and (ClaimMonth=".$_REQUEST['v']." OR (CrDate>='".date("Y-".$_REQUEST['v']."-01")."' AND CrDate<='".date("Y-".$_REQUEST['v']."-31")."')) order by CrBy asc, CrDate asc");
  $mlist=mysql_fetch_assoc($m);
  if($mlist['tot']>0)
  {	
?>
  <form action="" class="form-horizontal" role="form">
   <?php if($_REQUEST['v']>0){ ?>
   <tr style="height:40px;">
	<td colspan="15" style="text-align:left; vertical-align:middle;">
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	 <span style="font-size:16px;color:#000099;cursor:pointer; font-family:'Times New Roman', Times, serif;" onclick="ExpRep(1,<?=$_REQUEST['v']?>)"><u>Export - (Claim Wise)</u></span>
	  &nbsp;&nbsp;&nbsp;&nbsp;
	 <span style="font-size:16px;color:#000099;cursor:pointer; font-family:'Times New Roman', Times, serif;" onclick="ExpRep(2,<?=$_REQUEST['v']?>)"><u>Export - (Employee Wise)</u></span>
	 
	  <?php /*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <font style="font-size:16px;color:#CC0066;cursor:pointer;font-family:Times New Roman;"><b>New Level =></b></font>
	  <span style="font-size:16px;color:#000099;cursor:pointer;font-family:Times New Roman;" onclick="ExpRep(3,<?=$_REQUEST['v']?>)"><u>Export - (Claim Wise)</u></span>
	  &nbsp;&nbsp;&nbsp;&nbsp;
	 <span style="font-size:16px;color:#000099;cursor:pointer; font-family:'Times New Roman', Times, serif;" onclick="ExpRep(4,<?=$_REQUEST['v']?>)"><u>Export - (Employee Wise)</u></span> */ ?>
	 
	</td>
   </tr>
   <?php } ?>
 </form>						

<?php 
 } //if
?>

 </tbody>  
</table>	
  </div>
<?php } // if($_REQUEST['v']>0)?>			
	</div>
	<br>




				
<script type="text/javascript" src="js/reports.js"></script>















