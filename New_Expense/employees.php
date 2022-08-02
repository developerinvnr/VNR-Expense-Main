<?php include "header.php"; ?>

<div class="container">
	<div class="row shadow">
		<div class="col-md-8">
			<br>
			
			<div class="table-responsive">
				<table class="table shadow">
				  <thead class="thead-dark">
				    <tr>
				      <th scope="col" style="width:20px;text-align:center;">S.No</th>
				      
				      <th scope="col" style="width:100px;text-align:center;">Emp Code<br>
				      	<input type="text" id="empsearcode" onkeyup="showEmployeeList()" placeholder="Search..." class="form-control"></th>
				      <th scope="col" style="width:150px;text-align:center;">Name<br>
				      	<input type="text" id="empsearname" onkeyup="showEmployeeList()" placeholder="Search..." class="form-control"></th>


				      <?php
				      $claimseq= array();
				      $selc=mysql_query("SELECT * FROM `claimtype`");
				      while($selcd=mysql_fetch_assoc($selc)){
				      	array_push($claimseq,$selcd['ClaimCode']);
				      ?>
					  <th scope="col" style="width:50px;text-align:center;"><?=$selcd['ClaimCode']?></th>
				      <?php
				      }
				      ?>
				      <th></th>
				    </tr>
				  </thead>
				  <tbody id="Employeelisttbl">
				  	
				  </tbody>
				</table>
				
				<button id="<?=$pages?>btn" value="<?=$pages?>" onclick="prevpage()">Previous</button>

				<?php
				$seluq=mysql_query("SELECT * FROM ".dbemp.".`hrm_employee` where EmpType='E' AND EmpStatus='A'");
				$total=mysql_num_rows($seluq);
				$range=15;
				$pages=$total/$range;
				$pages=(int)$pages;
				$btn=1;
				while($btn<=$pages){
					?>
					<button id="<?=$btn?>btn" value="<?=$btn?>" onclick="pagechange(<?=$btn?>)"><?=$btn?></button>
					<?php
					$btn++;
					if($btn==4 && $pages>4){ break;}
				}
				
				?>
				<span id="btnspan">...............</span>
				<?php
				
				?>
				<button id="<?=$pages?>btn" value="<?=$pages?>" onclick="pagechange(<?=$pages?>)"><?=$pages?></button>
				<button id="<?=$pages?>btn" value="<?=$pages?>" onclick="nextpage()">Next</button>

				<input type="hidden" id="totalbtns" value="<?=$pages?>">
			</div>
			
		</div>
		<div class="col-md-4" id="udetsdiv">
			
		</div>
		
	</div>
	
</div>


<?php

function getemployee($u){
	$u=mysql_query("SELECT Fname,Sname,Lname FROM `hrm_employee` where EmpStatus='A' AND EmployeeID=".$u);
	$un=mysql_fetch_assoc($u);
	return $un['Fname'].' '.$un['Sname'].' '.$un['Lname'];
}


$claimsarr=json_encode($claimseq);

?>

<?php
include "footer.php";
?>

<input type="hidden" id="range" value="15">
<input type="hidden" id="page" value="1">
<style type="text/css">
	.actpgbtn{
		padding: .120rem .50rem !important;
		border-radius: 1px !important;
		margin-top: -4px !important;
	}
	.elist{
		
	}
	.elist:hover{
		background-color: #e6e6e6;
	}
	.table thead th, .table tbody th, .table tbody td {
	    padding: 1px 7px;
	}
