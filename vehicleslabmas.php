<?php
include "header.php";



if(isset($_POST['fsavebtn'])){
	$selexist=mysql_query("SELECT * FROM `vehicle_policyslab` WHERE `VPolicyName`='".$_POST['VPolicyName']."'");
	if(mysql_num_rows($selexist) > 0){
		$msg=$_POST['VPolicyName'].' Policy Slab Already Exists'; $msgcolor='danger';
	}else{
	
		$newyr=mysql_query("INSERT INTO `vehicle_policyslab`(CompanyId, VPolicyName, Gradefrom, GradeTo, 2w4w, ApplyForMonth, Slab1_f, Slab1_t, Slab1_rate, Slab2_f, Slab2_t, Slab2_rate, Slab3_f, Slab3_t, Slab3_rate, Slab4_f, Slab4_t, Slab4_rate, With_Driv, Without_Driv, SlabStatus, CrBy, CrDate, SysDate) VALUES ('".$_SESSION['CompanyId']."', '".$_POST['VPolicyName']."', '".$_POST['GradeFrom']."', '".$_POST['GradeTo']."', '".$_POST['2w4w']."', '".$_POST['ApplyForMonth']."', '".$_POST['Slab1_f']."', '".$_POST['Slab1_t']."', '".$_POST['Slab1_rate']."', '".$_POST['Slab2_f']."', '".$_POST['Slab2_t']."', '".$_POST['Slab2_rate']."', '".$_POST['Slab3_f']."', '".$_POST['Slab3_t']."', '".$_POST['Slab3_rate']."', '".$_POST['Slab4_f']."', '".$_POST['Slab4_t']."', '".$_POST['Slab4_rate']."', '".$_POST['With_Driv']."', '".$_POST['Without_Driv']."', '".$_POST['SlabStatus']."', '".$_SESSION['EmployeeID']."', '".date("Y-m-d")."', '".date("Y-m-d")."')");
		if($newyr){$msg='New Policy Slab Created'; $msgcolor='success';}
	}
	
}


if(isset($_POST['yearupdtbtn'])){
	$selexist=mysql_query("SELECT * FROM `vehicle_policyslab` WHERE `VPId`='".$_POST['VPId']."'");
	if(mysql_num_rows($selexist) > 0){
	
		$yrupdt=mysql_query("UPDATE `vehicle_policyslab` set VPolicyName='".$_POST['VPolicyName']."', Gradefrom='".$_POST['Grade2From']."', GradeTo='".$_POST['GradeTo']."', 2w4w='".$_POST['2w4w']."', ApplyForMonth='".$_POST['ApplyForMonth']."', Slab1_f='".$_POST['Slab1_f']."', Slab1_t='".$_POST['Slab1_t']."', Slab1_rate='".$_POST['Slab1_rate']."', Slab2_f='".$_POST['Slab2_f']."', Slab2_t='".$_POST['Slab2_t']."', Slab2_rate='".$_POST['Slab2_rate']."', Slab3_f='".$_POST['Slab3_f']."', Slab3_t='".$_POST['Slab3_t']."', Slab3_rate='".$_POST['Slab3_rate']."', Slab4_f='".$_POST['Slab4_f']."', Slab4_t='".$_POST['Slab4_t']."', Slab4_rate='".$_POST['Slab4_rate']."', With_Driv='".$_POST['With_Driv']."', Without_Driv='".$_POST['Without_Driv']."', SlabStatus='".$_POST['SlabStatus']."', CrBy='".$_SESSION['EmployeeID']."', CrDate='".date("Y-m-d")."', SysDate='".date("Y-m-d")."' WHERE `VPId`='".$_POST['VPId']."'");
		if($yrupdt){$msg='Policy Slab Updated Successfully'; $msgcolor='success';}
		
	}else{
		$msg=' Policy Slab Not Updated'; $msgcolor='danger';
	}
	
}



?>

