<?php
include "header.php";

if($_REQUEST['act']=='DisplayMonth')
{
  $sm=mysql_query("SELECT * FROM `y".$_REQUEST['y']."_monthexpensefinal` WHERE `EmployeeID`='".$_REQUEST['ei']."' and Month=".$_REQUEST['m']." and YearId=".$_REQUEST['y']); $rowm=mysql_num_rows($sm);
  if($rowm==0)
  {
   $ins=mysql_query("insert into `y".$_REQUEST['y']."_monthexpensefinal`(EmployeeID, Month, YearId, Status, Crdate) values (".$_REQUEST['ei'].", ".$_REQUEST['m'].", ".$_REQUEST['y'].", 'Open', '".date("Y-m-d")."')");
   if($ins){ echo '<script>alert("Month Availabled!"); window.location="openmonth.php?d='.$_REQUEST['d'].'";</script>'; }
  }
  else
  {
   echo '<script>alert("Month Already Availabled!"); window.location="openmonth.php?d='.$_REQUEST['d'].'";</script>'; 
  }
}

?>

<div class="container">
	<div class="row shadow">
		<div class="col-md-10">
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
				      <th scope="col" style="width:200px;text-align:center;">Employee Name</th>
					  <th scope="col" style="width:50px;text-align:center;">Grade</th>
					  <th scope="col" style="width:200px;text-align:center;">Availabe Month</th> 
				      <th scope="col" style="width:100px;text-align:center;">Open Month</th>
				    </tr>
				  <tbody>
				  <?php 
				  $sql=mysql_query("SELECT EmpCode,e.EmployeeID,Fname,Sname,Lname,GradeValue FROM `hrm_employee` e inner join hrm_employee_general g on e.EmployeeID=g.EmployeeID inner join hrm_grade gr on g.GradeId=gr.GradeId where g.DepartmentId=".$_REQUEST['d']." AND e.EmpStatus='A' order by EmpCode",$con2); $no=1; while($res=mysql_fetch_assoc($sql)){ 
				  ?>
				    <tr>
					  <td style="text-align:center;"><?=$no?></th>
					  <td style="text-align:center;"><?=$res['EmpCode']?></th>
					  <td><?=$res['Fname'].' '.$res['Sname'].' '.$res['Lname']?></td>
					  <td style="text-align:center;"><?=$res['GradeValue']?></td>
					  <td style="text-align:center;"><?php $m=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$res['EmployeeID']."' and YearId=".$_SESSION['FYearId']." group by Month order by Month asc"); while($mlist=mysql_fetch_assoc($m)){ if($mlist['Month']<=12){ echo $mlist['Month'].' &nbsp; '; } }?></td>
					  
					  <td style="text-align:center;">
					   <select class="form-control frminp" name="slab" id="slab<?=$no?>" style="background-color:#FFFFFF';" onchange="SelVpMonth(this.value,<?=$res['EmployeeID']?>,<?=$no.','.$_REQUEST['d'].','.$_SESSION['FYearId']?>)">
					   <option value='' selected>Select</option>    
					   <?php for($i=1; $i<=12; $i++){ ?>
					   <option value="<?=$i?>"><?=strtoupper(date('F', mktime(0,0,0,$i, 1, date('Y'))))?></option>
					   <?php } ?>
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
	window.location.href = 'openmonth.php?d='+d;
}

function SelVpMonth(m,ei,no,d,y)
{

 if(m!='' && ei>0)
 {
 
  if(confirm('Are you sure ?'))
  { window.location.href = 'openmonth.php?act=DisplayMonth&m='+m+'&ei='+ei+'&no='+no+'&d='+d+'&y='+y; }
  else{ return false; }
  
 }
 else{ alert("please select month!"); return false; }
 
}


</script>
