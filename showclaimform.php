
<?php
session_start();
if(!isset($_SESSION['login'])){
  session_destroy();
  header('location:index.php');
}
include 'config.php';
// echo 'aaa'.$_SESSION['EmpCode'].'---'.$_SESSION['EmployeeID'];

?>
<?php

/*
====================================================================================================
		setting fare details table visibility settings
====================================================================================================
*/
switch ($_SESSION['EmpRole']) {
    case 'E':
    	$title='readonly';
        $astate='readonly';
        $vastate='readonly';
        $aastate='readonly';
        $fastate='readonly';
		$Amount='';
		$VerifierEditAmount='';
		$ApproverEditAmount='';
		$FinanceEditAmount='';
        break;
    case 'M':
    	$title='readonly required';
        $astate='readonly';
        $vastate='readonly';
        $aastate='readonly';
        $fastate='readonly';
        $Amount='transparent';
		$VerifierEditAmount='';
		$ApproverEditAmount='';
		$FinanceEditAmount='';
        break;
    case 'V':
    	$title='readonly';
        $astate='readonly';
        $vastate='';
        $aastate='readonly';
        $fastate='readonly';
        $Amount='readonly';
		$VerifierEditAmount='transparent';
		$ApproverEditAmount='';
		$FinanceEditAmount='';
        break;
    case 'A':
    	$title='readonly';
        $astate='readonly';
        $vastate='readonly';
        $aastate='';
        $fastate='readonly';
        $Amount='';
		$VerifierEditAmount='';
		$ApproverEditAmount='transparent';
		$FinanceEditAmount='';
        break;
    case 'F':
    	$title='readonly';
        $astate='readonly';
        $vastate='readonly';
        $aastate='readonly';
        $fastate='';
        $Amount='';
		$VerifierEditAmount='';
		$ApproverEditAmount='';
		$FinanceEditAmount='transparent';
        break;
}


if(isset($exp['ClaimStatus'])){
	if($exp['ClaimStatus']=='Filled'){$astate='readonly';$Amount='';$title='readonly';}
	if($exp['ClaimStatus']=='Verified'){$vastate='readonly';$VerifierEditAmount='';}
	if($exp['ClaimStatus']=='Approved'){$aastate='readonly';$ApproverEditAmount='';}
	if($exp['ClaimStatus']=='Financed'){$fastate='readonly';$FinanceEditAmount='';}
}
/*
====================================================================================================
		setting fare details table visibility settings
====================================================================================================
*/

$cg=mysql_query("select cgId from claimtype where ClaimId=".$_REQUEST['claimid']);
$cgd=mysql_fetch_assoc($cg);


$e=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaims where ExpId=".$_REQUEST['expid']);
$exp=mysql_fetch_assoc($e);

if($cgd['cgId']>0){ $ef=mysql_query("select * from y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata where ExpId=".$_REQUEST['expid']);
$expf=mysql_fetch_assoc($ef); }



if($_SESSION['EmpRole']=='M'){ $actform='updateclaim.php'; }
elseif($_SESSION['EmpRole']=='V'){ $actform='updateclaimV.php'; }
elseif($_SESSION['EmpRole']=='A'){ $actform='updateclaimA.php'; }
elseif($_SESSION['EmpRole']=='F'){ $actform='updateclaimF.php'; }


if($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==1)
{ 
	/*
	====================================================================================================
			$_POST['claimid']==1      Lodging form 
	====================================================================================================
	*/


$BillDate = ($exp['BillDate']  != '0000-00-00' && $exp['BillDate']!= '') ? date("d-m-Y",strtotime($exp['BillDate'])) : '';
$arrdate  = ($expf['Arrival']   != '0000-00-00 00:00:00' && $expf['Arrival']   != '') ? date("d-m-Y H:i",strtotime($expf['Arrival'])) : '';
$depdate  = ($expf['Departure'] != '0000-00-00 00:00:00' && $expf['Departure'] != '') ? date("d-m-Y H:i",strtotime($expf['Departure'])) : '';

?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
			
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				<tr >
				<th scope="row">City Category</th>
				<td>
					<select type="text" class="form-control" id="CityCategory" name="CityCategory" readonly>
				    	<option value="A">A</option>
				    	<option value="B">B</option>
				    	<option value="C">C</option>
				    </select>
				</td>
				<script type="text/javascript">
					$("#CityCategory option[value=<?=$expf['CityCategory']?>]").attr('selected', 'selected');
				</script>
				
				</tr>


				<tr >
				<th scope="row">Hotel Name</th>
				<td><input type="text" class="form-control" name="HotelName" value="<?=$expf['HotelName']?>" readonly></td>
				<th scope="row">Hotel Address</th>
				<td><input class="form-control" name="HotelAddress" value="<?=$expf['HotelAddress']?>" readonly></td>
				</tr>
				
				<tr >
				<th scope="row">Billing Person </th>
				<td><input type="text" class="form-control" name="BillingName" value="<?=$expf['BillingName']?>" readonly></td>
				<th scope="row">Billing address</th>
				<td><input class="form-control" name="BillingAddress" value="<?=$expf['BillingAddress']?>" readonly></td>
				</tr>
				
				<tr>
				<th scope="row">Bill No. </th>
				<td><input type="text" class="form-control" name="BillNo" value="<?=$expf['BillNo']?>" readonly></td>
				<th scope="row">Bill date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly></td>
				</tr>
				
				<tr>
				<th scope="row">Arr. date/time</th>
				<td>
					<input id="arrdate" name="arrdate" value="<?=$arrdate?>" placeholder="Arrival" class="form-control" readonly>	
				</td>
				<th scope="row">Dept. date/time</th>
				<td>
					<input id="depdate" name="depdate" value="<?=$depdate?>" placeholder="Departure" class="form-control" readonly>
					
				</td>
				</tr>
				
				<tr>
				<th scope="row">Duration of stay</th>
				<td><input type="text" class="form-control" name="StayDuration" value="<?=$expf['StayDuration']?>" readonly></td>
				<th scope="row">Room rate/type</th>
				<td><input type="text" class="form-control" name="RoomRateType" value="<?=$expf['RoomRateType']?>" readonly></td>
				</tr>
				
				<tr>
				<th scope="row">Meal Plan</th>
				<td><select type="text" class="form-control" name="MealPlan" value="<?=$expf['Plan']?>" readonly>
				    <option value="AP">American Plan</option><option value="MAP">Modify American Plan</option><option value="EP">European plan</option><option value="CP">Continental plan</option></select>
				
				</td>
				<th scope="row">No. of pax</th>
				<td>
					<select type="text" class="form-control" id="NoOfPAX" name="NoOfPAX" readonly>
					    <option value="One">1</option>
					    <option value="Two">2</option>
					    <option value="Three">3</option>
					    <option value="Four">4</option>
					    <option value="Five">5</option>
					    <option value="Six">6</option>
					    <option value="Seven">7</option>
					    <option value="Eight">8</option>
					    <option value="Nine">9</option>
					    <option value="Ten">10</option>
					</select>

				    <script type="text/javascript">
						$("#NoOfPAX option[value=<?=$expf['NoOfPAX']?>]").attr('selected', 'selected');
					</script>

				</td>
				</tr>
				
				<tr>
				<th scope="row">GST/Tax Rate</th>
				<td><input type="text" class="form-control" name="GST" value="<?=$expf['GST']?>" readonly></td>
				
				<th scope="">Billing instruction</th>
				<td>
					<select type="text" class="form-control" id="BillIns" name="BillIns" readonly>
						<option value="Direct">Direct</option>
						<option value="Bill to Company">Bill to Company</option>
					</select>
					<script type="text/javascript">
						$("#BillIns option[value=<?=$expf['BillingInstruction']?>]").attr('selected', 'selected');
					</script>
				</td>
				</tr>

				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								
								<tr>
			<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
			<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
			<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
			<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
			<?php if($_SESSION['EmpRole']!='M'){ ?>
			<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
				<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
			<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
			<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
				<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
			<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
			<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
				<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
			<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
			<?php }?>


			<?php if($_SESSION['EmpRole']=='M'){ ?>
			<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
			<?php } ?>
									
		  </tr>
		  <?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">


						

						

				      	<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftLodging" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>
<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>
						<button class="btn btn-sm btn-success" id="Update" name="UpdateLodging" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>
						<?php } ?>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==2)
{
	/*
	====================================================================================================
			$_POST['claimid']==2      Air,Rail&Bus Fare Form
	====================================================================================================
	*/



$BookingDate=($expf['BookingDate']!='0000-00-00' && $expf['BookingDate']!='') ? date("d-m-Y",strtotime($expf['BookingDate'])) : '';
$JourneyStartDt=($expf['JourneyStartDt']!='0000-00-00' && $expf['JourneyStartDt']!='') ? date("d-m-Y",strtotime($expf['JourneyStartDt'])) : '';

?>
<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">
				

				<!-- <tr >
				<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
				<td colspan="3">
					<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" readonly value="<?=$exp['ExpenseName']?>">
				</td>
				
				</tr> -->
			
				<tr >
					<th scope="row">Mode&nbsp;<span class="text-danger">*</span></th>
					<td>
						<select class="form-control" id="Mode" name="Mode" readonly onchange="changemode(this.value)">
							<!--<option></option>-->
							<option value="Air" <?php echo $expf['Mode']=='Air'?'selected':'';?>>Air</option>
							<option value="Rail" <?php echo $expf['Mode']=='Rail'?'selected':'';?>>Rail</option>
							<option value="Bus" <?php echo $expf['Mode']=='Bus'?'selected':'';?>>Bus</option>
						</select>
					</td>
					<th scope="row"><span id="modenm">Flight</span> Name&nbsp;<span class="text-danger">*</span></th>
					<td>
						<input type="text" class="form-control" id="TrainBusName" name="TrainBusName" readonly value="<?=$expf['TrainBusName']?>">
					</td>
				</tr>
				
				<tr >
				<th scope="row">Quota&nbsp;<span class="text-danger">*</span></th>
				<td>
					<select class="form-control" id="Quota" readonly name="Quota">
						<!--<option></option>-->
						<option value="GN" <?php echo $expf['Quota']=='GN'?'selected':'';?>>GN</option>
						<option value="Tatkal" <?php echo $expf['Quota']=='Tatkal'?'selected':'';?>>Tatkal</option>
						<option value="Bus" <?php echo $expf['Quota']=='Bus'?'selected':'';?>>Pr. Tatkal</option>
					</select>
				</td>
				<th scope="row">Class&nbsp;<span class="text-danger">*</span></th>
				<td>
					
					<select class="form-control" id="Class" readonly name="Class">
						<!--<option></option>-->
						<option value="CC" <?php echo $expf['Class']=='CC'?'selected':'';?>>CC</option>
						<option value="SL" <?php echo $expf['Class']=='SL'?'selected':'';?>>SL</option>
						<option value="AC" <?php echo $expf['Class']=='AC'?'selected':'';?>>AC</option>
						<option value="Economy" <?php echo $expf['Class']=='Economy'?'selected':'';?>>Economy</option>
						<option value="Economy AC" <?php echo $expf['Class']=='Economy AC'?'selected':'';?>>Economy AC</option>
						<option value="Business" <?php echo $expf['Class']=='Business'?'selected':'';?>>Business</option>
					</select>
				</td>
			
				</tr>
				
				<tr>
				<th scope="row">Booking Date&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="BookingDate" name="BookingDate" readonly autocomplete="off" value="<?=$BookingDate?>"></td>
				<th scope="row">Journey Date&nbsp;<span class="text-danger">*</span></th>
				<td><input  class="form-control dat" id="rbJourneyStartDt" name="JourneyStartDt" readonly autocomplete="off" value="<?=$JourneyStartDt?>"></td>
				</tr>
				
				
				<tr>
				<th scope="row">Journey from&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="JourneyFrom" name="JourneyFrom" readonly value="<?=$expf['JourneyFrom']?>"></td>
				<th scope="row">Journey Upto&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="JourneyUpto" name="JourneyUpto" readonly value="<?=$expf['JourneyUpto']?>"></td>
				</tr>
				
				<tr>
				<th scope="row">Passenger Detail</th>
				<td><input type="text" class="form-control" id="PassengerDetail" name="PassengerDetail"  value="<?=$expf['PassengerDetail']?>" readonly></td>
				<th scope="row">Booking Status&nbsp;<span class="text-danger">*</span></th>
				<td>
					<select class="form-control" id="BookingStatus" readonly name="BookingStatus">
						<!--<option></option>-->
						<option value="Confirmed" <?php echo $expf['BookingStatus']=='Confirmed'?'selected':'';?>>Confirmed</option>
						<option value="Waiting" <?php echo $expf['BookingStatus']=='Waiting'?'selected':'';?>>Waiting</option>
						
					</select>
				</td>
				</tr>
				
				<tr>
				<th scope="row">Travel Insurance&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="TravelInsurance" name="TravelInsurance" readonly value="<?=$expf['TravelInsurance']?>"></td>
				
				<!-- <th scope="">Total Fare</th>
				<td><input type="text" class="form-control" id="TotalFare" name="TotalFare"  value="<?=$exp['TotalFare']?>" readonly></td>
				</tr> -->
				
				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --></td>	
				</tr>
				
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									
									<td>
										<input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>>
									</td>
									<td>
										<input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>>
									</td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td>
										<?php
										if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';}
										?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>>
									</td>
									
									<td>
										<?php
										if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}
										?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>>
									</td>
									
									<td>
										<?php
										if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}
										?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>>
									</td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td style="width:20px;text-align:center;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button>
									</td>
									<?php } ?>
									
								</tr>
								
								<?php
								$i++;
								}
								?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>
						

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				
				
				</tr><?php */?>

				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" id="Remark" name="Remark" value="<?=$exp['Remark']?>">

				      	<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?> 
				      	<button class="btn btn-sm btn-info" id="draft" name="draftRailBusFare" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
                        <?php } ?>
                        
						<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>
						<button class="btn btn-sm btn-success" id="Update" name="UpdateRailBusFare" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

				<?php } ?>		
				<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>


					</td>
				</tr>
			
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==3)
{
	/*
	====================================================================================================
			$_POST['claimid']==3      Local Conveyance form 
	====================================================================================================
	*/


$JourneyStartDt=($expf['JourneyStartDt']!='0000-00-00' && $expf['JourneyStartDt']!='') ? date("d-m-Y",strtotime($expf['JourneyStartDt'])) : '';
$JourneyEndDt=($expf['JourneyEndDt']!='0000-00-00' && $expf['JourneyEndDt']!='') ? date("d-m-Y",strtotime($expf['JourneyEndDt'])) : '';


?>
<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				
				<!-- <tr >
					<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
					<td colspan="3">
						<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" readonly value="<?=$exp['ExpenseName']?>">
					</td>
				
				</tr> -->

				<tr>
					<th scope="row">Trip Started On&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control dat" id="JourneyStartDt1" name="JourneyStartDt" value="<?=$JourneyStartDt?>" readonly required></td>
					<th scope="row">Trip Ended On&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="JourneyEndDt" name="JourneyEndDt" value="<?=$JourneyEndDt?>" readonly required></td>
				</tr>

				<tr >
					<th scope="row">Mode&nbsp;<span class="text-danger">*</span></th>
					<td>
						<!-- <input class="form-control" id="Mode" name="Mode" value="<?php $expf['Mode']; ?>" required readonly /> -->
						<select class="form-control" id="Mode" name="Mode" required readonly >
							<option></option>
							<option value="Sharing Taxi / Cab" <?php if($expf['Mode']=='Sharing Taxi / Cab'){echo 'selected';}?>>Sharing Taxi / Cab</option>
							<option value="Auto" <?php if($expf['Mode']=='Auto'){echo 'selected';}?>>Auto</option>
							<option value="Bus" <?php if($expf['Mode']=='Bus'){echo 'selected';}?>>Bus</option>
							
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --></td>	
				</tr>
				
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];
									
								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									
									<td>
										<input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>>
									</td>
									<td>
										<input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>>
									</td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td>
										<?php
										if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';}
										?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>>
									</td>
									
									<td>
										<?php
										if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}
										?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>>
									</td>
									
									<td>
										<?php
										if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}
										?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>>
									</td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td  style="width: 20px;">
										<button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;">
											<i class="fa fa-times fa-sm" aria-hidden="true"></i>
										</button>
									</td>
									<?php } ?>
									
								</tr>
								
								<?php
								$i++;
								}
								?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet(<?=$exp['ClaimId']?>)">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>
				
				

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" ><?=$exp['Remark']?></textarea></td>
				
				
				</tr><?php */?>

				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" id="Remark" name="Remark" value="<?=$exp['Remark']?>">


						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                         <?php if($_SESSION['EmpRole']=='M'){ ?> 
				      	<button class="btn btn-sm btn-info" id="draft" name="draftLocalConv" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
                         <?php } ?>
						 <?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>
						<button class="btn btn-sm btn-success" id="Update" name="UpdateLocalConv" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>
