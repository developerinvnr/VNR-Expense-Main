<?php session_start();
if($_REQUEST['view']=='verifier'){ $EmployeeID=$_REQUEST['ei']; $EmpRole=='E'; ?>

<?php  
if($_SESSION['CompanyId']==1){ $DbName='expense'; }
elseif($_SESSION['CompanyId']==3){ $DbName='expense_nr'; }
elseif($_SESSION['CompanyId']==4){ $DbName='expense_tl'; }
       
define('HOST','localhost');
define('USER','expense_user'); //vnrseed2_hr
define('PASS','expense@192'); //vnrhrims321 

define('USER2','hrims_user'); //vnrseed2_hr
define('PASS2','hrims@192'); //vnrhrims321 

//define('USER','root'); 
//define('PASS','ajaydbajay'); 

define('dbexpro',$DbName);
define('dbemp','hrims'); //vnrseed2_hrims 
define('CHARSET','utf8'); 
$con2=mysql_connect(HOST,USER2,PASS2) or die(mysql_error());
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

<div class="container-fluid ">
	<div class="row h-100">
    <?php if($EmpRole=='E' || $EmpRole=='A'){ ?>
	<div class="col-lg-3  d-none  d-lg-block">
    <br><br><br><br><br><br>
    <h2 style="color:#9299a0;" class="text-right">Welcome<br>To</h2>
    <h1 style="color: #9299a0;margin-top: -30px;" class="text-right">
	 <img src="images/Xeasylogotransparentgrey.png" style="width:55%;">
    </h1>
    <div class="text-muted pull-right">Platform to Claim and Record Expense</div>
   </div>
   <?php } ?>

<div id="claimerdiv" class="col-lg-9  col-md-12 <?php if($EmpRole=='E'){echo 'h-100';}?>" style="border-left:5px solid #d9d9d9;">
  <br />
   <span class="btn btn-sm btn-outline-warning font-weight-bold">P:&nbsp;<span class="badge badge-warning" style="font-size: 10px;">Pending</span></span> 
   <span class="btn btn-sm btn-outline-info font-weight-bold">F:&nbsp;<span class="badge badge-info" style="font-size: 10px;">Filled</span></span>
   <span class="btn btn-sm btn-outline-success font-weight-bold">&#10003;:&nbsp;<span class="badge badge-success" style="font-size: 10px;">OK</span></span>
   <span class="btn btn-sm btn-outline-danger font-weight-bold">D:&nbsp;<span class="badge badge-danger" style="font-size: 10px;">Denied</span></span>
  
  
<?php /****************************** Draft Draft Draft Open ****************/ ?>
<?php /****************************** Draft Draft Draft Open ****************/ ?>	
<div class="row" style="padding-top:8px;">
 <div class="col-lg-12 shadow table-responsive">
 <h6 style="padding-top:5px;">
  <small class="font-weight-bold text-muted"><i>Drafts</i>&nbsp;(<?php echo $_SESSION['FYear']; ?>)</small>&nbsp;&nbsp; 
 </h6>				
 <table class="estable table shadow" style="width:100%;">
  <thead class="thead-dark">
   <tr style="height:25px;">
	<th scope="col" style="width:20px;vertical-align:middle;">Sn</th>
	<th scope="col" style="width:25px;vertical-align:middle;">ID</th>
	<th scope="col" style="width:70px;vertical-align:middle;">Upload Date</th>
	<?php /*?><th scope="col" style="width:150px;">Status</th><?php */?>
	<th scope="col" style="vertical-align:middle;">View</th>
	<th scope="col" style="width:20px;vertical-align:middle;">Delete</th>  
   </tr>
  </thead>
  <tbody>
  <?php $di=1; $d=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `CrBy`='".$EmployeeID."' and (`ClaimStatus`='Submitted' or `ClaimStatus`='Draft') and AttachTo=0 order by ExpId asc"); while($dlist=mysql_fetch_assoc($d)){ ?>
   <tr>
	<td><?=$di?> </td>
	<td><?=$dlist['ExpId']?></td>
	<td><?=date('d-m-Y',strtotime($dlist['CrDate']))?></td>
	<?php /*?><td><?php echo 'Draft'; //$dlist['ClaimStatus'];?></td><?php */?>
	<td style="text-align:left;"><a class="btn btn-sm btn-primary" style="color:#fff;cursor: pointer;" onclick="showexpdet('<?=$dlist['ExpId']?>')"> View&nbsp;&nbsp;<?php $ch=mysql_query("select * from `y".$_SESSION['FYearId']."_expenseremark` where ExpId=".$dlist['ExpId']); $tch=mysql_num_rows($ch); if($tch>0){ ?><span class="btn btn-sm btn-outline-danger font-weight-bold" style="background-color: #fff; font-size:10px;">Chat:&nbsp;<span class="badge badge-danger" style="font-size:10px;"><?=$tch?></span></span><?php } ?></a></td>
	<td><a class="btn btn-sm btn-danger" style="color:#fff;cursor: pointer;" href="claim_delete.php?&id=<?=$dlist['ExpId']?>"><i class="fa fa-times" aria-hidden="true"></i></a></td>
   </tr>
   <?php $di++; } //while ?>
  </tbody>
 </table>			
 </div>
