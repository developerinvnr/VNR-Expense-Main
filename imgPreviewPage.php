<!DOCTYPE html>
<html>
<head>
<title></title>
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<script src="js/dragscroll.js"></script>
<link rel="stylesheet" type="text/css" href="css/style.css">

<style type="text/css">
	.draghand{
		cursor: -webkit-grab; cursor: grab;
	}
	.draghandactive{
		cursor: -webkit-grabbing; cursor: grabbing;
	}
</style>


</head>


<body class="dragscroll draghand" >
 <button style="position: fixed;left:10px;top:2vh;padding:4px;cursor: pointer;z-index: 99;" onClick="zoomin()">
 <i class="fa fa-plus fa-lg" aria-hidden="true"></i>
 </button>
 <button style="position: fixed;left:45px;top:2vh;padding:4px;cursor: pointer;z-index: 99;" onClick="zoomout()">
  <i class="fa fa-minus fa-lg" aria-hidden="true"></i>
 </button>



 <button style="position: fixed;right:10px;top:2vh;padding:4px;cursor: pointer;z-index: 99;" onClick="rotateImage(90);">
  <i class="fa fa-repeat fa-lg" aria-hidden="true"></i>
 </button>
 <input type="hidden" id="imgposition" value="0">




 <?php if(isset($_REQUEST['imglink'])){ ?>
 <img id="preimg" src="<?php echo $_REQUEST['imglink'];?>" style="width:100%;height: 100vh;">
 <?php } ?>

<script
        src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>

<script>
	

	
$(document.body)
  .mouseup(function() {
    $(document.body).css({'cursor' : 'grab'});
  })
  .mousedown(function() {
    $(document.body).css('cursor', 'grabbing'); 
 });



	function zoomin(){
		var h=parseInt(document.getElementById("preimg").clientHeight);
		var w=parseInt(document.getElementById("preimg").clientWidth);
		
		if(w>h){
			
			var ratio = w / h;
			var maxWidth=w+100;
			document.getElementById('preimg').style.height= (maxWidth/ratio)+'px';
			document.getElementById('preimg').style.width=(maxWidth)+'px';

		}else if(h>w){
			
			var ratio = h / w;
			var maxHeight=h+100;
			document.getElementById('preimg').style.height=(maxHeight)+'px';
			document.getElementById('preimg').style.width= (maxHeight/ratio)+'px';
		   
		}
		
	}
	function zoomout(){
		var h=parseInt(document.getElementById("preimg").clientHeight);
		var w=parseInt(document.getElementById("preimg").clientWidth);
		
		if(w>h){
			
			var ratio = w / h;
			var maxWidth=w-100;
			document.getElementById('preimg').style.height= (maxWidth/ratio)+'px';
			document.getElementById('preimg').style.width=(maxWidth)+'px';

		}else if(h>w){
			
			var ratio = h / w;
			var maxHeight=h-100;
			document.getElementById('preimg').style.height=(maxHeight)+'px';
			document.getElementById('preimg').style.width= (maxHeight/ratio)+'px';
		   
		}
	}


	function rotateImage(degree) {


		var imgpos= parseInt($('#imgposition').val());
		var newpos=imgpos+degree;
	    $('#imgposition').val(newpos);

	    
		$('#preimg').animate({  transform: newpos }, {
	    step: function(now,fx) {
	        $(this).css({
	            '-webkit-transform':'rotate('+newpos+'deg)', 
	            '-moz-transform':'rotate('+newpos+'deg)',
	            'transform':'rotate('+newpos+'deg)'
	        });
	    }
	    });
	}
</script>
</body>
</html>