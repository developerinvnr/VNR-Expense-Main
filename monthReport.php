<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>

<style type="text/css">
	body{
		padding: 20px;
	}
</style>

<?php
error_reporting(0);
session_start();
include 'config.php';


$crby=$_REQUEST['crby']; 
$month=$_REQUEST['month'];


function getUser($u){
	include "config.php";
	$u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$u, $con2);
	$un=mysql_fetch_assoc($u);
	return $un['Fname'].' '.$un['Sname'].' '.$un['Lname'];
}

?>
<h5 class="text-muted">
	&nbsp;<?php echo getUser($crby).' - '.date('F', mktime(0,0,0,$month, 1, date('Y')));?>
</h5>

<table id="TrvTable" class="mRepTbl table table-bordered table-striped">
	<thead>
		<tr>
			<th colspan="20" style="text-align: left;background-color:#006666;color: white; padding:!important 30px; ">
				&nbsp;Expense Type - Travel
			</th>
		</tr>
		
		<tr>
			<th style="min-width:90px;">Date</th>
			<th>LDG</th>
			<th>24W</th>
			<th>DHQ</th>
			<th>DOS</th>
			<th>RBS</th>
			<th>CON</th>
			<th>HVC</th>
			<th>PFX</th>
			<th>PSC</th>
			<th>MLS</th>
			<th>MSC</th>
			<th>VFL</th>
			<th>VMC</th>
			<th>Total</th>
		
		</tr>
	</thead>

	<tbody>
		<?php
		$gtot=0;
		
		$q="SELECT e.* FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype ct where ct.ClaimId=e.ClaimId and ct.cgId=1 and e.ClaimMonth='".$month."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$crby."  and e.AttachTo=0 and e.ClaimStatus!='Deactivate' and e.ClaimStatus!='Draft' and e.ClaimStatus='Filled' and e.FilledOkay!=2 group by e.BillDate order by e.BillDate"; 

        // print_r($q);
		$qd=mysql_query($q);
		while($selq=mysql_fetch_assoc($qd)){

			$claimamts= array(); $tot=0; 

		    $sc=mysql_query("SELECT e.ClaimId,SUM(e.FilledTAmt) as FillAmt FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype ct  where e.BillDate='".$selq['BillDate']."' and ct.ClaimId=e.ClaimId and ct.cgId=1 and ( e.ClaimStatus='Filled' and e.ClaimStatus!='Deactivate' and e.ClaimStatus!='Draft') and e.CrBy=".$crby );
			while($scd=mysql_fetch_assoc($sc)){

				$claimamts[$scd['ClaimId']]=$scd['FillAmt'];
				$tot+=$scd['FillAmt'];

			}
			$gtot+=$tot;
			?>
			<tr>
				<th><?=date("d-m-Y",strtotime($selq['BillDate']))?></th>
				<td><?=isset($claimamts[1]) ? $claimamts[1] : '';?></td>
				<td><?=isset($claimamts[7]) ? $claimamts[7] : '';?></td>
				<td><?=isset($claimamts[14]) ? $claimamts[14] : '';?></td>
				<td><?=isset($claimamts[15]) ? $claimamts[15] : '';?></td>
				<td><?=isset($claimamts[2]) ? $claimamts[2] : '';?></td>
				<td><?=isset($claimamts[3]) ? $claimamts[3] : '';?></td>
				<td><?=isset($claimamts[4]) ? $claimamts[4] : '';?></td>
				<td><?=isset($claimamts[5]) ? $claimamts[5] : '';?></td>
				<td><?=isset($claimamts[6]) ? $claimamts[6] : '';?></td>
				<td><?=isset($claimamts[17]) ? $claimamts[17] : '';?></td>
				<td><?=isset($claimamts[16]) ? $claimamts[16] : '';?></td>
				<td><?=isset($claimamts[9]) ? $claimamts[9] : '';?></td>
				<td><?=isset($claimamts[8]) ? $claimamts[8] : '';?></td>
				<td style="text-align: right;"><?=$tot?>&nbsp;</td>		
			</tr>
			<?php	
		}
		?>

		<tr>
			<th colspan="14" style="text-align: right;">Grand Total&nbsp;</th>
			<th style="text-align: right;"><?=$gtot?>&nbsp;</th>
		</tr>
	</tbody>


</table>


<hr>


