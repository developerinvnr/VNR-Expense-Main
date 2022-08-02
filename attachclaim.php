<?php
session_start();
if(!isset($_SESSION['login'])){
  session_destroy();
  header('location:index.php');
}
include 'config.php';
?>
<link rel="stylesheet" href="css/jquery.datetimepicker.css">
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/style.css">


<?php
if($_SESSION['EmpRole']=='E'){
	$crby=$_SESSION['EmployeeID'];
}else{
	$crby=$_REQUEST['crby'];
}


?>


<?php $ss='<script>document.write(sw);</script>'; ?>
<div class="container">
 	<div class="row">
 		<h3>Select Claims To Attach</h3>
		<div class="col-md-5 h-100 " style="padding:0px;">
		 	
		 	<?php
			$m=mysql_query("SELECT ClaimMonth FROM `y".$_SESSION['FYearId']."_expenseclaims`  WHERE  ExpId='".$_REQUEST['expid']."'"); 
			$md=mysql_fetch_assoc($m);


			$cl=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims`  WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `ClaimMonth`='".$md['ClaimMonth']."' and `CrBy`='".$crby."' and e.ClaimStatus='Submitted' "); 


			?>
			<table class="table shadow" style="width:100%;" align="center">
			  <thead class="thead-dark">
			    <tr>
					<th scope="col" style="width:20px;background-color:#008C8C;"><font style="font-size:11px;">Sn</font></th>
					
					<th scope="col" style="width:70px;background-color:#008C8C;"><font style="font-size:11px;">Claim Type</font></th>
					<th scope="col" style="width:90px;background-color:#008C8C;"><font style="font-size:11px;">Date</font></th>

					<th scope="col" style="width:70px;background-color:#008C8C;"><font style="font-size:11px;">Claim<br>Uploads</font></th>

					<th scope="col" style="width:140px;background-color:#008C8C;"><font style="font-size:11px;">Select to Attach</th>
					
			    </tr>
			  </thead>
			  <tbody>
			  	
				<?php
				$cl=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims`  WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `ClaimMonth`='".$md['ClaimMonth']."' and `CrBy`='".$crby."' and ExpId!='".$_REQUEST['expid']."' and ClaimStatus='Submitted' || ClaimStatus='Draft' and AttachTo=0"); 
				$i=1;
				while($clm=mysql_fetch_assoc($cl)){	

		      	if($clm['ClaimStatus']=='Deactivated'){ $trcolor='background-color:#ffcccc;';}else{$trcolor='';} ?>


			    <tr onclick="showdet('<?=$clm['EmployeeID']?>')" style="<?=$trcolor?>">
					<th scope="row"><font style="font-size:11px;"><?=$i?></font></th>

					<td>
						<a href="#" onclick="showexpdet('<?=$clm['ExpId']?>')">
							<font style="font-size:11px;">
								<?php echo substr('Not Filled', 0, 12)?>
							</font>
						</a>
					</td>
					<td>
						
						<font style="font-size:11px;font-weight: bold;">
							<?=date('d-m-Y',strtotime($clm['CrDate']))?>
						</font>
						
					</td>

					<td>
						<?php
						$user=$crby;
						$uY = "documents/".$_SESSION['FYearId'] ."/".$user."/";
						$location=$uY;
						$eu=mysql_query("select * from y".$_SESSION['FYearId']."_claimuploads cu, y".$_SESSION['FYearId']."_expenseclaims e where cu.ExpId=e.ExpId and (e.ExpId=".$clm['ExpId']." or e.AttachTo=".$clm['ExpId'].") order by UploadSequence asc");
						
						while($eup=mysql_fetch_assoc($eu)){	
							?>
							<img id="img<?=$i?>" src="<?=$location.$eup['FileName']?>" style="vertical-align:top;margin-top:4px;height:50px;border:3px solid <?php if($i==1){echo '#eee';}else{echo '#4d4d4d';} ?>; cursor: pointer;" onclick="showimginbig('<?=$location.$eup['FileName']?>')">
							<?php
						}
						?>
					</td>
			       
					<td style="text-align: left;">
						<input type="checkbox" onclick="attachthis(this,'<?=$_REQUEST['expid']?>','<?=$clm['ExpId']?>')">

						<span id="res<?=$clm['ExpId']?>">
					  	
					</span>
					</td>
			      
			    </tr>
			    <?php $i++; } ?>
				
				
				
				
			  </tbody>
			</table>

		</div>
   
	</div>
	
</div>
<input type="hidden" id="cid" value="<?=$clm['ClaimId']?>">

<script
        src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
        
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>

<script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>
<script src="js/jquery.datetimepicker.full.min.js"></script>

<script type="text/javascript">
	function attachthis(chk,attachid,expid){

		if(chk.checked){
           	$.post("homeajax.php",{act:"attach",expid:expid,attachid:attachid},function(data){
				if(data.includes('attached')){
					var spanfill="<div class='btn btn-sm btn-success'>Attached </div>";
					$('#res'+expid).html(spanfill);
				}
			});
        }else{
        	$.post("homeajax.php",{act:"unattach",expid:expid,attachid:attachid},function(data){
				if(data.includes('unattached')){
					var spanfill="<div class='btn btn-sm btn-warning'>UnAttached</div>";
					$('#res'+expid).html(spanfill);
				}
			});
        }
	}


	function showimginbig(sr){
		var modal = document.getElementById('myModal');
		modal.style.display = "block";

		document.getElementById('claimlistfr').src=sr;
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
			<iframe id="claimlistfr" src="" style="width:100%;height: 90%;"></iframe>
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