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
error_reporting(0);

if($_POST['savetype']==''){
	$clmsts='Approved';
}elseif($_POST['savetype']=='Draft'){
	$clmsts='Verified';
}

$cg=mysql_query("select cgId from claimtype where ClaimId=".$_POST['claimtype']);
$cgd=mysql_fetch_assoc($cg);


if(isset($_POST['UpdateLodging']) || isset($_POST['draftLodging'])){

	if(isset($_POST['draftLodging'])){
		$clmatstp=2;
	}else{$clmatstp=1;}

	$BillDate=date("Y-m-d",strtotime($_POST['BillDate']));
	$m=date("n",strtotime($_POST['BillDate']));
	$arrdate=date("Y-m-d H:i:s",strtotime($_POST['arrdate']));
	$depdate=date("Y-m-d H:i:s",strtotime($_POST['depdate']));
   
	
	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){

		// echo $_POST['expfid'];

		if(isset($_POST['expfid']) && $_POST['expfid']!=''){
			$ins=mysql_query("UPDATE `y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET `CityCategory`='".$_POST['CityCategory']."',`HotelName`='".$_POST['HotelName']."', `HotelAddress`='".$_POST['HotelAddress']."', `BillingName`='".$_POST['BillingName']."', `BillingAddress`='".$_POST['BillingAddress']."', `BillNo`='".$_POST['BillNo']."', `BillDate`='".$BillDate."', `Arrival`='".$arrdate."', `Departure`='".$depdate."', `StayDuration`='".$_POST['StayDuration']."', `RoomRateType`='".$_POST['RoomRateType']."',`Plan`='".$_POST['MealPlan']."',`NoOfPAX`='".$_POST['NoOfPAX']."',`GST`='".$_POST['GST']."',`BillingInstruction`='".$_POST['BillingInstruction']."' where did='".$_POST['expfid']."'");


		}
		
		

		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set `BillDate`='".$BillDate."',`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);

		if($up){
			//this is amount details insert code..................................................................
				
			$count = intval($_POST['fdtcount']);
			for($i=1;$i<=$count;$i++){

				if(isset($_POST['fdid'.$i]) && $_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){
					mysql_query("update `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `Title`='".$_POST['fdtitle'.$i]."',`ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."',`ApproverRemark`='".$_POST['fdApproverRemark'.$i]."' where ecdId='".$_POST['fdid'.$i]."'");
				}elseif($_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){
					
					mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`ApproverEditAmount`,`ApproverRemark`) VALUES ('".$_POST['expid']."','".$_POST['fdtitle'.$i]."','".$_POST['fdApproverEditAmount'.$i]."','".$_POST['fdApproverRemark'.$i]."')");
				}
			}
				
			//this is amount details insert code..................................................................
			
		}
	}

	
}elseif(isset($_POST['UpdateRailBusFare']) || isset($_POST['draftRailBusFare'])){

	if(isset($_POST['draftRailBusFare'])){
		$clmatstp=2;
	}else{$clmatstp=1;}

	$BookingDate=date("Y-m-d",strtotime($_POST['BookingDate']));
	$JourneyStartDt=date("Y-m-d",strtotime($_POST['JourneyStartDt']));
	$BillDate=date("Y-m-d",strtotime($_POST['JourneyStartDt']));//this billdate been copied JourneyStartDt because billdate is necessary


	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){



		if(isset($_POST['expfid']) && $_POST['expfid']!=''){
			
			$ins=mysql_query("UPDATE `y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET `Mode`='".$_POST['Mode']."',`TrainBusName`='".$_POST['TrainBusName']."',`Quota`='".$_POST['Quota']."', `Class`='".$_POST['Class']."', `BookingDate`='".$BookingDate."', `JourneyStartDt`='".$JourneyStartDt."',`JourneyFrom`='".$_POST['JourneyFrom']."', `JourneyUpto`='".$_POST['JourneyUpto']."', `PassengerDetail`='".$_POST['PassengerDetail']."', `BookingStatus`='".$_POST['BookingStatus']."', `TravelInsurance`='".$_POST['TravelInsurance']."',`BillDate`='".$BillDate."'  where did='".$_POST['expfid']."'");
		}


		
		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set `BillDate`='".$JourneyStartDt."',`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);
		 


		if($up){
			//this is amount details insert code..................................................................
				
			$count = intval($_POST['fdtcount']);
			for($i=1;$i<=$count;$i++){
				if(isset($_POST['fdid'.$i]) && $_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){
					
					mysql_query("update `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `Title`='".$_POST['fdtitle'.$i]."',`ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."',`ApproverRemark`='".$_POST['fdApproverRemark'.$i]."' where ecdId='".$_POST['fdid'.$i]."'");
				}elseif($_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){

					mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`ApproverEditAmount`,`ApproverRemark`) VALUES ('".$_POST['expid']."','".$_POST['fdtitle'.$i]."','".$_POST['fdApproverEditAmount'.$i]."','".$_POST['fdApproverRemark'.$i]."')");
				}
				
			}
			//this is amount details insert code..................................................................

			
		}
	}

}elseif(isset($_POST['UpdateLocalConv']) || isset($_POST['draftLocalConv'])){

	if(isset($_POST['draftLocalConv'])){
		$clmatstp=2;
	}else{$clmatstp=1;}

	$JourneyStartDt=date("Y-m-d",strtotime($_POST['JourneyStartDt']));
	$JourneyEndDt=date("Y-m-d",strtotime($_POST['JourneyEndDt']));
	$BillDate=date("Y-m-d",strtotime($_POST['JourneyStartDt']));//this billdate been copied JourneyStartDt because billdate is necessary
	
	$id=$_POST['expid'];
	

	if($_SESSION['EmpRole']=='A'){

		if(isset($_POST['expfid']) && $_POST['expfid']!=''){

			$ins=mysql_query("UPDATE `y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET `Mode`='".$_POST['Mode']."',`JourneyStartDt`='".$JourneyStartDt."',`JourneyEndDt`='".$JourneyEndDt."',`BillDate`='".$BillDate."'  where did='".$_POST['expfid']."'");
		}

				
		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set `BillDate`='".$JourneyEndDt."',`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);
		

		if($up){
			//this is amount details insert code..................................................................
				
			$count = intval($_POST['fdtcount']);
			for($i=1;$i<=$count;$i++){
				if(isset($_POST['fdid'.$i])){
					mysql_query("update `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `Title`='".$_POST['fdtitle'.$i]."',`ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."',`ApproverRemark`='".$_POST['fdApproverRemark'.$i]."' where ecdId='".$_POST['fdid'.$i]."'");
				}elseif($_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){
					
					mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`ApproverEditAmount`,`ApproverRemark`) VALUES ('".$_POST['expid']."','".$_POST['fdtitle'.$i]."','".$_POST['fdApproverEditAmount'.$i]."','".$_POST['fdApproverRemark'.$i]."')");
				}
				
			}
			//this is amount details insert code..................................................................

			
		}
	}
}elseif(isset($_POST['UpdateHiredVeh']) || isset($_POST['draftHiredVeh'])){

	if(isset($_POST['draftHiredVeh'])){
		$clmatstp=2;
	}else{$clmatstp=1;}

	$JourneyStartDt=date("Y-m-d",strtotime($_POST['JourneyStartDt']));
	$JourneyEndDt=date("Y-m-d",strtotime($_POST['JourneyEndDt']));
	$BillDate=date("Y-m-d",strtotime($_POST['BillDate']));
	
	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){


		if(isset($_POST['expfid']) && $_POST['expfid']!=''){

			$ins=mysql_query("UPDATE `y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET `AgencyName`='".$_POST['AgencyName']."',`AgencyAddress`='".$_POST['AgencyAddress']."',`BillingName`='".$_POST['BillingName']."',`BillingAddress`='".$_POST['BillingAddress']."',`Invoice`='".$_POST['Invoice']."', `BillDate`='".$BillDate."', `VehicleReg`='".$_POST['VehicleReg']."', `JourneyStartDt`='".$JourneyStartDt."', `JourneyEndDt`='".$JourneyEndDt."', `DistTraOpen`='".$_POST['DistTraOpen']."', `DistTraClose`='".$_POST['DistTraClose']."', `DailyBasisCharges`='".$_POST['DailyBasisCharges']."', `KmBasisCharges`='".$_POST['KmBasisCharges']."', `DriverCharges`='".$_POST['DriverCharges']."',`OtherCharges`='".$_POST['OtherCharges']."' where did='".$_POST['expfid']."'");
		}

		
				
		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set `BillDate`='".$BillDate."',`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);

		

		if($up){
			//this is amount details insert code..................................................................
				
			$count = intval($_POST['fdtcount']);
			for($i=1;$i<=$count;$i++){
				if(isset($_POST['fdid'.$i])){
					mysql_query("update `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `Title`='".$_POST['fdtitle'.$i]."',`ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."',`ApproverRemark`='".$_POST['fdApproverRemark'.$i]."' where ecdId='".$_POST['fdid'.$i]."'");
				}elseif($_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){
					
					mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`ApproverEditAmount`,`ApproverRemark`) VALUES ('".$_POST['expid']."','".$_POST['fdtitle'.$i]."','".$_POST['fdApproverEditAmount'.$i]."','".$_POST['fdApproverRemark'.$i]."')");
				}
				
			}
			//this is amount details insert code..................................................................

		}
	}
}elseif(isset($_POST['UpdateMobileBill']) || isset($_POST['draftMobileBill'])){

	if(isset($_POST['draftMobileBill'])){
		$clmatstp=2;
	}else{$clmatstp=1;}

	// $JourneyStartDt=date("Y-m-d",strtotime($_POST['JourneyStartDt']));
	$DueDate=date("Y-m-d",strtotime($_POST['DueDate']));
	$BillDate=date("Y-m-d",strtotime($_POST['BillDate']));
	
	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){

		if(isset($_POST['expfid']) && $_POST['expfid']!=''){

			$ins=mysql_query("UPDATE `y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET `BillDate`='".$BillDate."',`BillingName`='".$_POST['BillingName']."',`BillingAddress`='".$_POST['BillingAddress']."',`MobileService`='".$_POST['MobileService']."',`Mobile`='".$_POST['Mobile']."', `BillingCycle`='".$_POST['BillingCycle']."', `Plan`='".$_POST['Plan']."', `OtherCharges`='".$_POST['OtherCharges']."', `PrevBalance`='".$_POST['PrevBalance']."', `LastPayement`='".$_POST['LastPayement']."', `DueDate`='".$DueDate."', `PaymentMode`='".$_POST['PaymentMode']."'  where did='".$_POST['expfid']."'");
		}

				
		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set `BillDate`='".$BillDate."',`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);
		

		if($up){
			//this is amount details insert code..................................................................
				
			$count = intval($_POST['fdtcount']);
			for($i=1;$i<=$count;$i++){
				if(isset($_POST['fdid'.$i])){
					mysql_query("update `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `Title`='".$_POST['fdtitle'.$i]."',`ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."',`ApproverRemark`='".$_POST['fdApproverRemark'.$i]."' where ecdId='".$_POST['fdid'.$i]."'");
				}elseif($_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){
					
					mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`ApproverEditAmount`,`ApproverRemark`) VALUES ('".$_POST['expid']."','".$_POST['fdtitle'.$i]."','".$_POST['fdApproverEditAmount'.$i]."','".$_POST['fdApproverRemark'.$i]."')");
				}
				
			}
			//this is amount details insert code..................................................................

			
		}
	}
}elseif(isset($_POST['UpdatePostCour']) || isset($_POST['draftPostCour'])){

	if(isset($_POST['draftPostCour'])){
		$clmatstp=2;
	}else{$clmatstp=1;}

	// $JourneyStartDt=date("Y-m-d",strtotime($_POST['JourneyStartDt']));
	$DocketBookedDt=date("Y-m-d",strtotime($_POST['DocketBookedDt']));
	$BillDate=date("Y-m-d",strtotime($_POST['DocketBookedDt']));//this billdate been copied DocketBookedDt because billdate is necessary
	
	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){


		if(isset($_POST['expfid']) && $_POST['expfid']!=''){

			$ins=mysql_query("UPDATE `y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET `ServiceProvider`='".$_POST['ServiceProvider']."', `SenderName`='".$_POST['SenderName']."', `SenderAddress`='".$_POST['SenderAddress']."', `ReceiverName`='".$_POST['ReceiverName']."', `ReceiverAddress`='".$_POST['ReceiverAddress']."', `DocketNumber`='".$_POST['DocketNumber']."', `DocketBookedDt`='".$DocketBookedDt."', `WeightCharged`='".$_POST['WeightCharged']."',`BillDate`='".$BillDate."'   where did='".$_POST['expfid']."'");
		}

		
				
		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set `BillDate`='".$DocketBookedDt."',`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);

		
		

		if($up){
			//this is amount details insert code..................................................................
				
			$count = intval($_POST['fdtcount']);
			for($i=1;$i<=$count;$i++){
				if(isset($_POST['fdid'.$i])){
					mysql_query("update `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `Title`='".$_POST['fdtitle'.$i]."',`ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."',`ApproverRemark`='".$_POST['fdApproverRemark'.$i]."' where ecdId='".$_POST['fdid'.$i]."'");
				}elseif($_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){
					mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`ApproverEditAmount`,`ApproverRemark`) VALUES ('".$_POST['expid']."','".$_POST['fdtitle'.$i]."','".$_POST['fdApproverEditAmount'.$i]."','".$_POST['fdApproverRemark'.$i]."')");
				}
				
			}
			//this is amount details insert code..................................................................

			
		}
	}
}elseif(isset($_POST['Update24Wheeler']) || isset($_POST['draft24Wheeler'])){

	if(isset($_POST['draft24Wheeler'])){
		$clmatstp=2;
	}else{$clmatstp=1;}

	 $BillDate=date("Y-m-d",strtotime($_POST['BillDate']));//this billdate been copied JourneyStartDt because billdate is necessary
	$totalFillAmount = 0;
	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){


	if(isset($_POST['expfid']) && $_POST['expfid']!=''){

       if(isset($_POST['DistTraOpen']) and count($_POST['DistTraOpen'])>0){

         $arr = array();
         for($j=0; $j<count($_POST['DistTraOpen']); $j++){
           
             $VehicleReg = $_POST['VehicleReg'];
             $DistTraOpen = $_POST['DistTraOpen'];
             $JourneyStartDt = $_POST['JourneyStartDt'];
             $JourneyEndDt = $_POST['JourneyEndDt'];

             	$JourneyStartDt=date("Y-m-d h:i:s",strtotime($JourneyStartDt[$j]));
	            $JourneyEndDt=date("Y-m-d h:i:s",strtotime($JourneyEndDt[$j]));

             $DistTraClose = $_POST['DistTraClose'];
             $totalkm = $_POST['totalkm'];
             $FilledTAmt = $_POST['FilledTAmt'];
             $WheelId = $_POST['WheelId'];


              if($WheelId[$j]!=''){
					
				$ins=mysql_query("UPDATE `y".$_SESSION['FYearId']."_24_wheeler_entry` SET `VehicleReg`='".$VehicleReg[$j]."', `JourneyStartDt`='".$JourneyStartDt."', `JourneyEndDt`='".$JourneyEndDt."', `DistTraOpen`='".$DistTraOpen[$j]."', `DistTraClose`='".$DistTraClose[$j]."',`Totalkm`='".$totalkm[$j]."', `FilledTAmt` = '".$FilledTAmt[$j]."'  where WheelId='".$WheelId[$j]."' and  did='".$_POST['expfid']."' and ExpId='".$_POST['expid']."'");
              }
             	
             	$totalFillAmount = $totalFillAmount + $FilledTAmt[$j];
		   }
         
       }

			$ins=mysql_query("UPDATE `y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET  `VehicleType`='".$_POST['vehicleType']."', `BillDate`='".$BillDate."'  where did='".$_POST['expfid']."'");
	}else{
			
			$sqlmx=mysql_query("select MAX(did) as MaxId from `y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata`");
		 $resmx=mysql_fetch_assoc($sqlmx); $NewId=$resmx['MaxId']+1;

			$ins=mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` (`did`,`ExpId`,`VehicleType`,`BillDate`) VALUES (".$NewId.",'".$_POST['expid']."','".$_POST['vehicleType']."','".$BillDate."' )");

           $last_id = mysql_insert_id($ins);
		  
		    if(isset($_POST['DistTraOpen']) and count($_POST['DistTraOpen'])>0){

		         $arr = array();
        	     for($j=0; $j<count($_POST['DistTraOpen']); $j++){
           
		             $VehicleReg = $_POST['VehicleReg'];
		             $DistTraOpen = $_POST['DistTraOpen'];
		             $JourneyStartDt = $_POST['JourneyStartDt'];
		             $JourneyEndDt = $_POST['JourneyEndDt'];

		              	$JourneyStartDt=date("Y-m-d h:i:s",strtotime($JourneyStartDt[$j]));
	                    $JourneyEndDt=date("Y-m-d h:i:s",strtotime($JourneyEndDt[$j]));

		             $DistTraClose = $_POST['DistTraClose'];
		             $totalkm = $_POST['totalkm'];
		             $FilledTAmt = $_POST['FilledTAmt'];

		              	$ins=mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_24_wheeler_entry` (`did`, `ExpId`,`VehicleReg`,`JourneyStartDt`,`JourneyEndDt`,`DistTraOpen`,`DistTraClose`,`Totalkm`, `FilledTAmt`) VALUES ('".$NewId."', '".$_POST['expid']."', '".$VehicleReg[$j]."','".$JourneyStartDt."','".$JourneyEndDt."','".$DistTraOpen[$j]."','".$DistTraClose[$j]."', '".$totalkm[$j]."', '".$FilledTAmt[$j]."')");
             	
		         }
         
                $totalFillAmount = $totalFillAmount + $FilledTAmt[$j];
              }

		}

		

		
				
		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set `BillDate`='".$BillDate."',`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);


		
	}
}elseif(isset($_POST['UpdateVehMain']) || isset($_POST['draftVehMain'])){

	if(isset($_POST['draftVehMain'])){
		$clmatstp=2;
	}else{$clmatstp=1;}

	// $JourneyStartDt=date("Y-m-d",strtotime($_POST['JourneyStartDt']));
	// $DocketBookedDt=date("Y-m-d",strtotime($_POST['DocketBookedDt']));
	$BillDate=date("Y-m-d",strtotime($_POST['BillDate']));

	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){

		if(isset($_POST['expfid']) && $_POST['expfid']!=''){

			$ins=mysql_query("UPDATE`y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET `BillDate`='".$BillDate."' where did='".$_POST['expfid']."'");
		}else{
			
			$ins=mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` (`ExpId`, `BillDate`) VALUES ('".$_POST['expid']."','".$BillDate."')");
		}

		
				
		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set `BillDate`='".$BillDate."',`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);
		

		if($up){
			//this is amount details insert code..................................................................
				
			$count = intval($_POST['fdtcount']);
			for($i=1;$i<=$count;$i++){
				if(isset($_POST['fdid'.$i])){
					mysql_query("update `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `Title`='".$_POST['fdtitle'.$i]."',`ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."',`ApproverRemark`='".$_POST['fdApproverRemark'.$i]."' where ecdId='".$_POST['fdid'.$i]."'");
				}elseif($_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){
					mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`ApproverEditAmount`,`ApproverRemark`) VALUES ('".$_POST['expid']."','".$_POST['fdtitle'.$i]."','".$_POST['fdApproverEditAmount'.$i]."','".$_POST['fdApproverRemark'.$i]."')");
				}
				
			}
			//this is amount details insert code..................................................................

			
		}
	}
}elseif(isset($_POST['UpdateVehFuel']) || isset($_POST['draftVehFuel'])){

	if(isset($_POST['draftVehFuel'])){
		$clmatstp=2;
	}else{$clmatstp=1;}

	// $JourneyStartDt=date("Y-m-d",strtotime($_POST['JourneyStartDt']));
	// $DocketBookedDt=date("Y-m-d",strtotime($_POST['DocketBookedDt']));
	$BillDate=date("Y-m-d",strtotime($_POST['BillDate']));
	
	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){


		if(isset($_POST['expfid']) && $_POST['expfid']!=''){

			$ins=mysql_query("UPDATE`y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET `BillDate`='".$BillDate."' where did='".$_POST['expfid']."'");

		}

				
		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set `BillDate`='".$BillDate."',`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);
		

		if($up){
			//this is amount details insert code..................................................................
				
			$count = intval($_POST['fdtcount']);
			for($i=1;$i<=$count;$i++){
				if(isset($_POST['fdid'.$i])){
					mysql_query("update `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `Title`='".$_POST['fdtitle'.$i]."',`ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."',`ApproverRemark`='".$_POST['fdApproverRemark'.$i]."' where ecdId='".$_POST['fdid'.$i]."'");
				}elseif($_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){
					mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`ApproverEditAmount`,`ApproverRemark`) VALUES ('".$_POST['expid']."','".$_POST['fdtitle'.$i]."','".$_POST['fdApproverEditAmount'.$i]."','".$_POST['fdApproverRemark'.$i]."')");
				}
				
			}
			//this is amount details insert code..................................................................

			
		}
	}
}elseif(isset($_POST['UpdateRSTOFD']) || isset($_POST['draftRSTOFD'])){

	if(isset($_POST['draftRSTOFD'])){
		$clmatstp=2;
	}else{$clmatstp=1;}

	// $JourneyStartDt=date("Y-m-d",strtotime($_POST['JourneyStartDt']));
	// $DocketBookedDt=date("Y-m-d",strtotime($_POST['DocketBookedDt']));
	$BillDate=date("Y-m-d",strtotime($_POST['BillDate']));
	
	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){

		if(isset($_POST['expfid']) && $_POST['expfid']!=''){

			$ins=mysql_query("UPDATE`y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET  `Crop`='".$_POST['Crop']."', `BillDate`='".$BillDate."' where did='".$_POST['expfid']."'");

		}
		
				
		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set `BillDate`='".$BillDate."',`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);
		

		if($up){
			//this is amount details insert code..................................................................
				
			$count = intval($_POST['fdtcount']);
			for($i=1;$i<=$count;$i++){
				if(isset($_POST['fdid'.$i])){
					mysql_query("update `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `Title`='".$_POST['fdtitle'.$i]."',`ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."',`ApproverRemark`='".$_POST['fdApproverRemark'.$i]."' where ecdId='".$_POST['fdid'.$i]."'");
				}elseif($_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){
					mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`ApproverEditAmount`,`ApproverRemark`) VALUES ('".$_POST['expid']."','".$_POST['fdtitle'.$i]."','".$_POST['fdApproverEditAmount'.$i]."','".$_POST['fdApproverRemark'.$i]."')");
				}
				
			}
			//this is amount details insert code..................................................................

			
		}
	}
}elseif(isset($_POST['UpdateFDFV']) || isset($_POST['draftFDFV'])){

	if(isset($_POST['draftFDFV'])){
		$clmatstp=2;
	}else{$clmatstp=1;}

	// $JourneyStartDt=date("Y-m-d",strtotime($_POST['JourneyStartDt']));
	// $DocketBookedDt=date("Y-m-d",strtotime($_POST['DocketBookedDt']));
	$BillDate=date("Y-m-d",strtotime($_POST['BillDate']));
	
	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){

		if(isset($_POST['expfid']) && $_POST['expfid']!=''){

			$ins=mysql_query("UPDATE`y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET  `Vegetable`='".$_POST['Vegetable']."',`fms`='".$_POST['fms']."',`dtp`='".$_POST['dtp']."', `BillDate`='".$BillDate."' where did='".$_POST['expfid']."'");

		}
		
				
		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set `BillDate`='".$BillDate."',`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);
		

		if($up){
			//this is amount details insert code..................................................................
				
			$count = intval($_POST['fdtcount']);
			for($i=1;$i<=$count;$i++){
				if(isset($_POST['fdid'.$i])){
					mysql_query("update `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `Title`='".$_POST['fdtitle'.$i]."',`ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."',`ApproverRemark`='".$_POST['fdApproverRemark'.$i]."' where ecdId='".$_POST['fdid'.$i]."'");
				}elseif($_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){
					mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`ApproverEditAmount`,`ApproverRemark`) VALUES ('".$_POST['expid']."','".$_POST['fdtitle'.$i]."','".$_POST['fdApproverEditAmount'.$i]."','".$_POST['fdApproverRemark'.$i]."')");
				}
				
			}
			//this is amount details insert code..................................................................

			
		}
	}
}elseif(isset($_POST['UpdateJC']) || isset($_POST['draftJC'])){

	if(isset($_POST['draftJC'])){
		$clmatstp=2;
	}else{$clmatstp=1;}

	// $JourneyStartDt=date("Y-m-d",strtotime($_POST['JourneyStartDt']));
	// $DocketBookedDt=date("Y-m-d",strtotime($_POST['DocketBookedDt']));
	$BillDate=date("Y-m-d",strtotime($_POST['BillDate']));

	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){


		if(isset($_POST['expfid']) && $_POST['expfid']!=''){

			$ins=mysql_query("UPDATE`y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET  `Vegetable`='".$_POST['Vegetable']."', `BillDate`='".$BillDate."' where did='".$_POST['expfid']."'");


		}

		
				
		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set `BillDate`='".$BillDate."',`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);
		

		if($up){
			//this is amount details insert code..................................................................
				
			$count = intval($_POST['fdtcount']);
			for($i=1;$i<=$count;$i++){
				if(isset($_POST['fdid'.$i])){
					mysql_query("update `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `Title`='".$_POST['fdtitle'.$i]."',`ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."',`ApproverRemark`='".$_POST['fdApproverRemark'.$i]."' where ecdId='".$_POST['fdid'.$i]."'");
				}elseif($_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){
					mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`ApproverEditAmount`,`ApproverRemark`) VALUES ('".$_POST['expid']."','".$_POST['fdtitle'.$i]."','".$_POST['fdApproverEditAmount'.$i]."','".$_POST['fdApproverRemark'.$i]."')");
				}
				
			}
			//this is amount details insert code..................................................................

			
		}
	}

}elseif(isset($_POST['UpdateDm']) || isset($_POST['draftDm'])){

	if(isset($_POST['draftDm'])){
		$clmatstp=2;
	}else{$clmatstp=1;}

	// $JourneyStartDt=date("Y-m-d",strtotime($_POST['JourneyStartDt']));
	// $DocketBookedDt=date("Y-m-d",strtotime($_POST['DocketBookedDt']));
	$BillDate=date("Y-m-d",strtotime($_POST['BillDate']));
	
	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){

		if(isset($_POST['expfid']) && $_POST['expfid']!=''){

			$ins=mysql_query("UPDATE`y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET  `Crop`='".$_POST['Crop']."', CropDetails='".$_POST['CropDetails']."', `BillDate`='".$BillDate."' where did='".$_POST['expfid']."'");

		}

				
		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set `BillDate`='".$BillDate."',`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);
		

		if($up){
			//this is amount details insert code..................................................................
				
			$count = intval($_POST['fdtcount']);
			for($i=1;$i<=$count;$i++){
				if(isset($_POST['fdid'.$i])){
					mysql_query("update `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `Title`='".$_POST['fdtitle'.$i]."',`ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."',`ApproverRemark`='".$_POST['fdApproverRemark'.$i]."' where ecdId='".$_POST['fdid'.$i]."'");
				}elseif($_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){
					mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`ApproverEditAmount`,`ApproverRemark`) VALUES ('".$_POST['expid']."','".$_POST['fdtitle'.$i]."','".$_POST['fdApproverEditAmount'.$i]."','".$_POST['fdApproverRemark'.$i]."')");
				}
				
			}
			//this is amount details insert code..................................................................

			
		}
	}
}elseif(isset($_POST['UpdateDHQ']) || isset($_POST['draftDHQ'])){

	if(isset($_POST['draftDHQ'])){
		$clmatstp=2;
	}else{$clmatstp=1;}

	// $JourneyStartDt=date("Y-m-d",strtotime($_POST['JourneyStartDt']));
	// $DocketBookedDt=date("Y-m-d",strtotime($_POST['DocketBookedDt']));
	$BillDate=date("Y-m-d",strtotime($_POST['BillDate']));
	
	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){

		if(isset($_POST['expfid']) && $_POST['expfid']!=''){

			$ins=mysql_query("UPDATE`y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET  `DAAmount`='".$_POST['daAmount']."', `BillDate`='".$BillDate."' where did='".$_POST['expfid']."'");

		}

				
		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set 
		`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);
		

		
	}
}elseif(isset($_POST['UpdateDOS']) || isset($_POST['draftDOS'])){

	if(isset($_POST['draftDOS'])){
		$clmatstp=2;
	}else{$clmatstp=1;}

	// $JourneyStartDt=date("Y-m-d",strtotime($_POST['JourneyStartDt']));
	// $DocketBookedDt=date("Y-m-d",strtotime($_POST['DocketBookedDt']));
	$BillDate=date("Y-m-d",strtotime($_POST['BillDate']));
	
	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){

		if(isset($_POST['expfid']) && $_POST['expfid']!=''){

			$ins=mysql_query("UPDATE`y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET  `DAAmount`='".$_POST['daAmount']."', `BillDate`='".$BillDate."' where did='".$_POST['expfid']."'");

		}

				
		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set 
		`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);
		

		
	}
}elseif(isset($_POST['UpdateMLSaMISC']) || isset($_POST['draftMLSaMISC'])){




	if(isset($_POST['draftMLSaMISC'])){
		$clmatstp=2;
	}else{$clmatstp=1;}

	// $JourneyStartDt=date("Y-m-d",strtotime($_POST['JourneyStartDt']));
	// $DocketBookedDt=date("Y-m-d",strtotime($_POST['DocketBookedDt']));
	$BillDate=date("Y-m-d",strtotime($_POST['BillDate']));

	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){


		if(isset($_POST['expfid']) && $_POST['expfid']!=''){

			$ins=mysql_query("UPDATE`y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET  `BillDate`='".$BillDate."' where did='".$_POST['expfid']."'");


		}

		
				
		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set `BillDate`='".$BillDate."',`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);
		

		if($up){
			//this is amount details insert code..................................................................
				
			$count = intval($_POST['fdtcount']);
			for($i=1;$i<=$count;$i++){
				if(isset($_POST['fdid'.$i])){
					mysql_query("update `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `Title`='".$_POST['fdtitle'.$i]."',`ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."',`ApproverRemark`='".$_POST['fdApproverRemark'.$i]."' where ecdId='".$_POST['fdid'.$i]."'");
				}elseif($_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){
					mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`ApproverEditAmount`,`ApproverRemark`) VALUES ('".$_POST['expid']."','".$_POST['fdtitle'.$i]."','".$_POST['fdApproverEditAmount'.$i]."','".$_POST['fdApproverRemark'.$i]."')");
				}
				
			}
			//this is amount details insert code..................................................................

			
		}
	}

}elseif(isset($_POST['draftBoPa']) || isset($_POST['UpdateBoPa'])){

	if(isset($_POST['draftBoPa'])){
		$clmatstp=2;
	}else{$clmatstp=1;}


	$JourneyStartDt=date("Y-m-d",strtotime($_POST['JourneyStartDt']));
	$JourneyEndDt=date("Y-m-d",strtotime($_POST['JourneyEndDt']));
	$BillDate=date("Y-m-d",strtotime($_POST['JourneyStartDt']));//this billdate been copied JourneyStartDt because billdate is necessary

	

	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){



		if(isset($_POST['expfid']) && $_POST['expfid']!=''){
			
			$ins=mysql_query("UPDATE `y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET  `JourneyStartDt`='".$JourneyStartDt."',`JourneyEndDt`='".$JourneyEndDt."',`JourneyFrom`='".$_POST['JourneyFrom']."', `JourneyUpto`='".$_POST['JourneyUpto']."',`BillDate`='".$BillDate."'   where did='".$_POST['expfid']."'");
		}


		
		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set `BillDate`='".$JourneyStartDt."',`Remark`='".$_POST['Remark']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);


		if($up){
			//this is amount details insert code..................................................................
				
			$count = intval($_POST['fdtcount']);
			for($i=1;$i<=$count;$i++){
				if(isset($_POST['fdid'.$i]) && $_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){
					
					mysql_query("update `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `Title`='".$_POST['fdtitle'.$i]."',`ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."',`ApproverRemark`='".$_POST['fdApproverRemark'.$i]."' where ecdId='".$_POST['fdid'.$i]."'");
				}elseif($_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){

					mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`ApproverEditAmount`,`ApproverRemark`) VALUES ('".$_POST['expid']."','".$_POST['fdtitle'.$i]."','".$_POST['fdApproverEditAmount'.$i]."','".$_POST['fdApproverRemark'.$i]."')");
				}
				
			}
			//this is amount details insert code..................................................................

			
		}
	}

}elseif(isset($_POST['draftManual']) || isset($_POST['UpdateManual'])){

	if(isset($_POST['draftManual'])){
		$clmatstp=2;
	}else{$clmatstp=1;}


	$BillDate=date("Y-m-d",strtotime($_POST['mclaim_date']));//this billdate been copied JourneyStartDt because billdate is necessary


	$id=$_POST['expid'];

	if($_SESSION['EmpRole']=='A'){



		if(isset($_POST['expfid']) && $_POST['expfid']!=''){	

			$ins=mysql_query("UPDATE `y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` SET `BillDate`='".$BillDate."' where did='".$_POST['expfid']."'");
		}


		$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaims` set `BillDate`='".$BillDate."',`Remark`='".$_POST['mremarks']."', `ClaimStatus`='".$clmsts."', `ClaimMonth`='".date("m",strtotime($BillDate))."', `ApprBy`='".$_SESSION['EmployeeID']."', `ApprTAmt`='".$_POST['ApproverEditAmount']."', `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);

         if($up){

         		$ed=mysql_query("select ecdId from y".$_SESSION['FYearId']."_expenseclaimsdetails where ExpId=".$_POST['expid']);
         		$ecdId = mysql_fetch_assoc($ed);
  
	           if(count($ecdId)>0){

                      $u =  mysql_query("update `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `Title`='Manual amount', `Amount`='".$_POST['mamount']."', `Remark`='".$_POST['mremarks']."'  where ecdId='".$ecdId['ecdId']."' ");
  

	           }
            }

		}

}