<table id="TrvTable" class="mRepTbl table table-bordered table-striped" style="width: 50%;" >
	<thead>
		<tr>
			<th colspan="20" style="text-align: left;background-color:#006666;color: white; padding:!important 30px; ">
				&nbsp;Expense Type - FD/FV
			</th>
		</tr>
		
		<tr>
			<th style="min-width:90px;">Date</th>
			<th>HVC</th>
			<th>AVT</th>
			<th>SNK</th>
			<th>OTH</th>
			<th>FMS</th>
			<th>DTP</th>

			<th>Total</th>
		
		</tr>
	</thead>

	<tbody>
		<?php
		$gtot=0;
		$cgid=4; //giving here value 4 because the group id of FD/FV is 4
		$q="SELECT e.* FROM `y".$_SESSION['FYearId']."_expenseclaims` e where e.ClaimMonth='".$month."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$crby."  and e.AttachTo=0 and e.ClaimStatus!='Deactivate' and e.ClaimId=11 group by e.CrDate order by e.CrDate"; 
		$qd=mysql_query($q);
		while($selq=mysql_fetch_assoc($qd)){

			$tot=0; 

			$da=mysql_query("SELECT fms,dtp FROM  y".$_SESSION['FYearId']."_g".$cgid."_expensefilldata  where ExpId='".$selq['ExpId']."'");
			$dat=mysql_fetch_assoc($da);

			$sc=mysql_query("SELECT Title,Amount FROM `y".$_SESSION['FYearId']."_expenseclaimsdetails`  where ExpId='".$selq['ExpId']."'");
			while($scd=mysql_fetch_assoc($sc)){

				if(strchr($scd['Title'],"HVC")){$HVC=$scd['Amount']; }
				elseif(strchr($scd['Title'],"AVT")){$AVT=$scd['Amount']; }
				elseif(strchr($scd['Title'],"SNK")){$SNK=$scd['Amount']; }
				elseif(strchr($scd['Title'],"OTH")){$OTH=$scd['Amount']; }
				
				$tot+=$scd['Amount'];

			}

			$gtot+=$tot;
			?>
			
			<tr>
				<th><?=date("d-m-Y",strtotime($selq['CrDate']))?></th>
				<td><?=$HVC?></td>
				<td><?=$AVT?></td>
				<td><?=$SNK?></td>
				<td><?=$OTH?></td>

				<td><?=$dat['fms']?></td>
				<td><?=$dat['dtp']?></td>
				
				<td style="text-align: right;"><?=$tot?>&nbsp;</td>		
			</tr>
			<?php	
		}
		?>

		<tr>
			<th colspan="7" style="text-align: right;">Grand Total&nbsp;</th>
			<th style="text-align: right;"><?=$gtot?>&nbsp;</th>
		</tr>
	</tbody>


</table>


<hr>


<table id="TrvTable" class="mRepTbl table table-bordered table-striped" style="width: 50%;">
	<thead>
		<tr>
			<th colspan="20" style="text-align: left;background-color:#006666;color: white; padding:!important 30px; ">
				&nbsp;Expense Type - Dealer Meeting
			</th>
		</tr>
		
		<tr>
			<th style="min-width:90px;">Date</th>
			<th>MLS</th>
			<th>HTN</th>
			<th>GFT</th>
			<th>AV</th>
			<th>OTH</th>


			<th>Total</th>
		
		</tr>
	</thead>

	<tbody>
		<?php
		$gtot=0;
		$q="SELECT e.* FROM `y".$_SESSION['FYearId']."_expenseclaims` e where e.ClaimMonth='".$month."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$crby."  and e.AttachTo=0 and e.ClaimStatus!='Deactivate' and e.ClaimId=13 group by e.CrDate order by e.CrDate"; 
		$qd=mysql_query($q);
		while($selq=mysql_fetch_assoc($qd)){

			$tot=0; 
			$sc=mysql_query("SELECT Title,Amount FROM `y".$_SESSION['FYearId']."_expenseclaimsdetails`  where ExpId='".$selq['ExpId']."'");
			while($scd=mysql_fetch_assoc($sc)){

				if(strchr($scd['Title'],"MLS")){$MLS=$scd['Amount']; }
				elseif(strchr($scd['Title'],"HTN")){$HTN=$scd['Amount']; }
				elseif(strchr($scd['Title'],"GFT")){$GFT=$scd['Amount']; }
				elseif(strchr($scd['Title'],"AV")){$AV=$scd['Amount']; }
				elseif(strchr($scd['Title'],"OTH")){$OTH=$scd['Amount']; }
				$tot+=$scd['FilledTAmt'];
			}
			$gtot+=$tot;
			?>
			<tr>
				<th><?=date("d-m-Y",strtotime($selq['CrDate']))?></th>
				<td><?=$MLS?></td>
				<td><?=$HTN?></td>
				<td><?=$GFT?></td>
				<td><?=$AV?></td>
				<td><?=$OTH?></td>


				<td style="text-align: right;"><?=$tot?>&nbsp;</td>		
			</tr>
			<?php	
		}
		?>

		<tr>
			<th colspan="6" style="text-align: right;">Grand Total&nbsp;</th>
			<th style="text-align: right;"><?=$gtot?>&nbsp;</th>
		</tr>
	</tbody>


