<?php
include "header.php";
?>

<div class="container">
	<div class="row shadow">
		<div class="col-md-9">
			<?php if(isset($msg)){ ?>
				<div class="alert alert-<?=$msgcolor?> alert-dismissible">
			    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			    <strong><?=$msg?></strong>
			  </div>
			
			<?php } ?>
			<br>
			<select class="form-control" onchange="SelDept(this.value)" style="width:200px; background-color:#FFFF9D;">
			<option value="0" <?php if($_REQUEST['d']==0){echo 'selected';}?>>Select Department</option>
			<?php $sD=mysql_query("select DepartmentId,DepartmentCode,DepartmentName from hrm_department where CompanyId=".$_SESSION['CompanyId']." AND DeptStatus='A' order by DepartmentName ASC",$con2); while($rD=mysql_fetch_assoc($sD)){ ?>
		    <option value="<?=$rD['DepartmentId']?>" <?php if($_REQUEST['d']==$rD['DepartmentId']){echo 'selected';}?>><?=strtoupper($rD['DepartmentName'])?></option>
			<?php } ?>	
		    </select>


			<div class="table-responsive">
				<table class="table shadow" style="padding:0px;" cellspacing="0">

				  <thead class="thead-dark">
				    <tr>
					  <th scope="col" style="width:50px;text-align:center;">Sn</th>
					  <th scope="col" style="width:50px;text-align:center;">Code</th>
				      <th scope="col" style="width:300px;text-align:center;">Employee Name</th>
					  <th scope="col" style="width:50px;text-align:center;">Grade</th>
				      <th scope="col" style="width:200px;text-align:center;">Slab List</th>
				    </tr>
				  <tbody>
				  <?php $sely=mysql_query("SELECT * FROM `vehicle_policyslab` where CompanyId=".$_SESSION['CompanyId']." AND SlabStatus='A' order by VPId"); while($selyd=mysql_fetch_assoc($sely)){ $Vparr[$selyd['VPId']]=$selyd['VPolicyName']; }
				  
				  $sql=mysql_query("SELECT EmpCode,e.EmployeeID,Fname,Sname,Lname,GradeValue FROM `hrm_employee` e inner join hrm_employee_general g on e.EmployeeID=g.EmployeeID inner join hrm_grade gr on g.GradeId=gr.GradeId where g.DepartmentId=".$_REQUEST['d']." AND e.EmpStatus='A' order by EmpCode",$con2); $no=1; while($res=mysql_fetch_assoc($sql)){ 
				  $sSlbE=mysql_query("select VPId from vehicle_policyslab_employee where EmployeeID=".$res['EmployeeID']);
				  $rSlbE=mysql_fetch_assoc($sSlbE)
				  ?>
				    <tr>
					  <td style="text-align:center;"><?=$no?></th>
					  <td style="text-align:center;"><?=$res['EmpCode']?></th>
					  <td><?=$res['Fname'].' '.$res['Sname'].' '.$res['Lname']?></td>
					  <td style="text-align:center;"><?=$res['GradeValue']?></td>
					  <td style="text-align:center;">
					   <select class="form-control frminp" name="slab" id="slab<?=$no?>" style="background-color:<?php if($rSlbE['VPId']>0){echo '#B7FF6F';}else{echo '#FFFFFF';}?>;" onchange="SelVpISlab(this.value,<?=$res['EmployeeID']?>,<?=$no?>)">
					   <option  value="" <?php if($rSlbE['VPId']==''){echo 'selected';}?>>Select Slab</option>
					   <?php foreach ($Vparr as $key => $value) { ?>
					   <option value="<?=$key?>" <?=($rSlbE['VPId']==$key)?'selected':'';?>><?=$value?></option>
					   <?php } ?>
					   <option  value="0" <?php if($rSlbE['VPId']==0){echo 'selected';}?>>NA</option>
				       </select>
					  </td>
					</tr>
				    <?php $no++; } ?>
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

function SelDept(d){
	window.location.href = 'mappingslab.php?d='+d;
}

function SelVpISlab(slb,ei,no)
{

 if(slb!='' && ei>0)
 {
 
  if(confirm('Are you sure ?'))
  {	
   $.post("mappingslabajax.php",{act:"MappedSlab",slb:slb,ei:ei},function(data){ //alert(data);
    if(data.includes('Done'))
    { 
	 document.getElementById("slab"+no).style.background='#B7FF6F'; 
    }
	else
	{
	 alert("Error Occur");
	}
   });
  }
  else{ return false; }
  
 }
 else{ alert("please select slab!"); return false; }
 
}


</script>