<?php } ?>
						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

					</td>
				</tr>
			
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==4)
{
	/*
	====================================================================================================
			$_POST['claimid']==4      Hired Vehicle form 
	====================================================================================================
	*/



$BillDate = ($exp['BillDate']!= '0000-00-00' && $exp['BillDate']!= '') ? date("d-m-Y",strtotime($exp['BillDate'])) : '';
$JourneyStartDt  = ($expf['JourneyStartDt'] != '0000-00-00' && $expf['JourneyStartDt'] != '') ? date("d-m-Y",strtotime($expf['JourneyStartDt'])) : '';
$JourneyEndDt  = ($expf['JourneyEndDt'] != '0000-00-00' && $expf['JourneyEndDt'] != '') ? date("d-m-Y",strtotime($expf['JourneyEndDt'])) : '';


$DailyBasisCharges = ($expf['DailyBasisCharges']  != '0') ? $expf['DailyBasisCharges'] : '';
$KmBasisCharges = ($expf['KmBasisCharges']  != '0') ? $expf['KmBasisCharges'] : '';
$DriverCharges = ($expf['DriverCharges']  != '0') ? $expf['DriverCharges'] : '';
$OtherCharges = ($expf['OtherCharges']  != '0') ? $expf['OtherCharges'] : '';


?>
<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl " style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				
				<!-- <tr >
					<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
					<td colspan="3">
						<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" readonly required value="<?=$exp['ExpenseName']?>">
					</td>
				
				</tr> -->
				<tr>
					<th scope="row">Agency Name&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="AgencyName" name="AgencyName" value="<?=$expf['AgencyName']?>" readonly required></td>
					<th scope="row">Agency Address&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="AgencyAddress" name="AgencyAddress" value="<?=$expf['AgencyAddress']?>" readonly required></td>
				</tr>
				<tr >
					<th scope="row">Billing Person </th>
					<td><input type="text" class="form-control" name="BillingName" value="<?=$expf['BillingName']?>" readonly></td>
					<th scope="row">Billing address</th>
					<td><input class="form-control" rows="2" name="BillingAddress" value="<?=$expf['BillingAddress']?>" readonly required></td>
				</tr>
				<tr>
					<th scope="row">Invoice No. </th>
					<td><input type="text" class="form-control" name="Invoice" value="<?=$expf['Invoice']?>" readonly></td>
					<th scope="row">Date of Travel</th>
					<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly></td>
				</tr>
				<tr>
					
					<th colspan="2" scope="row">Vehicle class /Registration Number&nbsp;<span class="text-danger">*</span></th>
					<td colspan="2"><input type="text" class="form-control" id="VehicleReg" name="VehicleReg" value="<?=$expf['VehicleReg']?>" required readonly></td>
				</tr>
				<tr>
					<th scope="row">Journey Start Dt&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control dat" id="JourneyStartDt1" name="JourneyStartDt" value="<?=$JourneyStartDt?>" required readonly></td>
					<th scope="row">Journey End Dt&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="JourneyEndDt" name="JourneyEndDt" value="<?=$JourneyEndDt?>" required readonly></td>
				</tr>
				<tr>
					<th scope="row"><!--Distance_Travelled (-->Opening_Reading<!--)-->&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="DistTraOpen" name="DistTraOpen" value="<?=$expf['DistTraOpen']?>" required readonly></td>
					<th scope="row"><!--Distance_Travelled (-->Closing_Reading<!--)-->&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="DistTraClose" name="DistTraClose" value="<?=$expf['DistTraClose']?>" required readonly></td>
				</tr>


				<tr>
					<th scope="row">Charges (Daily basis)&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="DailyBasisCharges" name="DailyBasisCharges" value="<?=$DailyBasisCharges?>" required readonly onkeypress="return isNumber(event)" ></td>
					<th scope="row">(Km Basis)&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="KmBasisCharges" name="KmBasisCharges" value="<?=$KmBasisCharges?>" required readonly onkeypress="return isNumber(event)" ></td>
				</tr>

				<tr>
					<th scope="row">Driver Charges&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="DriverCharges" name="DriverCharges" value="<?=$DriverCharges?>" required readonly placeholder="Fooding/Allowances" onkeypress="return isNumber(event)" ></td>
					<th scope="row">Other Charges&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="OtherCharges" name="OtherCharges" value="<?=$OtherCharges?>" required readonly placeholder="Toll/Parking" onkeypress="return isNumber(event)" ></td>
				</tr>
				
                <tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --></td>	
				</tr>  


				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									
									<td>
										<input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>>
									</td>
									<td>
										<input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>>
									</td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td>
										<?php
										if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';}
										?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>>
									</td>
									
									<td>
										<?php
										if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}
										?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>>
									</td>
									
									<td>
										<?php
										if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}
										?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>>
									</td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td  style="width: 20px;">
										<button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;">
											<i class="fa fa-times fa-sm" aria-hidden="true"></i>
										</button>
									</td>
									<?php } ?>
									
								</tr>
								
								<?php
								$i++;
								}
								?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet(<?=$exp['ClaimId']?>)">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" ><?=$exp['Remark']?></textarea></td>
				
				
				</tr><?php */?>

				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
                        <input type="hidden" id="Remark" name="Remark" value="<?=$exp['Remark']?>">
				      	

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftHiredVeh" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
                        <?php } ?>
						
						<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>
						
						<button class="btn btn-sm btn-success" id="Update" name="UpdateHiredVeh" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>
