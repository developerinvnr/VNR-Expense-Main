<?php session_start();

include "header.php";

?><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
.form-control_manual { font-size: 12px;padding: 2px 1px !important;height: calc(1.6rem + 2px) !important; margin: 2px !important; }
</style>
</head>


<div class="container-fluid">
 <div class="row h-100">

  <div class="col-md-3"></div>

  <div class="col-md-6 shadow">
 &nbsp;
  <br>
  
  <a class="btn btn-sm btn-primary" href="home.php">&nbsp;&nbsp;<i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;Back&nbsp;&nbsp;</a>
  <a class="btn btn-sm btn-primary" href="javascript:location.reload(true)"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh</a>
 
  
  <center>
   
      



   <div class="table-responsive" style="padding-top:8px;" id="automateddiv">
    <table class="table table-sm claimtable" border="0" style="padding-top:0px;">
	
    <thead class="thead-dark">
<?php $m=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and YearId=".$_SESSION['FYearId']." and `Status`='Open' limit 1"); 
if(mysql_num_rows($m)>0){$ms=mysql_fetch_assoc($m); $_SESSION['todayMonth']=date("m",strtotime('2019-'.$ms['Month'].'-01'));}else{ $_SESSION['todayMonth']=4; } ?>
    </thead>

    <tbody id="claimformbody" style="width:100%;">
	<tr>
	 <td style=" text-align:center;width:100%;" colspan="15">
	   <div id="" style="position: relative; width:100%;">
		 <center>
		  <button id="removeupload" class="btn btn-danger" onclick="showuploadbtn()" style="position: absolute;float: right;right: 0px;display: none;"><i class="fa fa-times-circle-o fa-2x" aria-hidden="true"></i></button>
		  <span id="loadinganim" style="display: none;">
		  <br>
		  <img src="images/loader.gif">
		  <br>
		  <br>
		  </span>
			<span id="uploadform"><br />
			 <div>
			     
			    <?php if($_SESSION['FYearId']>=3){ ?>
			    
				<form id="imageform" method="post" enctype="multipart/form-data" action='ajaximage.php'>
					<div class="text-muted text-left" >
				  <b>Step 1:</b> Upload the files<br>
				  <b>Step 2:</b> After upload click submit button to claim.<br><br>
				  </div>
				
				<label class="btn btn-outline-primary font-weight-bold">
				 <input type="file"  id="NewFile" name="NewFile[]" multiple >
				 Upload To Odometer Reading
				</label>
				 <input type="hidden" id="uuid" name="uuid" value="<?php echo $_SESSION['EmployeeID']; ?>" />
				 <div class="text-muted">
				  Upload jpg, png or pdf file only
				  </div>
				  <input type="hidden" name="winheight" id="winheight" value="0">
				  <input type="hidden" id="prevRequestText" name="prevRequestText" value="" />
				 </form>
				 
				 <?php } ?>
				 
			 </div>
			</span>
			<span id="preview" style="width:100%;"></span>	    
		  </center>
		 </div>
	 </td>
	</tr>
    <tr>
	 <td colspan="10">
	 <form id="claimform" action="saveclaim.php" method="post" enctype="multipart/form-data">
	  <button class="btn btn-sm  form-control" id="submit" name="submitclaim" required disabled style="background-color:#c9e8d0 !important;color:#ffffff;height:30px;">Submit</button>
	 </form>
	 </td>
    </tr>
    </tbody>
    </table>
   </div>
   
<!-- For Desktop For Desktop ------------------------------>   
<!-- For Desktop For Desktop ------------------------------>   

			<div id="topCurMonUpl" class="table-responsive" style="padding-top:0px;">
				<table class="table shadow">
				  <thead class="thead-dark">
				  	<tr>
						
				 <th scope="row" colspan="10"><p class="h7  tht">Last 5 Claims:</p></th>	
				</tr>
				
				<tr>
<th scope="col" style="width:30px;background-color:#008C8C;"><font style="font-size:11px;">Sn</font></th>
<th scope="col" style="width:100px;background-color:#008C8C;"><font style="font-size:11px;">Claim<br />Type</font></th>
<th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Bill<br />Date</font></th>
<th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Applied<br />Date</font></th>


	    </tr>
	  </thead>
	  <tbody>	
	  	
