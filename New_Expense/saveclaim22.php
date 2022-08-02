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

if(isset($_POST['submitlodging'])){



	$docname=$_POST['ufilename'].'.'.$_POST['extname'];
	$docnameDir='documents/'.$_POST['ufilename'].'.'.$_POST['extname'];

	$BillDate=date("Y-m-d",strtotime($_POST['BillDate']));
	$arrdate=date("Y-m-d H:i:s",strtotime($_POST['arrdate']));
	$depdate=date("Y-m-d H:i:s",strtotime($_POST['depdate']));


	$ins=mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaims`( `ClaimId`,`HotelName`, `HotelAddress`, `BillingName`, `BillingAddress`, `BillNo`, `BillDate`, `Arrival`, `Departure`, `StayDuration`, `RoomRateType`, `Plan`, `NoOfPAX`, `GST`, `BillingInstruction`, `Remark`, `CrBy`, `CrDate`) VALUES ('".$_POST['claimtype']."','".$_POST['HotelName']."','".$_POST['HotelAddress']."','".$_POST['BillingName']."','".$_POST['BillingAddress']."','".$_POST['BillNo']."','".$BillDate."','".$arrdate."','".$depdate."','".$_POST['StayDuration']."','".$_POST['RoomRateType']."','".$_POST['MealPlan']."','".$_POST['NoOfPAX']."','".$_POST['GST']."','".$_POST['BillIns']."','".$_POST['Remark']."','".$_SESSION['EmployeeID']."','".date("Y-m-d")."')");

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
					window.open("claim.php?prevupload=<?=$id?>","_self")
					
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