

<?php


if($_POST['act']=='getmonthselect'){
	session_start();
	include 'config.php';
	
	$monthsubmitted = array();
	$sm=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and `Year`=".$_POST['year']." and `Status`='Submitted' order by Month asc");
	while($smlist=mysql_fetch_assoc($sm)){
		$monthsubmitted[]=date('F', mktime(0,0,0,$smlist['Month'], 1, date('Y')));
	}
	$monthclosed = array();
	$sm=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and `Year`=".$_POST['year']." and `Status`='Closed' order by Month asc");
	while($smlist=mysql_fetch_assoc($sm)){
		$monthclosed[]=date('F', mktime(0,0,0,$smlist['Month'], 1, date('Y')));
	}
	$closedlast=end($monthclosed);
	$closedlastnum=date('m',strtotime($closedlast));
	$n=1;
	if($closedlastnum==01){$n=3;}
	$nextMonth=$closedlastnum+$n;
	$claimMonth=date('F', mktime(0,0,0,$nextMonth, 1, date('Y')));

	if($claimMonth==''){$claimMonth="April";}


	?>

	<select class=" claimheadsel form-control pull-left" id="claimMonth" name="claimMonth" required style="width: 105px;"  form="claimform" onchange="disfClaim()" >
	    <option value="">Select</option>

		<?php
		$m=4;
		$em=12;
		while ($m<=$em) {
			$month = date('F', mktime(0,0,0,$m, 1, date('Y')));
			?>
			<option value="<?=$month?>" 
				<?php 
				if(in_array($month, $monthclosed)){
					echo 'disabled style="background-color:#999999;color:white;"';
				}elseif(!in_array($month, $monthsubmitted) && $month!=$claimMonth){
					echo 'disabled style="background-color:#e0e0d1;"';
				}elseif($month==$claimMonth){
					echo 'style="font-weight:bold;"';
				}
				?>
										
				>
			<?=$month?>
			</option>
			<?php
			if($m==12){ $m=0; $em=3; } 
			$m++;
		}
		?>
	</select>

<?php
}