<div class="container">
	<div class="row shadow">
		<div class="col-md-12">
			<?php if(isset($msg)){ ?>
				<div class="alert alert-<?=$msgcolor?> alert-dismissible">
			    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			    <strong><?=$msg?></strong>
			  </div>
			
			<?php } ?>
			<br>
			<button class="btn btn-sm btn-outline-primary " onclick="shownewadd()">New Slab +</button>


			<div class="table-responsive">
				<table class="table shadow" style="padding:0px;" cellspacing="0">

				  <thead class="thead-dark">
				    <tr>
				      <th scope="col" rowspan="2" style="width:150px;text-align:center;">Policy Name</th>
					  <th colspan="2" scope="col" style="text-align:center;">Grade</th>
				      <th scope="col" rowspan="2" style="width:60px;text-align:center;">Apply For</th>
					  <th scope="col" rowspan="2" style="width:100px;">Apply For Month</th>
					  <th colspan="3" scope="col" style="text-align:center;">Slab 1</th>
					  <th colspan="3" scope="col" style="text-align:center;">Slab 2</th>
					  <th colspan="3" scope="col" style="text-align:center;">Slab 3</th>
					  <th rowspan="2" scope="col" style="text-align:center;width:50px;">With<br />Driver</th>
					  <th rowspan="2" scope="col" style="text-align:center;width:50px;">Without<br />Driver</th>
					  <!--<th colspan="3" scope="col" style="text-align:center;">Slab 4</th>-->
				      <th scope="col" rowspan="2" style="width:60px;text-align:center;">Status</th>
				      <th scope="col" rowspan="2" style="width:50px;text-align:center;">Act</th>
				    </tr>
					<tr>
					  <th scope="col" style="width:60px;">From</th>
					  <th scope="col" style="width:60px;">To</th>
					  <th scope="col" style="width:50px;">From <br /><font color="#FF9F71">Km</font></th>
					  <th scope="col" style="width:50px;">To <br /><font color="#FF9F71">Km</font></th>
					  <th scope="col" style="width:50px;">Rate<br /><font color="#FF9F71">Rs/Km</font></th>
					  <th scope="col" style="width:50px;">From <br /><font color="#FF9F71">Km</font></th>
					  <th scope="col" style="width:50px;">To <br /><font color="#FF9F71">Km</font></th>
					  <th scope="col" style="width:50px;">Rate<br /><font color="#FF9F71">Rs/Km</font></th>
					  <th scope="col" style="width:50px;">From <br /><font color="#FF9F71">Km</font></th>
					  <th scope="col" style="width:50px;">To <br /><font color="#FF9F71">Km</font></th>
					  <th scope="col" style="width:50px;">Rate<br /><font color="#FF9F71">Rs/Km</font></th>
					  <!--<th scope="col" style="width:50px;">From <br /><font color="#FF9F71">Km</font></th>
					  <th scope="col" style="width:50px;">To <br /><font color="#FF9F71">Km</font></th>
					  <th scope="col" style="width:50px;">Rate<br /><font color="#FF9F71">Rs/Km</font></th>-->
					</tr>

				    <tr id="newaddtr" style="display:none;">
				      <form action="vehicleslabmas.php" method="post">
				      <td scope="col" >
					   <input type="text" class="form-control" name="VPolicyName"  />
				      </td>
					  
					  <td scope="col">
				      	<select class="form-control" name="GradeFrom">
						<?php $sG=mysql_query("select GradeId,GradeValue from hrm_grade where CompanyId=".$_SESSION['CompanyId']." AND GradeStatus='A' AND CreatedDate>='2014-02-01' order by GradeId ASC",$con2); while($rG=mysql_fetch_assoc($sG)){ ?>
				      		<option value="<?=$rG['GradeId']?>"><?=$rG['GradeValue']?></option>
						<?php } ?>	
				      	</select>
				      </td>
					  <td scope="col">
				      	<select class="form-control" name="GradeTo">
						<?php $sG=mysql_query("select GradeId,GradeValue from hrm_grade where CompanyId=".$_SESSION['CompanyId']." AND GradeStatus='A' AND CreatedDate>='2014-02-01' order by GradeId ASC",$con2); while($rG=mysql_fetch_assoc($sG)){ ?>
				      		<option value="<?=$rG['GradeId']?>"><?=$rG['GradeValue']?></option>
						<?php } ?>	
				      	</select>
				      </td>
					  
					  <td scope="col">
				      	<select class="form-control" name="2w4w">
				      		<option value="2">2 W</option>
				      		<option value="4">4 W</option>
				      	</select>
				      </td>
					  
					  <td scope="col">
				      	<select class="form-control" name="ApplyForMonth">
						 <?php for($i=1; $i<=12; $i++){ if($i!=5 && $i!=7 && $i!=8 && $i!=9 && $i!=10 && $i!=11){ ?>
				      		<option value="<?=$i?>"><?=$i?> Month</option>
						 <?php } } ?>	
				      	</select>
				      </td>
					  <td scope="col">
					   <input type="text" class="form-control" name="Slab1_f" maxlength="5"/>
				      </td>
					  <td scope="col">
					   <input type="text" class="form-control" name="Slab1_t" maxlength="5"/>
				      </td>
					  <td scope="col" >
					   <input type="text" class="form-control" name="Slab1_rate" maxlength="5"/>
				      </td>
					  <td scope="col">
					   <input type="text" class="form-control" name="Slab2_f" maxlength="5"/>
				      </td>
					  <td scope="col">
					   <input type="text" class="form-control" name="Slab2_t" maxlength="5"/>
				      </td>
					  <td scope="col" >
					   <input type="text" class="form-control" name="Slab2_rate" maxlength="5"/>
				      </td>
					  <td scope="col">
					   <input type="text" class="form-control" name="Slab3_f" maxlength="5"/>
				      </td>
					  <td scope="col">
					   <input type="text" class="form-control" name="Slab3_t" maxlength="5"/>
				      </td>
					  <td scope="col" >
					   <input type="text" class="form-control" name="Slab3_rate" maxlength="5"/>
				      </td>
				      <td scope="col" >
					   <input type="text" class="form-control" name="With_Driv" maxlength="6"/>
				      </td>
					  <td scope="col" >
					   <input type="text" class="form-control" name="Without_Driv" maxlength="6"/>
				      </td>
					  <!--<td scope="col">
					   <input type="text" class="form-control" name="Slab4_f" maxlength="5"/>
				      </td>
					  <td scope="col">
					   <input type="text" class="form-control" name="Slab4_t" maxlength="5"/>
				      </td>
					  <td scope="col" >
					   <input type="text" class="form-control" name="Slab4_rate" maxlength="5"/>
				      </td>-->
					  
					  <input type="hidden" name="Slab4_f" value="0"/>
					  <input type="hidden" name="Slab4_t" value="0" />
					  <input type="hidden" name="Slab4_rate" value="0"/>
					  <td scope="col">
				      	<select class="form-control" name="SlabStatus">
				      		<option value="A">A</option>
				      		<option value="D">D</option>
				      	</select>
				      </td>
					  
				      <td scope="col" style="text-align:center;">
				      	<button class="btn btn-sm btn-primary " type="submit" name="fsavebtn">Save</button>
				      </td>
				      </form>
				    </tr>
				  </thead>
				  
				  <tbody>
				  	<?php
				  	$sely=mysql_query("SELECT * FROM `vehicle_policyslab`");
				  	while($selyd=mysql_fetch_assoc($sely)){
				  		if(isset($_REQUEST['edit']) && $_REQUEST['edit']==$selyd['VPId']){
				  			?>
						    <tr>
						    	<form action="vehicleslabmas.php" method="post">
								<input type="hidden" name="VPId" value="<?=$selyd['VPId']?>">
						      
					  <td scope="col" >
					    <input type="text" class="form-control" name="VPolicyName" value="<?=$selyd['VPolicyName']?>"  />
				      </td>
					  
					  <td scope="col">
				      	<select class="form-control" name="Grade2From">
						<?php $sG=mysql_query("select GradeId,GradeValue from hrm_grade where CompanyId=".$_SESSION['CompanyId']." AND GradeStatus='A' AND CreatedDate>='2014-02-01' order by GradeId ASC",$con2); while($rG=mysql_fetch_assoc($sG)){ ?>
				      		<option value="<?=$rG['GradeId']?>" <?php if($selyd['GradeFrom']==$rG['GradeId']){echo 'selected';} ?>><?=$rG['GradeValue']?></option>
						<?php } ?>	
				      	</select>
				      </td>
					  <td scope="col">
				      	<select class="form-control" name="GradeTo">
						<?php $sG=mysql_query("select GradeId,GradeValue from hrm_grade where CompanyId=".$_SESSION['CompanyId']." AND GradeStatus='A' AND CreatedDate>='2014-02-01' order by GradeId ASC",$con2); while($rG=mysql_fetch_assoc($sG)){ ?>
				      		<option value="<?=$rG['GradeId']?>" <?php if($selyd['GradeTo']==$rG['GradeId']){echo 'selected';} ?>><?=$rG['GradeValue']?></option>
						<?php } ?>	
				      	</select>
				      </td>
					  
				      <td scope="col">
				      	<select class="form-control" name="2w4w">
				      		<option value="2" <?php if($selyd['2w4w']==2){echo 'selected';} ?>>2 W</option>
				      		<option value="4" <?php if($selyd['2w4w']==4){echo 'selected';} ?>>4 W</option>
				      	</select>
				      </td>
					  <td scope="col">
				      	<select class="form-control" name="ApplyForMonth">
						 <?php for($i=1; $i<=12; $i++){ if($i!=5 && $i!=7 && $i!=8 && $i!=9 && $i!=10 && $i!=11){  ?>
				      		<option value="<?=$i?>" <?php if($selyd['ApplyForMonth']==$i){echo 'selected';} ?>><?=$i?> Month</option>
						 <?php } } ?>	
				      	</select>
				      </td>
					  <td scope="col">
					   <input type="text" class="form-control" name="Slab1_f" value="<?=$selyd['Slab1_f']?>" maxlength="5"/>
				      </td>
					  <td scope="col">
					   <input type="text" class="form-control" name="Slab1_t" value="<?=$selyd['Slab1_t']?>" maxlength="5"/>
				      </td>
					  <td scope="col" >
					   <input type="text" class="form-control" name="Slab1_rate" value="<?=$selyd['Slab1_rate']?>" maxlength="5"/>
				      </td>
					  <td scope="col">
					   <input type="text" class="form-control" name="Slab2_f" value="<?=$selyd['Slab2_f']?>" maxlength="5"/>
				      </td>
					  <td scope="col">
					   <input type="text" class="form-control" name="Slab2_t" value="<?=$selyd['Slab2_t']?>" maxlength="5"/>
				      </td>
					  <td scope="col" >
					   <input type="text" class="form-control" name="Slab2_rate" value="<?=$selyd['Slab2_rate']?>" maxlength="5"/>
				      </td>
					  <td scope="col">
					   <input type="text" class="form-control" name="Slab3_f" value="<?=$selyd['Slab3_f']?>" maxlength="5"/>
				      </td>
					  <td scope="col">
					   <input type="text" class="form-control" name="Slab3_t" value="<?=$selyd['Slab3_t']?>" maxlength="5"/>
				      </td>
					  <td scope="col" >
					   <input type="text" class="form-control" name="Slab3_rate" value="<?=$selyd['Slab3_rate']?>" maxlength="5"/>
				      </td>
				      <td scope="col" >
					   <input type="text" class="form-control" name="With_Driv" value="<?=$selyd['With_Driv']?>" maxlength="6"/>
				      </td>
					  <td scope="col" >
					   <input type="text" class="form-control" name="Without_Driv" value="<?=$selyd['Without_Driv']?>" maxlength="6"/>
				      </td>
					  <?php /*?><td scope="col">
					   <input type="text" class="form-control" name="Slab4_f" value="<?=$selyd['Slab4_f']?>" maxlength="5"/>
				      </td>
					  <td scope="col">
					   <input type="text" class="form-control" name="Slab4_t" value="<?=$selyd['Slab4_t']?>" maxlength="5"/>
				      </td>
					  <td scope="col" >
					   <input type="text" class="form-control" name="Slab4_rate" value="<?=$selyd['Slab4_rate']?>" maxlength="5"/>
				      </td><?php */?>
					  
					  <input type="hidden" name="Slab4_f" value="0"/>
					  <input type="hidden" name="Slab4_t" value="0" />
					  <input type="hidden" name="Slab4_rate" value="0"/>
					  
					  <td scope="col">
				      	<select class="form-control" name="SlabStatus">
				      		<option value="A" <?php if($selyd['SlabStatus']=='A'){echo 'selected';} ?>>A</option>
				      		<option value="D" <?php if($selyd['SlabStatus']=='D'){echo 'selected';} ?>>D</option>
				      	</select>
				      </td>
							  
							  
							  <td style="text-align:center;">
						      	<button class="btn btn-sm btn-primary" name="yearupdtbtn" type="submit">Up</button>
						      </td>
							  </form>

						    </tr>
						    <?php
				  			
				  		}else{
				  			?>
						    <tr >

						      <td><?=$selyd['VPolicyName']?></th>
							  
							  <?php $sGn=mysql_query("select GradeValue from hrm_grade where GradeId=".$selyd['GradeFrom'],$con2); $rGn=mysql_fetch_assoc($sGn); ?><td><?=$rGn['GradeValue']?></td>
							  <?php $sGnn=mysql_query("select GradeValue from hrm_grade where GradeId=".$selyd['GradeTo'],$con2); $rGnn=mysql_fetch_assoc($sGnn); ?><td><?=$rGnn['GradeValue']?></td>
							  
						      <td><?=$selyd['2w4w']?></td>
						      <td><?=$selyd['ApplyForMonth']?></td>
							  <td><?=$selyd['Slab1_f']?></td>
						      <td><?=$selyd['Slab1_t']?></td>
						      <td><?=$selyd['Slab1_rate']?></td>
							  <td><?php if($selyd['Slab2_f']>0){echo $selyd['Slab2_f']; } ?></td>
						      <td><?php if($selyd['Slab2_t']>0){echo $selyd['Slab2_t']; } ?></td>
						      <td><?php if($selyd['Slab2_rate']>0){echo $selyd['Slab2_rate']; } ?></td>
							  <td><?php if($selyd['Slab3_f']>0){echo $selyd['Slab3_f']; } ?></td>
						      <td><?php if($selyd['Slab3_t']>0){echo $selyd['Slab3_t']; } ?></td>
						      <td><?php if($selyd['Slab3_rate']>0){echo $selyd['Slab3_rate']; } ?></td>
						      <td><?php if($selyd['With_Driv']>0){echo $selyd['With_Driv']; } ?></td>
						      <td><?php if($selyd['Without_Driv']>0){echo $selyd['Without_Driv']; } ?></td>
							  <?php /*?><td><?php if($selyd['Slab4_f']>0){echo $selyd['Slab4_f']; } ?></td>
						      <td><?php if($selyd['Slab4_t']>0){echo $selyd['Slab4_t']; } ?></td>
						      <td><?php if($selyd['Slab4_rate']>0){echo $selyd['Slab4_rate']; } ?></td><?php */?>
						      <td style="text-align:center;"><?=$selyd['SlabStatus']?></td>
						      <td style="text-align:center;">
						      	<button class="btn btn-sm btn-primary" onclick="edit('<?=$selyd['VPId']?>')">Edit</button>
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

<script type="text/javascript" src="js/slab.js"></script>
<script type="text/javascript">


function shownewadd(){
	$('#newaddtr').css('display','table-row');
}

function edit(id){
	window.location.href = 'vehicleslabmas.php?edit='+id;
}
</script>
