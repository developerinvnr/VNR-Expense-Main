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
</script>
<div class="col-md-10 h-100" style="border-left:5px solid #d9d9d9;">

<!--<link href="dt_bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">-->
<link href="dt_css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
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
&nbsp;&nbsp;&nbsp;<a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
				
 <div class="row">		
 <?php if($_REQUEST['action']=='displayrec' && $_REQUEST['chkval']==2){ ?>		
  <div class="col-lg-11 shadow">
  <br><h5><small class="font-weight-bold text-muted"> Pending Claims by Financed</small> </h5> 
				
 <table class="estable table shadow">
  <thead class="thead-dark">
	<tr>
	  <th scope="col" style="width:10px;vertical-align:middle;">S.No</th>
	  <th scope="col" style="width:200px;vertical-align:middle;">Claimer</th>
	  <th scope="col" style="width:100px;vertical-align:middle;">Month</th>
	  <th scope="col" style="width:150px;vertical-align:middle;">Claims</th>
	  <th scope="col" style="width:150px;vertical-align:middle;">Action</th>
	</tr>
  </thead>				  
  <tbody>
<?php if($_REQUEST['v']=='' || $_REQUEST['v']==0){ $cond='1=1'; }else{ $cond='Month='.$_REQUEST['v']; }

  $sql_statement=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE YearId=".$_SESSION['FYearId']." and `Status`='Closed' and Total_Claim>0 and Approved_Amount>0 and Approved_Date!='0000-00-00' and (Finance_Amount=0 OR Finance_Date='0000-00-00') and ".$cond." order by Month asc, EmployeeID asc");			
					
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
}					
	  			
  $m=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE YearId=".$_SESSION['FYearId']." and `Status`='Closed' and Total_Claim>0 and Approved_Amount>0 and Approved_Date!='0000-00-00' and (Finance_Amount=0 OR Finance_Date='0000-00-00') and ".$cond." order by Month asc, EmployeeID asc LIMIT ".$from.",".$offset);
  $i=1; 
  while($mlist=mysql_fetch_assoc($m))
  {	

?>
<form action="" class="form-horizontal"  role="form">
   <tr>
	<td><?=$i?></td>
	<td style="text-align:left;"><?=getUser($mlist['EmployeeID'])?></td>
	<td><a href="#" onclick="showmonthdet('<?=$mlist['Month']?>','Open','<?=$mlist['EmployeeID']?>','Approved')">
		 <?=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y')));?></a></td>	
	<td><?php if($mlist['Total_Claim']>0){ ?>
	    <span class="btn btn-sm btn-outline-primary font-weight-bold">Total: <?=$mlist['Total_Claim']?></span>
		<input type="hidden" id="sts<?=$mlist['EmployeeID']?><?=$mlist['Month']?>Approved" value="close">
		<?php } ?>
	</td>
	<td>
	    
	 <?php 
	 $curr_fyear = mysql_query("select YearId from financialyear where Year = '".$_SESSION["FYear"]."'");
			$fyear = mysql_fetch_array($curr_fyear);
			$url = "https://vnrseeds.org/focus-data/upload/".$_SESSION['CompanyId']."/".$fyear['YearId']."/".$mlist['Month']."/".$mlist['EmployeeID']."/".$_SESSION['EmployeeID'];	

		
	 $name=getUser($mlist['EmployeeID']); $month=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y'))); ?>
	 <button type="button" class="btn btn-sm btn-warning" onclick="closeClaimMonth('<?=$mlist['Month']?>','<?=$mlist['EmployeeID']?>','<?=$name?>','<?=$month?>','<?= $url?>')">Approval All Claim</button>
	 
	
	</td>
   </tr>
   </form>						
   <tr id="<?=$mlist['EmployeeID']?><?=$mlist['Month']?>Approved"></tr>

<?php 
$i++; 
  } //while
?>


<tr>
 <td align="center" colspan="10" style="font-family:Times New Roman;font-size:15px;font-weight:bold;">
