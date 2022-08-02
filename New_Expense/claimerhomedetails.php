<div id="claimerdiv" class="col-lg-9  col-md-12 <?php if($_SESSION['EmpRole']=='E'){echo 'h-100';}?>" style="border-left:5px solid #d9d9d9;">
   
 <br />
 
 
 <!--/***********************************/-->
 <!--/***********************************/-->
 <?php if($_SESSION['EmployeeID']==169){ ?>
 <?php if($_REQUEST['act']=='DelDupId' AND $_REQUEST['DupId']>0)
 {  $sqlDel=mysql_query("delete from y".$_SESSION['FYearId']."_monthexpensefinal where id=".$_REQUEST['DupId'],$con); } ?>
 
 <tr>
  <td><!--<font style="font-size:14px;font-weight:bold;color:#FFFFFF;"><b>Month</b></font><br>-->
  <table border="0">      
  <?php $sql=mysql_query("SELECT COUNT(*) AS repetitions, `EmployeeID`, Month, `Status` FROM y".$_SESSION['FYearId']."_monthexpensefinal WHERE `YearId`=".$_SESSION['FYearId']." GROUP BY EmployeeID, Month HAVING repetitions >1 ORDER BY EmployeeID ASC, Month ASC, id ASC",$con); while($res=mysql_fetch_assoc($sql)){ ?>
    <tr>
     <td colspan="6" style="font-size:14px;color:#FFFFFF;font-family:Georgia;"><?php echo 'Dup:&nbsp;'.$res['repetitions'].',&nbsp;Emp:&nbsp;'.$res['EmployeeID'].',&nbsp;Month:&nbsp;'.$res['Month']; ?></td>
    </tr>
    <tr>
	 <td align="left">
	  <table bgcolor="#FFF" border="1" cellspacing="0" cellspacing="1">
      <?php $sql2=mysql_query("select id,Month,Status,Crdate,DateOfSubmit from y".$_SESSION['FYearId']."_monthexpensefinal where EmployeeID=".$res['EmployeeID']." AND YearId=".$_SESSION['FYearId']." AND Month=".$res['Month']." order by id ASC",$con);
while($res2=mysql_fetch_assoc($sql2)){ ?>		  
	   <tr>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['id']; ?></td>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['Month']; ?></td>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['Status']; ?></td>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['Crdate']; ?></td>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['DateOfSubmit']; ?></td>
		<td style="width:50px;font-size:12px;" align="center"><span style="cursor:progress"><img src="images/delete.png" onClick="javascript:window.location='home.php?action=displayrec&v=&chkval=2&act=DelDupId&ern1=r114&ern2w=234&ern3y=10234&ern=4e2&erne=4e&ernw=234&erney=110022344&rernr=09drfGe&ernS=eewwqq&yAchQ=2&DupId=<?php echo $res2['id']; ?>'"/></span></td>
	   </tr>
       <?php } //while($res2=mysql_fetch_assoc($sql2)) ?>		   
	  </table>
	 </td>
	</tr>  
  <?php } //while($res=mysql_fetch_assoc($sql)) ?>		
  </table>
  </td>
 </tr>
 <?php } //if($_SESSION['EmployeeID']==169)?>
 <!--/***********************************/-->
 <!--/***********************************/-->
   <?php $sDl=mysql_query("select Driv_ExpiryDateTo from hrm_employee_personal where EmployeeID=".$_SESSION['EmployeeID'],$con2); $rDl=mysql_fetch_assoc($sDl); if(date("Y-m-d")<=date("Y-m-d",strtotime($rDl['Driv_ExpiryDateTo']))){ ?>
   <a class="btn btn-sm btn-primary" href="claim.php">&nbsp;&nbsp;<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Claim&nbsp;&nbsp;</a> 
   <?php }else{ echo '<font color="#FF0000">Your driving license no. is expired. Please renew.</font>';} ?>
   
   <a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
   
   <?php if($_SESSION['EmployeeID']==169){ ?>
	&nbsp;&nbsp;
	<a href="duplicate.php" target="_blank" style="font-size:12px;">Duplicate</a>
	<?php } ?>
	
   <br><br>
   <style>
      .blink {
        animation: blinker 0.8s linear infinite;
        color: #1c87c9;
        font-size: 15px; 
      }
      @keyframes blinker {
        50% {
          opacity: 0;
        }
      }
      .blink-one {
        animation: blinker-one 1s linear infinite;
      }
      @keyframes blinker-one {
        0% {
          opacity: 0;
        }
      }
      .blink-two {
        animation: blinker-two 1.8s linear infinite;
      }
      @keyframes blinker-two {
        100% {
          opacity: 0;
        }
      }
    </style>
   
   <?php /*	
   <span class="blink"><font style="color:#FF0000;"> Please re-login, If data is blank.</font></blink></span>
   <br><br>
   */ ?>
   
   <?php if($_SESSION['FYearId']==2){ ?> 
   <span class="blink"><font style="color:#FF0000;">For the <b>April</b> month claim, please login financial year 2021-2022.</font></blink></span>
   <br><br>
   <?php } ?>
   
   
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
  <?php $di=1; $d=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `CrBy`='".$_SESSION['EmployeeID']."' and (`ClaimStatus`='Submitted' or `ClaimStatus`='Draft') and AttachTo=0 order by ExpId asc"); while($dlist=mysql_fetch_assoc($d)){ ?>
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

