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
	<div class="col-md-1">Deviation<br>Reports</div>
	<div class="col-md-2">Emp:<select class="form-control" id="userfr">
    <?php $u=mysql_query("select EmployeeID,Fname,Sname,Lname from hrm_employee where EmpStatus!='De' and CompanyId=".$_SESSION['CompanyId']." group by EmployeeID order by Fname asc",$con2); 
	if(mysql_num_rows($u)>1){ ?><option value="ALL">ALL</option><?php }
	while($us=mysql_fetch_assoc($u)){ ?><option value="<?=$us['EmployeeID']?>" <?php if(isset($_REQUEST['u']) && $_REQUEST['u']==$us['EmployeeID']){echo 'selected';} ?>><?=$us['Fname'].' '.$us['Sname'].' '.$us['Lname']?></option><?php }	?></select>
	</div>
	
	<input type="hidden" class="form-control" id="claimTypefr" />
	<input type="hidden" class="form-control" id="claimStatusfr" />		   
			      	
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
	
	<div class="col-md-1">Bill_Date<br><input type="radio" id="rdo1" name="BUFDate" <?php if($_REQUEST['ff']=='' || $_REQUEST['ff']=='B'){echo 'checked';} ?> onclick="FunRdoC('B')"/></div>
	<div class="col-md-1">Fin_Date<br><input type="radio" id="rdo2" name="BUFDate" <?php if($_REQUEST['ff']=='F'){echo 'checked';} ?> onclick="FunRdoC('F')"/>
	<input type="hidden" id="RdoSelN" name="RdoSelN" value="<?php if($_REQUEST['ff']==''){echo 'B';}else{echo $_REQUEST['ff'];} ?>" /> 
	
	 <script>
	 function FunRdoC(v){document.getElementById("RdoSelN").value=v;}
	 function FunWDa()
	 { if(document.getElementById("WDA").checked==true){ document.getElementById("WithDA").value=1; }
	   else{ document.getElementById("WithDA").value=0; }
	 }
	 </script>
	</div>
	
	<div class="col-md-1">With_DA<br><input type="checkbox" id="WDA" <?php if($_REQUEST['wda']==1){echo 'checked';} ?> onclick="FunWDa()"/><input type="hidden" id="WithDA" value="<?php if($_REQUEST['wda']!=''){echo $_REQUEST['wda'];}else{echo 0;}?>" />
	</div>
	
	<div class="col-md-1">
	<br><button class="form-control btn-primary" onclick="filter()">Search</button>
	</div>
 
<?php 
$sy=mysql_query("SELECT Year FROM financialyear where YearId=".$_SESSION['FYearId']);
$ry=mysql_fetch_assoc($sy); $yeard=$ry['Year'];

$fd=date("d-m-Y",strtotime($_REQUEST['f'])); $td=date("d-m-Y",strtotime($_REQUEST['t']));
if($_REQUEST['u']>0){$ei=$_REQUEST['u'];}else{$ei=0;}

//echo 'http://45.124.144.98:6868/focus/json?fdate='.$fd.'&tdate='.$td.'&year='.$yeard.'&eid='.$ei;
//echo 'http://192.168.3.250/focus/json?fdate='.$fd.'&tdate='.$td.'&year='.$yeard.'&eid='.$ei;


if($ei==0 OR $ei=='')
{ $json = file_get_contents('http://45.124.144.98:6868/focus/json?fdate='.$fd.'&tdate='.$td.'&year='.$yeard); }
else
{ $json = file_get_contents('http://45.124.144.98:6868/focus/json?fdate='.$fd.'&tdate='.$td.'&eid='.$ei.'&year='.$yeard); }

//echo $json;

