function showclaimform(claimid){

	$.post("claimajax.php",{act:"getclaimform",claimid:claimid},function(data){
		$('#claimformbody').html(data);
       
        
        if($("#RequestText").val()){
            //here checking if files uploaded or not, if uploaded then removing disable from submit button
            $("#submit").prop("disabled",false);
        }


	});

}

function checkbdt(dt){
    var today = new Date();
    

    var datearrayb = dt.split(" ");

    var datearrayb = datearrayb[0].split("-");
    var yearb =  datearrayb[2];
    var monthb = datearrayb[1];
    var dayb = datearrayb[0];

        var cm = today.getMonth()+1;
        var cd = today.getDate();
        
        if(monthb != cm && cd >15){

            alert("This Billdate's month been closed");
            // $('#BillDate').unbind("change");
            $("#claimtype").val('select');
            $('#claimformbody').html('');
            // $('#BillDate').val('');
            
        }else{
            
        }
}

//loging form dates settings
$(document).ready(function(){

    if(typeof $('#BillDate').val() == 'undefined'){

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today =  (day) + "-" + (month) + "-" + now.getFullYear();
    
       var BillDate = today;
    }else{
       var BillDate = $('#BillDate').val();
    }

	var datearrayb = BillDate.split(" ");

    var datearrayb = datearrayb[0].split("-");
    var yearb =  datearrayb[2];
    var monthb = datearrayb[1];
    var dayb = datearrayb[0];
    var BillminDated = (yearb +"-"+ monthb +"-"+ dayb);

    $('#BillDate').datetimepicker({
        format:'d-m-Y',
        maxDate: new Date(BillminDated)
    });
    

    $('#BillDate').on('change', function(){
	    $(this).datetimepicker('hide');
        checkbdt($('#BillDate').val());

	}); //here closing the billdate datetimepicker on date change 
    

    $('#DocketBookedDt').datetimepicker({
        format:'d-m-Y'
        
    });

    $('#DocketBookedDt').on('change', function(){
        $(this).datetimepicker('hide');
    }); //here closing the DocketBookedDt datetimepicker on date change 


    $('#DueDate').datetimepicker({format:'d-m-Y'});
    $('#DueDate').on('change', function(){
        $(this).datetimepicker('hide');
    }); //here closing the billdate datetimepicker on date change 



    

    

    

    $('#arrdate').datetimepicker({format:'d-m-Y H:i'});
    $('#arrdate').on('change', function() { 
	    var datearray = $('#arrdate').val().split(" ");

	    var datearray = datearray[0].split("-");
	    var year =  datearray[2];
	    var month = datearray[1];
	    var day = datearray[0];
	    var minDated = (year +"-"+ month +"-"+ day);
		$("#depdate").datetimepicker("destroy");
	    
	    $('#depdate').datetimepicker({
	    	minDate: new Date(minDated),
	    	format:'d-m-Y H:i'
	    });

	 });
    $('#depdate').datetimepicker({format:'d-m-Y H:i'});
 
});



function openprevupload(expid){
	var w=1200;
	var h=600;
	var left = (screen.width/2)-(h/2);
	var top = (screen.height/2)-(w/2);
	win =window.open("showclaim.php?expid="+expid,"AttachForm","scrollbars=yes,resizable=no,width="+w+",height="+h+",top="+top+", left="+left);
	win.focus();
}


$(document).ready(function (e) {
    $('#imageform').on('submit',(function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
                // console.log("success");
                // console.log(data);
                $("#preview").html(data);

                $("#uploadform").hide();
                $("#removeupload").css("display","block");
               
                $("#submit").prop("disabled",false);

                $("#submit").css("background-color","");
                $("#submit").css("color","white");
                $("#submit").addClass("btn-success");


                // var ar=$("#RequestText").val();
                // $("#array").html(ar);
                $("#loadinganim").hide(200);
                
            },
            error: function(data){
                console.log("error");
                console.log(data);
            }
        });
    }));

    $("#NewFile").on("change", function(){
        $("#imageform").submit();
        $("#loadinganim").show(200);


    });
});



function showuploadbtn(){
    if (confirm('Are you sure to Delete?')) {
    	$("#uploadform").show();
    	$("#preview").html('');
        $("#removeupload").css("display","none");
        $("#submit").prop("disabled",true);
    }
}

function changemode(m){
    if(m=='Air'){$("#modenm").html('Flight');}
    if(m=='Rail'){$("#modenm").html('Train');}
    if(m=='Bus'){$("#modenm").html(m);}
    
}




