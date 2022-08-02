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

if($_POST['act']=='monthdettoclaimer'){
	?>

<!--<td style="background-color: #CCCCCC;"></td>-->
<td colspan="6" style="text-align:right;">
	
<!-- style="width:320px;overflow:auto !important;" -->
	<div id="clcldetdiv">
	<table class="table shadow" style="width:100%;" align="center">
	  <thead class="thead-dark">
	    <tr>
<th scope="col" style="width:30px;background-color:#008C8C;"><font style="font-size:11px;">Sn</font></th>
<!-- <th scope="col" style="width: 50px;background-color:#008C8C;"><font style="font-size:11px;">Claim ID</font></th> -->
<!-- <th scope="col" style="width: 150px;background-color:#008C8C;"><font style="font-size:11px;">Claim</font></th> -->
<th scope="col" style="width:100px;background-color:#008C8C;"><font style="font-size:11px;">Claim<br />Type</font></th>
<th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Applied<br />Date</font></th>
<th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Claimed<br>Amt</font></th>
<?php $msts=mysql_query("SELECT Month FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `Month`='".$_REQUEST['month']."' and `YearId`='".$_SESSION['FYearId']."' and `EmployeeID`='".$_SESSION['EmployeeID']."' and Status='Closed'");
if(mysql_num_rows($msts)>0){ ?>
<!--<th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Verified<br>Amt</font></th>
<th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Approved<br>Amt</font></th>-->
<th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Paid<br>Amt</font></th>
<?php } 
/*else{ ?><th scope="row" class="text-center table-active" colspan="3" style="background-color:#008C8C;"></th><?php } */	?>
<th scope="col" style="width:110px;background-color:#008C8C;"><font style="font-size:11px;">Claim Status</th>
<?php /*if(!isset($_REQUEST['csts']) || $_REQUEST['csts']!='Filled'){?>
<th scope="col" style="width:30px;background-color:#008C8C;"><font style="font-size:11px;">View</font></th>
<?php //} */ ?>
	    </tr>
	  </thead>
	  <tbody>
	  	<tr class="totalrow">
	      <th scope="row" colspan="3" class="text-right"><b style="font-size:11px;">Total&nbsp;</b></th>
		  <td class="text-right"><font style="font-size:11px;"><?php $totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'"); $clm=mysql_fetch_assoc($totpaid);	echo $clm['paid']; ?></font></td>

<?php $msts=mysql_query("SELECT Month FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `Month`='".$_REQUEST['month']."' and `YearId`='".$_SESSION['FYearId']."' and `EmployeeID`='".$_SESSION['EmployeeID']."' and Status='Closed'");
if(mysql_num_rows($msts)>0){ ?>

<?php /*?><?php $tot2paid=mysql_query("SELECT SUM(VerifyTAmt) as paid2 FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'"); $clm2=mysql_fetch_assoc($tot2paid);?><td class="text-right"><font style="font-size:11px;"><?php echo $clm2['paid2']; ?></font></td>	

<?php $tot3paid=mysql_query("SELECT SUM(ApprTAmt) as paid3 FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'"); $clm3=mysql_fetch_assoc($tot3paid);
?><td class="text-right"><font style="font-size:11px;"><?php echo $clm3['paid3']; ?></font></td><?php */?>		

<?php $tot4paid=mysql_query("SELECT SUM(FinancedTAmt) as paid4 FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'"); $clm4=mysql_fetch_assoc($tot4paid); ?><td class="text-right"><font style="font-size:11px;"><?php echo $clm4['paid4']; ?></font></td>	
			
<?php } ?>
		   <th colspan="2"></th>
	      </tr>	
	  	
<?php $stepcond="1=1";
	  $q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims`e, claimtype c, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and e.ClaimMonth='".$_REQUEST['month']."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_REQUEST['emp']." and (c.ClaimId=e.ClaimId or e.ClaimId=0) and ".$stepcond." group by e.ExpId"; $seleq=mysql_query($q);
      $i=1; while($exp=mysql_fetch_assoc($seleq)){ ?>
	      <tr onclick="showdet('<?=$exp['EmployeeID']?>')" >
	       <th scope="row"><font style="font-size:11px;"><?=$i?></font></th>
	       <?php /* <td><?=$exp['ExpId']?></td> 
	                <td><?=$exp['ExpenseName']?></td> */ ?>
	       <td><a href="#" onclick="showexpdet('<?=$exp['ExpId']?>')"><font style="font-size:11px;"><?php if($exp['ClaimId']!=0){echo substr($exp['ClaimName'], 0, 12).'..';}?></font></a></td>
	       <td><a href="#" onclick="showexpdet('<?=$exp['ExpId']?>')"><font style="font-size:11px;"><?=date("d/m/y",strtotime($exp['CrDate']))?></font></a></td>
		   <td class="text-right"><font style="font-size:11px;"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed"))){ $famt=intval($exp['FilledTAmt']);	echo $famt;	} ?></font></td>
			
<?php $msts=mysql_query("SELECT Month FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `Month`='".$_REQUEST['month']."' and `YearId`='".$_SESSION['FYearId']."' and `EmployeeID`='".$_SESSION['EmployeeID']."' and Status='Closed'");
if(mysql_num_rows($msts)>0){ ?>
<?php /* $vamt=intval($exp['VerifyTAmt']); if($vamt!=$famt && $exp['FilledTAmt']!=0){$vcstyle='style="background-color: #ffe6cc;"'; }else{$vcstyle='';} ?>
           <td class="text-right" <?=$vcstyle?>><font style="font-size:11px;"><?php	if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed"))){echo $vamt; } ?></font></td>

<?php $aamt=intval($exp['ApprTAmt']); if($aamt!=$vamt){$acstyle='style="background-color: #ffe6cc;"'; }else{$acstyle='';} ?>
           <td class="text-right"  <?=$acstyle?>><font style="font-size:11px;"><?php if(in_array($exp['ClaimStatus'], array("Approved","Financed"))){echo $aamt; } ?></font></td><?php */ ?>

<?php $aamt=intval($exp['FinancedTAmt']); 
      if($famt!=$aamt){$fcstyle='style="background-color: #ffe6cc;"'; }else{$fcstyle='';} ?>
		   <td class="text-right" <?=$fcstyle?>><font style="font-size:11px;">
		   <?php if(in_array($exp['ClaimStatus'], array("Financed"))){ echo $aamt; } ?></font></td>

<?php } ?>
			
	      <td><?php //$c='outline-secondary'; //$s=$exp['ClaimStatus'];
			if($exp['ClaimStatus']=='Submitted'){$s='Pending'; $clss='btn btn-sm btn-outline-warning font-weight-bold';}
			elseif($exp['ClaimStatus']=='Filled'){$s='Filled'; $clss='btn btn-sm btn-outline-success font-weight-bold';} ?>
	      	<div class="<?=$clss?>"><font style="font-size:10px;"><?=$s?></font></div>
	       
			<span id="okspanarea<?=$exp['ExpId']?>">
			 <?php if($exp['ClaimStatus']=="Filled" && $exp['FilledOkay']==0){ ?>
			 <input type="checkbox" onclick="okcheck(this,'<?=$exp['ExpId']?>')">
			 <button id="okbtn<?=$exp['ExpId']?>" class="btn btn-sm btn-success" onclick="expfillok('<?=$exp['ExpId']?>')" disabled><font style="font-size:10px;">Ok</font></button><?php }elseif($exp['ClaimStatus']=="Filled" && $exp['FilledOkay']==1){ ?><div class="btn btn-sm btn-success"><font style="font-size:10px;">Okay</font></div> <?php } ?>
			</span> 
			       	
	      </td>
	      <?php /*if(!isset($_REQUEST['csts']) || $_REQUEST['csts']!='Filled'){?>
	      <td class=""><button class="btn btn-sm btn-primary" onclick="showexpdet('<?=$exp['ExpId']?>')">view</button></td>
		  <?php //} */ ?>
	    </tr>
	    <?php $i++; } ?>
		
		
		<tr class="totalrow">
	      <th scope="row" colspan="3" class="text-right"><b style="font-size:11px;">Total&nbsp;</b></th>
		  <td class="text-right"><font style="font-size:11px;"><?php $totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'"); $clm=mysql_fetch_assoc($totpaid);	echo $clm['paid']; ?></font></td>

<?php $msts=mysql_query("SELECT Month FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `Month`='".$_REQUEST['month']."' and `YearId`='".$_SESSION['FYearId']."' and `EmployeeID`='".$_SESSION['EmployeeID']."' and Status='Closed'");
if(mysql_num_rows($msts)>0){ ?>

<?php /*?><?php $tot2paid=mysql_query("SELECT SUM(VerifyTAmt) as paid2 FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'"); $clm2=mysql_fetch_assoc($tot2paid);?><td class="text-right"><font style="font-size:11px;"><?php echo $clm2['paid2']; ?></font></td>	

<?php $tot3paid=mysql_query("SELECT SUM(ApprTAmt) as paid3 FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'"); $clm3=mysql_fetch_assoc($tot3paid);
?><td class="text-right"><font style="font-size:11px;"><?php echo $clm3['paid3']; ?></font></td><?php */?>		

<?php $tot4paid=mysql_query("SELECT SUM(FinancedTAmt) as paid4 FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'"); $clm4=mysql_fetch_assoc($tot4paid); ?><td class="text-right"><font style="font-size:11px;"><?php echo $clm4['paid4']; ?></font></td>	
			
<?php } ?>
		   <th colspan="2"></th>
	      </tr>	
		
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
	      <th scope="col" style="width:50px;text-align:center;">Claim ID</th>
	      <!-- <th scope="col" style="width: 150px;">Claim</th> -->
	      <th scope="col" style="text-align:center;">Claim Type</th>
	      <!--<th scope="col" style="text-align:center;">Applied By</th>-->
	      <th scope="col" style="text-align:center;">Applied Date</th>
	      <th scope="col" style="text-align:center;">Claim Status</th>
	      <th scope="col">Action</th>
	      <th colspan="4" style="text-align:center;"></th>
	    </tr>
	  </thead>
	  <tbody>
	  	
	  	<?php
	  	
  		$stepcond="e.ClaimAtStep IN (1,2) and e.ClaimStatus='".$_REQUEST['csts']."'";
  	
  		$crcond="1=1";
	  	

	  	$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims`e, claimtype c, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and e.ClaimMonth='".$_REQUEST['month']."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_REQUEST['emp']." and (c.ClaimId=e.ClaimId or e.ClaimId=0) and ".$stepcond." group by e.ExpId";


	  	

	  	$seleq=mysql_query($q);

	  	

		$i=1;
	  	while($exp=mysql_fetch_assoc($seleq)){
	  	?>
	    <tr onclick="showdet('<?=$exp['EmployeeID']?>')">

	      <th scope="row" style="text-align:center;"><?=$i?></th>
	      <td scope="row" style="text-align:center;"><?=$exp['ExpId']?></td>
	      <!-- <td><?=$exp['ExpenseName']?></td> -->
	      <td><?php if($exp['ClaimId']!=0){echo $exp['ClaimName'];}?></td>
	      <?php /*?><td><?=$exp['Fname'].' '.$exp['Sname'].' '.$exp['Lname']?></td><?php */?>
	      <td style="text-align:center;"><?=date("d-m-Y",strtotime($exp['CrDate']))?></td>
	      <td style="text-align:center;">
	      	<?php
	      	$c='outline-secondary';
	      	$s=$exp['ClaimStatus'];
	      	?>
	      	<div id="<?=$exp['ExpId']?>Status" class="btn btn-sm btn-<?=$c?> font-weight-bold" style="height:22px;">
	      		<?php 
	      		if($s=='Submitted'){echo 'Uploaded';} 
	      		?>
      		</div>
	      	
	      </td>

	      <td style="text-align:center;">
	      	<button id="<?=$exp['ExpId']?>btn" class="btn btn-sm btn-primary btnfill" style="height:22px;width:60px;" onclick="showexpdet('<?=$exp['ExpId']?>')">Fill</button>
	      </td>
	     
	     	<td colspan="4" style="width: 400px;"></td>
 
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
	      <th scope="col" style="width: 100px;">Claim</th>
	      <th scope="col">Claim<br>Type</th>
	      <th scope="col">Applied<br>Date</th>
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
	    	<th scope="row" colspan="5" class="text-right"><b>Total&nbsp;</b></th>
			<td class="text-right">
				<?php
	  			$totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' ");
	  			$clm=mysql_fetch_assoc($totpaid);
	  			echo $clm['paid'];
				?>
			</td>
			<td class="text-right">
				<?php
	  			$totpaid=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' ");
	  			$clm=mysql_fetch_assoc($totpaid);
	  			echo $clm['paid'];
				?>
			</td>
			<th colspan="4">
			</th>
	    </tr>

	  	<?php
	  	if($_REQUEST['csts']=='Filled'){
			$stepcond="e.ClaimAtStep=3";
	  	}elseif ($_REQUEST['csts']=='Verified') {
	  		$stepcond="e.ClaimAtStep=4";
	  	}
		
		$crcond="1=1";

	  	$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims`e, claimtype c, ".dbemp.".hrm_employee h where c.ClaimId=e.ClaimId and h.EmployeeID=e.CrBy and e.ClaimMonth='".$_REQUEST['month']."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_REQUEST['emp']." and ".$stepcond;

	  	$seleq=mysql_query($q);

		$i=1;
	  	while($exp=mysql_fetch_assoc($seleq)){
	  	?>
	    <tr onclick="showdet('<?=$exp['EmployeeID']?>')">

	      <th scope="row"><?=$i?></th>
	      <td><?=$exp['ExpId']?></td>
	      <td><?=$exp['ExpenseName']?></td>
	      <td><?=$exp['ClaimName']?></td>
	      
	      <td><?=date("d-m-Y",strtotime($exp['CrDate']))?></td>
			
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
	    	<th scope="row" colspan="5" class="text-right"><b>Total&nbsp;</b></th>			
			<td class="text-right">
				<?php
	  			$totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' ");
	  			$clm=mysql_fetch_assoc($totpaid);
	  			echo $clm['paid'];
				?>
			</td>
			<td class="text-right">
				<?php
	  			$totpaid=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' ");
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
 <table class="table shadow table-responsive" style="width:100%;">
 <thead class="thead-dark">
 <tr>
  <th scope="col" style="width:20px;background-color:#008C8C;"><font style="font-size:11px;">Sn</font></th>
  <!--<th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Claim ID</font></th>-->
  <!--<th scope="col" style="width:150px;background-color:#008C8C;"><font style="font-size:11px;">Claim</font></th>-->
  <th scope="col" style="width:100px;background-color:#008C8C;"><font style="font-size:11px;">Claim<br />Type</font></th>
  <th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Applied<br />Date</font></th>
  <th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Claimed<br>Amt</font></th>
  
  <th scope="row" class="text-center table-active" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Verified<br>Amt</th>
  <th scope="row" class="text-center table-active" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Approver<br>Amt</th>
  <th scope="row" class="text-center table-active" style="width:150px;background-color:#008C8C;"><font style="font-size:11px;">Approver Remark</th>
  <th scope="col" style="width:40px;background-color:#008C8C;"><font style="font-size:11px;">Claim Sts</th>
  <!--<th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Detail</th>-->
  <th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Approve</th>
 </tr>
 </thead>
 <tbody>
<?php
if($_REQUEST['csts']=='Verified'){ $stepcond="ClaimAtStep=4"; }
elseif ($_REQUEST['csts']=='Approved'){ $stepcond="ClaimAtStep=5"; }
?> 
 <tr class="totalrow">
  <th scope="row" colspan="3" class="text-right"><b>Total&nbsp;</b></th>
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."'and e.CrBy=".$_REQUEST['emp']." and ".$stepcond); $clm=mysql_fetch_assoc($totpaid); echo $clm['paid']; ?></font></td>
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid2=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and e.CrBy=".$_REQUEST['emp']." and ".$stepcond); $clm2=mysql_fetch_assoc($totpaid2); echo $clm2['paid']; ?></font></td>
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid3=mysql_query("SELECT SUM(ApprTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and e.CrBy=".$_REQUEST['emp']." and ".$stepcond); $clm3=mysql_fetch_assoc($totpaid3); echo $clm3['paid']; ?></font></td>
   <th colspan="4"></th>
 </tr>

<?php
if($_REQUEST['csts']=='Verified'){ $stepcond="e.ClaimAtStep=4"; }
elseif ($_REQUEST['csts']=='Approved'){ $stepcond="e.ClaimAtStep=5"; }
$crcond="1=1";
$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims`e, claimtype c, ".dbemp.".hrm_employee h where c.ClaimId=e.ClaimId and h.EmployeeID=e.CrBy and e.ClaimMonth='".$_REQUEST['month']."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_REQUEST['emp']." and ".$stepcond; $seleq=mysql_query($q);
$i=1; while($exp=mysql_fetch_assoc($seleq)){
?>
<tr onclick="showdet('<?=$exp['EmployeeID']?>')">
 <th scope="row"><?=$i?></th>
 <?php /*?><td><?=$exp['ExpId']?></td>
 <td><?=$exp['ExpenseName']?></td><?php */?>
 <td><a href="#" onclick="showexpdet('<?=$exp['ExpId']?>')"><font style="font-size:11px;cursor:pointer;"><?=substr($exp['ClaimName'], 0, 12).'..'?></font></a></td>
 <td><a href="#" onclick="showexpdet('<?=$exp['ExpId']?>')"><font style="font-size:11px;cursor:pointer;"><?=date("d/m/y",strtotime($exp['CrDate']))?></font></a></td>
 <td class="text-right"><font style="font-size:11px;"><?=intval($exp['FilledTAmt']) ?></font></td>
 
<?php $famt=intval($exp['FilledTAmt']); $vamt=intval($exp['VerifyTAmt']);
      if($vamt!=$famt){$vcstyle='style="background-color: #ffe6cc;"'; }else{$vcstyle='';} ?>
  <td <?=$vcstyle?> class="text-right"><?=$exp['VerifyTAmt']?></td>
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
  <button id="<?=$exp['ExpId']?>apprbtn" class="btn btn-sm btn-success pull-right" onclick="approveClaim('<?=$exp['ExpId']?>')" disabled><i class="fa fa-check" aria-hidden="true" ></i>Yes
  </button>
  </span>
<?php } ?>
 </td>

<?php } ?>

 </tr>
 <?php $i++; } ?>
 <?php
