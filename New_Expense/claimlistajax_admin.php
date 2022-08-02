<?php
session_start();

include 'config.php';

?>

 <link rel="icon" href="images/faviconexpro.png" type="image/png" sizes="16x16">

<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="css/jquery.datetimepicker.css">
<link rel="stylesheet" type="text/css" href="css/style.css">



<?php

function getClaimType($cid){
	$c=mysql_query("SELECT ClaimName FROM `claimtype` where ClaimId=".$cid);
	$ct=mysql_fetch_assoc($c);
	return $ct['ClaimName'];
}
function getUser($u){
	$u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$u,$con2);
	$un=mysql_fetch_assoc($u);
	return $un['Fname'].' '.$un['Sname'].' '.$un['Lname'];
}
?>
<?php
if($_POST['act']=='clGrpShowMonthDet'){

?>
<td colspan="6" style="text-align:justify;">
<?php

	$grpscount = array();

	
	$msts=mysql_query("select Status from `y".$_SESSION['FYearId']."_monthexpensefinal` where Month='".$_POST['month']."' and EmployeeID='".$_REQUEST['emp']."'");
	$mstsd=mysql_fetch_assoc($msts);

	$cg=mysql_query("select cgId,cgName from claimgroup");


	
	while($cgd=mysql_fetch_assoc($cg)){

		//$t=mysql_query("SELECT e.ClaimId FROM `y".$_SESSION['FYearId']."_expenseclaims` e, `y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` ef, `claimtype` ct WHERE e.`ClaimMonth`='".$_POST['month']."' and e.`ClaimYearId`='".$_SESSION['FYearId']."' and e.`CrBy`='".$_REQUEST['emp']."' and ef.ExpId=e.ExpId and e.ClaimId=ct.ClaimId and ct.cgId=".$cgd['cgId']);


		$t=mysql_query("SELECT e.ClaimId FROM `y".$_SESSION['FYearId']."_expenseclaims` e, `claimtype` ct  WHERE e.`ClaimMonth`='".$_POST['month']."' and e.`ClaimYearId`='".$_SESSION['FYearId']."' and e.`CrBy`='".$_REQUEST['emp']."' and e.ClaimId=ct.ClaimId and e.ClaimStatus!='Draft' and e.ClaimStatus!='Deactivate' and e.FilledBY>0 and e.AttachTo=0 and ct.cgId=".$cgd['cgId']);
		
		// echo $cgd['cgId'].'-'.mysql_num_rows($t).'<br>';

		$grpscount['g'.$cgd['cgId']] = mysql_num_rows($t);

	}

	
	// print_r($grpscount);

	echo '<span class="pull-left" style="padding-left: 1px !important;"></span>';

	//$p=mysql_query("SELECT e.ClaimStatus FROM `y".$_SESSION['FYearId']."_expenseclaims` e WHERE e.`ClaimMonth`='".$_POST['month']."' and e.`ClaimYearId`='".$_SESSION['FYearId']."' and e.`CrBy`='".$_REQUEST['emp']."'  and (e.ClaimStatus='Submitted' or e.ClaimStatus='Draft') and e.AttachTo=0");


	?>
	<!-- <button class="btn btn-outline-warning btn-sm pull-left grpbtns" style="font-weight:bold;height:25px;font-size:10px; margin-left:2px;" onclick="clShowDaysOfMonth(this,'<?=$_POST['month']?>','<?=$_REQUEST['emp']?>',0,'<?=$mstsd['Status']?>','Pending')" >
		P&nbsp;<span class="badge badge-danger" style="font-size:10px;"><?=mysql_num_rows($p)?></span>
	</button> -->
 
	

	<?php
	$cge=mysql_query("select cgId,cgCode from claimgroup");
	while($cged=mysql_fetch_assoc($cge)){
		
	?>
    <button class="btn btn-outline-primary btn-sm pull-left grpbtns" style="font-weight:bold;height:25px;font-size:10px;margin-left:2px;" onclick="clShowDaysOfMonth(this,'<?=$_POST['month']?>','<?=$_REQUEST['emp']?>','<?=$cged['cgId']?>','<?=$mstsd['Status']?>','Filled')" style="" >
		<?=$cged['cgCode']?> <span class="badge badge-danger" style="font-size:10px;"><?=$grpscount['g'.$cged['cgId']]?></span>
	</button>
	
	<!-- clshowmonthdet(this,'<?=$_POST['month']?>','<?=$_REQUEST['emp']?>','<?=$cged['cgId']?>') -->
	<?php

	}
	
?>

</td>
<?php

}elseif($_POST['act']=='daysOfMonthDetToClaimer'){
?>

<!--<td style="background-color: #CCCCCC;"></td>-->
<td colspan="7" style="text-align:left;padding-left:1px !important;">
	
	<!-- style="width:320px;overflow:auto !important;" -->
	<div id="clcldetdiv" style="display: inline-block !important;">
	<table class="table shadow" style="width:100%;" align="center">
	  <thead class="thead-dark">
	    <tr>
			<th scope="col" style="background-color:#007bff; width:20px;"><font style="font-size:11px;">Sn</font></th>
			
			<th scope="col" style="background-color:#007bff; width:70px;"><font style="font-size:11px;">Date</font></th>
			
			<th scope="col" style="background-color:#007bff; width:60px;"><font style="font-size:11px;">Total Amt</font></th>
			<?php //echo 'aa='.$_POST['msts'];
			if(isset($_POST['msts']) && $_POST['msts']=='Closed'){
			?>
			<th scope="col" style="background-color:#007bff;width:60px;"><font style="font-size:11px;">Paid Amt</font></th>
			<?php 
			}
			?>

			<th scope="col" style="background-color:#007bff; width:150px;">
				<?php

				if(isset($_POST['cgid']) && $_POST['cgid']!=0 && isset($_POST['msts']) && $_POST['msts']!='Closed'){
				?>
				<label for="okayall<?=$_POST['cgid']?>">
				<span id="okbtn<?=$exp['ExpId']?>" class="btn btn-sm btn-success" style="">
					<input id="okayall<?=$_POST['cgid']?>" type="checkbox" onclick="okayall('<?=$_REQUEST['emp']?>','<?=$_REQUEST['month']?>','<?=$_POST['cgid']?>')">
					<font style="font-size:10px;">Okay All</font>
				</span>
				</label>
				<?php
				}
				?>
			</th>

	    </tr>
	  </thead>
	  <tbody>

	  	
		<?php 

			if($_POST['cgid']==0 || $_POST['csts']=='Draft'){
				$q="SELECT e.CrDate as finalDate,e.*, h.Fname,h.Sname,h.Lname, h.EmployeeID FROM `y".$_SESSION['FYearId']."_expenseclaims` e, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and e.ClaimMonth='".$_REQUEST['month']."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_REQUEST['emp']."  and (e.ClaimStatus='Submitted' or e.ClaimStatus='Draft') and e.AttachTo=0 group by finalDate"; 
				
			}else{
				// echo $q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname,ef.BillDate as finalDate FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c, ".dbemp.".hrm_employee h,`y".$_SESSION['FYearId']."_g".$_POST['cgid']."_expensefilldata` ef where h.EmployeeID=e.CrBy and e.ClaimMonth='".$_REQUEST['month']."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_REQUEST['emp']." and (c.ClaimId=e.ClaimId) and c.cgId='".$_POST['cgid']."' and e.ClaimStatus='".$_POST['csts']."'  group by finalDate"; 


              // changes done feb 26 ef.BillDate
                // $q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname,ef.BillDate as finalDate, h.EmployeeID FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c, ".dbemp.".hrm_employee h,`y".$_SESSION['FYearId']."_g".$_POST['cgid']."_expensefilldata` ef where h.EmployeeID=e.CrBy and e.ClaimMonth='".$_REQUEST['month']."' and  e.CrBy=".$_REQUEST['emp']." and (c.ClaimId=e.ClaimId) and c.cgId='".$_POST['cgid']."' and e.ClaimStatus='".$_POST['csts']."'  group by finalDate"; 

				$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname,e.BillDate as finalDate, h.EmployeeID FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c, ".dbemp.".hrm_employee h,`y".$_SESSION['FYearId']."_g".$_POST['cgid']."_expensefilldata` ef where h.EmployeeID=e.CrBy and e.ClaimMonth='".$_REQUEST['month']."' and  e.CrBy=".$_REQUEST['emp']." and (c.ClaimId=e.ClaimId) and c.cgId='".$_POST['cgid']."' and e.ClaimStatus!='Deactivate' and e.FilledBy>0  group by finalDate"; 
			
			    //echo $q;
			    
			}


         // print_r($q); 
         // die();
	 	 $seleq=mysql_query($q);
          
		  $i=1; 
		  $k = 0;
		  while($exp=mysql_fetch_assoc($seleq)){ 
              $finalDate = explode('/', date("j/n/Y", strtotime($exp['finalDate'])));
               if($finalDate[1]==$_REQUEST['month']){
               
               $k = $k+1;
		  	?>
	    <tr onclick="showdet('<?=$exp['EmployeeID']?>')" >
	       <th scope="row">
	       	<font style="font-size:11px;"><?=$k?></font>
	       	<input type="hidden" id="sts<?=$exp['finalDate']?>1" value="close">
	       </th>
	       
	       
			<td>
				<a href="JavaScript:Void(0);" onclick="clShowDaydet(this,'<?=$exp['finalDate']?>','<?=$_REQUEST['emp']?>','<?=$_POST['cgid']?>','<?=$_POST['csts']?>')">
					<font style="font-size:12px;cursor: pointer;"><?=date("d/m/y",strtotime($exp['finalDate']))?></font>
				</a>
			</td>
		   
		   	<td class="text-center" style="vertical-align:middle;">
		   		<font style="font-size:11px;">
		   		<?php 
		   		
		   		if($_POST['cgid']==0 || $_POST['csts']=='Draft'){
		   		$totpaida=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_REQUEST['emp']."' and c.ClaimId=e.ClaimId  and c.cgId='".$_POST['cgid']."' and e.CrDate='".$exp['finalDate']."' and e.ClaimStatus='".$_POST['csts']."' and e.FilledOkay!=2"); 

			   	}else{
			   		$totpaida=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_REQUEST['emp']."' and c.ClaimId=e.ClaimId  and c.cgId='".$_POST['cgid']."' and e.BillDate='".$exp['finalDate']."' and e.ClaimStatus!='Deactivate' and e.FilledBy>0 and e.FilledOkay!=2"); 
			   		
			   		 
			   	}

			   	$clm=mysql_fetch_assoc($totpaida);	
		  		echo $clm['paid']; if($clm['paid']>0){echo '/-';}

		  		 

		  		?>
		  		</font>
			</td>
			
			<?php
			if(isset($_POST['msts']) && $_POST['msts']=='Closed'){
			?>
			<td class="text-right">
		   		<font style="font-size:11px;">
		   		<?php 
		   		$totpaid=mysql_query("SELECT SUM(FinancedTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_REQUEST['emp']."' and c.ClaimId=e.ClaimId  and c.cgId='".$_POST['cgid']."' and e.BillDate='".$exp['finalDate']."' and e.ClaimStatus!='Deactivate' and e.FilledBy>0 and e.FinancedBy>0 and e.FinancedDate!='0000-00-00'"); 
		  		$clm=mysql_fetch_assoc($totpaid);
		  		echo $clm['paid'];  if($clm['paid']>0){echo '/-';}?>
		  		</font>
			</td>
			<?php 
			}else{
			?>
			


			<td class="text-right">

				<?php 
                // $subm=mysql_query("SELECT CrDate FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_REQUEST['emp']."' and CrDate='".$exp['CrDate']."' and ClaimStatus='Submitted' and AttachTo=0"); $rsub=mysql_num_rows($subm);
				  
		   		$filla=mysql_query("SELECT CrDate FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_REQUEST['emp']."' and BillDate='".$exp['finalDate']."' and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0 and AttachTo=0"); 
		  		$fill=mysql_num_rows($filla);	

		  		$totpaid=mysql_query("SELECT CrDate FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_REQUEST['emp']."' and BillDate='".$exp['finalDate']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0 and AttachTo=0"); 
		  		$ok=mysql_num_rows($totpaid);	

		  		$totpaid=mysql_query("SELECT CrDate FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_REQUEST['emp']."' and BillDate='".$exp['finalDate']."' and FilledOkay=2 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0 and AttachTo=0"); 
		  		$den=mysql_num_rows($totpaid);	
		  		

		  		?>


                <!-- <?php if($rsub>0){ ?>
		   		<button class="btn btn-outline-warning btn-sm pull-left grpbtns" style="font-weight: bold;font-size:10px;" <?php /*?>onclick="clShowDaysOfMonth(this,'<?=$_POST['month']?>','<?=$_REQUEST['emp']?>','<?=$cged['cgId']?>','<?=$mstsd['Status']?>')"<?php */?> >
					P&nbsp;<span class="badge badge-warning" style="font-size: 10px;"><?=$rsub?></span>
				</button>
				<?php } ?> -->
				
				<?php if($fill>0){ ?>
		   		<button class="btn btn-outline-info btn-sm pull-left grpbtns" style="font-weight: bold;font-size:10px;margin-left:<?php if($rsub>0){echo 2;}else{0;} ?>px;" <?php /*?>onclick="clShowDaysOfMonth(this,'<?=$_POST['month']?>','<?=$_REQUEST['emp']?>','<?=$cged['cgId']?>','<?=$mstsd['Status']?>')"<?php */?> >
					F&nbsp;<span class="badge badge-info" style="font-size: 10px;"><?=$fill?></span>
				</button>
				<?php } ?>
				<?php if($ok>0){ ?>
				<button class="btn btn-outline-success btn-sm pull-left grpbtns" style="font-size:10px;margin-left:<?php if($fill>0){echo 2;}else{0;} ?>px;" <?php /*?>onclick="clShowDaysOfMonth(this,'<?=$_POST['month']?>','<?=$_REQUEST['emp']?>','<?=$cged['cgId']?>','<?=$mstsd['Status']?>')"<?php */?> >
					&#10003;&nbsp;<span class="badge badge-success" style="font-size: 10px;"><?=$ok?></span>
				</button>
				<?php } ?>
				<?php if($den>0){ ?>
				<button class="btn btn-outline-danger btn-sm pull-left grpbtns" style="font-weight: bold;font-size:10px; margin-left:<?php if($ok>0){echo 2;}else{0;} ?>px;" <?php /*?>onclick="clShowDaysOfMonth(this,'<?=$_POST['month']?>','<?=$_REQUEST['emp']?>','<?=$cged['cgId']?>','<?=$mstsd['Status']?>')"<?php */?> >
					D&nbsp;<span class="badge badge-danger" style="font-size: 10px;"><?=$den?></span>
				</button>
				<?php } ?>


			</td>
			
			<?php
			}
			?>
			
	    </tr>
	
	    <tr id="<?=$exp['finalDate']?>1">
			
		</tr>
		<?php }?>
	    <?php $i++;} ?>
		
	  </tbody>
	</table>
	</div>	
</td>

<?php
}elseif($_POST['act']=='clShowDaydet'){
?>

<!--<td style="background-color: #CCCCCC;"></td>-->
<td colspan="6" style="text-align:left;padding-left: 6px !important;">

	
	
	<!-- style="width:320px;overflow:auto !important;" -->
	<div id="clcldetdiv" style="display: inline-block !important;">
	<table class="table shadow" style="width:100%;" align="center">
	  <thead class="thead-dark">
	    <tr>
			<th scope="col" style="width:10px;background-color:#008C8C;"><font style="font-size:11px;">Sn</font></th>
			<!-- <th scope="col" style="width: 50px;background-color:#008C8C;"><font style="font-size:11px;">Claim ID</font></th> -->
			<!-- <th scope="col" style="width: 150px;background-color:#008C8C;"><font style="font-size:11px;">Claim</font></th> -->
			<th scope="col" style="width:90px;background-color:#008C8C;"><font style="font-size:11px;">Claim Id</font></th>
			<th scope="col" style="width:90px;background-color:#008C8C;"><font style="font-size:11px;">Claim Type</font></th>
			<!-- <th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Applied<br />Date</font></th> -->
			<th scope="col" style="width:60px;background-color:#008C8C;"><font style="font-size:11px;">Amount</font></th>
			
			<th scope="col" style="background-color:#008C8C;width:100px;"><font style="font-size:11px;">Status</th>
			<th scope="col" style="background-color:#008C8C;width:50px;"><font style="font-size:11px;">Verified<br />Amt</th>
			<th scope="col" style="background-color:#008C8C;width:150px;"><font style="font-size:11px;">Remark</th>
			
			<?php /*if(!isset($_REQUEST['csts']) || $_REQUEST['csts']!='Filled'){?>
			<th scope="col" style="width:30px;background-color:#008C8C;"><font style="font-size:11px;">View</font></th>
			<?php //} */ ?>
	    </tr>
	  </thead>
	  <tbody>
	  	<!-- <tr class="totalrow">
	      <th scope="row" colspan="2" class="text-right"><b style="font-size:11px;">Total&nbsp;</b></th>
		  <td class="text-right"><font style="font-size:11px;"><?php $totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_REQUEST['emp']."' and c.ClaimId=e.ClaimId  and c.cgId='".$_POST['cgid']."' and e.CrDate='".$_POST['date']."' and e.AttachTo=0 and e.ClaimStatus='".$_POST['csts']."'"); 

		  $clm=mysql_fetch_assoc($totpaid);	echo $clm['paid']; ?></font></td>

			<th></th>
		   
	    </tr> -->

		<?php 

			if($_POST['cgid']==0 || $_POST['csts']=='Draft'){
				$q="SELECT e.*, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims` e, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy  and e.ClaimYearId='".$_SESSION['FYearId']."' and e.CrBy=".$_REQUEST['emp']." and e.CrDate='".$_POST['date']."' and e.AttachTo=0 and (e.ClaimStatus='Submitted' or e.ClaimStatus='Draft') group by e.ExpId"; 
				
			}else{
				// $q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c, ".dbemp.".hrm_employee h,`y".$_SESSION['FYearId']."_g".$_POST['cgid']."_expensefilldata` ef where e.ExpId=ef.ExpId and h.EmployeeID=e.CrBy  and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_REQUEST['emp']." and (c.ClaimId=e.ClaimId) and c.cgId='".$_POST['cgid']."' and ef.BillDate='".$_POST['date']."' and e.AttachTo=0 and e.ClaimStatus='".$_POST['csts']."' and e.ClaimStatus!='Draft' group by e.ExpId"; 

				$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c, ".dbemp.".hrm_employee h,`y".$_SESSION['FYearId']."_g".$_POST['cgid']."_expensefilldata` ef where e.ExpId=ef.ExpId and h.EmployeeID=e.CrBy  and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_REQUEST['emp']." and (c.ClaimId=e.ClaimId) and c.cgId='".$_POST['cgid']."' and e.BillDate='".$_POST['date']."' and e.AttachTo=0 and e.FilledBy>0 and e.ClaimStatus!='Deactivate' and e.ClaimStatus!='Draft' group by e.ExpId"; 
			}
	 	 	
             //and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'
            // print_r($q);
	 	 	$seleq=mysql_query($q);
      		$i=1; 

      		while($exp=mysql_fetch_assoc($seleq)){ 

      		if($exp['ClaimId']!=0){

	      		$cg=mysql_query("select cgId from claimtype where ClaimId=".$exp['ClaimId']);
				$cgd=mysql_fetch_assoc($cg);

				$ef=mysql_query("select * from y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata where ExpId=".$exp['ExpId']);
				$expf=mysql_fetch_assoc($ef);
			
			}
		
      		?>

      	<?php
      	if($exp['ClaimStatus']=='Deactivate'){ $trcolor='background-color:#ffcccc;';}else{$trcolor='';} ?>
	    <tr onclick="showdet('<?=$exp['EmployeeID']?>')" style="<?=$trcolor?>">
	       <th scope="row"><font style="font-size:11px;"><?=$i?></font></th>
	       <?php /* 
	       <td><?=$exp['ExpId']?></td> 
	                <td><?=$exp['ExpenseName']?></td> */ ?>
	       <td><?=$exp['ExpId']?></td>         
	       <td>
	       	<a href="JavaScript:Void(0);" onclick="showexpdet('<?=$exp['ExpId']?>')">
	       		<font style="font-size:11px;">
	       			<?php if($exp['ClaimId']!=0 && $exp['ClaimStatus']!='Draft'){echo substr($exp['ClaimName'], 0, 12);}else{echo substr('Not Filled', 0, 12);}?>
	       		</font>
	       	</a>
	       </td>
	       <!-- <td><a href="#" onclick="showexpdet('<?=$exp['ExpId']?>')"><font style="font-size:11px;"><?=date("d/m/y",strtotime($exp['CrDate']))?></font></a></td> -->
		   <td class="text-right">

		   	<?php
		   
		   	$limcheck=0;
		 
		   	/* 
		   	=======================================================================================
			here in this if elseif nest below, deciding the columns of hrm_employee_eligibility table to be  picked for checking the expense limit of that claim type
		   	=======================================================================================
		   	*/
	      	if($exp['ClaimId']==1){
	      		$column='Lodging_Category'.$expf['CityCategory'];
	      		$limcheck=1;
	      		
	      	}elseif($exp['ClaimId']==5) {
	      		$column='Mobile_Exp_Rem_Rs';
	      		$limcheck=1;
	      	}elseif($exp['ClaimId']==14) {

	      		$seldep=mysql_query("SELECT `DepartmentCode` FROM `hrm_department` hd, `hrm_employee_general` heg where heg.DepartmentId=hd.DepartmentId and heg.EmployeeID=".$exp['CrBy'],$con2);
				$depnm=mysql_fetch_assoc($seldep);

				if($depnm['DepartmentCode']=='SALES'){
					$column='DA_Inside_HqSal';
				}elseif($depnm['DepartmentCode']=='PD'){
					$column='';
				}else{
					$column='DA_Inside_Hq';
				}

	      		$limcheck=1;
	      	}elseif($exp['ClaimId']==15) {
	      		$seldep=mysql_query("SELECT `DepartmentCode` FROM `hrm_department` hd, `hrm_employee_general` heg where heg.DepartmentId=hd.DepartmentId and heg.EmployeeID=".$exp['CrBy'],$con2);
				$depnm=mysql_fetch_assoc($seldep);

				if($depnm['DepartmentCode']=='SALES'){
					$column='DA_Outside_HqSal';
				}elseif($depnm['DepartmentCode']=='PD'){
					$column='DA_Outside_HqPD';

				}else{
					$column='DA_Outside_Hq';
				}
	      		$limcheck=1;
	      	}

	      	/* 
		   	=======================================================================================
			this below  if  will only run if the claim type have expense limit
		   	=======================================================================================
		   	*/
	      	if($limcheck==1){
	      		$li=mysql_query("SELECT ".$column." FROM `hrm_employee_eligibility` where EmployeeID=".$_REQUEST['emp']." order by EligibilityId desc limit 1",$con2);
				$lim=mysql_fetch_assoc($li);

				?>
				<input type="hidden" id="claimamt<?=$exp['ExpId']?>" value="<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed"))){ $famt=intval($exp['FilledTAmt']); echo $famt;	} ?>">
			   	<input type="hidden"  id="limitamt<?=$exp['ExpId']?>" value="<?=$lim[$column]?>">
				<?php
	      	}
	      	
		
			?>


		   	<font style="font-size:11px;"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed"))){ $famt=intval($exp['FilledTAmt']); echo $famt;	} ?></font>			

		   </td>
			
			
			
	      <td style="text-align: left;">

	      	


	      <?php //$c='outline-secondary'; //$s=$exp['ClaimStatus'];



			if($exp['ClaimStatus']=='Submitted' || $exp['ClaimStatus']=='Draft'){$s='P'; $clss='btn btn-sm btn-outline-warning font-weight-bold';}
			elseif($exp['ClaimStatus']=='Filled'){$s='F'; $clss='btn btn-sm btn-outline-info font-weight-bold';}
			elseif($exp['ClaimStatus']=='Deactivate'){$s='D'; $clss='btn btn-sm btn-danger font-weight-bold';} ?>


	      	<div id="stsdiv<?=$exp['ExpId']?>" class="<?=$clss?>"><font style="font-size:10px;"><?=$s?></font></div>

	      	
	       
			<span id="okspanarea<?=$exp['ExpId']?>">
				<?php if($exp['DateEntryRemark']!=""){ ?>

					<input value="<?=$exp['DateEntryRemark']?>" readonly style="width: 90%;" class="badge-danger">
				<?php } ?>
			 <?php if($exp['ClaimStatus']=="Filled" && $exp['FilledOkay']==0){

                 $closed='';
                 if(isset($_POST['date'])){
                 $m=date("n",strtotime($_POST['date']));
                 $c=mysql_query("SELECT * FROM y".$_SESSION['FYearId']."_monthexpensefinal WHERE EmployeeID = '".$exp['CrBy']."' and Month ='".$m."'");
				   
				    $ct=mysql_fetch_assoc($c);
			         
					 if($ct['Status']=='Closed'){

                       $closed='disabled';
					 }

                 }
               
			  ?>

				<!-- <input type="checkbox" onclick="okcheck(this,'<?=$exp['ExpId']?>')"> -->
				<button id="okbtn<?=$exp['ExpId']?>" class="btn btn-sm btn-success" onclick="showOkDenyForm('<?=$exp['ExpId']?>','okay')" style="" <?=$closed?>>
					<font style="font-size:10px;">Okay</font>
				</button>
				<button id="denybtn<?=$exp['ExpId']?>" class="btn btn-sm btn-danger" onclick="showOkDenyForm('<?=$exp['ExpId']?>','deny')" <?=$closed?>>
					<font style="font-size:10px;">Deny</font>
				</button>

				<span id="okdenyspan<?=$exp['ExpId']?>" style="display: none;">
					<input id="okdenyremark<?=$exp['ExpId']?>" class="" placeholder="Pls type reason" style="width:110px;">
					<button id="saveokbtn<?=$exp['ExpId']?>" class="btn btn-sm btn-success" onclick="expfillok('<?=$exp['ExpId']?>')" style="padding: 1px !important; width:35px;display: none;">Save
					</button>
					<button id="savedenybtn<?=$exp['ExpId']?>" class="btn btn-sm btn-danger" onclick="expfilldeny('<?=$exp['ExpId']?>')" style="padding: 1px !important; width:35px;display: none;">Save
					</button>
				</span>
				
			 
			 <?php }elseif($exp['ClaimStatus']=="Filled" && $exp['FilledOkay']==1){ ?>
			
			 	<div class="btn btn-sm btn-success"><font style="font-size:10px;">&#10003;</font></div> 
			 
			 <?php }elseif($exp['ClaimStatus']=="Filled" && $exp['FilledOkay']==2){ ?>
			
			 	<div class="btn btn-sm btn-danger"><font style="font-size:10px;">D</font></div> 
			 
			 <?php } ?>
			</span> 

			<?php if($exp['ClaimAtStep']==1 || $exp['ClaimAtStep']==2){ ?>   
				<button class="btn btn-sm btn-danger pull-right"  style="padding:3px 3px !important;align-self: top!important;" onclick="deactivate(this,'<?=$exp['ExpId']?>')" >
					<i class="fa fa-times" aria-hidden="true"></i>
				</button>
			<?php } ?>

			<?php if($exp['ClaimStatus']=="Submitted" || $exp['ClaimStatus']=="Draft"){ ?>    	
				
				<button class="btn btn-sm btn-info pull-right"  style="padding:3px 3px !important;align-self: top!important;margin-right:10px;" onclick="attachclaims(this,'<?=$exp['ExpId']?>')" >
					<i class="fa fa-paperclip" aria-hidden="true"></i>
				</button>

			<?php } ?>

	      </td>
	      
	      <td class="text-right"><?=$exp['VerifyTAmt']?></td>
		  <td class="text-left"><?=$exp['VerifyTRemark']?></td>
	      
	      <?php /*if(!isset($_REQUEST['csts']) || $_REQUEST['csts']!='Filled'){?>
	      <td class=""><button class="btn btn-sm btn-primary" onclick="showexpdet('<?=$exp['ExpId']?>')">view</button></td>
		  <?php //} */ ?>
	    </tr>
	    <?php $i++; } ?>
		
		
		<!-- <tr class="totalrow">
	      <th scope="row" colspan="2" class="text-right"><b style="font-size:11px;">Total&nbsp;</b></th>
		  <td class="text-right"><font style="font-size:11px;"><?php $totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c WHERE  `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_REQUEST['emp']."' and c.ClaimId=e.ClaimId  and c.cgId='".$_POST['cgid']."' and e.CrDate='".$_POST['date']."' and e.ClaimStatus='".$_POST['csts']."'"); $clm=mysql_fetch_assoc($totpaid);	echo $clm['paid']; ?></font></td>
		  <th></th>
	    </tr>	 -->
		
	  </tbody>
	</table>
	
	</div>	
</td>

<?php
}elseif($_POST['act']=='monthdettomediator'){

?>
<td style="background-color:#CCCCCC;"></td>
 <td colspan="10">
	<table class="table shadow" style="padding:0px;">
	  <thead  style="background-color:#ffbf80;border:1px solid black;">
	<?php $qq="SELECT h.Fname,h.Sname,h.Lname FROM ".dbemp.".hrm_employee h where h.EmployeeID=".$_REQUEST['emp']; 
	$seleqq=mysql_query($qq); $expn=mysql_fetch_assoc($seleqq); ?>

	  	 
	    <tr>
	      <th colspan="15" scope="col" style="width:30px;text-align:left;color:#FFFFFF;">
		   <font color="#000000">Applied By:</font> &nbsp;&nbsp;<?=$expn['Fname'].' '.$expn['Sname'].' '.$expn['Lname']?>
		  </th>
	    </tr>
	    <tr>
	      <th scope="col" style="width:30px;text-align:center;">Sn</th>
	      <th scope="col" style="width:50px;text-align:center;">Claim<br />ID</th>
	      <!-- <th scope="col" style="width: 150px;">Claim</th> -->
	      <th scope="col" style="width:120px;text-align:center;">Claim Type</th>
	      <!--<th scope="col" style="text-align:center;">Applied By</th>-->
	      <th scope="col" style="text-align:center;">Date</th>
	      <th scope="col" style="text-align:center;">Claim Status</th>
	      <th scope="col" style="text-align:center;">Action</th>
	      <th colspan="4" style="text-align:left;">Remark to Claimer</th>
	    </tr>
	  </thead>
	  <tbody>
	  	
	  	<?php
	  	
  		$stepcond="e.ClaimAtStep IN (1,2) and ((e.ClaimStatus='".$_REQUEST['csts']."' or e.ClaimStatus='Filled'  or e.ClaimStatus='Draft')   or (e.`FilledOkay`=2))";
  	
  		$crcond="1=1";
		/*

		SELECT *,
		CASE WHEN ClaimId != 0  THEN BillDate 
		WHEN ClaimId = 0  THEN CrDate 
		END AS finalDate FROM `y1_expenseclaims` ORDER BY `ClaimStatus`  DESC

		*/
	  	
	  	$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname,

	  	CASE WHEN e.ClaimId != 0  THEN BillDate 
		WHEN e.ClaimId = 0  THEN CrDate 
		END AS finalDate 

	  	 FROM `y".$_SESSION['FYearId']."_expenseclaims`e, claimtype c, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_REQUEST['emp']." and (c.ClaimId=e.ClaimId or e.ClaimId=0) and ".$stepcond." and e.AttachTo=0  group by e.ExpId order by finalDate";

	  	$seleq=mysql_query($q);

		$i=1;
	  	while($exp=mysql_fetch_assoc($seleq)){
	  	?>
	    <tr onclick="showdet('<?=$exp['EmployeeID']?>')">

	      <th scope="row" style="text-align:center;"><?=$i?></th>
	      <td scope="row" style="text-align:center;"><?=$exp['ExpId']?></td>
	      <!-- <td><?=$exp['ExpenseName']?></td> -->
	      <td><?php if($exp['ClaimId']!=0){echo substr($exp['ClaimName'],0,15);}?></td>
	      <?php /*?><td><?=$exp['Fname'].' '.$exp['Sname'].' '.$exp['Lname']?></td><?php */?>
	      <td style="text-align:center;"><?=date("d-m-Y",strtotime($exp['finalDate']))?></td>
	      <td style="text-align:center; width:250px; vertical-align:middle;">
	      	<?php if($exp['ClaimStatus']=='Filled'){?>
			<div id="<?=$exp['ExpId']?>FilledBtn" class="btn btn-sm btn-success font-weight-bold" style="height:22px;width:40px;">F</div>
	      	<?php }	?>


	      	<?php
	      	$c='outline-secondary';
	      	if($exp['FilledOkay']==2){ $c='danger';}
	      	$s=$exp['ClaimStatus'];
	      	if($s=='Draft'){ $c='secondary';}


	      	if($s=='Submitted' || $s=='Draft' || $exp['FilledOkay']==2){
	      	?>
	      	<div id="<?=$exp['ExpId']?>Status" class="btn btn-sm btn-<?=$c?> font-weight-bold" style="height:22px; width:40px;">
	      		<?php 
	      		if($s=='Submitted'){echo 'Up';}elseif($s=='Draft'){echo 'Drft';}elseif($exp['FilledOkay']==2){ echo 'D';}
	      		?>
      		</div>
      		<?php 
      		} 

      		if($c=='danger'){ ?>
	      	<input id="<?=$exp['ExpId']?>Rem" value="<?=$exp['FilOkDenyRemark']?>" placeholder="Remark" readonly>
	      	<?php } ?>
	      	
	      </td>

	      <td style="text-align:center; width:100px;">
	      	<?php 
	      	if ($s=='Submitted') {
		      	?>
		      	<button id="<?=$exp['ExpId']?>btn" class="btn btn-sm btn-primary btnfill" style="height:22px;width:50px;" onclick="showexpdet('<?=$exp['ExpId']?>')">Fill</button>
		      	<?php 
		    }elseif($s=='Filled'){
		     	?>
		     	<button id="<?=$exp['ExpId']?>btn" class="btn btn-sm btn-info btnfill" style="height:22px;width:50px;" onclick="showexpdet('<?=$exp['ExpId']?>')">View</button>
		     	<?php
		    }elseif($s=='Draft'){
		     	?>
		     	<button id="<?=$exp['ExpId']?>btn" class="btn btn-sm btn-primary btnfill" style="height:22px;width:50px;" onclick="showexpdet('<?=$exp['ExpId']?>')">Edit</button>
		     	<?php
		    }
	      	?>
	      	
	      	<?php if($s!='Filled'){ ?>
	      	<button id="<?=$exp['ExpId']?>atbtn" class="btn btn-sm btn-info pull-right"  style="padding:3px 3px !important;align-self: top!important;margin-right:10px;" onclick="attachclaims(this,'<?=$exp['ExpId']?>','<?=$exp['CrBy']?>')" >
					<i class="fa fa-paperclip" aria-hidden="true"></i>
			</button>
			<?php } ?>

	      </td>
	     
	     	<td colspan="4" style="width: 400px;">
	     		
	     		<input id="RemToClaimer<?=$exp['ExpId']?>" value="<?=$exp['DateEntryRemark']?>" placeholder="Remark to Claimer" style="width: 70%;border:0px;" >
	     		<button onclick="submitrem('<?=$exp['ExpId']?>',this)">Submit</button>
	     		<span id="RemSpan<?=$exp['ExpId']?>" style="display: inline-block;"></span>
	     	</td>

	    </tr>
	    <tr id="">
	    	<td colspan="20" style="padding: 0px;margin:0px;">
	    	 <iframe id="clMonClmDet<?=$exp['ExpId']?>" src="" class="clframes" width="100%" style="display:none;height: 400px;"></iframe>
	    	</td>
	    </tr>
	    <?php
	    $i++;	
		}
		
		?>

	  </tbody>
	</table>
	<br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> 
</td>
<?php
}elseif($_POST['act']=='monthdettoverifier'){

?>
<td style="background-color: #CCCCCC;"></td>
<td colspan="10">
	<table class="table shadow">
	  <thead class="thead-dark">
	    <tr>
	      <th scope="col" style="width: 30px;">Sn</th>
	      <th scope="col" style="width: 40px;">Claim ID</th>
	      <th scope="col" style="width: 100px;">Claimer</th>
	      <th scope="col">Claim<br>Type</th>
	      <th scope="col">Upload<br>Date</th>
	      <th scope="col">Bill<br>Date</th>
	      <th scope="row" class="text-center table-active"  style=""><span style="font-size: 10px !important;">Claimed</span><br>Amt</th>

			<th scope="row" class="text-center table-active" ><span style="font-size: 10px !important;">Verified</span><br>Amt</th>
			
			<th scope="row" class="text-center table-active" >Remark</th>

			
	      
	      <th scope="col">Claim Status</th>
	      
	      <th scope="col">Detail</th>
	      <th scope="col">Action</th>

	    </tr>
	  </thead>
	  <tbody>
	  	<tr class="totalrow">
	    	<th scope="row" colspan="6" class="text-right"><b>Total&nbsp;</b></th>
			<td class="text-right">
				<?php
				$totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_REQUEST['emp']."' and ClaimMonth='".$_REQUEST['month']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0");
	  			//$totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' ");
	  			$clm=mysql_fetch_assoc($totpaid);
	  			echo $clm['paid'];
				?>
			</td>
			<td class="text-right">
				<?php
				$totpaid=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_REQUEST['emp']."' and ClaimMonth='".$_REQUEST['month']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and VerifyBy>0"); 
	  			//$totpaid=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' ");
	  			$clm=mysql_fetch_assoc($totpaid);
	  			echo $clm['paid'];
				?>
			</td>
			<th colspan="4">
			</th>
	    </tr>

	  	<?php
	  	if($_REQUEST['csts']=='Filled'){
			$stepcond="e.ClaimAtStep>=3";
	  	}elseif ($_REQUEST['csts']=='Verified') {
	  		$stepcond="e.ClaimAtStep>=4";
	  	}
		
		$crcond="1=1";

	  	$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims`e, claimtype c, ".dbemp.".hrm_employee h where c.ClaimId=e.ClaimId and h.EmployeeID=e.CrBy and e.ClaimMonth='".$_REQUEST['month']."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_REQUEST['emp']." and e.FilledOkay=1 and e.ClaimStatus!='Deactivate' and e.ClaimStatus!='Draft' and ".$stepcond." order by BillDate ASC";

	  	$seleq=mysql_query($q);

		$i=1;
	  	while($exp=mysql_fetch_assoc($seleq)){

	  	?>
	    <tr onclick="showdet('<?=$exp['EmployeeID']?>')">

	      <th scope="row"><?=$i?></th>
	      <td><?=$exp['ExpId']?></td>
	      <td><?php echo $exp['Fname']."&nbsp".$exp['Sname']."&nbsp".$exp['Lname']; ?></td>
	      <td><?=$exp['ClaimName']?></td>   
	      <td><?=date("d-m-Y",strtotime($exp['CrDate']))?></td>
	      <td><?=date("d-m-Y",strtotime($exp['BillDate']))?></td>
			
			<td>
				<input style="width: 70px;" class="form-control text-right" type="text" id="claimedtamt" value="<?=$exp['FilledTAmt']?>" readonly>
			</td>
			<td>				
				<input style="width: 70px;"  class="form-control text-right" type="text" id="<?=$exp['ExpId']?>verifiedtamt" value="<?=$exp['VerifyTAmt']?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$exp['FilledTAmt']?>')">
			</td>
			<td>
				<textarea style="width: 120px;height: 27px;"  class="form-control  text-left" id="<?=$exp['ExpId']?>verifiedtremark" rows="1"><?=$exp['VerifyTRemark']?></textarea>
			</td>
			
	      <td>
	      	<?php
	      	$c='outline-secondary';
	      	$s=$exp['ClaimStatus'];
	      	?>
	      	<div id="<?=$exp['ExpId']?>Status" class="btn btn-sm btn-<?=$c?> font-weight-bold"><?=$s?></div>
	      	
	      </td>
	      <?php if(!isset($_REQUEST['csts']) || $_REQUEST['csts']!='Verified'){?>

	      <td class="">
	      	<?php

	      	$btn='Detail';
			// if($s=='Verified' && ($exp['ClaimAtStep']==3 || $exp['ClaimAtStep']==4)){$c='success'; $btn='View';}
			
			?>
			<button id="<?=$exp['ExpId']?>btn" class="btn btn-sm btn-primary" onclick="showexpdet('<?=$exp['ExpId']?>')"><?=$btn?></button>
	      	
	      </td>
	      <td>
	      	<?php if($exp['ClaimStatus']!='Verified'){ ?>
	      	<span id="<?=$exp['ExpId']?>verifyaction">
	      	<input type="checkbox" onclick="showbtn(this,'<?=$exp['ExpId']?>')">
	      	<button id="<?=$exp['ExpId']?>verifybtn" class="btn btn-sm btn-success pull-right" onclick="verifyClaim('<?=$exp['ExpId']?>')" disabled>
				<i class="fa fa-check" aria-hidden="true" ></i> Verify
			</button>
			</span>
			<?php } ?>
	      </td>
		  <?php } ?>
	     
	    </tr>
	    <?php
	    $i++;
		}
		?>
		<tr class="totalrow">
	    	<th scope="row" colspan="6" class="text-right"><b>Total&nbsp;</b></th>			
			<td class="text-right">
				<?php
				$totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_REQUEST['emp']."' and ClaimMonth='".$_REQUEST['month']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0");
	  			//$totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' ");
	  			$clm=mysql_fetch_assoc($totpaid);
	  			echo $clm['paid'];
				?>
			</td>
			<td class="text-right">
				<?php
				$totpaid=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_REQUEST['emp']."' and ClaimMonth='".$_REQUEST['month']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and VerifyBy>0");
	  			//$totpaid=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' ");
	  			$clm=mysql_fetch_assoc($totpaid);
	  			echo $clm['paid'];
				?>
			</td>
			<th colspan="4">

			</th>

	    </tr>
	  </tbody>
	</table>
