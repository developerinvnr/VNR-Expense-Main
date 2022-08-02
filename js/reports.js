function openprevupload(expid){
	var w=1200;
	var h=600;
	var left = (screen.width/2)-(h/2);
	var top = (screen.height/2)-(w/2);
	win =window.open("showclaim.php?expid="+expid,"AttachForm","scrollbars=yes,resizable=no,width="+w+",height="+h+",top="+top+", left="+left);

	win.focus();

}

$(document).ready(function(){
	
   	$('#fromdtfr').datepicker({
        uiLibrary: 'bootstrap4',
        format:"dd-mm-yyyy",
    });
    //here on change of from date setting todate minimum date
    $('#fromdtfr').on('change', function() { 
	    var datearray = $('#fromdtfr').val().split("-");
	    var year =  datearray[2];
	    var month = datearray[1];
	    var day = datearray[0];
	    var minDated = (year +"-"+ month +"-"+ day);
		$("#todtfr").datepicker("destroy");
	    
	    $('#todtfr').datepicker({
	    	minDate: new Date(minDated),
	        uiLibrary: 'bootstrap4',
	        format:"dd-mm-yyyy",
	    });

	});
    $('#todtfr').datepicker({
        uiLibrary: 'bootstrap4',
        format:"dd-mm-yyyy",
    });

    //here on todate blank when searched, setting fromdate maximum date
    if($('#fromdtfr').val()=='' && $('#todtfr').val()!=''){
    	var datearray = $('#todtfr').val().split("-");
	    var year =  datearray[2];
	    var month = datearray[1];
	    var day = datearray[0];
	    var maxDated = (year +"-"+ month +"-"+ day);
		$("#fromdtfr").datepicker("destroy");
	    
	    $('#fromdtfr').datepicker({
	    	maxDate: new Date(maxDated),
	        uiLibrary: 'bootstrap4',
	        format:"dd-mm-yyyy",
	    });
    }

    //here on fromdate blank when searched, setting todate minimum date
    if($('#fromdtfr').val()!='' && $('#todtfr').val()==''){
    	var datearray = $('#fromdtfr').val().split("-");
	    var year =  datearray[2];
	    var month = datearray[1];
	    var day = datearray[0];
	    var minDated = (year +"-"+ month +"-"+ day);
		$("#todtfr").datepicker("destroy");
	    
	    $('#todtfr').datepicker({
	    	minDate: new Date(minDated),
	        uiLibrary: 'bootstrap4',
	        format:"dd-mm-yyyy",
	    });
    }
});




function filter(){

var u = $('#userfr').val();
var cs = $('#claimStatusfr').val();
var ct = $('#claimTypefr').val();
var f = $('#fromdtfr').val();
var t = $('#todtfr').val();
var ff = $('#RdoSelN').val(); 

window.open("reports.php?u="+u+"&ct="+ct+"&cs="+cs+"&f="+f+"&t="+t+"&ff="+ff,"_self")
}