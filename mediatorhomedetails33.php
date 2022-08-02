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
   
   <!-- 1111111111111111111111 Open -->
<!--    <div class="col-lg-2  d-none  d-lg-block" >
	
	
<br /><a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
     
<div class="row"  style="padding: 0px !important;">
 <div class="col-lg-12 ">
   	<h6 style="padding-top:8px;"><!--<small class="font-weight-bold text-muted"><i>Claims To Be Filled</i></small>--></h6>		
   	<?php 
   	/*
   	<div style="width:100%;float:left;display:inline-block;border:1px solid #999999;height:70%;background-color: white;padding:0px;" class="shadow" id="mediatorProcessTableDiv">
     
		<table class="estable table " style="width:100%;float:left;width:100%;" id="mediatorProcessTable">
		<thead class="thead-dark">
			<tr>
				<th scope="col" style="width:100%;"><b>Claimer List</b>
				<?php if($_SESSION['EmpRole']=='E'){ $crcond="EmployeeID=".$_SESSION['EmployeeID'];
				}else{ $crcond="EmpRole='E'"; } ?>

				</th>
			</tr>
		</thead>
		<tbody>
		<?php 
			if(isset($_REQUEST['u']) && $_REQUEST['u']!='' && $_REQUEST['u']!='ALL')
		    { $ucond="ec.CrBy=".$_REQUEST['u']; }else{ $ucond="1=1"; }

		    if(isset($_REQUEST['cm']) && $_REQUEST['cm']!='' && $_REQUEST['cm']!='ALL')
			{ $mcond="ec.ClaimMonth=".$_REQUEST['cm']; }else{ $mcond="1=1"; }

			
			$selr=mysql_query("SELECT rEmployeeID FROM `dataentry_reporting` where uEmployeeID=".$_SESSION['EmployeeID']);
			if(mysql_num_rows($selr)>0){

				$selrd=mysql_fetch_assoc($selr);
				$reportingNo=$selrd['rEmployeeID'];
				$empshowcond="and ".$reportingNo." IN (rl.`EmployeeID`,rl.`R1`, rl.`R2`, rl.`R3`, rl.`R4`, rl.`R5`)";


			    $emp=mysql_query("SELECT ec.`CrBy` FROM `y".$_SESSION['FYearId']."_expenseclaims` ec, ".dbemp.".`hrm_sales_reporting_level` rl WHERE ec.`ClaimYearId`='".$_SESSION['FYearId']."' and ((ec.`ClaimAtStep`=2 )  or ec.ClaimAtStep=1) and ec.CrBy=rl.EmployeeID  ".$empshowcond."  group by ec.CrBy order by ec.ExpId asc");

			}else{

				// $empshowcond="AND ec.`CrBy` NOT IN (select rl.EmployeeID from  ".dbemp.".`hrm_sales_reporting_level` rl)";

				$data= mysql_query("SELECT rEmployeeID FROM dataentry_reporting GROUP BY rEmployeeID");
				while($rec=mysql_fetch_array($data)){ $array_data[]=$rec['rEmployeeID']; }
				$str_data = implode(',', $array_data);
				$empshowcond="AND rl.`EmployeeID` NOT IN (".$str_data.") AND rl.`R1` NOT IN (".$str_data.") AND rl.`R2` NOT IN (".$str_data.") AND rl.`R3` NOT IN (".$str_data.") AND rl.`R4` NOT IN (".$str_data.") AND rl.`R5` NOT IN (".$str_data.")";

			  	$emp=mysql_query("SELECT ec.`CrBy` FROM `y".$_SESSION['FYearId']."_expenseclaims` ec, ".dbemp.".`hrm_sales_reporting_level` rl WHERE ec.`ClaimYearId`='".$_SESSION['FYearId']."' and ((ec.`ClaimAtStep`=2 )  or ec.ClaimAtStep=1) ".$empshowcond."  group by ec.CrBy order by ec.ExpId asc");



			  	
			}
			$enum=mysql_num_rows($emp);
		   	if($enum > 0)
		   	{
		    while($empl=mysql_fetch_assoc($emp)){
			   
				?>
				<tr style="height:25px;">
					<td style="text-align:left; height:25px; vertical-align:middle;background-color:#FFFF97;width: 100%;">
						<a href="#" class="clMonth"  onclick="showmonthdet('Open','<?=$empl['CrBy']?>','Submitted',this)" style="background-color:#FFFF97;border:0px;display: block;color:#000000;">&emsp;<?=getUser($empl['CrBy'])?> 
						<?php
						$dnum="SELECT FilledOkay from y".$_SESSION['FYearId']."_expenseclaims where FilledOkay=2 and `ClaimYearId`='".$_SESSION['FYearId']."' and CrBy=".$empl['CrBy'];
						$dno=mysql_num_rows(mysql_query($dnum));
						if($dno!=0){ ?> <span class="blink badge badge-danger"><?=$dno?></span><?php }
						?>
						</a>

					</td>
				</tr>
				<?php  
				
		    } //While
		    
		    } //if

			?>

		</tbody>
		</table>
		
   	</div>
   	*/
   	?>
 <!-- </div> -->