<?PHP doPages($offset, 'home.php', '', $total_records); ?></td>
</tr>

 </tbody>		  
</table>	
	</div>
<?php } // if($_REQUEST['action']=='displayrec')?>		
	
  </div>

  <br>
  <div class="row">
<?php if($_REQUEST['action']=='displayrec' && $_REQUEST['chkval']==1){ ?>		
	<div class="col-lg-11 shadow">
	<br><h5><small class="font-weight-bold text-muted"> Approved Claims by Financed</small> </h5> 			
	<table class="estable table shadow ">
	  <thead class="thead-dark">
		<tr>
		  <th scope="col" style="width:10px;">S.No</th>
		  <th scope="col" style="width:220px;">Claimer</th>
		  <th scope="col" style="width:80px;">Month</th>
		  <th scope="col" style="width:100px;">Total Claims</th>
		  <th scope="col" style="width:100px;">Total Payment Amount</th>
		  <th scope="col" style="width:100px;">Submited Date</th>
		</tr>
	  </thead>  
	  <tbody>
<?php if($_REQUEST['v']=='' || $_REQUEST['v']==0){ $cond='1=1'; }else{ $cond='Month='.$_REQUEST['v']; }
	 
	  $sql_statement=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE YearId=".$_SESSION['FYearId']." and `Status`='Closed' and Total_Claim>0 and Approved_Amount>0 and Approved_Date!='0000-00-00' and Finance_Amount>0 and Finance_Date!='0000-00-00' and ".$cond." order by Month asc, EmployeeID asc");			
					
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
}					
	  			
  $m=mysql_query("SELECT * FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE YearId=".$_SESSION['FYearId']." and `Status`='Closed' and Total_Claim>0 and Approved_Amount>0 and Approved_Date!='0000-00-00' and Finance_Amount>0 and Finance_Date!='0000-00-00' and ".$cond." order by Month asc, EmployeeID asc LIMIT ".$from.",".$offset);
  $i=1;
  while($mlist=mysql_fetch_assoc($m))
  {
?>
	<tr>
	 <td><?=$i?></td>
	 <td style="text-align:left;"><?=getUser($mlist['EmployeeID'])?></td>
	 <td><a href="#" onclick="showmonthdet('<?=$mlist['Month']?>','Closed','<?=$mlist['EmployeeID']?>','Closed')">
		 <?=date('F', mktime(0,0,0,$mlist['Month'], 1, date('Y')));?></a></td>
	 <td><?php if($mlist['Total_Claim']>0){ ?>
	    <span class="btn btn-sm btn-outline-primary font-weight-bold">Total: <?=$mlist['Total_Claim']?></span>
		<input type="hidden" id="sts<?=$mlist['EmployeeID']?><?=$mlist['Month']?>Closed" value="close">
		<?php } ?></td>							
	 <td><?php if($mlist['Finance_Amount']>0){ echo $mlist['Finance_Amount']; }?></td>
	 <td><?php if($mlist['Finance_Date']!='0000-00-00' AND $mlist['Finance_Date']!='1970-01-01'){ echo date("d-m-Y",strtotime($mlist['Finance_Date'])); }?></td>	
	</tr>
	<tr id="<?=$mlist['EmployeeID']?><?=$mlist['Month']?>Closed"></tr>
<?php
 $i++;
 } //while
?>


<tr>
 <td align="center" colspan="10" style="font-family:Times New Roman;font-size:15px;font-weight:bold;">
