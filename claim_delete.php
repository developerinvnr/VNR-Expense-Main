<?php

session_start();
include 'config.php'; 

if(isset($_GET['id'])){
		$up=mysql_query("UPDATE y".$_SESSION['FYearId']."_expenseclaims SET ClaimStatus='Deactivate' where ExpId='".$_GET['id']."'");
			
			header("Location:home.php"); 
            exit; 
}
?>