<?php } ?>
						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

					</td>
				</tr>
			
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==5)
{
	/*
	====================================================================================================
			$_POST['claimid']==5      Phone Fax form 
	====================================================================================================
	*/


$BillDate = ($exp['BillDate']  != '0000-00-00' && $exp['BillDate']  != '') ? date("d-m-Y",strtotime($exp['BillDate'])) : '';
// $JourneyStartDt  = ($exp['JourneyStartDt'] != '0000-00-00') ? date("d-m-Y",strtotime($exp['JourneyStartDt'])) : '';
$DueDate  = ($expf['DueDate'] != '0000-00-00' && $expf['DueDate'] != '') ? date("d-m-Y",strtotime($expf['DueDate'])) : '';



?>
<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl " style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				
				<!-- <tr >
					<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
					<td colspan="3">
						<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" readonly required value="<?=$exp['ExpenseName']?>">
					</td>
				
				</tr> -->

				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly style="width:80px;"></td>
				
				</tr>

				<tr >
					<th scope="row">Billing Person </th>
					<td><input type="text" class="form-control" name="BillingName" value="<?=$expf['BillingName']?>" readonly></td>
					<th scope="row">Billing address</th>
					<td><input class="form-control" rows="2" name="BillingAddress" value="<?=$expf['BillingAddress']?>" readonly></td>
				</tr>

				<tr>
					<th scope="row">Mobile Service&nbsp;<span class="text-danger">*</span></th>
					<td>
						<select class="form-control" id="MobileService" readonly name="MobileService">
							<!--<option></option>-->
							<option value="PREPAID" <?php echo $exp['MobileService']=='PREPAID'?'selected':'';?>>PREPAID</option>
							<option value="POSTPAID" <?php echo $exp['MobileService']=='POSTPAID'?'selected':'';?>>POSTPAID</option>
						</select>
					</td>
					<th scope="row">Mobile Number&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="Mobile" name="Mobile" value="<?php if($expf['Mobile']!=0){echo $expf['Mobile'];}?>" readonly required onkeypress="return isNumber(event)" pattern="[789][0-9]{9}" maxlength="10"></td>
				</tr>

				<tr>
					<th scope="row">Billing Cycle&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="BillingCycle" name="BillingCycle" value="<?=$expf['BillingCycle']?>" readonly required></td>
					<th scope="row">Tariff Plan&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="Plan" name="Plan" value="<?=$expf['Plan']?>" readonly required></td>
				</tr>
				
				
				<tr>
					<th scope="row">Charges&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="OtherCharges" name="OtherCharges" value="<?php if($expf['OtherCharges']!=0){echo $expf['OtherCharges'];}?>" placeholder="other than usage charges" required readonly onkeypress="return isNumber(event)" ></td>
					<th scope="row">Previous balance </th>
					<td><input  class="form-control" id="PrevBalance" name="PrevBalance" value="<?=$expf['PrevBalance']?>" readonly></td>
				</tr>

				<tr>
					<th scope="row" colspan="2">Last Payement Detail&nbsp;<span class="text-danger">*</span></th>
					<td colspan="2"><input type="text" class="form-control" id="LastPayement" name="LastPayement" value="<?=$expf['LastPayement']?>" required readonly></td>
				</tr>
				<tr>
					<th scope="row">Payment Mode&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="PaymentMode" name="PaymentMode" value="<?=$expf['PaymentMode']?>" required readonly></td>
					<th  scope="row">Due Date&nbsp;<span class="text-danger">*</span></th>
					<!-- <td><input  class="form-control" id="BillDate" name="BillDate" value="<?=$BillDate?>" readonly></td> -->
					<td ><input class="form-control" id="DueDate" name="DueDate" value="<?=$DueDate?>" readonly></td>

					<script type="text/javascript">
						$('#DueDate').datetimepicker({format:'d-m-Y'});
					    $('#DueDate').on('change', function(){
					        $(this).datetimepicker('hide');
					    }); //here closing the billdate datetimepicker on date change 
					</script>
					
				</tr>
                <tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --></td>	
				</tr>

				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									
									<td>
										<input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>>
									</td>
									<td>
										<input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>>
									</td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td>
										<?php
										if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';}
										?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>>
									</td>
									
									<td>
										<?php
										if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}
										?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>>
									</td>
									
									<td>
										<?php
										if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}
										?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>>
									</td>
									<?php } ?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td  style="width: 20px;">
										<button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;">
											<i class="fa fa-times fa-sm" aria-hidden="true"></i>
										</button>
									</td>
									<?php } ?>
									
								</tr>
							
								<?php
								$i++;
								}
								?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet(<?=$exp['ClaimId']?>)">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>
				

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" ><?=$exp['Remark']?></textarea></td>
				
				
				</tr><?php */?>

				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
                        <input type="hidden" id="Remark" name="Remark" value="<?=$exp['Remark']?>">
                        <?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft2" name="draftMobileBill" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?> 
						<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>

						<button class="btn btn-sm btn-success" id="Update" name="UpdateMobileBill" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

<?php } ?>
						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>
				      	

					</td>
				</tr>
			
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==6)
{
	/*
	====================================================================================================
			$_POST['claimid']==6      Postage Courier form 
	====================================================================================================
	*/


$DocketBookedDt  = ($expf['DocketBookedDt'] != '0000-00-00' && $expf['DocketBookedDt'] != '') ? date("d-m-Y",strtotime($expf['DocketBookedDt'])) : '';

?>
<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl " style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">
				

				<!-- <tr >
					<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
					<td colspan="3">
						<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" readonly required value="<?=$exp['ExpenseName']?>">
					</td>
				
				</tr> -->

				<tr >
					<th scope="row">Provider Name</th>
					<td colspan=""><input type="text" class="form-control" name="ServiceProvider" value="<?=$expf['ServiceProvider']?>" readonly></td>
					<th scope="row">Weight Charged&nbsp;<span class="text-danger">*</span></th>
					<td ><input type="text" class="form-control" name="WeightCharged" value="<?=$expf['WeightCharged']?>" required readonly></td> 
					
				</tr>

				<tr>
					<th scope="row">Sender Name</th>
					<td><input type="text" class="form-control" name="SenderName" value="<?=$expf['SenderName']?>" readonly></td>
					<th scope="row">Sender_Address</th>
					<td><input class="form-control" rows="2" name="SenderAddress" readonly value="<?=$expf['SenderAddress']?>" /></td>
					
				</tr>

				<tr>
					<th scope="row">Receiver name &nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" name="ReceiverName" value="<?=$expf['ReceiverName']?>" readonly required></td>
					
					<th scope="row">Receiver Address</th>
					<td><input class="form-control" rows="2" name="ReceiverAddress" readonly value="<?=$expf['ReceiverAddress']?>" /></td>
					
				</tr>
				

				<tr>
					<th scope="row">Docket No. &nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" name="DocketNumber" value="<?=$expf['DocketNumber']?>" required readonly></td>
					<th scope="row">Booked Date </th>
					
					<td><input  class="form-control dat" id="BillDate2" name="DocketBookedDt" value="<?=$DocketBookedDt?>" readonly></td>
				</tr>

				<tr>
					
				</tr>
                <tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --></td>	
				</tr>
				
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
									<td>
										<input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
										<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>>
										
									</td>
									
									<td>
										<input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>>
									</td>
									<td>
										<input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>>
									</td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td>
										<?php
										if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';}
										?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>>
									</td>
									
									<td>
										<?php
										if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}
										?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>>
									</td>
									
									<td>
										<?php
										if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}
										?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>>
									</td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td  style="width: 20px;">
										<button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;">
											<i class="fa fa-times fa-sm" aria-hidden="true"></i>
										</button>
									</td>
									<?php } ?>
									
								</tr>
								
								<?php
								$i++;
								}
								?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
					<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet(<?=$exp['ClaimId']?>)">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>
				

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" ><?=$exp['Remark']?></textarea></td>
				
				
				</tr><?php */?>

				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
                        <input type="hidden" id="Remark" name="Remark" value="<?=$exp['Remark']?>">
				      	

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftPostCour" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>
						
						<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>

						<button class="btn btn-sm btn-success" id="Update" name="UpdatePostCour" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>

