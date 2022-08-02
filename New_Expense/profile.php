<?php include "header.php"; ?>

<div class="container">
	<h3>Profile</h3>
	<div class="row shadow">
		
		<div class="col-md-4 table-responsive">
			<br>
			<table class="table table-sm shadow minpadtable">
			  <thead class="thead-dark">
			    <tr>
			      <th scope="col" colspan="2">
				      Details
				  </th>
			    </tr>
			  </thead>

			  <?php
			  if($_SESSION['EmpRole']=='E' || $_SESSION['EmpRole']=='A'){
			  	$table=dbemp.".`hrm_employee`";
			  }else{
			  	$table='`hrm_user`';
			  }
			  

			  $seluq=mysql_query("SELECT * FROM ".$table." where EmployeeID=".$_SESSION['EmployeeID']);
			  $selu=mysql_fetch_assoc($seluq);
			  ?>
			  <tbody>
			  	<tr>
			      <th scope="row" colspan="2">
				      Name &nbsp;&nbsp;
				      <input readonly class="form-control namesinp" type="text" id="Fnameinp" value="<?=$selu['Fname']?>" placeholder="First Name">
				      <input readonly class="form-control namesinp" type="text" id="Snameinp" value="<?=$selu['Sname']?>" placeholder="Second Name">
				      <input readonly class="form-control namesinp" type="text" id="Lnameinp" value="<?=$selu['Lname']?>" placeholder="Last Name">
				  </th>
			    </tr>
			    <tr>
			      <th scope="row">Login Id</th>
			      <td><input readonly class="form-control" type="text" id="EmpCodeinp" value="<?=$selu['EmpCode']?>"></td>
				</tr>
				<?php
				  if($_SESSION['EmpRole']!='E' && $_SESSION['EmpRole']!='A'){
				?>
				<tr>
			      <th scope="row">User Role</th>
			      <td>
			      	<select disabled class="form-control" id="EmpRoleinp">
			      		<option></option>
			      		<option value="S" <?php if($selu['EmpRole']=='S'){echo 'selected';}?>>Admin</option>
			      		<option value="M" <?php if($selu['EmpRole']=='M'){echo 'selected';}?>>Mediator</option>
						<option value="V" <?php if($selu['EmpRole']=='V'){echo 'selected';}?>>Verifier</option>
			      		<option value="F" <?php if($selu['EmpRole']=='F'){echo 'selected';}?>>Finance</option>
			      	</select>
			      </td>
				</tr>
				<?php
				}
				?>
				<tr>
			      <th scope="row">Status</th>
			      <td>
			      	<select disabled class="form-control" id="EmpStatusinp">
			      	
			      		<option value="A" <?php if($selu['EmpStatus']=='A'){echo 'selected';}?>>Active</option>
			      		<option value="D" <?php if($selu['EmpStatus']=='D'){echo 'selected';}?>>Deactive</option>
			      	</select>
			      	
			      </td>
				</tr>
				<tr>
			      <th scope="row">Email</th>
			      <td><input readonly class="form-control" type="email" id="EmailIdinp" value="<?=$selu['EmailId']?>"></td>
				</tr>
				<tr>
			    </tr>
			    <tr>
			      <th scope="row">Mobile</th>
			      <td><input readonly class="form-control" type="text" id="MobileNoinp" value="<?=$selu['MobileNo']?>" onkeypress="return isNumber(event)" maxlength="10"></td>
				</tr>
				<tr>
			    </tr>

			  </tbody>
			</table>
			<br>

			<?php
			  if($_SESSION['EmpRole']!='E' && $_SESSION['EmpRole']!='A'){
			?>
			<button type="button" id="chngpassbtn" class="btn btn-sm btn-outline-primary vsmbtn " onclick="showchngtbl()" style="font-weight: bold;">
		      <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Change Password
		  	</button>
		  	<?php

			}
			?>
		  	<br>
		  	
		  	<table class="table table-sm shadow minpadtable" id="changetbl" style="display: none;">
			  
		  		<thead class="thead-dark">
				    <tr>
				      <th scope="col" colspan="2">
					      Change Password &nbsp;&nbsp;
					  </th>
				    </tr>
				</thead>
			  
			  <tbody>
			  	
			    <tr>
			      <th scope="row">New Password</th>
			      <td><input class="form-control" type="password" id="newpass" value=""></td>
				</tr>
				<tr>
			      <th scope="row">Confirm Password</th>
			      <td><input class="form-control" type="password" id="confpass" value=""></td>
				</tr>
				<tr>
			      <th scope="row"></th>
			      <td>
			      	<button type="button" id="savepassbtn" class="btn btn-sm btn-primary vsmbtn " onclick="changepass('<?=$_SESSION['EmployeeID']?>')" style="font-weight: bold;">
				      &nbsp;<i class="fa fa-save" aria-hidden="true"></i> Save&nbsp;
				  	</button>
			      </td>
				</tr>
				

			  </tbody>
			</table>



		</div>
		<?php
		  if($_SESSION['EmpRole']=='E'){
		?>
		<div class="col-md-5 table-responsive">
			<br>
			<table class="table table-sm shadow minpadtable">
			  <thead class="thead-dark">
			    <tr>
			      <th scope="col" colspan="10">
				      Assigned Claim Types &nbsp;&nbsp;
				  </th>
			    </tr>
			    <tr>
			      <th scope="col" > Claim Name </th>
			      <th scope="col"> Claim Code </th>
			      <th scope="col" > Claim Limit </th>
			    </tr>
			  </thead>

			  
			  <tbody>
			  	
			  	<?php
				$selc=mysql_query("SELECT ct.ClaimName,ct.ClaimCode,cl.verifiedLimit FROM `y".$_SESSION['FYearId']."_empclaimassign` ea, `claimtype` ct, `claimlimits` cl where ea.EmployeeID='".$_SESSION['EmployeeID']."' and ea.ClaimId=ct.ClaimId and ea.EmployeeID=cl.EmployeeID and ct.ClaimId= cl.ClaimId");
				while($selcl=mysql_fetch_assoc($selc)){
				?>
			    <tr>
			      <th scope="row"><?=$selcl['ClaimName']?></th>
			      <th scope="row"><?=$selcl['ClaimCode']?></th>
			      
			      <td>
			      	<input type="text" class="form-control" readonly value="<?=$selcl['verifiedLimit']?>">
			      </td>
				</tr>
				
				<?php
				}
				?>
			  </tbody>
			</table>
		</div>

		<?php

		}
		?>


	</div>
	
</div>


<?php



function getUser($u){
	$u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$u);
	$un=mysql_fetch_assoc($u);
	return $un['Fname'].' '.$un['Sname'].' '.$un['Lname'];
}

?>

<?php
include "footer.php";
?>

<script type="text/javascript" src="js/user.js"></script>

<script type="text/javascript">
	function changepass(eid){
		var newpass=$("#newpass").val();
		var confpass=$("#confpass").val();

		if(newpass==confpass){
			$.post("uajax.php",{act:"changepass",eid:eid,newpass:newpass},function(data){

				if(data="success"){
					var s='<div class="alert alert-success"> <strong>Success!</strong> Password Changed Successfully. </div>';
					$("#changetbl").html(s);
					$("#chngpassbtn").hide();

				}
				
			});
		}else{
			alert('New Password Field and Confirm Password Field not matched\nPlease Try Again');
			$("#newpass").val('');
			$("#confpass").val('');
			$("#newpass").focus();
		}
	}



	function showchngtbl(){
		$("#changetbl").show();
		$("#newpass").focus();
	}

	

</script>