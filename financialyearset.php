<?php
include "header.php";



if(isset($_POST['fsavebtn'])){
	$selexist=mysql_query("SELECT * FROM `financialyear` WHERE `Year`='".$_POST['FYearId']."'");
	if(mysql_num_rows($selexist) > 0){
		$msg=$_POST['FYearId'].' Financial Year Already Exists'; $msgcolor='danger';
	}else{
		$newyr=mysql_query("INSERT INTO `financialyear`( `Year`, `Status`, `CrBy`) VALUES ('".$_POST['FYearId']."','".$_POST['status']."',".$_SESSION['EmployeeID'].")");
		if($newyr){$msg='New Financial Year Created'; $msgcolor='success';}
	}
	
}


if(isset($_POST['yearupdtbtn'])){
	$selexist=mysql_query("SELECT * FROM `financialyear` WHERE `YearId`='".$_POST['YearId']."'");
	if(mysql_num_rows($selexist) > 0){
		$yrupdt=mysql_query("UPDATE `financialyear` set `Year`='".$_POST['FYearId']."', `Status`='".$_POST['status']."' WHERE `YearId`='".$_POST['YearId']."'");
		if($yrupdt){$msg='Financial Year Updated Successfully'; $msgcolor='success';}
		
	}else{
		$msg=$_POST['FYearId'].' Financial Year Not Updated'; $msgcolor='danger';
	}
	
}



?>

<div class="container">
	<div class="row shadow">
		<div class="col-md-4">
			<?php if(isset($msg)){ ?>
				<div class="alert alert-<?=$msgcolor?> alert-dismissible">
			    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			    <strong><?=$msg?></strong>
			  </div>
			
			<?php } ?>
			<br>
			<button class="btn btn-sm btn-outline-primary " onclick="shownewadd()">New Financial Year +</button>


			<div class="table-responsive">
				<table class="table shadow">

				  <thead class="thead-dark">
				    <tr>
				      <th scope="col" style="width:60px;">Year ID</th>
				      <th scope="col" >Financial Year</th>
				      <th scope="col" style="width: 50px;">Status</th>
				      <th scope="col">Action</th>

				    </tr>

				    <tr id="newaddtr" style="display: none;">
				      <form action="financialyearset.php" method="post">
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
				  </thead>
				  <tbody>
				  	<?php
				  	$sely=mysql_query("SELECT * FROM `financialyear`");

				  	while($selyd=mysql_fetch_assoc($sely)){
				  		if(isset($_REQUEST['edit']) && $_REQUEST['edit']==$selyd['YearId']){
				  			?>
						    <tr >
						    	<form action="financialyearset.php" method="post">
						      <th scope="row">
							      	<?=$selyd['YearId']?>
						      		<input type="hidden" name="YearId" value="<?=$selyd['YearId']?>">
						      	</th>
						      <td>
						      		<select class="form-control" name="FYearId" >
								    	<?php
									    	$startYear = 2017;									    	

									    	while($startYear <= (date('Y')+10)){
									    		$FYearId=$startYear.'-'.($startYear+1);
									    		?>
									    		<option value="<?=$FYearId?>" <?php if($FYearId==$selyd['Year']){echo 'selected';}?>><?=$FYearId?></option>
									    		<?php
									    		$startYear++;
									    	}
									    ?>
								    </select>

						      </td>
						      <td>
						      	<select  class="form-control" name="status">
						      		<option value="Active" <?php if($selyd['Status']=='Active'){echo 'selected';}?>>Active</option>
						      		<option value="Deactive" <?php if($selyd['Status']=='Deactive'){echo 'selected';}?>>Deactive</option>
						      	</select>
						      </td>
						      <td>
						      	<button class="btn btn-sm btn-primary" name="yearupdtbtn" type="submit">Update</button>
						      </td>
							  </form>

						    </tr>
						    <?php
				  			
				  		}else{
				  			?>
						    <tr >

						      <th scope="row"><?=$selyd['YearId']?></th>
						      <td><?=$selyd['Year']?></td>
						      <td><?=$selyd['Status']?></td>
						      <td>
						      	<button class="btn btn-sm btn-primary" onclick="edit('<?=$selyd['YearId']?>')">Edit</button>
						      </td>


						    </tr>
						    <?php
				  		}
				  	
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


function shownewadd(){
	$('#newaddtr').css('display','table-row');
}

function edit(id){
	window.location.href = 'financialyearset.php?edit='+id;
}
</script>
