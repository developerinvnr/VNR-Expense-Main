<?php

// function getClaimType($cid){
// 	include 'config.php';
// 	$c=mysql_query("SELECT ClaimName FROM `claimtype` where ClaimId=".$cid);
// 	$ct=mysql_fetch_assoc($c);
// 	return $ct['ClaimName'];
// }
function getUser($u){
include 'config.php';
$u=mysql_query("SELECT Fname,Sname,Lname,EmpCode FROM `hrm_employee` where EmployeeID=".$u,$con2);
$un=mysql_fetch_assoc($u);
return $un['Fname'].' '.$un['Sname'].' '.$un['Lname'].' - '.$un['EmpCode'];
}

if($_REQUEST['v']=='')
{
 if(date("m")==1){$_REQUEST['v']=12;}else{$_REQUEST['v']=date("m")-1;}
}
?>


<?php

if($_SESSION['EmpRole']=='V')
{
  $eMM=mysql_query("select ExpId from y".$_SESSION['FYearId']."_expenseclaims where ClaimId=7 AND ClaimStatus='Submitted' and VerifyBy=0"); $roweMM=mysql_num_rows($eMM); 
  if($roweMM>0)
  {
   
    while($reM=mysql_fetch_assoc($eMM))
    {
	 $sAmt=mysql_query("select SUM(FilledTAmt) as TotalAmt from 2_4_wheeler_entry where Totalkm>0 AND ExpId=".$reM['ExpId']);
	 $rAmt=mysql_fetch_assoc($sAmt); 
	 if($rAmt['TotalAmt']>0)
	 {
	  $qry=mysql_query("update y".$_SESSION['FYearId']."_expenseclaims set FilledTAmt='".$rAmt['TotalAmt']."', VerifyTAmt='".$rAmt['TotalAmt']."', ApprTAmt='".$rAmt['TotalAmt']."', FinancedTAmt='".$rAmt['TotalAmt']."' where ClaimId=7 AND ExpId=".$reM['ExpId']." AND ClaimStatus='Submitted' and VerifyBy=0");
     }
	}
  
  } 
}

?>

<script type="text/javascript">
function isNumberKey(evt)
{
 var charCode = (evt.which) ? evt.which : event.keyCode
 //if (charCode > 31 && (charCode < 48 || charCode > 57))
 if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))  /* For floating*/
	return false;

 return true;
}

function FunChk(v)
{
 $('#chkval').val(v);
}

function FselMonth()
{ 
 var chkval=$('#chkval').val(); var SelMonth =$('#SelMonth').val();
 if(SelMonth==''){alert("please select month"); return false; }
 window.location="home.php?action=displayrec&v="+SelMonth+"&chkval="+chkval;
}

function FunPrint(e,m,y,n)
{
 window.open("printdetails.php?e="+e+"&m="+m+"&y="+y+"&n="+n,"PForm","menubar=no,scrollbars=yes,resizable=no,directories=no,width=1200,height=450"); 
}
</script>

<div class="col-md-9 h-100" style="border-left:5px solid #d9d9d9;">
    <br>
		<font style="font-size:14px;">Pending : <input type="radio" id="c2" name="chkp" onclick="FunChk(2)" <?php if($_REQUEST['chkval']==2){echo 'checked';} ?>/></font>
		&nbsp;
		<font style="font-size:14px;">Approved : <input type="radio" id="c1" name="chkp" onclick="FunChk(1)" <?php if($_REQUEST['chkval']==1){echo 'checked';} ?>/></font>
		<input type="hidden" id="chkval" value="<?php if($_REQUEST['chkval']!=''){echo $_REQUEST['chkval'];}else{echo 2;} ?>" />
		&nbsp;&nbsp;
		<font style="font-size:14px;">Month :</font>&nbsp;
		       <select style="font-size:14px;" id="SelMonth">
			      <option value="0" <?php if($_REQUEST['v']=='' || $_REQUEST['v']==0){echo 'selected';}?>>All</option>
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
&nbsp;
<a class="btn btn-sm btn-primary" onclick="FselMonth()"><i class="fa fa-btn" aria-hidden="true"></i><span style="color:#FFFFFF; width:80px;">&nbsp;&nbsp;&nbsp;Click&nbsp;&nbsp;&nbsp;</span></a>

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
		<a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
		<div class="row">