<!-- </div>  -->
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style type="text/css">
		
		.vr:hover{
			background-color: #a4900f;
			color: white !important;
		}

		.fi:hover{
			background-color: #0d01bd;
			color: white !important;
		}

		.ap:hover{
            background-color: #01bd43;
			color: white !important;	
		}


		.sub:hover{
			background-color: #c41a6a;
			color: white !important;		
		}
	</style>
</head>


	
	
	
   <!-- </div> --> 
   <!-- 1111111111111111111111 Close -->
   
   <!-- 2222222222222222222222 Open -->

    <div class="col-lg-1"></div>
    
	<div id="claimerdiv" class="col-lg-11  col-md-8" >
	<!-- <div id="claimerdiv" class="col-lg-10  col-md-10" style="border-left:5px solid #d9d9d9;"> -->
	 <div class="row">
      <div class="col-lg-12"><br />
	   <h5 style="box-shadow: 0px 0px 5px 0px #888 !important;background-color:white; padding: 6px;"><small class="font-weight-bold text-muted" style="font-size: 13px !important;">CLAIMS TO BE FILLED :</small>
	   
	   <?php  $q_all = "SELECT count(*) AS countT FROM `y".$_SESSION['FYearId']."_expenseclaims`e, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and (e.ClaimStatus='Submitted' or e.ClaimStatus='Filled' or e.ClaimStatus='Draft') "; $seleq_all=mysql_query($q_all);
             $rrs_all = mysql_fetch_assoc($seleq_all); ?>
      <span class="btn btn-sm btn-outline-info font-weight-bold">All:&nbsp;<span class="badge badge-info" style="font-size: 10px;">All (<?=$rrs_all['countT'];?>)</span></span> <!--<a href="home.php?sts=home" style="color: white;"></a>-->
 
	  <?php $q_Ex2= "SELECT count(*) AS countT2 FROM y".$_SESSION['FYearId']."_expenseclaims e, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and e.ClaimAtStep=2 and e.ClaimStatus='Submitted'"; $seleq_Ex2=mysql_query($q_Ex2);
             $rrs_Ex2 = mysql_fetch_assoc($seleq_Ex2); ?>
      <span class="btn btn-sm btn-outline-secondary font-weight-bold">H:&nbsp;<span class="badge badge-secondary" style="font-size: 10px;"><a href="home.php?&sts=Hold" style="color: white;">Hold (<?=$rrs_Ex2['countT2'];?>)</a></span></span>
 
      
      <?php $q_count = "SELECT e.ClaimStatus, count(*) AS Count FROM `y".$_SESSION['FYearId']."_expenseclaims`e, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and e.ClaimStatus!='Deactivate' and e.ClaimStatus='Draft' and Filledokay!=2"; 
      $seleq_month=mysql_query($q_count); $rrs = mysql_fetch_assoc($seleq_month); ?>
      <span class="btn btn-sm btn-outline-secondary font-weight-bold">Drft:&nbsp;<span class="badge badge-secondary" style="font-size: 10px;"><a href="home.php?&sts=Draft" style="color: white;">Draft (<?=$rrs['Count'];?>)</a></span></span>
	  
	  
      <?php $q_count2 = "SELECT e.ClaimStatus, count(*) AS Count FROM `y".$_SESSION['FYearId']."_expenseclaims`e, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and e.ClaimStatus!='Deactivate' and e.ClaimStatus='Filled' and Filledokay!=2"; 
      $seleq_month2=mysql_query($q_count2); $rrs2 = mysql_fetch_assoc($seleq_month2); ?>       	
      <span class="btn btn-sm btn-outline-success font-weight-bold">F:&nbsp;<span class="badge badge-success" style="font-size: 10px;"><a href="home.php?&sts=Filled" style="color: white;">Filled  (<?=$rrs2['Count'];?>)</a></span></span>


      <?php $q_count3 = "SELECT e.ClaimStatus, count(*) AS Count FROM `y".$_SESSION['FYearId']."_expenseclaims`e, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and e.ClaimStatus!='Deactivate' and e.ClaimAtStep!=2 and e.ClaimStatus='Submitted' and Filledokay!=2"; $seleq_month3=mysql_query($q_count3); $rrs3 = mysql_fetch_assoc($seleq_month3); ?>       	
      <span class="btn btn-sm font-weight-bold sub" style="border-color: #c41a6a !important; color: #c41a6a;">S:&nbsp;<span class="badge badge-success" style="font-size: 10px; background-color: #c41a6a !important;"><a href="home.php?&sts=Submitted" style="color: white;">Submitted  (<?=$rrs3['Count'];?>)</a></span></span>

     <?php $q_Ex = "SELECT count(*) AS count FROM y1_expenseclaims e, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and e.FilledOkay=2 and e.ClaimStatus='Filled'"; $seleq_Ex=mysql_query($q_Ex); $rrs_Ex = mysql_fetch_assoc($seleq_Ex); ?>
     <span class="btn btn-sm btn-outline-danger font-weight-bold">D:&nbsp;<span class="badge badge-danger" style="font-size: 10px;"><a href="home.php?&sts=Denied" style="color: white;">Denied (<?=$rrs_Ex['count'];?>)</a></span></span>
 
     <span style="float: right;"><a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a></span>
	 </h5>
	   
	   
	   
	   <!-- <span style="width:1050px;float:left; padding-top:0px;"  id="claimerMonthClaims">
	   </span> -->

	   <td style="background-color:#CCCCCC;"></td>
		 <td colspan="10">
			<!-- <table class="table shadow" style="padding:0px;box-shadow: 0px 0px 4px -1pxrgba(0,0,0,0.75)!important;"> -->
			<table class="table" style="padding:0px;box-shadow: 0px 0px 5px 0px #888 !important; background-color: white !important;">
			  <thead  style="background-color:#ffbf80;border:1px solid black;">
			

			  	 
			    
			    <tr style=" text-transform: uppercase;">
			      <th scope="col" style="width:5%;text-align:center;">Month</th>
			      <th scope="col" style="width:5%;text-align:center;">Sn</th>
			      <th scope="col" style="width:5%;text-align:center;">Claim<br />ID</th>
			      <!-- <th scope="col" style="width: 150px;">Claim</th> -->
			      <th scope="col" style="width:20%;text-align:center;">Claimer</th>
			      <!-- <th scope="col" style="width:120px;text-align:center;">Claim Type</th> -->
			      <!--<th scope="col" style="text-align:center;">Applied By</th>-->
			      <th scope="col" style="width:10%; text-align:center;">Date</th>
			      <th scope="col" style="width:10%; text-align:center;">Claim Status</th>

			      <th scope="col" style="width:10%; text-align:center;">Action</th>
			      <!-- <th></th> -->

			      <?php if($_GET['sts']=='Hold'){ ?>
			      <th scope="col" style="width:40%;text-align:left;">Remark</th>
				  <?php } ?>
			    </tr>
			  </thead>
			  <tbody>
			  	
			  	<?php

