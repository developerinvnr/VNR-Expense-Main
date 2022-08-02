<?php
session_start();
if(!isset($_SESSION['login'])){
  session_destroy();
  header('location:index.php');
}
include 'config.php';
if($_POST['act']=='getudet'){
?>
<br><br>
<div class="table-responsive">
	<table class="table table-sm shadow minpadtable">
	  <thead class="thead-dark">
	    <tr>
	      <th scope="col" colspan="2">
		      Details
		      <!-- <button type="button" id="editbtn" class="btn btn-sm btn-outline-light pull-right vsmbtn " onclick="editudets('<?=$_POST['eid']?>')">
			      <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
			  </button>
			  <button  type="button" id="savebtn" class="btn btn-sm btn-outline-light pull-right vsmbtn " onclick="saveudets('<?=$_POST['eid']?>')" style="display:none;">
			      <i class="fa fa-save" aria-hidden="true"></i> Save
			  </button> -->
			  <div id="editsts" class="pull-right"></div>
		  </th>
	    </tr>
	  </thead>

	  <?php
	  $seluq=mysql_query("SELECT * FROM `hrm_user` where EmployeeID=".$_POST['eid']);
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
</div>
<script type="text/javascript" src="js/user.js"></script>
<?php 
}elseif($_POST['act']=='getempdet'){
?>
<br><br>
<div class="table-responsive">
	<table class="table table-sm shadow minpadtable">
	  <thead class="thead-dark">
	    <tr>
	      <th scope="col" colspan="2">
		      Details
		      <!-- <button type="button" id="editbtn" class="btn btn-sm btn-outline-light pull-right vsmbtn " onclick="editedets('<?=$_POST['eid']?>')">
			      <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
			  </button>
			  <button  type="button" id="savebtn" class="btn btn-sm btn-outline-light pull-right vsmbtn " onclick="saveedets('<?=$_POST['eid']?>')" style="display:none;">
			      <i class="fa fa-save" aria-hidden="true"></i> Save
			  </button> -->
			  <div id="editsts" class="pull-right"></div>
		  </th>
	    </tr>
	  </thead>

	  <?php
	  $seluq=mysql_query("SELECT * FROM ".dbemp.".`hrm_employee` where EmployeeID=".$_POST['eid']);
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

	<table class="table table-sm shadow minpadtable">
	  <thead class="thead-dark">
	    <tr>
	      <th scope="col" colspan="2">
		      Claim Type Assign &nbsp;&nbsp;
		  </th>
	    </tr>
	  </thead>

	  
	  <tbody>
	  	
	  	<?php
		$selc=mysql_query("SELECT * FROM `claimtype` where ClaimStatus='A'");
		while($selcl=mysql_fetch_assoc($selc)){
		?>
	    <tr>
	      <th scope="row"><?=$selcl['ClaimName']?></th>
	      <?php
			$sela=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_empclaimassign` where EmployeeID='".$_POST['eid']."' and ClaimId='".$selcl['ClaimId']."'");

			if(mysql_num_rows($sela)>0){
				$tdcol='green';
				$chksts='checked';
			}else{
				$tdcol='';
				$chksts='';

			}
			?>
	      <td id="asgtd<?=$selcl['ClaimId']?>" style="background-color: <?=$tdcol?>"><input readonly class="form-control" type="checkbox" id="Empasgchk<?=$selcl['ClaimId']?>" onchange="assigncl(this,'<?=$selcl['ClaimId']?>','<?=$_POST['eid']?>')"   <?=$chksts?>></td>
		</tr>
		
		<?php
		}
		?>
	  </tbody>
	</table>





</div>
<script type="text/javascript" src="js/user.js"></script>
<?php 
}elseif($_POST['act']=='saveudets'){

	$myArray = $_POST['inputs'];	
	$eid= $_POST['eid'];
	
	$up=mysql_query("UPDATE `hrm_user` SET `EmpRole`='".$myArray['EmpRoleinp']."',`EmpCode`='".$myArray['EmpCodeinp']."',`EmpStatus`='".$myArray['EmpStatusinp']."',`Fname`='".$myArray['Fnameinp']."',`Sname`='".$myArray['Snameinp']."',`Lname`='".$myArray['Lnameinp']."',`EmailId`='".$myArray['EmailIdinp']."',`MobileNo`='".$myArray['MobileNoinp']."' WHERE `EmployeeID`=".$eid);

	if($up){ echo 'success'; }

	
}elseif($_POST['act']=='savenewuser'){

	$myArray = $_POST['inputs'];

	$up=mysql_query("INSERT INTO `hrm_user`(`EmpCode`, `EmpPass`,`EmpRole`,`EmpStatus`,`Fname`,`Sname`,`Lname`,`EmailId`,`MobileNo`,`CreatedBy`,`CreatedDate`) VALUES ('".$myArray['EmpCodeinp']."','".md5($myArray['EmpPassinp'])."','".$myArray['EmpRoleinp']."','".$myArray['EmpStatusinp']."','".$myArray['Fnameinp']."','".$myArray['Snameinp']."','".$myArray['Lnameinp']."','".$myArray['EmailIdinp']."','".$myArray['MobileNoinp']."','".$_SESSION['EmployeeID']."','".date("Y-m-d")."')");

	if($up){ echo 'success'; }


	
}elseif($_POST['act']=='eassigncl'){

	$sela=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_empclaimassign` where EmployeeID='".$_POST['eid']."' and ClaimId='".$_POST['clid']."'");

	if(mysql_num_rows($sela)==0){
		$up=mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_empclaimassign`(`EmployeeID`, `ClaimId`, `AssignBy`) VALUES ('".$_POST['eid']."','".$_POST['clid']."','".$_SESSION['EmployeeID']."')");
	}

	if($up){ echo 'success'; }

}elseif($_POST['act']=='remeassigncl'){

	$sela=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_empclaimassign` where EmployeeID='".$_POST['eid']."' and ClaimId='".$_POST['clid']."'");

	if(mysql_num_rows($sela)>0){
		$del=mysql_query("DELETE FROM `y".$_SESSION['FYearId']."_empclaimassign` WHERE `EmployeeID`='".$_POST['eid']."' and `ClaimId`='".$_POST['clid']."' and `AssignBy`='".$_SESSION['EmployeeID']."'");
	}

	if($del){ echo 'deleted'; }

}elseif($_POST['act']=='employeelistshow'){


	$aa=json_decode($_REQUEST['claimseq']);
	

	if(isset($_REQUEST['page']) && isset($_REQUEST['range'])){

		$page=(int)$_REQUEST['page'];
		$range=(int)$_REQUEST['range'];
		$uplimit=$range*($page-1);
		$limitcond="limit ".$uplimit.",".$range;
	}else{
		$limitcond="1=1";
	}


	if(isset($_REQUEST['name']) && $_REQUEST['name']!=''){
		$namecond="(`Fname` like '%".$_REQUEST['name']."%' or `Sname` like '%".$_REQUEST['name']."%' or `Lname` like '%".$_REQUEST['name']."%')";
		$uplimit=0;
		$limitcond="limit ".$uplimit.",".$range;
	}else{
		$namecond="1=1";
	}


	if(isset($_REQUEST['code']) && $_REQUEST['code']!=''){
		$empCodecond="`EmpCode` like '%".$_REQUEST['code']."%'";
		$uplimit=0;
		$limitcond="limit ".$uplimit.",".$range;
	}else{
		$empCodecond="1=1";
	}

    if($_SESSION['CompanyId']==4){$Econ='EmpCode>790000';}else{$Econ='1=1';}
    
  	$seluq=mysql_query("SELECT * FROM ".dbemp.".`hrm_employee` where EmpStatus='A' and EmpType='E' and ".$empCodecond." and ".$namecond." and CompanyId=".$_SESSION['CompanyId']." and ".$Econ." order by EmpCode ASC ".$limitcond);

	$sno=$uplimit+1;

  	while($selu=mysql_fetch_assoc($seluq)){ 
  	?>
  	
    <tr id="<?=$sno?>tr" class="elist">
    	<td style="text-align:center;"><?=$sno?></td>
      
		<td style="text-align:center;"><?=$selu['EmpCode']?></td>
		<td onclick="showempdet('<?=$selu['EmployeeID']?>','<?=$sno?>')" style="color:#0066ff;cursor:pointer;">
			<?=$selu['Fname'].' '.$selu['Sname'].' '.$selu['Lname']?>
		</td>

     
		<?php
		foreach ($aa as $key => $value) {
		?>
			<td>
				<?php
				
				$selc=mysql_query("SELECT cl.verifiedLimit,cl.updatedLimit,cl.verify FROM `claimlimits` cl, claimtype ct where cl.EmployeeID='".$selu['EmployeeID']."' and ct.ClaimCode='".$value."' and cl.ClaimId=ct.ClaimId ");
				$selcd=mysql_fetch_assoc($selc);

				echo '<input type="text" class="form-control" id="'.$selu['EmployeeID'].$value.'" value="'.$selcd['verifiedLimit'].'" style="width:40px;margin:0px !important;background-color:#e6fff2;" readonly onkeypress="return isNumber(event)">';

				
				?>
				
				<input type="text" class="form-control" id="up<?=$selu['EmployeeID'].$value?>" value="<?=$selcd['updatedLimit']?>" style="width:40px;margin:0px !important;<?php if($selcd['verify']==1){echo 'display: none;';}?>" readonly onkeypress="return isNumber(event)">

				
			</td>
		<?php
		}
		?>
		<td style="width: 70px;">
			<button class="btn btn-sm btn-primary" id="editbtn<?=$selu['EmployeeID']?>" onclick="editclaimlimits(<?=$selu['EmployeeID']?>)">
				<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
			</button>
			<button class="btn btn-sm btn-success" id="savebtn<?=$selu['EmployeeID']?>" onclick="saveclaimlimits(<?=$selu['EmployeeID']?>)" style="display:none;">
				<i class="fa fa-save" aria-hidden="true"></i> Save
			</button>
			

			<label id="verifychklbl<?=$selu['EmployeeID']?>" style="vertical-align: top;display: <?php if($selcd['verify']==0){echo 'inline;';}else{echo 'none;';}?>">
				<input id="verifychk<?=$selu['EmployeeID']?>" class="form-control" type="checkbox" onclick="verifylimit(<?=$selu['EmployeeID']?>)" style="width: 15px;display: inline-block;"><b style="vertical-align: top;color:green;">Verify</b>
			</label>

			<span style="font-size: 10px;color:green;font-weight: bold;display: none;" id="verifysp<?=$selu['EmployeeID']?>">Verified</span>
			
		</td>
    </tr>
    <?php 
    $sno++;
	}

	?>
	
	<?php


	if($sno<$range){
		?>
		<script type="text/javascript">
			// pagechange(1);
		</script>
		<?php
	}

	?>
	<script type="text/javascript">
		
		function verifylimit(eid){
			// alert(eid);
			var flag=0;
			var js_array =<?php echo json_encode($aa);?>;
			for (index = 0; index <= js_array.length-1; index++) {
				
				var i=js_array[index];
				var aaa=$("#"+eid+js_array[index]).val();
				// alert(aaa);
			    $.post("uajax.php",{act:"verifylimit",empid:eid,claimcode:js_array[index]},function(data){
					// console.log(data);
					var obj = JSON.parse(data);
					// alert(obj[0].sts+',,,'+obj[0].claimcode);
					if(obj[0].sts=='updated'){
						$("#"+eid+obj[0].claimcode).val($("#up"+eid+obj[0].claimcode).val());
						$("#up"+eid+obj[0].claimcode).hide();
						$("#verifychk"+eid).hide();
						$("#verifychklbl"+eid).hide();
						$("#verifysp"+eid).show();
					}
					
				});
			}

				
			setTimeout(function(){$("#verifysp"+eid).hide();},1500);
			
		}
		function saveclaimlimits(eid){

			var js_array =<?php echo json_encode($aa);?>;
			
			for (index = 0; index <= js_array.length-1; index++) {
				// alert("#"+eid+js_array[index]);
				
				var aaa=$("#up"+eid+js_array[index]).val();
				
			    $.post("uajax.php",{act:"empclaimlim",empid:eid,claimcode:js_array[index],amt:aaa},function(data){
			    	// console.log(data);
					var obj = JSON.parse(data);
					// alert(obj[0].sts+',,,'+obj[0].claimcode);
					if(obj[0].sts=='updated'){
						$("#up"+eid+obj[0].claimcode).css('border','2px solid green');
						$("#verifychklbl"+eid).css('display','inline');

					}
					
				});
			}
			againDoReadonlylimits(eid);
		}

		function editclaimlimits(eid){

			//alert('dasd');

			$("#editbtn"+eid).hide();
			$("#savebtn"+eid).show();

			var js_array =<?php echo json_encode($aa);?>;
			for (index = 0; index <= js_array.length-1; index++) {
				// alert("#"+eid+js_array[index]);
				$("#up"+eid+js_array[index]).css('display', 'block');
				$("#up"+eid+js_array[index]).attr('readonly', false);
				
			    
			}
		}

		function againDoReadonlylimits(eid){

			$("#editbtn"+eid).show();
			$("#savebtn"+eid).hide();

			var js_array =<?php echo json_encode($aa);?>;
			for (index = 0; index <= js_array.length-1; index++) {
				// alert("#"+eid+js_array[index]);
				$("#"+eid+js_array[index]).attr('readonly', true);
				
			    
			}
		}

		
	</script>

	<?php
					

}elseif($_POST['act']=='empclaimlim'){


	$cid=mysql_query("SELECT * FROM `claimtype` where ClaimCode='".$_REQUEST['claimcode']."'");
	$cidd=mysql_fetch_assoc($cid);

	$selcllim=mysql_query("SELECT * FROM `claimlimits` where EmployeeID='".$_REQUEST['empid']."' and  ClaimId='".$cidd['ClaimId']."'");

	if($_REQUEST['amt']!=0){
		if(mysql_num_rows($selcllim)){
			$q=mysql_query("UPDATE `claimlimits` SET updatedLimit='".$_REQUEST['amt']."',verify=0 where EmployeeID='".$_REQUEST['empid']."' and  ClaimId='".$cidd['ClaimId']."'");
		}else{
			$q=mysql_query("INSERT INTO `claimlimits`( `EmployeeID`, `ClaimId`, `updatedLimit`,`crdate`) VALUES ('".$_REQUEST['empid']."','".$cidd['ClaimId']."','".$_REQUEST['amt']."','".date('Y-m-d')."')");
		}

		if($q){
			// echo 'updated';
			$return_arr[] = array(
			"sts" => "updated",
            "claimcode" => $_REQUEST['claimcode']
            );

			echo json_encode($return_arr);
		}
	}
	

}elseif($_POST['act']=='changepass'){

	$newpass=md5($_REQUEST['newpass']);

	$up = mysql_query("UPDATE `hrm_user` SET `EmpPass`='".$newpass."' where `EmployeeID`='".$_REQUEST['eid']."'");

	if($up){
		echo 'success';

		
	}
}elseif($_POST['act']=='verifylimit'){


	$cid=mysql_query("SELECT * FROM `claimtype` where ClaimCode='".$_REQUEST['claimcode']."'");
	$cidd=mysql_fetch_assoc($cid);

	$selcllim=mysql_query("SELECT * FROM `claimlimits` where EmployeeID='".$_REQUEST['empid']."' and  ClaimId='".$cidd['ClaimId']."'");

	
		if(mysql_num_rows($selcllim)){
			$q=mysql_query("UPDATE `claimlimits` SET verify='1',verifiedLimit=updatedLimit where EmployeeID='".$_REQUEST['empid']."' and  ClaimId='".$cidd['ClaimId']."'");
		}

		if($q){

			$return_arr[] = array(
			"sts" => "updated",
            "claimcode" => $_REQUEST['claimcode']
            );

			echo json_encode($return_arr);
		}
	
	

}elseif($_POST['act']=='StateAccess'){

 $sqlus=mysql_query("select * from hrm_user_state where UserId=".$_POST['eid']." AND StateId=".$_POST['sid']."");
 $rowus=mysql_num_rows($sqlus);
 if($rowus>0)
 {
  $sUI=mysql_query("update hrm_user_state set Status=".$_POST['v'].", CrBy=".$_SESSION['EmployeeID'].", CrDate='".date("Y-m-d")."' where UserId=".$_POST['eid']." AND StateId=".$_POST['sid']."");
 }
 else
 {
  $sUI=mysql_query("insert into hrm_user_state(UserId, StateId, Status, CrBy, CrDate) values(".$_POST['eid'].", ".$_POST['sid'].", ".$_POST['v'].", ".$_SESSION['EmployeeID'].", '".date("Y-m-d")."')");
 }
 if($sUI){echo 'success'; }else{echo 'error';}
}




?>