if($_POST['act']=='getclaimform' && $_POST['claimid']==1){
?>
<tr>
	<td colspan="6">
		<form id="claimform" action="saveclaim.php" method="post" enctype="multipart/form-data">
		<table class="table-bordered table-sm claimtable w-100 paddedtbl ">

				<tr >
				<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
				<td colspan="3">
					<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" required>
				</td>
				
				</tr>
			
				<tr >
				<th scope="row">Name of Hotel&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="HotelName" name="HotelName" required></td>
				<th scope="row">Address of Hotel&nbsp;<span class="text-danger">*</span></th>
				<td><textarea class="form-control" rows="2" id="HotelAddress" name="HotelAddress" required></textarea></td>
				</tr>
				
				<tr >
				<th scope="row">Billing Name &nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="BillingName" name="BillingName" required></td>
				<th scope="row">Billing address&nbsp;<span class="text-danger">*</span></th>
				<td><textarea class="form-control" rows="2" id="BillingAddress" name="BillingAddress" required></textarea></td>
				</tr>
				
				<tr>
				<th scope="row">Bill No. &nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="BillNo" name="BillNo" required></td>
				<th scope="row">Bill date&nbsp;<span class="text-danger">*</span></th>
				<td><input  class="form-control" id="BillDate" name="BillDate" required autocomplete="off"></td>
				</tr>
				
				<tr>
				<th scope="row">Arrival date with time&nbsp;<span class="text-danger">*</span></th>
				<td>
					<input id="arrdate" name="arrdate" required class="form-control" autocomplete="off">
				</td>
				<th scope="row">Departure date with time&nbsp;<span class="text-danger">*</span></th>
				<td>
					<input id="depdate" name="depdate" required class="form-control" autocomplete="off" >
				</td>
				</tr>
				
				<tr>
				<th scope="row">Duration of stay&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="StayDuration" name="StayDuration" required></td>
				<th scope="row">Room rate / Room type&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="RoomRateType" name="RoomRateType" required></td>
				</tr>
				
				<tr>
				<th scope="row">Meal Plan (AP, MAP, EP, CP)</th>
				<td><input type="text" class="form-control" id="MealPlan" name="MealPlan" ></td>
				<th scope="row">No. of pax (Single, Double)&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="NoOfPAX" name="NoOfPAX" required></td>
				</tr>
				
				<tr>
				<th scope="row">GST No. /Tax Rate&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="GST" name="GST" required></td>
				
				<th scope="">Billing instruction (direct /bill to Company)</th>
				<td><input type="text" class="form-control" id="BillIns" name="BillIns" ></td>
				</tr>

				<tr>
					<th scope="row">Fare Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted">(Fare / Parking / Toll tax)</span></th>
					<td colspan="3">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 70%;">Reason</th>
								<th scope="row" class="text-center table-active"  style="width: 25%;">Amount</th>
								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<tr>
									<td><input class="form-control" name="fdtitle1" style=""></td>
									<td>
										<input class="form-control text-right" id="fdamount1" name="fdamount1" style="" onkeypress="return isNumber(event)" onkeyup="caltotal()" required>
										
									</td>
									<td  style="width: 20px;">
										<button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)">
											<i class="fa fa-times fa-sm" aria-hidden="true"></i>
										</button>
									</td>
								</tr>
								
							</tbody>
								<tr>
									<th scope="row" class="text-right table-active">Total</th>
									<td>
										<input class="form-control text-right" id="Amount" name="Amount" style="background-color:transparent;" readonly required >
									</td>
									<td  style="width: 20px;" class="text-center table-active" >
										
									</td>
								</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="1">
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>
					</td>
				</tr>
				

				<tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" ></textarea></td>
				
				
				</tr>

				<tr>
					<td colspan="4">
						<button class="btn btn-sm btn-success form-control" id="submit" name="submitlodging" required disabled >Submit</button>
					</td>
				</tr>
			
		</table>
		<br><br>
		<span class="text-danger">*</span> Required
		</form>
	</td>
</tr>

