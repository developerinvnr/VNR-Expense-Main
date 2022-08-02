

function showdet(eid){
	$.post("uajax.php",{act:"getudet",eid:eid},function(data){
		var loading='<br><br><br><center><div class="spinner-border" role="status"> <span class="sr-only">Loading...</span> </div></center><br><br>';
		$("#udetsdiv").html(loading);
		setTimeout(function(){ $("#udetsdiv").html(data); },300);
		
	});
}

function editudets(eid){
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

function saveudets(eid){
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

function savenewuser(){

	
	$("#savebtn").css("display","none");
	var loading='Saving...<div class="spinner-border spinner-border-sm" role="status"> <span class="sr-only">Loading...</span> </div>'; 
	$("#editsts").html(loading);

	

	var arr=["Fnameinp","Snameinp","Lnameinp","EmpCodeinp","EmpPassinp","EmpRoleinp","EmpStatusinp","EmailIdinp","MobileNoinp"];

	var inparr={};

	for(var i=0;i<arr.length;i++){
		var v=document.getElementById(arr[i]).value;
		var av=arr[i];
		inparr[av]=v;
		document.getElementById(arr[i]).readOnly = true; 
	}
	// console.log(inparr);
	
	$.post("uajax.php",{act:"savenewuser",inputs:inparr},function(data){
		console.log(data);
		if(data.includes('success')){
			setTimeout(function(){ 
				$("#editsts").html('<button class="btn btn-success btn-sm font-weight-bold">Saved</button> ');
				
			},1000);
			
		}else{
			
			$("#editsts").html('Error...');
			
			
		}

	});
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function newuser(){
	var w=520;
	var h=400;
	var left = (screen.width/2)-(h/2);
  var top = (screen.height/2)-(w/2);
	win =window.open("newuser.php","AttachForm","scrollbars=yes,resizable=no,width="+w+",height="+h+",top="+top+", left="+left);
          
          win.focus();
 // popupwindow("newuser.php","AttachForm","450","1100");
}





$(document).ready(function(){
	// $('#txtDate').datepicker('setDate', 'today');
   	$('#DOBinp').datepicker({
        uiLibrary: 'bootstrap4',
        format:"dd-mm-yyyy",
    });
    $('#DateJoininginp').datepicker({
        uiLibrary: 'bootstrap4',
        format:"dd-mm-yyyy",
    });
});


