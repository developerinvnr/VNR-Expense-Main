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
	$u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$u,$con2);
	$un=mysql_fetch_assoc($u);
	return $un['Fname'].' '.$un['Sname'].' '.$un['Lname'];
}

?>
<div class="container-fluid ">
 <div class="row  d-flex justify-content-around">
  <div class="col-md-10">
   <div class="row filrow font-weight-bold">
				
	<div class="col-md-2">Users:<select class="form-control" id="userfr">
    <?php $u=mysql_query("select EmployeeID,Fname,Sname,Lname from hrm_employee where EmpStatus='A' and COmpanyId=".$_SESSION['CompanyId']." group by EmployeeID order by Fname asc", $con2); 
	if(mysql_num_rows($u)>1){ ?><option value="ALL">ALL</option><?php }
	while($us=mysql_fetch_assoc($u)){ ?><option value="<?=$us['EmployeeID']?>" <?php if(isset($_REQUEST['u']) && $_REQUEST['u']==$us['EmployeeID']){echo 'selected';} ?>><?=$us['Fname'].' '.$us['Sname'].' '.$us['Lname']?></option><?php }	?></select>
	</div>
			      	
	<div class="col-md-2">Claim Type:<select class="form-control" id="claimTypefr">
	<option value="ALL">ALL</option>
	<?php $c=mysql_query("select ClaimId,ClaimName from claimtype where ClaimStatus='A' group by ClaimId");
		  while($cid=mysql_fetch_assoc($c)){ ?><option value="<?=$cid['ClaimId']?>" <?php if(isset($_REQUEST['ct']) && $_REQUEST['ct']==$cid['ClaimId']){echo 'selected';} ?>><?=$cid['ClaimName']?></option><?php } ?>
		  
	<?php if($_SESSION['EmployeeID']==11)
	     { 
	    
	    $c1=mysql_query("select ClaimId,ClaimName from claimtype where ClaimStatus='B' group by ClaimId");
		  while($cid1=mysql_fetch_assoc($c1)){ ?><option value="<?=$cid1['ClaimId']?>" <?php if(isset($_REQUEST['ct']) && $_REQUEST['ct']==$cid1['ClaimId']){echo 'selected';} ?>><?=$cid1['ClaimName']?></option><?php } ?>
		  
		  <option value="1920" <?php if(isset($_REQUEST['ct']) && $_REQUEST['ct']==1920){echo 'selected';} ?>>DA@ In/Out</option>
		  
		  
	<?php } //if($_SESSION['EmployeeID']==11) ?>	  
		  
		  </select>
	</div>
	<div class="col-md-2">Claim Status:<select class="form-control" id="claimStatusfr">
	 <option value="Submitted" <?php if($_REQUEST['cs']=='Submitted'){echo 'selected';} ?>>Submitted</option>
	 <option value="Filled" <?php if($_REQUEST['cs']=='Filled'){echo 'selected';} ?>>Filled</option>
	 <?php if($_SESSION['EmpRole']!='M'){ ?> 
	 <option value="ALL">ALL</option>
	 <option value="Verified" <?php if($_REQUEST['cs']=='Verified'){echo 'selected';} ?>>Verified</option>
	 <option value="Approved" <?php if($_REQUEST['cs']=='Approved'){echo 'selected';} ?>>Approved</option>
	 <option value="Financed" <?php if($_REQUEST['cs']=='Financed'){echo 'selected';} ?>>Paid</option>
     <?php } ?> 
	 </select>
	</div>

	<?php
	//here setting bu default from and to date to current date if the dates not set
	if(!isset($_REQUEST['f']) && !isset($_REQUEST['t']))
	{
	 $_REQUEST['f']= date("d-m-Y");
	 $_REQUEST['t']= date("d-m-Y");
	}
	?>
	<div class="col-md-2">From<input id="fromdtfr" class="form-control" value="<?php if(isset($_REQUEST['f'])){echo $_REQUEST['f'];}?>"></div>
	<div class="col-md-2">To<input id="todtfr" class="form-control" value="<?php if(isset($_REQUEST['t'])){echo $_REQUEST['t'];}?>"></div>
	<div class="col-md-<?php if($_SESSION['EmpRole']=='E'){ echo '4';}else{echo '2';} ?>">
	<br><button class="form-control btn-primary" onclick="filter()">Search</button>
	</div>
    
	
	<div class="col-md-12" style="text-align:center;">
	 <input type="radio" id="rdo1" name="BUFDate" <?php if($_REQUEST['ff']=='' || $_REQUEST['ff']=='B'){echo 'checked';} ?> onclick="FunRdoC('B')"/>&nbsp;Bill Date&nbsp;&nbsp;&nbsp;
	 <input type="radio" id="rdo2" name="BUFDate" <?php if($_REQUEST['ff']=='U'){echo 'checked';} ?> onclick="FunRdoC('U')"/>&nbsp;Upload Date&nbsp;&nbsp;
	 <input type="radio" id="rdo3" name="BUFDate" <?php if($_REQUEST['ff']=='F'){echo 'checked';} ?> onclick="FunRdoC('F')"/>&nbsp;Filled Date&nbsp;&nbsp;
	 
	 <input type="hidden" id="RdoSelN" name="RdoSelN" value="<?php if($_REQUEST['ff']==''){echo 'B';}else{echo $_REQUEST['ff'];} ?>" /> 
	 <script>
	 function FunRdoC(v)
	 {
	  document.getElementById("RdoSelN").value=v;
	 }
	 </script>
	</div>
	
	
   </div>      	
   <div class="row filrow font-weight-bold">
	<div class="table-responsive d-flex justify-content-center align-items-center">
	 <table class="table shadow table-responsive" id="ReportTable">
      <thead class="thead-dark">
		<?php if(isset($_REQUEST['u']) && isset($_REQUEST['ct'])){ ?>
		<tr>
		 <td colspan="12">
		 <button class="form-control btn-primary" onclick="ResultExp('<?=$_REQUEST['u']?>','<?=$_REQUEST['ct']?>','<?=$_REQUEST['cs']?>','<?=$_REQUEST['ff']?>')" style="cursor:pointer;width:100px;">Export</button>
		 </td>
		</tr>
			
		<?php } ?>
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

		if(isset($_REQUEST['ct']) && $_REQUEST['ct']=='1920')
		{
		  $ctcond="(e.ClaimId=19 OR e.ClaimId=20)"; 
		    
		}else if(isset($_REQUEST['ct']) && $_REQUEST['ct']!='' && $_REQUEST['ct']!='ALL' && $_REQUEST['ct']!='1920'){
			$ctcond="e.ClaimId='".$_REQUEST['ct']."'";
		}else{ $ctcond="1=1"; }

		if(isset($_REQUEST['cs']) && $_REQUEST['cs']!='' && $_REQUEST['cs']!='ALL'){
			
			if($_REQUEST['cs']=='Submitted')
			{
			 $cscond="(e.ClaimStatus='Submitted' OR e.ClaimStatus='Filled' OR e.ClaimStatus='Verified' OR e.ClaimStatus='Approved' OR e.ClaimStatus='Financed')";
			}
			elseif($_REQUEST['cs']=='Filled')
			{
			 $cscond="(e.ClaimStatus='Filled' OR e.ClaimStatus='Verified' OR e.ClaimStatus='Approved' OR e.ClaimStatus='Financed')";
			}
			elseif($_REQUEST['cs']=='Verified')
			{
			 $cscond="(e.ClaimStatus='Verified' OR e.ClaimStatus='Approved' OR e.ClaimStatus='Financed')";
			}
			elseif($_REQUEST['cs']=='Approved')
			{
			 $cscond="(e.ClaimStatus='Approved' OR e.ClaimStatus='Financed')";
			}
			elseif($_REQUEST['cs']=='Financed')
			{
			 $cscond="e.ClaimStatus='Financed'";
			}
			
			
			
		}else{ $cscond="1=1"; }

		if(isset($_REQUEST['f']) && isset($_REQUEST['t'])){
			$f=$_REQUEST['f']!='' ? date("Y-m-d",strtotime($_REQUEST['f'])) : '2018-01-01';
			//here by default given 2018-01-01 because the site started from 2018 so claims must be uploaded after that
			$t=$_REQUEST['t']!='' ? date("Y-m-d",strtotime($_REQUEST['t'])) : date("Y-m-d");
			//here by default given current date because claims must be uploaded till today only
			//$dtcond="e.CrDate between '".$f."' and '".$t."'";
			
			if($_REQUEST['ff']=='B'){ $dtcond="e.BillDate between '".$f."' and '".$t."'"; }
			elseif($_REQUEST['ff']=='U'){ $dtcond="e.CrDate between '".$f."' and '".$t."'"; }
			elseif($_REQUEST['ff']=='F'){ $dtcond="e.FilledDate between '".$f."' and '".$t."' and CrBy!=FilledBy"; }
			else{ $dtcond="e.BillDate between '".$f."' and '".$t."'"; }
			
		}else{ $dtcond="1=1"; }
		
		//if($f>='2022-04-01'){$y=4;}else{$y=3;}
	    //$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and e.ClaimYearId='".$y."' and (c.ClaimId=e.ClaimId or e.ClaimId=0) and ".$ucond." and ".$ctcond." and e.ClaimStatus!='Deactivate' and ".$cscond." and ".$dtcond." group by e.ExpId order by e.BillDate ASC";

       if($_REQUEST['ct']=='1920')
       {
           
        $q="SELECT e.*, sum(FilledTAmt) as FillAmt, sum(VerifyTAmt) as VeriAmt, sum(ApprTAmt) as AppAmt, sum(FinancedTAmt) as PaidAmt, c.ClaimName FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c where e.ClaimYearId='".$_SESSION['FYearId']."' and (c.ClaimId=e.ClaimId or e.ClaimId=0) and ".$ucond." and ".$ctcond." and e.ClaimStatus!='Deactivate' and ".$cscond." and ".$dtcond." group by e.CrBy, e.ClaimMonth order by e.BillDate ASC";  
       }
       else
       {
	    $q="SELECT e.*, c.ClaimName FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c where e.ClaimYearId='".$_SESSION['FYearId']."' and (c.ClaimId=e.ClaimId or e.ClaimId=0) and ".$ucond." and ".$ctcond." and e.ClaimStatus!='Deactivate' and ".$cscond." and ".$dtcond." group by e.ExpId order by e.BillDate ASC";
       } 
	    //echo $q;
	    
	    $seleq=mysql_query($q);
		$i=1;
		while($exp=mysql_fetch_assoc($seleq)){
		?>
		<tr onclick="showdet('<?=$exp['EmployeeID']?>')">

		  <th scope="row"><?=$i?></th>
		  <td><?=$exp['ExpId']?></td>
		  <!-- <td><?=$exp['ExpenseName']?></td> -->
		  <td><?=$exp['ClaimName']?></td>
		  
		  <?php $u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$exp['CrBy'],$con2); $un=mysql_fetch_assoc($u); ?>
		  
		  <td><?=$un['Fname'].' '.$un['Sname'].' '.$un['Lname']?></td>
		  
		  
		  <?php if($_REQUEST['ct']=='1920'){ ?>
		  
		  <td></td>
		  <td></td>
		  <td><?php echo $exp['FillAmt'];?></td>
		  <td><?php echo $exp['VeriAmt'];?></td>
		  <td><?php echo $exp['AppAmt'];?></td>
		  <td><?php echo $exp['PaidAmt'];?></td>
		 
		 <?php } else { ?>
		   <td><?=date("d-m-Y",strtotime($exp['CrDate']))?></td>
		  <td><?=date("d-m-Y",strtotime($exp['BillDate']))?></td>
		  <td><?php if($exp['FilledTAmt']!=0 && $exp['FilledBy']>0 && $exp['FilledDate']!='0000-00-00'){echo $exp['FilledTAmt'];}?></td>
		  <td><?php if($exp['VerifyTAmt']!=0 && $exp['VerifyBy']>0 && $exp['VerifyDate']!='0000-00-00'){echo $exp['VerifyTAmt'];}?></td>
		  <td><?php if($exp['ApprTAmt']!=0 && $exp['ApprBy']>0 && $exp['ApprDate']!='0000-00-00'){echo $exp['ApprTAmt'];}?></td>
		  <td><?php if($exp['FinancedTAmt']!=0 && $exp['FinancedBy']>0 && $exp['FinancedDate']!='0000-00-00'){echo $exp['FinancedTAmt'];}?></td>
		 <?php } ?> 
		
		 <?php /*
		  <td><?php if($exp['FilledTAmt']!=0){echo $exp['FilledTAmt'];}?></td>
		  <td><?php if($exp['VerifyTAmt']!=0){echo $exp['VerifyTAmt'];}?></td>
		  <td><?php if($exp['ApprTAmt']!=0){echo $exp['ApprTAmt'];}?></td>
		  <td><?php if($exp['FinancedTAmt']!=0){echo $exp['FinancedTAmt'];}?></td>
		  */ ?>
		  <td>
		  <?php if($exp['ClaimStatus']=='Financed'){ ?><div class="btn btn-sm btn-success">Paid</div><?php
			    }else{ ?><div class="btn btn-sm btn-warning"><?=$exp['ClaimStatus']?></div><?php } ?>
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


function filter()
{
 var u = $('#userfr').val();
 var cs = $('#claimStatusfr').val();
 var ct = $('#claimTypefr').val();
 var f = $('#fromdtfr').val();
 var t = $('#todtfr').val();
 var ff = $('#RdoSelN').val(); 
 window.open("reports.php?u="+u+"&ct="+ct+"&cs="+cs+"&f="+f+"&t="+t+"&ff="+ff,"_self");
}


function ResultExp()
{
 var u = $('#userfr').val(); var cs = $('#claimStatusfr').val();
 var ct = $('#claimTypefr').val(); var f = $('#fromdtfr').val();
 var t = $('#todtfr').val(); var ff = $('#RdoSelN').val(); 
 
 //alert("reportsexp.php?act=resultexp&u="+u+"&ct="+ct+"&cs="+cs+"&f="+f+"&t="+t+"&ff="+ff);
 
 var win = window.open("reportsexp.php?act=resultexp&u="+u+"&ct="+ct+"&cs="+cs+"&f="+f+"&t="+t+"&frf="+ff,"ExpForm","menubar=no,scrollbars=yes,resizable=no,directories=no,width=50,height=50");
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