<?php
}elseif($_POST['act']=='getclaimform' && $_POST['claimid']==2){
?>
<tr>
	<td colspan="6">
		<form id="claimform" action="saveclaim.php" method="post" enctype="multipart/form-data">
		<table class="table-bordered table-sm claimtable w-100 paddedtbl ">

				<tr >
				<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
				<td colspan="3">
					<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" required>
				</td>
				
				</tr>
			
				<tr >
					<th scope="row">Mode&nbsp;<span class="text-danger">*</span></th>
					<td>
						<select class="form-control" id="Mode" name="Mode" required onchange="changemode(this.value)">
							<option value="Rail">Rail</option>
							<option value="Bus">Bus</option>
						</select>
					</td>
					<th scope="row"><span id="modenm">Train</span> Name&nbsp;<span class="text-danger">*</span></th>
					<td>
						<input type="text" class="form-control" id="TrainBusName" name="TrainBusName" required>
					</td>
				</tr>
				
				<tr >
				<th scope="row">Quota&nbsp;<span class="text-danger">*</span></th>
				<td>
					<select class="form-control" id="Quota" required name="Quota">
						<option value="GN">GN</option>
						<option value="Tatkal">Tatkal</option>
						<option value="Bus">Pr. Tatkal</option>
					</select>
				</td>
				<th scope="row">Class&nbsp;<span class="text-danger">*</span></th>
				<td>
					
					<select class="form-control" id="Class" required name="Class">
						<option value="CC">CC</option>
						<option value="SL">SL</option>
						<option value="AC">AC</option>
					</select>
				</td>
			
				</tr>
				
				<tr>
				<th scope="row">Booking Date&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="BookingDate" name="BookingDate" required></td>
				<th scope="row">Journey Date&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="rbJourneyStartDt" name="rbJourneyStartDt" required autocomplete="off"></td>
				</tr>
				
				
				<tr>
				<th scope="row">Journey from&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="JourneyFrom" name="JourneyFrom" required></td>
				<th scope="row">Journey Upto&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="JourneyUpto" name="JourneyUpto" required></td>
				</tr>
				
				<tr>
				<th scope="row">Passenger detail</th>
				<td><input type="text" class="form-control" id="PassengerDetail" name="PassengerDetail" ></td>
				<th scope="row">Booking Status&nbsp;<span class="text-danger">*</span></th>
				<td>
					<select class="form-control" id="BookingStatus" required name="BookingStatus">
						<option value="Waiting">Waiting</option>
						<option value="Confirmed">Confirmed</option>
					</select>
				</td>
				</tr>
				
				<tr>
				<th scope="row">Travel Insurance&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="TravelInsurance" name="TravelInsurance" required></td>
				
				<th scope="">Total Fare</th>
				<td><input type="text" class="form-control" id="TotalFare" name="TotalFare" ></td>
				</tr>

				<tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" ></textarea></td>
				
				
				</tr>

				<tr>
					<td colspan="4">
						<button class="btn btn-sm btn-success form-control" id="submit" name="submitrailbusexp" required disabled >Submit</button>
					</td>
				</tr>
			
		</table>
		<br><br>
		<span class="text-danger">*</span> Required
		</form>
	</td>
</tr>

<?php
}elseif($_POST['act']=='getclaimform' && $_POST['claimid']==3){
?>
<tr>
	<td colspan="6">
		<form id="claimform" action="saveclaim.php" method="post" enctype="multipart/form-data">
		<table class="table-bordered table-sm claimtable w-100 paddedtbl ">

				<tr >
				<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
				<td colspan="3">
					<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" required>
				</td>
				
				</tr>
			


				<tr>
				<th scope="row">Trip Started On&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="JourneyStartDt" name="JourneyStartDt" required></td>
				<th scope="row">Trip Ended On&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="JourneyEndDt" name="JourneyEndDt" required></td>
				</tr>

				<tr >
					<th scope="row">Mode of Conveyance&nbsp;<span class="text-danger">*</span></th>
					<td>
						<select class="form-control" id="Mode" name="Mode" required >
							<option value="Sharing Taxi /Cab">Sharing Taxi /Cab</option>
							
						</select>
					</td>
					
				</tr>

				<tr>
					<th scope="row">Fare Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted">(Fare / Parking / Toll tax)</span></th>
					<td colspan="3">
						<div class="table-responsive-xl">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active"  style="width: 70%;">Reason</th>
								<th scope="row" class="text-center table-active"  style="width: 25%;">Amount</th>
								<th scope="row" class="text-center table-active"  style="width: 5%;"></th>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<tr>
									<td><input class="form-control" name="fdtitle1" style=""></td>
									<td>
										<input class="form-control text-right" id="fdamount1" name="fdamount1" style="" onkeypress="return isNumber(event)" onkeyup="caltotal()" required>
										
									</td>
									<td  style="width: 20px;">
										<button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)">
											<i class="fa fa-times fa-sm" aria-hidden="true"></i>
										</button>
									</td>
								</tr>
								
							</tbody>
							<tr>
									<th scope="row" class="text-right table-active">Total</th>
									<td>
										<input class="form-control text-right" id="Amount" name="Amount" style="background-color:transparent;" readonly required >

										
									</td>
									<td  style="width: 20px;" class="text-center table-active" >
										
									</td>
								</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="1">
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>
					</td>
				</tr>
				

				
				

				<tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" ></textarea></td>
				
				
				</tr>

				<tr>
					<td colspan="4">
						<button class="btn btn-sm btn-success form-control" id="submit" name="submitlocal" required disabled >Submit</button>
					</td>
				</tr>
			
		</table>
		<br><br>
		<span class="text-danger">*</span> Required
		</form>
	</td>
</tr>

