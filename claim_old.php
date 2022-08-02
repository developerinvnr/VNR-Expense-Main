<?php
include "header.php";
?>

<!-- <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css"> -->
<div class="container-fluid">
	<div class="row h-100">
		<div class="col-md-6 shadow">
			<?php
			if(isset($_REQUEST['prevupload'])){
			?>
			<button class="btn btn-primary btn-sm" onclick="showexpdet('<?=$_REQUEST['prevupload']?>')" style="position: absolute;float: left;left: 0px;margin:2px 15px;">
				View Previous Upload
			</button>
			<?php
			}
			?>
			<br>
			<center>
			<div class="table-responsive">
				
				<table class="table table-sm claimtable">
				  
					<thead class="thead-dark">
						<?php
						
						$m=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and YearId=".$_SESSION['FYearId']." and `Status`='Open' limit 1");
						  if(mysql_num_rows($m)>0){
						    $ms=mysql_fetch_assoc($m);
						    $_SESSION['todayMonth']=date("m",strtotime('2019-'.$ms['Month'].'-01'));
						  }else{
						    $_SESSION['todayMonth']=4;
						  }
						  ?>
						<tr>
							<th scope="row"><p class="h6 pull-right tht">Month:</p></th>
							<th scope="row" style="width: 25%;">
								<input class="claimheadsel form-control pull-left" required form="claimform" autocomplete="off" readonly value="<?=date('F',strtotime(date('Y-'.$_SESSION['todayMonth'].'-d')))?>" style="">
							</th>
							
							<th colspan="15">
								<?php
								$c=mysql_query("select * from y".$_SESSION['FYearId']."_empclaimassign ca where ca.EmployeeID='".$_SESSION['EmployeeID']."' and ca.ClaimId=7");
								if(mysql_num_rows($c)>0){
								?>

								<div style="margin-bottom: -8px;">
								&emsp;&emsp;
								<p class="h6 tht" style="display: inline-block;"> Type:&nbsp;</p>
								<input type="radio" class="claimheadsel" style="display: inline !important;" name="claimtype" checked id="otherrad" onclick="claimradio(this.value)" value="1">
								<label for="otherrad"><p class="h6 tht" style="display: inline-block;"> Other</p></label>
								&emsp;
								<input type="radio" class="claimheadsel" style="display: inline !important;" name="claimtype" id="travelrad" onclick="claimradio(this.value)" value="2">
								<label for="travelrad"><p class="h6 tht" style="display: inline-block;"> Travel</p></label>
								</div>

								<?php
								}else{
									echo '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;';
								}
								?>



							</th>
						</tr>
					</thead>
				  
					<tbody id="claimformbody">
						<tr>
							<td colspan="10">
								<br>
								<form id="claimform" action="saveclaim.php" method="post" enctype="multipart/form-data">
								<button class="btn btn-sm btn-success form-control" id="submit" name="submitclaim" required disabled >Submit</button>
						  		</form>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div id="topCurMonUpl" class="table-responsive" >
				<table class="table shadow">
				  <thead class="thead-dark">
				  	<tr>
						<th scope="row" colspan="10"><p class="h6  tht"><?=date('F',strtotime(date('Y-'.$_SESSION['todayMonth'].'-d')))?> Claims:</p></th>
					</tr>
				    <tr>
				      <th scope="col" style="width: 30px;">S.No</th>				      
				      <th scope="col">Claim Type</th>
				      <th scope="col" >Uploads</th>
				      <th scope="col">Applied Date</th>
					  <th scope="row" class="text-center table-active"  style=""><span style="font-size: 10px !important;">Claimed</span><br>Amt</th>
				      <th scope="col">Claim Status</th>
				    </tr>
				  </thead>
				  <tbody>

				  	<tr class="totalrow">
				    	<th scope="row" colspan="4" class="text-right"><b>Total&nbsp;</b></th>
						
						<td class="text-right">
							<?php
				  			$totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");
				  			$clm=mysql_fetch_assoc($totpaid);
				  			echo $clm['paid'];
							?>
						</td>
						<?php
			  			$totpaid=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");
			  			$clm=mysql_fetch_assoc($totpaid);
			  			if($clm['paid']==0){
			  			?>
			  			<th colspan="5">
						</th>
			  			<?php
			  			}else{
						?>
						<td class="text-right">
							<?php
				  			$totpaid=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");
				  			$clm=mysql_fetch_assoc($totpaid);
				  			echo $clm['paid'];
							?>
						</td>
						<td class="text-right">
							<?php
				  			$totpaid=mysql_query("SELECT SUM(ApprTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");
				  			$clm=mysql_fetch_assoc($totpaid);
				  			echo $clm['paid'];
							?>
						</td>
						<td class="text-right">
							<?php
				  			$totpaid=mysql_query("SELECT SUM(FinancedTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");
				  			$clm=mysql_fetch_assoc($totpaid);
				  			echo $clm['paid'];
							?>
						</td>
						<th colspan="2">
						</th>
						<?php
						}
						?>
					  
				    </tr>	
				  	
				  	<?php
				  	
				  	$stepcond="1=1";
                    
					//$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims`e, claimtype c, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and e.ClaimMonth='".$_REQUEST['month']."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_REQUEST['emp']." and (c.ClaimId=e.ClaimId or e.ClaimId=0) and ".$stepcond." group by e.ExpId";
					
					
				  	$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and e.ClaimMonth='".$_SESSION['todayMonth']."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_SESSION['EmployeeID']." and (c.ClaimId=e.ClaimId or e.ClaimId=0) and ".$stepcond." group by e.ExpId";

				  	$seleq=mysql_query($q);

					$i=1;
				  	while($exp=mysql_fetch_assoc($seleq)){

				  	?>
				    <tr  >

				      <th scope="row"><?=$i?></th>
				      
				      <td><?php if($exp['ClaimId']!=0){echo $exp['ClaimName'];} ?> </td>
				      <?php
				      $user=$_SESSION['EmployeeID'];
				      $location = "documents/".$_SESSION['FYearId']."/".$user."/";
				      ?>
						
				      <td class="">
				      		<?php
							$eu=mysql_query("select * from y".$_SESSION['FYearId']."_claimuploads where ExpId=".$exp['ExpId']." order by UploadSequence asc");
							?>
							<span class="btn btn-sm btn-secondary"><?php echo mysql_num_rows($eu);?></span>
				      		<button class="btn btn-sm btn-primary" onclick="showexpdet('<?=$exp['ExpId']?>')">Details</button>
				      </td>
				      
				      <td><?=date("d-m-Y",strtotime($exp['CrDate']))?></td>
						
						<td class="text-right">
							<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed"))){ 
								$famt=intval($exp['FilledTAmt']);
								echo $famt;
							} ?>
						</td>
						
						
				      <td>
				      	<div class="btn btn-sm btn-outline-secondary font-weight-bold"><?=$exp['ClaimStatus']?></div>
				      </td>

				    </tr>
				    
				    <?php
				    $i++;
					}
					?>
					<tr class="totalrow">
				    	<th scope="row" colspan="4" class="text-right"><b>Total&nbsp;</b></th>
						
						<td class="text-right">
							
							<?php
							
				  			$totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

				  			$clm=mysql_fetch_assoc($totpaid);
				  			
				  			echo $clm['paid'];
							
							?>
							
						</td>
						<?php
			  			$totpaid=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

			  			$clm=mysql_fetch_assoc($totpaid);
			  			
			  			if($clm['paid']==0){

			  			?>
			  			<th colspan="5">
						</th>
			  			<?php

			  			}else{
						?>
						<td class="text-right">
							
							<?php
				  			$totpaid=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");
				  			$clm=mysql_fetch_assoc($totpaid);
				  			echo $clm['paid'];
							?>
							
						</td>
						<td class="text-right">
							
							<?php
				  			$totpaid=mysql_query("SELECT SUM(ApprTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

				  			$clm=mysql_fetch_assoc($totpaid);
				  			
				  			echo $clm['paid'];
							
							?>
							
						</td>
						<td class="text-right">
							
							<?php
				  			$totpaid=mysql_query("SELECT SUM(FinancedTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

				  			$clm=mysql_fetch_assoc($totpaid);
				  			
				  			echo $clm['paid'];
							
							?>
							
						</td>
						
						<th colspan="2">
						</th>

						
						<?php
						}
						?>
					  
				    </tr>
				  </tbody>
				</table>
			</div>	

			</center>
			
		</div>
		<div class="col-md-6 lg-h-100 xs-h-75 previewdiv">
			
			<div id="" style="position: relative;">
				<center>
					
					<button id="removeupload" class="btn btn-danger" onclick="showuploadbtn()" style="position: absolute;float: right;right: 0px;display: none;">
						<i class="fa fa-times-circle-o fa-2x" aria-hidden="true"></i>
					</button>

					<span id="uploadform">
						<br><br><br><br><br><br><br><br><br><br>
						<div>
						
						<form id="imageform" method="post" enctype="multipart/form-data" action='ajaximage.php'>
						    <label class="btn btn-outline-primary font-weight-bold">
						    <input type="file"  id="NewFile" name="NewFile[]" multiple>
						    Upload
						    </label>
					      	<input type="hidden" id="uuid" name="uuid" value="<?php echo $_SESSION['EmployeeID']; ?>" />
					   		<div class="text-muted">
					   			Upload jpg, png or pdf file only
					   		</div>
					   		<input type="hidden" name="winheight" id="winheight" value="0">
					   		<input type="hidden" id="prevRequestText" name="prevRequestText" value="" />
					    </form>
						</div>
				    </span>
				    <span id="preview">
				    	
				    </span>
				    
				</center>
			</div>
			
		</div>
		
		<div id="botCurMonUpl" class="col-md-12 lg-h-100 xs-h-75table-responsive" style="display: none;">
			<br>
			<table class="table shadow">
			  <thead class="thead-dark">
			  	<tr>
					<th scope="row" colspan="10"><p class="h6  tht"><?=date('F',strtotime(date('Y-'.$_SESSION['todayMonth'].'-d')))?> Claims:</p></th>
					
				</tr>
			    <tr>
			      <th scope="col" style="width: 30px;">S.No</th>				      
			      <th scope="col">Claim Type</th>
			      <th scope="col" >Uploads</th>
			      <th scope="col">Applied Date</th>
					<th scope="row" class="text-center table-active"  style=""><span style="font-size: 10px !important;">Claimed</span><br>Amt</th>
			      <th scope="col">Claim Status</th>
			    </tr>
			  </thead>
			  <tbody>

			  	<tr class="totalrow">
			    	<th scope="row" colspan="4" class="text-right"><b>Total&nbsp;</b></th>
			      
					
					<td class="text-right">
						
						<?php
						
			  			$totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

			  			$clm=mysql_fetch_assoc($totpaid);
			  			
			  			echo $clm['paid'];
						
						?>
						
					</td>
					<?php
		  			$totpaid=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

		  			$clm=mysql_fetch_assoc($totpaid);
		  			
		  			if($clm['paid']==0){

		  			?>
		  			<th colspan="5">
					</th>
		  			<?php

		  			}else{
					?>
					<td class="text-right">
						
						<?php
			  			$totpaid=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

			  			$clm=mysql_fetch_assoc($totpaid);
			  			
			  			echo $clm['paid'];
						
						?>
						
					</td>
					<td class="text-right">
						
						<?php
			  			$totpaid=mysql_query("SELECT SUM(ApprTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

			  			$clm=mysql_fetch_assoc($totpaid);
			  			
			  			echo $clm['paid'];
						
						?>
						
					</td>
					<td class="text-right">
						
						<?php
			  			$totpaid=mysql_query("SELECT SUM(FinancedTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

			  			$clm=mysql_fetch_assoc($totpaid);
			  			
			  			echo $clm['paid'];
						
						?>
						
					</td>
					
					<th colspan="2">
					</th>

					
					<?php
					}
					?>
				  
			    </tr>	
			  	
			  	<?php
			  	
			  	$stepcond="1=1";
			  	//$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims`e, claimtype c, ".dbemp.".hrm_employee h where c.ClaimId=e.ClaimId and h.EmployeeID=e.CrBy and e.ClaimMonth='".$_SESSION['todayMonth']."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_SESSION['EmployeeID']." and ".$stepcond;
				
				$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and e.ClaimMonth='".$_SESSION['todayMonth']."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_SESSION['EmployeeID']." and (c.ClaimId=e.ClaimId or e.ClaimId=0) and ".$stepcond." group by e.ExpId";

			  	$seleq=mysql_query($q);

				$i=1;
			  	while($exp=mysql_fetch_assoc($seleq)){

			  	?>
			    <tr  >

			      <th scope="row"><?=$i?></th>
			      
			      <td><?php if($exp['ClaimId']!=0){echo $exp['ClaimName'];} ?></td>
			      <?php
			      $user=$_SESSION['EmployeeID'];
			      $location = "documents/".$_SESSION['FYearId']."/".$user."/";
			      ?>
					
			      <td class="">
			      		<?php
						$eu=mysql_query("select * from y".$_SESSION['FYearId']."_claimuploads where ExpId=".$exp['ExpId']." order by UploadSequence asc");
						?>
						<span class="btn btn-sm btn-secondary"><?php echo mysql_num_rows($eu);?></span>
			      		<button class="btn btn-sm btn-primary" onclick="showexpdet('<?=$exp['ExpId']?>')">Details</button>
			      </td>
			      
			      <td><?=date("d-m-Y",strtotime($exp['CrDate']))?></td>
					
					<td class="text-right">
						<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed"))){ 
							$famt=intval($exp['FilledTAmt']);
							echo $famt;
						} ?>
					</td>
					
					
			      <td>
			      	<div class="btn btn-sm btn-outline-secondary font-weight-bold"><?=$exp['ClaimStatus']?></div>
			      </td>
			     
			    </tr>
			    
			    <?php
			    $i++;
				}
				?>
				<tr class="totalrow">
			    	<th scope="row" colspan="4" class="text-right"><b>Total&nbsp;</b></th>
			      
					
					<td class="text-right">
						
						<?php
						
			  			$totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

			  			$clm=mysql_fetch_assoc($totpaid);
			  			
			  			echo $clm['paid'];
						
						?>
						
					</td>
					<?php
		  			$totpaid=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

		  			$clm=mysql_fetch_assoc($totpaid);
		  			
		  			if($clm['paid']==0){

		  			?>
		  			<th colspan="5">
					</th>
		  			<?php

		  			}else{
					?>
					<td class="text-right">
						
						<?php
			  			$totpaid=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

			  			$clm=mysql_fetch_assoc($totpaid);
			  			
			  			echo $clm['paid'];
						
						?>
						
					</td>
					<td class="text-right">
						
						<?php
			  			$totpaid=mysql_query("SELECT SUM(ApprTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

			  			$clm=mysql_fetch_assoc($totpaid);
			  			
			  			echo $clm['paid'];
						
						?>
						
					</td>
					<td class="text-right">
						
						<?php
			  			$totpaid=mysql_query("SELECT SUM(FinancedTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_SESSION['todayMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

			  			$clm=mysql_fetch_assoc($totpaid);
			  			
			  			echo $clm['paid'];
						
						?>
						
					</td>
					
					<th colspan="2">
					</th>

					
					<?php
					}
					?>
				  
			    </tr>
			  </tbody>
			</table>
		</div>	
		
	</div>
	
</div>




<?php
include "footer.php";
?>


<?php
 
$year_arr = explode ("-", $_SESSION['FYear']); 
$earliest_year = $year_arr[0]; 
$latest_year = $year_arr[1]; 


$sm=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and `YearId`=".$_SESSION['FYearId']." and Status='Open' order by Month desc limit 1");


if(mysql_num_rows($sm)>0){
	$lm=mysql_fetch_assoc($sm);
	$lastmonth=$lm['Month'];
}else{$lastmonth=4;}

if($lastmonth>=4 && $lastmonth<=12){
	$yeartoshow=$earliest_year;
}elseif($lastmonth>=1 && $lastmonth<=3){
	$yeartoshow=$latest_year;
}

?>

<script type="text/javascript" src="js/claim.js"></script>

<script type="text/javascript">


function claimradio(a){

	if(a==1){
		var data='<tr> <td colspan="10"> <br> <form id="claimform" action="saveclaim.php" method="post" enctype="multipart/form-data"> <button class="btn btn-sm btn-success form-control" id="submit" name="submitclaim" required disabled >Submit</button> </form> </td> </tr>'; 

		$('#claimformbody').html(data);
	}else if(a==2){
		showclaimform(7);
	}
	
}

function addfaredetaa(){
    
   var c=parseInt($('#fdtcount').val());
    c++;
    $('#fdtcount').val(c);

    var aa='<tr> <td><input class="form-control" name="fdtitle'+c+'" style=""></td> <td> <input class="form-control text-right" id="fdamount'+c+'" name="fdamount'+c+'" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td>  <td  style="width: 20px;"> <button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)"> <i class="fa fa-times fa-sm" aria-hidden="true"></i> </button> </td> </tr>'; 

    $('#faredettbody').append(aa);     

}


$(document).ready(function(){
	
    $('#Date').datetimepicker({
        format:'d-m-Y',
        defaultDate:'01-<?=$lastmonth?>-<?=$yeartoshow?>',
        minDate: '<?=$yeartoshow?>-<?=$lastmonth?>-01',
        maxDate: '<?=$yeartoshow?>-<?=$lastmonth?>-31',
    });
    // $('#Date').datetimepicker("setDate", '2016-01-01');

    $('#Date').on('change', function(){
        $(this).datetimepicker('hide');
        var str = $(this).val();
        var res = str.split("-");
         $('#claimMonth').val(res[1]);

    }); //here closing the Date datetimepicker on date chang


    var sh=window.screen.availHeight;
	var sw=window.screen.availWidth;

	if(sh>sw){
		$('#topCurMonUpl').hide();
		$('#botCurMonUpl').show();
		$('#winheight').val(45);
	}else{
		$('#topCurMonUpl').show();
		$('#botCurMonUpl').hide();
		$('#winheight').val(98);
	}
});

$(window).resize(function() {
    var sh=window.screen.availHeight;
	var sw=window.screen.availWidth;

	if(sh>sw){
		$('#topCurMonUpl').hide();
		$('#botCurMonUpl').show();
		$('#winheight').val(45);
	}else{
		$('#topCurMonUpl').show();
		$('#botCurMonUpl').hide();
		$('#winheight').val(98);
	}
}).resize()

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
  top:-70px;
  height: 110%;
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
    
      <span class="close pull-right" >&times;</span><br>
      
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