<?php } ?>
						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

					</td>
				</tr>
			
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==7)
{ 
	/*
	====================================================================================================
			$_POST['claimid']==7      2/4 Wheeler form 
	====================================================================================================
	*/

if($_SESSION['CompanyId']==1){ $DbName='expense'; }
elseif($_SESSION['CompanyId']==3){ $DbName='expense_nr'; }
elseif($_SESSION['CompanyId']==4){ $DbName='expense_tl'; }	
	
$servername = "localhost";
$username = "expense_user";
$password = "expense@192";
$dbname = $DbName;



    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
    if(isset($expf['did'])){
    //$stmt = $conn->prepare("select * from y".$_SESSION['FYearId']."_24_wheeler_entry where did='".$expf['did']."' and ExpId='".$_REQUEST['expid']."'");
    $stmt = $conn->prepare("select * from y".$_SESSION['FYearId']."_24_wheeler_entry where ExpId='".$_REQUEST['expid']."'");
    $stmt->execute();
    $expf_wheeler = $stmt->fetchAll();
    }else{
    $expf_wheeler = array();
    }
// $ef_wheeler=mysql_query("select * from y".$_SESSION['FYearId']."_24_wheeler_entry where ExpId=".$_REQUEST['expid']);
// $expf_wheeler=mysql_fetch_assoc($ef_wheeler);

$JourneyStartDt  = ($expf['JourneyStartDt'] != '0000-00-00' && $expf['JourneyStartDt'] != '') ? date("d-m-Y",strtotime($expf['JourneyStartDt'])) : date("d-m-Y h:i",strtotime($exp['CrDate'])) ;
$JourneyEndDt  = ($expf['JourneyEndDt'] != '0000-00-00' && $expf['JourneyEndDt'] != '') ? date("d-m-Y",strtotime($expf['JourneyEndDt'])) : date("d-m-Y h:i",strtotime($exp['CrDate']));

?>


<?php  /*
$sY=mysql_query("select y1,y2 from financialyear where YearId=".$_SESSION['FYearId']);
$rY=mysql_fetch_assoc($sY); $y1=$rY['y1']; $y2=$rY['y2'];

$sqle=mysql_query("select * from vehicle_policyslab_employee where EmployeeID=".$exp['CrBy']." AND VPId>0 AND VPEStatus='Y'");
$rowe=mysql_num_rows($sqle);
if($rowe>0)
{
 $rese=mysql_fetch_assoc($sqle);
 $sely=mysql_query("SELECT * FROM `vehicle_policyslab` where CompanyId=".$_SESSION['CompanyId']." AND SlabStatus='A' AND VPId=".$rese['VPId']); $selyd=mysql_fetch_assoc($sely); $applym=$res['ApplyForMonth'];
?>
<tr>
<td colspan="10">
<table border="1" style="width:100%;border-color:#000000;" >
 <tr style="background-color:#00366C;color:#FFFFFF;">
  <td scope="col" style="text-align:center; border:thin;">Slab 1 Rate<br />
  <?=$selyd['Slab1_f'].' to '.$selyd['Slab1_t'].' <font color="#FF9F71">Km</font> -> '.$selyd['Slab1_rate'].' <font color="#FF9F71">Rs</font>'?></td>
  <td scope="col" style="text-align:center;">Slab 2 Rate<br />
  <?=$selyd['Slab2_f'].' to '.$selyd['Slab2_t'].' <font color="#FF9F71">Km</font> -> '.$selyd['Slab2_rate'].' <font color="#FF9F71">Rs</font>'?></td>
  <td scope="col" style="text-align:center;">Slab 3 Rate<br />
  <?=$selyd['Slab3_f'].' to '.$selyd['Slab3_t'].' <font color="#FF9F71">Km</font> -> '.$selyd['Slab3_rate'].' <font color="#FF9F71">Rs</font>'?></td></td>
 </tr>

</table>
</td>
</tr>

<tr>
<td colspan="10">
<table border="1" style="width:100%;border-color:#000000;" >
<?php 
$subQ="inner join y".$_SESSION['FYearId']."_expenseclaims clm on w.ExpId=clm.ExpId where clm.ClaimStatus!='Deactivate' AND clm.CrBy=".$exp['CrBy'];

$S4=mysql_query("select SUM(Totalkm) as tot4 from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($y1."-04-01")."' AND '".date($y1."-04-30")."' "); $R4=mysql_fetch_assoc($S4);
$S5=mysql_query("select SUM(Totalkm) as tot5 from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($y1."-05-01")."' AND '".date($y1."-05-31")."' "); $R5=mysql_fetch_assoc($S5);
$S6=mysql_query("select SUM(Totalkm) as tot6 from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($y1."-06-01")."' AND '".date($y1."-06-30")."' "); $R6=mysql_fetch_assoc($S6);
$S7=mysql_query("select SUM(Totalkm) as tot7 from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($y2."-07-01")."' AND '".date($y1."-07-31")."' "); $R7=mysql_fetch_assoc($S7);

$S8=mysql_query("select SUM(Totalkm) as tot8 from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($y1."-08-01")."' AND '".date($y1."-08-31")."' "); $R8=mysql_fetch_assoc($S8);
$S9=mysql_query("select SUM(Totalkm) as tot9 from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($y1."-09-01")."' AND '".date($y1."-09-30")."' "); $R9=mysql_fetch_assoc($S9);
$S10=mysql_query("select SUM(Totalkm) as tot10 from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($y1."-10-01")."' AND '".date($y1."-10-31")."' "); $R10=mysql_fetch_assoc($S10);
$S11=mysql_query("select SUM(Totalkm) as tot11 from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($y2."-11-01")."' AND '".date($y1."-11-30")."' "); $R11=mysql_fetch_assoc($S11);

$S12=mysql_query("select SUM(Totalkm) as tot12 from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($y1."-12-01")."' AND '".date($y1."-12-31")."' "); $R12=mysql_fetch_assoc($S12);
$S1=mysql_query("select SUM(Totalkm) as tot1 from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($y2."-01-01")."' AND '".date($y2."-01-31")."' "); $R1=mysql_fetch_assoc($S1);
$S2=mysql_query("select SUM(Totalkm) as tot2 from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($y2."-02-01")."' AND '".date($y2."-02-29")."' "); $R2=mysql_fetch_assoc($S2);
$S3=mysql_query("select SUM(Totalkm) as tot3 from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($y2."-03-01")."' AND '".date($y2."-03-31")."' "); $R3=mysql_fetch_assoc($S3);
?>
 
<tr>
 <td colspan="4"><b>Running Km :</b> &nbsp;&nbsp;
 <?php if($R4['tot4']>0){?><font color="#003399"><b>Apr:</b></font> <font color="#EB0142"><?=$R4['tot4']?></font>,&nbsp;&nbsp;<?php } ?>
<?php if($R5['tot5']>0){?><font color="#003399"><b>May:</b></font> <font color="#EB0142"><?=$R5['tot5']?></font>,&nbsp;&nbsp;<?php } ?>
<?php if($R6['tot6']>0){?><font color="#003399"><b>Jun:</b></font> <font color="#EB0142"><?=$R6['tot6']?></font>,&nbsp;&nbsp;<?php } ?>
<?php if($R7['tot7']>0){?><font color="#003399"><b>Jul:</b></font> <font color="#EB0142"><?=$R7['tot7']?></font>,&nbsp;&nbsp;<?php } ?>
<?php if($R8['tot8']>0){?><font color="#003399"><b>Aug:</b></font> <font color="#EB0142"><?=$R8['tot8']?></font>,&nbsp;&nbsp;<?php } ?>
<?php if($R9['tot9']>0){?><font color="#003399"><b>Sep:</b></font> <font color="#EB0142"><?=$R9['tot9']?></font>,&nbsp;&nbsp;<?php } ?>
<?php if($R10['tot10']>0){?><font color="#003399"><b>Oct:</b></font> <font color="#EB0142"><?=$R10['tot10']?></font>,&nbsp;&nbsp;<?php } ?>
<?php if($R11['tot11']>0){?><font color="#003399"><b>Nov:</b></font> <font color="#EB0142"><?=$R11['tot11']?></font>,&nbsp;&nbsp;<?php } ?>
<?php if($R12['tot12']>0){?><font color="#003399"><b>Dec:</b></font> <font color="#EB0142"><?=$R12['tot12']?></font>,&nbsp;&nbsp;<?php } ?>
<?php if($R1['tot1']>0){?><font color="#003399"><b>Jan:</b></font> <font color="#EB0142"><?=$R1['tot1']?></font>,&nbsp;&nbsp;<?php } ?>
<?php if($R2['tot2']>0){?><font color="#003399"><b>Feb:</b></font> <font color="#EB0142"><?=$R2['tot2']?></font>,&nbsp;&nbsp;<?php } ?>
<?php if($R3['tot3']>0){?><font color="#003399"><b>Mar:</b></font> <font color="#EB0142"><?=$R3['tot3']?></font>
<?php } ?>
 </td>
</tr>

<?php   
}

*/
?>


<?php /************* New Open ************/ ?>
<?php
$sY=mysql_query("select y1,y2 from financialyear where YearId=".$_SESSION['FYearId']);
$rY=mysql_fetch_assoc($sY); $y1=$rY['y1']; $y2=$rY['y2'];

$sqle=mysql_query("select * from vehicle_policyslab_employee where EmployeeID=".$exp['CrBy']." AND VPEStatus='Y'");
$rowe=mysql_num_rows($sqle);
if($rowe>0)
{
 $rese=mysql_fetch_assoc($sqle);
 $sely=mysql_query("SELECT * FROM `vehicle_policyslab` where CompanyId=".$_SESSION['CompanyId']." AND SlabStatus='A' AND VPId=".$rese['VPId']); $selyd=mysql_fetch_assoc($sely);
?>
<tr>
<td colspan="10">
<table border="1" style="width:100%;border-color:#000000;" >
 <tr style="background-color:#00366C;color:#FFFFFF;">
  <td scope="col" style="text-align:center; border:thin;">Slab 1 Rate<br />
  <?=$selyd['Slab1_f'].' to '.$selyd['Slab1_t'].' <font color="#FF9F71">Km</font> -> '.$selyd['Slab1_rate'].' <font color="#FF9F71">Rs</font>'?></td>
  <td scope="col" style="text-align:center;">Slab 2 Rate<br />
  <?=$selyd['Slab2_f'].' to '.$selyd['Slab2_t'].' <font color="#FF9F71">Km</font> -> '.$selyd['Slab2_rate'].' <font color="#FF9F71">Rs</font>'?></td>
  <td scope="col" style="text-align:center;">Slab 3 Rate<br />
  <?=$selyd['Slab3_f'].' to '.$selyd['Slab3_t'].' <font color="#FF9F71">Km</font> -> '.$selyd['Slab3_rate'].' <font color="#FF9F71">Rs</font>'?></td></td>
 </tr>

</table>
</td>
</tr>

<tr>
<td colspan="10">
<table border="1" style="width:100%;border-color:#000000;" >
<?php 
$subQ="inner join y".$_SESSION['FYearId']."_expenseclaims clm on w.ExpId=clm.ExpId where clm.ClaimStatus!='Deactivate' AND clm.CrBy=".$exp['CrBy'];

$S1=mysql_query("select SUM(Totalkm) as tot1 from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($y1."-04-01")."' AND '".date($y1."-06-31")."' "); $R1=mysql_fetch_assoc($S1);
$S2=mysql_query("select SUM(Totalkm) as tot2 from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($y1."-07-01")."' AND '".date($y1."-09-30")."' "); $R2=mysql_fetch_assoc($S2);
$S3=mysql_query("select SUM(Totalkm) as tot3 from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($y1."-10-01")."' AND '".date($y1."-12-31")."' "); $R3=mysql_fetch_assoc($S3);
$S4=mysql_query("select SUM(Totalkm) as tot4 from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($y2."-01-01")."' AND '".date($y2."-03-31")."' "); $R4=mysql_fetch_assoc($S4);
?>
 
<tr>
 <td colspan="4"><b>Running Km :</b> &nbsp;&nbsp;
  <font color="#003399"><b>[Apr-Jun]:</b></font> <font color="#EB0142"><?=$R1['tot1']?></font>,&nbsp;&nbsp;
  <font color="#003399"><b>[Jul-Sep]:</b></font> <font color="#EB0142"><?=$R2['tot2']?></font>,&nbsp;&nbsp;
  <font color="#003399"><b>[Oct-Dec]:</b></font> <font color="#EB0142"><?=$R3['tot3']?></font>,&nbsp;&nbsp;
  <font color="#003399"><b>[Jan-Mar]:</b></font> <font color="#EB0142"><?=$R4['tot4']?></font>
 </td>
</tr>

<?php   
}
?>
<?php /************* New Close ***********/ ?>


<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl " style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

<?php $sdept=mysql_query("select DepartmentId,GradeValue from hrm_employee_general g inner join hrm_grade gr on g.GradeId=gr.GradeId where EmployeeID=".$exp['CrBy']."",$con2);
$rdept=mysql_fetch_assoc($sdept); ?>

				<tr>
				  <th>Bill_Date</th><?php if($exp['BillDate']!='' && $exp['BillDate']!='0000-00-00' && $exp['BillDate']!='1970-01-01'){$expBill=$exp['BillDate'];}else{$expBill='';} ?>
					<td><input type="text" class="form-control dat" id="BillDate1" name="BillDate" value="<?=$expBill;?>"  required style="width:80px;"></td>
				
					<th scope="row">Vehicle&nbsp;<span class="text-danger">*</span></th>
					<td >
						<label >
							<input type="radio" class="" id="vehicleType2" name="vehicleType" value="2" readonly required  onclick="vehTypeSel(2,<?=$rdept['DepartmentId']?>,<?=$rowe?>,<?=$exp['CrBy']?>,<?=$y1.','.$y2?>)" checked=""><b>2W</b>
						</label>&emsp;
					
						
					</td>
					<td>
						<label >
							<input type="radio" class="" id="vehicleType4" name="vehicleType" value="4" readonly required onclick="vehTypeSel(4,<?=$rdept['DepartmentId']?>,<?=$rowe?>,<?=$exp['CrBy']?>,<?=$y1.','.$y2?>)"><b>4W</b>
						</label>
					</td>
 
<?php 

$sAmt=mysql_query("select SUM(FilledTAmt) as TotalAmt, SUM(Totalkm) as TotalKm from y".$_SESSION['FYearId']."_24_wheeler_entry where Totalkm>0 AND ExpId=".$_REQUEST['expid']);
$rAmt=mysql_fetch_assoc($sAmt); 
if($rAmt['TotalAmt']>0)
{
$qry=mysql_query("update y".$_SESSION['FYearId']."_expenseclaims set FilledTAmt='".$rAmt['TotalAmt']."', VerifyTAmt='".$rAmt['TotalAmt']."', ApprTAmt='".$rAmt['TotalAmt']."', FinancedTAmt='".$rAmt['TotalAmt']."' where ClaimId=7 AND ExpId=".$_REQUEST['expid']." AND ClaimStatus='Filled' and FilledOkay=0");
}

$li=mysql_query("SELECT Travel_TwoWeeKM,Travel_FourWeeKM,FourWElig,CostOfVehicle,WithDriver,AdvanceCom,DateOfEntryPolicy,LessKm,Plan FROM `hrm_employee_eligibility` where EmployeeID=".$exp['CrBy']." order by EligibilityId desc limit 1",$con2);
$lim=mysql_fetch_assoc($li);

$tpkm=$lim['Travel_TwoWeeKM'];
$fpkm=$lim['Travel_FourWeeKM'];

if($tpkm==''){$tpkm=0;}else{$tpkm=$tpkm;} 
if($fpkm==''){$fpkm=0;}else{$fpkm=$fpkm;} 

if($rdept['DepartmentId']!=2)
{
 if($tpkm=='' OR $tpkm=='0' OR $tpkm==0){ $tpkm=3; }
}

?>	

<input type="hidden" id="tw" value="<?=$tpkm?>" /><input type="hidden" id="tw2" value="<?=$tpkm2?>" /><br>
<input type="hidden" id="fw" value="<?=$fpkm?>" /><input type="hidden" id="fw2" value="<?=$fpkm2?>" />

                    <th>Rs/KM</th>
					<td> 
						<input type="text" class="form-control" id="tpkm" name="tpkm" value="<?=$tpkm?>" required style="background-color:#00FF99;">
						<input type="text" class="form-control" id="fpkm" name="fpkm" value="<?=$fpkm?>" required style="display:none;background-color:#FFFF95;">
					</td>	
				
				</tr>

 
  
                <tr><th>Trip Started</th>
                	<th>Trip Ended</th>
                	<th>Vehicle Reg no</th>
                	<th>Dist Trvld Opening</th>
                	<th>Dist Trvld closing</th>
                	<th>Total Km</th>
                	<th>Amount</th>
                	<th></th>
                </tr>
                 
                 <tbody id="d">

                  <?php $i=0; if(count($expf_wheeler)>0){
                    for ($i=0; $i <count($expf_wheeler); $i++) { 
                    
	               		 $JourneyStartDt=date("d-m-Y H:i:s",strtotime($expf_wheeler[$i]['JourneyStartDt']));
		           		 $JourneyEndDt=date("d-m-Y H:i:s",strtotime($expf_wheeler[$i]['JourneyEndDt']));

                        $k = $i.'_';
                      ?>


                   <tr><td><input type="text" class="form-control DateTime" id="JourneyStartDt<?=$k?>" name="JourneyStartDt[]" value="<?=!empty($expf_wheeler[$i]['JourneyStartDt'])?$JourneyStartDt:''?>" title="<?=!empty($expf_wheeler[$i]['JourneyStartDt'])?$JourneyStartDt:''?>" readonly required></td>
                	<td><input type="text" class="form-control DateTime" id="JourneyEndDt<?=$k?>" name="JourneyEndDt[]" value="<?=!empty($expf_wheeler[$i]['JourneyEndDt'])?$JourneyEndDt:''?>" title="<?=!empty($expf_wheeler[$i]['JourneyEndDt'])?$JourneyEndDt:''?>" readonly required></td>
                	<td><input type="text" class="form-control" id="VehicleReg<?=$k?>" name="VehicleReg[]" value="<?=!empty($expf_wheeler[$i]['VehicleReg'])?$expf_wheeler[$i]['VehicleReg']:''?>" required readonly></td>
                	<td><input type="text" class="form-control" id="DistTraOpen<?=$k?>" name="DistTraOpen[]" value="<?=!empty($expf_wheeler[$i]['DistTraOpen'])?$expf_wheeler[$i]['DistTraOpen']:0?>" required readonly></td>
                	<td><input type="text" class="form-control" id="DistTraClose<?=$k?>" name="DistTraClose[]" value="<?=!empty($expf_wheeler[$i]['DistTraClose'])?$expf_wheeler[$i]['DistTraClose']:0?>" onkeyup="cald('<?=$k?>');"  required readonly></td>
                	<td><input type="text" class="form-control" id="totalkm<?=$k?>" name="totalkm[]" value="<?=!empty($expf_wheeler[$i]['Totalkm'])?$expf_wheeler[$i]['Totalkm']:0?>" required readonly></td>
                	<td><input type="text" class="form-control" id="FilledTAmt<?=$k?>" name="FilledTAmt[]" value="<?=!empty($expf_wheeler[$i]['FilledTAmt'])?$expf_wheeler[$i]['FilledTAmt']:''?>" required readonly></td>

                	   <?php //echo $i;
                	   if($i==(count($expf_wheeler)-1)){
                	   // if($i==0){
						   	?>

                	<td style="width:5px !important;"><a style="background-color: #36af2e; font-weight:600; padding: 3px 8px 6px 7px; color: white;" class="btn-sm btn-default add-row">+</a>
							
							<input type="hidden" class="form-control" id="WheelId" name="WheelId[]" value="<?=!empty($expf_wheeler[$i]['WheelId'])?$expf_wheeler[$i]['WheelId']:''?>" >
                	</td>
                <?php }else{?>
                	<td style="width:5px !important;">
                	<!-- 	<a style="background-color: #d63434; font-weight:600; padding: 1px 10px 6px 8px; color:white;" onclick="del(<?=$i?>);" class="btn-sm btn-default cut-row">-</a> -->

                		<input type="hidden" class="form-control" id="WheelId" name="WheelId[]" value="<?=!empty($expf_wheeler[$i]['WheelId'])?$expf_wheeler[$i]['WheelId']:''?>" >
                	</td>
                <?php } ?>
                 </tr>


                   <?php }
                   }else{ ?>

  					<tr id="row_data<?=$i?>"><td><input type="text" class="form-control DateTime" id="JourneyStartDt_" name="JourneyStartDt[]" value="" readonly required></td>
                	<td><input type="text" class="form-control DateTime" id="JourneyEndDt_" name="JourneyEndDt[]" value="" readonly required></td>
                	<td><input type="text" class="form-control" id="VehicleReg" name="VehicleReg[]" value="" required readonly></td>
                	<td><input type="text" class="form-control" id="DistTraOpen" name="DistTraOpen[]" value="0" required readonly></td>
                	<td><input type="text" class="form-control" id="DistTraClose" name="DistTraClose[]" value="0" required readonly onkeyup="caldist(<?=$i?>)" ></td>
                	<td><input type="text" class="form-control" id="totalkm" name="totalkm[]" value="0" required readonly></td>
                	<td><input type="text" class="form-control" id="FilledTAmt" name="FilledTAmt[]" value="0" required readonly></td>
                	<td style="width:5px !important;"><a style="background-color: #36af2e; font-weight:600; padding: 3px 8px 6px 7px; color: white;" class="btn-sm btn-default add-row">+</a>

                      <input type="hidden" class="form-control" id="WheelId" name="WheelId[]" value="" >

                	</td>
                 </tr>

                  <?php } ?> 
                
				 
				 
				 
                
                </tbody>
                <tbody>
				 <tr><td colspan="5" style="text-align:right;"><b>Total:</b>&nbsp;</td>
                  <td><input type="text" class="form-control" style="font-weight:bold;" id="Ttotalkm" value="<?php if($rAmt['TotalKm']==''){echo 0;}else{echo $rAmt['TotalKm'];}?>" readonly><input type="hidden" class="form-control" id="T2totalkm" value="<?php if($rAmt['TotalKm']==''){echo 0;}else{echo $rAmt['TotalKm'];}?>" readonly></td>
                  <td><input type="text" class="form-control" style="font-weight:bold;" id="TFilledTAmt" value="<?php if($rAmt['TotalAmt']==''){echo 0;}else{echo $rAmt['TotalAmt'];}?>" readonly><input type="hidden" class="form-control" id="T2FilledTAmt" value="<?php if($rAmt['TotalAmt']==''){echo 0;}else{echo $rAmt['TotalAmt'];}?>" readonly></td>
                  <td style="width:5px !important;">&nbsp;</td>
                 </tr>
				</tbody> 
				
				 <?php for($i=1; $i<=31; $i++){?>
				  <input type="hidden" id="tkm<?=$i?>" value="0"/>
				  <input type="hidden" id="tamt<?=$i?>" value="0"/>
				 <?php } ?>
				
<!---------------------------------------->
<!---------------------------------------->
<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
			<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
			<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
			<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
			<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
			<?php if($_SESSION['EmpRole']!='M'){ ?>
			<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
				<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
			<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
			<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
				<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
			<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
			<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
				<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
			<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
			<?php }?>


			<?php if($_SESSION['EmpRole']=='M'){ ?>
			<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
			<?php } ?>
									
		  </tr>
		  <?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
<!---------------------------------------->
<!---------------------------------------->
				 

				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
                        <input type="hidden" id="Remark" name="Remark" value="<?=$exp['Remark']?>">
                        <?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draft24Wheeler" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>
						
						<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND ($exp['ClaimStatus']=='Filled' OR $exp['ClaimStatus']=='Verified')) OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>

						<button class="btn btn-sm btn-success" id="Update" name="Update24Wheeler" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>
						<?php } ?>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

				      	

					</td>
				</tr>
				
				<?php
				if($expf['VehicleType']==2){
					?>
					<script type="text/javascript"> $("#vehicleType2").prop('checked','checked'); vehTypeSel(2); caldist();</script>
					<?php
				}elseif($expf['VehicleType']==4){
					?>
					<script type="text/javascript"> $("#vehicleType4").prop('checked','checked'); vehTypeSel(4); caldist();</script>
					<?php
				}else{
					?>
			
					<?php
				}
				?>
			
				<script type="text/javascript">
			
			$(document).ready(function() {

			    jQuery('.DateTime').datetimepicker({
			      format:'d-m-Y H:i:s'
			      });

				var i=1;
				//$("#tpkm").prop('readonly',true);
				//$("#fpkm").prop('readonly',true);
				$("#totalkm").prop('readonly',true);
				$("#FilledTAmt").prop('readonly',true);


			$(".add-row").click(function(){ 

			 var markup = '<tr id="row_data'+i+'"><td><input type="text" class="form-control DateTime" id="JourneyStartDt'+i+'" name="JourneyStartDt[]" value=""  required></td><td><input type="text" class="form-control DateTime" id="JourneyEndDt'+i+'" name="JourneyEndDt[]" value=""  required></td><td><input type="text" class="form-control" id="VehicleReg'+i+'" name="VehicleReg[]" value="" required ></td><td><input type="text" class="form-control" id="DistTraOpen'+i+'" name="DistTraOpen[]" value="0" required ></td><td><input type="text" class="form-control" id="DistTraClose'+i+'" name="DistTraClose[]" value="0" required onkeyup="cald('+i+');" ></td><td><input type="text" class="form-control" id="totalkm'+i+'" name="totalkm[]" value="0" required readonly></td><td><input type="text" class="form-control" id="FilledTAmt'+i+'" name="FilledTAmt[]" value="" required readonly></td><td style="width:5px !important;"><a style="background-color: #d63434; font-weight:600; padding: 1px 10px 6px 8px; color:white;" onclick="del('+i+');" class="btn-sm btn-default cut-row">-</a><input type="hidden" class="form-control" id="WheelId" name="WheelId[]" value="" ></td></tr>';

            $("table tbody#d").append(markup);
                 i++;

             jQuery('.DateTime').datetimepicker({
			      format:'d-m-Y H:i:s'
			      });

        });
		});


              function del(id){
                   $("#row_data"+id).remove();
              }


              function cald(id){ 

              		var opening=parseInt($("#DistTraOpen"+id).val() || 0);
					var closing=parseInt($("#DistTraClose"+id).val() || 0);
					var dist= closing-opening;

					$("#totalkm"+id).val(dist); 

					var tChecked = $('#vehicleType2').prop('checked');
					var fChecked = $('#vehicleType4').prop('checked');

					if(tChecked){
						$("#FilledTAmt"+id).val(dist * $("#tpkm").val());
					}else if(fChecked){
						$("#FilledTAmt"+id).val(dist * $("#fpkm").val());
					} 
					
					CalTot(id);
					
					/***********************/
                     function CalTot(id)
                     {
					  
					  for(var i=1; i<=30; i++)
	                  {
					   if($("#totalkm"+i).val()>0){ $("#tkm"+i).val($("#totalkm"+i).val()); }
					   if($("#FilledTAmt"+i).val()>0){ $("#tamt"+i).val($("#FilledTAmt"+i).val()); }
	                  }
					  
					  var totKm=parseFloat($("#tkm1").val())+parseFloat($("#tkm2").val())+parseFloat($("#tkm3").val())+parseFloat($("#tkm4").val())+parseFloat($("#tkm5").val())+parseFloat($("#tkm6").val())+parseFloat($("#tkm7").val())+parseFloat($("#tkm8").val())+parseFloat($("#tkm9").val())+parseFloat($("#tkm10").val())+parseFloat($("#tkm11").val())+parseFloat($("#tkm12").val())+parseFloat($("#tkm13").val())+parseFloat($("#tkm14").val())+parseFloat($("#tkm15").val())+parseFloat($("#tkm16").val())+parseFloat($("#tkm17").val())+parseFloat($("#tkm18").val())+parseFloat($("#tkm19").val())+parseFloat($("#tkm20").val())+parseFloat($("#tkm21").val())+parseFloat($("#tkm22").val())+parseFloat($("#tkm23").val())+parseFloat($("#tkm24").val())+parseFloat($("#tkm25").val())+parseFloat($("#tkm26").val())+parseFloat($("#tkm27").val())+parseFloat($("#tkm28").val())+parseFloat($("#tkm29").val())+parseFloat($("#tkm30").val());
					  var totAmt=parseFloat($("#tamt1").val())+parseFloat($("#tamt2").val())+parseFloat($("#tamt3").val())+parseFloat($("#tamt4").val())+parseFloat($("#tamt5").val())+parseFloat($("#tamt6").val())+parseFloat($("#tamt7").val())+parseFloat($("#tamt8").val())+parseFloat($("#tamt9").val())+parseFloat($("#tamt10").val())+parseFloat($("#tamt11").val())+parseFloat($("#tamt12").val())+parseFloat($("#tamt13").val())+parseFloat($("#tamt14").val())+parseFloat($("#tamt15").val())+parseFloat($("#tamt16").val())+parseFloat($("#tamt17").val())+parseFloat($("#tamt18").val())+parseFloat($("#tamt19").val())+parseFloat($("#tamt20").val())+parseFloat($("#tamt21").val())+parseFloat($("#tamt22").val())+parseFloat($("#tamt23").val())+parseFloat($("#tamt24").val())+parseFloat($("#tamt25").val())+parseFloat($("#tamt26").val())+parseFloat($("#tamt27").val())+parseFloat($("#tamt28").val())+parseFloat($("#tamt29").val())+parseFloat($("#tamt30").val());
					  
					  if($("#Ttotalkm").val()>0){ $("#Ttotalkm").val(parseFloat($("#T2totalkm").val())+totKm); }
					  else{ $("#Ttotalkm").val(parseFloat($("#totalkm").val())+totKm); }
					  
					  if($("#TFilledTAmt").val()>0){ $("#TFilledTAmt").val(parseFloat($("#T2FilledTAmt").val())+totAmt); }
					  else{ $("#TFilledTAmt").val(parseFloat($("#FilledTAmt").val())+totAmt); }
					  
                     }
	                /***********************/
					
              }
			  
			  
					</script>
		</table>

	
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==8)
{
	/*
	====================================================================================================
			$_POST['claimid']==8      Vehicle Maintenance form 
	====================================================================================================
	*/
?>

<?php


$BillDate = ($exp['BillDate']  != '0000-00-00' && $exp['BillDate']  != '') ? date("d-m-Y",strtotime($exp['BillDate'])) : '';




?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly style="width:80px;"></td>
				
				</tr>
				
				

				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
			<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
			<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
			<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
			<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
			<?php if($_SESSION['EmpRole']!='M'){ ?>
			<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
				<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
			<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
			<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
				<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
			<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
			<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
				<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
			<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
			<?php }?>


			<?php if($_SESSION['EmpRole']=='M'){ ?>
			<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
			<?php } ?>
									
		  </tr>
		  <?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftVehMain" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>
<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>
						<button class="btn btn-sm btn-success" id="Update" name="UpdateVehMain" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>
						<?php } ?>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

				      	

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==9)
{
	/*
	====================================================================================================
			$_POST['claimid']==9      Vehicle Fuel form 
	====================================================================================================
	*/



$BillDate = ($exp['BillDate']  != '0000-00-00' && $exp['BillDate']  != '') ? date("d-m-Y",strtotime($exp['BillDate'])) : '';




?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly style="width:80px;"></td>
				
				</tr>
				
				

				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
			<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
			<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
			<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
			<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
			<?php if($_SESSION['EmpRole']!='M'){ ?>
			<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
				<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
			<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
			<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
				<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
			<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
			<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
				<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
			<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
			<?php }?>


			<?php if($_SESSION['EmpRole']=='M'){ ?>
			<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
			<?php } ?>
									
		  </tr>
		  <?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">


						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftVehFuel" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>

<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>
						<button class="btn btn-sm btn-success" id="Update" name="UpdateVehFuel" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>
						<?php } ?>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>


				      	

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==10)
{
	/*
	====================================================================================================
			$_POST['claimid']==10      RST/OFD form 
	====================================================================================================
	*/



$BillDate = ($exp['BillDate']  != '0000-00-00' && $exp['BillDate']  != '' && $exp['BillDate'] != '') ? date("d-m-Y",strtotime($exp['BillDate'])) : '';
// $arrdate  = ($expf['Arrival']   != '0000-00-00 00:00:00' && $expf['Arrival']   != '') ? date("d-m-Y H:i",strtotime($expf['Arrival'])) : '';
// $depdate  = ($expf['Departure'] != '0000-00-00 00:00:00' && $expf['Departure'] != '') ? date("d-m-Y H:i",strtotime($expf['Departure'])) : '';



?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly style="width:80px;"></td>

				<th scope="row">Crop</th>
				<td>
					<select  class="form-control" name="Crop" required>
						<?php 
						if($expf['Crop']!=''){
						?>
							<option value="<?=$expf['Crop']?>" selected><?=$expf['Crop']?></option>
						<?php
						}else{
							?>
							<option value="">--Select--</option>
							<?php
						}
						?>
						<option value="Vegetable" >Vegetable</option> 
						<option value="Field Crops" >Field Crops</option> 
						
					</select>
				</td>
			
				</tr>
				
				<tr >
				<th scope="row">Crop Details</th>
				<td colspan="3"><input class="form-control dat" id="CropDetails" name="CropDetails" value="<?=$exp['CropDetails']?>" readonly style="width:90%;" required></td>
				</tr>


				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								if(mysql_num_rows($ed)<=0){
								?>
								
								<tr> 
									<td>Rent<input type="hidden" class="form-control" name="fdtitle1" value="Rent" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount1" name="fdamount1" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark1" name="fdremark1" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Trial Operation Exp.<input type="hidden" class="form-control" name="fdtitle2" value="Trial Operation Exp." readonly></td> 
									<td> <input class="form-control text-right" id="fdamount2" name="fdamount2" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark2" name="fdremark2" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Fertilizer - Chemicals<input type="hidden" class="form-control" name="fdtitle3" value="Fertilizer - Chemicals" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount3" name="fdamount3" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark3" name="fdremark3" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Consumable<input type="hidden" class="form-control" name="fdtitle4" value="Consumable" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount4" name="fdamount4" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark4" name="fdremark4" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Miscellaneous<input type="hidden" class="form-control" name="fdtitle5" value="Miscellaneous" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount5" name="fdamount5" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark5" name="fdremark5" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								
								
								<?php /*
								<tr> 
									<td>Labour Charge (LC)<input type="hidden" class="form-control" name="fdtitle1" value="Labour Charge (LC)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount1" name="fdamount1" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark1" name="fdremark1" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Input Charge (IC)<input type="hidden" class="form-control" name="fdtitle2" value="Input Charge (IC)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount2" name="fdamount2" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark2" name="fdremark2" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Miscellaneous (MSC)<input type="hidden" class="form-control" name="fdtitle3" value="Miscellaneous (MSC)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount3" name="fdamount3" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark3" name="fdremark3" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
                                */?>
                                
								<?php
								}
								$i=6; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
									<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
									<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
									<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
									<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
									<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
									<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
									<?php } ?>
															
								</tr>
								<?php $i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftRSTOFD" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>
