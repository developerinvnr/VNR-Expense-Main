<?php
include "header.php";



// if(isset($_POST['fsavebtn'])){
// 	$selexist=mysql_query("SELECT * FROM `claimtype` WHERE `Year`='".$_POST['FYearId']."'");
// 	if(mysql_num_rows($selexist) > 0){
// 		$msg=$_POST['FYearId'].' Financial Year Already Exists'; $msgcolor='danger';
// 	}else{
// 		$newyr=mysql_query("INSERT INTO `claimtype`( `Year`, `Status`, `CrBy`) VALUES ('".$_POST['FYearId']."','".$_POST['status']."',".$_SESSION['EmployeeID'].")");
// 		if($newyr){$msg='New Financial Year Created'; $msgcolor='success';}
// 	}
	
// }


if(isset($_POST['claimupdtbtn'])){
	$selexist=mysql_query("SELECT * FROM `claimtype` WHERE `ClaimId`='".$_POST['ClaimId']."'");
	if(mysql_num_rows($selexist) > 0){
		$yrupdt=mysql_query("UPDATE `claimtype` set `ClaimCode`='".$_POST['ClaimCode']."', `ClaimStatus`='".$_POST['status']."' WHERE `ClaimId`='".$_POST['ClaimId']."'");
		if($yrupdt){$msg='Claim Updated Successfully'; $msgcolor='success';}
		
	}else{
		$msg='Claim Not Updated'; $msgcolor='danger';
	}
}



?>

<div class="container">
	<div class="row shadow">
		<div class="col-md-5">
			<?php if(isset($msg)){ ?>
				<div class="alert alert-<?=$msgcolor?> alert-dismissible">
			    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			    <strong><?=$msg?></strong>
			  </div>
			
			<?php } ?>
			<br>
			<!-- <button class="btn btn-sm btn-outline-primary " onclick="shownewadd()">New Financial Year +</button> -->


			<div class="table-responsive">
				<table class="table shadow">

				  <thead class="thead-dark">
				    <tr>
				      <th scope="col" style="width:60px;">S.No</th>
				      <th scope="col" >Claim Name</th>
				      <th scope="col" >Claim Code</th>
				      <th scope="col" style="width: 50px;">Status</th>
				      <th scope="col">Action</th>

				    </tr>
				    <!-- 
				    <tr id="newaddtr" style="display: none;">
				      <form action="claimmaster.php" method="post">
				      <td scope="col" style="width: 30px;">
				      	
				      </td>
				      <td scope="col" >
				      	<select class="form-control" name="FYearId" >
					    	<?php
						    	$startYear = 2017;
						    	$curMonth  = date('m');
						    	
						    	//below logic is to choose the current financial year as per current month
						    	if($curMonth >= 04){
						    		$curFYearId = date('Y').'-'.date('Y',strtotime('+1 year'));
						    	}else{
						    		$curFYearId = date('Y',strtotime('-1 year')).'-'.date('Y');
						    	}

						    	while($startYear <= (date('Y')+10)){
						    		$FYearId=$startYear.'-'.($startYear+1);
						    		?>
						    		<option value="<?=$FYearId?>" <?php if($FYearId==$curFYearId){echo 'selected';}?>><?=$FYearId?></option>
						    		<?php
						    		$startYear++;
						    	}
						    ?>
					    </select>
				      </td>
				      <td scope="col">
				      	<select  class="form-control" name="status">
				      		<option>Active</option>
				      		<option>Deactive</option>
				      	</select>
				      </td>
				      <td scope="col" >
				      	<button class="btn btn-sm btn-primary " type="submit" name="fsavebtn">Save</button>
				      </td>
				      </form>
				    </tr>
					-->


				  </thead>
				  <tbody>
				  	<?php
				  	$selc=mysql_query("SELECT * FROM `claimtype`");
				  	$i=1;
				  	while($selcd=mysql_fetch_assoc($selc)){
				  		if(isset($_REQUEST['edit']) && $_REQUEST['edit']==$selcd['ClaimId']){
				  			?>
						    <tr >
						    	<form action="claimmaster.php" method="post">
						      	<th scope="row">
							      	<?=$i?>
						      		<input type="hidden" name="ClaimId" value="<?=$selcd['ClaimId']?>">
						      	</th>
						      	<td>
						      		<?=$selcd['ClaimName']?>
						      	</td>
						      	<td>
						      		<input class="form-control" style="width: 70px"  type="text" name="ClaimCode" value="<?=$selcd['ClaimCode']?>" onkeyup="this.value = this.value.toUpperCase();" maxlength="5">
						      	</td>
								<td>
									<select  class="form-control" name="status">
										<option value="Active" <?php if($selcd['ClaimStatus']=='A'){echo 'selected';}?>>Active</option>
										<option value="Deactive" <?php if($selcd['ClaimStatus']=='D'){echo 'selected';}?>>Deactive</option>
									</select>
								</td>
								<td>
									<button class="btn btn-sm btn-primary" name="claimupdtbtn" type="submit">Update</button>
								</td>
								</form>
						    </tr>
						    <?php
				  			
				  		}else{
				  			?>
						    <tr >

						      <th scope="row"><?=$i?></th>
						      <td><?=$selcd['ClaimName']?></td>
						      <td>
						      	<input type="text" readonly class="form-control" style="width: 70px" value="<?=$selcd['ClaimCode']?>">
						      </td>
						      <td><?php if($selcd['ClaimStatus']=='A'){echo 'Active';}else{echo 'Deactive';}?></td>
						      <td>
						      	<button class="btn btn-sm btn-primary" onclick="edit('<?=$selcd['ClaimId']?>')">Edit</button>
						      </td>


						    </tr>
						    <?php
				  		}
				  	$i++;
					}
					
					?>
				  </tbody>
				</table>
			</div>
			
		</div>
		<div class="col-md-4" id="udetsdiv">
			

			
			
		</div>
		
	</div>
	
</div>




<?php
include "footer.php";
?>

<script type="text/javascript" src="js/user.js"></script>
<script type="text/javascript">


// function shownewadd(){
// 	$('#newaddtr').css('display','table-row');
// }

function edit(id){
	window.location.href = 'claimmaster.php?edit='+id;
}
</script>
