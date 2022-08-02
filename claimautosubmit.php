<?php
session_start();
include 'config.php';
// error_reporting(E_ALL);
// $month=date("m");
// $m = 9;
// echo $month = sprintf("%02d", $m);
// echo '<br>';
// $lm = $m-1;
// echo $lastmonth = sprintf("%02d", $lm);
// echo '<br>';


if(date("d") == 14){

	$selY = mysql_query("select YearId from financialyear where Status='Active'");
	$selYd = mysql_fetch_assoc($selY);

	$YearId = $selYd['YearId'];

	$selU = mysql_query("select EmployeeID, Month from y".$YearId."_monthexpensefinal where Status='Open' and Month != '".date("m")."'");
	
	while( $selUd = mysql_fetch_assoc($selU) ){
	


		// if($_REQUEST['user']){
			$crCond=" and Crby=".$selUd['EmployeeID'];
			$eidCond=" and EmployeeID=".$selUd['EmployeeID'];
		// }else{
		// 	$crCond="";
		// 	$eidCond="";
		// }


		// $closingMonth=(int)$_REQUEST['month'];
		// $openingMonth=$closingMonth+1;

		$closingMonth=(int)$selUd['Month'];
		$openingMonth=date("m");

		$month = $openingMonth;
		$lastmonth = $closingMonth;

		//this code for moving the filled claims to verifying stage...................................

		$up=mysql_query("UPDATE y".$YearId."_expenseclaims SET ClaimAtStep=3, VerifyTAmt=FilledTAmt, ApprTAmt=FilledTAmt, FinancedTAmt=FilledTAmt where FilledOkay=1 and ClaimMonth='".$lastmonth."' and ClaimYearId='".$YearId."' and ClaimStatus='Filled'".$crCond);

		// ....................................................................................................





		//this code for closing months........................................................................

		mysql_query("UPDATE y".$YearId."_monthexpensefinal SET Status='Closed' where  Month='".$lastmonth."' and YearId='".$YearId."'".$eidCond);

		// ....................................................................................................






		//this code for copying the amounts...............................................................

		$selexp=mysql_query("select ExpId from y".$YearId."_expenseclaims where ClaimStatus='Filled' and ClaimMonth='".$lastmonth."' and ClaimYearId='".$YearId."'".$crCond);
		while($selexpd=mysql_fetch_assoc($selexp)){
			
			mysql_query("update y".$YearId."_y".$YearId."_expenseclaimsdetails set VerifierEditAmount=Amount where ExpId='".$selexpd['ExpId']."'");
		}

		// ....................................................................................................






		//this code for creating open months of closed months employees.....................................

		$selm=mysql_query("select EmployeeID from y".$YearId."_monthexpensefinal where Status='Closed' and Month='".$lastmonth."' and YearId='".$YearId."'".$eidCond." group by EmployeeID");
		while($selmd=mysql_fetch_assoc($selm)){

			$selo=mysql_query("select EmployeeID from y".$YearId."_monthexpensefinal where Month='".$month."' and EmployeeID='".$selmd['EmployeeID']."'".$eidCond);
			if(mysql_num_rows($selo)==0){
				mysql_query("INSERT INTO `y".$YearId."_monthexpensefinal`( `EmployeeID`, `Month`, `YearId`, `Status`, `Crdate`) VALUES ('".$selmd['EmployeeID']."','".$month."','".$YearId."','Open','".date('Y-m-d')."')");
				echo 'month created';
			}

		}

		// ....................................................................................................
		echo 'submitted';
	}




	// if($up){
	// 	echo '<br><br>';
	// 	echo 'submitted';
	// }

}

?>