<?php session_start();  
if($_SESSION['CompanyId']=='' || $_SESSION['EmployeeID']=='') //|| $_SESSION['CheckLogin']==''
{
 echo "<script>window.location.href = 'index.php?msg=Please Login Again&msgcolor=danger'</script>";
}

include "header.php"; 

if(($_SESSION['EmpRole']=='E' OR $_SESSION['EmpRole']=='A') AND $_SESSION['FYearId']>=4)
{
  $mnt=intval(date("m"));
  $chk=mysql_query("select * from y".$_SESSION['FYearId']."_monthexpensefinal where Month=".$mnt." AND EmployeeID=".$_SESSION['EmployeeID']); $rowchk=mysql_num_rows($chk);
  if($rowchk==0)
  {
   $ins=mysql_query("insert into y".$_SESSION['FYearId']."_monthexpensefinal(EmployeeID, Month, YearId, Status, Crdate) values(".$_SESSION['EmployeeID'].", ".$mnt.", ".$_SESSION['FYearId'].", 'Open', '".date("Y-m-d")."')");
  }
  
  $mnt2=intval(date("m"))-1;
  if($mnt2>=5 AND $_SESSION['FYearId']==4)
  {
   $chk=mysql_query("select * from y".$_SESSION['FYearId']."_monthexpensefinal where Month=".$mnt2." AND EmployeeID=".$_SESSION['EmployeeID']); $rowchk=mysql_num_rows($chk);
   if($rowchk==0)
   {
    $ins=mysql_query("insert into y".$_SESSION['FYearId']."_monthexpensefinal(EmployeeID, Month, YearId, Status, Crdate) values(".$_SESSION['EmployeeID'].", ".$mnt2.", ".$_SESSION['FYearId'].", 'Open', '".date("Y-m-d")."')");
   }
  }
  
  
  $mnt3=intval(date("m"))-2;
  if($mnt3>=6 AND $_SESSION['FYearId']==4)
  {
   $chk=mysql_query("select * from y".$_SESSION['FYearId']."_monthexpensefinal where Month=".$mnt3." AND EmployeeID=".$_SESSION['EmployeeID']); $rowchk=mysql_num_rows($chk);
   if($rowchk==0)
   {
    $ins=mysql_query("insert into y".$_SESSION['FYearId']."_monthexpensefinal(EmployeeID, Month, YearId, Status, Crdate) values(".$_SESSION['EmployeeID'].", ".$mnt3.", ".$_SESSION['FYearId'].", 'Open', '".date("Y-m-d")."')");
   }
  }
  
  
  
} 


if($_SESSION['EmpRole']=='E' OR $_SESSION['EmpRole']=='A')
{
  $eMM=mysql_query("select ExpId from y".$_SESSION['FYearId']."_expenseclaims where ClaimId=7 and CrBy=".$_SESSION['EmployeeID']." AND ClaimStatus='Filled' and VerifyBy=0"); $roweMM=mysql_num_rows($eMM); 
  if($roweMM>0)
  {
   
    while($reM=mysql_fetch_assoc($eMM))
    {
     $sAmt=mysql_query("select SUM(FilledTAmt) as TotalAmt from y".$_SESSION['FYearId']."_24_wheeler_entry where Totalkm>0 AND ExpId=".$reM['ExpId']);    
	 $rAmt=mysql_fetch_assoc($sAmt); 
	 if($rAmt['TotalAmt']>0)
	 {
	  $qry=mysql_query("update y".$_SESSION['FYearId']."_expenseclaims set FilledTAmt='".$rAmt['TotalAmt']."', VerifyTAmt='".$rAmt['TotalAmt']."', ApprTAmt='".$rAmt['TotalAmt']."', FinancedTAmt='".$rAmt['TotalAmt']."' where ClaimId=7 AND CrBy=".$_SESSION['EmployeeID']." AND ExpId=".$reM['ExpId']." AND ClaimStatus='Filled' and VerifyBy=0");
     }
	}
  
  }
  
} //if($_SESSION['EmpRole']=='E' OR $_SESSION['EmpRole']=='A')

?>

