<?php
session_start();
include 'config.php';

?>
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="//npmcdn.com/pdfjs-dist/build/pdf.js"></script>

<style type="text/css">
	.delbtns{
		vertical-align   : top;
		position         : relative;
		right            : 25px;
		padding          : 4px;
		cursor           : pointer;
		background-color : #DC3545;
		color            : white;
		border           : 0px;
	}
</style>
<script type="text/javascript">
	function showthumbs(i,link){
		pdfjsLib.getDocument(link).promise.then(function (doc) {
		  var pages = [];
		   while (pages.length < doc.numPages) pages.push(pages.length + 1);
		  return Promise.all(pages.map(function (num) {
		    
		    return doc.getPage(num).then(makeThumb)
		      .then(function (canvas) {
		        document.getElementById('div'+i).appendChild(canvas);
		    });
		  }));
		}).catch(console.error);
		// alert('div'+i);
	}

	function makeThumb(page) {
	  // draw page to fit into canvas
	  var vp = page.getViewport(1);
	  var canvas = document.createElement("canvas");
	  canvas.width = 62;
	  canvas.height = 80;
	  var scale = Math.min(canvas.width / vp.width, canvas.height / vp.height);
	  return page.render({canvasContext: canvas.getContext("2d"), viewport: page.getViewport(scale)}).promise.then(function () {
	    return canvas;
	  });
	}

	
	function showinbig(a){
		$('#showcase img').css('border','3px solid #4d4d4d');
		$('#showcase div').css('border','3px solid #4d4d4d');
		$(a).css('border','3px solid #eee');
		var aa=$(a).attr('src');
		$('#frameunderframe').attr('src','imgPreviewPage.php?imglink='+aa);
	}

	function showpdfinbig(i,link){
		$('#showcase img').css('border','3px solid #4d4d4d');
		$('#showcase div').css('border','3px solid #4d4d4d');
		$('#div'+i).css('border','3px solid #eee');
		
		$('#frameunderframe').attr('src',link);
	}

	function zoomin(){
		var h=parseInt(document.getElementById("preimg").clientHeight);
		document.getElementById('preimg').style.height=h+100+'px';	

		var w=parseInt(document.getElementById("preimg").clientWidth);
		document.getElementById('preimg').style.width=w+85+'px';	
	}
	function zoomout(){
		var h=parseInt(document.getElementById("preimg").clientHeight);
		document.getElementById('preimg').style.height=h+(-100)+'px';

		var w=parseInt(document.getElementById("preimg").clientWidth);
		document.getElementById('preimg').style.width=w+(-85)+'px';
	}


	
</script>
<?php

// print_r($_REQUEST['imglink']);
// echo array($_REQUEST['imglink']);
$Text = urldecode($_REQUEST['imglink']);
$uploadfiles = json_decode($Text);
// print_r( $uploadfiles);
?>

