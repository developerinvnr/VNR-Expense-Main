<?php 
session_start();
if(!isset($_SESSION['login'])){ session_destroy(); header('location:index.php'); }
include 'config.php'; 
?>
<link rel="stylesheet" href="css/jquery.datetimepicker.css">
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/style.css">

<?php 
$e=mysql_query("select * from y".$_SESSION['FYearId']."_expenseclaims where ExpId=".$_REQUEST['expid']); 
$exp=mysql_fetch_assoc($e);
$eu=mysql_query("select * from y".$_SESSION['FYearId']."_claimuploads cu, y".$_SESSION['FYearId']."_expenseclaims e where cu.ExpId=e.ExpId and (e.ExpId=".$_REQUEST['expid']." or e.AttachTo=".$_REQUEST['expid'].") order by UploadSequence asc");
$data = array(); $i=0; while($expup=mysql_fetch_assoc($eu)){ $data[$i] = $expup['FileName']; $i++; }
$Text = json_encode($data);
$RequestText = urlencode($Text); //here preparing the array, to send it by iframe url
?>

<?php $ss='<script>document.write(sw);</script>'; ?>
<div class="container-fluid">
 <div class="row">
  
  <div id="uplshdiv" class="col-md-6 h-100 previewdiv" style="padding:0px;">
	<div id="" style="position: relative;width:100%;padding:0px;">
	 <center>
	  <div id="preview" style="width:100%;height:400px;padding:0px;">
	  <?php echo '<input type="hidden" id="RequestText" name="RequestText" value="'.$RequestText.'" form="claimform" />';
			echo '<iframe src="imgPreBeforeUpdate.php?imglink='.$RequestText.'&inpage=showclaim&expid='.$_REQUEST['expid'].'" border="0" style="width:100%;height:400px;"/></iframe>'; ?>
	  </div>
	 </center>
	 <!--<form id="imageform" method="post" enctype="multipart/form-data" action='ajaximage.php'>
	  <div class="text-muted text-left">
	   <b>Step 1:</b> Upload the files<br>
	   <b>Step 2:</b> After upload click submit button to claim.<br><br>
	  </div>
	  <label class="btn btn-outline-primary font-weight-bold">
	   <input type="file"  id="NewFile" name="NewFile[]" multiple>Upload
	  </label>
	  <input type="hidden" id="uuid" name="uuid" value="<?php echo $_SESSION['EmployeeID']; ?>" />
	  <div class="text-muted">Upload jpg, png or pdf file only</div>
	  <input type="hidden" name="winheight" id="winheight" value="0">
	  <input type="hidden" id="prevRequestText" name="prevRequestText" value="" />
     </form>-->
	</div>
  </div>
   
  <div class="col-md-6 shadow">
   <center>
   <div class="table-responsive" style="padding-top:5px;">
   <table class="table table-sm claimtable" border="0" style="width:100%;">
	<thead class="thead-dark">
	 <tr>
	  <th scope="row" style="width:30px;vertical-align:middle;"><p class="h7 pull-left tht">Claim:</p></th>
	  <th style="vertical-align:middle;width:150px;">
	  <?php if($_SESSION['EmpRole']=='M' OR $_SESSION['EmpRole']=='V'){ ?>
	  <select id="claimtype" name="claimtype" class="claimheadsel form-control pull-left " onchange="showclaimforma(this.value)" style="width:100%;font-size:11px;" form="claimform" disabled>
	  <option value="">--Select--</option>
	  <?php 
	  $arr_exist = [19, 20, 21]; # this is your array
	  if(in_array($exp['ClaimId'], $arr_exist))
	  {  
	    $c2=mysql_query("select ct.ClaimId,ct.ClaimName,cg.cgCode,cg.cgName  from claimtype ct, y".$_SESSION['FYearId']."_empclaimassign ca, claimgroup cg  where ct.ClaimStatus='B' and ct.cgId=cg.cgId group by ct.ClaimId");
      	while($cl=mysql_fetch_assoc($c2)){ ?><option value="<?=$cl['ClaimId']?>" <?php if($exp['ClaimId']==$cl['ClaimId']){echo 'selected';}?>><?php echo $cl['ClaimName']?></option><?php } 
	  }
	  else{ $c=mysql_query("select ca.ClaimId,ct.ClaimName,cg.cgCode,cg.cgName  from claimtype ct, y".$_SESSION['FYearId']."_empclaimassign ca, claimgroup cg  where ca.ClaimId=ct.ClaimId and ct.ClaimStatus='A' and ct.cgId=cg.cgId group by ct.ClaimId");
      	while($cl=mysql_fetch_assoc($c)){ ?><option value="<?=$cl['ClaimId']?>" <?php if($exp['ClaimId']==$cl['ClaimId']){echo 'selected';}?>><?php if($cl['cgName']!=$cl['ClaimName']){echo $cl['cgCode'].'-';} echo $cl['ClaimName'];?></option>
		<?php } //while
	  } //else ?>
      </select>
	  <?php }else{ ?>
	  <div class="form-control font-weight-bold">
	  <?php $c=mysql_query("select ClaimId,ClaimName from claimtype where ClaimId=".$exp['ClaimId']);
			$cl=mysql_fetch_assoc($c); echo $cl['ClaimName'].'-'.$cl['ClaimId']; ?>
	  </div>
	  <script type="text/javascript">getlimit(<?=$exp['CrBy']?>,<?=$exp['ClaimId']?>);</script>
	  <?php } ?>	
      </th>
	  <th scope="row" style="width:80px; vertical-align:middle;"><p class="h7 pull-right tht">Year:</p></th>
	  <th style="vertical-align:middle;width:150px; text-align:center;">
		<div class="form-control font-weight-bold"><?php echo $_SESSION['FYear']; ?></div>	
	  </th>  
	  <th style="vertical-align:middle;width:50px;">
	  <?php $upbtndis='';	//echo $exp['ClaimStatus'];
	    if(($_SESSION['EmpRole']=='M' AND ($exp['ClaimStatus']=='Draft' OR $exp['ClaimStatus']=='Submitted' OR $exp['ClaimStatus']=='Filled')) OR ($_SESSION['EmpRole']=='V' AND ($exp['ClaimStatus']=='Filled' OR $exp['ClaimStatus']=='Verified')) OR ($_SESSION['EmpRole']=='A' AND $exp['ClaimStatus']=='Verified' AND $_SESSION['EmployeeID']!=$exp['CrBy']) OR ($_SESSION['EmpRole']=='F' AND $exp['ClaimStatus']=='Approved') OR $_SESSION['EmpRole']=='S')
		{ ?>
	    <button id="editbtn" class="btn btn-sm btn-warning pull-right" onclick="chngsel();edit();<?php if($_SESSION['EmpRole']=='M'){ ?>addfaredet();<?php } ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit </button>
	  <?php $upbtndis='';  
		}
	  ?>
      </th>
     </tr>
    </thead>			  
	<tbody id="claimformbody"></tbody>
   </table>
   </div>
   </center>
  <?php include 'multipleremark.php';?>		
  </div>		
 </div>
	
