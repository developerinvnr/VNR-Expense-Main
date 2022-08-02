<?php
include "header.php";
?>

<?php


$seluq=mysql_query("SELECT * FROM `hrm_employee`");
$users=mysql_num_rows($seluq);


$seleq=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims`");
$expenses=mysql_num_rows($seleq);

?>


	<div class="row h-100">
		<div class="col-md-6">
			<br><br><br><br><br><br>
			<h2  style="color: #9299a0;" class="text-right">
				Welcome<br>To
			</h2>
			<h1 style="color: #9299a0;margin-top: -40px;" class="text-right">
				<span style="font-size: 100px;">Expense</span>
			</h1>
			<div class="text-muted pull-right">
	   			Platform to Claim and Record Expense
	   		</div>
		</div>
		<div class="col-md-4" style="border-left:5px solid #d9d9d9;">
			
				<br><br>
				<?php
				if($_SESSION['EmpRole']=='S'){
				?>
				<div class="row shadow dashinfo" >
					<div class="subinfohead">
						<i class="fa fa-user fa-2x" aria-hidden="true"></i>&emsp;
						<h4 style="display: inline-block;">Users</h4>
					</div>
					<div>
						<div class="subinfos">
							Total: <?php echo mysql_num_rows(mysql_query("SELECT * FROM `hrm_employee`")); ?> 
						</div>
						<div class="subinfos">
							Active: <?php echo mysql_num_rows(mysql_query("SELECT * FROM `hrm_employee` where EmpStatus='A'")); ?> 
						</div>
						<div class="subinfos">
							Deactive: <?php echo mysql_num_rows(mysql_query("SELECT * FROM `hrm_employee` where EmpStatus='D'")); ?>
						</div>
						
					</div>
				</div>
				<?php
				}else{echo '<br><br>';}
				?>
				<div class="row shadow dashinfo">
					<div class="subinfohead">
						<i class="fa fa-file fa-2x" aria-hidden="true"></i>&emsp;
						<h4 style="display: inline-block;"><?php if($_SESSION['EmpRole']!='S'){echo 'My ';}?>Claims</h4> </div>

					<div >
						<?php if($_SESSION['EmpRole']!='S'){$crcond="CrBy=".$_SESSION['EmployeeID'];}else{$crcond="1=1";}?>
						<div class="subinfos">
							Total: <?php echo mysql_num_rows(mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` where ".$crcond)); ?> 
						</div>
						<div class="subinfos">
							Approved: <?php echo mysql_num_rows(mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` where ApprBy != 0 and ".$crcond)); ?> 
						</div>
						<div class="subinfos">
							Unapproved: <?php echo mysql_num_rows(mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` where ApprBy = 0 and ".$crcond)); ?>
						</div>
						<div class="subinfos">
							Verified: <?php echo mysql_num_rows(mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims`  where VerifyBy != 0 and ".$crcond)); ?> 
						</div>
					</div>
				</div>

				<div class="row shadow dashinfo">
					<div class="subinfohead">
						<i class="fa fa-file fa-2x" aria-hidden="true"></i>&emsp;
						<h4 style="display: inline-block;"><?php if($_SESSION['EmpRole']!='S'){echo 'My ';}?>To Act On</h4> </div>

					<div >
						<?php if($_SESSION['EmpRole']!='S'){$crcond="CrBy=".$_SESSION['EmployeeID'];}else{$crcond="1=1";}?>
						<div class="subinfos">
							Total: <?php echo mysql_num_rows(mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` where ".$crcond)); ?> 
						</div>
						<div class="subinfos">
							Approved: <?php echo mysql_num_rows(mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` where ApprBy != 0 and ".$crcond)); ?> 
						</div>
						<div class="subinfos">
							Unapproved: <?php echo mysql_num_rows(mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` where ApprBy = 0 and ".$crcond)); ?>
						</div>
						<div class="subinfos">
							Verified: <?php echo mysql_num_rows(mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims`  where VerifyBy != 0 and ".$crcond)); ?> 
						</div>
					</div>
				</div>

				
				
				

			
		</div>
	</div>

	
</div>
<?php
include "footer.php";
?>