

<?php
if($_POST['act']=='verifyClaim'){
	session_start();
	include 'config.php';

	// if($_POST['vtamt']){
	// 	$up=mysql_query("UPDATE y".$_SESSION['FYearId']."_y".$_SESSION['FYearId']."_expenseclaimsdetails SET VerifierEditAmount='".$_POST['vtamt']."', VerifierRemark='".$_POST['vtamt']."' where ExpId='".$_POST['expid']."'");
	// }

	if(isset($_POST['verifiedtremark'])){
		$r=$_POST['verifiedtremark'];
		//mysql_query("UPDATE y".$_SESSION['FYearId']."_y".$_SESSION['FYearId']."_expenseclaimsdetails SET VerifierEditAmount='', VerifierRemark='' where ExpId='".$_POST['expid']."'");
	}else{
		$r='';	
	}
	
	$up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET ClaimStatus='Verified', ClaimAtStep=3, VerifyBy='".$_SESSION['EmployeeID']."', `VerifyTAmt`='".$_POST['vtamt']."',  `VerifyTRemark`='".$r."',  `VerifyDate`='".date("Y-m-d")."',`ApprTAmt`='".$_POST['vtamt']."',`FinancedTAmt`='".$_POST['vtamt']."' where ExpId='".$_POST['expid']."'");


	if($up){
		echo 'verified';
	}

}elseif($_POST['act']=='approveClaim'){
	session_start();
	include 'config.php';

	if(isset($_POST['apprtremark'])){
		$r=$_POST['apprtremark'];
		//mysql_query("UPDATE y".$_SESSION['FYearId']."_y".$_SESSION['FYearId']."_expenseclaimsdetails SET ApproverEditAmount='', ApproverRemark='' where ExpId='".$_POST['expid']."'");
	}else{
		$r='';
	}
	$up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET ClaimStatus='Approved', ClaimAtStep=4, ApprBy='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['atamt']."',  `ApprTRemark`='".$r."',  `ApprDate`='".date("Y-m-d")."',`FinancedTAmt`='".$_POST['atamt']."' where ExpId='".$_POST['expid']."'");
	
	if($up){
		echo 'approved';
	}

}elseif($_POST['act']=='financeClaim'){
	session_start();
	include 'config.php';

	if(isset($_POST['financetremark'])){
		$r=$_POST['financetremark'];
		//mysql_query("UPDATE y".$_SESSION['FYearId']."_y".$_SESSION['FYearId']."_expenseclaimsdetails SET FinanceEditAmount='', FinanceRemark='' where ExpId='".$_POST['expid']."'");
	}else{
		$r='';
	}
    
	if($_POST['t']=='A')
	{
	 $up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET ClaimStatus='Financed', ClaimAtStep=5, FinancedBy='".$_SESSION['EmployeeID']."', `FinancedTAmt`='".$_POST['ftamt']."',  `FinancedTRemark`='".$r."',  `FinancedDate`='".date("Y-m-d")."' where ExpId='".$_POST['expid']."'");
	 if($up){ echo 'financed'; }
	}
	elseif($_POST['t']=='R')
	{
	 $up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET ClaimStatus='Rej_Fin', ClaimAtStep=5, FinancedBy='".$_SESSION['EmployeeID']."', `FinancedTAmt`='0',  `FinancedTRemark`='".$r."',  `FinancedDate`='".date("Y-m-d")."' where ExpId='".$_POST['expid']."'");
	 if($up){ echo 'Rejected'; }
	}
	
	
}

