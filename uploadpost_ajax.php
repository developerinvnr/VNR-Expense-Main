<?php 
// echo "heelll";die();



session_start();
// include 'config.php';
// error_reporting(0);


if (isset($_POST['mclaim_date'])) {
    
    if($_SESSION['CompanyId']==1){ $DbName='vnrseed2_expense'; }
    elseif($_SESSION['CompanyId']==4){ $DbName='vnrseed2_expense_tl'; }

  // $servername = "localhost";
  // $username = "root";
  // $password = "";
  // $dbname = "expense_demo";

    $servername = "localhost";
    $username = "vnrseed2_hr";
    $password = "vnrhrims321";
    $dbname = $DbName;


    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
    $_POST['mclaim_date'];
    $_POST['mclaimtype'];
    $_POST['mamount'];
    $_POST['mremarks']; 

    $BillDate=date("Y-m-d",strtotime($_POST['mclaim_date']));//this billdate been copied JourneyStartDt because billdate is necessary

      $sth = $conn->prepare("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE ClaimId='".$_POST['mclaimtype']."' and BillDate = '". $BillDate."' and ClaimStatus!='Deactivate' and FilledBy='".$_POST['uuid']."'");
      $sth->execute();
      $result = $sth->fetchAll();

      // print_r($result);
    if(count($result)==0){

      $stmt = $conn->prepare("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaims`  (`ClaimId`,`BillDate`,`Remark`,`ClaimStatus`,`ClaimAtStep`,`ClaimMonth`,`FilledBy`,`FilledOkay`,`FilledTAmt`,`FilledDate`, `CrBy`, `CrDate`, `ClaimYearId`) VALUES  ('".$_POST['mclaimtype']."', '". $BillDate."', '".$_POST['mremarks']."',  'Submitted', 2,  '".date("m",strtotime($_POST['mclaim_date']))."', '".$_POST['uuid']."', 0, '".$_POST['mamount']."', '".date("Y-m-d")."', '".$_POST['uuid']."', '".date("Y-m-d")."', '".$_SESSION['FYearId']."')");
      $stmt->execute();
      $expid = $conn->lastInsertId();


     $cl = $conn->prepare("INSERT INTO `y".$_SESSION['FYearId']."_g1_expensefilldata` (`ExpId`,`BillDate`) VALUES ('".$expid."','".$BillDate."')");
     $cl->execute();


     $cl2 = $conn->prepare("INSERT INTO `y".$_SESSION['FYearId']."_expenseclaimsdetails`( `ExpId`,`Title`,`Amount`,`Remark`) VALUES ('".$expid."', 'Manual amount', '".$_POST['mamount']."', '".$_POST['mremarks']."')");
     $st = $cl2->execute();

    if($st){
          echo json_encode(array("status"=>'success', "msg"=>"Manual claim added successfully."));
          die();
    }

    }else{

    echo json_encode(array("status"=>'error', "msg"=>"Claim is already exists."));

    }


}
else{
		echo json_encode(array("status"=>'error', "msg"=>"There is some problem in your reuquest."));
}
  



?>