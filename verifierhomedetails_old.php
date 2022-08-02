<?php

// function getClaimType($cid){
// 	include 'config.php';
// 	$c=mysql_query("SELECT ClaimName FROM `claimtype` where ClaimId=".$cid);
// 	$ct=mysql_fetch_assoc($c);
// 	return $ct['ClaimName'];
// }
// function getUser($u){
// 	include 'config.php';
// 	$u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$u,$con2);
// 	$un=mysql_fetch_assoc($u);
// 	return $un['Fname'].' '.$un['Sname'].' '.$un['Lname'];
// }
?>
<div class="col-md-9 h-100" style="border-left:5px solid #d9d9d9;">
    <br>
        <font style="font-size:14px;">Month :</font>&nbsp;
		<script type="text/javascript">function selMonth(v){window.location="home.php?action=displayrec&v="+v;}</script>
		       <select style="font-size:14px;" onchange="selMonth(this.value)">
			      <option value="0" <?php if($_REQUEST['v']==''){echo 'selected';}?>>Select</option>
		          <option value="03" <?php if($_REQUEST['v']==3){echo 'selected';}?>>March</option>
				  <option value="02" <?php if($_REQUEST['v']==2){echo 'selected';}?>>February</option>
				  <option value="01" <?php if($_REQUEST['v']==1){echo 'selected';}?>>January</option>
				  <option value="12" <?php if($_REQUEST['v']==12){echo 'selected';}?>>December</option>
				  <option value="11" <?php if($_REQUEST['v']==11){echo 'selected';}?>>November</option>
				  <option value="10" <?php if($_REQUEST['v']==10){echo 'selected';}?>>October</option>
				  <option value="09" <?php if($_REQUEST['v']==9){echo 'selected';}?>>September</option>
				  <option value="08" <?php if($_REQUEST['v']==8){echo 'selected';}?>>August</option>
				  <option value="07" <?php if($_REQUEST['v']==7){echo 'selected';}?>>July</option>
				  <option value="06" <?php if($_REQUEST['v']==6){echo 'selected';}?>>June</option>
				  <option value="05" <?php if($_REQUEST['v']==5){echo 'selected';}?>>May</option>
				  <option value="04" <?php if($_REQUEST['v']==4){echo 'selected';}?>>April</option>
			   </select>
&nbsp;&nbsp;&nbsp; 

<?php /*
 $data= mysql_query("SELECT rEmployeeID FROM dataentry_reporting GROUP BY rEmployeeID");
			while($rec=mysql_fetch_array($data)){ $array_data[]=$rec['rEmployeeID']; }
			$str_data = implode(',', $array_data);

			$empshowcond=" rl.`EmployeeID` NOT IN (".$str_data.") AND rl.`R1` NOT IN (".$str_data.") AND rl.`R2` NOT IN (".$str_data.") AND rl.`R3` NOT IN (".$str_data.") AND rl.`R4` NOT IN (".$str_data.") AND rl.`R5` NOT IN (".$str_data.")";
			
			$crcond="EmpType='E'";
 ?>

 <font style="font-size:14px;">User :</font>&nbsp;
               <select style="font-size:14px;" onchange="selMonth(0,this.value)">
				      		
				      		<?php
							$u=mysql_query("select h.EmployeeID,h.Fname,h.Sname,h.Lname from `y".$_SESSION['FYearId']."_monthexpensefinal` e, ".dbemp.".hrm_employee h, ".dbemp.".hrm_employee_reporting her, ".dbemp.".hrm_sales_reporting_level rl where h.EmpStatus='A' and ".$crcond." and h.EmployeeID=e.EmployeeID group by h.EmployeeID order by h.Fname asc");
				      		if(mysql_num_rows($u)>1){
				      		?>
				      		<option value="ALL">ALL</option>
				      		<?php
				      		}
				      		while($us=mysql_fetch_assoc($u)){
				      		?>
				      		<option value="<?=$us['EmployeeID']?>" <?php if(isset($_REQUEST['u']) && $_REQUEST['u']==$us['EmployeeID']){echo 'selected';} ?>><?=$us['Fname'].' '.$us['Sname'].' '.$us['Lname']?></option>
				      		<?php
					      	}
				      		?>
				      	</select>			   
	*/ ?>		   
