<?php include "header.php"; ?>

<div class="container">
	<div class="row shadow">
		<div class="col-md-8">
			<br>
			<button class="btn btn-sm btn-outline-primary " onclick="newuser()">New User +</button>
			<div class="table-responsive">
				<table class="table shadow">
				  <thead class="thead-dark">
				    <tr>
				      <th scope="col" style="width:50px;text-align:center;">User ID</th>
				      <th scope="col" style="width:100px;text-align:center;">Login Id</th>
				      <th scope="col" style="width:150px;text-align:center;">Name</th>
					  <th scope="col" style="width:50px;text-align:center;">Role</th>
				      <th scope="col" style="text-align:center;">Email</th>
				      <th scope="col" style="text-align:center;">Mobile</th>
				      <th scope="col" style="width:50px;text-align:center;">Status</th>
				      <th scope="col" style="width:50px;text-align:center;">Pwd</th>
				      <th scope="col" style="width:50px;text-align:center;">State</th>
				    </tr>
				  </thead>
				  <tbody>
				  	<?php
				  	$seluq=mysql_query("SELECT * FROM `hrm_user` where EmpRole!='S'");

				  	while($selu=mysql_fetch_assoc($seluq)){
				  	?>
				    <tr onclick="showdet('<?=$selu['EmployeeID']?>')">

				      <td style="text-align:center;"><?=$selu['EmployeeID']?></td>
				      <td style="text-align:center;"><?=$selu['EmpCode']?></td>
				      <td><?=$selu['Fname'].' '.$selu['Sname'].' '.$selu['Lname']?></td>
					  <td style="text-align:center;"><?=$selu['EmpRole']?></td>
				      <td><?=$selu['EmailId']?></td>
				      <td style="text-align:center;"><?=$selu['MobileNo']?></td>
				      <td style="text-align:center;"><?=$selu['EmpStatus']?></td>
				      <td style="text-align:center;">
                      <button type="button" id="chngpassbtn" class="btn btn-sm btn-outline-primary vsmbtn " onclick="showchngtbl(<?=$selu['EmployeeID']?>)" style="font-weight: bold;">Click</button>
			          </td>
			          <td style="text-align:center;">
                      <button type="button" class="btn btn-sm btn-outline-primary vsmbtn " onclick="showState(<?=$selu['EmployeeID']?>)" style="font-weight: bold;">Click</button>
			          </td>

				    </tr>
				    
				    
				    <tr>
					 <td colspan="10">
<?php /****************** 111111 Open *************/?>
<?php /****************** 111111 Open *************/?>
<div id="changetbl<?=$selu['EmployeeID']?>" style="display: none;">
<table class="table table-sm shadow minpadtable" style="width:500px;" id="">
 <thead class="thead-dark">
 <tr><th scope="col" colspan="2">Change Password &nbsp;&nbsp;</th></tr>
 </thead>
 <tbody>
 <tr>
  <th scope="row">New Password</th>
   <td><input class="form-control" type="password" id="newpass<?=$selu['EmployeeID']?>" value=""></td>
  </tr>
  <tr>
   <th scope="row">Confirm Password</th>
   <td><input class="form-control" type="password" id="confpass<?=$selu['EmployeeID']?>" value=""></td>
  </tr>
  <tr>
  <th scope="row"></th>
   <td>
     <button type="button" id="savepassbtn" class="btn btn-sm btn-primary vsmbtn " onclick="changepass('<?=$selu['EmployeeID']?>')" style="font-weight: bold;">&nbsp;<i class="fa fa-save" aria-hidden="true"></i> Save&nbsp;</button>
     <button type="button" class="btn btn-sm btn-primary " onclick="closepass('<?=$selu['EmployeeID']?>')" style="font-weight: bold;">&nbsp;<i class="fa fa-save" aria-hidden="true"></i> close&nbsp;</button>
     <div id="msgtbl<?=$selu['EmployeeID']?>"></div>
   </td>
  </tr>
 </tbody>
</table>
</div>
<?php /****************** 111111 Close *************/?>
<?php /****************** 111111 Close *************/?>


<?php /****************** 222222 Open *************/?>
<?php /****************** 222222 Open *************/?>
<div id="changetState<?=$selu['EmployeeID']?>" style="display:none;">
<table class="table table-sm shadow minpadtable" style="width:500px;" id="">
 <thead class="thead-dark">
 <tr><th scope="col" colspan="3">Assign State &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <button type="button" class="btn btn-sm btn-primary " onclick="closestate('<?=$selu['EmployeeID']?>')" style="font-weight: bold;">&nbsp;close&nbsp;</button>
 </th></tr>
 </thead>
 <tbody>
 <?php $sqle=mysql_query("SELECT CostCenter,StateName FROM `hrm_employee_general` g inner join hrm_state s on g.CostCenter=s.StateId Group by StateId order by StateName",$con2);
      while($rese=mysql_fetch_assoc($sqle)){ 
	  
$sqlus=mysql_query("select * from hrm_user_state where UserId=".$selu['EmployeeID']." AND StateId=".$rese['CostCenter']." AND Status=1");  
$rowus=mysql_num_rows($sqlus);	  
 ?>
 <tr>
   <th scope="row" style="width:200px;"><?=$rese['StateName']?></th>
   <td id="TD<?=$selu['EmployeeID'].'_'.$rese['CostCenter']?>" style="width:50px; text-align:center; background-color:<?php if($rowus>0){echo '#006600';}else{echo '#FFFFFF';}?>;"><input type="checkbox" class="form-control" id="StateChk<?=$selu['EmployeeID'].'_'.$rese['CostCenter']?>" onclick="FunChkSt(<?=$selu['EmployeeID'].','.$rese['CostCenter']?>)" <?php if($rowus>0){echo 'checked';}else{echo '';}?>></td>
   <td style="width:250px;"><div id="msgtbl2<?=$selu['EmployeeID'].'_'.$rese['CostCenter']?>"></div></td>
  </tr>
 <?php } ?> 
 </tbody>
</table>
</div>
<?php /****************** 222222 Close *************/?>
<?php /****************** 222222 Close *************/?>
					 </td>
					</tr>
				    
				    <?php
					}
					?>
				  </tbody>
				</table>
			</div>
			
		</div>
		
		