</div>

<input id="claimstatus<?=$exp['ExpId']?>" value="<?=$exp['ClaimStatus']?>" type="hidden">
<input type="hidden" id="cid" value="<?=$exp['ClaimId']?>">

<script
        src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
        
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>
<script src="js/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript">

function chngsel(){
	 $('#claimtype').attr('disabled', false);
	  $('#Update').attr('disabled',false);
    $('#draft2').attr('disabled',false);
}


function vehTypeSel(th,di,slb,ei,y1,y2)
{ 
	//alert(th+","+di+","+slb+","+ei+","+y1+","+y2);
	var vehtype=th; 
	if(vehtype==2)
	{ $("#tpkm").show(); $("#fpkm").hide(); var rate=$("#tpkm").val(); 
	  if(di!=2)
	  {
	   if(rate=='' || rate=='0' || rate==0){ $("#tpkm").val(3); document.getElementById("tpkm").readonly=true; }
	  }
	}
	else if(vehtype==4){ $("#fpkm").show(); $("#tpkm").hide(); var rate=$("#fpkm").val();  }
	
	if(slb>0)
	{
	
	 var bd = $("#BillDate1").val();
	 $.post("mappingslabajax.php",{act:"CheckKM",slb:slb,ei:ei,bd:bd,th:th,y1:y1,y2:y2,rate:rate},function(data){ //if(ei==34){ alert(data); }
	 
	 //FUnPut(data,vehtype);
	 
	 $('#SpanRateBody').html(data); 
	 if(vehtype==2){ $("#tpkm").val(data); }
	 else if(vehtype==4){ $("#fpkm").val(data); } 
	
     });
	 
	}
	else
	{
	
	 if(vehtype==2){ $("#tpkm").show(); $("#fpkm").hide(); }
	 else if(vehtype==4){ $("#tpkm").hide(); $("#fpkm").show(); }
	 
	}
	
	
}

function FUnPut(v,vt)
{
  if(vt==2){ $("#tpkm").val(v); }
  else if(vt==4){ $("#fpkm").val(v); }    
}


function caldist(){
	

	var opening=parseInt($("#DistTraOpen").val() || 0);
	var closing=parseInt($("#DistTraClose").val() || 0);

	var dist= closing-opening;
	$("#totalkm").val(dist);


	var tChecked = $('#vehicleType2').prop('checked');
	var fChecked = $('#vehicleType4').prop('checked');

	if(tChecked){
		$("#FilledTAmt").val(dist * $("#tpkm").val());
	}else if(fChecked){
		$("#FilledTAmt").val(dist * $("#fpkm").val());
	}
	
	
	CalTott();
    
	/***********************/
    function CalTott()
    {
     var Ttotalkm=parseFloat($("#Ttotalkm").val());
     var TFilledTAmt=parseFloat($("#TFilledTAmt").val());
     var Tkm=parseFloat($("#totalkm").val());
     var TAmt=parseFloat($("#FilledTAmt").val());
	 //alert(Tkm+"-"+TAmt);
     if(Tkm>0){ $("#Ttotalkm").val(Ttotalkm+Tkm); $("#T2totalkm").val(Ttotalkm+Tkm); }
     if(TAmt>0){ $("#TFilledTAmt").val(TFilledTAmt+TAmt); $("#T2FilledTAmt").val(TFilledTAmt+TAmt); }
    }
	/***********************/

}