<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>
						<button class="btn btn-sm btn-success" id="Update" name="UpdateRSTOFD" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>
						<?php } ?>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>


				      	

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==11)
{
	/*
	====================================================================================================
			$_POST['claimid']==11      FD/FV form 
	====================================================================================================
	*/


$BillDate = ($exp['BillDate']  != '0000-00-00' && $exp['BillDate']  != '' ) ? date("d-m-Y",strtotime($exp['BillDate'])) : '';
// $arrdate  = ($expf['Arrival']   != '0000-00-00 00:00:00' && $expf['Arrival']   != '') ? date("d-m-Y H:i",strtotime($expf['Arrival'])) : '';
// $depdate  = ($expf['Departure'] != '0000-00-00 00:00:00' && $expf['Departure'] != '') ? date("d-m-Y H:i",strtotime($expf['Departure'])) : '';



?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly style="width:80px;"></td>
				<th scope="row">Vegetable</th>
				<td>
					<!-- <input type="text" class="form-control" name="Item" value="<?=$expf['Item']?>" readonly> -->
					<select  class="form-control" name="Vegetable" required>
						
						<?php if($expf['Vegetable']!=''){
							?>
							<option value="<?=$expf['Vegetable']?>" selected><?=$expf['Vegetable']?></option>
							<?php
						} ?>
						<option value="">--Select--</option>
						<option value="Chilly">Chilly</option> 
						<option value="Gourds">Gourds</option> 
						<option value="Okra">Okra</option> 
						<option value="Paddy">Paddy</option> 
						<option value="Maize">Maize</option> 
						<option value="Pearl Millet">Pearl Millet</option> 
						<option value="Tomato">Tomato</option> 
						<option value="Brinjal">Brinjal</option>
						<option value="Cucumber">Cucumber</option>
						<option value="Mustard">Mustard</option>
						<option value="Pumpkin">Pumpkin</option>
						<option value="Papaya">Papaya</option>
						<option value="Watermelon">Watermelon</option>
					</select>
				</td>
				
				</tr>

				<tr> 
					<th scope="row">No. of Farmers (FMS)*<input type="hidden" class="form-control" name="fdtitle5" value="No. of Farmers (FMS)*" readonly></th> 
					<td> <input class="form-control text-right" id="fms" name="fms" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" required> </td> 
					<th scope="row">Dealers/Trade Partners (DTP)<input type="hidden" class="form-control" name="fdtitle6" value="Dealers/Trade Partners (DTP)" readonly></th> 
					<td> <input class="form-control text-right" id="dtp" name="dtp" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
					
				</tr>
				

				
				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">


								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								if(mysql_num_rows($ed)<=0){
								?>
								<tr> 
									<td>Hired Vehicle (HVC)<input type="hidden" class="form-control" name="fdtitle1" value="Hired Vehicle (HVC)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount1" name="fdamount1" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark1" name="fdremark1" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>AV Tent (AVT)<input type="hidden" class="form-control" name="fdtitle2" value="AV Tent (AVT)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount2" name="fdamount2" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark2" name="fdremark2" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Snacks (SNK)<input type="hidden" class="form-control" name="fdtitle3" value="Snacks (SNK)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount3" name="fdamount3" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark3" name="fdremark3" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>

								<tr> 
									<td>Other (OTH)<input type="hidden" class="form-control" name="fdtitle4" value="Other (OTH)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount4" name="fdamount4" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark4" name="fdremark4" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								


								<?php
								}
								$i=5; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
									<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
									<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
									<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
									<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
									<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
									<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
									<?php } ?>
															
								</tr>
								<?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftFDFV" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>