</table>


<hr>


<table id="TrvTable" class="mRepTbl table table-bordered table-striped" style="width: 45%;">
	<thead>
		<tr>
			<th colspan="20" style="text-align: left;background-color:#006666;color: white; padding:!important 30px; ">
				&nbsp;Expense Type - Jeep Caimpaign
			</th>
		</tr>
		
		<tr>
			<th style="min-width:90px;">Date</th>
			<th>HVC</th>
			<th>AV</th>
			<th>OTH</th>


			<th>Total</th>
		
		</tr>
	</thead>

	<tbody>
		<?php
		$gtot=0;
		$q="SELECT e.* FROM `y".$_SESSION['FYearId']."_expenseclaims` e where e.ClaimMonth='".$month."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$crby."  and e.AttachTo=0 and e.ClaimStatus!='Deactivate' and e.ClaimId=12 group by e.CrDate order by e.CrDate"; 
		$qd=mysql_query($q);
		while($selq=mysql_fetch_assoc($qd)){

			$tot=0; 
			$sc=mysql_query("SELECT Title,Amount FROM `y".$_SESSION['FYearId']."_expenseclaimsdetails`  where ExpId='".$selq['ExpId']."'");
			while($scd=mysql_fetch_assoc($sc)){

				if(strchr($scd['Title'],"HVC")){$HVC=$scd['Amount']; }
				elseif(strchr($scd['Title'],"AV")){$AV=$scd['Amount']; }
				elseif(strchr($scd['Title'],"OTH")){$OTH=$scd['Amount']; }
				$tot+=$scd['FilledTAmt'];
			}
			$gtot+=$tot;
			?>
			<tr>
				<th><?=date("d-m-Y",strtotime($selq['CrDate']))?></th>
				<td><?=$HVC?></td>
				<td><?=$AV?></td>
				<td><?=$OTH?></td>


				<td style="text-align: right;"><?=$tot?>&nbsp;</td>		
			</tr>
			<?php	
		}
		?>

		<tr>
			<th colspan="4" style="text-align: right;">Grand Total&nbsp;</th>
			<th style="text-align: right;"><?=$gtot?>&nbsp;</th>
		</tr>
	</tbody>


</table>


<hr>


<table id="TrvTable" class="mRepTbl table table-bordered table-striped" style="width: 40%;">
	<thead>
		<tr>
			<th colspan="20" style="text-align: left;background-color:#006666;color: white; padding:!important 30px; ">
				&nbsp;Expense Type - RST/OFD
			</th>
		</tr>
		
		<tr>
			<th style="min-width:90px;">Date</th>
			<th>LC</th>
			<th>IC</th>
			<th>MSC</th>

			<th>Total</th>
		
		</tr>
	</thead>

	<tbody>
		<?php
		$gtot=0;
		$q="SELECT e.* FROM `y".$_SESSION['FYearId']."_expenseclaims` e where e.ClaimMonth='".$month."' and e.ClaimYearId='".$_SESSION['FYearId']."' and  e.CrBy=".$crby."  and e.AttachTo=0 and e.ClaimStatus!='Deactivate' and e.ClaimId=10 group by e.CrDate order by e.CrDate"; 
		$qd=mysql_query($q);
		while($selq=mysql_fetch_assoc($qd)){

			$tot=0; 
			$sc=mysql_query("SELECT Title,Amount FROM `y".$_SESSION['FYearId']."_expenseclaimsdetails`  where ExpId='".$selq['ExpId']."'");
			while($scd=mysql_fetch_assoc($sc)){

				if(strchr($scd['Title'],"LC")){$LC=$scd['Amount']; }
				elseif(strchr($scd['Title'],"IC")){$IC=$scd['Amount']; }
				elseif(strchr($scd['Title'],"MSC")){$MSC=$scd['Amount']; }
				$tot+=$scd['FilledTAmt'];
			}
			$gtot+=$tot;
			?>
			<tr>
				<th><?=date("d-m-Y",strtotime($selq['CrDate']))?></th>
				<td><?=$LC?></td>
				<td><?=$IC?></td>
				<td><?=$MSC?></td>


				<td style="text-align: right;"><?=$tot?>&nbsp;</td>		
			</tr>
			<?php	
		}
		?>

		<tr>
			<th colspan="4" style="text-align: right;">Grand Total&nbsp;</th>
			<th style="text-align: right;"><?=$gtot?>&nbsp;</th>
		</tr>
	</tbody>


</table>