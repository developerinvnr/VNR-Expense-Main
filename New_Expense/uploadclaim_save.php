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
// error_reporting(E_ALL);
include 'config.php';

$Text = urldecode($_POST['RequestText']);
$uploadfiles = json_decode($Text);

$claimMonth=$_SESSION['todayMonth'];
// $date=$_SESSION['todayDate'];
// $date=date('Y-m-d',strtotime($_POST['Date']));
$claimYear=$_SESSION['FYearId'];




if(isset($_POST['submitclaim'])){


	$ins=mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaims`(`CrBy`, `CrDate`,`ClaimMonth`,`ClaimYearId`,`ClaimStatus`,`ClaimAtStep`) VALUES ('".$_POST['uuidee']."','".date("Y-m-d")."','0','".$_SESSION['FYearId']."','Submitted',2)");

	if($ins){
		$id=mysql_insert_id();
		

		//here moving uploaded files from temp folder to original dir........................................
		foreach ($uploadfiles as $key => $value) {
			if($value != ''){
				$s=$key+1; //this is doing because array key is starting from '0' and uploadfiles sequence starting from '1'
				$uYTemp = "documents/". $_SESSION['FYearId']. "/" . $_POST['uuidee']. "/" ."temp/".$value;
				$uY = "documents/" .$_SESSION['FYearId']. "/" . $_POST['uuidee']. "/" .$value;
				if(rename($uYTemp, $uY)){
					mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_claimuploads`(`ExpId`, `FileName`, `UploadSequence`) VALUES ('".$id."','".$value."','".$s."')");
				}
			}
		}
		//here moving uploaded files from temp folder to original dir........................................

		
		//here deleteing all non-uploaded images from user temp folder.........................................
		$files = glob("documents/".$_SESSION['FYearId']. "/" . $_POST['uuidee']."/"."temp/*"); // get all file names
		foreach($files as $file){ // iterate files
		  if(is_file($file))
		    unlink($file); // delete file
		}
		//here deleteing all non-uploaded images from user temp folder.........................................



		//this is y".$_SESSION['FYearId']."_monthexpensefinal table insert code..........................................................
		$sm=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_POST['uuidee']."' and `Month`='".$claimMonth."' and `YearId`='".$claimYear."'");

		if(mysql_num_rows($sm) > 0){
			mysql_query("UPDATE `y".$_SESSION['FYearId']."_monthexpensefinal` SET Status='Saved' WHERE `EmployeeID`='".$_POST['uuidee']."' and `Month`='".$claimMonth."' and `YeaIdr`='".$claimYear."'");
		}else{
			mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_monthexpensefinal`(`EmployeeID`, `Month`, `YearId`, `Status`) VALUES ('".$_POST['uuidee']."','".$claimMonth."','".$claimYear."','Open')");
		}
		//this is y".$_SESSION['FYearId']."_monthexpensefinal table insert code..........................................................


		?>
		<script type="text/javascript">
			setTimeout(function(){
				var gif='<img src="images/success_animation.gif">';
				$("#animarea").html(gif);
				setTimeout(function(){
					window.open("uploadclaim.php?prevupload=<?=$id?>&nemp=<?=$_POST['uuidee']?>","_self");
				},2600);
			},900);
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