<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>
						<button class="btn btn-sm btn-success" id="Update" name="UpdateFDFV" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>
<?php } ?>
						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

				      	

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==12)
{
	/*
	====================================================================================================
			$_POST['claimid']==12      Jeep Campaign form 
	====================================================================================================
	*/


$BillDate = ($exp['BillDate']  != '0000-00-00' && $exp['BillDate']  != '' && $exp['BillDate'] != '') ? date("d-m-Y",strtotime($exp['BillDate'])) : '';
// $arrdate  = ($expf['Arrival']   != '0000-00-00 00:00:00' && $expf['Arrival']   != '') ? date("d-m-Y H:i",strtotime($expf['Arrival'])) : '';
// $depdate  = ($expf['Departure'] != '0000-00-00 00:00:00' && $expf['Departure'] != '') ? date("d-m-Y H:i",strtotime($expf['Departure'])) : '';



?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly style="width:80px;"></td>

				<th scope="row">Vegetable</th>
				<td>
					<select  class="form-control" name="Vegetable" required>
						<?php if($expf['Vegetable']!=''){
							?>
							<option value="<?=$expf['Vegetable']?>" selected><?=$expf['Vegetable']?></option>
							<?php
						} ?>
						<option value="">--Select--</option>
						<option value="Chilly">Chilly</option> 
						<option value="Gourds">Gourds</option> 
						<option value="Okra">Okra</option> 
						<option value="Paddy">Paddy</option> 
						<option value="Maize">Maize</option> 
						<option value="Pearl Millet">Pearl Millet</option> 
						<option value="Tomato">Tomato</option> 
						<option value="Brinjal">Brinjal</option>
						<option value="Cucumber">Cucumber</option>
						<option value="Mustard">Mustard</option>
					</select>
				</td>

				
				</tr>


				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">


								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								if(mysql_num_rows($ed)<=0){
								?>
								<tr> 
									<td>Hired Vehicle (HVC)<input type="hidden" class="form-control" name="fdtitle1" value="Hired Vehicle (HVC)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount1" name="fdamount1" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark1" name="fdremark1" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>AV (AV)<input type="hidden" class="form-control" name="fdtitle2" value="AV (AV)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount2" name="fdamount2" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark2" name="fdremark2" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Other (OTH)<input type="hidden" class="form-control" name="fdtitle3" value="Other (OTH)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount3" name="fdamount3" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark3" name="fdremark3" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>

								


								<?php
								}
								$i=4; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
									<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
									<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
									<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
									<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
									<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
									<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
									<?php } ?>
															
							</tr>
							<?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftJC" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>
<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>
						<button class="btn btn-sm btn-success" id="Update" name="UpdateJC" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>
