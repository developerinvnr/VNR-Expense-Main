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
 //if(SelMonth==0){alert("please select month"); return false; }
 window.location="home.php?action=displayrec&v="+SelMonth+"&chkval="+chkval;
}

function FunPrint(e,m,y,n)
{
 window.open("printdetails.php?e="+e+"&m="+m+"&y="+y+"&n="+n,"PForm","menubar=no,scrollbars=yes,resizable=no,directories=no,width=1200,height=450"); 
}

function FunAdvPay(n)
{ 
 var amt=document.getElementById("FinnAmt"+n).value; 
 var adv=document.getElementById("AdvAmt"+n).value; 
 var pay=document.getElementById("PayAmt"+n).value; 
 if(amt==''){var amt=parseFloat(0);}else{ var amt=parseFloat(document.getElementById("FinnAmt"+n).value); }
 if(adv==''){var adv=parseFloat(0);}else{ var adv=parseFloat(document.getElementById("AdvAmt"+n).value); }
 if(pay==''){ var pay=parseFloat(0);}else{ var pay=parseFloat(document.getElementById("PayAmt"+n).value); }
 document.getElementById("DiffAmt"+n).value=amt-(adv+pay);
}


</script>

<div class="col-md-11 h-100" style="border-left:5px solid #d9d9d9;">

<!--<link href="dt_bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">-->
<link href="dt_css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">

		<br>
		<font style="font-size:14px;">Unpaid : <input type="radio" id="c2" name="chkp" onclick="FunChk(2)" <?php if($_REQUEST['chkval']==2){echo 'checked';} ?>/></font>
		&nbsp;
		<font style="font-size:14px;">Paid : <input type="radio" id="c1" name="chkp" onclick="FunChk(1)" <?php if($_REQUEST['chkval']==1){echo 'checked';} ?>/></font>
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
&nbsp;&nbsp;&nbsp;
		<a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
				
		
		<div class="row">