<script>
function showchngtbl(id){
		$("#changetbl"+id).show();
		$("#newpass").focus();
	}
	
function closepass(id){
		$("#changetbl"+id).hide();
		
	}	
	
function changepass(eid){
		var newpass=$("#newpass"+eid).val();
		var confpass=$("#confpass"+eid).val();

		if(newpass==confpass){
			$.post("uajax.php",{act:"changepass",eid:eid,newpass:newpass},function(data){

				if(data="success"){
					var s='<div class="alert alert-success"> <strong>Success!</strong> Password Changed Successfully. </div>';
					$("#msgtbl"+eid).html(s);

				}
				
			});
		}else{
			alert('New Password Field and Confirm Password Field not matched\nPlease Try Again');
			$("#newpass").val('');
			$("#confpass").val('');
			$("#newpass").focus();
		}
	}
	
	
function showState(id){ $("#changetState"+id).show();}
function closestate(id){ $("#changetState"+id).hide(); }		
function FunChkSt(eid,sid)
{
  if(document.getElementById("StateChk"+eid+"_"+sid).checked==true){var v=1; var vn='Checked'; }
  else{var v=0; var vn='Un-Checked';}
  
  $.post("uajax.php",{act:"StateAccess",eid:eid,sid:sid,v:v},function(data){
   if(data="success")
   { 
    var s='<strong>Successfully!</strong> '+vn+'.';
	if(v==1){ document.getElementById("TD"+eid+"_"+sid).style.background='#006600'; }  
	else if(v==0){ document.getElementById("TD"+eid+"_"+sid).style.background='#FFFFFF'; }  
   }
   else
   { 
    var s='<strong>Error</strong>';  
   }
   $("#msgtbl2"+eid+"_"+sid).html(s); 
	
  });
}	
	
</script>			
		
		
		<div class="col-md-4" id="udetsdiv">
			

			
			
		</div>
		
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