<!-- First Rows Start -->
<!-- First Rows Start -->
<div id="showcase" style="width:100%;overflow-x: auto; white-space: nowrap;background-color:#4d4d4d;"> 

 <?php
   $i=1;
   if(isset($_REQUEST['inpage']) && $_REQUEST['inpage']=='showclaim'){
	 $selu=mysql_fetch_assoc(mysql_query("select ClaimStatus, ClaimAtStep,CrBy from y".$_SESSION['FYearId']."_expenseclaims where ExpId=".$_REQUEST['expid']));
		$user=$selu['CrBy'];
	}else{
		$user=$_SESSION['EmployeeID'];
	}
	

   /*

	$uYTemp = "documents/".$_SESSION['FYearId'] ."/".$user."/"."temp/";
	$uY = "documents/".$_SESSION['FYearId'] ."/".$user."/";
	
	$uYTemp_old = "documents/3/".$user."/"."temp/";
	$uY_old = "documents/3/".$user."/";
	
	$uYTemp3_old = "documents/4/".$user."/"."temp/";
	$uY3_old = "documents/4/".$user."/";
	
	/****************
	$u2YTemp = "http://45.124.144.101/xeasyfile/".$_SESSION['FYearId'] ."/".$user."/"."temp/";
	$u2Y = "http://45.124.144.101/xeasyfile/".$_SESSION['FYearId'] ."/".$user."/";
	
	$u2YTemp_phone = "https://45.124.144.101/xeasyfile/".$_SESSION['FYearId'] ."/".$user."/"."temp/";
	$u2Y_phone = "https://45.124.144.101/xeasyfile/".$_SESSION['FYearId'] ."/".$user."/";
    /****************

	if(isset($_REQUEST['inpage']) && $_REQUEST['inpage']=='showclaim'){
		$location=$uY;  $location2=$u2Y;  $location_old=$uY_old;
		$claim='uploaded'; $location_phone=$u2Y_phone; $location3_old=$uY3_old;

	}else{
		$location=$uYTemp;  $location2=$u2YTemp;   $location_old=$uYTemp_old;
		$claim='uploading'; $location_phone=$u2YTemp_phone; $location3_old=$uYTemp3_old;
		$left='ok';
	}

    */
    
    /************* New New *******************************/
	$oldY=$_SESSION['FYearId']-1;
	
	$loc_1="documents/".$_SESSION['FYearId'] ."/".$user."/";
	$loc_1temp="documents/".$_SESSION['FYearId'] ."/".$user."/"."temp/";
	
	$loc_2="documents_old/".$_SESSION['FYearId'] ."/".$user."/";
	$loc_2temp="documents_old/".$_SESSION['FYearId'] ."/".$user."/"."temp/";
	
	$loc_3="documents/".$oldY."/".$user."/";
	$loc_3temp="documents/".$oldY."/".$user."/"."temp/";
	
	$loc_4 = "http://45.124.144.101/xeasyfile/".$_SESSION['FYearId'] ."/".$user."/";
	$loc_4temp = "http://45.124.144.101/xeasyfile/".$_SESSION['FYearId'] ."/".$user."/"."temp/";
	
	$loc_5 = "https://45.124.144.101/xeasyfile/".$_SESSION['FYearId'] ."/".$user."/";
	$loc_5temp = "https://45.124.144.101/xeasyfile/".$_SESSION['FYearId'] ."/".$user."/"."temp/";
	
	$loc_6 = "http://45.124.144.101/xeasyfile/".$oldY."/".$user."/";
	$loc_6temp = "http://45.124.144.101/xeasyfile/".$oldY."/".$user."/"."temp/";
	
	$loc_7 = "https://45.124.144.101/xeasyfile/".$oldY."/".$user."/";
	$loc_7temp = "https://45.124.144.101/xeasyfile/".$oldY."/".$user."/"."temp/";
	
	if(isset($_REQUEST['inpage']) && $_REQUEST['inpage']=='showclaim')
	{
	  $claim='uploaded';
	  $Loc1=$loc_1; $Loc2=$loc_2; $Loc3=$loc_3; $Loc4=$loc_4; $Loc5=$loc_5; $Loc6=$loc_6; $Loc7=$loc_7; 
	}
	else
	{   
	  $claim='uploading'; $left='ok';
	  $Loc1=$loc_1temp; $Loc2=$loc_2temp; $Loc3=$loc_3temp; $Loc4=$loc_4temp; $Loc5=$loc_5temp; 
	  $Loc6=$loc_6temp; $Loc7=$loc_7temp; 
	}
    /************* New New *******************************/
    

	foreach ($uploadfiles as $key => $value) {
		$name = explode( '.', $value );//echo $name[0];
		$ext = substr($value, strrpos($value, '.') + 1);
		if($i==1){}
		// echo 'div'.$i;
		
		  /*
		  if(file_exists($location.$value)){ $path=$location.$value; $loc=$location; } 
	      elseif(file_exists($location_old.$value)){ $path=$location_old.$value; $loc=$location_old; }
	      elseif(file_exists($location3_old.$value)){ $path=$location3_old.$value; $loc=$location3_old; }
	      //elseif(file_exists($location_phone.$value)){ $path=$location_phone.$value; $loc=$location_phone; }
	      else{ $path=$location2.$value; $loc=$location2; }
	      */
	      
	      /************* New New *******************************/
	      if(file_exists($Loc1.$value)){ $path=$Loc1.$value; $loc=$Loc1; } 
          elseif(file_exists($Loc2.$value)){ $path=$Loc2.$value; $loc=$Loc2; } 
          elseif(file_exists($Loc3.$value)){ $path=$Loc3.$value; $loc=$Loc3; } 
	      elseif(file_exists($Loc4.$value)){ $path=$Loc4.$value; $loc=$Loc4; }
	      elseif(file_exists($Loc5.$value)){ $path=$Loc5.$value; $loc=$Loc5; }
	      elseif(file_exists($Loc6.$value)){ $path=$Loc6.$value; $loc=$Loc6; }
	      elseif(file_exists($Loc7.$value)){ $path=$Loc7.$value; $loc=$Loc7; }
          else{ $path=$Loc2.$value; $loc=$Loc2; }
	      /************* New New *******************************/
	      
	      
		
		//echo 'aa='.$path;
		
		if($ext=='jpg' || $ext=='jpeg' || $ext=='JPG' || $ext=='JPEG' || $ext=='png' || $ext=='PNG'){
			?>
			
			
			<span  id="div<?=$i?>" style="margin-left: <?php if($left=='ok' && $i!=1){echo '-20px;';}else{ echo '0px;'; }?>; width:100%;">
			<img id="img<?=$i?>" src="<?=$path?>" style="vertical-align:top;margin-top:4px;height:30px;border:3px solid <?php if($i==1){echo '#eee';}else{echo '#4d4d4d';} ?>; cursor: pointer;" onclick="showinbig(this)">
			<?php
			// echo $claim;

			if(isset($selu) && $selu['ClaimStatus'] == 'Submitted'){
			?>
			<button id="btn<?=$i?>" class="delbtns"><i class="fa fa-times fa-sm" aria-hidden="true" onclick="deleteimg('<?=$path?>')"></i></button>

			</span>
			<?php
			}else{
				?>
				<!-- <div class="delbtns"></div> -->
				<?php
			}
		}else if($ext=='pdf' || $ext=='PDF'){
			?>
			
			
			<div id="div<?=$i?>" style="width:44px;height:60px;border:3px solid #4d4d4d; cursor: pointer;overflow: hidden;display:inline-block;background-color: orange;margin-left: -20px;" onclick="showpdfinbig('<?=$i?>','<?=$path?>')">
			</div>
			<button id="btn<?=$i?>" class="delbtns"><i class="fa fa-times fa-sm" aria-hidden="true" onclick="deleteimg('<?=$path?>')"></i></button>
			<script type="text/javascript">
				//this function here gets the pdf,    then changes all pdf pages to canvase ,   then put it in this above div
				showthumbs('<?=$i?>','<?=$path?>');
			</script>
			<?php
		}
		$i++;
	}
	?>

	<?php
	
	
	if(isset($_REQUEST['expid'])){  //this add button shows when add new files to claim uploades

		if($selu['ClaimAtStep']<=2 && ($_SESSION['EmpRole']=='E' || $_SESSION['EmpRole']=='A')){
			?>
			<button style="margin-top:10px;margin-left:0px;border-radius:50%;padding:4px;cursor: pointer;" onclick="addnewimage()">
				<i class="fa fa-plus fa-lg" aria-hidden="true"></i>
			</button>
			<?php
		}
	}elseif($_SESSION['EmpRole']=='E' || $_SESSION['EmpRole']=='A'){ //this add button shows when first time uploading files to claim
		?>

		<button style="margin-top:20px;margin-left:-20px;border-radius:50%;padding:4px;cursor: pointer;" onclick="addimage()">
			<i class="fa fa-plus fa-lg" aria-hidden="true"></i>
		</button>
		<?php
	}

	
	?>
