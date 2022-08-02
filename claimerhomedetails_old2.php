
<div id="claimerdiv" class="col-lg-9  col-md-12 <?php if($_SESSION['EmpRole']=='E'){echo 'h-100';}?>" style="border-left:5px solid #d9d9d9;">
   
  <br /><!-- <a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
   
   <a class="btn btn-sm btn-primary" href="claim.php">&nbsp;&nbsp;<i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Claim&nbsp;&nbsp;</a><br />-->
   
   
   <!--/***********************************/-->
		 <!--/***********************************/-->
		 <?php if($_SESSION['EmployeeID']==169){ ?>
		 <?php 
		 if($_REQUEST['act']=='DelDupId' AND $_REQUEST['DupId']>0)
		 {  $sqlDel=mysql_query("delete from y".$_SESSION['FYearId']."_monthexpensefinal where id=".$_REQUEST['DupId'],$con); }
		 ?>
		 <tr>
	 <td><font style="font-size:14px;font-weight:bold;color:#FFFFFF;"><b>Month</b></font><br>
	   <table border="0">      
<?php $sql=mysql_query("SELECT COUNT(*) AS repetitions, `EmployeeID`, Month, `Status` FROM y".$_SESSION['FYearId']."_monthexpensefinal WHERE `YearId`=2 GROUP BY EmployeeID, Month HAVING repetitions >1 ORDER BY EmployeeID ASC, Month ASC, id ASC",$con);
while($res=mysql_fetch_assoc($sql)){ 
?>
        <tr>
		 <td colspan="6" style="font-size:14px;color:#FFFFFF;font-family:Georgia;"><?php echo 'Dup:&nbsp;'.$res['repetitions'].',&nbsp;Emp:&nbsp;'.$res['EmployeeID'].',&nbsp;Month:&nbsp;'.$res['Month']; ?></td>
		</tr>
		<tr>
		 <td align="left">
		  <table bgcolor="#FFF" border="1" cellspacing="0" cellspacing="1">
<?php $sql2=mysql_query("select id,Month,Status,Crdate,DateOfSubmit from y".$_SESSION['FYearId']."_monthexpensefinal where EmployeeID=".$res['EmployeeID']." AND YearId=2 AND Month=".$res['Month']." order by id ASC",$con);
while($res2=mysql_fetch_assoc($sql2)){ ?>		  
		   <tr>
		    <td style="width:100px;font-size:12px;" align="center"><?php echo $res2['id']; ?></td>
		    <td style="width:100px;font-size:12px;" align="center"><?php echo $res2['Month']; ?></td>
			<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['Status']; ?></td>
			<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['Crdate']; ?></td>
			<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['DateOfSubmit']; ?></td>
			<td style="width:50px;font-size:12px;" align="center"><span style="cursor:progress"><img src="images/delete.png" onClick="javascript:window.location='home.php?action=displayrec&v=&chkval=2&act=DelDupId&ern1=r114&ern2w=234&ern3y=10234&ern=4e2&erne=4e&ernw=234&erney=110022344&rernr=09drfGe&ernS=eewwqq&yAchQ=2&DupId=<?php echo $res2['id']; ?>'"/></span></td>
		   </tr>
<?php } ?>		   
		  </table>
		 </td>
		</tr>  
<?php } ?>		
	   </table>
	  </td>
	</tr>
	     <?php } ?>
		 <!--/***********************************/-->
		 <!--/***********************************/-->

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
                             &nbsp;&nbsp;
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

				$m=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and YearId=".$_SESSION['FYearId']." and `Status`!='Closed' group by Month order by Month asc"); 
				     // print_r("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and YearId=".$_SESSION['FYearId']." and `Status`!='Closed' order by Month asc");
         //        die();
				while($mlist=mysql_fetch_assoc($m)){ 

                      $m2=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and YearId=".$_SESSION['FYearId']." and Month='".$mlist['Month']."' and `Status`='Closed'"); 
				$rowm2=mysql_num_rows($m2);
				
				if($rowm2==0){

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
				  		$fde=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`>=1 and `ClaimAtStep`!=2 and `ClaimStatus`='Filled'"); $fde=mysql_num_rows($fde);
						$fdo=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`>=1 and `ClaimAtStep`!=2 and `ClaimStatus`='Filled' and `FilledOkay`=1"); $fdo=mysql_num_rows($fdo);
						
						$totDen=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `ClaimMonth`='".$mlist['Month']."' and `CrBy`='".$_SESSION['EmployeeID']."' and FilledOkay=2 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' and FilledBy>0 and AttachTo=0"); $den=mysql_num_rows($totDen);
							
				  		if($tde>0){ ?> 
						 <span class="btn btn-sm btn-outline-warning font-weight-bold">P:&nbsp;<span class="badge badge-warning" style="font-size: 10px;"><?=$sde?></span></span> 
						 <span class="btn btn-sm btn-outline-info font-weight-bold">F:&nbsp;<span class="badge badge-info" style="font-size: 10px;"><?=$fde?></span></span>
						 <span class="btn btn-sm btn-outline-danger font-weight-bold">D:&nbsp;<span class="badge badge-danger" style="font-size: 10px;"><?=$den?></span></span>
						 <span class="btn btn-sm btn-outline-success font-weight-bold">&#10003;:&nbsp;<span class="badge badge-success" style="font-size: 10px;"><?=$fdo?></span></span> 
				 		<?php } ?>
				 </td>
				  
				  <td style="text-align:center;">
				  	<button class="btn btn-sm btn-primary" style="font-size: 15px;float: center;" onclick="showMonthReport('<?=$_SESSION['EmployeeID']?>','<?=$mlist['Month']?>',<?=$_SESSION['FYearId']?>,2)"><i class="fa fa-table">&nbsp;Expense</i></button>

				  	<?php $fdee=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`>=1 and `ClaimStatus`='Filled'"); $fdee=mysql_num_rows($fdee);
					if($fdee>0){ ?>
					 <button class="btn btn-sm btn-primary font-weight-bold"  onclick="submitmonthfill('<?=$mlist['Month']?>','<?=$_SESSION['EmployeeID']?>')">Final Submit</button>
					<?php } ?> 
				  	
				  </td>
				 </tr>
				 <tr id="g<?=$mlist['Month']?>"></tr>
				 <tr id="<?=$mlist['Month']?>"></tr>
				<?php 
				} //if($rowm2==0)
				
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
			  	
			  	$m=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and YearId=".$_SESSION['FYearId']." and `Status`='Closed' group by Month order by Month asc");

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

                        <?php $totpaid=mysql_query("SELECT SUM(FilledTAmt) as Filled, SUM(ApprTAmt) as Apprd FROM `y".$_SESSION['FYearId']."_expenseclaims` e, claimtype c WHERE `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and ClaimMonth='".$mlist['Month']."' and c.ClaimId=e.ClaimId and FilledOkay=1 and e.ClaimStatus!='Deactivate' and e.ClaimStatus!='Draft' and e.FilledBy>0"); $clm=mysql_fetch_assoc($totpaid); 
						      $totpaid2=mysql_query("SELECT Fin_PayAmt,Fin_AdvancePay FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `Month`='".$mlist['Month']."' and `YearId`='".$_SESSION['FYearId']."' and `EmployeeID`='".$_SESSION['EmployeeID']."'");  $clm2=mysql_fetch_assoc($totpaid2);
						?>	
						<td style="vertical-align:middle;">
				  		<?php echo $clm['Filled']; if($clm['Filled']>0){echo '/-';} ?>
						</td>
						
						<td style="vertical-align:middle;">
				  		<?php if($clm['Apprd']>0){ echo $clm['Apprd']; if($clm['Apprd']>0){echo '/-';} } ?>
				  		
				  		<?php //if($clm2['Fin_PayAmt']>0 OR $clm2['Fin_AdvancePay']>0){ echo $clm['Filled']; if($clm['Filled']>0){echo '/-';} } ?>
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
  
  //window.open("printdetailsemp.php?e="+e+"&m="+m+"&y="+y+"&n="+n,"PForm","menubar=no,scrollbars=yes,resizable=no,directories=no,width=800,height=500" );
  
  var modal = document.getElementById('myModal');
		modal.style.display = "block";
		document.getElementById('claimlistfr').src="printdetailsemp.php?e="+e+"&m="+m+"&y="+y+"&n="+n;
  
  
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

	function expfilldeny(expid){

		
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



	function deactivate(t,expid){
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