<!--<br />-->  

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
  $m=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and YearId=".$_SESSION['FYearId']." and `Status`='Open' group by Month order by Month asc"); 
  while($mlist=mysql_fetch_assoc($m))
  { 
    if(date('m', mktime(0,0,0,$mlist['Month'], 1, date('Y'))) != date("m")){ $popupORnot="yes"; } 
	
	$t=mysql_query("SELECT count(*) as TotC FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and ClaimMonth='".$mlist['Month']."' and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'"); $tclaim=mysql_fetch_assoc($t);
    if($tclaim['TotC']>0)
	{
  ?>
  <tr>
   <td>
    <button class="btn btn-sm btn-warning" onclick="clGrpShowMonthDet(this,'<?=$mlist['Month']?>','<?=$_SESSION['EmployeeID']?>')" style="width:100%;"><?=strtoupper(date('M', mktime(0,0,0,$mlist['Month'], 1, date('Y'))));?></button>
    <input type="hidden" id="md<?=$mlist['Month']?>" value="close">
    <input type="hidden" id="gmd<?=$mlist['Month']?>" value="close">
   </td>
   <td><span class="btn btn-sm btn-outline-primary font-weight-bold"><?=$tclaim['TotC']?></span></td>
   
   <?php $totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and ClaimMonth='".$mlist['Month']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and ClaimStatus='Filled'"); $clm=mysql_fetch_assoc($totpaid); ?><td><?php if($clm['paid']>0){ echo $clm['paid'].'/-'; } ?></td> 
   
   <?php $fdo=mysql_query("SELECT count(*) as TotFdo FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`>=1 and `ClaimAtStep`!=2 and `ClaimStatus`='Filled' and `FilledOkay`=1"); $fdo=mysql_fetch_assoc($fdo); 
    
	$totDen=mysql_query("SELECT count(*) as TotDen FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `ClaimMonth`='".$mlist['Month']."' and `CrBy`='".$_SESSION['EmployeeID']."' and FilledOkay=2 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0 and AttachTo=0"); $den=mysql_fetch_assoc($totDen); ?>			
   <td><?php if($fdo['TotFdo']>0){?><span class="btn btn-sm btn-outline-success font-weight-bold">&#10003;:<span class="badge badge-success" style="font-size:10px;"><?=$fdo['TotFdo']?></span><?php } ?>		
	<?php if($den['TotDen']>0){?><span class="btn btn-sm btn-outline-danger font-weight-bold">D:&nbsp;<span class="badge badge-danger" style="font-size: 10px;"><?=$den['TotDen']?></span></span><?php } ?></td>
	  
   <td><button class="btn btn-sm btn-primary" style="font-size:14px;float:center;" onclick="showMonthReport('<?=$_SESSION['EmployeeID']?>','<?=$mlist['Month']?>',<?=$_SESSION['FYearId']?>,2,1)"><i class="fa fa-table">&nbsp;Exp</i></button></td>  
   <td><?php //if($fdo['TotFdo']>0){ ?><button class="btn btn-sm btn-primary" style="font-size:14px;float:center;" onclick="submitmonthfill('<?=$mlist['Month']?>','<?=$_SESSION['EmployeeID']?>')"><i class="fa fa-">Submit</i></button><?php //} ?></td>
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
	  <th rowspan="2" scope="col" style="width:50px;vertical-align:middle;">Direct<br />Print</th>
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
  <?php $m=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and YearId=".$_SESSION['FYearId']." and `Status`='Closed' and Total_Claim>0 group by Month order by Month asc");
        $sn=1; while($mlist=mysql_fetch_assoc($m)){ ?>
   <tr>
	<td>
	 <button class="btn btn-sm btn-success" onclick="clGrpShowMonthDet(this,'<?=$mlist['Month']?>','<?=$_SESSION['EmployeeID']?>')" style="width:100%;"><?=date('M', mktime(0,0,0,$mlist['Month'], 1, date('Y')));?></button>
	 <input type="hidden" id="md<?=$mlist['Month']?>" value="close">
	 <input type="hidden" id="gmd<?=$mlist['Month']?>" value="close">
	</td>
	<td><span class="btn btn-sm btn-outline-primary font-weight-bold"><?=$mlist['Total_Claim'];?></span></td>
	<td><?php if($mlist['Claim_Amount']>0){echo intval($mlist['Claim_Amount']).'/-';} ?></td>
	<td style="cursor:pointer;text-decoration:underline;"><span onclick="FUnOPen(<?=$sn?>)">click</span></td>
	<td style="width:50px;"><?php if($mlist['Verified_Amount']>0){echo intval($mlist['Verified_Amount']).'/-';} ?></td>
	<td style="width:60px;"><?php if($mlist['Verified_Amount']>0){echo date("d-m-y",strtotime($mlist['Verified_Date']));}?></td>
	<td style="width:50px;"><?php if($mlist['Approved_Amount']>0){echo intval($mlist['Approved_Amount']).'/-';} ?></td>
	<td style="width:60px;"><?php if($mlist['Approved_Amount']>0){echo date("d-m-y",strtotime($mlist['Approved_Date']));}?></td>	  
	<td style="width:50px;"><?php if($mlist['Fin_PayAmt']>0){ echo intval($mlist['Fin_AdvancePay']+$mlist['Fin_PayAmt']).'/-';} ?></td>
	<td style="width:60px;"><?php if($mlist['Fin_PayAmt']>0){ echo date("d-m-y",strtotime($mlist['Fin_PayDate']));}?></td>	
	<td style="width:100px;"><input style="border:hidden;width:100%;" value="<?php echo ucwords(strtolower($mlist['Fin_PayRemark']));?>"/></td>				
	
	<td style="text-align:center;">
	    
	    <button class="btn btn-sm btn-primary" style="font-size: 12px;float:center;" onclick="showMonthReport('<?=$_SESSION['EmployeeID']?>','<?=$mlist['Month']?>',<?=$_SESSION['FYearId']?>,2,2)"><i class="fa fa-table">&nbsp;Exp</i></button>
	</td>	
	<td style="text-align:center;">
	    <a href="printdetailsemp.php?e=<?=$_SESSION['EmployeeID']?>&m=<?=$mlist['Month']?>&y=<?=$_SESSION['FYearId']?>&n=2&nn=2"><button class="btn btn-sm btn-primary" style="font-size: 12px;float:center;"><i>Print</i></button></a>
	</td>
    
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
		<button class="btn-primary btn-sm" style="font-size:10px;" onclick="FunSave(<?=$sn.','.$_SESSION['EmployeeID'].','.$mlist['Month'].','.$_SESSION['FYearId']?>)">save</button><?php } ?>
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



<script type="text/javascript" src="dt_jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
<!--<script type="text/javascript" src="dt_bootstrap/js/bootstrap.min.js"></script>-->
<script type="text/javascript" src="dt_js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="dt_js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<script type="text/javascript">
	$('.form_date').datetimepicker({
        language:  'us',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
    });
</script>


<script type="text/javascript">
 function FUnOPen(sn){ document.getElementById("Div"+sn).style.display='block'; }
 function FunClose(sn){ document.getElementById("Div"+sn).style.display='none'; }
 
 function FunSave(sn,eid,m,yid)
 {
   if(confirm('Are you sure?'))
   { 
     var pd=$('#PostDate'+sn).val(); 
	 var dn=$('#DocateNo'+sn).val();
	 var ag=$('#Agency'+sn).val();
	 $.post("courierajax.php",{act:"upcourierdetails",sn:sn,eid:eid,m:m,yid:yid,pd:pd,dn:dn,ag:ag},function(data){
	 if(data.includes('done')){ alert('Updated Successfully'); }else{ alert("Error"); }	});
   }
 }

 
 function showMonthReport(e,m,y,n,nn)
 {
  
  //window.open("printdetailsemp.php?e="+e+"&m="+m+"&y="+y+"&n="+n,"PForm","menubar=no,scrollbars=yes,resizable=no,directories=no,width=800,height=500" );
  
  var modal = document.getElementById('myModal');
		modal.style.display = "block";
		document.getElementById('claimlistfr').src="printdetailsemp.php?e="+e+"&m="+m+"&y="+y+"&n="+n+"&nn="+nn;
  
  
 }

	function okayall(emp,month,cgid){


		if (confirm('Are you sure to Mark Okay all filled claims?')){


			$.post("homeajax.php",{act:"okayAllFilledClaims",month:month,emp:emp,cgid:cgid},function(data){
				// console.log(data);
				// alert(data);
				if(data.includes('okay')){

					alert('Okay Successfully');
					// location.reload();
				}			
			});


			// okayAllFilledClaims

			// $.post("homeajax.php",{act:"clGrpShowMonthDet",month:month,emp:emp,cgid:cgid},function(data){

			// 	console.log(data);
			// 	if(data.includes('okay')){

			// 		alert('Okay Successfully');
			// 		// location.reload();
			// 	}
			// });
		}

	}

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



	function submitmonthfill(month,crby){

		if (confirm('Are you sure to Final Submit this month claims for Verifying?')){
			$.post("homeajax.php",{act:"submitmonthfill",month:month,crby:crby},function(data){ //alert(data);
				if(data.includes('submitted')){
					alert('Submitted Successfully');
					location.reload();
				}
			});
		}
	}
	function showexpdet(expid){ 
		var modal = document.getElementById('myModal');
		modal.style.display = "block";
		document.getElementById('claimlistfr').src="showclaim.php?expid="+expid;
	}

	function okcheck(chk,expid){
		if(chk.checked){
           	$('#okdenyspan'+expid).css('display', 'block');
        }else{
        	$('#okdenyspan'+expid).css('display', 'none');
        }
	}
	

	function showOkDenyForm(expid,act){

		var actionTaken=1;

		if(act=='okay'){

			if($('#claimamt'+expid)){
				var claimamt = parseInt($('#claimamt'+expid).val());
				var limitamt = parseInt($('#limitamt'+expid).val());

				if(claimamt > limitamt){
					$('#okdenyremark'+expid).removeClass('bg-outline-danger');
					$('#okdenyremark'+expid).addClass('bg-outline-success');
					$('#saveokbtn'+expid).show();
					
				}else{
					expfillok(expid);
					actionTaken=0;
				}
			}else{
				expfillok(expid);
				actionTaken=0;
			}
				
		}else if(act=='deny'){

			$('#okdenyremark'+expid).removeClass('bg-outline-success');
			$('#okdenyremark'+expid).addClass('bg-outline-danger');
			$('#savedenybtn'+expid).show();

		}

		if(actionTaken==1){
			$('#okdenyspan'+expid).show(1000);
			$('#stsdiv'+expid).hide(400);
			$('#okbtn'+expid).hide(500);
			$('#denybtn'+expid).hide(600);
		}


	}



	function expfillok(expid){


		var claimamt = parseInt($('#claimamt'+expid).val());
		var limitamt = parseInt($('#limitamt'+expid).val());

		var remark=$('#okdenyremark'+expid).val();

		if(claimamt > limitamt){
			if(remark !=''){
				if (confirm('Mark Ok to filled Claim ?')){
					
					$.post("homeajax.php",{act:"expfillok",expid:expid,remark:remark},function(data){
						if(data.includes('okay')){
							var okay="<div class='btn btn-sm btn-success'><i class='fa fa-check' aria-hidden='true'></i> Okay</div>";
							$('#okspanarea'+expid).html(okay);
						}
					});
				}
			}else if(remark ==''){
				alert("Can't submit blank remark");
				$('#okdenyremark'+expid).focus();
			}
		}else{
			
			if (confirm('Mark Ok to filled Claim ?')){
					
				$.post("homeajax.php",{act:"expfillok",expid:expid,remark:remark},function(data){
					if(data.includes('okay')){
						var okay="<div class='btn btn-sm btn-success'><i class='fa fa-check' aria-hidden='true'></i> Okay</div>";
						$('#okspanarea'+expid).html(okay);
					}
				});
			}
			
		}







		
	}

	function expfilldeny(expid){ //alert("bb");

		
		var remark=$('#okdenyremark'+expid).val();
		if(remark !=''){
			if (confirm('Show Deny form for filled Claim ?')){
				
				$.post("homeajax.php",{act:"expfilldeny",expid:expid,remark:remark},function(data){
					if(data.includes('okay')){
						var okay="<div class='btn btn-sm btn-danger'><i class='fa fa-times' aria-hidden='true'></i>Denied</div>";
						$('#okspanarea'+expid).html(okay);
					}
				});
			}
		}else if(remark ==''){
			alert("Can't submit blank remark");
			$('#okdenyremark'+expid).focus();
		}
	}



	function deactivate(t,expid){ //alert("aa");
		if (confirm("Do you want to Deactivate this claim ?")) {
			$.post("homeajax.php",{act:"deactivateclaim",expid:expid},function(data){
				if(data.includes('deactivated')){
					
					$(t).html('Deactivated');
				}
			});
		} else {
			
		}
		
	}

	function attachclaims(t,expid){
		if (confirm("Do you want to Attach Claims to This Claim ?")) {
			var modal = document.getElementById('myModal');
			modal.style.display = "block";
			document.getElementById('claimlistfr').src="attachclaim.php?expid="+expid;
		} else {
			
		}
		
	}

	
</script>



<!-- 
<?php if($fde>0){ ?>
 <button class="btn btn-sm btn-primary font-weight-bold"  onclick="submitmonthfill('<?=$mlist['Month']?>','<?=$_SESSION['EmployeeID']?>')">Final Submit</button>
<?php } ?> 
-->