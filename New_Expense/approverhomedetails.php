<div id="apprdiv" class="col-lg-9 col-md-12 h-100" style="border-left:5px solid #d9d9d9;<?php if($_SESSION['EmpRole']=='A'){echo 'display:none;';} ?> ">
 
 <br><a class="btn btn-sm btn-primary" href="home.php?show=appr"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
 &nbsp;
  <?php $svs=mysql_query("select MoveRep from hrm_employee where EmployeeID=".$_SESSION['EmployeeID'],$con2); 
      $resv=mysql_fetch_assoc($svs); 
      if($resv['MoveRep']=='Y'){ ?>
	 <a class="btn btn-sm btn-warning" href="https://www.vnress.in/expense/RepIndxHome.php?ID=<?=$_SESSION['EmployeeID']?>" target="_blank">Other Team Group</a> 
  <?php } ?>
 
 
 <div class="row" style="padding-top:8px;">
  <div class="col-lg-11 shadow  table-responsive">
  <h6 style="padding-top:8px;"><small class="font-weight-bold text-muted"><i>Pending Claims</i></small>&nbsp;&nbsp;<span class="btn btn-sm btn-outline-warning font-weight-bold">P: Pending</span> <span class="btn btn-sm btn-outline-success font-weight-bold">A: Approved</span> </h6> 
  

<table class="estable table shadow ">
 <thead class="thead-dark">
  <tr>
   <th scope="col" style="width:10px;">Sn</th>
   <th scope="col" style="width:220px;">Claimer Name</th>
   <th scope="col" style="width:50px;">Month</th>
   <th scope="col" style="width:50px;">Total Claims</th>
   <th scope="col" style="width:120px;">Action</th>
  </tr>
 </thead>
 <tbody>
 <?php $e=mysql_query("SELECT f.* FROM `y".$_SESSION['FYearId']."_monthexpensefinal` f inner join ".dbemp.".hrm_employee_reporting r on f.EmployeeID=r.EmployeeID WHERE YearId=".$_SESSION['FYearId']." and r.AppraiserId=".$_SESSION['EmployeeID']." and `Status`='Closed' and Total_Claim>0 and Verified_Amount>0 and Verified_Date!='0000-00-00' and (Approved_Amount=0 OR Approved_Date='0000-00-00') order by Month asc, EmployeeID");  
  $i=1;
  while($enumd=mysql_fetch_assoc($e))
  {   
?>
  <tr>
   <td><?=$i?></td>
   <td style=" text-align:left;"><a href="javascript:void(0)" onclick="showmonthdet('<?=$enumd['Month']?>','Open','<?=$enumd['EmployeeID']?>','Verified')"><?php $u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$enumd['EmployeeID'],$con2); $un=mysql_fetch_assoc($u); echo $un['Fname'].' '.$un['Sname'].' '.$un['Lname']; ?></a></td>
  <td><a href="javascript:void(0)" onclick="showmonthdet('<?=$enumd['Month']?>','Open','<?=$enumd['EmployeeID']?>','Verified')"><?=date('F', mktime(0,0,0,$enumd['Month'], 1, date('Y')));?></a></td>
  <td>
  <?php if($enumd['Total_Claim']>0){ ?>	  
  <span class="btn btn-sm btn-outline-warning font-weight-bold"><?=$enumd['Total_Claim']?></span>
  <input type="hidden" id="sts<?=$enumd['EmployeeID']?><?=$enumd['Month']?>Verified" value="close"> 
  <?php } ?>
  </td>
  <td><button type="button" class="btn btn-sm btn-primary" onclick="submittofinance('<?=$enumd['Month']?>','<?=$enumd['EmployeeID']?>')">Submit</button>
      
      <?php /**
      &nbsp;&nbsp;
	  <button type="button" class="btn btn-sm btn-warning" onclick="submitFtoFreturn('<?=$enumd['Month']?>','<?=$enumd['EmployeeID']?>')">Return</button>
	  */ ?>
  </td><!--Submit To Finance-->
 </tr>
 <tr id="<?=$enumd['EmployeeID']?><?=$enumd['Month']?>Verified"></tr>
<?php 
$i++; } //While ?>
 </tbody>
</table>

   </div>
  </div>
  <br>
 <div class="row">
 <div class="col-lg-11 shadow" style="height:400px; overflow:scroll;">
 <h6 style="padding-top:8px;"><small class="font-weight-bold text-muted"><i>Approved Claims</i></small> </h6> 