</div>
<!-- First Rows Close -->
<!-- First Rows Close -->

<!-- Second Rows Start -->
<!-- Second Rows Start -->
<input type="hidden" id="uploadscount" value="<?=$i?>">
<iframe src="imgPreviewPage.php?imglink=<?=$loc.$uploadfiles[0]?>" id="frameunderframe" style="width:99%;height:98%; padding:0px;"></iframe>
<!-- Second Rows Close -->
<!-- Second Rows Close -->


<!-- 
===============================================
Add new image form start
===============================================
-->

<form id="imageform" method="post" enctype="multipart/form-data" action='ajaxnewimage.php' style="display: none;">
	
	<input type="file"  id="NewFile" name="NewFile[]" multiple>
	<input type="hidden" id="uuid" name="uuid" value="<?php echo $_SESSION['EmployeeID']; ?>" />
	<input id="expid" name="expid" value="<?php if(isset($_REQUEST['expid'])){ echo $_REQUEST['expid']; } ?>" />

</form>

<!-- 
===============================================
Add new image form end
===============================================
-->

<?php
include "footer.php";
?>
<script
        src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>




<script type="text/javascript">


$("#NewFile").on("change", function(){
        $("#imageform").submit();
    });


function addimage(){
	
	parent.document.getElementById("prevRequestText").value=parent.document.getElementById("RequestText").value;

	parent.document.getElementById("NewFile").click();

}