<?php if($_REQUEST['action']=='displayrec' && $_REQUEST['chkval']==2){ ?>		
			<div class="col-lg-11 shadow">
				<br>
				<h5 ><small class="font-weight-bold text-muted"> Claims To Be Verified</small> </h5> 
				
				
				<table class="estable table shadow ">
				  <thead class="thead-dark">
				    <tr>
				      <th scope="col" style="width:130px;">Claimer</th>
				      <th scope="col" style="width:100px;">Month</th>
				      <th scope="col">Claims</th>
				      <th scope="col">Action</th>
				      <th scope="col" style="width:100px;">Courier Detail</th> 
				    </tr>
				  </thead>
				  					  
				  <tbody>
				  	<?php if($_REQUEST['v']=='' || $_REQUEST['v']==0){ $cond='1=1'; }else{ $cond='ClaimMonth='.$_REQUEST['v']; }
				  	$m=mysql_query("SELECT `ClaimMonth`,`CrBy` FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE  `ClaimAtStep`=3 AND FilledOkay=1 and ClaimYearId=".$_SESSION['FYearId']." and ".$cond." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' group by ClaimMonth,CrBy order by ClaimMonth asc");

                    $sn=1;
					while($mlist=mysql_fetch_assoc($m)){
					
  $chkM=mysql_query("select Status,Month,PostDate,DocateNo,Agency,RecevingDate,VerifDate,DocRmk from y".$_SESSION['FYearId']."_monthexpensefinal where EmployeeID='".$mlist['CrBy']."' and Month='".$mlist['ClaimMonth']."' and YearId='".$_SESSION['FYearId']."'"); $chkM=mysql_fetch_assoc($chkM);
  if($chkM['Status']=='Closed')
  {
					
						
						$e=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE  `CrBy`='".$mlist['CrBy']."' and `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `ClaimAtStep`=3 and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' order by ExpId asc");
						$enum=mysql_num_rows($e);

						if($enum > 0){
					  	?>
					  	<tr>
					  		<td><?php
					  			// include 'config.php';
								$u=mysql_query("SELECT Fname,Sname,Lname,EmpCode FROM `hrm_employee` where EmployeeID=".$mlist['CrBy'],$con2);
								$un=mysql_fetch_assoc($u);

								echo $un['Fname'].' '.$un['Sname'].' '.$un['Lname'].' - '.$un['EmpCode'];


					  		// getUser($mlist['CrBy'])
					  		?></td>
					  		<td>
					  			<span onclick="showmonthdet('<?=$mlist['ClaimMonth']?>','Open','<?=$mlist['CrBy']?>','Filled')" style="cursor:pointer; color:#0033CC;">
					  				<u></u><?=date('F', mktime(0,0,0,$mlist['ClaimMonth'], 1, date('Y')));?></u>
				  				</span>
				  			</td>
					  		<td>
					  			
					  			<?php
					  			$tv=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$mlist['CrBy']."' and `ClaimAtStep`=3 and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'");
					  			$sv=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$mlist['CrBy']."' and `ClaimAtStep`=3 and `ClaimStatus`='Filled' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'");
					  			$fv=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `CrBy`='".$mlist['CrBy']."' and `ClaimAtStep`=3 and `ClaimStatus`='Verified' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'");
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
					  		
					  		<?php
							$qrys=mysql_query("select Status from y".$_SESSION['FYearId']."_monthexpensefinal where EmployeeID='".$mlist['CrBy']."' and Month='".$mlist['ClaimMonth']."' and YearId='".$_SESSION['FYearId']."'");
							$qryr=mysql_fetch_assoc($qrys);
							?>
					  		<td><button type="button" class="btn btn-sm btn-primary" onclick="submittoapprover('<?=$mlist['ClaimMonth']?>','<?=$mlist['CrBy']?>')" <?php if($fv==0 OR $qryr['Status']=='Open'){echo 'disabled';}?>>Submit To Approver</button>
					  		
					  		&nbsp;&nbsp;
							    <button type="button" class="btn btn-sm btn-warning" onclick="submittoreturn('<?=$mlist['ClaimMonth']?>','<?=$mlist['CrBy']?>')" <?php if($qryr['Status']=='Open'){echo 'disabled';}?>>Return</button>
								
							&nbsp;&nbsp;
							    <button type="button" class="btn btn-sm btn-success" onclick="submittodetails('<?=$mlist['ClaimMonth']?>','<?=$mlist['CrBy']?>')">E-Home</button>
							    
							&nbsp;&nbsp;
							<?php
		                      $name=getUser($mlist['CrBy']);
		                      $month=date('F', mktime(0,0,0,$mlist['ClaimMonth'], 1, date('Y')));
	                        ?>	
							<button type="button" class="btn btn-sm btn-Danger" onclick="closeClaimMonth('<?=$mlist['ClaimMonth']?>','<?=$mlist['CrBy']?>','<?=$name?>','<?=$month?>')" <?php //if($ff==0){echo 'disabled';}?>>Approval All Claim</button>		    
					  		
					  		</td>
					  		
					  		<td style="vertical-align:middle;cursor:pointer;text-decoration:underline;">
	                          <span onclick="FUnOPen(<?=$sn?>)">
							   <?php if($chkM['PostDate']!='0000-00-00' && $chkM['RecevingDate']=='0000-00-00'){ ?>Click
							   <?php }elseif($chkM['PostDate']!='0000-00-00' && $chkM['RecevingDate']!='0000-00-00'){ ?>
							   Recieved<?php } ?>
							  </span>
	                        </td>
					  		
					  	</tr>
					  	<tr id="<?=$mlist['CrBy']?><?=$mlist['ClaimMonth']?>Filled">
					  	</tr>
					  	
					  	
<?php /*****************************************/ ?>
  <tr>
    <td colspan="6" style="width:100%;text-align:right;">
	 <div id="Div<?=$sn?>" style="display:none;">
	  <table style="width:100%; vertical-align:top;" cellspacing="0">
	   <tr>
	     <td style="width:350px;text-align:right;">
		 <font style="float:left;">&nbsp;<b>Post Date:</b>&nbsp;<?php echo date("d-m-Y",strtotime($chkM['PostDate'])); ?></font>
		 <font style="float:left;">&nbsp;&nbsp;<b>DocateNo:</b>&nbsp;<?=$chkM['DocateNo']?></font>
		 
		 <b>Recieving Date</b>
		 </td>
	     <td style="width:200px;"><div class="input-group date form_date col-md-12" data-date="" data-date-format="dd-mm-yyyy" data-link-field="RecevingDate<?=$sn?>" data-link-format="dd-mm-yyyy" style="padding:0px;"><input id="RecevingDate<?=$sn?>" style="font-family:Georgia;font-size:12px;width:100%;text-align:center;text-align:left;" value="<?php if($chkM['RecevingDate']!='0000-00-00'){echo date("d-m-Y",strtotime($chkM['RecevingDate'])); }else{echo date("d-m-Y"); }?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div></td>
		 
	   </tr>
	   <tr>
	     <td style="text-align:right;">
		 <font style="float:left;">&nbsp;<b>Agency:</b>&nbsp;<?php echo $chkM['Agency']; ?></font>
		 
		 <b>Verify Date</b>
		 </td>
	     <td><div class="input-group date form_date col-md-12" data-date="" data-date-format="dd-mm-yyyy" data-link-field="VerifDate<?=$sn?>" data-link-format="dd-mm-yyyy" style="padding:0px;"><input id="VerifDate<?=$sn?>" style="font-family:Georgia;font-size:12px;width:100%; text-align:left;" value="<?php if($chkM['VerifDate']!='0000-00-00'){echo date("d-m-Y",strtotime($chkM['VerifDate'])); }else{echo date("d-m-Y"); }?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div></td>
	   </tr>
	   <tr>  
		 <td style="text-align:right;"><b>Any Remark</b></td>
	     <td><input id="DocRmk<?=$sn?>" style="font-family:Georgia;font-size:12px;width:100%;" value="<?=$chkM['DocRmk']?>"></td>
	   </tr>
	   <tr style="background-color:#FFFFFF;height:24px;">
	    <td colspan="2" style="text-align:right;">
		<?php if($chkM['RecevingDate']=='0000-00-00' OR $chkM['RecevingDate']=='1970-01-01' OR $chkM['RecevingDate']==''){ ?>
		<button class="btn-primary btn-sm" style="font-size:10px;" onclick="FunSave(<?=$sn.','.$mlist['CrBy'].','.$chkM['Month'].','.$_SESSION['FYearId']?>)">save</button><?php } ?>
		<button class="btn-primary btn-sm" style="font-size:10px;" onclick="FunClose(<?=$sn?>)">close</button>
		</td>
	   </tr>	
	  </table>
	 </div>
	</td>
   <tr>
<?php /*****************************************/ ?>					  	
					  	
					  	<?php
					  	}
						
	} //if($chkM=='Closed')
				  	
					$sn++; }
				  	?>
				  </tbody>
				</table>
				
			</div>
<?php } // if($_REQUEST['action']=='displayrec')?>	
		</div>

		<br>
		<div class="row">
<?php if($_REQUEST['action']=='displayrec' && $_REQUEST['chkval']==1){ ?>			
			<div class="col-lg-11 shadow">
				<br>
				<h5 ><small class="font-weight-bold text-muted"> Claim Submitted (Approver)</small> </h5> 
				
				
				<table class="estable table shadow ">
				  <thead class="thead-dark">
				    <tr>
				      <th scope="col" style="width:150px;">Claimer</th>
				      <th scope="col" style="width:100px;">Month</th>
				      <th scope="col">Claims</th>
				      <th scope="col" style="width:100px;">Courier Detail</th>
				      
				    </tr>
				  </thead>
				  <tbody>
				  	<?php
				  	$m=mysql_query("SELECT `ClaimMonth`,`CrBy` FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `VerifyBy`='".$_SESSION['EmployeeID']."' and `ClaimAtStep`>=4 and ClaimYearId=".$_SESSION['FYearId']." and ClaimMonth=".$_REQUEST['v']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' group by ClaimMonth,CrBy order by ClaimMonth asc");
                    $sn=1;
					while($mlist=mysql_fetch_assoc($m)){
					
  $chkM=mysql_query("select Status,Month,PostDate,DocateNo,Agency,RecevingDate,VerifDate,DocRmk from y".$_SESSION['FYearId']."_monthexpensefinal where EmployeeID='".$mlist['CrBy']."' and Month='".$mlist['ClaimMonth']."' and YearId='".$_SESSION['FYearId']."'"); $chkM=mysql_fetch_assoc($chkM);
  if($chkM['Status']=='Closed')
  {					

						$e=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE `ClaimMonth`='".$mlist['ClaimMonth']."' and `ClaimYearId`='".$_SESSION['FYearId']."' and `ClaimAtStep`>=4 and `CrBy`='".$mlist['CrBy']."' and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft' order by ExpId asc");

						$enum=mysql_num_rows($e);
						if($enum > 0){
					  	?>
					  	<tr>
					  		<td><?php
                            
                            	$u=mysql_query("SELECT Fname,Sname,Lname,EmpCode FROM `hrm_employee` where EmployeeID=".$mlist['CrBy'],$con2);
								$un=mysql_fetch_assoc($u);

								echo $un['Fname'].' '.$un['Sname'].' '.$un['Lname'].' - '.$un['EmpCode'];


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
				  				
				  				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							    <button type="button" class="btn btn-sm btn-success" onclick="submittodetails('<?=$mlist['ClaimMonth']?>','<?=$mlist['CrBy']?>')">E-Home</button>
				  				
				  			</td>
				  			<td style="vertical-align:middle;cursor:pointer;text-decoration:underline;">
	                          <span onclick="FUnOPen(<?=$sn?>)">
							   <?php if($chkM['PostDate']!='0000-00-00' && $chkM['RecevingDate']=='0000-00-00'){ ?>Click
							   <?php }elseif($chkM['PostDate']!='0000-00-00' && $chkM['RecevingDate']!='0000-00-00'){ ?>
							   Recieved<?php } ?>
							  </span>
	                        </td>
					  		
					  	</tr>
					  	<tr id="<?=$mlist['CrBy']?><?=$mlist['ClaimMonth']?>Verified">
					  	</tr>
					  	
<?php /*****************************************/ ?>
  <tr>
    <td colspan="6" style="width:100%;text-align:right;">
	 <div id="Div<?=$sn?>" style="display:none;">
	  <table style="width:100%; vertical-align:top;" cellspacing="0">
	   <tr>
	     <td style="width:350px;text-align:right;">
		 <font style="float:left;">&nbsp;<b>Post Date:</b>&nbsp;<?php echo date("d-m-Y",strtotime($chkM['PostDate'])); ?></font>
		 <font style="float:left;">&nbsp;&nbsp;<b>DocateNo:</b>&nbsp;<?=$chkM['DocateNo']?></font>
		 
		 <b>Recieving Date</b>
		 </td>
	     <td style="width:200px;"><div class="input-group date form_date col-md-12" data-date="" data-date-format="dd-mm-yyyy" data-link-field="RecevingDate<?=$sn?>" data-link-format="dd-mm-yyyy" style="padding:0px;"><input id="RecevingDate<?=$sn?>" style="font-family:Georgia;font-size:12px;width:100%;text-align:center;text-align:left;" value="<?php if($chkM['RecevingDate']!='0000-00-00'){echo date("d-m-Y",strtotime($chkM['RecevingDate'])); }else{echo date("d-m-Y"); }?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div></td>
		 
	   </tr>
	   <tr>
	     <td style="text-align:right;">
		 <font style="float:left;">&nbsp;<b>Agency:</b>&nbsp;<?php echo $chkM['Agency']; ?></font>
		 
		 <b>Verify Date</b>
		 </td>
	     <td><div class="input-group date form_date col-md-12" data-date="" data-date-format="dd-mm-yyyy" data-link-field="VerifDate<?=$sn?>" data-link-format="dd-mm-yyyy" style="padding:0px;"><input id="VerifDate<?=$sn?>" style="font-family:Georgia;font-size:12px;width:100%; text-align:left;" value="<?php if($chkM['VerifDate']!='0000-00-00'){echo date("d-m-Y",strtotime($chkM['VerifDate'])); }else{echo date("d-m-Y"); }?>"><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div></td>
	   </tr>
	   <tr>  
		 <td style="text-align:right;"><b>Any Remark</b></td>
	     <td><input id="DocRmk<?=$sn?>" style="font-family:Georgia;font-size:12px;width:100%;" value="<?=$chkM['DocRmk']?>"></td>
	   </tr>
	   <tr style="background-color:#FFFFFF;height:24px;">
	    <td colspan="2" style="text-align:right;">
		<?php if($chkM['RecevingDate']=='0000-00-00' OR $chkM['RecevingDate']=='1970-01-01' OR $chkM['RecevingDate']==''){ ?>
		<button class="btn-primary btn-sm" style="font-size:10px;" onclick="FunSave(<?=$sn.','.$mlist['CrBy'].','.$chkM['Month'].','.$_SESSION['FYearId']?>)">save</button><?php } ?>
		<button class="btn-primary btn-sm" style="font-size:10px;" onclick="FunClose(<?=$sn?>)">close</button>
		</td>
	   </tr>	
	  </table>
	 </div>
	</td>
   <tr>
<?php /*****************************************/ ?>					  	
					  	
					  	
					  	<?php
						}
						
	} //if($chkM['Status']=='Closed')
						
					$sn++; }
				  	?>
				  </tbody>					  
				  
				</table>
				
			</div>
<?php } //if($_REQUEST['action']=='displayrec') ?>			
		</div>
		<br>

	
		
	
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
     var rd=$('#RecevingDate'+sn).val(); 
	 var vd=$('#VerifDate'+sn).val();
	 var rmk=$('#DocRmk'+sn).val();
	 $.post("courierajax.php",{act:"VerifyCourierDetails",sn:sn,eid:eid,m:m,yid:yid,rd:rd,vd:vd,rmk:rmk},function(data){
	 if(data.includes('done')){ alert('Updated Successfully'); }else{ alert("Error"); }	});
   }
}


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
	
	
	function submittoreturn(month,crby){
		if (confirm('Are you sure to return this employee month claim?')){
			$.post("homeajax.php",{act:"submittoreturn",month:month,crby:crby},function(data){
				if(data.includes('Returned')){
					alert('Returned to Approver Successfully');
					location.reload();
				}
			});
		}
	}
	
	function submittodetails(month,crby){
		window.open("EvhomeDetails.php?view=verifier&mnt="+month+"&ei="+crby,"Home","menubar=no,scrollbars=yes,resizable=no,directories=no,width=800,height=500"); 
	}
	
	
	function closeClaimMonth(month,crby,username,monthname){
		if (confirm('Are you sure to Final Close '+username+'\'s '+monthname+' Claim\'s?')){
         
			$.post("homeajax.php",{act:"FromVerifierCloseClaimMonth",month:month,crby:crby},function(data){
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