<?php $stepcond="1=1";
	  $q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims`e, claimtype c, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and  e.CrBy=".$_SESSION['EmployeeID']." and (c.ClaimId=e.ClaimId or e.ClaimId=0) and ".$stepcond." and e.ClaimStatus!='Deactivate' group by e.ExpId order by e.ExpId desc limit 0,5"; $seleq=mysql_query($q);

      $i=1; while($exp=mysql_fetch_assoc($seleq)){ ?>
	      <tr onclick="showdet('<?=$exp['EmployeeID']?>')" >
	       <th scope="row"><font style="font-size:11px;"><?=$i?></font>
	       </th>
	       
	       <td><a href="#" onclick="showexpdet('<?=$exp['ExpId']?>')"><font style="font-size:11px;"><?php if($exp['ClaimId']!=0){echo substr($exp['ClaimName'], 0, 12).'..';}?></font></a></td>
	       
	       <td><a href="#" onclick="showexpdet('<?=$exp['ExpId']?>')"><font style="font-size:11px;"><?php if($exp['BillDate']!='0000-00-00' && $exp['BillDate']!='1970-01-01'){ echo date("d/m/y",strtotime($exp['BillDate'])); } ?></font></a></td>
	       
	       <td><a href="#" onclick="showexpdet('<?=$exp['ExpId']?>')"><font style="font-size:11px;"><?=date("d/m/y",strtotime($exp['CrDate']))?></font></a></td>
		   
	     
	    </tr>
	    <?php $i++; } ?>
					
					
				   
				  </tbody>
				</table>
			</div>	

			</center>
			
		</div>
		<div class="col-md-6 lg-h-100 xs-h-75 previewdiv">
			
			
			
		</div>

<!-- For Mobile For Mobile ------------------------------>   
<!-- For Mobile For Mobile ------------------------------>  
		
		<div id="botCurMonUpl" class="col-md-12 lg-h-100 xs-h-75table-responsive" style="display: none; padding-top:0px;">
			<br>
			<table class="table shadow">
			  <thead class="thead-dark">
			  	<tr>
				  
				 <th scope="row" colspan="10"><p class="h7  tht">Last 5 Claims:</p></th>	
				</tr>
				
				<tr>
<th scope="col" style="width:30px;background-color:#008C8C;"><font style="font-size:11px;">Sn</font></th>
<th scope="col" style="width:100px;background-color:#008C8C;"><font style="font-size:11px;">Claim<br />Type</font></th>
<th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Bill<br />Date</font></th>
<th scope="col" style="width:50px;background-color:#008C8C;"><font style="font-size:11px;">Applied<br />Date</font></th>



	    </tr>
	  </thead>
	  <tbody>	
	  	
<?php $stepcond="1=1";
	  $q="SELECT e.*, c.ClaimName, h.Fname,h.Sname,h.Lname FROM `y".$_SESSION['FYearId']."_expenseclaims`e, claimtype c, ".dbemp.".hrm_employee h where h.EmployeeID=e.CrBy and  e.CrBy=".$_SESSION['EmployeeID']." and (c.ClaimId=e.ClaimId or e.ClaimId=0) and ".$stepcond." and e.ClaimStatus!='Deactivate' group by e.ExpId order by e.ExpId desc limit 0,5"; $seleq=mysql_query($q);
      $i=1; while($exp=mysql_fetch_assoc($seleq)){ ?>
	      <tr onclick="showdet('<?=$exp['EmployeeID']?>')" >
	       <th scope="row"><font style="font-size:11px;"><?=$i?></font></th>
	       
	       <td><a href="#" onclick="showexpdet('<?=$exp['ExpId']?>')"><font style="font-size:11px;"><?php if($exp['ClaimId']!=0){echo substr($exp['ClaimName'], 0, 12).'..';}?></font></a></td>
	       
	       <td><a href="#" onclick="showexpdet('<?=$exp['ExpId']?>')"><font style="font-size:11px;"><?php if($exp['BillDate']!='0000-00-00' && $exp['BillDate']!='1970-01-01'){ echo date("d/m/y",strtotime($exp['BillDate'])); } ?></font></a></td>
	       
	       <td><a href="#" onclick="showexpdet('<?=$exp['ExpId']?>')"><font style="font-size:11px;"><?=date("d/m/y",strtotime($exp['CrDate']))?></font></a>
	       
	       
	       </td>



					   
					  </font></td>

			
			
	      <!-- <td><?php //$c='outline-secondary'; //$s=$exp['ClaimStatus'];
			if($exp['ClaimStatus']=='Submitted'){$s='Pending'; $clss='btn btn-sm btn-outline-warning font-weight-bold';}
			elseif($exp['ClaimStatus']=='Filled'){$s='Filled'; $clss='btn btn-sm btn-outline-success font-weight-bold';} ?>
	      	<div class="<?=$clss?>"><font style="font-size:10px;"><?=$s?></font></div>
	       
			<span id="okspanarea<?=$exp['ExpId']?>">
			 <?php if($exp['ClaimStatus']=="Filled" && $exp['FilledOkay']==0){ ?>
			 <input type="checkbox" onclick="okcheck(this,'<?=$exp['ExpId']?>')">
			 <button id="okbtn<?=$exp['ExpId']?>" class="btn btn-sm btn-success" onclick="expfillok('<?=$exp['ExpId']?>')" disabled><font style="font-size:10px;">Ok</font></button><?php }elseif($exp['ClaimStatus']=="Filled" && $exp['FilledOkay']==1){ ?><div class="btn btn-sm btn-success"><font style="font-size:10px;">Okay</font></div> <?php } ?>
			</span> 
			       	
	      </td> -->
	      <?php /*if(!isset($_REQUEST['csts']) || $_REQUEST['csts']!='Filled'){?>
	      <td class=""><button class="btn btn-sm btn-primary" onclick="showexpdet('<?=$exp['ExpId']?>')">view</button></td>
		  <?php //} */ ?>
	    </tr>
	    <?php $i++; } ?>
		
		
				
				
				
			     </tbody>
			</table>
		</div>	
		
	</div>
	
</div>




<?php
include "footer.php";
?>


<?php
 
$year_arr = explode ("-", $_SESSION['FYear']); 
$earliest_year = $year_arr[0]; 
$latest_year = $year_arr[1]; 


$sm=mysql_query("SELECT `Month` FROM `y".$_SESSION['FYearId']."_monthexpensefinal` WHERE `EmployeeID`='".$_SESSION['EmployeeID']."' and `YearId`=".$_SESSION['FYearId']." and Status='Open' order by Month desc limit 1");


if(mysql_num_rows($sm)>0){
	$lm=mysql_fetch_assoc($sm);
	$lastmonth=$lm['Month'];
}else{$lastmonth=4;}

if($lastmonth>=4 && $lastmonth<=12){
	$yeartoshow=$earliest_year;
}elseif($lastmonth>=1 && $lastmonth<=3){
	$yeartoshow=$latest_year;
}

?>

<script type="text/javascript" src="js/claim.js"></script>

<script type="text/javascript">


function claimradio(a){
  showclaimform(a);
	if(a==1){  showclaimform(8);
	
	}else if(a==2){
		showclaimform(7);
	}
	
}

function addfaredetaa(){
    
   var c=parseInt($('#fdtcount').val());
    c++;
    $('#fdtcount').val(c);

    var aa='<tr> <td><input class="form-control" name="fdtitle'+c+'" style=""></td> <td> <input class="form-control text-right" id="fdamount'+c+'" name="fdamount'+c+'" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td>  <td  style="width: 20px;"> <button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)"> <i class="fa fa-times fa-sm" aria-hidden="true"></i> </button> </td> </tr>'; 

    $('#faredettbody').append(aa);     

}


$(document).ready(function(){
	
     $('#claimdate').datetimepicker({format:'d-m-Y'});


    $("#automateddiv").css("display","block");
	$("#manualdiv").css("display","none");

    $('#Date').datetimepicker({
        format:'d-m-Y',
        defaultDate:'01-<?=$lastmonth?>-<?=$yeartoshow?>',
        minDate: '<?=$yeartoshow?>-<?=$lastmonth?>-01',
        maxDate: '<?=$yeartoshow?>-<?=$lastmonth?>-31',
    });
    // $('#Date').datetimepicker("setDate", '2016-01-01');

    $('#Date').on('change', function(){
        $(this).datetimepicker('hide');
        var str = $(this).val();
        var res = str.split("-");
         $('#claimMonth').val(res[1]);

    }); //here closing the Date datetimepicker on date chang


    var sh=window.screen.availHeight;
	var sw=window.screen.availWidth;

	if(sh>sw){
		$('#topCurMonUpl').hide();
		$('#botCurMonUpl').show();
		$('#winheight').val(240);
	}else{
		$('#topCurMonUpl').show();
		$('#botCurMonUpl').hide();
		$('#winheight').val(98);
	}


	
	$('#manual_claim_form').submit(function(e){

        e.preventDefault(); 

            $.ajax({
            url: 'post_ajax.php',	
            type:'post',
            data: $('#manual_claim_form').serialize(),
            dataType: 'json',
            success:function(data){ 
               if(data.status=='success'){	
                   alert(data.msg);
                 location.reload();
            }else if(data.status=='dateissue'){	
                   alert(data.msg);
                 //location.reload();
			}else if(data.status=='monthissue'){	
                   alert(data.msg);
                 //location.reload();
			} else{
            	  alert(data.msg);
            }    
       }
	}); 


});





});


$('#manual').click(function(){
	$("#automateddiv").css("display", "none");
	$("#manualdiv").css("display","block")
});

$('#automated').click(function(){
	$("#automateddiv").css("display","block");
	$("#manualdiv").css("display","none");
});


$(window).resize(function() {
    var sh=window.screen.availHeight;
	var sw=window.screen.availWidth;

	if(sh>sw){
		$('#topCurMonUpl').hide();
		$('#botCurMonUpl').show();
		$('#winheight').val(245);
	}else{
		$('#topCurMonUpl').show();
		$('#botCurMonUpl').hide();
		$('#winheight').val(98);
	}
}).resize()

function showexpdet(expid){
		
		var modal = document.getElementById('myModal');
		modal.style.display = "block";
		document.getElementById('claimlistfr').src="showclaim.php?expid="+expid;
	}
</script>

<!-- from here the style, div and script all are for displaying modal on page view click -->

<style>


/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  position: relative;
  background-color: #fefefe;
  margin: auto;
  padding: 0;
  border: 1px solid #888;
  width: 90%;
  top:-70px;
  height: 110%;
  box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
  -webkit-animation-name: animatetop;
  -webkit-animation-duration: 0.4s;
  animation-name: animatetop;
  animation-duration: 0.4s
}

/* Add Animation */
@-webkit-keyframes animatetop {
  from {top:-300px; opacity:0} 
  to {top:-70px; opacity:1}
}

@keyframes animatetop {
  from {top:-300px; opacity:0}
  to {top:-70px; opacity:1}
}

/* The Close Button */
.close {
  position: absolute;
  top:0px;
  color: #000;
  right: 4px;
  font-size: 28px;
  font-weight: bold;
  display: block;
  cursor: pointer;

}

/*.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}*/

.modal-header {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}

.modal-body {padding: 2px 16px;}

.modal-footer {
  padding: 2px 16px;
  background-color: #5cb85c;
  color: white;
}

.lbl{cursor: pointer;}
</style>


<div id="detailbox" style="display:none;position: absolute;height: 98%;width: 98%;">
  <iframe id="detailfr" src="" style="width:100%;height: 100%;"></iframe>
</div>



<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    
      <span class="close pull-right" >&times;</span><br>
      
    <div class="modal-body d-flex justify-content-center align-items-center">
    	
	    <div style="position: absolute;margin:0 auto;height: 98%;width: 98%;">
			<iframe id="claimlistfr" src="" style="width:100%;height: 100%;"></iframe>
		</div>
      
      
      
      
    </div>
    
  </div>

</div>

<script>
// Get the modal
var modal = document.getElementById('myModal');

var span = document.getElementsByClassName("close")[0];

span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}





</script>