<?php
}elseif($_POST['act']=='getclaimform' && $_POST['claimid']==4){
?>
<tr>
	<td colspan="6">
		<form id="claimform" action="saveclaim.php" method="post" enctype="multipart/form-data">
		<table class="table-bordered table-sm claimtable w-100 paddedtbl ">

				<tr >
				<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
				<td colspan="3">
					<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" required>
				</td>
				
				</tr>


				<tr>
				<th scope="row">Travel Agency name&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="AgencyName" name="AgencyName" required></td>
				<th scope="row">Travel Agency address&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="AgencyAddress" name="AgencyAddress" required></td>
				</tr>

				<tr>
				<th scope="row">Billing name&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="BillingName" name="BillingName" required></td>
				<th scope="row">Billing address&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="BillingAddress" name="BillingAddress" required></td>
				</tr>

				<tr>
				<th scope="row">Invoice number&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="Invoice" name="Invoice" required></td>
				<th scope="row">Date of Travel&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="JourneyStartDt" name="JourneyEndDt" required></td>
				</tr>

				<tr>
				<th scope="row">Vehicle class&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="class" name="class" required></td>
				<th scope="row">Registration Number&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="registrationno" name="registrationno" required></td>
				</tr>

				<tr>
				<th scope="row">Basis of Charges on Daily basis &nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="chargesondailybasis" name="chargesondailybasis" required></td>
				<th scope="row">Basis of Charges on Km Basis&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="chargesonkmbasis" name="chargesonkmbasis" required></td>
				</tr>

				<tr>
				<th scope="row">Distance Travelled (Opening Reading)&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="distopen" name="distopen" required></td>
				<th scope="row">Distance Travelled (Closing Reading)&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="distopen" name="distopen" required></td>
				</tr>



				

				<tr >
					<th scope="row">Mode of Conveyance&nbsp;<span class="text-danger">*</span></th>
					<td>
						<select class="form-control" id="Mode" name="Mode" required >
							<option value="Sharing Taxi /Cab">Sharing Taxi /Cab</option>
							
						</select>
					</td>
					
				</tr>
				
				<tr>
					<th scope="row">Fare Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted">(Fare / Parking / Toll tax)</span></th>
					<td colspan="3">
						<div class="table-responsive">
						<table class="table table-sm faredettbl" >
							<thead>
								<tr class="">
								<th scope="row" class="text-center table-active" style="width: 70%;">Reason</th>
								<th scope="row" class="text-center table-active" style="width: 25%;">Amount</th>
								<th scope="row" class="text-center table-active" style="width: 5%;"></th>
								</tr>
							</thead>
							<tbody id="faredettbody">
								<tr>
									<td><input class="form-control" name="fdtitle1" style=""></td>
									<td>
										<input class="form-control text-right" id="fdamount1" name="fdamount1" style="" onkeypress="return isNumber(event)" onkeyup="caltotal()" required>
										
									</td>
									<td  style="width: 20px;">
										<button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)">
											<i class="fa fa-times fa-sm" aria-hidden="true"></i>
										</button>
									</td>
								</tr>
								
							</tbody>
								<tr>
									<th scope="row" class="text-right table-active">Total</th>
									<td>
										<input class="form-control text-right" id="Amount" name="Amount" style="background-color:transparent;" readonly required >

										
									</td>
									<td style="width: 20px;" class="text-center table-active" >
										
									</td>
								</tr>
						</table>
						
						</div>
						<input type="hidden" id="fdtcount" name="fdtcount" value="1">
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;" onclick="addfaredet()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>
					</td>
				</tr>
				
				

				<tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" ></textarea></td>
				
				
				</tr>

				<tr>
					<td colspan="4">
						<button class="btn btn-sm btn-success form-control" id="submit" name="submithiredveh" required disabled >Submit</button>
					</td>
				</tr>
			
		</table>
		<br><br>
		<span class="text-danger">*</span> Required
		</form>
	</td>
</tr>

<?php
}
?>
<script type="text/javascript" src="js/claim.js"></script>