if($_POST['act']=='getclaimform' && $_POST['claimid']==1){
?>
<tr>
	<td colspan="6">
		<form id="claimform" action="saveclaim.php" method="post" enctype="multipart/form-data">
		
		<table class="table-bordered table-sm claimtable w-100 paddedtbl ">
			<tr>
				<td colspan="4">
					<button class="btn btn-sm btn-success form-control" id="submit" name="submitlodging" required disabled >Submit</button>
				</td>
			</tr>
		</table>
		<br><br>
		
		</form>
	</td>
</tr>

<?php
}elseif($_POST['act']=='getclaimform' && $_POST['claimid']==7){
?>
<tr>
	<td colspan="17">
		<form id="claimform" action="saveclaim.php" method="post" enctype="multipart/form-data">
		<table class="table-bordered table-sm claimtable w-100 paddedtbl ">

				<!-- <tr >
				<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
				<td colspan="3">
					<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" required>
				</td>
				
				</tr> -->
			

				<tr>
				<th scope="row">Trip Started On&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="JourneyStartDt" name="JourneyStartDt" required></td>
				<th scope="row">Trip Ended On&nbsp;<span class="text-danger">*</span></th>
				<td><input type="text" class="form-control" id="JourneyEndDt" name="JourneyEndDt" required></td>
				</tr>

				<tr>
					
					<th colspan="2" scope="row">Vehicle class /Registration Number&nbsp;<span class="text-danger">*</span></th>
					<td colspan="2"><input type="text" class="form-control" id="VehicleReg" name="VehicleReg" value="" required ></td>
				</tr>


				<tr>
					<th scope="row">Distance_Travelled (Opening_Reading)&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="DistTraOpen" name="DistTraOpen" value="" required ></td>
					<th scope="row">Distance_Travelled (Closing_Reading)&nbsp;<span class="text-danger">*</span></th>
					<td><input type="text" class="form-control" id="DistTraClose" name="DistTraClose" value="" required ></td>
				</tr>


				
				<tr>
					<th scope="row">Amount Detail&nbsp;<span class="text-danger">*</span> <span class="text-muted">(Fare / Parking / Toll tax)</span></th>
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
										<input class="form-control text-right" id="fdamount1" name="fdamount1" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" required>
										
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
						<button  type="button" class="btn btn-sm btn-primary pull-right" style="margin-top: -18px;" onclick="addfaredetaa()">
							<i class="fa fa-plus fa-sm" aria-hidden="true"></i> Add
						</button>
						<input type="hidden" id="cid" value="1">
					</td>
				</tr>

				<tr>
				<th scope="row">Remark</th>
				<td colspan="3"><textarea class="form-control" rows="2" id="Remark" name="Remark" ></textarea></td>
				
				
				</tr>

				<tr>
					<td colspan="4">
						<button class="btn btn-sm btn-success form-control" id="submit" name="submittravelclaim" required style="height:30px;" >Submit</button>
					</td>
				</tr>
			
		</table>
		<br><br>
		<span class="text-danger">*</span> Required
		</form>
	</td>
</tr>

<?php
}elseif($_POST['act']=='getclaimform' && $_POST['claimid']==8){
?>
<tr>
	 <td style=" text-align:center;width:100%;" colspan="15">
	   <div id="" style="position: relative; width:100%;">
		 <center>
		  <button id="removeupload" class="btn btn-danger" onclick="showuploadbtn()" style="position: absolute;float: right;right: 0px;display: none;"><i class="fa fa-times-circle-o fa-2x" aria-hidden="true"></i></button>

			<span id="uploadform"><br /><br />
			 <div>
				<form id="imageform" method="post" enctype="multipart/form-data" action='ajaximage.php'>
				<label class="btn btn-outline-primary font-weight-bold">
				 <input type="file"  id="NewFile" name="NewFile[]" multiple>
				 Upload
				</label>
				 <input type="hidden" id="uuid" name="uuid" value="<?php echo $_SESSION['EmployeeID']; ?>" />
				 <div class="text-muted">
				  Upload jpg, png or pdf file only
				  </div>
				  <input type="hidden" name="winheight" id="winheight" value="0">
				  <input type="hidden" id="prevRequestText" name="prevRequestText" value="" />
				 </form>
			 </div>
			</span>
			<span id="preview" style="width:100%;"></span>	    
		  </center>
		 </div>
	 </td>
	</tr>
    <tr>
	 <td colspan="10">
	 <form id="claimform" action="saveclaim.php" method="post" enctype="multipart/form-data">
	  <button class="btn btn-sm btn-success form-control" id="submit" name="submitclaim" required disabled style="height:30px;">Submit</button>
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

				<!-- <tr >
				<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
				<td colspan="3">
					<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" required>
				</td>
				
				</tr> -->
			


				

				

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
										<input class="form-control text-right" id="fdamount1" name="fdamount1" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" required>
										
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

				<!-- <tr >
				<th scope="row">Expense Name&nbsp;<span class="text-danger">*</span></th>
				<td colspan="3">
					<input type="text" class="form-control" id="ExpenseName" name="ExpenseName" required>
				</td>
				
				</tr> -->


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
										<input class="form-control text-right" id="fdamount1" name="fdamount1" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)" required>
										
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
}elseif($_POST['act']=='getlimit'){
	session_start();
	include 'config.php';

	$eid=$_POST['empid'];
	$cid=$_POST['cid'];


	$selc=mysql_query("SELECT cl.verifiedLimit,cl.updatedLimit,cl.verify FROM `claimlimits` cl where cl.EmployeeID='".$eid."' and cl.ClaimId='".$cid."'");
	$selcd=mysql_fetch_assoc($selc);

	?>

	<input type="" class="form-control text-right" readonly id="limitAmount" value="<?=$selcd['verifiedLimit']?>">
	<?php



}
?>
<script type="text/javascript" src="js/claim.js"></script>
