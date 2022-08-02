<?php session_start();

if($_REQUEST['view']=='verifier'){ $_SESSION['EmployeeID']=$_REQUEST['ei']; $_SESSION['EmpRole']=='E'; ?>


<?php   
if($_SESSION['CompanyId']=='')
{
 if($_SESSION['EmpRole']=='E')
 {
  $con2=mysql_connect('localhost','vnrseed2_hr','vnrhrims321') or die(mysql_error());
  $empdb=mysql_select_db('vnrseed2_hrims', $con2) or die(mysql_error());
  $ruqry=mysql_query("select CompanyId,EmpCode from hrm_employee where EmployeeID=".$_SESSION['EmployeeID'],$con2); 
  $resuqry=mysql_fetch_assoc($ruqry); 
  if($resuqry['EmpCode']>790000 && $resuqry['CompanyId']==4){ $_SESSION['CompanyId']=4; }
  else{ $_SESSION['CompanyId']=1; }
  //$_SESSION['CompanyId']=$resuqry['CompanyId'];
 }
 else
 {
  $string=$_SESSION['EmpCode'];
  $FirstLet=substr($string, 0, 1);
  if($FirstLet=='T'){ $_SESSION['CompanyId']=4; }else{ $_SESSION['CompanyId']=1; }  
 } 
}
?>
<?php  
if($_SESSION['CompanyId']==1){ $DbName='vnrseed2_expense'; }
elseif($_SESSION['CompanyId']==4){ $DbName='vnrseed2_expense_tl'; }
       
define('HOST','localhost');
define('USER','vnrseed2_hr'); 
define('PASS','vnrhrims321'); 
define('dbexpro',$DbName);
define('dbemp','vnrseed2_hrims');  
define('CHARSET','utf8'); 
$con2=mysql_connect(HOST,USER,PASS) or die(mysql_error());
$empdb=mysql_select_db(dbemp, $con2) or die(mysql_error());
$con=mysql_connect(HOST,USER,PASS,true) or die(mysql_error());
$exprodb=mysql_select_db(dbexpro,$con) or die(mysql_error());
mysql_query("SET NAMES utf8");
date_default_timezone_set('Asia/Kolkata');
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="images/faviconexpro.png" type="image/png" sizes="16x16">
<title>Xeasy</title>
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />

<link href="https://fonts.googleapis.com/css?family=Lemonada:400,700" rel="stylesheet">

<link rel="stylesheet" href="css/jquery.datetimepicker.css">
<link rel="stylesheet" type="text/css" href="css/style.css">

<?php 
//if($_SESSION['EmpRole']=='E'){ $seluq=mysql_query("SELECT * FROM `hrm_employee`",$con2); }
//else{ $seluq=mysql_query("SELECT * FROM `hrm_user`",$con); }
//$users=mysql_num_rows($seluq);

$sely=mysql_query("SELECT * FROM `financialyear` where status='Active'",$con);
$selyd=mysql_fetch_assoc($sely); $FYearId=$selyd['YearId'];

$_SESSION['FYearId']=$selyd['YearId'];
$_SESSION['FYear']=$selyd['Year'];
$_SESSION['todayDate']=date("Y-m-d");
$_SESSION['todayMonth']=date("m",strtotime(date('Y-m-d')));

?>


<div class="container-fluid ">

	<div class="row h-100">

    <?php
    if($_SESSION['EmpRole']=='E' || $_SESSION['EmpRole']=='A'){
    ?>
		<div class="col-lg-3  d-none  d-lg-block">
			<br><br><br><br><br><br>
			<h2  style="color: #9299a0;" class="text-right">
				Welcome<br>To
			</h2>
			<h1 style="color: #9299a0;margin-top: -30px;" class="text-right">
				<img src="images/Xeasylogotransparentgrey.png" style="width:55%;">
			</h1>
			<div class="text-muted pull-right">
	   			Platform to Claim and Record Expense
	   	</div>
		</div>

    <?php
    }
    ?>


<div id="claimerdiv" class="col-lg-9  col-md-12 <?php if($_SESSION['EmpRole']=='E'){echo 'h-100';}?>" style="border-left:5px solid #d9d9d9;">
   
  <br /><!-- <a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
   
   <a class="btn btn-sm btn-primary" href="claim.php">&nbsp;&nbsp;<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Claim&nbsp;&nbsp;</a><br />-->

   <a class="btn btn-sm btn-primary" href="claim.php">&nbsp;&nbsp;<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Claim&nbsp;&nbsp;</a> 
	<a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
	<br>
	<br>
   
   <span class="btn btn-sm btn-outline-warning font-weight-bold">P:&nbsp;<span class="badge badge-warning" style="font-size: 10px;">Pending</span></span> 
   <span class="btn btn-sm btn-outline-info font-weight-bold">F:&nbsp;<span class="badge badge-info" style="font-size: 10px;">Filled</span></span>
   <span class="btn btn-sm btn-outline-success font-weight-bold">&#10003;:&nbsp;<span class="badge badge-success" style="font-size: 10px;">OK</span></span>
   <span class="btn btn-sm btn-outline-danger font-weight-bold">D:&nbsp;<span class="badge badge-danger" style="font-size: 10px;">Denied</span></span>
  
   <?php $sely=mysql_query("SELECT * FROM `financialyear` where YearId=".$_SESSION['FYearId'],$con); 
         $selyd=mysql_fetch_assoc($sely); ?>	

    <div class="row" style="padding-top:8px;">
	 <div class="col-lg-9 shadow  table-responsive">
	  <h6 style="padding-top:8px;">
	  	<small class="font-weight-bold text-muted"><i>Drafts</i>&nbsp;
		  (<?php echo $selyd['Year']; ?>)</small>&nbsp;&nbsp; 

		  <!-- <a class="btn btn-sm btn-primary" href="claim.php">&nbsp;&nbsp;<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Claim&nbsp;&nbsp;</a> <a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>  -->

		</h6>
				
			<table class="estable table shadow" style="width:100%;">
			  <thead class="thead-dark">
			    <tr>
			      <th scope="col" style="width:25px;">Sn</th>
			      <th scope="col" style="width:25px;">ID</th>
			      <th scope="col" style="width:100px;">Upload Date</th>
				  <th scope="col" style="width:150px;">Status</th>
				  <th scope="col" >View</th>
				  <th scope="col" style="width:25px;">Delete</th>
			      
			    </tr>
			    
			  </thead>
			  <tbody>
				<?php 
				
				$di=1;
				$d=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `CrBy`='".$_SESSION['EmployeeID']."' and (`ClaimStatus`='Submitted' or `ClaimStatus`='Draft') order by ExpId asc"); 

				while($dlist=mysql_fetch_assoc($d)){ ?>
			    <tr>
					<td><?=$di?> </td>
					<td><?=$dlist['ExpId']?></td>
					<td>
						<?=date('d-m-Y',strtotime($dlist['CrDate']))?> 
						
					</td>
					<td><?php echo 'Draft'; //$dlist['ClaimStatus'];?></td>
					<td>
						<a class="btn btn-sm btn-primary" style="color:#fff;cursor: pointer;" onclick="showexpdet('<?=$dlist['ExpId']?>')"> View

							<?php
							$ch=mysql_query("select * from `y".$_SESSION['FYearId']."_expenseremark` where ExpId=".$dlist['ExpId']);
							$tch=mysql_num_rows($ch);
							if($tch>0){
							?>
							<span class="btn btn-sm btn-outline-danger font-weight-bold" style="background-color: #fff;">Chats :&nbsp;<span class="badge badge-danger" style="font-size: 10px;"><?=$tch?></span></span>
							<?php 
							}
							?>

						</a>

					</td>
					<td>	<a class="btn btn-sm btn-danger" style="color:#fff;cursor: pointer;" href="claim_delete.php?&id=<?=$dlist['ExpId']?>"><i class="fa fa-times" aria-hidden="true"></i> 
						</a>
                    

					</td>
					
				</tr>
				
			  	<?php 
			  	$di++;
				} 


				
				?>
			  </tbody>
			</table>
			
		</div>
	</div>
	<br>
  

	<div class="row" style="padding-top:8px;">
	 <div class="col-lg-9 shadow  table-responsive">
	  <h6 style="padding-top:8px;">
	  	<small class="font-weight-bold text-muted"><i>Opened Months</i>&nbsp;
		  (<?php echo $selyd['Year']; ?>)</small>&nbsp;&nbsp; 

		  <!-- <a class="btn btn-sm btn-primary" href="claim.php">&nbsp;&nbsp;<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Claim&nbsp;&nbsp;</a> 
		  <a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>  -->
		</h6>
				
			<table class="estable table shadow" style="width:100%;">
			  <thead class="thead-dark">
			    <tr>
			      <th scope="col" style="width:60px;">Month</th>
			      <th scope="col" style="width:50px;">Total<br>Claims</th>
			      <th scope="col" style="width:60px;">Total<br>Amt</th>
				  <th scope="col" style="width:150px;">Status</th>
			      <th scope="col">Details</th> 
			    </tr>
			  </thead>
			  <tbody>
				<?php 
				$popupORnot="no";

				$m=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and YearId=".$_SESSION['FYearId']." and `Status`!='Closed' order by Month asc"); 
				     // print_r("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and YearId=".$_SESSION['FYearId']." and `Status`!='Closed' order by Month asc");
         //        die();
				while($mlist=mysql_fetch_assoc($m)){ 



					if(date('m', mktime(0,0,0,$mlist['Month'], 1, date('Y'))) != date("m")){
						$popupORnot="yes";
					}
				?>
			    <tr>
				 <td><button class="btn btn-sm btn-warning" onclick="clGrpShowMonthDet(this,'<?=$mlist['Month']?>','<?=$_SESSION['EmployeeID']?>')" style="width:100%;"><?=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y')));?></button>
                      
                      <input type="hidden" id="md<?=$mlist['Month']?>" value="close">

                      <input type="hidden" id="gmd<?=$mlist['Month']?>" value="close">
                  </td>
			  			
				 <td><?php
                  
               // print_r("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and ClaimMonth='".$mlist['Month']."' and c.ClaimId=e.ClaimId  and (e.ClaimStatus!='Deactivate' and e.ClaimStatus!='Draft' and e.ClaimStatus=='Filled')  and e.FilledOkay!=2");
				   // $t=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and AttachTo=0 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'"); 
				   //  $tclaim=mysql_num_rows($t);

				    $t=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and ClaimMonth='".$mlist['Month']."' and c.ClaimId=e.ClaimId  and (e.ClaimStatus!='Deactivate' and e.ClaimStatus!='Draft' and e.ClaimStatus='Filled')");  
				    $tclaim=mysql_num_rows($t);

				  ?>

				 <span class="btn btn-sm btn-outline-primary font-weight-bold"><?=$tclaim?></span></td>

				 <td style="vertical-align:middle;">
				<?php 

		   		$totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and ClaimMonth='".$mlist['Month']."' and c.ClaimId=e.ClaimId and FilledOkay=1 and (e.ClaimStatus!='Deactivate' and e.ClaimStatus!='Draft' and e.ClaimStatus='Filled')"); 
		  		$clm=mysql_fetch_assoc($totpaid);	
		  		echo $clm['paid']; if($clm['paid']>0){echo '/-';} ?>
				</td>
				  		
				 <td>
				 <?php 
						$tde=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep` IN (1,2) and ClaimStatus!='Saved'"); $tde=mysql_num_rows($tde);

						// print_r("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep` IN (1,2) and ClaimStatus!='Saved'");
						// die();
                     	$sde=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`=2 and `ClaimStatus`='Submitted'"); $sde=mysql_num_rows($sde);
				  		$fde=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`=1 and `ClaimStatus`='Filled'"); $fde=mysql_num_rows($fde);
						$fdo=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`=1 and `ClaimStatus`='Filled' and `FilledOkay`=1"); $fdo=mysql_num_rows($fdo);
							
				  		if($tde>0){ ?> 
						 <span class="btn btn-sm btn-outline-warning font-weight-bold">P:&nbsp;<span class="badge badge-warning" style="font-size: 10px;"><?=$sde?></span></span> 
						 <span class="btn btn-sm btn-outline-info font-weight-bold">F:&nbsp;<span class="badge badge-info" style="font-size: 10px;"><?=$fde?></span></span>
						 <span class="btn btn-sm btn-outline-success font-weight-bold">&#10003;:&nbsp;<span class="badge badge-success" style="font-size: 10px;"><?=$fdo?></span></span> 
				 		<?php } ?>
				 </td>
				  
				  <td style="text-align:center;">
				  	<button class="btn btn-sm btn-primary" style="font-size: 15px;float: center;" onclick="showMonthReport('<?=$_SESSION['EmployeeID']?>','<?=$mlist['Month']?>',<?=$_SESSION['FYearId']?>,2)"><i class="fa fa-table">&nbsp;Expense</i></button>

				  	<?php if($fde>0){ ?>
					 <button class="btn btn-sm btn-primary font-weight-bold"  onclick="submitmonthfill('<?=$mlist['Month']?>','<?=$_SESSION['EmployeeID']?>')">Final Submit</button>
					<?php } ?> 
				  	
				  </td>
				 </tr>
				 <tr id="g<?=$mlist['Month']?>"></tr>
				 <tr id="<?=$mlist['Month']?>"></tr>
				<?php 
				}
/*
				if($popupORnot=="yes" && date("d")>15 ){
					?>
					<script type="text/javascript">
						alert('Please Final Submit the Previous Open Months Claims as the last date 15-<?=date("m-Y")?> crossed.');
					</script>
					<?php
				}
*/				
				?>
			  </tbody>
			</table>
			
		</div>
	</div>
	<br>
	
	<div class="row">
		<div class="col-lg-9 shadow">
			<h6 style="padding-top:8px;"><small class="font-weight-bold text-muted"><i>Closed Months</i>&nbsp;&nbsp;
	  (<?php echo $selyd['Year']; ?>)</small> </h6> 
			
			
			<table class="estable table shadow" style="width:100%;">
			  <thead class="thead-dark">
			    <tr>
			      <th scope="col" style="width:80px;vertical-align:middle;">Month</th>
			      <th scope="col" style="width:60px;">Total<br>Claims</th>
			      <th scope="col" style="width:100px;">Claim <br>Amount</th>
				  <th scope="col" style="width:100px;">Approved<br>Amount</th>
			      <th scope="col" style="width:100px;">Total <br>Paid Amount</th>
			      <th scope="col" style="width:100px;vertical-align:middle;">Details</th> 

			    </tr>
			  </thead>
			  <tbody>
			  	<?php
			  	
			  	$m=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and YearId=".$_SESSION['FYearId']." and `Status`='Closed' order by Month asc");

				while($mlist=mysql_fetch_assoc($m)){
					
				  	?>
				  	<tr>
				  		<td><button class="btn btn-sm btn-success" onclick="clGrpShowMonthDet(this,'<?=$mlist['Month']?>','<?=$_SESSION['EmployeeID']?>')" style="width:100%;"><?=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y')));?></button>
                            <input type="hidden" id="md<?=$mlist['Month']?>" value="close">
                            <input type="hidden" id="gmd<?=$mlist['Month']?>" value="close">
                        </td>
			  			
				  	   <td><?php 

				  	   // $t=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'"); 

				  	   $t=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and ClaimMonth='".$mlist['Month']."' and c.ClaimId=e.ClaimId  and (e.ClaimStatus!='Deactivate' and e.ClaimStatus!='Draft' and e.FilledBy>0) "); 
				  	   $tclaim=mysql_num_rows($t);

				  	   ?>
						   <span class="btn btn-sm btn-outline-primary font-weight-bold"><?=$tclaim?></span></td>

                        <?php $totpaid=mysql_query("SELECT SUM(FilledTAmt) as Filled, SUM(ApprTAmt) as Apprd FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and ClaimMonth='".$mlist['Month']."' and FilledOkay=1 and c.ClaimId=e.ClaimId  and e.ClaimStatus!='Deactivate' and e.ClaimStatus!='Draft' and e.FilledBy>0"); $clm=mysql_fetch_assoc($totpaid); 
						      $totpaid2=mysql_query("SELECT Fin_PayAmt,Fin_AdvancePay FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `Month`='".$mlist['Month']."' and `YearId`='".$_SESSION['FYearId']."' and `EmployeeID`='".$_SESSION['EmployeeID']."'");  $clm2=mysql_fetch_assoc($totpaid2);
						?>	
						<td style="vertical-align:middle;">
				  		<?php echo $clm['Filled']; if($clm['Filled']>0){echo '/-';} ?>
						</td>
						
						<td style="vertical-align:middle;">
				  		<?php if($clm2['Fin_PayAmt']>0 OR $clm2['Fin_AdvancePay']>0){ echo $clm['Filled']; if($clm['Filled']>0){echo '/-';} } ?>
						</td>
				  		
				  	   <td style="vertical-align:middle;">
					    <?php echo $clm2['Fin_AdvancePay']+$clm2['Fin_PayAmt']; if($clm2['Fin_PayAmt']>0 OR $clm2['Fin_AdvancePay']>0){echo '/-';} ?></td>
				  	   <td style="text-align:center;">
						  	<button class="btn btn-sm btn-primary" style="font-size: 15px;float:center;" onclick="showMonthReport('<?=$_SESSION['EmployeeID']?>','<?=$mlist['Month']?>',<?=$_SESSION['FYearId']?>,2)"><i class="fa fa-table">&nbsp;Expense</i></button>
						  	
					  	
						</td>
				  		
				  	</tr>
				  	<tr id="g<?=$mlist['Month']?>"></tr>
				  	<tr id="<?=$mlist['Month']?>"></tr>
				<?php } ?>
			  </tbody>
			</table>
		</div>
	</div>
</div>



<script type="text/javascript">
 
 function showMonthReport(e,m,y,n)
 {
  window.open("printdetailsemp.php?e="+e+"&m="+m+"&y="+y+"&n="+n,"PForm","menubar=no,scrollbars=yes,resizable=no,directories=no,width=1000,height=550"); 
 }

	function okayall(emp,month,cgid){}

	function clGrpShowMonthDet(t,month,emp){
		
		var sts=document.getElementById('gmd'+month);
		var modal = document.getElementById('g'+month);
		var dmodal=document.getElementById(month);

		if(sts.value=='close'){
			$.post("claimlistajax.php",{act:"clGrpShowMonthDet",month:month,emp:emp},function(data){
				modal.innerHTML = data;				
			});
			sts.value="open";
		}else if(sts.value=='open'){
			dmodal.innerHTML = '';
			modal.innerHTML = '';
			sts.value="close";
		}
	}

	function clShowDaysOfMonth(t,month,emp,cgid,msts,csts){

		var sts=document.getElementById('md'+month);
		var modal = document.getElementById(month);
		// if(sts.value=='close'){
			$.post("claimlistajax.php",{act:"daysOfMonthDetToClaimer",month:month,emp:emp,cgid:cgid,msts:msts,csts:csts},function(data){
				// modal.innerHTML = '';
				modal.innerHTML = data;
				// alert(data);
				var sh=window.screen.availHeight;
				var sw=window.screen.availWidth;

				if(sh>sw){
					$('#clcldetdiv').prop('style','width:340px;overflow:auto !important;');
				}
				
				$('.grpbtns').removeClass("btn-primary");
				$('.grpbtns').addClass("btn-outline-primary");

				$(t).removeClass("btn-outline-primary");
				$(t).addClass("btn-primary");
			});
			// sts.value="open";
		// }else if(sts.value=='open'){
		// 	modal.innerHTML = '';
		// 	sts.value="close";
		// }
	}

	function clShowDaydet(t,date,emp,cgid,csts){
		// alert(date);
		// alert(cgid);
		// alert(csts);
		// alert(emp);
		var sts=document.getElementById('sts'+date+'1');
		var modal = document.getElementById(date+'1');
		if(sts.value=='close'){
			$.post("claimlistajax.php",{act:"clShowDaydet",date:date,emp:emp,cgid:cgid,csts:csts},function(data){
				// modal.innerHTML = '';
				modal.innerHTML = data;
				console.log(data);
				// alert(data);
				var sh=window.screen.availHeight;
				var sw=window.screen.availWidth;

				if(sh>sw){
					$('#clcldetdiv').prop('style','width:340px;overflow:auto !important;');
				}
				
				
			});
			sts.value="open";
		}else if(sts.value=='open'){
			modal.innerHTML = '';
			sts.value="close";
		}
	}



	

	

	// function submitmonthexp(month){
	// 	if (confirm('Are you sure to Send this month claims for Data Entry?')) {
	// 		$.post("homeajax.php",{act:"submitmonthexp",month:month},function(data){
				
	// 			if(data.includes('submitted')){
	// 				alert('Submitted Successfully');
	// 				location.reload();
	// 			}

	// 		});
	// 	}

	// }

	/*function showMonthReport(crby,month){
		var modal = document.getElementById('myModal');
		modal.style.display = "block";
		document.getElementById('claimlistfr').src="monthReport.php?crby="+crby+"&month="+month;
	}*/



	function submitmonthfill(month,crby){}
	function showexpdet(expid){ 
		var modal = document.getElementById('myModal');
		modal.style.display = "block";
		document.getElementById('claimlistfr').src="showclaim.php?expid="+expid;
	}

/*
	function okcheck(chk,expid){
		if(chk.checked){
           	$('#okdenyspan'+expid).css('display', 'block');
        }else{
        	$('#okdenyspan'+expid).css('display', 'none');
        }
	}
*/	

	function showOkDenyForm(expid,act){}



	function expfillok(expid){}

	function expfilldeny(expid){}



	function deactivate(t,expid){}

	function attachclaims(t,expid){}

	
</script>



<!-- 
<?php if($fde>0){ ?>
 <button class="btn btn-sm btn-primary font-weight-bold"  onclick="submitmonthfill('<?=$mlist['Month']?>','<?=$_SESSION['EmployeeID']?>')">Final Submit</button>
<?php } ?> 
-->


</div>

	
</div>
<?php
include "footer.php";
?>


<div></div>


</div>

<?php } ?>