<table class="estable table shadow ">
 <thead class="thead-dark">
  <tr>
   <th scope="col" style="width: 10px;">Sn</th>
   <th scope="col" style="width:150px;">Claimer Name</th>
   <th scope="col" style="width: 50px;">Month</th>
   <th scope="col" style="width: 50px;">Total<br />Claims</th>
  </tr>
 </thead>
 <tbody>
 <?php  
  /*$sql_statement=mysql_query("SELECT f.* FROM `y".$_SESSION['FYearId']."_monthexpensefinal` f inner join ".dbemp.".hrm_employee_reporting r on f.EmployeeID=r.EmployeeID WHERE YearId=".$_SESSION['FYearId']." and r.AppraiserId=".$_SESSION['EmployeeID']." and `Status`='Closed' and Total_Claim>0 and Verified_Amount>0 and Verified_Date!='0000-00-00' and Approved_Amount>0 and Approved_Date!='0000-00-00' order by EmployeeID asc,Month asc");			
					
$total_records = mysql_num_rows($sql_statement);
if(isset($_GET['page']))
$page = $_GET['page'];
else
$page = 1;
$offset = 15;
if ($page){
$from = ($page * $offset) - $offset;
}else{
$from = 0;
}	*/				
	  			
  $ea=mysql_query("SELECT f.* FROM `y".$_SESSION['FYearId']."_monthexpensefinal` f inner join ".dbemp.".hrm_employee_reporting r on f.EmployeeID=r.EmployeeID WHERE YearId=".$_SESSION['FYearId']." and r.AppraiserId=".$_SESSION['EmployeeID']." and `Status`='Closed' and Total_Claim>0 and Verified_Amount>0 and Verified_Date!='0000-00-00' and Approved_Amount>0 and Approved_Date!='0000-00-00' order by Month asc, EmployeeID");  // LIMIT ".$from.",".$offset
 
  $i=1;
  while($eadet=mysql_fetch_assoc($ea)) 
  {
 ?>
 <tr>
  <td><?=$i?></td>
  <td style="text-align:left;"><?php $u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmployeeID=".$eadet['EmployeeID'],$con2); $un=mysql_fetch_assoc($u); echo $un['Fname'].' '.$un['Sname'].' '.$un['Lname']; ?></td>
  <td><a href="javascript:void(0)" onclick="showmonthdet('<?=$eadet['Month']?>','Open','<?=$eadet['EmployeeID']?>','Approved')"><?=date('F', mktime(0,0,0,$eadet['Month'], 1, date('Y')));?></a></td>
  <td>
  <?php if($eadet['Total_Claim']>0){ ?>	  
   <span class="btn btn-sm btn-outline-success font-weight-bold"><?=$eadet['Total_Claim']?></span> 
   <input type="hidden" id="sts<?=$eadet['EmployeeID']?><?=$eadet['Month']?>Approved" value="close">
  <?php } ?>
  </td> 
 </tr>
 <tr id="<?=$eadet['EmployeeID']?><?=$eadet['Month']?>Approved"></tr>
 <?php $i++; } //While ?>
 
 <?php /*
 <tr>
  <td align="center" colspan="10" style="font-family:Times New Roman;font-size:15px;font-weight:bold;">
   <?PHP doPages($offset, 'home.php', '', $total_records); ?></td>
 </tr>
 */ ?>
 
 </tbody>
</table>

  </div>
 </div>
</div>

