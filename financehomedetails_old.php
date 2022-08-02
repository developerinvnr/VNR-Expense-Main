<?php

function getClaimType($cid){
	include "config.php";
	$c=mysql_query("SELECT ClaimName FROM `claimtype` where ClaimId=".$cid);
	$ct=mysql_fetch_assoc($c);
	return $ct['ClaimName'];
}
function getUser($u){
	include "config.php";
	$u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$u, $con2);
	$un=mysql_fetch_assoc($u);
	return $un['Fname'].' '.$un['Sname'].' '.$un['Lname'];
}
?>
<div class="col-md-9 h-100" style="border-left:5px solid #d9d9d9;">
		<br><a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a><br>
		<div class="row">
			<div class="col-lg-11 shadow">
				<br>
				<h5 ><small class="font-weight-bold text-muted"> Claims To Be Financed</small> </h5> 
				
				
				<table class="estable table shadow">
				  <thead class="thead-dark">
				    <tr>
				      <th scope="col" style="width: 10px;">S.No</th>
				      <th scope="col" style="width: 200px;">Claimer</th>
				      <th scope="col" style="width: 100px;">Month</th>
				      <th scope="col" style="width: 100px;">Claims</th>
				      <th scope="col" style="width: 100px;">Action</th>
				      <th scope="col"></th>
				      
				      
				    </tr>
				  </thead>
				  <tbody>
				  	<?php
				  	$m=mysql_query("SELECT `ClaimMonth`,`CrBy` FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE  `ClaimAtStep`=5  and ClaimYearId=".$_SESSION['FYearId']." group by ClaimMonth,CrBy order by ClaimMonth asc");

				  	$i=1;
					while($mlist=mysql_fetch_assoc($m)){
						
						$e=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE  `CrBy`='".$mlist['CrBy']."' and `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `ClaimAtStep`=5  order by ExpId asc");
						$enum=mysql_num_rows($e);

						if($enum > 0){
					  	?>
					  	<tr>
					  		<td>
					  			<?=$i?>
					  		</td>
					  		<td>
					  			<?=getUser($mlist['CrBy'])?>
					  		</td>
					  		<td>
					  			<a href="#" onclick="showmonthdet('<?=$mlist['ClaimMonth']?>','Open','<?=$mlist['CrBy']?>','Approved')">
					  				<?=date('F', mktime(0,0,0,$mlist['ClaimMonth'], 1, date('Y')));?>
				  				</a>
				  			</td>
					  		<td>
					  			<?php
					  			$tf=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$mlist['CrBy']."' and `ClaimAtStep`=5");
					  			$sf=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$mlist['CrBy']."' and `ClaimAtStep`=5 and `ClaimStatus`='Approved'");
					  			$ff=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$mlist['CrBy']."' and `ClaimAtStep`=5 and `ClaimStatus`='Financed'");
								$tf=mysql_num_rows($tf);
								$sf=mysql_num_rows($sf);
								$ff=mysql_num_rows($ff);
								if($tf>0){
					  			?>	
					  			
								 <span class="btn btn-sm btn-outline-primary font-weight-bold">Total: <?=$tf?></span>
								 <span class="btn btn-sm btn-secondary font-weight-bold"><?=$sf?></span>
								 <span class="btn btn-sm btn-success font-weight-bold"><?=$ff?></span>
								 <input type="hidden" id="sts<?=$mlist['CrBy']?><?=$mlist['ClaimMonth']?>Approved" value="close">
								 
								<?php
								}
								?>
					  		</td>
					  		<td>

					  			<?php

					  			$name=getUser($mlist['CrBy']);
					  			$month=date('F', mktime(0,0,0,$mlist['ClaimMonth'], 1, date('Y')));

					  			?>
					  			<button type="button" class="btn btn-sm btn-warning" onclick="closeClaimMonth('<?=$mlist['ClaimMonth']?>','<?=$mlist['CrBy']?>','<?=$name?>','<?=$month?>')" <?php if($ff==0){echo 'disabled';}?>>Close Month</button>

					  			

					  		</td>
					  		<td></td>

					  	</tr>
					  	<tr id="<?=$mlist['CrBy']?><?=$mlist['ClaimMonth']?>Approved">
						  		
					  	</tr>
					  	<?php
					  	}
				  	$i++;
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
				<h5 ><small class="font-weight-bold text-muted"> Closed Month Claims</small> </h5> 
				
				
				<table class="estable table shadow ">
				  <thead class="thead-dark">
				    <tr>
				      <th scope="col" style="width: 10px;">S.No</th>

				      <th scope="col" style="width:200px;">Claimer</th>
				      <th scope="col" style="width: 100px;">Month</th>
				      <th scope="col" style="width: 100px;">Claims</th>
				      <th scope="col" style="width: 100px;">Status</th>
				      <th scope="col"></th>
				    </tr>
				  </thead>
				  <tbody>
				  	<?php
				  	$m=mysql_query("SELECT `ClaimMonth`,`CrBy` FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE  `ClaimAtStep`=6 and ClaimYearId=".$_SESSION['FYearId']." group by ClaimMonth,CrBy order by ClaimMonth asc");

				  	$i=1;
					while($mlist=mysql_fetch_assoc($m)){

						$e=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `ClaimAtStep`=6 and `CrBy`='".$mlist['CrBy']."' order by ExpId asc");

						$enum=mysql_num_rows($e);
						if($enum > 0){
					  	?>
					  	<tr>
					  		<td>
					  			<?=$i?>
					  		</td>
					  		<td><?=getUser($mlist['CrBy'])?></td>
					  		<td>
					  			<a href="#" onclick="showmonthdet('<?=$mlist['ClaimMonth']?>','Closed','<?=$mlist['CrBy']?>','Closed')">
					  				<?=date('F', mktime(0,0,0,$mlist['ClaimMonth'], 1, date('Y')));?>	
				  				</a>
				  			</td>
					  		<td><?=$enum?>
					  			<input type="hidden" id="sts<?=$mlist['CrBy']?><?=$mlist['ClaimMonth']?>Closed" value="close">
					  		</td>
					  		<td><button class="btn btn-sm btn-secondary" onclick="">Closed</button></td>
					  	</tr>
					  	<tr id="<?=$mlist['CrBy']?><?=$mlist['ClaimMonth']?>Closed">
						  		
					  	</tr>
					  	<?php
						}
				  	$i++;

					}
				  	?>
				  </tbody>
				</table>
				
			</div>
		</div>
		<br>

	
		
	
</div>

<script type="text/javascript">
	function showtobeverified(month,sts,emp){
		
		var modal = document.getElementById('myModal');
		modal.style.display = "block";
		document.getElementById('claimlistfr').src='showclaimslist.php?action=finance&month='+month+'&sts='+sts+'&emp='+emp;
	}
	function showmonthdet(month,sts,emp,csts){
		
		var status=document.getElementById('sts'+emp+month+csts);
		var modal = document.getElementById(emp+month+csts);
		if(status.value=='close'){
			$.post("claimlistajax.php",{act:"monthdettofinance",month:month,sts:sts,csts:csts,emp:emp},function(data){
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
	// 	document.getElementById('claimlistfr').src='showclaimslist.php?action=finance&month='+month+'&sts='+sts+'&emp='+emp;
	// }
	function closeClaimMonth(month,crby,username,monthname){
		// alert('dfdffdf');
		// alert(month+','+crby+','+username+','+monthname);
		if (confirm('Are you sure to Final Close '+username+'\'s '+monthname+' Claim\'s?')){

			$.post("homeajax.php",{act:"closeClaimMonth",month:month,crby:crby},function(data){
				console.log(data);
				if(data.includes('closed')){
					alert( username+'\'s '+monthname+' month Closed Successfully');
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
	function financeClaim(expid){

		var f=parseInt(document.getElementById(expid+'financetamt').value);
		var r=document.getElementById(expid+'financetremark').value;

		$.post("claimajax.php",{act:"financeClaim",expid:expid,ftamt:f,financetremark:r},function(data){
			
			if(data.includes('financed')){
				document.getElementById(expid+'Status').innerHTML='Financed'; 
				document.getElementById(expid+'btn').innerHTML='View'; 
				alert('Financed Successfully');
				document.getElementById('financection').innerHTML=''; 

				
			}
			
		});
	}
	function showbtn(chk,expid){

		if (chk.checked) {
           $('#'+expid+'finbtn').prop('disabled', false);
        }else{
        	$('#'+expid+'finbtn').prop('disabled', true);
        }
		
	}
	function checkrange(thisamt,mainamt){
    
	    var t=parseInt(thisamt.value);
	    var m=parseInt(mainamt);
	    if(t>m){
	        $(thisamt).val(m);
	        alert("You can't provide more amount than claimed amount");
	    }
	    
	}
</script>