<?php if($_SESSION['EmpRole']=='E')
{ 
    $svs=mysql_query("select MoveRep from hrm_employee where EmployeeID=".$_SESSION['EmployeeID'],$con2); 
      $resv=mysql_fetch_assoc($svs); //echo 'aa='.$resv['MoveRep']; 
      if($resv['MoveRep']=='Y'){ $_SESSION['EmpRole']='A'; } 
}      
?>


<div class="container-fluid ">
<?php if($_SESSION['EmpRole']=='A'){ ?>
 <div class="row">
  <div class="col-lg-3" style="height:8px;"></div>
   <div style="border-left:5px solid #d9d9d9;padding:8px;background-color:white;width:100%; vertical-align:middle; text-align:left; height: 38px;" class="col-lg-9 font-weight-bold shadow">
   &emsp;<input checked type="radio" style="cursor: pointer;"  id="Claimerradio" name="role" onclick="showdiv('claimerdiv','apprdiv')"><label style="cursor: pointer;" for="Claimerradio" onclick="showdiv('claimerdiv','apprdiv')">&nbsp;<font style="font-size:15px;">Claimer</font></label>
   &nbsp;&nbsp;&nbsp;&nbsp;<input  type="radio" style="cursor: pointer;"  id="Approverradio" name="role" onclick="showdiv('apprdiv','claimerdiv')"><label style="cursor: pointer;" for="Approverradio" onclick="showdiv('apprdiv','claimerdiv')">&nbsp;<font style="font-size:15px;">Approver</font></label>
   </div>
  </div>
<?php } ?>

  <div class="row h-100">
<?php if($_SESSION['EmpRole']=='E' || $_SESSION['EmpRole']=='A'){?>
   <div class="col-lg-3  d-none  d-lg-block">
    <br><br><br><br><br><br>
    <h2 style="color:#9299a0;" class="text-right">Welcome<br>To</h2>
    <h1 style="color: #9299a0;margin-top: -30px;" class="text-right">
	 <img src="images/Xeasylogotransparentgrey.png" style="width:55%;">
    </h1>
    <div class="text-muted pull-right">Platform to Claim and Record Expense</div>
   </div>
<?php } ?>

  
<?php 
      switch ($_SESSION['EmpRole']) 
      {
	    case 'E':
		  include 'claimerhomedetails.php'; 
		  break;
		case 'M':
		  include 'mediatorhomedetails.php'; 
		  break;
		case 'V':
		  include 'verifierhomedetails.php'; 
		  break;
		case 'A':
          include 'claimerhomedetails.php';
		  include 'approverhomedetails.php'; 
		  break;
		case 'F':
		  include 'financehomedetails.php'; 
		  break;
		case 'P':
		  include 'paymenthomedetails.php';
		  break;         
      }
?>

   </div>
 </div>
 <?php include "footer.php"; ?>

</div>
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
  height: 110%;
  top:-70px;
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
  -webkit-animation-name: animatetop;
  -webkit-animation-duration: 0.4s;
  animation-name: animatetop;
  animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
  from {top:-300px; opacity:0} 
  to {top:-70px; opacity:1}
}

@keyframes animatetop {
  from {top:-300px; opacity:0}
  to {top:-70px; opacity:1}
}

/* The Close Button */
.close {
  position: absolute;
  top:0px;
  color: #000;
  right: 4px;
  font-size: 28px;
  font-weight: bold;
  display: block;
  cursor: pointer;

}

/*.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}*/

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
    <span class="close pull-right" >&times;</span>&emsp;<br>
	
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

<script type="text/javascript">
  function showdiv(div,hdiv){
    $('#'+div).show();
    $('#'+hdiv).hide();
  }
</script>

<?php if(isset($_REQUEST['show']) && $_REQUEST['show']=='appr'){
  ?>
  <script type="text/javascript">
    document.getElementById("Approverradio").checked = true;
    showdiv('apprdiv','claimerdiv');
  </script>
  <?php
}?>


<style type="text/css">
@media only screen and (max-width: 600px) {
 /* small {
    font-size: 30px;
  }*/
}
</style>