</td>

<?php
}elseif($_POST['act']=='monthdettoapprover'){

?>
<!--<td></td>-->
 <td colspan="10">
 <table class="table shadow" style="width:100%;">
 <thead class="thead-dark">
 <tr>
  <th scope="col" style="width:20px;background-color:#008C8C;"><font style="font-size:11px;">Sn</font></th>
  <!--<th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Claim ID</font></th>-->
  <!--<th scope="col" style="width:150px;background-color:#008C8C;"><font style="font-size:11px;">Claim</font></th>-->
  <th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Claim ID</font></th>
  <th scope="col" style="width:100px;background-color:#008C8C;"><font style="font-size:11px;">Claim<br />Type</font></th>
  <th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Upload<br />Date</font></th>
  <th scope="col" style="width:200px;background-color:#008C8C;"><font style="font-size:11px;">Remark</font></th>
  <th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Bill<br />Date</font></th>
  <th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Claimed<br>Amt</font></th>
  
  <th scope="row" class="text-center table-active" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Verified<br>Amt</th>
  <th scope="row" class="text-center table-active" style="width:100px;background-color:#008C8C;"><font style="font-size:11px;">Verifier Remark</th>
  
  <th scope="row" class="text-center table-active" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Approver<br>Amt</th>
  <th scope="row" class="text-center table-active" style="width:150px;background-color:#008C8C;"><font style="font-size:11px;">Approver Remark</th>
  <th scope="col" style="width:40px;background-color:#008C8C;"><font style="font-size:11px;">Claim Sts</th>
  <!--<th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Detail</th>-->
  <th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Action</th>
 </tr>
 </thead>
 <tbody>
<?php
if($_REQUEST['csts']=='Verified'){ $stepcond="ClaimAtStep>=4"; }
elseif ($_REQUEST['csts']=='Approved'){ $stepcond="ClaimAtStep>=5"; }
?> 
 <tr class="totalrow">
  <th scope="row" colspan="6" class="text-right"><b>Total&nbsp;</b></th>
   <td class="text-right"><font style="font-size:11px;"><?php

    $totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."'and e.CrBy=".$_REQUEST['emp']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0"); $clm=mysql_fetch_assoc($totpaid); echo $clm['paid']; ?></font></td>
    
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid2=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and e.CrBy=".$_REQUEST['emp']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and VerifyBy>0"); $clm2=mysql_fetch_assoc($totpaid2); echo $clm2['paid']; ?></font></td>
   
   <td class="text-right">&nbsp;</td>
   
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid3=mysql_query("SELECT SUM(ApprTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and e.CrBy=".$_REQUEST['emp']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and ApprBy>0"); $clm3=mysql_fetch_assoc($totpaid3); echo $clm3['paid']; ?></font></td>
   <th colspan="4"></th>
 </tr>

<?php
if($_REQUEST['csts']=='Verified'){ $stepcond="e.ClaimAtStep>=4"; }
elseif ($_REQUEST['csts']=='Approved'){ $stepcond="e.ClaimAtStep>=5"; }
$crcond="1=1";
$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims`e, claimtype c, ".dbemp.".hrm_employee h where c.ClaimId=e.ClaimId and h.EmployeeID=e.CrBy and e.ClaimMonth='".$_REQUEST['month']."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_REQUEST['emp']." and e.FilledOkay=1 and e.ClaimStatus!='Deactivate' and e.ClaimStatus!='Draft' and ".$stepcond." order by BillDate ASC"; $seleq=mysql_query($q);
$i=1; while($exp=mysql_fetch_assoc($seleq)){
?>
<tr onclick="showdet('<?=$exp['EmployeeID']?>')">
 <th scope="row"><?=$i?></th>
 <?php /*?><td><?=$exp['ExpId']?></td>
 <td><?=$exp['ExpenseName']?></td><?php */?>
  <td class="text-right" style="text-align: center !important;"><font style="font-size:11px;"><?=$exp['ExpId']?></font></td>
 <td><a href="#" onclick="showexpdet('<?=$exp['ExpId']?>')"><font style="font-size:11px;cursor:pointer;"><?=substr($exp['ClaimName'], 0, 12).'..'?></font></a></td>
 <td><a href="#" onclick="showexpdet('<?=$exp['ExpId']?>')"><font style="font-size:11px;cursor:pointer;"><?=date("d/m/y",strtotime($exp['CrDate']))?></font></a></td>
 <td style="text-align:left;"><?php echo strtoupper($exp['Remark']);?></td>
 <td><a href="#" onclick="showexpdet('<?=$exp['ExpId']?>')"><font style="font-size:11px;cursor:pointer;"><?=date("d/m/y",strtotime($exp['BillDate']))?></font></a></td>
 
 
 <td class="text-right"><font style="font-size:11px;"><?=intval($exp['FilledTAmt']) ?></font></td>
 
<?php $famt=intval($exp['FilledTAmt']); $vamt=intval($exp['VerifyTAmt']);
      if($vamt!=$famt){$vcstyle='style="background-color: #ffe6cc;"'; }else{$vcstyle='';} ?>
  <td <?=$vcstyle?> class="text-right"><?=$exp['VerifyTAmt']?></td>
  
  <td class="text-left"><?=$exp['VerifyTRemark']?></td>
  
  <td><input style="width:100%;" class="form-control text-right" type="text" id="<?=$exp['ExpId']?>apprtamt" value="<?=$exp['ApprTAmt']?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$exp['FilledTAmt']?>')"></td>
  <td><input style="width:100%;height:27px;"  class="form-control  text-left" id="<?=$exp['ExpId']?>apprtremark" value="<?=$exp['ApprTRemark']?>" /></td>
  <td><?php if($exp['ClaimStatus']=='Verified'){$c='outline-warning'; $s='P'; }elseif($exp['ClaimStatus']=='Approved'){$c='outline-success'; $s='A'; } ?>
  <div id="<?=$exp['ExpId']?>Status" class="btn btn-sm btn-<?=$c?> font-weight-bold"><?=$s?></div></td>

  <?php if(!isset($_REQUEST['csts']) || $_REQUEST['csts']!='Filled'){?>
  <?php /*?><td class=""><?php $btn='Detail';
// if($s=='Approved' && ($exp['ClaimAtStep']==4 || $exp['ClaimAtStep']==5)){$c='success'; $btn='View';} ?>
  <button id="<?=$exp['ExpId']?>btn" class="btn btn-sm btn-primary" onclick="showexpdet('<?=$exp['ExpId']?>')"><?=$btn?></button><?php ?></td><?php */?>
  
  <td>
<?php if($exp['ClaimStatus']!='Approved'){ ?>
  <span id="appraction">
  <input type="checkbox" onclick="showbtn(this,'<?=$exp['ExpId']?>')">
  <button id="<?=$exp['ExpId']?>apprbtn" class="btn btn-sm btn-success pull-right" onclick="approveClaim('<?=$exp['ExpId']?>')" disabled>
  <i class="fa fa-check" aria-hidden="true" ></i> Approve
  </button>
  </span>
<?php } ?>
 </td>

<?php } ?>

 </tr>
 <?php $i++; } ?>
 <?php
if($_REQUEST['csts']=='Verified'){ $stepcond="ClaimAtStep>=4"; }
elseif ($_REQUEST['csts']=='Approved'){ $stepcond="ClaimAtStep>=5"; }

?> 
 <tr class="totalrow">
  <th scope="row" colspan="6" class="text-right"><b>Total&nbsp;</b></th>
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['month']."' and FilledOkay=1 and `ClaimYearId`='".$_SESSION['FYearId']."'and e.CrBy=".$_REQUEST['emp']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0"); $clm=mysql_fetch_assoc($totpaid); echo $clm['paid']; ?></font></td>
   
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid2=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and e.CrBy=".$_REQUEST['emp']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and VerifyBy>0"); $clm2=mysql_fetch_assoc($totpaid2); echo $clm2['paid']; ?></font></td>
   
   <td class="text-left">&nbsp;</td>
   
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid3=mysql_query("SELECT SUM(ApprTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and e.CrBy=".$_REQUEST['emp']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and ApprBy>0"); $clm3=mysql_fetch_assoc($totpaid3); echo $clm3['paid']; ?></font></td>
   <th colspan="4"></th>
 </tr>
 </tbody>
 </table>
</td>

<?php
}elseif($_POST['act']=='monthdettofinance'){
?>
<td></td>
<td colspan="10" >
	<table class="table shadow table-responsive">
	  <thead class="thead-dark">
	    <tr>
	      <th scope="col" style="width: 30px;">Sn</th>
	      <th scope="col" style="width: 50px;">Claim ID</th>
	      <!--<th scope="col" style="width: 100px;">Claim</th>-->
	      <th scope="col">Claim Type</th>
	      
	      <th scope="col">Upload Date</th>
	      <th scope="col">Bill Date</th>
	      <th scope="row" class="text-center table-active"  style=""><span style="font-size: 10px !important;">Claimed</span><br>Amt</th>

			<th scope="row" class="text-center table-active" ><span style="font-size: 10px !important;">Verified</span><br>Amt</th>
			<!-- <th scope="row" class="text-center table-active" >Remark</th> -->
			<th scope="row" class="text-center table-active" ><span style="font-size: 10px !important;">Approver</span><br>Amt</th>
			<!-- <th scope="row" class="text-center table-active" ><span style="font-size: 10px !important;">Approver</span><br>Remark</th> -->
			<th scope="row" class="text-center table-active" ><span style="font-size: 10px !important;">Finance</span><br>Amt</th>
			<th scope="row" class="text-center table-active" ><span style="font-size: 10px !important;">Finance</span><br>Remark</th>
			<th scope="col">Claim Status</th>
	      <th scope="col">Detail</th>
	      <?php
	      if($_REQUEST['sts']=='Open'){
	      ?>
	      <th scope="col" style="width:120px;">Action</th>
		  <?php } ?>
	    </tr>
	  </thead>
	  <tbody>

<?php
if($_REQUEST['csts']=='Closed'){$stepcond="e.ClaimAtStep>=6";
}else{ $stepcond="e.ClaimAtStep>=5"; }
?> 
 <tr class="totalrow">
  <th scope="row" colspan="5" class="text-right"><b>Total&nbsp;</b></th>
   <td class="text-right"><font style="font-size:11px;"><?php

    $totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['month']."' and FilledOkay=1 and `ClaimYearId`='".$_SESSION['FYearId']."'and e.CrBy=".$_REQUEST['emp']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0"); $clm=mysql_fetch_assoc($totpaid); echo $clm['paid']; ?></font></td>
    
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid2=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and e.CrBy=".$_REQUEST['emp']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and VerifyBy>0"); $clm2=mysql_fetch_assoc($totpaid2); echo $clm2['paid']; ?></font></td>
   
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid3=mysql_query("SELECT SUM(ApprTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and e.CrBy=".$_REQUEST['emp']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and ApprBy>0"); $clm3=mysql_fetch_assoc($totpaid3); echo $clm3['paid']; ?></font></td>
   
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid4=mysql_query("SELECT SUM(FinancedTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and e.CrBy=".$_REQUEST['emp']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FinancedBy>0"); $clm4=mysql_fetch_assoc($totpaid4); //echo $clm4['paid']; ?></font>
   <input type="text" style="font-family:Times New Roman; font-size:12px; height:20px; width:95%; text-align:right;" id="FinTAmt" value="<?=$clm4['paid']?>" readonly/>
   </td>
   
   <th colspan="4"></th>
 </tr>

	  	
	  	<?php
	  	
	  	
	  	$crcond="1=1";

	  	$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims`e, claimtype c, ".dbemp.".hrm_employee h where c.ClaimId=e.ClaimId and h.EmployeeID=e.CrBy and e.ClaimMonth='".$_REQUEST['month']."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_REQUEST['emp']." and e.FilledOkay=1 and e.ClaimStatus!='Deactivate' and e.ClaimStatus!='Draft' and ".$stepcond." order by BillDate ASC";

	  	$seleq=mysql_query($q);

		$i=1;
	  	while($exp=mysql_fetch_assoc($seleq)){
	  	?>
	    <tr onclick="showdet('<?=$exp['EmployeeID']?>')">

	      <th scope="row"><?=$i?></th>
	      <td><?=$exp['ExpId']?></td>
	      <!-- <td><?=$exp['ExpenseName']?></td> -->
	      <td><?=$exp['ClaimName']?></td>
	      
	      <td><?=date("d-m-Y",strtotime($exp['CrDate']))?></td>
	      <td><?=date("d-m-Y",strtotime($exp['BillDate']))?></td>
	      <td>
				<?=$exp['FilledTAmt']?>
			</td>
			<td>
				<?=$exp['VerifyTAmt']?>
			</td>
			<!-- 
			<td>
				<textarea style="width: 120px;height: 27px;"  class="form-control  text-left" id="<?=$exp['ExpId']?>verifiedtremark" rows="1" readonly><?=$exp['VerifyTRemark']?></textarea>
			</td> 
			-->
			<td>
				<?=$exp['ApprTAmt']?>
			</td>
			<!-- 
			<td>
				<textarea readonly style="width: 120px;height: 27px;"  class="form-control  text-left" id="<?=$exp['ExpId']?>apprtremark" rows="1"><?=$exp['ApprTRemark']?></textarea>
			</td> 
			-->
			<td>
				<input style="width: 50px;"  class="form-control text-right" type="text" id="<?=$exp['ExpId']?>financetamt" value="<?=$exp['FinancedTAmt']?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$exp['FinancedTAmt']?>')">
			</td>
			<td>
				<textarea style="width: 100px;height: 27px;"  class="form-control  text-left" id="<?=$exp['ExpId']?>financetremark" rows="1"><?=$exp['FinancedTRemark']?></textarea>
			</td>
	      <td>
	      	<?php
	      	$c=''; //$c='outline-secondary'; 
	      	if($exp['ClaimStatus']=='Financed'){$s='Ok';}
			elseif($exp['ClaimStatus']=='Rej_Fin'){$s='Rejected';}
			else { $s=''; }
	      	?>
	      	<div id="<?=$exp['ExpId']?>Status" class="btn btn-sm btn-<?=$c?> font-weight-bold"><?=$s?></div>	
	      </td>
	      
	      <td class="">
	      	<?php
		    $btn='Detail';
			//if(($s=='Financed' && $exp['ClaimAtStep']==5) || $exp['ClaimAtStep']==6){$c='success'; $btn='View';}
	        ?>
	        <button id="<?=$exp['ExpId']?>btn" class="btn btn-sm btn-primary" onclick="showexpdet('<?=$exp['ExpId']?>')"><?=$btn?></button>
	      </td>
	      <?php
	      if($_REQUEST['sts']=='Open' AND $exp['ClaimStatus']!='Financed' AND $exp['ClaimStatus']!='Rej_Fin' AND $_SESSION['EmpRole']!='P'){
	      ?>
	      <td style="text-align:center;">
	      	<?php //if($exp['ClaimStatus']!='Financed'){ ?>
      		<span id="financection<?=$exp['ExpId']?>" style="text-align:center;">
	      	<?php /*?><input type="checkbox" onclick="showbtn(this,'<?=$exp['ExpId']?>')"><?php */?>
	      	<button id="<?=$exp['ExpId']?>finbtn" class="btn btn-sm btn-success pull-center" onclick="financeClaim('A','<?=$exp['ExpId']?>')"><!--<i class="fa fa-check" aria-hidden="true" ></i>--> Approval </button>
			&nbsp;&nbsp;
			<button id="<?=$exp['ExpId']?>finbtn" class="btn btn-sm btn-danger pull-center" onclick="financeClaim('R','<?=$exp['ExpId']?>')">Reject </button>
			</span>
			<?php //} ?>
	      </td>
		  <?php } ?>
	     
	    </tr>
	    <?php
	    $i++;
		}
		?>
		
		<?php
if($_REQUEST['csts']=='Closed'){$stepcond="e.ClaimAtStep>=6";
}else{ $stepcond="e.ClaimAtStep>=5"; }
?> 
 <tr class="totalrow">
  <th scope="row" colspan="5" class="text-right"><b>Total&nbsp;</b></th>
   <td class="text-right"><font style="font-size:11px;"><?php

    $totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['month']."' and FilledOkay=1 and `ClaimYearId`='".$_SESSION['FYearId']."'and e.CrBy=".$_REQUEST['emp']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0"); $clm=mysql_fetch_assoc($totpaid); echo $clm['paid']; ?></font></td>
    
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid2=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and e.CrBy=".$_REQUEST['emp']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and VerifyBy>0"); $clm2=mysql_fetch_assoc($totpaid2); echo $clm2['paid']; ?></font></td>
   
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid3=mysql_query("SELECT SUM(ApprTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and e.CrBy=".$_REQUEST['emp']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and ApprBy>0"); $clm3=mysql_fetch_assoc($totpaid3); echo $clm3['paid']; ?></font></td>
   
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid4=mysql_query("SELECT SUM(FinancedTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and e.CrBy=".$_REQUEST['emp']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FinancedBy>0"); $clm4=mysql_fetch_assoc($totpaid4); //echo $clm4['paid']; ?></font>
   <input type="text" style="font-family:Times New Roman; font-size:12px; height:20px; width:95%; text-align:right;" id="FinPAmt" value="<?=$clm4['paid']?>" readonly/>
   </td>
   
   <th colspan="4"></th>
 </tr>
		
	  </tbody>
	</table>
</td>
<?php

}elseif($_POST['act']=='submitrem'){
	
	$up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET DateEntryRemark='".$_POST['remark']."' where ExpId='".$_POST['expid']."'");

	if($up){
		echo 'updated';
	}

}
?>