&nbsp;&nbsp;&nbsp; 
		<a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a><br>
		<div class="row">
			<div class="col-lg-11 shadow">
				<br>
				<h5 ><small class="font-weight-bold text-muted"> Claims To Be Verified</small> </h5> 
				
				
				<table class="estable table shadow ">
				  <thead class="thead-dark">
				    <tr>
				      <th scope="col" style="width:130px;">Claimer</th>
				      <th scope="col" style="width: 200px;">Month</th>
				      <th scope="col">Claims</th>
				      <th scope="col">Action</th>
				      
				    </tr>
				  </thead>
				  
<?php if($_REQUEST['action']=='displayrec'){ ?>					  
				  <tbody>
				  	<?php
				  	$m=mysql_query("SELECT `ClaimMonth`,`CrBy` FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE  `ClaimAtStep`=3 AND FilledOkay=1 and ClaimYearId=".$_SESSION['FYearId']." and ClaimMonth=".$_REQUEST['v']." group by ClaimMonth,CrBy order by ClaimMonth asc");


					while($mlist=mysql_fetch_assoc($m)){
						
						$e=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE  `CrBy`='".$mlist['CrBy']."' and `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `ClaimAtStep`=3 order by ExpId asc");
						$enum=mysql_num_rows($e);

						if($enum > 0){
					  	?>
					  	<tr>
					  		<td><?php
					  			// include 'config.php';
								$u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$mlist['CrBy'],$con2);
								$un=mysql_fetch_assoc($u);

								echo $un['Fname'].' '.$un['Sname'].' '.$un['Lname'];


					  		// getUser($mlist['CrBy'])
					  		?></td>
					  		<td>
					  			<a href="#" onclick="showmonthdet('<?=$mlist['ClaimMonth']?>','Open','<?=$mlist['CrBy']?>','Filled')">
					  				<?=date('F', mktime(0,0,0,$mlist['ClaimMonth'], 1, date('Y')));?>
				  				</a>
				  			</td>
					  		<td>
					  			
					  			<?php
					  			$tv=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$mlist['CrBy']."' and `ClaimAtStep`=3");
					  			$sv=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$mlist['CrBy']."' and `ClaimAtStep`=3 and `ClaimStatus`='Filled'");
					  			$fv=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$mlist['CrBy']."' and `ClaimAtStep`=3 and `ClaimStatus`='Verified'");
								$tv=mysql_num_rows($tv);
								$sv=mysql_num_rows($sv);
								$fv=mysql_num_rows($fv);
								if($tv>0){
					  			?>	
					  			
								 <span class="btn btn-sm btn-outline-primary font-weight-bold">Total: <?=$tv?></span>
								 <span class="btn btn-sm btn-secondary font-weight-bold"><?=$sv?></span>
								 <span class="btn btn-sm btn-success font-weight-bold"><?=$fv?></span>
								 <input type="hidden" id="sts<?=$mlist['CrBy']?><?=$mlist['ClaimMonth']?>Filled" value="close">
								<?php
								}
								?>
					  		</td>
					  		<td><button type="button" class="btn btn-sm btn-primary" onclick="submittoapprover('<?=$mlist['ClaimMonth']?>','<?=$mlist['CrBy']?>')" <?php if($fv==0){echo 'disabled';}?>>Submit To Approver</button></td>
					  	</tr>
					  	<tr id="<?=$mlist['CrBy']?><?=$mlist['ClaimMonth']?>Filled">
						  		
					  	</tr>
					  	<?php
					  	}
				  	
					}
				  	?>
				  </tbody>
				</table>
				
			</div>

		</div>

		<br>
		<div class="row">
			<div class="col-lg-11 shadow">
				<br>
				<h5 ><small class="font-weight-bold text-muted"> Claim Submitted (Approver)</small> </h5> 
				
				
				<table class="estable table shadow ">
				  <thead class="thead-dark">
				    <tr>
				      <th scope="col" style="width:150px;">Claimer</th>
				      <th scope="col" style="width: 200px;">Month</th>
				      <th scope="col">Claims</th>
				      
				    </tr>
				  </thead>
				  <tbody>
				  	<?php
				  	$m=mysql_query("SELECT `ClaimMonth`,`CrBy` FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `VerifyBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`>=4 and ClaimYearId=".$_SESSION['FYearId']." and ClaimMonth=".$_REQUEST['v']." group by ClaimMonth,CrBy order by ClaimMonth asc");

					while($mlist=mysql_fetch_assoc($m)){

						$e=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `ClaimAtStep`>=4 and `CrBy`='".$mlist['CrBy']."' order by ExpId asc");

						$enum=mysql_num_rows($e);
						if($enum > 0){
					  	?>
					  	<tr>
					  		<td><?php
                            
                            	$u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$mlist['CrBy'],$con2);
								$un=mysql_fetch_assoc($u);

								echo $un['Fname'].' '.$un['Sname'].' '.$un['Lname'];


					  		// getUser($mlist['CrBy'])
					  		?></td>
					  		<td>
					  			<a href="#" onclick="showmonthdet('<?=$mlist['ClaimMonth']?>','Open','<?=$mlist['CrBy']?>','Verified')">
					  				<?=date('F', mktime(0,0,0,$mlist['ClaimMonth'], 1, date('Y')));?>	
				  				</a>
				  			</td>
					  		<td>
					  			<?=$enum?>
				  				<input type="hidden" id="sts<?=$mlist['CrBy']?><?=$mlist['ClaimMonth']?>Verified" value="close">
				  			</td>
					  		
					  	</tr>
					  	<tr id="<?=$mlist['CrBy']?><?=$mlist['ClaimMonth']?>Verified">
						  		
					  	</tr>
					  	<?php
						}
					}
				  	?>
				  </tbody>
				  
<?php } //if($_REQUEST['action']=='displayrec') ?>					  
				  
				</table>
				
			</div>
		</div>
		<br>

	
		
	
</div>

<script type="text/javascript">

	// function showtobeverified(month,sts,emp){
		
	// 	var modal = document.getElementById('myModal');
	// 	modal.style.display = "block";
	// 	document.getElementById('claimlistfr').src='showclaimslist.php?action=verify&month='+month+'&sts='+sts+'&emp='+emp;
	// }
	function showmonthdet(month,sts,emp,csts){

		var status=document.getElementById('sts'+emp+month+csts);
		var modal = document.getElementById(emp+month+csts);
		if(status.value=='close'){
			$.post("claimlistajax.php",{act:"monthdettoverifier",month:month,sts:sts,csts:csts,emp:emp},function(data){
				modal.innerHTML = data;
			});
			
			status.value="open";

		}else if(status.value=='open'){
			
			modal.innerHTML = '';
			status.value="close";
		}
	}
	// function showmonthdet(month,sts,emp){
		
	// 	var modal = document.getElementById('myModal');
	// 	modal.style.display = "block";
	// 	document.getElementById('claimlistfr').src='showclaimslist.php?action=approve&month='+month+'&sts='+sts+'&emp='+emp;
	// }
	function submittoapprover(month,crby){
		if (confirm('Are you sure to Final Submit this month claims to Approver?')){
			$.post("homeajax.php",{act:"submittoapprover",month:month,crby:crby},function(data){
				if(data.includes('submitted')){
					alert('Submitted to Approver Successfully');
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

	function verifyClaim(expid){
		var v=parseInt(document.getElementById(expid+'verifiedtamt').value);
		var r=document.getElementById(expid+'verifiedtremark').value;
		
		$.post("claimajax.php",{act:"verifyClaim",expid:expid,vtamt:v,verifiedtremark:r},function(data){
			
			if(data.includes('verified')){
				document.getElementById(expid+'Status').innerHTML='Verified'; 
				document.getElementById(expid+'btn').innerHTML='View'; 
				alert('Verified Successfully');
				document.getElementById(expid+'verifyaction').innerHTML=''; 
			}
		});
	}

	function showbtn(chk,expid){
		if (chk.checked) {
           $('#'+expid+'verifybtn').prop('disabled', false);
        }else{
        	$('#'+expid+'verifybtn').prop('disabled', true);
        }
	}

	function checkrange(thisamt,mainamt){
    
	    var t=parseInt(thisamt.value);
	    var m=parseInt(mainamt);
	    //if(t>m){
	        //$(thisamt).val(m);
	        //alert("You can't provide more amount than claimed amount");
	    //}
	    
	}
	function isNumber(evt) {
	    evt = (evt) ? evt : window.event;
	    var charCode = (evt.which) ? evt.which : evt.keyCode;
	    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
	        return false;
	    }
	    return true;
	}
</script>