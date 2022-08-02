<?php
include "header.php"; 

function getClaimType($cid){
	$c=mysql_query("SELECT ClaimName FROM `claimtype` where ClaimId=".$cid);
	$ct=mysql_fetch_assoc($c);
	return strtoupper($ct['ClaimName']);
}
function getUser($u){ 
	$u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$u,$con2);
	$un=mysql_fetch_assoc($u);
	return $un['Fname'].' '.$un['Sname'].' '.$un['Lname'];
}

function moneyFormatIndia($num) {
    $explrestunits = "" ;
    if(strlen($num)>3) {
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i<sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if($i==0) {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash; // writes the final format where $currency is the currency symbol.
}
?>
<style type="text/css">
.th{ text-align:center; font-weight:bold; font-size:12px; vertical-align:middle; }
.tdc{ text-align:center;font-size:12px;vertical-align:middle; }
.tdr{ text-align:right;font-size:12px;vertical-align:middle; }
.tdl{ text-align:left;font-size:12px;vertical-align:middle; }
</style>
<script type="text/javascript">
function FunU(v,n)
{
 if(v!='ALL')
 {
  if(n==1){ document.getElementById("usern").value=document.getElementById("userc").value; }
  else if(n==2){ document.getElementById("userc").value=document.getElementById("usern").value; }
 }
 else
 {
  document.getElementById("userc").value='ALL'; document.getElementById("usern").value='ALL';
 }
}

function ResultFit()
{ 
 var uc=document.getElementById("userc").value;  
 var un=document.getElementById("usern").value;  
 var ct=document.getElementById("claimType").value; 
 var cs=document.getElementById("claimStatus").value;  
 var fy=document.getElementById("finy").value; 
 window.location="claimamount.php?act=resultfit&uc="+uc+"&un="+un+"&ct="+ct+"&cs="+cs+"&fy="+fy;
}

function ResultExp(uc,un,ct,cs,fy)
{
 var win = window.open("claimamountexp.php?act=resultexp&uc="+uc+"&un="+un+"&ct="+ct+"&cs="+cs+"&fy="+fy,"ExpForm","menubar=no,scrollbars=yes,resizable=no,directories=no,width=50,height=50"); 
}

function FunODiv(i)
{ 
 if(document.getElementById("DIV"+i).style.display=='none'){ document.getElementById("DIV"+i).style.display='block'; }
 else{ document.getElementById("DIV"+i).style.display='none'; } 
}
</script>


<div class="container-fluid ">
 <div class="row  d-flex justify-content-around">
  <div class="col-md-11">
	<div class="row filrow font-weight-bold">
	  <div class="col-md-2">Code:<select class="form-control" id="userc" onChange="FunU(this.value,1)"><?php $u=mysql_query("select EmployeeID,EmpCode,Fname,Sname,Lname from hrm_employee where EmpStatus!='De' AND CompanyId=".$_SESSION['CompanyId']." AND Fname!='' AND EmpCode!='' AND EmpCode!=0 order by EmpCode asc", $con2); ?>
							<?php if(mysql_num_rows($u)>1){?><option value="ALL" <?php if(isset($_REQUEST['uc']) && $_REQUEST['uc']=='ALL'){echo 'selected';} ?>>ALL</option><?php }
				      		while($us=mysql_fetch_assoc($u)){ ?>
				      		<option value="<?=$us['EmployeeID']?>" <?php if(isset($_REQUEST['uc']) && $_REQUEST['uc']==$us['EmployeeID']){echo 'selected';} ?>><?=$us['EmpCode'].' - '.$us['Fname'].' '.$us['Sname'].' '.$us['Lname']?></option>
				      		<?php }	?></select>
	  </div>
	  
	  <div class="col-md-2">Name:<select class="form-control" id="usern" onChange="FunU(this.value,2)"><?php $u=mysql_query("select EmployeeID,EmpCode,Fname,Sname,Lname from hrm_employee where EmpStatus!='De' AND CompanyId=".$_SESSION['CompanyId']." AND Fname!='' AND EmpCode!='' AND EmpCode!=0 order by EmpCode asc", $con2); ?>
							<?php if(mysql_num_rows($u)>1){?><option value="ALL" <?php if(isset($_REQUEST['un']) && $_REQUEST['un']=='ALL'){echo 'selected';} ?>>ALL</option><?php }
				      		while($us=mysql_fetch_assoc($u)){ ?>
				      		<option value="<?=$us['EmployeeID']?>" <?php if(isset($_REQUEST['un']) && $_REQUEST['un']==$us['EmployeeID']){echo 'selected';} ?>><?=$us['Fname'].' '.$us['Sname'].' '.$us['Lname'].' - '.$us['EmpCode']?></option>
				      		<?php }	?></select>
      </div>
			      	
	  <div class="col-md-2">ClaimType:<select class="form-control" id="claimType"><option value="ALL" <?php if(isset($_REQUEST['ct']) && $_REQUEST['ct']=='ALL'){echo 'selected';} ?>>ALL</option><?php $c=mysql_query("select * from claimtype where (ClaimStatus='A' OR ClaimStatus='B') order by ClaimName asc");
				  while($cid=mysql_fetch_assoc($c)){?><option value="<?=$cid['ClaimId']?>" <?php if(isset($_REQUEST['ct']) && $_REQUEST['ct']==$cid['ClaimId']){echo 'selected';} ?>><?=$cid['ClaimName']?></option><?php }	?></select>		      		
	  </div>
	  
	  <div class="col-md-2">Claim Status:<select class="form-control" id="claimStatus"><option value="ALL" <?php if(isset($_REQUEST['cs']) && $_REQUEST['cs']=='ALL'){echo 'selected';} ?>>ALL</option><option value="Filled" <?php if(isset($_REQUEST['cs']) && $_REQUEST['cs']=='Filled'){echo 'selected';} ?>>Filled</option>
<option value="Verified" <?php if(isset($_REQUEST['cs']) && $_REQUEST['cs']=='Verified'){echo 'selected';} ?>>Verified</option><option value="Approved" <?php if(isset($_REQUEST['cs']) && $_REQUEST['cs']=='Approved'){echo 'selected';} ?>>Approved</option><option value="Financed" <?php if(isset($_REQUEST['cs']) && $_REQUEST['cs']=='Financed'){echo 'selected';} ?>>Financed</option><option value="Paid" <?php if(isset($_REQUEST['cs']) && $_REQUEST['cs']=='Paid'){echo 'selected';} ?>>Paid</option></select>
	  </div>

	  <div class="col-md-2">Financial Year:<select class="form-control" id="finy" name="finy">
	  <?php for($k=$_SESSION['FYearId']; $k>=2; $k--){
	  $sqy=mysql_query("select y1,y2 from financialyear where YearId=".$k); $rqy=mysql_fetch_assoc($sqy); ?>
	  <option value="<?=$k?>" <?php if(isset($_REQUEST['fy']) && $_REQUEST['fy']==2){echo 'selected';} ?>><?=$rqy['y1'].' - '.$rqy['y2']?></option>
	  <?php } ?>
	  </select></div>
			      	
	  <div class="col-md-1"><br>
		<button class="form-control btn-primary" onclick="ResultFit()" style="cursor:pointer;">Search</button>
	  </div>
	  
	  <?php if($_REQUEST['act']=='resultfit'){ ?>
	  <div class="col-md-1"><br>
		<button class="form-control btn-primary" onclick="ResultExp('<?=$_REQUEST['uc']?>','<?=$_REQUEST['un']?>','<?=$_REQUEST['ct']?>','<?=$_REQUEST['cs']?>',<?=$_REQUEST['fy']?>)" style="cursor:pointer;">Export</button>
	  </div>
	  <?php } ?>
		      	
	 </div>
	      	
     <div class="row filrow font-weight-bold">
      <div class="table-responsive d-flex justify-content-center align-items-center">
      <?php if($_REQUEST['act']=='resultfit'){ ?>
      <table class="table shadow table-responsive" style="width:80%;" cellpadding="0" cellspacing="0">
       <thead class="thead-dark">
	    <tr>
	     <th class="th" colspan="<?php if($_REQUEST['cs']=='ALL'){echo '6';}else{echo '2';}?>" style="text-align:left;font-size:15px; width:500px;">Claim Type: <font style="color:#FFFF80;"><?php if($_REQUEST['ct']!='ALL'){ echo getClaimType($_REQUEST['ct']); }else{ echo 'ALL'; }?></font>&nbsp;,&nbsp;User : <font style="color:#FFFF80;"><?php if($_REQUEST['uc']!='ALL' && $_REQUEST['un']!='ALL'){ if($_REQUEST['uc']>0){ $u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$_REQUEST['uc'],$con2); $un=mysql_fetch_assoc($u); echo $un['Fname'].' '.$un['Sname'].' '.$un['Lname']; }else{ $u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$_REQUEST['un'],$con2); $un=mysql_fetch_assoc($u); echo $un['Fname'].' '.$un['Sname'].' '.$un['Lname']; } }else{ echo 'ALL'; }?></font></th>
	    </tr>
	    <tr>
	     <th class="th" rowspan="2" style="width:100px;">Month</th>
		 <?php if($_REQUEST['cs']!='Paid'){ ?>
		 <th class="th" colspan="<?php if($_REQUEST['cs']=='ALL'){echo '4';}else{echo '0';}?>" scope="col">Claim Amount</th>
		 <?php } ?>
	     <?php if($_REQUEST['ct']=='ALL' AND ($_REQUEST['cs']=='Paid' OR $_REQUEST['cs']=='ALL')){?><th class="th" rowspan="2" style="width:100px;">Paid</th><?php } ?>
	    </tr>
	    <tr>
<?php if($_REQUEST['cs']=='Filled' OR $_REQUEST['cs']=='ALL'){?><th class="th" style="width:100px;">Filled</th><?php } ?>
<?php if($_REQUEST['cs']=='Verified' OR $_REQUEST['cs']=='ALL'){?><th class="th" style="width:100px;">Verified</th><?php } ?>
<?php if($_REQUEST['cs']=='Approved' OR $_REQUEST['cs']=='ALL'){?><th class="th" style="width:100px;">Approved</th><?php } ?>
<?php if($_REQUEST['cs']=='Financed' OR $_REQUEST['cs']=='ALL'){?><th class="th" style="width:100px;">Financed</th><?php } ?>
    
	    </tr>
       </thead>
	    <?php if($_REQUEST['uc']=='ALL'){ $qryu='1=1'; $qry2u='1=1'; }else{ $qryu='CrBy='.$_REQUEST['uc']; $qry2u='EmployeeID='.$_REQUEST['uc']; }
		      if($_REQUEST['ct']=='ALL'){ $qryct='1=1'; }else{ $qryct='ClaimId='.$_REQUEST['ct']; } ?>
       <tbody>
	    <?php if($_REQUEST['fy']==2){ $cnt=9; }else{ $cnt=4; } ?>
	    <?php for($i=15; $i>=$cnt; $i--){ if($i==15){$j=03;}elseif($i==14){$j=02;}elseif($i==13){$j=01;}else{$j=$i;} ?>
		<?php $FTot=0; $VTot=0; $ATot=0; $FiTot=0; $PTot=0; ?>
		<tr style="background-color:#FFFFFF;">
		 <td class="tdl" style="color:#007100;">&nbsp;<b><span style="cursor:pointer;" <?php if($_REQUEST['ct']=='ALL' AND ($_REQUEST['cs']=='ALL' OR $_REQUEST['cs']=='Filled' OR $_REQUEST['cs']=='Verified' OR $_REQUEST['cs']=='Approved' OR $_REQUEST['cs']=='Financed')){ ?>onclick="FunODiv(<?=$j?>)" <?php } ?>><?=strtoupper(date("F",strtotime(date("Y-".$j."-01"))))?></span></b></td>
		 
<?php if($_REQUEST['cs']=='Filled' OR $_REQUEST['cs']=='ALL'){ $stotF=mysql_query("SELECT SUM(FilledTAmt) as FTot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE `ClaimMonth`='".$j."' AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ".$qryct." AND ClaimStatus!='Deactivate' AND FilledBy>0"); $rtotF=mysql_fetch_assoc($stotF); $FTot=$rtotF['FTot']; ?>
         <td class="tdr"><?=moneyFormatIndia($FTot)?>&nbsp;</td><?php } ?>
		 
<?php if($_REQUEST['cs']=='Verified' OR $_REQUEST['cs']=='ALL'){ $stotV=mysql_query("SELECT SUM(VerifyTAmt) as VTot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE `ClaimMonth`='".$j."' AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ".$qryct." AND ClaimStatus!='Deactivate' AND VerifyBy>0"); $rtotV=mysql_fetch_assoc($stotV); $VTot=$rtotV['VTot']; ?>
         <td class="tdr"><?=moneyFormatIndia($VTot)?>&nbsp;</td><?php } ?>
		 
<?php if($_REQUEST['cs']=='Approved' OR $_REQUEST['cs']=='ALL'){ $stotA=mysql_query("SELECT SUM(ApprTAmt) as ATot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE `ClaimMonth`='".$j."' AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ".$qryct." AND ClaimStatus!='Deactivate' AND ApprBy>0"); $rtotA=mysql_fetch_assoc($stotA); $ATot=$rtotA['ATot']; ?>
         <td class="tdr"><?=moneyFormatIndia($ATot)?>&nbsp;</td><?php } ?>
		 
<?php if($_REQUEST['cs']=='Financed' OR $_REQUEST['cs']=='ALL'){ $stotFi=mysql_query("SELECT SUM(FinancedTAmt) as FiTot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE `ClaimMonth`='".$j."' AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ".$qryct." AND ClaimStatus!='Deactivate' AND FinancedBy>0"); $rtotFi=mysql_fetch_assoc($stotFi); $FiTot=$rtotFi['FiTot']; ?>
         <td class="tdr"><?=moneyFormatIndia($FiTot)?>&nbsp;</td><?php } ?>
		 
<?php if($_REQUEST['ct']=='ALL' AND ($_REQUEST['cs']=='Paid' OR $_REQUEST['cs']=='ALL')){ $stotP=mysql_query("SELECT sum(Fin_AdvancePay) as sTotA,sum(Fin_PayAmt) as sTotB FROM `y".$_REQUEST['fy']."_monthexpensefinal` WHERE YearId=".$_REQUEST['fy']." AND Month='".$j."' AND ".$qry2u." AND Fin_AppBy>0 AND Fin_AppDate!='0000-00-00' AND Fin_AppDate!='' AND Fin_AppDate!='1970-01-01' AND Fin_PayAmt!='' AND Fin_PayOption!='' AND Fin_PayBy>0"); $rtotP=mysql_fetch_assoc($stotP); $PTot=$rtotP['sTotA']+$rtotP['sTotB'];?>
         <td class="tdr"><?=moneyFormatIndia($PTot)?>&nbsp;</td><?php } ?> 
		 
		</tr>
		
		<?php /****************************************/ ?>
		<tr>
		 <td></td>
		 <td colspan="<?php if($_REQUEST['cs']=='ALL'){echo '5';}else{echo '0';} ?>">
		  <div id="DIV<?=$j?>" style="width:100%; display:none;">
		   <table style="width:100%;">
		    <tr style="background-color:#15648C;color:#FFFFFF;">
<th class="th" style="width:150px;">Claim Type</th>
<?php if($_REQUEST['cs']=='Filled' OR $_REQUEST['cs']=='ALL'){?><th class="th" style="width:90px;">Filled</th><?php } ?>
<?php if($_REQUEST['cs']=='Verified' OR $_REQUEST['cs']=='ALL'){?><th class="th" style="width:90px;">Verified</th><?php } ?>
<?php if($_REQUEST['cs']=='Approved' OR $_REQUEST['cs']=='ALL'){?><th class="th" style="width:90px;">Approved</th><?php } ?>
<?php if($_REQUEST['cs']=='Financed' OR $_REQUEST['cs']=='ALL'){?><th class="th" style="width:90px;">Financed</th><?php } ?>
			</tr>
			<?php $c=mysql_query("select * from claimtype where (ClaimStatus='A' OR ClaimStatus='B') order by ClaimName asc");
				  while($cid=mysql_fetch_assoc($c)){ ?>
			<tr style="background-color:#DAFEF5;">
			 <td class="tdl">&nbsp;<?=$cid['ClaimName']?></td>
			 <?php if($_REQUEST['cs']=='Filled' OR $_REQUEST['cs']=='ALL'){ $stotF=mysql_query("SELECT SUM(FilledTAmt) as FTot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE `ClaimMonth`='".$j."' AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ClaimId=".$cid['ClaimId']." AND ClaimStatus!='Deactivate' AND FilledBy>0"); $rtotF=mysql_fetch_assoc($stotF); $FTot=$rtotF['FTot']; ?>
         <td class="tdr"><?=moneyFormatIndia($FTot)?>&nbsp;</td><?php } ?>
		 
<?php if($_REQUEST['cs']=='Verified' OR $_REQUEST['cs']=='ALL'){ $stotV=mysql_query("SELECT SUM(VerifyTAmt) as VTot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE `ClaimMonth`='".$j."' AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ClaimId=".$cid['ClaimId']." AND ClaimStatus!='Deactivate' AND VerifyBy>0"); $rtotV=mysql_fetch_assoc($stotV); $VTot=$rtotV['VTot']; ?>
         <td class="tdr"><?=moneyFormatIndia($VTot)?>&nbsp;</td><?php } ?>
		 
<?php if($_REQUEST['cs']=='Approved' OR $_REQUEST['cs']=='ALL'){ $stotA=mysql_query("SELECT SUM(ApprTAmt) as ATot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE `ClaimMonth`='".$j."' AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ClaimId=".$cid['ClaimId']." AND ClaimStatus!='Deactivate' AND ApprBy>0"); $rtotA=mysql_fetch_assoc($stotA); $ATot=$rtotA['ATot']; ?>
         <td class="tdr"><?=moneyFormatIndia($ATot)?>&nbsp;</td><?php } ?>
		 
<?php if($_REQUEST['cs']=='Financed' OR $_REQUEST['cs']=='ALL'){ $stotFi=mysql_query("SELECT SUM(FinancedTAmt) as FiTot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE `ClaimMonth`='".$j."' AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ClaimId=".$cid['ClaimId']." AND ClaimStatus!='Deactivate' AND FinancedBy>0"); $rtotFi=mysql_fetch_assoc($stotFi); $FiTot=$rtotFi['FiTot']; ?>
         <td class="tdr"><?=moneyFormatIndia($FiTot)?>&nbsp;</td><?php } ?>
			</tr>
			<?php } //=$cid['ClaimId']?>
		   </table>
		  </div>
		 </td>
		</tr>
		
		
		
		<?php /****************************************/ ?>
		
		<?php } //for($i=15; $i>=4; $i--) ?>
		
		<tr style="background-color:#FFFFFF;">
		 <td class="tdr"><b>Total:</b>&nbsp;</td>
         <?php if($_REQUEST['fy']==2){ $qryadd='`ClaimMonth` in (9,10,11,12,1,2,3)'; $qry2add='`Month` in (9,10,11,12,1,2,3)'; }               else{ $qryadd='`ClaimMonth`>0'; $qry2add='`Month`>0'; } ?>
		  
<?php if($_REQUEST['cs']=='Filled' OR $_REQUEST['cs']=='ALL'){ $stotF=mysql_query("SELECT SUM(FilledTAmt) as FTot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE ".$qryadd." AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ".$qryct." AND ClaimStatus!='Deactivate' AND FilledBy>0"); $rtotF=mysql_fetch_assoc($stotF); $FTot=$rtotF['FTot']; ?>
         <td class="tdr"><b><?=moneyFormatIndia($FTot)?></b>&nbsp;</th><?php } ?>
		 
<?php if($_REQUEST['cs']=='Verified' OR $_REQUEST['cs']=='ALL'){ $stotV=mysql_query("SELECT SUM(VerifyTAmt) as VTot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE ".$qryadd." AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ".$qryct." AND ClaimStatus!='Deactivate' AND VerifyBy>0"); $rtotV=mysql_fetch_assoc($stotV); $VTot=$rtotV['VTot']; ?>
         <td class="tdr"><b><?=moneyFormatIndia($VTot)?></b>&nbsp;</th><?php } ?>
		 
<?php if($_REQUEST['cs']=='Approved' OR $_REQUEST['cs']=='ALL'){ $stotA=mysql_query("SELECT SUM(ApprTAmt) as ATot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE ".$qryadd." AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ".$qryct." AND ClaimStatus!='Deactivate' AND ApprBy>0"); $rtotA=mysql_fetch_assoc($stotA); $ATot=$rtotA['ATot']; ?>
         <td class="tdr"><b><?=moneyFormatIndia($ATot)?></b>&nbsp;</th><?php } ?>
		 
<?php if($_REQUEST['cs']=='Financed' OR $_REQUEST['cs']=='ALL'){ $stotFi=mysql_query("SELECT SUM(FinancedTAmt) as FiTot FROM `y".$_REQUEST['fy']."_expenseclaims` WHERE ".$qryadd." AND FilledOkay=1 AND `ClaimYearId`='".$_REQUEST['fy']."' AND ".$qryu." AND ".$qryct." AND ClaimStatus!='Deactivate' AND FinancedBy>0"); $rtotFi=mysql_fetch_assoc($stotFi); $FiTot=$rtotFi['FiTot']; ?>
         <td class="tdr"><b><?=moneyFormatIndia($FiTot)?></b>&nbsp;</th><?php } ?>
		 
<?php if($_REQUEST['ct']=='ALL' AND ($_REQUEST['cs']=='Paid' OR $_REQUEST['cs']=='ALL')){ $stotP=mysql_query("SELECT sum(Fin_AdvancePay) as sTotA,sum(Fin_PayAmt) as sTotB FROM `y".$_REQUEST['fy']."_monthexpensefinal` WHERE ".$qry2add." AND YearId=".$_REQUEST['fy']." AND ".$qry2u." AND Fin_AppBy>0 AND Fin_AppDate!='0000-00-00' AND Fin_AppDate!='' AND Fin_AppDate!='1970-01-01' AND Fin_PayAmt!='' AND Fin_PayOption!='' AND Fin_PayBy>0"); $rtotP=mysql_fetch_assoc($stotP); $PTot=$rtotP['sTotA']+$rtotP['sTotB'];?>
         <td class="tdr"><b><?=moneyFormatIndia($PTot); ?></b>&nbsp;</th><?php } ?> 
		 
		</tr>
		
       </tbody>
  
      </table>
	  
	 
      <?php } //if($_REQUEST['act']=='resultfit') ?>
      </div>
     </div>

		</div>
	</div>
</div>
<?php
include "footer.php";
?>




