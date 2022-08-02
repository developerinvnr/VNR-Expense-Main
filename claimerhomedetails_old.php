
<div id="claimerdiv" class="col-lg-9  col-md-12 <?php if($_SESSION['EmpRole']=='E'){echo 'h-100';}?>" style="border-left:5px solid #d9d9d9;">
   
   <br /><a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
   <a class="btn btn-sm btn-primary" href="claim.php"><i class="fa fa-plus" aria-hidden="true"></i> Claim</a>
	
	
	
	<div class="row" style="padding-top:8px;">
		<div class="col-lg-9 shadow  table-responsive">
			<h6 style="padding-top:8px;"><small class="font-weight-bold text-muted"><i>Opened Months</i></small> </h6> 
			
			
			<table class="estable table shadow" style="width:100%;">
			  <thead class="thead-dark">
			    <tr>
			      <th scope="col" style="width:60px;">Month</th>
			      <!-- <th scope="col" style="width:60px;">Status</th> -->
			      <th scope="col" style="width:40px;">Total<br>Claims</th>
			      <!-- <th scope="col">Me <br><small>Step 1</small></th> -->
			      <th scope="col" style="width:140px;">Data Entry<!-- <br><small>Step 2</small> --></th>
			      <th scope="col"></th>
			      <!-- <th scope="col">Verifier<br><small>Step 3</small></th> -->
			      <!-- <th scope="col">Approver<br><small>Step 4</small></th> -->
			      <!-- <th scope="col">Finance<br><small>Step 5</small></th> -->
			      <!-- <th scope="col" style="width:70px;"><small>Final Pay</small><Br>Estimation</th> -->
			      
			    </tr>
			  </thead>
			  <tbody>
			  	<?php
			  	
			  	$m=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and YearId=".$_SESSION['FYearId']." and `Status`!='Closed' order by Month asc");

				while($mlist=mysql_fetch_assoc($m)){
					
				  	?>
				  	<tr>
				  		<td>
                                                     <button class="btn btn-sm btn-warning" onclick="clshowmonthdet(this,'<?=$mlist['Month']?>','<?=$_SESSION['EmployeeID']?>')" style="width:100%;"><?=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y')));?></button>
                                                        
                                                        <?php /*
				  			<a href="javascript:void(0)" onclick="clshowmonthdet(this,'<?=$mlist['Month']?>','<?=$_SESSION['EmployeeID']?>')">
				  				<?=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y')));?>	
			  				</a>
                                                        */ ?>
			  				<input type="hidden" id="md<?=$mlist['Month']?>" value="close">
			  			</td>
			  			<!--  <td><button class="btn btn-sm btn-success" onclick="">Open</button></td> -->
				  		<td>
				  			<?php
				  			$t=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");
							$tclaim=mysql_num_rows($t);?>
							<span class="btn btn-sm btn-outline-primary font-weight-bold"><?=$tclaim?></span>
				  		</td>
				  		<!-- 
				  		<td>
				  			<?php
				  			$tme=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`=1 and `ClaimStatus`='Saved'");
							if(mysql_num_rows($tme)>0){
				  			?>	
				  			<span class="btn btn-sm btn-success font-weight-bold"><?=mysql_num_rows($tme)?></span>
							 <button class="btn btn-sm btn-primary font-weight-bold" onclick="submitmonthexp('<?=$mlist['Month']?>')">Send</button>
				  			<?php
					  		}
					  		?>
				  		</td> 
					  	-->
				  		<td>
				  			<?php
				  			
				  			$tde=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep` IN (1,2) and ClaimStatus!='Saved'");
				  			$sde=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`=2 and `ClaimStatus`='Submitted'");
				  			$fde=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`=1 and `ClaimStatus`='Filled'");
							$tde=mysql_num_rows($tde);
							$sde=mysql_num_rows($sde);
							$fde=mysql_num_rows($fde);
							if($tde>0){
				  			?>	
                                                       				   
							 <span class="btn btn-sm btn-outline-warning font-weight-bold">Pending: <?=$sde?></span> 
							 <span class="btn btn-sm btn-outline-success font-weight-bold">Filled: <?=$fde?></span> 
							 
							 <?php /*
							 <span class="btn btn-sm btn-outline-primary font-weight-bold">Total: <?=$tde?></span> 
							 <span class="btn btn-sm btn-secondary font-weight-bold"><?=$sde?></span> -->
							 <span class="btn btn-sm btn-success font-weight-bold">:<?=$fde?></span>
							 */ ?>
							 
							 <?php /* <?php if($fde>0){ ?>
							 <button class="btn btn-sm btn-primary font-weight-bold"  onclick="submitmonthfill('<?=$mlist['Month']?>','<?=$_SESSION['EmployeeID']?>')">Final Submit</button>
							<?php }  */ ?>
							<?php
							}
							?>
							
				  		</td>
				  		<?php /*
				  		<td>
				  			<?php
				  			$tv=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`=3");
				  			$sv=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`=3 and `ClaimStatus`='Filled'");
				  			$fv=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`=3 and `ClaimStatus`='Verified'");
							$tv=mysql_num_rows($tv);
							$sv=mysql_num_rows($sv);
							$fv=mysql_num_rows($fv);
							if($tv>0){
				  			?>	
				  			
							 <span class="btn btn-sm btn-outline-primary font-weight-bold">Total: <?=$tv?></span>
							 <span class="btn btn-sm btn-secondary font-weight-bold"><?=$sv?></span>
							 <span class="btn btn-sm btn-success font-weight-bold"><?=$fv?></span>
							 
							<?php
							}
							?>
							
				  		</td>
				  		<td>
				  			<?php
				  			$ta=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`=4");
				  			$sa=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`=4 and `ClaimStatus`='Verified'");
				  			$fa=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`=4 and `ClaimStatus`='Approved'");
							$ta=mysql_num_rows($ta);
							$sa=mysql_num_rows($sa);
							$fa=mysql_num_rows($fa);
							if($ta>0){
				  			?>	
				  			
							 <span class="btn btn-sm btn-outline-primary font-weight-bold">Total: <?=$ta?></span>
							 <span class="btn btn-sm btn-secondary font-weight-bold"><?=$sa?></span>
							 <span class="btn btn-sm btn-success font-weight-bold"><?=$fa?></span>
							 
							<?php
							}
							?>
							
				  		</td>
				  		<td>
				  			<?php
				  			$tf=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`=5");
				  			$sf=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`=5 and `ClaimStatus`='Approved'");
				  			$ff=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`=5 and `ClaimStatus`='Financed'");
							$tf=mysql_num_rows($tf);
							$sf=mysql_num_rows($sf);
							$ff=mysql_num_rows($ff);
							if($tf>0){
				  			?>	
				  			
							 <span class="btn btn-sm btn-outline-primary font-weight-bold">Total: <?=$tf?></span>
							 <span class="btn btn-sm btn-secondary font-weight-bold"><?=$sf?></span>
							 <span class="btn btn-sm btn-success font-weight-bold"><?=$ff?></span>
							 
							<?php
							}
							?>
							
				  		</td>
				  		<td>
				  			<?php
				  			$totpaid=mysql_query("SELECT SUM(FinancedTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

				  			$clm=mysql_fetch_assoc($totpaid);
				  			
				  			echo $clm['paid'];
							
							?>
				  		</td>
				  		 */ ?>
				  		 <td></td>
				  	</tr>
				  	<tr id="<?=$mlist['Month']?>">
				  		
				  	</tr>
				  	<?php
				  	
				}
			  	?>
			  </tbody>
			</table>
			
		</div>
	</div>
	<br>
	
	<div class="row">
		<div class="col-lg-9 shadow">
			<h6 style="padding-top:8px;"><small class="font-weight-bold text-muted"><i>Closed Months</i></small> </h6> 
			
			
			<table class="estable table shadow" style="width:100%;">
			  <thead class="thead-dark">
			    <tr>
			      <th scope="col" style="width:60px;">Month</th>
			      <!-- <th scope="col" style="width:60px;">Status</th> -->
			      <th scope="col" style="width:50px;">Total<br>Claims</th>
			      <th scope="col" style="width:90px;">Total Claimed<br>Amount</th>
			      <th scope="col" style="">Total Paid<br>Amount</th>

			    </tr>
			  </thead>
			  <tbody>
			  	<?php
			  	
			  	$m=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and YearId=".$_SESSION['FYearId']." and `Status`='Closed' order by Month asc");

				while($mlist=mysql_fetch_assoc($m)){
					
				  	?>
				  	<tr>
				  		<td>
							  
                                                  <button class="btn btn-sm btn-success" onclick="clshowmonthdet(this,'<?=$mlist['Month']?>','<?=$_SESSION['EmployeeID']?>')" style="width:100%;"><?=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y')));?></button>
                                                      <?php /*
				  			<a href="javascript:void(0)" onclick="clshowmonthdet(this,'<?=$mlist['Month']?>','<?=$_SESSION['EmployeeID']?>')">
				  				<?=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y')));?>
			  				</a>
                                                        */ ?> 
			  				<input type="hidden" id="md<?=$mlist['Month']?>" value="close">
			  			</td>
			  			<!-- <td><button class="btn btn-sm btn-secondary" onclick="">Closed</button></td> -->
				  		<td>
							
				  			<?php
				  			$t=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");
							$tclaim=mysql_num_rows($t);?>
							<span class="btn btn-sm btn-outline-primary font-weight-bold"><?=$tclaim?></span>
				  		</td>

				  		<td class="text-right">
				  			<?php
				  			$totpaid=mysql_query("SELECT SUM(FilledTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

				  			$clm=mysql_fetch_assoc($totpaid);
				  			
				  			echo $clm['paid'];
							
							?>
				  		</td>
				  		
				  		<td class="text-right">
				  			<?php
				  			$totpaid=mysql_query("SELECT SUM(FinancedTAmt) as paid FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['Month']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$_SESSION['EmployeeID']."'");

				  			$clm=mysql_fetch_assoc($totpaid);
				  			
				  			echo $clm['paid'];
							
							?>
				  		</td>
				  		<!-- <td></td> -->
				  		
				  	</tr>
				  	<tr id="<?=$mlist['Month']?>">
				  		
				  	</tr>
				  	<?php
				  	
				}
			  	?>
			  </tbody>
			</table>
			
		</div>
	</div>
</div>



<script type="text/javascript">
	function clshowmonthdet(t,month,emp){
		var sts=document.getElementById('md'+month);
		var modal = document.getElementById(month);
		if(sts.value=='close'){
			$.post("claimlistajax.php",{act:"monthdettoclaimer",month:month,emp:emp},function(data){
				modal.innerHTML = data;
				var sh=window.screen.availHeight;
				var sw=window.screen.availWidth;

				if(sh>sw){
					$('#clcldetdiv').prop('style','width:320px;overflow:auto !important;');
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
	function submitmonthfill(month,crby){
		if (confirm('Are you sure to Final Submit this month claims for Verifying?')){
			$.post("homeajax.php",{act:"submitmonthfill",month:month,crby:crby},function(data){
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
           	$('#okbtn'+expid).prop('disabled', false);
        }else{
        	$('#okbtn'+expid).prop('disabled', true);
        }
	}
	function expfillok(expid){
		$.post("homeajax.php",{act:"expfillok",expid:expid},function(data){
			if(data.includes('okay')){
				var okay="<div class='btn btn-sm btn-success'><i class='fa fa-check' aria-hidden='true'></i> Okay</div>";
				$('#okspanarea'+expid).html(okay);
			}
		});
	}
	
</script>



<!-- 
<?php if($fde>0){ ?>
 <button class="btn btn-sm btn-primary font-weight-bold"  onclick="submitmonthfill('<?=$mlist['Month']?>','<?=$_SESSION['EmployeeID']?>')">Final Submit</button>
<?php } ?> 
-->