</div>
<?php /****************************** Draft Draft Draft Close ****************/ ?>
<?php /****************************** Draft Draft Draft Close ****************/ ?> 
  

<?php /****************************** Opened Opened Opened Open ****************/ ?>
<?php /****************************** Opened Opened Opened Open ****************/ ?>
<div class="row" style="padding-top:10px;">
 <div class="col-lg-12 shadow table-responsive">
 <h6 style="padding-top:5px;"><small class="font-weight-bold text-muted"><i>Opened Months</i>&nbsp;(<?php echo $_SESSION['FYear']; ?>)</small>&nbsp;&nbsp;</h6>	
 			
 <table class="estable table shadow" style="width:100%;">
  <thead class="thead-dark">
   <tr style="height:25px;">
	<th scope="col" style="width:30px;vertical-align:middle;">Month</th>
	<th scope="col" style="width:30px;vertical-align:middle;">Total<br />Claim</th>
	<th scope="col" style="width:50px;vertical-align:middle;">Amount<br />Verifed</th>
	<th scope="col" style="width:100px;vertical-align:middle;">Status</th>
	<th scope="col" style="width:50px;vertical-align:middle;">View</th> 
	<th scope="col" style="width:50px;vertical-align:middle;">Action</th> 
   </tr>
  </thead>
  <tbody>
  <?php $popupORnot="no"; 
  $m=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$EmployeeID."' and YearId=".$_SESSION['FYearId']." and `Status`='Open' group by Month order by Month asc"); 
  while($mlist=mysql_fetch_assoc($m))
  { 
    if(date('m', mktime(0,0,0,$mlist['Month'], 1, date('Y'))) != date("m")){ $popupORnot="yes"; } 
	
	$t=mysql_query("SELECT count(*) as TotC FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$EmployeeID."' and ClaimMonth='".$mlist['Month']."' and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'"); $tclaim=mysql_fetch_assoc($t);
    if($tclaim['TotC']>0)
	{
  ?>
  <tr>
   <td>
    <button class="btn btn-sm btn-warning" onclick="clGrpShowMonthDet(this,'<?=$mlist['Month']?>','<?=$EmployeeID?>')" style="width:100%;"><?=strtoupper(date('M', mktime(0,0,0,$mlist['Month'], 1, date('Y'))));?></button>
    <input type="hidden" id="md<?=$mlist['Month']?>" value="close">
    <input type="hidden" id="gmd<?=$mlist['Month']?>" value="close">
   </td>
   <td><span class="btn btn-sm btn-outline-primary font-weight-bold"><?=$tclaim['TotC']?></span></td>
   
   <?php $totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$EmployeeID."' and ClaimMonth='".$mlist['Month']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and ClaimStatus='Filled'"); $clm=mysql_fetch_assoc($totpaid); ?><td><?php if($clm['paid']>0){ echo $clm['paid'].'/-'; } ?></td> 
   
   <?php $fdo=mysql_query("SELECT count(*) as TotFdo FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$EmployeeID."' and `ClaimAtStep`>=1 and `ClaimAtStep`!=2 and `ClaimStatus`='Filled' and `FilledOkay`=1"); $fdo=mysql_fetch_assoc($fdo); 
    
	$totDen=mysql_query("SELECT count(*) as TotDen FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `ClaimMonth`='".$mlist['Month']."' and `CrBy`='".$EmployeeID."' and FilledOkay=2 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0 and AttachTo=0"); $den=mysql_fetch_assoc($totDen); ?>			
   <td><?php if($fdo['TotFdo']>0){?><span class="btn btn-sm btn-outline-success font-weight-bold">&#10003;:<span class="badge badge-success" style="font-size:10px;"><?=$fdo['TotFdo']?></span><?php } ?>		
	<?php if($den['TotDen']>0){?><span class="btn btn-sm btn-outline-danger font-weight-bold">D:&nbsp;<span class="badge badge-danger" style="font-size: 10px;"><?=$den['TotDen']?></span></span><?php } ?></td>
	  
   <td><button class="btn btn-sm btn-primary" style="font-size:14px;float:center;" onclick="showMonthReport('<?=$EmployeeID?>','<?=$mlist['Month']?>',<?=$_SESSION['FYearId']?>,2,1)"><i class="fa fa-table">&nbsp;Exp</i></button></td>  
   <td><?php //if($fdo['TotFdo']>0){ ?><button class="btn btn-sm btn-primary" style="font-size:14px;float:center;" onclick="submitmonthfill('<?=$mlist['Month']?>','<?=$EmployeeID?>')"><i class="fa fa-">Submit</i></button><?php //} ?></td>
  </tr>
  <tr id="g<?=$mlist['Month']?>"></tr>
  <tr id="<?=$mlist['Month']?>"></tr>
  <?php 
    } //if($tclaim['TotC']>0)
  } //while
  ?>
 </tbody>
 </table>			
 </div>
</div>
<?php /****************************** Opened Opened Opened Close ****************/ ?>
<?php /****************************** Opened Opened Opened Close ****************/ ?>

<?php /****************************** Closed Closed Closed Open ****************/ ?>
<?php /****************************** Closed Closed Closed Open ****************/ ?>	
<div class="row" style="padding-top:10px;">
 <div class="col-lg-12 shadow">
  <h6 style="padding-top:5px;"><small class="font-weight-bold text-muted"><i>Closed Months</i>&nbsp;&nbsp;(<?php echo $_SESSION['FYear']; ?>)</small></h6> 
						
<table class="estable table shadow" style="width:100%;">
  <thead class="thead-dark">
	<tr style="height:25px;">
	  <th rowspan="2" scope="col" style="width:30px;vertical-align:middle;">Month</th>
	  <th rowspan="2" scope="col" style="width:30px;vertical-align:middle;">Total<br />Claim</th>
	  <th rowspan="2" scope="col" style="width:50px;vertical-align:middle;">Amount<br />Claim</th>
	  <th rowspan="2" scope="col" style="width:50px;vertical-align:middle;">Courier<br />Detail</th>
	  
	  <th colspan="2" scope="col" style="vertical-align:middle;">Verified</th>
	  <th colspan="2" scope="col" style="vertical-align:middle;">Approved</th>
	  <th colspan="3" scope="col" style="vertical-align:middle;">Paid</th>
	  <th rowspan="2" scope="col" style="width:50px;vertical-align:middle;">Deatils<br />View</th> 
	</tr>
	<tr style="height:25px;">
	  <th scope="col" style="width:50px;vertical-align:middle;">Amount</th>
	  <th scope="col" style="width:60px;vertical-align:middle;">Date</th>
	  <th scope="col" style="width:50px;vertical-align:middle;">Amount</th>
	  <th scope="col" style="width:60px;vertical-align:middle;">Date</th>
	  <th scope="col" style="width:50px;vertical-align:middle;">Amount</th>
	  <th scope="col" style="width:60px;vertical-align:middle;">Date</th>
	  <th scope="col" style="width:150px;vertical-align:middle;">Remark</th>
	</tr>
  </thead>
  <tbody>
  <?php $m=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$EmployeeID."' and YearId=".$_SESSION['FYearId']." and `Status`='Closed' and Total_Claim>0 group by Month order by Month asc");
        $sn=1; while($mlist=mysql_fetch_assoc($m)){ ?>
   <tr>
	<td>
	 <button class="btn btn-sm btn-success" onclick="clGrpShowMonthDet(this,'<?=$mlist['Month']?>','<?=$EmployeeID?>')" style="width:100%;"><?=date('M', mktime(0,0,0,$mlist['Month'], 1, date('Y')));?></button>
	 <input type="hidden" id="md<?=$mlist['Month']?>" value="close">
	 <input type="hidden" id="gmd<?=$mlist['Month']?>" value="close">
	</td>
	<td><span class="btn btn-sm btn-outline-primary font-weight-bold"><?=$mlist['Total_Claim'];?></span></td>
	<td><?php if($mlist['Claim_Amount']>0){echo intval($mlist['Claim_Amount']).'/-';} ?></td>
	<td style="cursor:pointer;text-decoration:underline;"><span onclick="FUnOPen(<?=$sn?>)">click</span></td>
	<td><?php if($mlist['Verified_Amount']>0){echo intval($mlist['Verified_Amount']).'/-';} ?></td>
	<td><?php if($mlist['Verified_Amount']>0){echo date("d-m-y",strtotime($mlist['Verified_Date']));}?></td>
	<td><?php if($mlist['Approved_Amount']>0){echo intval($mlist['Approved_Amount']).'/-';} ?></td>
	<td><?php if($mlist['Approved_Amount']>0){echo date("d-m-y",strtotime($mlist['Approved_Date']));}?></td>	  
	<td><?php if($mlist['Fin_PayAmt']>0){ echo intval($mlist['Fin_AdvancePay']+$mlist['Fin_PayAmt']).'/-';} ?></td>
	<td><?php if($mlist['Fin_PayAmt']>0){ echo date("d-m-y",strtotime($mlist['Fin_PayDate']));}?></td>	
	<td><input style="border:hidden;width:100%;" value="<?php echo ucwords(strtolower($mlist['Fin_PayRemark']));?>"/></td>				
	<td style="text-align:center;"><button class="btn btn-sm btn-primary" style="font-size: 14px;float:center;" onclick="showMonthReport('<?=$EmployeeID?>','<?=$mlist['Month']?>',<?=$_SESSION['FYearId']?>,2,2)"><i class="fa fa-table">&nbsp;Exp</i></button></td>	
   </tr>
   
   <tr>
    <td colspan="6" style="width:100%;">
	 <div id="Div<?=$sn?>" style="display:none;">
	  <table style="width:100%; vertical-align:top;" cellspacing="0">
	   <tr>
	     <td style="width:80px;"><b>PostDate</b></td>
	     <td style="width:200px;"><div class="input-group date form_date col-md-12" data-date="" data-date-format="dd-mm-yyyy" data-link-field="PostDate<?=$sn?>" data-link-format="dd-mm-yyyy" style="padding:0px;"><input id="PostDate<?=$sn?>" style="font-family:Georgia;font-size:12px;width:100%;text-align:left;" value="<?php if($mlist['PostDate']!='0000-00-00'){echo date("d-m-Y",strtotime($mlist['PostDate'])); }else{echo date("d-m-Y"); }?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div></td>
	   </tr>
	   <tr>
	     <td><b>DocateNo</b></td><td><input id="DocateNo<?=$sn?>" style="font-family:Georgia;font-size:12px;width:100%;" value="<?=$mlist['DocateNo']?>"></td>
	   </tr>
	   <tr>  
		 <td><b>Agency</b></td><td><input id="Agency<?=$sn?>" style="font-family:Georgia;font-size:12px;width:100%;" value="<?=$mlist['Agency']?>"></td>
	   </tr>
	 <?php if($mlist['RecevingDate']!='0000-00-00' && $mlist['RecevingDate']!='1970-01-01' && $mlist['RecevingDate']!=''){ ?>
	   <tr>
	     <td><b>Receving Date</b></td><td><?=date("Y-m-d",strtotime($mlist['RecevingDate']))?></td>
	   </tr>
	   <tr>
	     <td><b>Any Remark</b></td><td><?=$mlist['DocRmk']?></td>
	   </tr>
	 <?php } ?>
	   <tr style="background-color:#FFFFFF;height:24px;">
	    <td colspan="2">
		<?php if($mlist['PostDate']=='0000-00-00' OR $mlist['PostDate']=='1970-01-01' OR $mlist['PostDate']==''){ ?>
		<button class="btn-primary btn-sm" style="font-size:10px;" onclick="FunSave(<?=$sn.','.$EmployeeID.','.$mlist['Month'].','.$_SESSION['FYearId']?>)">save</button><?php } ?>
		<button class="btn-primary btn-sm" style="font-size:10px;" onclick="FunClose(<?=$sn?>)">close</button></td>
	   </tr>	
	  </table>
	 </div>
	</td>
   <tr>
   
   <tr id="g<?=$mlist['Month']?>"></tr>
   <tr id="<?=$mlist['Month']?>"></tr>
		
 <?php $sn++; } ?>
 </tbody>
 </table>
 </div>
</div>
	
<?php /****************************** Closed Closed Closed Close ****************/ ?>
<?php /****************************** Closed Closed Closed Close ****************/ ?>	
	
</div>



<!-- from here the style, div and script all are for displaying modal on page view click -->

<style>


/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  position: relative;
  background-color: #fefefe;
  margin: auto;
  padding: 0;
  border: 1px solid #888;
  width: 90%;
  height: 110%;
  top:-70px;
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
  -webkit-animation-name: animatetop;
  -webkit-animation-duration: 0.4s;
  animation-name: animatetop;
  animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
  from {top:-300px; opacity:0} 
  to {top:-70px; opacity:1}
}

@keyframes animatetop {
  from {top:-300px; opacity:0}
  to {top:-70px; opacity:1}
}

/* The Close Button */
.close {
  position: absolute;
  top:0px;
  color: #000;
  right: 4px;
  font-size: 28px;
  font-weight: bold;
  display: block;
  cursor: pointer;

}

/*.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}*/

.modal-header {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}

.modal-body {padding: 2px 16px;}

.modal-footer {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}

.lbl{cursor: pointer;}
</style>




<div id="detailbox" style="display:none;position: absolute;height: 98%;width: 98%;">
  <iframe id="detailfr" src="" style="width:100%;height: 100%;"></iframe>
</div>


<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close pull-right" >&times;</span>&emsp;<br>
	
    <div class="modal-body d-flex justify-content-center align-items-center">	
	    <div style="position: absolute;margin:0 auto;height: 98%;width: 98%;">
			<iframe id="claimlistfr" src="" style="width:100%;height:100%;"></iframe>
		</div>    
    </div> 
  </div>
</div>


<script>
// Get the modal
var modal = document.getElementById('myModal');

var span = document.getElementsByClassName("close")[0];

span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>

<script type="text/javascript">
 
 function showMonthReport(e,m,y,n,nn)
 {
  window.open("printdetailsemp.php?e="+e+"&m="+m+"&y="+y+"&n="+n+"&nn="+nn,"PForm","menubar=no,scrollbars=yes,resizable=no,directories=no,width=800,height=500" ); 
 }

	function okayall(emp,month,cgid){}

	function clGrpShowMonthDet(t,month,emp){
		
		var sts=document.getElementById('gmd'+month);
		var modal = document.getElementById('g'+month);
		var dmodal=document.getElementById(month);

		if(sts.value=='close'){
			$.post("claimlistajax_admin.php",{act:"clGrpShowMonthDet",month:month,emp:emp},function(data){
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
			$.post("claimlistajax_admin.php",{act:"daysOfMonthDetToClaimer",month:month,emp:emp,cgid:cgid,msts:msts,csts:csts},function(data){
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
			$.post("claimlistajax_admin.php",{act:"clShowDaydet",date:date,emp:emp,cgid:cgid,csts:csts},function(data){
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
 <button class="btn btn-sm btn-primary font-weight-bold"  onclick="submitmonthfill('<?=$mlist['Month']?>','<?=$EmployeeID?>')">Final Submit</button>
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