<?PHP doPages($offset, 'home.php', '', $total_records); ?></td>
</tr>					
					
					
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

	
	function closeClaimMonth(month,crby,username,monthname,focusurl){
		console.log(focusurl);
		if (confirm('Are you sure to Final Close '+username+'\'s '+monthname+' Claim\'s?')){
         
			$.post("homeajax.php",{act:"closeClaimMonth",month:month,crby:crby},function(data){
				console.log(data);
				if(data.includes('closed')){
				    fetch(focusurl)
					  .then(response => response.json())
					  .then(data => console.log(data));
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
	function financeClaim(t,expid){
       
		var f=parseInt(document.getElementById(expid+'financetamt').value);
		var r=document.getElementById(expid+'financetremark').value;
		
		
		if(t=='A'){var msg='Approve';}else if(t=='R'){var msg='Reject';}
		var agree=confirm("Are you sure, you want to "+msg+" claim?");
		
		if(agree)
		{ 

		$.post("claimajax.php",{act:"financeClaim",expid:expid,ftamt:f,financetremark:r,t:t},function(data){
			
			if(data.includes('financed')){
				document.getElementById(expid+'Status').innerHTML='Ok'; 
				document.getElementById(expid+'btn').innerHTML='View'; 
				alert('Approved Successfully');
				document.getElementById('financection'+expid).innerHTML=''; 
	
			}
			if(data.includes('Rejected')){
				document.getElementById(expid+'Status').innerHTML='Rejected'; 
				document.getElementById(expid+'btn').innerHTML='View'; 
				alert('Rejected Successfully');
				document.getElementById('financection'+expid).innerHTML=''; 
	
			}
			
			
		});
		
	    } //if(agree)	
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
	    
	    /*
	    if(t>m){
	        $(thisamt).val(m);
	        alert("You can't provide more amount than claimed amount");
	    }
	    */
	    
	}
</script>






<?php
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
echo '<a href="'.$thepage.'?page='.$i.$query.'&action='.$_REQUEST['action'].'&v='.$_REQUEST['v'].'&chkval='.$_REQUEST['chkval'].'&yi='.$_SESSION['FYearId'].'&ee=we23&er=1013&rr=wew101" class="prn" rel="nofollow" title="go to page '.$i.'">&lt; Previous</a>&nbsp;';
echo '<span class="prn">...</span>&nbsp;';
}
if($start > 1) {
$i = 1;
echo '<a href="'.$thepage.'?page='.$i.$query.'&action='.$_REQUEST['action'].'&v='.$_REQUEST['v'].'&chkval='.$_REQUEST['chkval'].'&yi='.$_SESSION['FYearId'].'&ee=we23&er=1013&rr=wew101" title="go to page '.$i.'">'.$i.'</a>&nbsp;';
}
for ($i = $start; $i <= $end && $i <= $total_pages; $i++){
if($i==$current) {
echo '<span>'.$i.'</span>&nbsp;';
} else {
echo '<a href="'.$thepage.'?page='.$i.$query.'&action='.$_REQUEST['action'].'&v='.$_REQUEST['v'].'&chkval='.$_REQUEST['chkval'].'&yi='.$_SESSION['FYearId'].'&ee=we23&er=1013&rr=wew101" title="go to page '.$i.'">'.$i.'</a>&nbsp;';
}
}
if($total_pages > $end){
$i = $total_pages;
echo '<a href="'.$thepage.'?page='.$i.$query.'&action='.$_REQUEST['action'].'&v='.$_REQUEST['v'].'&chkval='.$_REQUEST['chkval'].'&yi='.$_SESSION['FYearId'].'&ee=we23&er=1013&rr=wew101" title="go to page '.$i.'">'.$i.'</a>&nbsp;';
}
if($current < $total_pages) {
$i = $current+1;
echo '<span class="prn">...</span>&nbsp;';
echo '<a href="'.$thepage.'?page='.$i.$query.'&action='.$_REQUEST['action'].'&v='.$_REQUEST['v'].'&chkval='.$_REQUEST['chkval'].'&yi='.$_SESSION['FYearId'].'&ee=we23&er=1013&rr=wew101" class="prn" rel="nofollow" title="go to page '.$i.'">Next &gt;</a>&nbsp;';
} else {
echo '<span class="prn">Next &gt;</span>&nbsp;';
}
if ($total != 0){
//prints the total result count just below the paging
echo '&nbsp;&nbsp;&nbsp;&nbsp;<font color="#ee4545"<h4>(Total '.$total.' Records)</h></div>';
}
}
?>