<?php if($_REQUEST['action']=='displayrec' && $_REQUEST['chkval']==2){ ?>		
			<div class="col-lg-12 shadow">
				<br>
				<h5 ><small class="font-weight-bold text-muted">Pending for Payment</small> </h5> 
				
<table class="estable table shadow">
  <thead class="thead-dark">
	<tr>
	  <th scope="col" style="width:10px;vertical-align:middle;">S.No</th>
	  <th scope="col" style="width:300px;vertical-align:middle;">Claimer</th>
	  <th scope="col" style="width:50px;vertical-align:middle;">EmpCode</th>
	  <th scope="col" style="width:50px;vertical-align:middle;">Month</th>
	  <th scope="col" style="width:60px;vertical-align:middle;">Total<br />Claims</th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Print</th>
	  <th scope="col" style="width:80px;vertical-align:middle;">Payment<br />Amount</th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Advance<br />Amount</th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Paid<br />Amount <span style="color:#FF9428;">*</span></th>
	  <th scope="col" style="width:80px;vertical-align:middle;">Diff.<br />Amount</th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Payment<br />Option <span style="color:#FF9428;">*</span></th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Payment<br />Date <span style="color:#FF9428;">*</span></th>
	  <th scope="col" style="width:250px;vertical-align:middle;">Any<br />Remark</th>
	  
	  <th scope="col" style="width:60px;vertical-align:middle;">Action</th>
	</tr>
	<tr>
	  
	</tr>
  </thead>
				  
				  <tbody>
				  
<?php if($_REQUEST['v']=='' || $_REQUEST['v']==0){ $cond='1=1'; }else{ $cond='Month='.$_REQUEST['v']; }

$m=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE YearId=".$_SESSION['FYearId']." AND ".$cond." AND Fin_AppBy>0 AND Fin_AppDate!='0000-00-00' AND Fin_AppDate!='' AND Fin_AppDate!='1970-01-01' AND Fin_PayAmt='' AND Fin_PayOption='' AND Fin_PayBy=0 order by EmployeeID asc");
$i=1; while($mlist=mysql_fetch_assoc($m)){	

$sTot=mysql_query("SELECT count(*) as TotClaim, SUM(FinancedTAmt) as TotFin FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE  `ClaimAtStep`=6 and CrBy=".$mlist['EmployeeID']." and ClaimYearId=".$_SESSION['FYearId']." and ClaimMonth=".$mlist['Month']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'"); $rTot=mysql_fetch_assoc($sTot); ?>

<form action="" class="form-horizontal"  role="form">
   <tr>
	<td><?=$i?></td>
	<td style="text-align:left;"><?=getUser($mlist['EmployeeID'])?></td>
	<td><?=getCode($mlist['EmployeeID'])?></td>
	<td>
	 <a href="#" onclick="showmonthdet('<?=$mlist['Month']?>','Open','<?=$mlist['EmployeeID']?>','Approved')">
	  <?=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y')));?>	
	 </a>
	 <input type="hidden" id="sts<?=$mlist['EmployeeID']?><?=$mlist['Month']?>Approved" value="close">  
	</td>
	
	<td><b><?=$rTot['TotClaim'];?></b></td>
	<td>
	  <span onclick="FunPrint(<?php echo $mlist['EmployeeID'].','.$mlist['Month'].','.$_SESSION['FYearId'];?>,1)" style="color:#000099; cursor:pointer;"><u>One</u></span>
	  &nbsp;
	  <span onclick="FunPrint(<?php echo $mlist['EmployeeID'].','.$mlist['Month'].','.$_SESSION['FYearId'];?>,2)" style="color:#000099; cursor:pointer;"><u>Two</u></span>
	 </td>
	
	<td style="text-align:right;"><b><?=$rTot['TotFin'];?></b>&nbsp;
	<input type="hidden" id="FinnAmt<?=$i?>" name="FinnAmt<?=$i?>" value="<?=$rTot['TotFin'];?>" />
	</td>
	
	
	
	<td><input id="AdvAmt<?=$i?>" name="AdvAmt<?=$i?>" style="text-align:right;width:100%;background-color:#D9ECFF;" onKeyPress="return isNumberKey(event)" onkeyup="FunAdvPay(<?=$i?>)" maxlength="10"></td>
	<td><input id="PayAmt<?=$i?>" name="PayAmt<?=$i?>" style="text-align:right;width:100%;background-color:#D9ECFF;" onKeyPress="return isNumberKey(event)" onkeyup="FunAdvPay(<?=$i?>)" maxlength="10"></td>
	<td><input id="DiffAmt<?=$i?>" name="DiffAmt<?=$i?>" style="text-align:right;width:100%;background-color:#D9ECFF;" readonly></td>
	<td><select id="PayOption<?=$i?>" name="PayOption<?=$i?>" style="font-size:14px;font-family:Times New Roman;width:99%;background-color:#D9ECFF;">
		 <option value="Bank">Bank</option><option value="Cash">Cash</option></select></td>
	<td> 
	 <div style="padding:0px;"> <!--class="form-group"--> 
      <div class="input-group date form_date col-md-12" data-date="" data-date-format="dd-mm-yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="padding:0px;">
      <input id="PayDate<?=$i?>" name="PayDate<?=$i?>" type="text" value="" style="background-color:#D9ECFF; width:100%; text-align:center;" readonly>
     <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>-->
      <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
     </div>
     <input type="hidden" id="dtp_input2" value="" />
     </div>
	</td>
	<td><input id="PayRemark<?=$i?>" name="PayRemark<?=$i?>" style="background-color:#D9ECFF;width:100%;"></td>
	 <td>
	 <?php
		$name=getUser($mlist['EmployeeID']);
		$month=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y')));
	 ?>
	 <button type="button" class="btn btn-sm btn-warning" onclick="closeClaimMonth('<?=$mlist['Month']?>','<?=$mlist['EmployeeID']?>','<?=$name?>','<?=$month?>',<?=$i?>)" <?php //if($ff==0){echo 'disabled';}?>>Submit</button>
	 </td>
    </tr>
</form>						
    <tr id="<?=$mlist['EmployeeID']?><?=$mlist['Month']?>Approved"></tr>
<?php $i++; } ?>
 </tbody>
	  
</table>	
	</div>
<?php } // if($_REQUEST['action']=='displayrec' && $_REQUEST['chkval']==1)?>			
	
		</div>

		<br>
		<div class="row">
		
<?php if($_REQUEST['action']=='displayrec' && $_REQUEST['chkval']==1){ ?>		
			<div class="col-lg-12 shadow">
				
				<h5 ><small class="font-weight-bold text-muted">Paid Claims</small> </h5> 
				
				
				<table class="estable table shadow">
  <thead class="thead-dark">
	<tr>
	  <th scope="col" style="width:10px;vertical-align:middle;">S.No</th>
	  <th scope="col" style="width:250px;vertical-align:middle;">Claimer</th>
	  <th scope="col" style="width:50px;vertical-align:middle;">EmpCode</th>
	  <th scope="col" style="width:50px;vertical-align:middle;">Month</th>
	  <th scope="col" style="width:60px;vertical-align:middle;">Total<br />Claims</th>
	  <th scope="col" style="width:80px;vertical-align:middle;">Payment<br />Amount</th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Advance<br />Amount</th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Paid<br />Amount</th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Total<br />Amount</th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Payment<br />Option</th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Payment<br />Date</th>
	  <th scope="col" style="width:200px;vertical-align:middle;">Any<br />Remark</th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Print</th>
	</tr>
	<tr>
	  
	</tr>
  </thead>
				  
				  <tbody>
				  
<?php if($_REQUEST['v']=='' || $_REQUEST['v']==0){ $cond='1=1'; }else{ $cond='Month='.$_REQUEST['v']; } 

$m=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE YearId=".$_SESSION['FYearId']." AND ".$cond." AND Fin_AppBy>0 AND Fin_AppDate!='0000-00-00' AND Fin_AppDate!='' AND Fin_AppDate!='1970-01-01' AND Fin_PayAmt!='' AND Fin_PayOption!='' AND Fin_PayBy>0 order by EmployeeID asc"); 
$i=1; while($mlist=mysql_fetch_assoc($m)){	

$sTot=mysql_query("SELECT count(*) as TotClaim, SUM(FinancedTAmt) as TotFin FROM `y".$_SESSION['FYearId']."_expenseclaims` WHERE  `ClaimAtStep`=6 and CrBy=".$mlist['EmployeeID']." and ClaimYearId=".$_SESSION['FYearId']." and ClaimMonth=".$mlist['Month']." and FilledOkay=1 and ClaimStatus!='Deactivate' and ClaimStatus!='Draft'"); $rTot=mysql_fetch_assoc($sTot); ?>

<form action="" class="form-horizontal"  role="form">
   <tr>
	<td><?=$i?></td>
	<td style="text-align:left;"><?=getUser($mlist['EmployeeID'])?></td>
	<td><?=getCode($mlist['EmployeeID'])?></td>
	<td>
	 <a href="#" onclick="showmonthdet('<?=$mlist['Month']?>','Closed','<?=$mlist['EmployeeID']?>','Closed')">
	  <?=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y')));?>	
	 </a>
	 <input type="hidden" id="sts<?=$mlist['EmployeeID']?><?=$mlist['Month']?>Closed" value="close">  
	</td>
	<td><?=$rTot['TotClaim'];?></td>
	<td style="text-align:right;"><b><?=$rTot['TotFin'];?></b>&nbsp;</td>
	<td style="text-align:right;"><?=$mlist['Fin_AdvancePay'];?>&nbsp;</td>
	<td style="text-align:right;"><?=$mlist['Fin_PayAmt'];?>&nbsp;</td>
	<td style="text-align:right;"><b><?=$mlist['Fin_AdvancePay']+$mlist['Fin_PayAmt'];?></b>&nbsp;</td>
	<td style="text-align:left;"><?=$mlist['Fin_PayOption'];?></td>
	<td style="text-align:center;"><b><?=date("d-m-Y",strtotime($mlist['Fin_PayDate']));?></b></td>
	<td><input style="width:100%; border:hidden;" value="<?=$mlist['Fin_PayRemark'];?>"></td>
	 <td>
	  <span onclick="FunPrint(<?php echo $mlist['EmployeeID'].','.$mlist['Month'].','.$_SESSION['FYearId'];?>,1)" style="color:#000099; cursor:pointer;"><u>One</u></span>
	  &nbsp;
	  <span onclick="FunPrint(<?php echo $mlist['EmployeeID'].','.$mlist['Month'].','.$_SESSION['FYearId'];?>,2)" style="color:#000099; cursor:pointer;"><u>Two</u></span>
	 </td>
    </tr>
</form>						
    <tr id="<?=$mlist['EmployeeID']?><?=$mlist['Month']?>Closed"></tr>
<?php $i++; } ?>
 </tbody>
		  
</table>
				
			</div>
<?php } //if($_REQUEST['action']=='displayrec' && $_REQUEST['chkval']==2) ?>			
			
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
	function closeClaimMonth(month,crby,username,monthname,n){
		// alert('dfdffdf');
		// alert(month+','+crby+','+username+','+monthname);
		
		var FinAmt=parseFloat($('#FinnAmt'+n).val());
		var AdvAmt=parseFloat($('#AdvAmt'+n).val());
		var PayAmt=parseFloat($('#PayAmt'+n).val());
		var PayOption=$('#PayOption'+n).val();
	    var PayDate=$('#PayDate'+n).val();
		var PayRemark=$('#PayRemark'+n).val();   
		if(PayAmt==''){ alert("pleasse input payment amount"); return false; }
		var tamt=AdvAmt+PayAmt; 
		if(tamt>FinAmt){alert("please check amount"); return false;}
		if(PayDate==''){ alert("pleasse input payment date"); return false; }
		
		if (confirm('Are you sure?')){
         
			$.post("homeajax.php",{act:"PaymentClaimMonth",month:month,crby:crby,PayDate:PayDate,PayRemark:PayRemark,AdvAmt:AdvAmt,PayAmt:PayAmt,PayOption:PayOption},function(data){
				console.log(data);
				if(data.includes('payment')){
					alert( username+'\'s '+monthname+' Payment Successfully Submitted');
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