$obj = json_decode($json);
?>	      
	
   </div>      	
   <div class="row filrow font-weight-bold">
	<div class="table-responsive d-flex justify-content-center align-items-center">
	 <table class="table shadow table-responsive" id="ReportTable">
      <thead class="thead-dark">
		<?php if(isset($_REQUEST['u']) && isset($_REQUEST['f']) && isset($_REQUEST['t'])){ ?>
		<tr>
		 <td colspan="12">
		 <button class="form-control btn-primary" onclick="ResultExp('<?=$_REQUEST['u']?>','<?=$_REQUEST['f']?>','<?=$_REQUEST['t']?>','<?=$_REQUEST['ff']?>','<?=$yeard?>',<?=$ei?>,'<?=$fd?>','<?=$td?>',<?=$_SESSION['FYearId'].','.$_REQUEST['wda']?>)" style="cursor:pointer;width:100px;">Export</button>
		 </td>
		</tr>
		<?php } ?>
		<tr>
		  <th scope="col" style="width:10px;">Sn</th>
		  <th scope="col" style="width:150px;">Employee</th>
		  <th scope="col" style="width:150px;">Claim Type</th>
		  <th scope="col" style="width:100px;">Claim Month</th>
		  <th scope="col" style="width:100px;">Finance Date</th>
		  <th scope="col" style="width:100px;"><span style="font-size: 9px !important;">Xeasy</span><br>Amt</th>
		  <th scope="col" style="width:100px;"><span style="font-size: 9px !important;">Focus</span><br>Amt</th>
		  <th scope="col" style="width:100px;"><span style="font-size: 9px !important;">Deviation</span><br>Amt</th>
		</tr>
	   </thead>
      <tbody>	

	  		  	
	  <?php
	    //filters user,claim status,fromdate,todate condition setting start//////////////
		if(isset($_REQUEST['u']) && $_REQUEST['u']!='' && $_REQUEST['u']!='ALL'){
			$ucond="x.CrBy=".$_REQUEST['u'];
		}else{ $ucond="1=1"; }

		if(isset($_REQUEST['f']) && isset($_REQUEST['t'])){
			$f=$_REQUEST['f']!='' ? date("Y-m-d",strtotime($_REQUEST['f'])) : date("Y-m-d");
			$t=$_REQUEST['t']!='' ? date("Y-m-d",strtotime($_REQUEST['t'])) : date("Y-m-d");
			
			if($_REQUEST['ff']=='B'){ $dtcond="x.BillDate between '".$f."' and '".$t."'"; }
			elseif($_REQUEST['ff']=='F'){ $dtcond="f.Finance_Date between '".$f."' and '".$t."'"; }
			else{ $dtcond="x.BillDate between '".$f."' and '".$t."'"; }
			
		}else{ $dtcond="1=1"; }

        
        
		$q="SELECT x.`ClaimId`,ClaimName,x.`ClaimMonth`,x.CrBy,sum(`FinancedTAmt`) as TotalAmount,x.FinancedDate, x.CrBy FROM `y".$_SESSION['FYearId']."_expenseclaims` x inner join claimtype ct on x.ClaimId=ct.ClaimId inner join y".$_SESSION['FYearId']."_monthexpensefinal f on (x.CrBy=f.EmployeeID AND x.ClaimMonth=f.Month) where x.`ClaimStatus`='Financed' AND x.FinancedBy>0 AND x.`FinancedDate`!='0000-00-00' AND x.`FinancedDate`!='1970-01-01' AND ".$ucond." and ".$dtcond." group by x.`ClaimId`, x.`ClaimMonth`, x.`CrBy` order by x.`CrBy`, x.`ClaimMonth` ";
			
			//echo $q;
			
		$seleq=mysql_query($q);
		
		$i=1;
		while($exp=mysql_fetch_assoc($seleq)){
		    
		    $u=mysql_query("SELECT EmpCode,Fname,Sname,Lname,GradeId FROM `hrm_employee` e inner join hrm_employee_general g on e.EmployeeID=g.EmployeeID where e.EmployeeID=".$exp['CrBy'], $con2); $un=mysql_fetch_assoc($u); 
		    
		    
		if(($exp['ClaimId']!=19 AND $exp['ClaimId']!=20 AND $un['GradeId']<=66) OR $un['GradeId']>66 OR $_REQUEST['wda']==1)
		{   
		?>
		<tr>
		  <td scope="row"><?=$i?></td>
		  <td><?=$un['Fname'].' '.$un['Sname'].' '.$un['Lname']?></td>
		   <td><?=$exp['ClaimName']?></td>
		  <td><?=date("F",strtotime($exp['FinancedDate']))?></td>
		  <td><?=date("d-m-Y",strtotime($exp['FinancedDate']))?></td>
		  <td style="text-align:right;"><?php if($exp['TotalAmount']>0){echo $exp['TotalAmount'];}?>&nbsp;</td>
		  
		  <td style="text-align:right;">
		  <?php $amount=0;
		   foreach($obj as $key =>$value)
           {
		    $ei=$value->emp_id;
            $claimid=$value->claim_id;
            $month=$value->claim_month;
            $year=$value->year_id;
		    $amount=$value->amount;
            $fin_date=date("d-m-Y",strtotime($value->created_at));
		    
		    //echo $ei.'=='.$exp['CrBy'].' && '.$claimid.'=='.$exp['ClaimId'].' && '.$month.'=='.$exp['ClaimMonth'].' && '.$year==$yeard;
		    
			if($ei==$exp['CrBy'] && $claimid==$exp['ClaimId'] && $month==$exp['ClaimMonth'] && $year==$yeard)
		    { echo $amount; }
			
		   }
		   ?>
		   &nbsp;</td>
		   <td style="text-align:right;">
		   <?php $amount=0;
		   foreach($obj as $key =>$value)
           {
		    $ei=$value->emp_id;
            $claimid=$value->claim_id;
            $month=$value->claim_month;
            $year=$value->year_id;
		    $amount=$value->amount;
            $fin_date=date("d-m-Y",strtotime($value->created_at));
		    
			if($ei==$exp['CrBy'] && $claimid==$exp['ClaimId'] && $month==$exp['ClaimMonth'] && $year==$yeard)
		    { echo $exp['TotalAmount']-$amount; } 
			
		   }
		   ?>&nbsp;
		   </td>
		  
		  
		</tr>
		<?php 
		$i++;
		 }
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
 var f = $('#fromdtfr').val();
 var t = $('#todtfr').val();
 var ff = $('#RdoSelN').val(); 
 var WDA = $('#WithDA').val();
 window.location="deviation.php?u="+u+"&f="+f+"&t="+t+"&ff="+ff+"&wda="+WDA;
}

function ResultExp(u,f,t,ff,y,ei,fd,td,yi,wda)
{
 var win = window.open("deviationexp.php?act=resultexp&u="+u+"&f="+f+"&t="+t+"&ff="+ff+"&y="+y+"&ei="+ei+"&fd="+fd+"&td="+td+"&yi="+yi+"&wda="+wda,"ExpForm","menubar=no,scrollbars=yes,resizable=no,directories=no,width=50,height=50");
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