if($_REQUEST['csts']=='Verified'){ $stepcond="ClaimAtStep=4"; }
elseif ($_REQUEST['csts']=='Approved'){ $stepcond="ClaimAtStep=5"; }
?> 
 <tr class="totalrow">
  <th scope="row" colspan="3" class="text-right"><b>Total&nbsp;</b></th>
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."'and e.CrBy=".$_REQUEST['emp']." and ".$stepcond); $clm=mysql_fetch_assoc($totpaid); echo $clm['paid']; ?></font></td>
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid2=mysql_query("SELECT SUM(VerifyTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and e.CrBy=".$_REQUEST['emp']." and ".$stepcond); $clm2=mysql_fetch_assoc($totpaid2); echo $clm2['paid']; ?></font></td>
   <td class="text-right"><font style="font-size:11px;"><?php $totpaid3=mysql_query("SELECT SUM(ApprTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$_REQUEST['month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and e.CrBy=".$_REQUEST['emp']." and ".$stepcond); $clm3=mysql_fetch_assoc($totpaid3); echo $clm3['paid']; ?></font></td>
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
	      <th scope="col" style="width: 100px;">Claim</th>
	      <th scope="col">Claim Type</th>
	      
	      <th scope="col">Applied Date</th>
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
	      <th scope="col">Action</th>
		  <?php } ?>
	    </tr>
	  </thead>
	  <tbody>
	  	
	  	<?php
	  	if($_REQUEST['csts']=='Closed'){
	  		$stepcond="e.ClaimAtStep=6";
	  	}else{
	  		$stepcond="e.ClaimAtStep=5";
	  	}
	  	
	  	$crcond="1=1";

	  	$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims`e, claimtype c, ".dbemp.".hrm_employee h where c.ClaimId=e.ClaimId and h.EmployeeID=e.CrBy and e.ClaimMonth='".$_REQUEST['month']."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$_REQUEST['emp']." and ".$stepcond;

	  	$seleq=mysql_query($q);

		$i=1;
	  	while($exp=mysql_fetch_assoc($seleq)){
	  	?>
	    <tr onclick="showdet('<?=$exp['EmployeeID']?>')">

	      <th scope="row"><?=$i?></th>
	      <td><?=$exp['ExpId']?></td>
	      <td><?=$exp['ExpenseName']?></td>
	      <td><?=$exp['ClaimName']?></td>
	      
	      <td><?=date("d-m-Y",strtotime($exp['CrDate']))?></td>
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
	      	$c='outline-secondary';
	      	$s=$exp['ClaimStatus'];
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
	      if($_REQUEST['sts']=='Open'){
	      ?>
	      <td>
	      	<?php if($exp['ClaimStatus']!='Financed'){ ?>
      		<span id="financection">
	      	<input type="checkbox" onclick="showbtn(this,'<?=$exp['ExpId']?>')">
	      	<button id="<?=$exp['ExpId']?>finbtn" class="btn btn-sm btn-success pull-right" onclick="financeClaim('<?=$exp['ExpId']?>')" disabled>
				<i class="fa fa-check" aria-hidden="true" ></i> Finance
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
	  </tbody>
	</table>
</td>
<?php

}elseif($_POST['act']=='showClaimerMonth'){
?>


<!--<div style="width: 22px;height:100%;display: inline-block;background-color: #999999;padding: 0px;">
	
</div>-->

<table class="estable table pull-right" style="width: 120px;background-color: white;text-align: left;">
	
	<tbody>
		<?php

		$m=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal`  WHERE EmployeeID=".$_POST['emp']." and Status='Open'  group by Month");

		while($mlist=mysql_fetch_assoc($m)){

			$emp=mysql_query("SELECT *,COUNT(ExpId) as count FROM `y".$_SESSION['FYearId']."_expenseclaims` ec, ".dbemp.".`hrm_sales_reporting_level` rl WHERE ec.`ClaimMonth`='".$mlist['Month']."' and ec.`ClaimYearId`='".$_SESSION['FYearId']."' and ec.`ClaimAtStep`=2  and ec.ClaimStatus='Submitted' and ec.CrBy=rl.EmployeeID  and  ec.CrBy=".$_POST['emp']." group by ec.CrBy  order by ec.ExpId asc");
			

			while($empl=mysql_fetch_assoc($emp)){
				
				$enum=mysql_num_rows($emp);

				if($enum > 0){
			  	?>
			  	<tr>
			  		
			  		<td style="padding: 0px !important; padding:2px;">
			  			<a href="#" onclick="showmonthdet('<?=$mlist['Month']?>','Open','<?=$_POST['emp']?>','Submitted',this)" class="clMonthss" style="display: block;margin:0px;padding:0px;">
			  				<?=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y')));?>
		  				</a>
		  			</td>
			  		
			  	</tr>
			  	
			  	<?php
			  	}
		  	}
		}
		?>
	</tbody>

</table>

<?php
}
?>



