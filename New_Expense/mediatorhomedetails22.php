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

$selr=mysql_query("SELECT rEmployeeID FROM `dataentry_reporting` where uEmployeeID=".$_SESSION['EmployeeID']);
if(mysql_num_rows($selr)>0){
	$selrd=mysql_fetch_assoc($selr);
	$reportingNo=$selrd['rEmployeeID'];
	$empshowcond="and ".$reportingNo." IN (rl.`EmployeeID`,rl.`R1`, rl.`R2`, rl.`R3`, rl.`R4`, rl.`R5`)";
}else{
	$data= mysql_query("SELECT rEmployeeID FROM dataentry_reporting GROUP BY rEmployeeID");
	while($rec=mysql_fetch_array($data)){ $array_data[]=$rec['rEmployeeID']; }
	$str_data = implode(',', $array_data);
	$empshowcond="AND rl.`EmployeeID` NOT IN (".$str_data.") AND rl.`R1` NOT IN (".$str_data.") AND rl.`R2` NOT IN (".$str_data.") AND rl.`R3` NOT IN (".$str_data.") AND rl.`R4` NOT IN (".$str_data.") AND rl.`R5` NOT IN (".$str_data.")";
	
	//$empshowcond="and rl.`EmployeeID`!= 140 and rl.`R1` != 140 and rl.`R2` != 140 and rl.`R3` != 140 and rl.`R4` != 140 and rl.`R5` != 140 and rl.`EmployeeID` != 531 and rl.`R1`!= 531 and  rl.`R2` != 531 and rl.`R3` != 531 and rl.`R4` != 531 and rl.`R5` != 531";
}
?>

<div class="container-fluid ">
 <div class="row h-100">
   
   <!-- 1111111111111111111111 Open -->
   <div class="col-lg-2  d-none  d-lg-block">
	<div class="text-muted pull-left">
	
<br /><a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
     
<div class="row">
 <div class="col-lg-12 ">
   <h6 style="padding-top:8px;"><!--<small class="font-weight-bold text-muted"><i>Claims To Be Filled</i></small>--></h6>		
   <div style="float:left;display:inline-block;border:1px solid #999999;height:70%;background-color: white;padding:0px;" class="shadow" id="mediatorProcessTableDiv">
     
<table class="estable table " style="width:150px;float:left;width:100%;" id="mediatorProcessTable">
<thead class="thead-dark">
 <tr>
  <th scope="col" style="width:150px;"><b>Claimer List</b>
  <?php if($_SESSION['EmpRole']=='E'){ $crcond="EmployeeID=".$_SESSION['EmployeeID'];
	    }else{ $crcond="EmpRole='E'"; } ?>
  </th>
 </tr>
</thead>
<tbody>
<?php if(isset($_REQUEST['u']) && $_REQUEST['u']!='' && $_REQUEST['u']!='ALL')
      { $ucond="ec.CrBy=".$_REQUEST['u']; }else{ $ucond="1=1"; }

      if(isset($_REQUEST['cm']) && $_REQUEST['cm']!='' && $_REQUEST['cm']!='ALL')
	  { $mcond="ec.ClaimMonth=".$_REQUEST['cm']; }else{ $mcond="1=1"; }

      $emp=mysql_query("SELECT *,COUNT(ExpId) as count FROM `y".$_SESSION['FYearId']."_expenseclaims` ec, ".dbemp.".`hrm_sales_reporting_level` rl WHERE ec.`ClaimYearId`='".$_SESSION['FYearId']."' and ec.`ClaimAtStep`=2 and ec.ClaimStatus='Submitted' and ec.CrBy=rl.EmployeeID  ".$empshowcond."  group by ec.CrBy  order by ec.ExpId asc");
      while($empl=mysql_fetch_assoc($emp))
      {
	   $enum=mysql_num_rows($emp);
	   if($enum > 0)
	   {
?>
 <tr style="height:25px;">
  <td style="text-align:left; height:25px; vertical-align:middle;background-color:#FFFF97;">
   <a href="#" class="clMonth"  onclick="showClaimerMonth('<?=$empl['CrBy']?>',this)" style="background-color:#FFFF97;border:0px;display: block;color:#000000;"><?=getUser($empl['CrBy'])?></a>
   <div id="claimerMonths<?=$empl['CrBy']?>" class="claimerMonthsDiv" style="background-color:#FFFF97;"></div>
  </td>
 </tr>
<?php  }
     } //While