function addnewimage(){
	
	//parent.document.getElementById("prevRequestText").value=parent.document.getElementById("RequestText").value;

	document.getElementById("NewFile").click();

}
	

function deleteimg(img){


	if (confirm('Are you sure to Delete?')) {
	
	    var r=parent.document.getElementById("RequestText").value;
	    
	    var ra=decodeURIComponent(r);
	    ra=JSON.parse(ra);
	    // alert(ra);
	    // alert(ra[0]);
	    // alert(img);
	    console.log(img);
	    
	    img=img.split("/"); //spliting the path with / for getting filename
	    // console.log(img);
	    // alert(img[4]);
	    var i=img[3]; // assinging file name to var i
	    i=i.toString();
	    console.log(i);

	    $.post("homeajax.php",{act:"delupimg",imgname:i},function(data){
			console.log(data);
		});
	    //here checking is the deleted image is displaying in big frame, if yes then removing from the big frame->id->'frameunderframe'
	    var fr=document.getElementById("frameunderframe").src;
		fr=fr.split("/");
	    if(fr[fr.length-1]==i){
	    	document.getElementById("frameunderframe").src='';
	    }

	    i=i.replace(/ /g,"+"); // here replacing space with '+' for files named with spaces 
	    // alert(i);
	    var index=ra.indexOf(i);

	    // alert(index);
	    if(index > -1){
	    	ra[index]="";
	    }
	    // alert(ra);
	    ra=JSON.stringify(ra);
	    ra=encodeURIComponent(ra);
	    parent.document.getElementById("RequestText").value=ra;

	    var deldiv=index+1;
	   	
	   	//removing the deleted file thumbnail and delete button
	    document.getElementById('div'+deldiv).remove();
	    if(document.getElementById('btn'+deldiv)){
	    	document.getElementById('btn'+deldiv).remove();
	    }
	    
	    //updating the counter on deletion
	    var uc=document.getElementById('uploadscount').value;
	    uc=parseInt(uc)-1;
	    document.getElementById('uploadscount').value=uc;

	    if(uc==1){
	    	//here disabling the submit button if deleted all uploads
	    	parent.document.getElementById("submit").disabled = true;
	    }
    } else {
	    // Do nothing!
	}


}
</script>