</style>
<script type="text/javascript">

	$( document ).ready(function() {
	    pagechange(1);
	});


	function prevpage(){
		var p=parseInt($("#page").val());
		var totalbtns=parseInt($("#totalbtns").val());
		if(p!=1){p=p-1;}
		pagechange(p);

		var midbtn='....<button id="'+p+'btn" value="'+p+'" class="btn btn-primary actpgbtn" onclick="pagechange('+p+')">'+p+'</button>....';

		if(p!=1 && p!=2 && p!=3 && p!=totalbtns){
			$("#btnspan").html(midbtn);
		}else{$("#btnspan").html('...............');}

	}
	function nextpage(){
		var p=parseInt($("#page").val());
		var totalbtns=parseInt($("#totalbtns").val());
		if(p!=totalbtns){p=p+1;}
		pagechange(p);

		var midbtn='....<button id="'+p+'btn" value="'+p+'" class="btn btn-primary actpgbtn" onclick="pagechange('+p+')">'+p+'</button>....';
		if(p!=1 && p!=2 && p!=3 && p!=totalbtns){
			$("#btnspan").html(midbtn);
		}else{$("#btnspan").html('...............');}

	}

	function pagechange(page){
		var totalbtns=parseInt($("#totalbtns").val());
		
		for(var a=1;a<=totalbtns;a++){
			if(a!=page){
				$("#"+a+"btn").attr('class','');	
				// $("#"+a+"btn").addClass('btn');
			}else if(a==page){
				$("#page").val(page);
				$("#"+page+"btn").addClass('btn btn-primary actpgbtn');
			}
		}
		showEmployeeList();
	}


	function showEmployeeList(){
		var r=parseInt($("#range").val());
		var p=parseInt($("#page").val());
		var c=$("#empsearcode").val();
		var n=$("#empsearname").val();
		var aa='<?=$claimsarr?>';

		//alert(aa);

		$.post("uajax.php",{act:"employeelistshow",range:r,page:p,name:n,code:c,claimseq:aa},function(data){
			 $("#Employeelisttbl").html(data);
		});
	}


	function showempdet(eid,sno){
		$.post("uajax.php",{act:"getempdet",eid:eid},function(data){
			var loading='<br><br><br><center><div class="spinner-border" role="status"> <span class="sr-only">Loading...</span> </div></center><br><br>';
			$("#udetsdiv").html(loading);
			
			setTimeout(function(){ $("#udetsdiv").html(data); },300);


			var r=parseInt($("#range").val());

			for(var a=1;a<=r;a++){
				if(a!=sno){
					$("#"+a+"tr").css('background-color','');
				}else if(a==sno){
					$("#"+a+"tr").css('background-color','#e6e6e6');
				}
			}
		});
	}


	function editedets(eid){
		// alert(eid);
		$("#Fnameinp").prop("readonly",false);
		$("#Snameinp").prop("readonly",false);
		$("#Lnameinp").prop("readonly",false);
		$("#EmpCodeinp").prop("readonly",false);
		$("#EmpRoleinp").prop("disabled",false);
		$("#EmpStatusinp").prop("disabled",false);
		$("#EmailIdinp").prop("readonly",false);
		$("#MobileNoinp").prop("readonly",false);
		$("#editbtn").css("display","none");
		$("#savebtn").css("display","block");

	}
	function saveedets(eid){
		// alert(eid);
		$("#Fnameinp").prop("readonly",true);
		$("#Snameinp").prop("readonly",true);
		$("#Lnameinp").prop("readonly",true);
		$("#EmpCodeinp").prop("readonly",true);
		$("#EmpRoleinp").prop("disabled",true);
		$("#EmpStatusinp").prop("disabled",true);
		$("#EmailIdinp").prop("readonly",true);
		$("#MobileNoinp").prop("readonly",true);
		$("#savebtn").css("display","none");
		var loading='Saving...<div class="spinner-border spinner-border-sm" role="status"> <span class="sr-only">Loading...</span> </div>'; 
		$("#editsts").html(loading);

		var arr=["Fnameinp","Snameinp","Lnameinp","EmpCodeinp","EmpRoleinp","EmpStatusinp","EmailIdinp","MobileNoinp"];
	 
		var inparr={};

		for(var i=0;i<arr.length;i++){
			var v=document.getElementById(arr[i]).value;
			var av=arr[i];
			inparr[av]=v;
			document.getElementById(arr[i]).readOnly = true; 
		}
		// console.log(inparr);
		
		$.post("uajax.php",{act:"saveudets",eid:eid,inputs:inparr},function(data){
			
			if(data.includes('success')){
				setTimeout(function(){ 
					$("#editsts").html('<button class="btn btn-success btn-sm font-weight-bold">Saved</button> ');
					setTimeout(function(){ $("#editsts").html(''); $("#editbtn").css("display","block"); },2000);
				},1000);
			}else{
				$("#editsts").html('Error...');
				setTimeout(function(){ 
					$("#editsts").html('');
					$("#editbtn").css("display","block");
				},2000);
			}

		});
	}

	function assigncl(t,clid,eid){
		if($(t).prop("checked") == true){
			$.post("uajax.php",{act:"eassigncl",clid:clid,eid:eid},function(data){
				if(data.includes('success')){
					$("#asgtd"+clid).prop("style","background-color:green !important;");
					
				}
			});

		}else if($(t).prop("checked") == false){

			$.post("uajax.php",{act:"remeassigncl",clid:clid,eid:eid},function(data){
				if(data.includes('deleted')){
					$("#asgtd"+clid).prop("style","background-color:;");
				}
			});
		}
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