?>

</tbody>
</table>
   </div>
 </div>
</div> 



	
	
	</div>
   </div>
   <!-- 1111111111111111111111 Close -->
   
   <!-- 2222222222222222222222 Open -->
	<div id="claimerdiv" class="col-lg-10  col-md-12" style="border-left:5px solid #d9d9d9;">
	 <div class="row">
      <div class="col-lg-12"><br />
	   <h5><small class="font-weight-bold text-muted"><i>Claims To Be Filled</i></small></h5>
	   
	   <span style="width:1050px;float:left; padding-top:0px;"  id="claimerMonthClaims">
	   </span>
	   
	  </div>
	 </div> 
	</div>
   <!-- 2222222222222222222222 Close -->	

  </div>
</div>

<script type="text/javascript">


	function showClaimerMonth(emp,t){

		var divsToHide = document.getElementsByClassName("clMonth");
	    for(var i = 0; i < divsToHide.length; i++){
	        divsToHide[i].style.borderBottom = "thick solid #FFFF97"; //#fff
	    }


		var divsToHide = document.getElementsByClassName("claimerMonthsDiv");
	    for(var i = 0; i < divsToHide.length; i++){
	        divsToHide[i].innerHTML = "";
	    }

		$.post("claimlistajax.php",{act:"showClaimerMonth",emp:emp},function(data){
			$('#claimerMonths'+emp).html(data);
			$(t).css('border-bottom','thick solid #FFFF97 ');  //#ffbf80
			$('#claimerMonthClaims').html('');
		});
			
	}
	
	function showmonthdet(month,sts,emp,csts,t){

		var divsToHide = document.getElementsByClassName("clMonthss");
	    for(var i = 0; i < divsToHide.length; i++){
	        divsToHide[i].style.backgroundColor = "#FFFF97"; //#fff
	        divsToHide[i].style.color = "##0056BA";
	    }


		$.post("claimlistajax.php",{act:"monthdettomediator",month:month,sts:sts,csts:csts,emp:emp},function(data){
			$('#claimerMonthClaims').html(data);
			$(t).css('background-color','#ffbf80 ');
			$(t).css('border-right','10px solid #red '); 
			$(t).css('color','#000');

			// $('#mediatorProcessTableDiv').css('border-right','thick solid #ffbf80 ');

			
		});
			
		
	}
	// function showmonthdet(month,sts,emp,csts){
		
	// 	var modal = document.getElementById('myModal');
	// 	modal.style.display = "block";
	// 	document.getElementById('claimlistfr').src='showclaimslist.php?action=mediator&month='+month+'&sts='+sts+'&emp='+emp+'&csts='+csts;
	// }
	function submitforcheck(month,crby){
		if (confirm('Are you sure to Final Submit this month claims?')){
			$.post("homeajax.php",{act:"submitforcheck",month:month,crby:crby},function(data){
				console.log(data);
				if(data.includes('submitted')){
					alert('Submitted Successfully');
					location.reload();
				}

			});
		}

	}

	function showexpdet(expid,num){


		var framesToHide = document.getElementsByClassName("clframes");
	    for(var i = 0; i < framesToHide.length; i++){
	        framesToHide[i].style.display = "none";
	    }

		document.getElementById('clMonClmDet'+expid).style.display="block";
		document.getElementById('clMonClmDet'+expid).src="showclaim.php?expid="+expid;
		var btntxt=document.getElementById(expid+'btn').innerHTML;
		
		if(btntxt=='Fill'){
		document.getElementById(expid+'btn').innerHTML='Close';
		
		}else if(btntxt=='Close'){
		document.getElementById('clMonClmDet'+expid).style.display="none";
		document.getElementById('clMonClmDet'+expid).src="";
		document.getElementById(expid+'btn').innerHTML='Fill';
		}
		
	}


	function filter(){

	var u = $('#userfr').val();
	var cm = $('#claimonth').val();
	

	window.open("home.php?u="+u+"&cm="+cm,"_self")
	}

	function filtert(){

	var u = $('#userfrt').val();
	var cm = $('#claimontht').val();
	

	window.open("home.php?ut="+u+"&cmt="+cm,"_self")
	}

</script>