<?php

session_start();
include 'config.php';


if($_POST['act']=='okayAllFilledClaims'){

	$emp=$_POST['emp'];
	$month=$_POST['month'];
	$cgid=$_POST['cgid'];

	$claimids="";


	$cg=mysql_query("select ClaimId from claimtype where cgId=".$cgid);
	while($cgd=mysql_fetch_assoc($cg)){
		$claimids.=$cgd['ClaimId'].",";
	}
	$claimids.="123456";

	$up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET FilledOkay=1 where Crby='".$emp."' and ClaimMonth='".$month."' and ClaimId IN (".$claimids.") and ClaimStatus='Filled'");

	

	if($up){
		echo 'okay';
	}



}

?>