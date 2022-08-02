<?php
session_start();
if(!isset($_SESSION['login'])){
  session_destroy();
  header('location:index.php');
}
date_default_timezone_set('Asia/Calcutta');
// echo 'aaa'.$_SESSION['EmpCode'].'---'.$_SESSION['EmployeeID'];
include 'config.php';




if($_POST['act']=='addremark'){
	$a=mysql_query("INSERT INTO `y".$_SESSION['FYearId']."_expenseremark`( `ExpId`, `Remark`, `By`,`ByRole`, `CrDateTime`) VALUES ('".$_POST['expid']."','".$_POST['remark']."','".$_POST['byid']."','".$_POST['emprole']."','".date("Y-m-d H:i:s")."')");


    $se=mysql_query("select CrBy from y".$_SESSION['FYearId']."_expenseclaims where ExpId=".$_POST['expid']);
	$re=mysql_fetch_assoc($se);
	if($_POST['byid']==$re['CrBy']){ $attchqry='Rmk=0'; }else{ $attchqry='Rmk=1'; }

	if($a)
	{
	    $up=mysql_query("update y".$_SESSION['FYearId']."_expenseclaims set ".$attchqry." where ExpId=".$_POST['expid']);
	    echo 'added';
	    
	}


}elseif($_POST['act']=='refreshChatArea'){


	$sr = mysql_query("select * from y".$_SESSION['FYearId']."_expenseremark where ExpId='".$_POST['expid']."' order by rid asc");
	while($srd = mysql_fetch_assoc($sr)){
		if($srd['By'] == $_POST['sessionid']){
			?>
			<div style="width: 80%;background-color: #DAE1E7;float: right;padding:4px;margin:4px;border-radius: 4px;border: 1px solid #E2E4E5;">
				<span style="font-size: 11px;color:#0469D9;"><?=$_POST['myname']?><br></span>
				<?=$srd['Remark']?>
				
				<span style="float: right;font-size: 11px;color:#757575;"><br><?=date("d-m-Y H:i",strtotime($srd['CrDateTime']))?></span>
			</div>
			<?php
		}else{
			?>
			<div style="width: 80%;background-color: #ffffff;float: left;padding:4px;margin:4px;border-radius: 4px;border: 1px solid #E2E4E5;">
				<span style="font-size: 11px;color:#0469D9;"><?=$_POST['othname']?><br></span>
				<?=$srd['Remark']?>
				<span style="float: right;font-size: 11px;color:#757575;"><br><?=date("d-m-Y H:i",strtotime($srd['CrDateTime']))?></span>
			</div>
			<?php
		}

	}
	

}



?>