if(isset($_GET['sts']))
{
 if($_GET['sts']=='Denied'){ $sts = "and e.ClaimMonth!=0 and e.ClaimStatus='Filled' and e.Filledokay=2"; }
 elseif($_GET['sts']=='Filled'){ $sts = "and e.ClaimMonth!=0 and e.ClaimStatus='Filled' and e.Filledokay!=2"; }
 elseif($_GET['sts']=='Hold'){ $sts = "and e.ClaimStatus='Submitted' and ClaimAtStep=2 and e.Filledokay!=2"; }
 elseif($_GET['sts']=='Draft'){ $sts = "and e.ClaimStatus='Draft' and e.Filledokay!=2"; }
 elseif($_GET['sts']=='Submitted'){ $sts = "and e.ClaimStatus='Submitted' and e.ClaimAtStep!=2 and e.Filledokay!=2"; }
 else{ $sts = "and e.ClaimStatus='Submitted' and e.ClaimAtStep!=2 and e.Filledokay!=2"; }
}
else{ $sts = "and e.ClaimStatus='Submitted' and e.ClaimAtStep!=2 and e.Filledokay!=2"; }


    			$qmonth="SELECT e.ClaimMonth, count(*) AS Count ,CASE WHEN e.ClaimId != 0  THEN BillDate 
				WHEN e.ClaimId = 0  THEN CrDate 
				END AS finalDate  FROM `y".$_SESSION['FYearId']."_expenseclaims`e, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and e.ClaimStatus!='Deactivate'  $sts GROUP BY ClaimMonth ORDER BY finalDate ASC";
	  	 	// print_r($qmonth);die();
			  	$seleq_month=mysql_query($qmonth);
			  	 // print_r(mysql_fetch_assoc($seleq_month));die();
				$j=1;
			    $totcount =0;
					$i=1;
			  	while($exp_month=mysql_fetch_assoc($seleq_month)){ 

                 $totcount =  $totcount + $exp_month['Count'];
                        $month_num = $exp_month['ClaimMonth'];
						$month_name = date("M", mktime(0, 0, 0, $month_num, 10));
						// echo $month_name."\n";
				 		?>

                       
			  		<td rowspan="<?=($exp_month['Count'] * 2)?>" style="font-weight: bold; text-transform: uppercase; color:#682d3d; text-align: center;"><?=$j."<br>".$month_name." ".date('Y', strtotime($exp_month['finalDate'])); ?></td>

			  	
          <?php
	  	
		  		// $stepcond=" e.ClaimStatus='Submitted' ";
		  		$stepcond=" e.ClaimStatus!='Deactivate' ";
		  		// $stepcond="1=1";
		  	
		  		$crcond="1=1";
				/*

				SELECT *,
				CASE WHEN ClaimId != 0  THEN BillDate 
				WHEN ClaimId = 0  THEN CrDate 
				END AS finalDate FROM `y1_expenseclaims` ORDER BY `ClaimStatus`  DESC

				*/
			  	
			  	$q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname,

			  	CASE WHEN e.ClaimId != 0  THEN BillDate 
				WHEN e.ClaimId = 0  THEN CrDate 
				END AS finalDate 

			  	 FROM `y".$_SESSION['FYearId']."_expenseclaims`e, claimtype c, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and (c.ClaimId=e.ClaimId or e.ClaimId=0) and ".$stepcond." and e.ClaimMonth = '".$exp_month['ClaimMonth']."' $sts group by e.ExpId order by finalDate, EmployeeID";

// print_r($q);die();
			  	$seleq=mysql_query($q);

			
			  	while($exp=mysql_fetch_assoc($seleq)){
               
                 $bclr  = '';
                if($exp['ClaimStatus']=='Verified' or $exp['ClaimStatus']=='Approved' or $exp['ClaimStatus']=='Financed'){

                        switch ($exp['ClaimStatus']) {
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


			    <!-- <tr onclick="showdet('<?=$exp['EmployeeID']?>')"> -->

			    <tr <?=$bclr?>>
                
			      <th scope="row" style="text-align:center;"><?=$i?></th>
			      <td scope="row" style="text-align:center;"><?=$exp['ExpId']?></td>
			      
			      <td><?php echo $exp['Fname'].' '.$exp['Sname'].' '.$exp['Lname']?></td>
			      <?php /*
			      <td><?php if($exp['ClaimId']!=0){echo substr($exp['ClaimName'],0,15);}?></td>
			      */ ?>
			      <?php /*?><td><?=$exp['Fname'].' '.$exp['Sname'].' '.$exp['Lname']?></td><?php */?>
			      <td style="text-align:center;"><?=date("d-m-Y",strtotime($exp['finalDate']))?></td>
			      <td style="text-align:center; width:100px; vertical-align:middle;">
			      	<?php if($exp['ClaimStatus']=='Filled'){?>
					<div id="<?=$exp['ExpId']?>FilledBtn" class="btn btn-sm btn-success font-weight-bold" style="height:22px;width:40px;">F</div>
			      	<?php }	?>


			      	<?php
			      	$c='outline-secondary';
			      	if($exp['FilledOkay']==2){ $c='danger';}
			      	$s=$exp['ClaimStatus'];
			      	if($s=='Draft'){ $c='secondary';}


			      	if($s=='Submitted' || $s=='Draft' || $exp['FilledOkay']==2){
			      	?>
			      	<div id="<?=$exp['ExpId']?>Status" class="btn btn-sm btn-<?=$c?> font-weight-bold" style="height:22px; width:40px;">
			      		<?php 
			      		if($s=='Submitted'){echo 'Up';}elseif($s=='Draft'){echo 'Drft';}elseif($exp['FilledOkay']==2){ echo 'D';}
			      		?>
		      		</div>
		      		<?php 
		      		} 

		      		if($c=='danger'){ ?>
			      	<input type="hidden" id="<?=$exp['ExpId']?>Rem" value="<?=$exp['FilOkDenyRemark']?>" placeholder="Remark" readonly>
			      	<?php } ?>
			      	
			      </td>

			      <td style="text-align:center; width:100px;">
			      	<?php 
			      	if ($s=='Submitted') {
				      	?>
				      	<button id="<?=$exp['ExpId']?>btn" class="btn btn-sm btn-primary btnfill" style="height:22px;width:50px;" onclick="showexpdet('<?=$exp['ExpId']?>')">Fill</button>
				      	<?php 
				    }elseif($s=='Filled'){
				     	?>
				     	<button id="<?=$exp['ExpId']?>btn" class="btn btn-sm btn-info btnfill" style="height:22px;width:50px;" onclick="showexpdet('<?=$exp['ExpId']?>')">View</button>
				     	<?php
				    }elseif($s=='Draft'){
				     	?>
				     	<button id="<?=$exp['ExpId']?>btn" class="btn btn-sm btn-primary btnfill" style="height:22px;width:50px;" onclick="showexpdet('<?=$exp['ExpId']?>')">Edit</button>
				     	<?php
				    }
			      	?>
			      	
			      	<?php 
                   if($s=='Verified' or $s=='Approved' or $s=='Financed'){

                        switch ($s) {
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
                    }else{

                        $arr_exist = [19, 20, 21]; # this is your array
						if(in_array($exp['ClaimId'], $arr_exist)){ 

                          echo "";
                          
                     	}else{

                      if($s!='Filled'){ ?>

				      	<button id="<?=$exp['ExpId']?>atbtn" class="btn btn-sm btn-info pull-right"  style="padding:3px 3px !important;align-self: top!important;margin-right:10px;" onclick="attachclaims(this,'<?=$exp['ExpId']?>','<?=$exp['CrBy']?>')" >
								<i class="fa fa-paperclip" aria-hidden="true"></i>
						</button>

                  <?php } } }?>

     	
			      </td>
			     
			     	<!-- <td colspan="4" style="width: 400px;">
			     		
			     		<input id="RemToClaimer<?=$exp['ExpId']?>" value="<?=$exp['DateEntryRemark']?>" placeholder="Remark to Claimer" style="width: 70%;border:0px;" >
			     		<button onclick="submitrem('<?=$exp['ExpId']?>',this)">Submit</button>
			     		<span id="RemSpan<?=$exp['ExpId']?>" style="display: inline-block;"></span>
			     	</td> -->
			     	<!-- <td style="width: 400px;"></td> -->
			     	
			     	<?php if($_GET['sts']=='Hold'){ ?>
			     	<td style="width:400px;">
			     		<?php include 'multipleremarkForCc.php';?>
			     	</td>
			       <?php } ?>
			    </tr>
			    <tr id="">
			    	<td colspan="20" style="padding: 0px;margin:0px;">
			    	 <iframe id="clMonClmDet<?=$exp['ExpId']?>" src="" class="clframes" width="100%" style="display:none;height: 400px;"></iframe>
			    	</td>
			    </tr>
			    <?php
			    $i++;	
				}
				
				?>
              
                <?php $j++; }?>
                <tr style="background-color: #e2e0f4; color: #110257;"><td style="text-align: center; font-weight: bold; font-size: 13px;">TOTAL CLAIMS</td><td colspan="7" style="font-size: 14px;"><b><?=$totcount;?></b></td></tr>
			  </tbody>
			</table>
			<br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> 
		</td>
	   
	  </div>
	 </div> 
	</div>
 <div class="col-lg-2"></div>

   <!-- 2222222222222222222222 Close -->	

  </div>
</div>


<!-- <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br>  -->


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