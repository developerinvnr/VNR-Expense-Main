<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">



<script
        src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
        
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>

<script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>


<?php
session_start();
include 'config.php';

// echo '<pre>';
$Text = urldecode($_POST['RequestText']);
$uploadfiles = json_decode($Text);
//print_r($uploadfiles); //json_decode($_REQUEST['RequestText']);

// die();


if(isset($_POST['submitlodging'])){

	//$docname=$_POST['ufilename'].'.'.$_POST['extname'];
	//$docnameDir='documents/'.$_POST['ufilename'].'.'.$_POST['extname'];

	$BillDate=date("Y-m-d",strtotime($_POST['BillDate']));
	$arrdate=date("Y-m-d H:i:s",strtotime($_POST['arrdate']));
	$depdate=date("Y-m-d H:i:s",strtotime($_POST['depdate']));

	$claimMonth=date("m",strtotime($_POST['claimMonth']));
	$claimYear=date("Y",strtotime($_POST['claimYear']));



	$ins=mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaims`( `ClaimId`,`ExpenseName`,`HotelName`, `HotelAddress`, `BillingName`, `BillingAddress`, `BillNo`, `BillDate`, `Arrival`, `Departure`, `StayDuration`, `RoomRateType`, `Plan`, `NoOfPAX`, `GST`, `BillingInstruction`, `Remark`, `CrBy`, `CrDate`,`ClaimMonth`,`ClaimYearId`) VALUES ('".$_POST['claimtype']."','".$_POST['ExpenseName']."','".$_POST['HotelName']."','".$_POST['HotelAddress']."','".$_POST['BillingName']."','".$_POST['BillingAddress']."','".$_POST['BillNo']."','".$BillDate."','".$arrdate."','".$depdate."','".$_POST['StayDuration']."','".$_POST['RoomRateType']."','".$_POST['MealPlan']."','".$_POST['NoOfPAX']."','".$_POST['GST']."','".$_POST['BillIns']."','".$_POST['Remark']."','".$_SESSION['EmployeeID']."','".date("Y-m-d")."','".$claimMonth."','".$claimYear."')");


	$sm=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and `Month`='".$claimMonth."' and `Year`='".$claimYear."'");

	if(mysql_num_rows($sm) > 0){
		mysql_query("UPDATE `y".$_SESSION['FYearId']."_monthexpensefinal` SET Status='Saved' WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and `Month`='".$claimMonth."' and `Year`='".$claimYear."'");
	}else{
		mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_monthexpensefinal`(`EmployeeID`, `Month`, `Year`, `Status`) VALUES ('".$_SESSION['EmployeeID']."','".$claimMonth."','".$claimYear."','Open')");
	}

	if($ins){
		$id=mysql_insert_id();
		// $new_name=$id.'.'.$_POST['extname'];
		// $new_nameDir='documents/'.$id.'.'.$_POST['extname'];
		// if(rename( $docnameDir, $new_nameDir)){
		// 	mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` SET `DocName`='".$new_name."' WHERE `ExpId`=".$id);
		// }

		foreach ($uploadfiles as $key => $value) {
			if($value != ''){
				$s=$key+1; //this is doing because array key is starting from '0' and uploadfiles sequence starting from '1'
				$uYTemp = "documents/". "/". $_SESSION['FYearId'] . $_SESSION['EmployeeID']. "/" ."temp/".$value;
				$uY = "documents/". "/" .$_SESSION['FYearId'] . $_SESSION['EmployeeID']. "/" .$value;
				if(rename($uYTemp, $uY)){
					mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_claimuploads`(`ExpId`, `FileName`, `UploadSequence`) VALUES ('".$id."','".$value."','".$s."')");
				}
			}
		}

		//here deleteing all non-uploaded images from user temp folder
		$files = glob("documents/". $_SESSION['EmployeeID']. "/".$_SESSION['FYearId'] ."/"."temp/*"); // get all file names
		foreach($files as $file){ // iterate files
		  if(is_file($file))
		    unlink($file); // delete file
		}

		//this is fare details insert code..................................................................
		$count = intval($_POST['fdtcount']);
		for($i=1;$i<=$count;$i++){
			mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`Amount`) VALUES ('".$id."','".$_POST['fdtitle'.$i]."','".$_POST['fdamount'.$i]."')");
			
		}
		//this is fare details insert code..................................................................

		
		
		// echo $act='inserted';
		?>
		<script type="text/javascript">
			setTimeout(function(){
				var gif='<img src="images/success_animation.gif">';
				$("#animarea").html(gif);
				setTimeout(function(){
					window.open("claim.php?prevupload=<?=$id?>","_self");
					
				},2800);
			},1800);
			
		</script>
		<?php
	}
}elseif(isset($_POST['submitrailbusexp'])){

	$docname=$_POST['ufilename'].'.'.$_POST['extname'];
	$docnameDir='documents/'.$_POST['ufilename'].'.'.$_POST['extname'];

	$BookingDate=date("Y-m-d",strtotime($_POST['BookingDate']));
	$rbJourneyStartDt=date("Y-m-d",strtotime($_POST['rbJourneyStartDt']));



	$ins=mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaims`( `ClaimId`,`ExpenseName`,`Mode`,`TrainBusName`,`Quota`,`Class`,`BookingDate`,`JourneyStartDt`,`JourneyFrom`,`JourneyUpto`,`PassengerDetail`,`BookingStatus`,`TravelInsurance`,`TotalFare`,`Remark`, `CrBy`, `CrDate`) VALUES ('".$_POST['claimtype']."','".$_POST['ExpenseName']."','".$_POST['Mode']."','".$_POST['TrainBusName']."','".$_POST['Quota']."','".$_POST['Class']."','".$BookingDate."','".$rbJourneyStartDt."','".$_POST['JourneyFrom']."','".$_POST['JourneyUpto']."','".$_POST['PassengerDetail']."','".$_POST['BookingStatus']."','".$_POST['TravelInsurance']."','".$_POST['TotalFare']."','".$_POST['Remark']."','".$_SESSION['EmployeeID']."','".date("Y-m-d")."')");


	if($ins){
		$id=mysql_insert_id();
		$new_name=$id.'.'.$_POST['extname'];
		$new_nameDir='documents/'.$id.'.'.$_POST['extname'];
		if(rename( $docnameDir, $new_nameDir)){
			mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` SET `DocName`='".$new_name."' WHERE `ExpId`=".$id);
		}
		
		// echo $act='inserted';
		?>
		<script type="text/javascript">
			setTimeout(function(){
				var gif='<img src="images/success_animation.gif">';
				$("#animarea").html(gif);
				setTimeout(function(){
					window.open("claim.php?prevupload=<?=$id?>","_self");
					
				},2800);
			},1800);
			
		</script>
		<?php
	}

}elseif(isset($_POST['submitlocal'])){

	$docname=$_POST['ufilename'].'.'.$_POST['extname'];
	$docnameDir='documents/'.$_POST['ufilename'].'.'.$_POST['extname'];


	$JourneyStartDt=date("Y-m-d H:i:s",strtotime($_POST['JourneyStartDt']));
	$JourneyEndDt=date("Y-m-d H:i:s",strtotime($_POST['JourneyEndDt']));


	$ins=mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaims`( `ClaimId`,`ExpenseName`,`Mode`,`JourneyStartDt`,`JourneyEndDt`,`Amount`,`Remark`, `CrBy`, `CrDate`) VALUES ('".$_POST['claimtype']."','".$_POST['ExpenseName']."','".$_POST['Mode']."','".$JourneyStartDt."','".$JourneyEndDt."','".$_POST['Amount']."','".$_POST['Remark']."','".$_SESSION['EmployeeID']."','".date("Y-m-d")."')");
	

	if($ins){
		$id=mysql_insert_id();

		//this is fare details insert code..................................................................
		$count = intval($_POST['fdtcount']);
		for($i=1;$i<=$count;$i++){
			mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`Amount`) VALUES ('".$id."','".$_POST['fdtitle'.$i]."','".$_POST['fdamount'.$i]."')");
			
		}
		//this is fare details insert code..................................................................



		$new_name=$id.'.'.$_POST['extname'];
		$new_nameDir='documents/'.$id.'.'.$_POST['extname'];
		if(rename( $docnameDir, $new_nameDir)){
			mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` SET `DocName`='".$new_name."' WHERE `ExpId`=".$id);
		}
		
		// echo $act='inserted';
		?>
		<script type="text/javascript">
			setTimeout(function(){
				var gif='<img src="images/success_animation.gif">';
				$("#animarea").html(gif);
				setTimeout(function(){
					window.open("claim.php?prevupload=<?=$id?>","_self");
					
				},2800);
			},1800);
			
		</script>
		<?php
	}
}elseif(isset($_POST['submithiredveh'])){

	$docname=$_POST['ufilename'].'.'.$_POST['extname'];
	$docnameDir='documents/'.$_POST['ufilename'].'.'.$_POST['extname'];


	$JourneyStartDt=date("Y-m-d H:i:s",strtotime($_POST['JourneyStartDt']));
	$JourneyEndDt=date("Y-m-d H:i:s",strtotime($_POST['JourneyEndDt']));


	// SELECT `ExpId`, `ClaimId`, `ExpenseName`,  `JourneyStartDt`, `JourneyEndDt`, `AgencyName`, `AgencyAddress`, `Invoice`, `VehicleReg`, `DailyBasisCharges`, `KmBasisCharges`, `DistanceTravelled`, `DriverCharges`, `OtherCharges`, `MobileService`, `Mobile`, `BillingCycle`, `PrevBalance`, `LastPayement`, `DueDate`, `PaymentMode`, `ServiceProvider`, `SenderName`, `SenderAddress`, `ReceiverName`, `ReceiverAddress`, `DocketNumber`, `DocketBookedDt`, `WeightCharged`, `Amount`, `Remark`, `ClaimStatus`, `CrBy`, `CrDate`, `ApprBy`, `VerifyBy` FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE 1




	$ins=mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaims`( `ClaimId`,`ExpenseName`,`Mode`,`JourneyStartDt`,`JourneyEndDt`,`Amount`,`Remark`, `CrBy`, `CrDate`) VALUES ('".$_POST['claimtype']."','".$_POST['ExpenseName']."','".$_POST['Mode']."','".$JourneyStartDt."','".$JourneyEndDt."','".$_POST['Amount']."','".$_POST['Remark']."','".$_SESSION['EmployeeID']."','".date("Y-m-d")."')");
	

	if($ins){
		$id=mysql_insert_id();

		//this is fare details insert code..................................................................
		$count = intval($_POST['fdtcount']);
		for($i=1;$i<=$count;$i++){
			mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`Amount`) VALUES ('".$id."','".$_POST['fdtitle'.$i]."','".$_POST['fdamount'.$i]."')");
			
		}
		//this is fare details insert code..................................................................



		$new_name=$id.'.'.$_POST['extname'];
		$new_nameDir='documents/'.$id.'.'.$_POST['extname'];
		if(rename( $docnameDir, $new_nameDir)){
			mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` SET `DocName`='".$new_name."' WHERE `ExpId`=".$id);
		}
		
		// echo $act='inserted';
		?>
		<script type="text/javascript">
			setTimeout(function(){
				var gif='<img src="images/success_animation.gif">';
				$("#animarea").html(gif);
				setTimeout(function(){
					window.open("claim.php?prevupload=<?=$id?>","_self");
					
				},2800);
			},1800);
			
		</script>
		<?php
	}
}

?>

<div class="row h-100 align-items-center align-middle">
	<div class="col-md-12 " >
		<center>
		<span id="animarea" class="text-secondary" style="font-size: 64px;">
			Saving...
		<div class="spinner-border text-secondary " role="status" style="width: 5rem;height: 5rem;"> 
		  <span class="sr-only">Saving...</span>
		</div>

		
		</span>
		</center>
	</div>
	
</div>