//rail & bus form dates settings
$(document).ready(function(){
    
    $('#BookingDate').datetimepicker({format:'d-m-Y'});
    $('#BookingDate').on('change', function(){
        $(this).datetimepicker('hide');
    }); //here closing the BookingDate datetimepicker on date change 

    $('#rbJourneyStartDt').datetimepicker({format:'d-m-Y'});
    $('#rbJourneyStartDt').on('change', function(){
        $(this).datetimepicker('hide');
    }); //here closing the JourneyStartDt datetimepicker on date change 

});


function edit(){

    
    $('#editbtn').hide();
    $('#Update').attr('disabled',false);
    // $('#claimtype').attr('readonly', false);
    $('#claimform input').attr('readonly', false);
    $('#claimform select').attr('readonly', false);
    $('#claimform textarea').attr('readonly', false);
    $('#claimform button').css('display','block');

    $('#Amount').attr('readonly', true);
    $('#VerifierEditAmount').attr('readonly', true);
    $('#ApproverEditAmount').attr('readonly', true);
    $('#FinanceEditAmount').attr('readonly', true);
}


function addfaredet(){
    
    var cid=$('#cid').val();
    
    // alert(cid);
    // if(cid==1){
       var c=parseInt($('#fdtcount').val());
        c++;
        $('#fdtcount').val(c);

        var aa='<tr> <td><input class="form-control" name="fdtitle'+c+'" style="" required></td> <td> <input class="form-control text-right" id="fdamount'+c+'" name="fdamount'+c+'" style="" onkeypress="return isNumber(event)" onkeyup="caltotal(this)"  required> </td> <td> <input class="form-control" id="fdremark'+c+'" name="fdremark'+c+'" > </td> <td  style="width: 20px;"> <button  type="button" class="btn btn-sm btn-danger pull-right" onclick="delthis(this)"> <i class="fa fa-times fa-sm" aria-hidden="true"></i> </button> </td> </tr>'; 

        $('#faredettbody').append(aa); 
    // }
    

}

function delthis(a){
    
    $(a).closest("tr").remove();  
    caltotal(a);
}


function caltotal(thi){
    
    var c=parseInt($('#fdtcount').val());

    var amt=0;
    for(var i=1;i<=c;i++){
        amt+=parseInt($('#fdamount'+i).val() || 0);
    }


    var limit=$('#limitAmount').val();
    var role=$('#EmpRole').val();

    if(role!='M'){

        if(amt<=limit){
            $('#Amount').val(amt);
        }else{
            $(thi).val('');
            alert('You cant assign more than limit amount');
            // caltotal(thi);
        }
    }else if(role=='M'){
        $('#Amount').val(amt);
    }


    
}

function calvatotal(thi){
    
    var c=parseInt($('#fdtcount').val());
    var amt=0;
    for(var i=1;i<=c;i++){
        amt+=parseInt($('#fdVerifierEditAmount'+i).val() || 0);
    }
    // $('#VerifierEditAmount').val(amt);

    var limit=$('#limitAmount').val();
    // alert(limit);

    if(amt<=limit){
        $('#VerifierEditAmount').val(amt);
    }else{
        $(thi).val('');
        alert('You cant assign more than limit amount');
        calvatotal(thi)
    }
}

function calaatotal(thi){
    
    var c=parseInt($('#fdtcount').val());
    var amt=0;
    for(var i=1;i<=c;i++){
        amt+=parseInt($('#fdApproverEditAmount'+i).val() || 0);
    }
    // $('#ApproverEditAmount').val(amt);

    var limit=$('#limitAmount').val();
    // alert(limit);

    if(amt<=limit){
        $('#ApproverEditAmount').val(amt);
    }else{
        $(thi).val('');
        alert('You cant assign more than limit amount');
        calaatotal(thi)
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



$(document).ready(function(){

    $('#JourneyStartDt').datetimepicker({format:'d-m-Y H:i'});
    $('#JourneyStartDt').on('change', function() { 
        var datearray = $('#JourneyStartDt').val().split(" ");

        var datearray = datearray[0].split("-");
        var year =  datearray[2];
        var month = datearray[1];
        var day = datearray[0];
        var minDated = (year +"-"+ month +"-"+ day);
        $("#JourneyEndDt").datetimepicker("destroy");
        
        $('#JourneyEndDt').datetimepicker({
            minDate: new Date(minDated),
            format:'d-m-Y H:i'
        });

     });
    $('#JourneyEndDt').datetimepicker({format:'d-m-Y H:i'});
 
});



// function disfMonth(year){
//     $('#claimMonth').attr('disabled',false);

//     $.post("claimajax.php",{act:"getmonthselect",year:year},function(data){
//         $('#monthselectspan').html(data);

//     });

// }