if($_SESSION['EmpRole']=='V'){
	$count = intval($_POST['fdtcount']);
	for($i=1;$i<=$count;$i++){
		if(isset($_POST['fdid'.$i])){
					mysql_query("update `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `Title`='".$_POST['fdtitle'.$i]."',`ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."',`ApproverRemark`='".$_POST['fdApproverRemark'.$i]."' where ecdId='".$_POST['fdid'.$i]."'");
				}elseif($_POST['fdtitle'.$i]!='' && $_POST['fdamount'.$i]!=''){
			
			$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `VerifierEditAmount`='".$_POST['fdVerifierEditAmount'.$i]."',`VerifierRemark`='".$_POST['fdVerifierRemark'.$i]."' where `ExpId`='".$_POST['expid']."' and `Title`='".$_POST['fdtitle'.$i]."' and `Amount`='".$_POST['fdamount'.$i]."'");
		}
	}
	mysql_query("UPDATE `y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` set `VerifyBy`='".$_SESSION['EmployeeID']."',`VerifyTAmt`='".$_POST['VerifierEditAmount']."',  `VerifyDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);
	
	
}elseif($_SESSION['EmpRole']=='A'){
	$count = intval($_POST['fdtcount']);
	for($i=1;$i<=$count;$i++){
		if($_POST['fdtitle'.$i]!='' && $_POST['fdVerifierEditAmount'.$i]!=''){
			
			$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."',`ApproverRemark`='".$_POST['fdApproverRemark'.$i]."' where `ExpId`='".$_POST['expid']."' and `Title`='".$_POST['fdtitle'.$i]."' and `VerifierEditAmount`='".$_POST['fdVerifierEditAmount'.$i]."'");
		}
	}
	mysql_query("UPDATE `y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` set `ApprBy`='".$_SESSION['EmployeeID']."',`ApprTAmt`='".$_POST['ApproverEditAmount']."',  `ApprDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);
	
}elseif($_SESSION['EmpRole']=='F'){
	$count = intval($_POST['fdtcount']);
	for($i=1;$i<=$count;$i++){
		if($_POST['fdtitle'.$i]!='' && $_POST['fdApproverEditAmount'.$i]!=''){
			
			$up=mysql_query("UPDATE `y".$_SESSION['FYearId']."_expenseclaimsdetails` set `FinanceEditAmount`='".$_POST['fdFinanceEditAmount'.$i]."',`FinanceRemark`='".$_POST['fdFinanceRemark'.$i]."' where `ExpId`='".$_POST['expid']."' and `Title`='".$_POST['fdtitle'.$i]."' and `ApproverEditAmount`='".$_POST['fdApproverEditAmount'.$i]."'");
		}
	}
	mysql_query("UPDATE `y".$_SESSION['FYearId']."_g".$cgd['cgId']."_expensefilldata` set `FinancedBy`='".$_SESSION['EmployeeID']."',`FinancedTAmt`='".$_POST['FinanceEditAmount']."',  `FinancedDate`='".date("Y-m-d")."' where ExpId=".$_POST['expid']);
}



if($up){
	?>
	<script type="text/javascript">
		setTimeout(function(){
			var gif='<img src="images/success_animation.gif">';
			$("#animarea").html(gif);
			setTimeout(function(){

				var sts= window.parent.document.getElementById('<?=$_POST['expid']?>'+'Status');
				var btn=window.parent.document.getElementById('<?=$_POST['expid']?>'+'btn');
				var atbtn=window.parent.document.getElementById('<?=$_POST['expid']?>'+'atbtn');
				var FilledBtn=window.parent.document.getElementById('<?=$_POST['expid']?>'+'FilledBtn');
				var Rem=window.parent.document.getElementById('<?=$_POST['expid']?>'+'Rem');
				var clmsts= '<?=$clmsts?>';

				if(clmsts=='Approved'){
					$(sts).removeClass('btn-secondary');
					$(sts).removeClass('btn-outline-secondary');
					$(sts).removeClass('btn-danger');
					$(sts).addClass('btn-success');
					$(sts).html('Approved');
					$(btn).removeClass('btn-primary');
					$(btn).addClass('btn-info');
					$(btn).html('View');
					$(atbtn).hide();
					$(FilledBtn).hide();
					$(Rem).hide();

				}else if(clmsts=='Verified'){
					$(sts).removeClass('btn-outline-secondary');
					$(sts).addClass('btn-secondary');
					$(sts).html('Drft');
					$(btn).html('Edit');

				}

				if(window.parent.document.getElementById('clMonClmDet'+'<?=$_POST['expid']?>')){
				    window.parent.document.getElementById('clMonClmDet'+'<?=$_POST['expid']?>').style.display="none";
				}else{
				    window.parent.document.getElementById('myModal').style.display="none";
				}
				
		          
				
			},2000);
		},900);
		
		
		
	</script>
	<script type="text/javascript">
				
			</script>
	<?php
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