<script type="text/javascript">
	function showtobeverified(month,sts,emp,csts){
		
		var modal = document.getElementById('myModal');
		modal.style.display = "block";
		document.getElementById('claimlistfr').src='showclaimslist.php?action=approve&month='+month+'&sts='+sts+'&emp='+emp+'&csts='+csts;
	}

	function showmonthdet(month,sts,emp,csts){ 

		var status=document.getElementById('sts'+emp+month+csts); //alert(month+"-"+sts+"-"+emp+"-"+csts+"-"+status.value);
		var modal = document.getElementById(emp+month+csts); 
		if(status.value=='close'){ 
			$.post("claimlistajax.php",{act:"monthdettoapprover",month:month,sts:sts,csts:csts,emp:emp},function(data){
				modal.innerHTML = data;
			});
			
			status.value="open";

		}else if(status.value=='open'){
			
			modal.innerHTML = '';
			status.value="close";
		}
	}

	// function showmonthdet(month,sts,emp,csts){
		
	// 	var modal = document.getElementById('myModal');
	// 	modal.style.display = "block";
	// 	document.getElementById('claimlistfr').src='showclaimslist.php?action=finance&month='+month+'&sts='+sts+'&emp='+emp+'&csts='+csts;
	// }
	function submittofinance(month,crby){

		if (confirm('Are you sure to Final Submit this month claims to Finance?')){
			$.post("homeajax.php",{"act":"submittofinance","month":month,"crby":crby},function(data){ //alert(data);
				console.log(data);
				if(data.includes('submitted')){
					alert('Submitted to Finance Successfully');
					// location.reload();
					window.location.href="home.php?show=appr";
				}

			});
		}
	}
	
	
	function submitFtoFreturn(month,crby){
		if (confirm('Are you sure to return this month claim from approver level?')){
			$.post("homeajax.php",{act:"submitFtoFreturn",month:month,crby:crby},function(data){ alert(data);
				if(data.includes('Returned')){
					alert('Returned to Verifier Successfully');
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
	function checkrange(thisamt,mainamt){
    
	    var t=parseInt(thisamt.value);
	    var m=parseInt(mainamt);
	    
	    /*
	    if(t>m){
	        $(thisamt).val(m);
	        alert("You can't provide more amount than claimed amount");
	    }
	    */
	    
	}
	function approveClaim(expid){

		var a=parseInt(document.getElementById(expid+'apprtamt').value);
		var r=document.getElementById(expid+'apprtremark').value;

		$.post("claimajax.php",{act:"approveClaim",expid:expid,atamt:a,apprtremark:r},function(data){
			
			if(data.includes('approved')){
				
				document.getElementById(expid+'Status').innerHTML='Approved'; 
				document.getElementById(expid+'btn').innerHTML='View'; 
				alert('Approved Successfully');
				document.getElementById('appraction').innerHTML=''; 

			}
			
		});
		
	}
	function showbtn(chk,expid){

		if (chk.checked) {
           $('#'+expid+'apprbtn').prop('disabled', false);
        }else{
        	$('#'+expid+'apprbtn').prop('disabled', true);
        }
		
	}
</script>



<?php /*
function check_integer($which) {
if(isset($_REQUEST[$which])){
if (intval($_REQUEST[$which])>0) {
return intval($_REQUEST[$which]);
} else {
return false;
}
}
return false;
}
function get_current_page() {
if(($var=check_integer('page'))) {
return $var;
} else {
//return 1, if it wasnt set before, page=1
return 1;
}
}
function doPages($page_size, $thepage, $query_string, $total=0) {
$index_limit = 10;
$query='';
if(strlen($query_string)>0){
$query = "&amp;".$query_string;
}
$current = get_current_page();
$total_pages=ceil($total/$page_size);
$start=max($current-intval($index_limit/2), 1);
$end=$start+$index_limit-1;
echo '<div class="paging">';
if($current==1) {
echo '<span class="prn">&lt; Previous</span>&nbsp;';
} else {
$i = $current-1;
echo '<a href="'.$thepage.'?page='.$i.$query.'&action='.$_REQUEST['action'].'&v='.$_REQUEST['v'].'&chkval='.$_REQUEST['chkval'].'&yi='.$_SESSION['FYearId'].'&ee=we23&er=1013&rr=wew101&show=appr" class="prn" rel="nofollow" title="go to page '.$i.'">&lt; Previous</a>&nbsp;';
echo '<span class="prn">...</span>&nbsp;';
}
if($start > 1) {
$i = 1;
echo '<a href="'.$thepage.'?page='.$i.$query.'&action='.$_REQUEST['action'].'&v='.$_REQUEST['v'].'&chkval='.$_REQUEST['chkval'].'&yi='.$_SESSION['FYearId'].'&ee=we23&er=1013&rr=wew101&show=appr" title="go to page '.$i.'">'.$i.'</a>&nbsp;';
}
for ($i = $start; $i <= $end && $i <= $total_pages; $i++){
if($i==$current) {
echo '<span>'.$i.'</span>&nbsp;';
} else {
echo '<a href="'.$thepage.'?page='.$i.$query.'&action='.$_REQUEST['action'].'&v='.$_REQUEST['v'].'&chkval='.$_REQUEST['chkval'].'&yi='.$_SESSION['FYearId'].'&ee=we23&er=1013&rr=wew101&show=appr" title="go to page '.$i.'">'.$i.'</a>&nbsp;';
}
}
if($total_pages > $end){
$i = $total_pages;
echo '<a href="'.$thepage.'?page='.$i.$query.'&action='.$_REQUEST['action'].'&v='.$_REQUEST['v'].'&chkval='.$_REQUEST['chkval'].'&yi='.$_SESSION['FYearId'].'&ee=we23&er=1013&rr=wew101&show=appr" title="go to page '.$i.'">'.$i.'</a>&nbsp;';
}
if($current < $total_pages) {
$i = $current+1;
echo '<span class="prn">...</span>&nbsp;';
echo '<a href="'.$thepage.'?page='.$i.$query.'&action='.$_REQUEST['action'].'&v='.$_REQUEST['v'].'&chkval='.$_REQUEST['chkval'].'&yi='.$_SESSION['FYearId'].'&ee=we23&er=1013&rr=wew101&show=appr" class="prn" rel="nofollow" title="go to page '.$i.'">Next &gt;</a>&nbsp;';
} else {
echo '<span class="prn">Next &gt;</span>&nbsp;';
}
if ($total != 0){
//prints the total result count just below the paging
echo '&nbsp;&nbsp;&nbsp;&nbsp;<font color="#ee4545"<h4>(Total '.$total.' Records)</h></div>';
}
}
*/
?>