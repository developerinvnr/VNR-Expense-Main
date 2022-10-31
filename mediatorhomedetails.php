<?php
error_reporting(0);
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

<div class="container-fluid ">
 <div class="row h-50">
 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
.vr:hover{ background-color: #a4900f; color: white !important; }
.fi:hover{ background-color: #0d01bd; color: white !important; }
.ap:hover{ background-color: #01bd43; color: white !important; }
.sub:hover{	background-color:#c41a6a; color: white !important; }
</style>
</head>
   
<!-- 2222222222222222222222 Open -->
<!-- 2222222222222222222222 Open -->
<?php  
if($_SESSION['CompanyId']==1)
{
 $sqls = mysql_query("select StateId from hrm_user_state where UserId=".$_SESSION['EmployeeID']." AND Status=1 group by StateId");
while($ress=mysql_fetch_array($sqls)){ $array_data[]=$ress['StateId']; } $state_data = implode(',', $array_data);
}
else
{
  $sqls = mysql_query("select CostCenter from hrm_employee_general group by CostCenter",$con2);
while($ress=mysql_fetch_array($sqls)){ $array_data[]=$ress['CostCenter']; } $state_data = implode(',', $array_data);
}
$maxDate="and CrDate>='2019-10-01' AND BillDate>='2019-10-01' ";
$maxDatee="and e.CrDate>='2019-10-01' AND e.BillDate>='2019-10-01' ";
?>
    <div class="col-lg-1"></div>
    
	<div id="claimerdiv" class="col-lg-11  col-md-8" >
	<!-- <div id="claimerdiv" class="col-lg-10  col-md-10" style="border-left:5px solid #d9d9d9;"> -->
	 <div class="row">
      <div class="col-lg-12"><br />
	   <h5 style="box-shadow: 0px 0px 5px 0px #888 !important;background-color:white; padding: 6px;"><small class="font-weight-bold text-muted" style="font-size: 13px !important;">CLAIMS TO BE FILLED :</small>
	   <?php $mn=date("Y-m-01",strtotime('-1 month', strtotime(date("Y-m-d")))); ?>
 
	  <?php $q_Ex2= "SELECT count(*) AS countT2 FROM y".$_SESSION['FYearId']."_expenseclaims e where e.ClaimStatus!='Deactivate' and e.ClaimStatus='Submitted' and e.Rmk=1 and AttachTo=0"; $seleq_Ex2=mysql_query($q_Ex2); //and g.CostCenter in (".$state_data.") 
             $rrs_Ex2 = mysql_fetch_assoc($seleq_Ex2); ?>
      <span class="btn btn-sm btn-outline-secondary font-weight-bold">H:&nbsp;<span class="badge badge-secondary" style="font-size: 10px;"><a href="home.php?&sts=Hold" style="color: white;">Hold (<?=$rrs_Ex2['countT2'];?>)</a></span></span>
 
      
      <?php $q_count = "SELECT e.ClaimStatus, count(*) AS Count FROM `y".$_SESSION['FYearId']."_expenseclaims`e where e.ClaimStatus!='Deactivate' and e.ClaimStatus='Draft' and Filledokay!=2 and AttachTo=0"; //and g.CostCenter in (".$state_data.") 
      $seleq_month=mysql_query($q_count); $rrs = mysql_fetch_assoc($seleq_month); ?>
      <span class="btn btn-sm btn-outline-secondary font-weight-bold">Drft:&nbsp;<span class="badge badge-secondary" style="font-size: 10px;"><a href="home.php?&sts=Draft" style="color: white;">Draft (<?=$rrs['Count'];?>)</a></span></span>
	  
	  
      <?php if($_SESSION['EmployeeID']==10 OR $_SESSION['EmployeeID']==51116){ $q_count2 = "SELECT e.ClaimStatus, count(*) AS Count FROM `y".$_SESSION['FYearId']."_expenseclaims`e where e.ClaimStatus!='Deactivate' and e.ClaimStatus='Filled' and Filledokay!=2 and AttachTo=0 and e.BillDate>='".$mn."'"; //and g.CostCenter in (".$state_data.") 
      $seleq_month2=mysql_query($q_count2); $rrs2 = mysql_fetch_assoc($seleq_month2); ?>       	
      <span class="btn btn-sm btn-outline-success font-weight-bold">F:&nbsp;<span class="badge badge-success" style="font-size: 10px;"><a href="home.php?&sts=Filled" style="color: white;">Filled  <?php if($_SESSION['EmployeeID']!=8){?>(<?=$rrs2['Count'];?>)<?php } ?></a></span></span><?php  } ?>


      <?php $q_count3 = "SELECT e.ClaimStatus, count(*) AS Count FROM `y".$_SESSION['FYearId']."_expenseclaims`e where e.ClaimStatus!='Deactivate' and e.ClaimStatus='Submitted' and e.Rmk=0 and AttachTo=0"; $seleq_month3=mysql_query($q_count3); $rrs3 = mysql_fetch_assoc($seleq_month3); //and g.CostCenter in (".$state_data.")  ?>       	
      <span class="btn btn-sm font-weight-bold sub" style="border-color: #c41a6a !important; color: #c41a6a;">S:&nbsp;<span class="badge badge-success" style="font-size: 10px; background-color: #c41a6a !important;"><a href="home.php?&sts=Submitted" style="color: white;"><!--Submitted--> Uploaded  (<?=$rrs3['Count'];?>)</a></span></span>

     <?php $q_Ex = "SELECT count(*) AS count FROM `y".$_SESSION['FYearId']."_expenseclaims` e WHERE e.`ClaimYearId`='".$_SESSION['FYearId']."' and e.FilledOkay=2 and e.ClaimStatus!='Deactivate' and e.ClaimStatus!='Draft' and e.ClaimStatus='Filled' and e.FilledBy>0 and e.AttachTo=0"; $seleq_Ex=mysql_query($q_Ex); $rrs_Ex = mysql_fetch_assoc($seleq_Ex);
     //and g.CostCenter in (".$state_data.") 
     ?>
     <span class="btn btn-sm btn-outline-danger font-weight-bold">D:&nbsp;<span class="badge badge-danger" style="font-size: 10px;"><a href="home.php?&sts=Denied" style="color: white;">Denied (<?=$rrs_Ex['count'];?>)</a></span></span>  
     &nbsp;&nbsp; 
	 
<script type="text/javascript">
function Funve(vv,sts,t)                 
{ if(t=='e'){ var ve=vv; var vd=document.getElementById("SelDate").value; }
  else if(t=='d'){ var vd=vv; var ve=document.getElementById("SelMonth").value; }
  window.location="home.php?sts="+sts+"&ve="+ve+"&vd="+vd;
}
</script>	 

 <?php if($_REQUEST['sts']!=''){$stst=$_REQUEST['sts'];}else{$stst='Submitted';} ?> 
 <?php if($_REQUEST['ve']!=''){$ve=$_REQUEST['ve'];}else{$ve=0;} 
       if($_REQUEST['vd']!=''){$vd=$_REQUEST['vd'];}else{$vd=0;}?>  
 <select style="font-size:14px; width:180px;" id="SelMonth" onchange="Funve(this.value,'<?=$stst;?>','e')">
  <option value="0" <?php if($_REQUEST['ve']=='' || $_REQUEST['ve']==0){echo 'selected';}?>>All Employee</option>			  
  <?php if(isset($_GET['sts']))
        {
          if($_GET['sts']=='Denied'){ $sts = "and e.ClaimMonth!=0 and e.ClaimStatus='Filled' and e.Filledokay=2 and FilledBy>0 and AttachTo=0 and AttachTo=0"; }
          elseif($_GET['sts']=='Filled'){ $sts = "and e.ClaimMonth!=0 and e.ClaimStatus='Filled' and e.Filledokay!=2 and e.BillDate>='".$mn."' and AttachTo=0"; }
          elseif($_GET['sts']=='Hold'){ $sts = "and e.ClaimStatus!='Deactivate' and e.ClaimStatus='Submitted' and e.Rmk=1 and AttachTo=0"; }
          elseif($_GET['sts']=='Draft'){ $sts = "and e.ClaimStatus='Draft' and e.Filledokay!=2 and AttachTo=0"; }
          elseif($_GET['sts']=='Submitted'){ $sts = "and e.ClaimStatus!='Deactivate' and e.ClaimStatus='Submitted' and e.Rmk=0 and AttachTo=0"; }
          else{ $sts = "and e.ClaimStatus='Submitted' and e.ClaimAtStep!=2 and e.Filledokay!=2 and AttachTo=0"; }
        }
        else{ $sts = "and e.ClaimStatus!='Deactivate' and e.ClaimStatus='Submitted' and e.Rmk=0 and AttachTo=0"; }

  $seleqe=mysql_query("SELECT e.CrBy, CASE WHEN e.ClaimId!=0 THEN BillDate WHEN e.ClaimId=0 THEN CrDate END AS finalDate  FROM `y".$_SESSION['FYearId']."_expenseclaims`e where e.ClaimStatus!='Deactivate' ". $sts." GROUP BY e.CrBy ORDER BY e.CrBy ASC"); //and g.CostCenter in (".$state_data.")
  while($expe=mysql_fetch_assoc($seleqe))
  { 

   $senn=mysql_query("select Fname, Sname, Lname from hrm_employee where EmployeeID=".$expe['CrBy'],$con2); 
   $expnn=mysql_fetch_assoc($senn);
  ?>
  <option value="<?=$expe['CrBy']?>" <?php if($_REQUEST['ve']==$expe['CrBy']){echo 'selected';}?>><?=$expnn['Fname'].' '.$expnn['Sname'].' '.$expnn['Lname']?></option>
  <?php } //while ?>								  
 </select>
 
 <select style="font-size:14px; width:120px;" id="SelDate" onchange="Funve(this.value,'<?=$stst;?>','d')">
  <option value="0" <?php if($_REQUEST['vd']=='' || $_REQUEST['vd']==0){echo 'selected';}?>>All Date</option>			  
  <?php if(isset($_GET['sts']))
        {
          if($_GET['sts']=='Denied'){ $sts = "and e.ClaimMonth!=0 and e.ClaimStatus='Filled' and e.Filledokay=2 and FilledBy>0 and AttachTo=0 and AttachTo=0"; }
          elseif($_GET['sts']=='Filled'){ $sts = "and e.ClaimMonth!=0 and e.ClaimStatus='Filled' and e.Filledokay!=2 and e.BillDate>='".$mn."' and AttachTo=0"; }
          elseif($_GET['sts']=='Hold'){ $sts = "and e.ClaimStatus!='Deactivate' and e.ClaimStatus='Submitted' and e.Rmk=1 and AttachTo=0"; }
          elseif($_GET['sts']=='Draft'){ $sts = "and e.ClaimStatus='Draft' and e.Filledokay!=2 and AttachTo=0"; }
          elseif($_GET['sts']=='Submitted'){ $sts = "and e.ClaimStatus!='Deactivate' and e.ClaimStatus='Submitted' and e.Rmk=0 and AttachTo=0"; }
          else{ $sts = "and e.ClaimStatus='Submitted' and e.ClaimAtStep!=2 and e.Filledokay!=2 and AttachTo=0"; }
        }
        else{ $sts = "and e.ClaimStatus!='Deactivate' and e.ClaimStatus='Submitted' and e.Rmk=0 and AttachTo=0"; }

  $seleqe=mysql_query("SELECT e.CrDate, CASE WHEN e.ClaimId!=0 THEN BillDate WHEN e.ClaimId=0 THEN CrDate END AS finalDate  FROM `y".$_SESSION['FYearId']."_expenseclaims` e where e.ClaimStatus!='Deactivate' ". $sts." GROUP BY e.CrDate ORDER BY e.CrDate ASC"); 
  //and g.CostCenter in (".$state_data.") 
  while($expe=mysql_fetch_assoc($seleqe))
  { 

  ?>
  <option value="<?=$expe['CrDate']?>" <?php if($_REQUEST['vd']==$expe['CrDate']){echo 'selected';}?>><?=date("d-m-Y",strtotime($expe['CrDate']))?></option>
  <?php } //while ?>								  
 </select>
		 
 &nbsp;&nbsp;<font style="font-size:18px; font-family:'Times New Roman', Times, serif; color:#FF0000;"><?=$_REQUEST['sts']?></font><span style="float: right;"><a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a></span></h5>
	   
  
  <td style="background-color:#CCCCCC;"></td>
  <td colspan="10">
   <table class="table" style="padding:0px;box-shadow:0px 0px 5px 0px #888 !important;background-color:white !important;">
	<thead style="background-color:#ffbf80;border:1px solid black;">
	 <tr style=" text-transform: uppercase;">
	  <th scope="col" style="width:5%;text-align:center;">Sn</th>
	  <th scope="col" style="width:5%;text-align:center;">Claim<br />ID</th>
	  <th scope="col" style="width:20%;text-align:center;">Claimer</th>
	  <th scope="col" style="width:10%; text-align:center;">Date</th>
	  <th scope="col" style="width:10%; text-align:center;">Claim Status</th>
	  <th scope="col" style="width:10%; text-align:center;">Claimer Remark</th>
	  <th scope="col" style="width:10%; text-align:center;">Action</th>
	  <?php if($_GET['sts']=='Hold'){ ?><th scope="col" style="width:40%;text-align:left;">Remark</th><?php } ?>
     </tr>
    </thead>
    <tbody>

<?php

 if($ve>0 OR $vd>0 OR $_GET['sts']=='Submitted')
 { 

   $j=1; $totcount=0; $i=1;
   if($ve>0){ $sqr=' AND e.CrBy='.$ve;}else{$sqr='';}
   if($vd>0){ $sqr2=" AND e.CrDate='".$vd."'";}else{$sqr2='';}
			  
   $q="SELECT e.*, CASE WHEN e.ClaimId!=0 THEN BillDate WHEN e.ClaimId = 0 THEN CrDate END AS finalDate FROM `y".$_SESSION['FYearId']."_expenseclaims` e where e.ClaimStatus!='Deactivate' ".$sts." ".$sqr." ".$sqr2." group by e.ExpId order by finalDate, CrBy";
   //and g.CostCenter in (".$state_data.")
   //echo $q;
     $seleq=mysql_query($q);		
     while($exp=mysql_fetch_assoc($seleq))
     {
       $bclr  = '';
       if($exp['ClaimStatus']=='Verified' or $exp['ClaimStatus']=='Approved' or $exp['ClaimStatus']=='Financed')
       {
		 switch ($exp['ClaimStatus']) 
		 {
		  case 'Verified':
				$bclr = 'style="background-color: #fffdd5;"';
				break;
		  case 'Approved':
				$bclr = 'style="background-color: #cfffe2;"';
				break;
		  case 'Financed':
				$bclr = 'style="background-color: #d5e7ff;"';
				break;
		 }                
      }
?>

     <tr <?=$bclr?>>           
      <th scope="row" style="text-align:center;"><?=$i?></th>
      <td scope="row" style="text-align:center;"><?=$exp['ExpId']?></td>
      <td><?php $sen=mysql_query("select Fname, Sname, Lname from hrm_employee where EmployeeID=".$exp['CrBy'],$con2); 
                $expn=mysql_fetch_assoc($sen); echo $expn['Fname'].' '.$expn['Sname'].' '.$expn['Lname']?></td>
      <td style="text-align:center;"><?=date("d-m-Y",strtotime($exp['finalDate']))?></td>
      <td style="text-align:center; width:100px; vertical-align:middle;">
	  <?php if($exp['ClaimStatus']=='Filled'){?><div id="<?=$exp['ExpId']?>FilledBtn" class="btn btn-sm btn-success font-weight-bold" style="height:22px;width:40px;">F</div><?php } ?>

	  <?php
	   $c='outline-secondary'; if($exp['FilledOkay']==2){ $c='danger'; }
	   $s=$exp['ClaimStatus']; if($s=='Draft'){ $c='secondary'; }
	   
	   if($s=='Submitted' || $s=='Draft' || $exp['FilledOkay']==2){ ?>
	   <div id="<?=$exp['ExpId']?>Status" class="btn btn-sm btn-<?=$c?> font-weight-bold" style="height:22px; width:40px;">
	   <?php if($s=='Submitted'){echo 'Up';}elseif($s=='Draft'){echo 'Drft';}elseif($exp['FilledOkay']==2){ echo 'D';} ?>
	   </div><?php } ?> 
	   
	   <?php if($c=='danger'){ ?><input type="hidden" id="<?=$exp['ExpId']?>Rem" value="<?=$exp['FilOkDenyRemark']?>" placeholder="Remark" readonly><?php } ?>
      </td>
      <td><?=$exp['FilOkDenyRemark']?></td>
      <td style="text-align:center; width:100px;">
	  <?php if($s=='Submitted'){ ?><button id="<?=$exp['ExpId']?>btn" class="btn btn-sm btn-primary btnfill" style="height:22px;width:50px;" onclick="showexpdet('<?=$exp['ExpId']?>')">Fill</button>
	  <?php }elseif($s=='Filled'){ ?><button id="<?=$exp['ExpId']?>btn" class="btn btn-sm btn-info btnfill" style="height:22px;width:50px;" onclick="showexpdet('<?=$exp['ExpId']?>')">View</button>
	  <?php }elseif($s=='Draft'){ ?><button id="<?=$exp['ExpId']?>btn" class="btn btn-sm btn-primary btnfill" style="height:22px;width:50px;" onclick="showexpdet('<?=$exp['ExpId']?>')">Edit</button>
	  <?php } ?>
	
	  <?php 
      if($s=='Verified' or $s=='Approved' or $s=='Financed')
      {
		switch ($s) 
		{
		 case 'Verified':
			$clr = "#94900f";
			break;
		 case 'Approved':
			$clr = "#01bd43";
			break;
		 case 'Financed':
			$clr = "#0d01bd";
			break;
        }
        echo '<p style="color:'.$clr.'; border-style: outset; border-color: white;"><b>'.trim($s).'</b></p>';
      }
	  else
	  {
        $arr_exist = [19, 20, 21]; # this is your array
		if(in_array($exp['ClaimId'], $arr_exist)){ echo ""; }
		else{ ?><button id="<?=$exp['ExpId']?>atbtn" class="btn btn-sm btn-info pull-right"  style="padding:3px 3px !important;align-self: top!important;margin-right:10px;" onclick="attachclaims(this,'<?=$exp['ExpId']?>','<?=$exp['CrBy']?>')" ><i class="fa fa-paperclip" aria-hidden="true"></i></button><?php } 
	  
	  } //else ?>
      </td>
	
	  <?php if($_GET['sts']=='Hold'){ ?><td style="width:400px;"><?php include 'multipleremarkForCc.php';?></td><?php } ?>
   
     </tr>
	 <tr>
	  <td colspan="20" style="padding: 0px;margin:0px;"><iframe id="clMonClmDet<?=$exp['ExpId']?>" src="" class="clframes" width="100%" style="display:none;height: 400px;"></iframe></td>
	 </tr>
<?php			    			
     $i++; } //while($exp=mysql_fetch_assoc($seleq))

   $j++; 
                
 } //if($ve>0 OR $_GET['sts']=='Submitted')
?>
                
				<?php /*?><tr style="background-color: #e2e0f4; color: #110257;"><td style="text-align: center; font-weight: bold; font-size: 13px;">TOTAL CLAIMS</td><td colspan="7" style="font-size: 14px;"><b><?=$totcount;?></b></td></tr><?php */?>
    </tbody>
   </table>
   <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> 
  </td>
     
	  </div>
	 </div> 
	</div>
 <div class="col-lg-2"></div>

<!-- 2222222222222222222222 Close -->
<!-- 2222222222222222222222 Close -->	

  </div>
</div>


<script type="text/javascript">

	//showld be deleted
	
	function submitrem(expid,th){
		var remark=$('#RemToClaimer'+expid).val();
		if(remark !=''){
			$.post("claimlistajax.php",{act:"submitrem",remark:remark,expid:expid},function(data){
				if(data.includes('updated')){
						$(th).hide();
						$('#RemSpan'+expid).html('<p style="color:green;">Submitted</p>');
						setTimeout(function(){
							$('#RemSpan'+expid).html('');
							$(th).show();
						},1000);
				}
			});
		}else if(remark ==''){
			alert("Can't submit blank remark");
			$('#RemToClaimer'+expid).focus();
		}
		
	}
	
	function showmonthdet(sts,emp,csts,t){

		var divsToHide = document.getElementsByClassName("clMonthss");
	    for(var i = 0; i < divsToHide.length; i++){
	        divsToHide[i].style.backgroundColor = "#FFFF97"; //#fff
	        divsToHide[i].style.color = "##0056BA";
	    }


		$.post("claimlistajax.php",{act:"monthdettomediator",sts:sts,csts:csts,emp:emp},function(data){
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

	    var btnsTochng = document.getElementsByClassName("btnfill");
	    for(var i = 0; i < btnsTochng.length; i++){
	    	if(btnsTochng[i].id!=expid+'btn' && btnsTochng[i].innerHTML!='Edit' && btnsTochng[i].innerHTML!='View' && btnsTochng[i].innerHTML!='Close_View' && btnsTochng[i].innerHTML!='Close Edit'){
	    		btnsTochng[i].innerHTML='Fill';
	    	}else if(btnsTochng[i].id!=expid+'btn' && btnsTochng[i].innerHTML=='Close Edit'){
	    		btnsTochng[i].innerHTML='Edit';
	    	}else if(btnsTochng[i].id!=expid+'btn' && btnsTochng[i].innerHTML=='Close_View'){
	    		btnsTochng[i].innerHTML='View';
	    	}
	    }

		document.getElementById('clMonClmDet'+expid).style.display="block";
		document.getElementById('clMonClmDet'+expid).src="showclaim.php?expid="+expid;
		var btntxt=document.getElementById(expid+'btn').innerHTML;
		
		if(btntxt=='Fill'){
			document.getElementById(expid+'btn').innerHTML='Close';
		
		}else if(btntxt=='Edit'){
			document.getElementById(expid+'btn').innerHTML='Close Edit';
		
		}else if(btntxt=='Close'){
			document.getElementById('clMonClmDet'+expid).style.display="none";
			document.getElementById('clMonClmDet'+expid).src="";
			document.getElementById(expid+'btn').innerHTML='Fill';
		}else if(btntxt=='Close Edit'){
			document.getElementById('clMonClmDet'+expid).style.display="none";
			document.getElementById('clMonClmDet'+expid).src="";
			document.getElementById(expid+'btn').innerHTML='Edit';
		}else if(btntxt=='View'){
			document.getElementById(expid+'btn').innerHTML='Close_View';
		
		}else if(btntxt=='Close_View'){
			document.getElementById('clMonClmDet'+expid).style.display="none";
			document.getElementById('clMonClmDet'+expid).src="";
			document.getElementById(expid+'btn').innerHTML='View';
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

	function attachclaims(t,expid,crby){ 
		if (confirm("Do you want to Attach Claims to This Claim ?")) {
			var modal = document.getElementById('myModal');
			modal.style.display = "block";
			document.getElementById('claimlistfr').src="mediatorAttachClaim.php?expid="+expid+'&crby='+crby;
		} else {
			
		}
		
	}




</script>
<style type="text/css">
	@-webkit-keyframes blinker {
  from {opacity: 1.0;}
  to {opacity: 0.0;}
}
.blink{
  text-decoration: blink;
  -webkit-animation-name: blinker;
  -webkit-animation-duration: 0.6s;
  -webkit-animation-iteration-count:infinite;
  -webkit-animation-timing-function:ease-in-out;
  -webkit-animation-direction: alternate;
}
</style>