<?php } ?>
						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

				      	

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==13)
{
	/*
	====================================================================================================
			$_POST['claimid']==13      Dealer Meeting form 
	====================================================================================================
	*/
?>

<?php

$cg=mysql_query("select cgId from claimtype where ClaimId=".$_REQUEST['claimid']);
$cgd=mysql_fetch_assoc($cg);


$e=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaims where ExpId=".$_REQUEST['expid']);
$exp=mysql_fetch_assoc($e);

$ef=mysql_query("select * from y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata where ExpId=".$_REQUEST['expid']);
$expf=mysql_fetch_assoc($ef);

$BillDate = ($exp['BillDate']  != '0000-00-00' && $exp['BillDate']  != '' && $exp['BillDate'] != '') ? date("d-m-Y",strtotime($exp['BillDate'])) : '';
// $arrdate  = ($expf['Arrival']   != '0000-00-00 00:00:00' && $expf['Arrival']   != '') ? date("d-m-Y H:i",strtotime($expf['Arrival'])) : '';
// $depdate  = ($expf['Departure'] != '0000-00-00 00:00:00' && $expf['Departure'] != '') ? date("d-m-Y H:i",strtotime($expf['Departure'])) : '';



?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly style="width:80px;"></td>

				<th scope="row">Crop</th>
				<td>
					<select  class="form-control" name="Crop" required>
						<?php 
						if($expf['Crop']!=''){
						?>
							<option value="<?=$expf['Crop']?>" selected><?=$expf['Crop']?></option>
						<?php
						}else{
							?>
							<option value="">--Select--</option>
							<?php
						}
						?>
						<option value="Vegetable">Vegetable</option> 
						<option value="Field Crop">Field Crop</option> 
						<option value="Paddy">Paddy</option> 
						
					</select>
				</td>
			
				</tr>
				
				<tr>
				<th scope="row">Crop Details</th>
				<td colspan="3"><input class="form-control" id="CropDetails" name="CropDetails" value="<?=$expf['CropDetails']?>" readonly style="width:90%;" required></td>
				</tr>

				

				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">

								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								if(mysql_num_rows($ed)<=0){
								?>
								<tr> 
									<td>Meals (MLS)<input type="hidden" class="form-control" name="fdtitle1" value="Meals (MLS)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount1" name="fdamount1" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark1" name="fdremark1" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Hall/Tent (HTN)<input type="hidden" class="form-control" name="fdtitle2" value="Hall/Tent (HTN)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount2" name="fdamount2" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark2" name="fdremark2" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Gift (GFT)<input type="hidden" class="form-control" name="fdtitle3" value="Gift (GFT)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount3" name="fdamount3" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark3" name="fdremark3" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>

								<tr> 
									<td>AV (AV)<input type="hidden" class="form-control" name="fdtitle4" value="AV (AV)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount4" name="fdamount4" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark4" name="fdremark4" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								<tr> 
									<td>Others (OTH)<input type="hidden" class="form-control" name="fdtitle5" value="Others (OTH)" readonly></td> 
									<td> <input class="form-control text-right" id="fdamount5" name="fdamount5" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> 
									<td> <input class="form-control" id="fdremark5" name="fdremark5" > </td> 
									<td style="width: 20px;">  </td> 
								</tr>
								


								<?php
								}
								$i=6; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
									<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
									<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
									<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
									<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
									<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
									<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
									<?php } ?>
															
								</tr>
								<?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftDm" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>
<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>
						<button class="btn btn-sm btn-success" id="Update" name="UpdateDm" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>
<?php } ?>
						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

				      	

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==14)
{
	/*
	====================================================================================================
			$_POST['claimid']==13      DHQ form 
	====================================================================================================
	*/
?>

<?php

$cg=mysql_query("select cgId from claimtype where ClaimId=".$_REQUEST['claimid']);
$cgd=mysql_fetch_assoc($cg);


$e=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaims where ExpId=".$_REQUEST['expid']);
$exp=mysql_fetch_assoc($e);

$ef=mysql_query("select * from y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata where ExpId=".$_REQUEST['expid']);
$expf=mysql_fetch_assoc($ef);

$BillDate = ($exp['BillDate']  != '0000-00-00' && $exp['BillDate']  != '' && $exp['BillDate'] != '') ? date("d-m-Y",strtotime($exp['BillDate'])) : '';


$seldep=mysql_query("SELECT `DepartmentCode` FROM `hrm_department` hd, `hrm_employee_general` heg where heg.DepartmentId=hd.DepartmentId and heg.EmployeeID=".$exp['CrBy'],$con2);
$depnm=mysql_fetch_assoc($seldep);

if($depnm['DepartmentCode']=='SALES'){
	$column='DA_Inside_HqSal';
}elseif($depnm['DepartmentCode']=='PD'){
	$column='';

}else{
	$column='DA_Inside_Hq';
}



$li=mysql_query("SELECT ".$column." FROM `hrm_employee_eligibility` where EmployeeID=".$exp['CrBy']." order by EligibilityId desc limit 1",$con2);
$lim=mysql_fetch_assoc($li);

$daAmount=$lim[$column];

if($expf['DAAmount']!=0){
	$daAmount=$expf['DAAmount'];
}

?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly style="width:80px;"></td>

				<th scope="row">Amount</th>
				<td>
					<input class="form-control" id="daAmount" name="daAmount" value="<?=$daAmount?>" readonly>
				</td>
			
				</tr>

				
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftDHQ" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>
<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>
						<button class="btn btn-sm btn-success" id="Update" name="UpdateDHQ" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>
<?php } ?>
						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

				      	

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==15)
{
	/*
	====================================================================================================
			$_POST['claimid']==13      DOS form 
	====================================================================================================
	*/
?>

<?php

$cg=mysql_query("select cgId from claimtype where ClaimId=".$_REQUEST['claimid']);
$cgd=mysql_fetch_assoc($cg);


$e=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaims where ExpId=".$_REQUEST['expid']);
$exp=mysql_fetch_assoc($e);

$ef=mysql_query("select * from y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata where ExpId=".$_REQUEST['expid']);
$expf=mysql_fetch_assoc($ef);

$BillDate = ($exp['BillDate']  != '0000-00-00' && $exp['BillDate']  != '' && $exp['BillDate'] != '') ? date("d-m-Y",strtotime($exp['BillDate'])) : '';


$seldep=mysql_query("SELECT `DepartmentCode` FROM `hrm_department` hd, `hrm_employee_general` heg where heg.DepartmentId=hd.DepartmentId and heg.EmployeeID=".$exp['CrBy'],$con2);
$depnm=mysql_fetch_assoc($seldep);

if($depnm['DepartmentCode']=='SALES'){
	$column='DA_Outside_HqSal';
}elseif($depnm['DepartmentCode']=='PD'){
	$column='DA_Outside_HqPD';

}else{
	$column='DA_Outside_Hq';
}



$li=mysql_query("SELECT ".$column." FROM `hrm_employee_eligibility` where EmployeeID=".$exp['CrBy']." order by EligibilityId desc limit 1",$con2);
$lim=mysql_fetch_assoc($li);

$daAmount=$lim[$column];


if($expf['DAAmount']!=0){
	$daAmount=$expf['DAAmount'];
}


?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly style="width:80px;"></td>

				<th scope="row">Amount</th>
				<td>
					<input class="form-control" id="daAmount" name="daAmount" value="<?=$daAmount?>" readonly>
				</td>
			
				</tr>
			
			

				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftDOS" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>
<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>
						<button class="btn btn-sm btn-success" id="Update" name="UpdateDOS" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>
						<?php } ?>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

				      	

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && ($_REQUEST['claimid']==16 || $_REQUEST['claimid']==17))
{
	/*
	====================================================================================================
			$_POST['claimid']==13      Miscellaneous form 
	====================================================================================================
	*/
?>

<?php

$cg=mysql_query("select cgId from claimtype where ClaimId=".$_REQUEST['claimid']);
$cgd=mysql_fetch_assoc($cg);


$e=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaims where ExpId=".$_REQUEST['expid']);
$exp=mysql_fetch_assoc($e);

$ef=mysql_query("select * from y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata where ExpId=".$_REQUEST['expid']);
$expf=mysql_fetch_assoc($ef);

$BillDate = ($exp['BillDate']!= '0000-00-00' && $exp['BillDate']!= '' && $exp['BillDate'] != '') ? date("d-m-Y",strtotime($exp['BillDate'])) : date("d-m-Y",strtotime($expf['BillDate']));
// $arrdate  = ($expf['Arrival']   != '0000-00-00 00:00:00' && $expf['Arrival']   != '') ? date("d-m-Y H:i",strtotime($expf['Arrival'])) : '';
// $depdate  = ($expf['Departure'] != '0000-00-00 00:00:00' && $expf['Departure'] != '') ? date("d-m-Y H:i",strtotime($expf['Departure'])) : '';



?>

<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">

				

				<!-- <tr >
				<th scope="row">Expense Name</th>
				<td colspan="3">
					<input type="text" class="form-control" name="ExpenseName"  value="<?=$exp['ExpenseName']?>" readonly>
				</td> 
				
				</tr> -->
				<tr >
				<th scope="row">Bill Date</th>
				<td><input  class="form-control dat" id="BillDate2" name="BillDate" value="<?=$BillDate?>" readonly style="width:80px;"></td>

				
			
				</tr>

				

				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								
								<tr>
			<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
			<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
			<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
			<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
			<?php if($_SESSION['EmpRole']!='M'){ 
			
			/* onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" */
			?>
			<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
				<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>"  name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" value="<?=$vamt?>" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" <?php if($_SESSION['EmpRole']=='V'){echo 'required';}?> <?=$vastate?>></td>
			<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
			<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
				<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" value="<?=$aamt?>" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" <?php if($_SESSION['EmpRole']=='A'){echo 'required';}?> <?=$aastate?>></td>
			<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
			<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
				<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" value="<?=$famt?>" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" <?php if($_SESSION['EmpRole']=='F'){echo 'required';}?> <?=$fastate?>></td>
			<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
			<?php }?>


			<?php if($_SESSION['EmpRole']=='M'){ ?>
			<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
			<?php } ?>
									
		  </tr>
		  <?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">

						<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftMLSaMISC" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>
<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>
						<button class="btn btn-sm btn-success" id="Update" name="UpdateMLSaMISC" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>
						<?php } ?>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

					</td>
				</tr>
				
		
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
		<br><br>
		<!-- <?php include 'multipleremark.php';?> -->
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && $_REQUEST['claimid']==18)
{
	/*
	====================================================================================================
			$_POST['claimid']==18     Boarding Pass Form
	====================================================================================================
	*/

$JourneyStartDt=($expf['JourneyStartDt']!='0000-00-00' && $expf['JourneyStartDt']!='') ? date("d-m-Y",strtotime($expf['JourneyStartDt'])) : '';
$JourneyEndDt=($expf['JourneyEndDt']!='0000-00-00' && $expf['JourneyEndDt']!='') ? date("d-m-Y",strtotime($expf['JourneyEndDt'])) : '';

?>
<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">
				

				<!-- <tr >
				<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
				<td colspan="3">
					<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" readonly value="<?=$exp['ExpenseName']?>">
				</td>
				
				</tr> -->
			
				
				
				<tr>
					<th scope="row">Trip Started On&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="JourneyStartDt" name="JourneyStartDt" value="<?=$JourneyStartDt?>" readonly required></td>
					<th scope="row">Trip Ended On&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="JourneyEndDt" name="JourneyEndDt" value="<?=$JourneyEndDt?>" readonly required></td>
				</tr>
				
				
				<tr>
				<th scope="row">Journey from&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="JourneyFrom" name="JourneyFrom" readonly value="<?=$expf['JourneyFrom']?>"></td>
				<th scope="row">Journey Upto&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="JourneyUpto" name="JourneyUpto" readonly value="<?=$expf['JourneyUpto']?>"></td>
				</tr>
				
				
				<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --></td>	
				</tr>
				
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
									<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
									<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
									
									<td>
										<input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>>
									</td>
									<td>
										<input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>>
									</td>
									<?php if($_SESSION['EmpRole']!='M'){ ?>
									<td>
										<?php
										if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';}
										?>
										<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>>
									</td>
									
									<td>
										<?php
										if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}
										?>
										<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>>
									</td>
									
									<td>
										<?php
										if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}
										?>
										<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>>
									</td>
									<td>
										<input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>>
									</td>
									<?php }?>


									<?php if($_SESSION['EmpRole']=='M'){ ?>
									<td style="width:20px;text-align:center;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button>
									</td>
									<?php } ?>
									
								</tr>
								
								<?php
								$i++;
								}
								?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>
						

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				
				
				</tr><?php */?>

				<tr>
					<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" id="Remark" name="Remark" value="<?=$exp['Remark']?>">

				      	<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?>
				      	<button class="btn btn-sm btn-info" id="draft" name="draftBoPa" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';">Save as Draft</button>
						<?php } ?>