function showclaimforma(cid){ 
	var expid=parseInt('<?=$_REQUEST['expid']?>'); 
	var empid=parseInt('<?=$exp['CrBy']?>'); 
	// var cid=parseInt('<?=$exp['ClaimId']?>'); 
	$('#cid').val(cid);
	var upbtndis='<?=$upbtndis?>'; 
	$.post("showclaimform.php",{act:"showclaimform",claimid:cid,expid:expid,upbtndis:upbtndis,sessionid:"<?=$_SESSION['EmployeeID']?>",emprole:"<?=$_SESSION['EmpRole']?>"},function(data){

		$('#claimformbody').html(data);
		edit();
		addfaredet();
		getlimit(empid,cid);
		chngsel();
	});

}
$(document).ready(function(){
//alert("aa");

	var expid=parseInt('<?=$_REQUEST['expid']?>');
	var empid=parseInt('<?=$exp['CrBy']?>');  
	var cid=parseInt('<?=$exp['ClaimId']?>'); 
	var upbtndis='<?=$upbtndis?>'; 
	var csts=$('#claimstatus'+expid).val(); 


   if(cid==19 || cid==20 || cid==21){
       
    $('#Update').attr('disabled',true);
    $('.draft2').attr('disabled',true);
    
       $.post("showclaimform.php",{act:"showclaimform",claimid:cid,expid:expid,upbtndis:upbtndis},function(data){
			$('#claimformbody').html(data);

		});
   }else{

   		//if(csts=='Filled' || csts=='Draft'){
		$.post("showclaimform.php",{act:"showclaimform",claimid:cid,expid:expid,upbtndis:upbtndis},function(data){
			$('#claimformbody').html(data);
			/*Extra*/
		    addfaredet();
		    getlimit(empid,cid);
		    chngsel();
            /*Extra*/
		});
	//}
   }
	
	var sw=parseInt(screen.width);
	if(sw<700){
		$('#uplshdiv').removeClass('h-100');
		$('#uplshdiv').addClass('h-50');
	}
	
});

function verifyClaim(expid){
	$.post("claimajax.php",{act:"verifyClaim",expid:expid},function(data){
		
		if(data.includes('verified')){
			window.parent.document.getElementById('<?=$exp['ExpId']?>'+'Status').innerHTML='Verified'; 
			window.parent.document.getElementById('<?=$exp['ExpId']?>'+'btn').innerHTML='View'; 
			alert('Verified Successfully');
			location.reload();
		}
		
	});
}

function approveClaim(expid){

	$.post("claimajax.php",{act:"approveClaim",expid:expid},function(data){
		
		if(data.includes('approved')){
			window.parent.document.getElementById('<?=$exp['ExpId']?>'+'Status').innerHTML='Approved'; 
			window.parent.document.getElementById('<?=$exp['ExpId']?>'+'btn').innerHTML='View'; 
			alert('Approved Successfully');
			window.location.href = 'showclaim.php?expid=<?=$_REQUEST['expid']?>';
		}
	});
	
}
function financeClaim(expid){
	$.post("claimajax.php",{act:"financeClaim",expid:expid},function(data){
		
		if(data.includes('financed')){
			window.parent.document.getElementById('<?=$exp['ExpId']?>'+'Status').innerHTML='Financed'; 
			window.parent.document.getElementById('<?=$exp['ExpId']?>'+'btn').innerHTML='View'; 
			alert('Financed Successfully');
			location.reload();	
		}
		
	});
}


function checkrange(thisamt,mainamt){
    
    var t=parseInt(thisamt.value);
    var m=parseInt(mainamt);
    if(t>m){
        $(thisamt).val(m);
        alert("You can't provide more amount than claimed amount");
        return false;
    }
    
}


function calfatotal(){
    
    var c=parseInt($('#fdtcount').val());
    var amt=0;
    for(var i=1;i<=c;i++){
        amt+=parseInt($('#fdFinanceEditAmount'+i).val() || 0);
    }
    

    var limit=$('#limitAmount').val();

    if(amt<=limit || limit=='' || limit==0){
        $('#FinanceEditAmount').val(amt);
    }else{
        $(thi).val('');
        alert('You cant assign more than limit amount');
        return false;
    }
}




function getlimit(empid,cid){ 
	$.post("claimajax.php",{act:"getlimit",empid:empid,cid:cid},function(data){
		
		setTimeout(function(){ $("#limitspan").html(data); },500);
		
	});
}
</script>