<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>
						<button class="btn btn-sm btn-success" id="Update" name="UpdateBoPa" readonly style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" >Submit</button>
						<?php } ?>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>


					</td>
				</tr>
			
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>

<?php
}elseif($_REQUEST['act']=='showclaimform' && ($_REQUEST['claimid']==19 || $_REQUEST['claimid']==20 || $_REQUEST['claimid']==21))
{

$cg=mysql_query("select cgId from claimtype where ClaimId=".$_REQUEST['claimid']);
$cgd=mysql_fetch_assoc($cg);


$e=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaims where ExpId=".$_REQUEST['expid']);
$exp=mysql_fetch_assoc($e);


$ef=mysql_query("select * from y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata where ExpId=".$_REQUEST['expid']);
$expf=mysql_fetch_assoc($ef);

$BillDate = ($exp['BillDate']  != '0000-00-00' && $exp['BillDate']  != '' && $exp['BillDate'] != '') ? date("d-m-Y",strtotime($exp['BillDate'])) : '';
 ?>


<tr>
	<td colspan="6" style="width:100%; padding:0px;">
		<form id="claimform" action="<?=$actform;?>" method="post" enctype="multipart/form-data">
			<?php if (isset($expf['did'])) {?>
				<input type="hidden" name="expfid" value="<?=$expf['did']?>">
			<?php } ?>
		<table class="table-bordered table-sm claimtable w-100 paddedtbl" style="width:100%;padding:0px;" cellspacing="0" cellpadding="0">
				                    

                    <tr>
                     <td><b>Bill Date</b></td>	
                     <td><b>Amount</b></td>	
                     <td><b>Remarks</b></td>	
                    </tr>


                    <tr>
                    <td>		
					<div class="form-group">
			            <label for="date" class="sr-only">Date</label>
			            <input id="claimdate" class="form-control_manual form-control input-group-lg dat reg_name" type="text" name="mclaim_date" title="Date" placeholder="Date" value="<?=$BillDate?>" required />
			        </div>
                     </td>

                    <td>
				    <div class="form-group">
				        <label for="amount" class="sr-only">Amount</label>
				        <input id="amount" class="form-control_manual form-control input-group-lg" type="text" autocapitalize='off' name="mamount" value="<?=$exp['FilledTAmt']?>" title="Enter Amount" placeholder="Amount" required/>
				    </div></td>


                    <td>
				    <div class="form-group">
				        <label for="remarks" class="sr-only">Remarks</label>
				       <input id="remarks" class="form-control_manual form-control input-group-lg" type="remarks" name="mremarks"  value="<?=$exp['Remark']?>" title="Enter Remarks" placeholder="Remarks"/>
				    </div></td>
                     </tr>


                    <tr>
                  
				  
<!---------------------------------------->
<!---------------------------------------->
<tr>
					<th scope="row"  colspan="2" style="color:#0080FF;">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted"><?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?> <?php } ?></span></th>
					<th scope="row" style="color:#0080FF;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>">Limit</th>
					<td><span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts --> </td>	
				</tr>
				<tr>
					<td colspan="4">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 30%;">Title</th>
								
								<th scope="row" class="text-center table-active"  style="">Amount</th>
								<th scope="row" class="text-center table-active"  style="">Remark </th>
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<th scope="row" class="text-center table-active"  style="">Verified Amt</th>
								<th scope="row" class="text-center table-active"  style="">Verifier Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Approver Amt</th>
								<th scope="row" class="text-center table-active"  style="">Approver Remark </th>
								
								<th scope="row" class="text-center table-active"  style="">Finance Amt</th>
								<th scope="row" class="text-center table-active"  style="">Finance Remark </th>

								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								<?php } ?>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<?php
								$ed=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_REQUEST['expid']);
								$i=1; $amt=0; $vamt=0; $aamt=0; $famt=0;
								

								while($edets=mysql_fetch_assoc($ed)){

								$amt+=$edets['Amount'];
								$vamt+=$edets['VerifierEditAmount'];
								$aamt+=$edets['ApproverEditAmount'];
								$famt+=$edets['FinanceEditAmount'];

									
								?>
								<tr>
			<td><input class="form-control" name="fdtitle<?=$i?>" value="<?=$edets['Title']?>" <?=$title?>>
			<input class="form-control" name="fdid<?=$i?>" type="hidden" value="<?=$edets['ecdId']?>" <?=$title?>></td>
			<td><input class="form-control text-right" id="fdamount<?=$i?>" name="fdamount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" value="<?=$edets['Amount']?>" required <?=$astate?>></td>
			<td><input class="form-control" id="fdremark<?=$i?>" name="fdremark<?=$i?>" value="<?=$edets['Remark']?>" <?=$astate?>></td>
			<?php if($_SESSION['EmpRole']!='M'){ ?>
			<td><?php if($edets['VerifierEditAmount']!=0){$vamt=$edets['VerifierEditAmount'];}else{$vamt='';} ?>
				<input class="form-control text-right" id="fdVerifierEditAmount<?=$i?>" name="fdVerifierEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calvatotal(this);" value="<?=$vamt?>" <?php if($_SESSION['EmpRole']=='V'){ echo 'required'; } ?> <?=$vastate?>></td>
			<td><input class="form-control text-right" id="fdVerifierRemark<?=$i?>" name="fdVerifierRemark<?=$i?>" value="<?=$edets['VerifierRemark']?>" <?=$vastate?>></td>
			<td><?php if($edets['ApproverEditAmount']!=0){$aamt=$edets['ApproverEditAmount'];}else{$aamt='';}?>
				<input class="form-control text-right" id="fdApproverEditAmount<?=$i?>" name="fdApproverEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calaatotal(this);" value="<?=$aamt?>" <?php if($_SESSION['EmpRole']=='A'){ echo 'required'; } ?> <?=$aastate?>></td>
			<td><input class="form-control text-right" id="fdApproverRemark<?=$i?>" name="fdApproverRemark<?=$i?>" value="<?=$edets['ApproverRemark']?>" <?=$aastate?>></td>
			<td><?php if($edets['FinanceEditAmount']!=0){$famt=$edets['FinanceEditAmount'];}else{$famt='';}?>
				<input class="form-control text-right" id="fdFinanceEditAmount<?=$i?>" name="fdFinanceEditAmount<?=$i?>" onkeypress="return isNumber(event)" onkeyup="checkrange(this,'<?=$edets['Amount']?>');calfatotal(this);" value="<?=$famt?>" <?php if($_SESSION['EmpRole']=='F'){ echo 'required'; } ?> <?=$fastate?>></td>
			<td><input class="form-control text-right" id="fdFinanceRemark<?=$i?>" name="fdFinanceRemark<?=$i?>" value="<?=$edets['FinanceRemark']?>" <?=$fastate?>></td>
			<?php }?>


			<?php if($_SESSION['EmpRole']=='M'){ ?>
			<td  style="width: 20px;"><button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)" style="display: none;"><i class="fa fa-times fa-sm" aria-hidden="true"></i></button></td>
			<?php } ?>
									
		  </tr>
		  <?php	$i++; } ?>
							</tbody>
							<tr>
								<th scope="row" class="text-right table-active">Total</th>
								
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input  class="form-control text-right" id="Amount" name="Amount" style="background-color:<?=$Amount?>;" value="<?=$exp['FilledTAmt']?>"  readonly required >
									<span id="limitspan" style="width:50px;<?php if($_SESSION['EmpRole']=='M'){echo "display:none;";}?>"></span> <input id="EmpRole" type="hidden" value="<?=$_SESSION['EmpRole']?>" /> <!-- this input been added here just to control the checking of limit when mediator/data entry person entering the amounts -->
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Filled", "Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='M'){ ?>
									<input class="form-control" readonly value="<?=$exp['Remark']?>">
									<?php } ?>
								</td>

								
								<?php if($_SESSION['EmpRole']!='M'){ ?>
								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control text-right" id="VerifierEditAmount" name="VerifierEditAmount" style="background-color:<?=$VerifierEditAmount?>;" value="<?=$exp['VerifyTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Verified", "Approved","Financed")) || $_SESSION['EmpRole']=='V'){ ?>
									<input class="form-control" readonly value="<?=$exp['VerifyTRemark']?>">
									<?php } ?>	
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
									<input class="form-control text-right" id="ApproverEditAmount" name="ApproverEditAmount" style="background-color:<?=$ApproverEditAmount?>;" value="<?=$exp['ApprTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Approved","Financed")) || $_SESSION['EmpRole']=='A'){ ?>
										<input class="form-control" readonly value="<?=$exp['ApprTRemark']?>">
									<?php } ?>
								</td>
								

								<td class="table-active">
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
									<input class="form-control text-right" id="FinanceEditAmount" name="FinanceEditAmount" style="background-color:<?=$FinanceEditAmount?>;" value="<?=$exp['FinancedTAmt']?>" readonly required >
									<?php } ?>
								</td>
								<td style="width: 20px;" class="table-active" >
									<?php if(in_array($exp['ClaimStatus'], array("Financed")) || $_SESSION['EmpRole']=='F'){ ?>
										<input class="form-control" readonly value="<?=$exp['FinancedTRemark']?>">
									<?php } ?>
								</td>
								<?php } ?>
								

							</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="<?=$i?>">
						<?php if($_SESSION['EmpRole']=='M'){ ?>
									
						
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;display: none;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>

						<?php } ?>
					</td>
				</tr>

				<?php /*?><tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="3" name="Remark" readonly><?=$exp['Remark']?></textarea></td>
				</tr><?php */?>
<!---------------------------------------->
<!---------------------------------------->				  

                   	<td colspan="4">
						<input type="hidden" name="expid" value="<?=$_REQUEST['expid']?>">
						<input type="hidden" name="Remark" value="<?=$exp['Remark']?>">		

				      	<?php
						//if(($exp['ClaimAtStep']!='1' || $exp['FilledOkay']==2 || $exp['ClaimStatus']=='Draft') && $_SESSION['EmpRole']!='E'){
				      	?>
                        <?php if($_SESSION['EmpRole']=='M'){ ?>
				      	<button class="btn btn-sm btn-info draft2" id="draft2" name="draftManual" style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" onclick="document.getElementById('savetype').value='Draft';" disabled>Save as Draft</button>
						<?php } ?>
<?php if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND $exp['ClaimStatus']=='Filled') OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		   { ?>
						<button class="btn btn-sm btn-success" id="Update" name="UpdateManual" style="<?=$_REQUEST['upbtndis']?>width:50%; height:25px;display: inline-block;float:left;" disabled>Submit</button>
						<?php } ?>

						<input type="hidden" id="savetype" name="savetype" value="">
						<?php //} ?>

					</td>

				</tr>
					
			
		</table>
		<!--<br><br>
		<span class="text-danger">*</span> Required-->
		</form>
	</td>
</tr>
<?php
}
?>
<script type="text/javascript" src="js/claim.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
     
    $('.dat').datetimepicker({format:'d-m-Y'});
        $('#JourneyStartDt1').datetimepicker({format:'d-m-Y H:i'});

    //here closing the JourneyStartDt datetimepicker on date change 

});


	$('.dat').on('click', function(){
	    $(this).datetimepicker('hide');
         
           var FYearId = '<?=$_SESSION['FYearId']?>';
           var expid= '<?=$_REQUEST['expid']?>';
           var BillDate = $(this).val();
           
           
            if(BillDate!='' && BillDate!='0000-00-00'){
            var postdata = {'FYearId':FYearId, 'expid':expid, 'BillDate':BillDate};
           
            //alert('FYearId:'+FYearId+'expid:'+expid+'BillDate:'+BillDate);

            $.ajax({
            url: 'post_ajax.php',	
            type:'post',
            data: postdata,
            dataType: 'json',
            success:function(data){ 
               if(data.status=='success'){
                  	alert("This Month Entry has been submitted and closed.");
                         window.location.reload();
                   }
                   
            }    
        });
           }

	}); 
</script>