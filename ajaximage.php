
<?php
error_reporting(0);
session_start();
include 'config.php';
date_default_timezone_set('Asia/Calcutta');


function compress($source, $destination, $quality) {

    $info = getimagesize($source);

    if ($info['mime'] == 'image/jpeg') 
        $image = imagecreatefromjpeg($source);

    elseif ($info['mime'] == 'image/gif') 
        $image = imagecreatefromgif($source);

    elseif ($info['mime'] == 'image/png') 
        $image = imagecreatefrompng($source);

    imagejpeg($image, $destination, $quality);

    return $destination;
}


if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
{


  $filecount=1;

  $uploadfiles=array();



  foreach($_FILES["NewFile"]["tmp_name"] as $key=>$tmp_name){

  // $file_name=$_FILES["files"]["name"][$key];
  // $file_tmp=$_FILES["files"]["tmp_name"][$key];

    $sourcePath = $_FILES['NewFile']['tmp_name'][$key];
    $targetPath = "documents/".$_FILES['NewFile']['name'][$key];
      
    $ext = substr($targetPath, strrpos($targetPath, '.') + 1);
    $without_ext = basename($targetPath, '.'.$ext);
    $newfileName=$without_ext.'_'.date("dmy").'_'.$_POST['uuid'].'_S'.$filecount;
    $new_targetPath = "documents/".$newfileName.'.'.$ext;
    

    $userYearlyFolder = "documents/". $_SESSION['FYearId'] . "/";
    if(!file_exists($userYearlyFolder)){
        mkdir($userYearlyFolder);
    }

    $userfolder = $userYearlyFolder. $_SESSION['EmployeeID'] . "/";
    if(!file_exists($userfolder)){
        mkdir($userfolder);
    }

    $userYearlyTempFolder = $userfolder."temp/";
    if(!file_exists($userYearlyTempFolder)){
        mkdir($userYearlyTempFolder);
    }

    $second_new_targetPath = $userYearlyTempFolder.$newfileName.'.'.$ext;

    if($ext=='jpg' || $ext=='jpeg' || $ext=='JPG' || $ext=='JPEG' || $ext=='png' || $ext=='PNG'){ 

      //here 'S' stands for submit so for example the name "S12" shows the sequence of file when selected multiple files
      $new_targetPath = "documents/beforeCompress/".$newfileName.'.'.$ext;


      if(move_uploaded_file($sourcePath,$new_targetPath)){

        if($d = compress($new_targetPath, $second_new_targetPath, 50)){

          $uf=$newfileName.'.'.$ext;
          array_push($uploadfiles,$uf);

          unlink($new_targetPath);
        }

      }

    }elseif($ext=='pdf' || $ext=='PDF'){

      if(move_uploaded_file($sourcePath,$second_new_targetPath)){
        $uf=$newfileName.'.'.$ext;
        array_push($uploadfiles,$uf);

      }

    }else{
      echo "<br><br><br><br><br><div class='btn-danger'>Error: Only jpg, png, gif or pdf file is allowed</div>";
    }

    $filecount++;


  }
  
   // print_r($uploadfiles);

  $prevuploadfiles=array();

  if(isset($_POST['prevRequestText']) && $_POST['prevRequestText']!=''){
    $Text = urldecode($_POST['prevRequestText']);
    $prevuploadfiles = json_decode($Text);

    // print_r($prevuploadfiles)

    $Text = json_encode(array_merge($prevuploadfiles,$uploadfiles));
    $RequestText = urlencode($Text); //here preparing the array, to send it by iframe url
  }else{
    $Text = json_encode($uploadfiles);
    $RequestText = urlencode($Text); //here preparing the array, to send it by iframe url
  }


    

    // echo '<input type="hidden" id="ufilename" name="ufilename" value="'.$newfileName.'"form="claimform"  />';
    // echo '<input type="hidden" id="extname" name="extname" value="'.$ext.'" form="claimform" />';


    
    
    if(isset($_POST['winheight'])){$sh=$_POST['winheight'];}else{$sh=98;}


    
    
    echo '<input type="hidden" id="RequestText" name="RequestText" value="'.$RequestText.'" form="claimform" />';
    echo '<iframe src="imgPreBeforeUpdate.php?imglink='.$RequestText.'" border="1" style="width:100%;height:'.$sh.'%;"/></iframe>';

    // echo '<input type="hidden" id="ufilename" name="ufilename" value="'.$newfileName.'" form="claimform" />';
    // echo '<input type="hidden" id="extname" name="extname" value="'.$ext.'" form="claimform" />';
    // echo '<iframe src="'.$new_targetPath.'" border="1" style="width:100%;height:93%;"></iframe>';
} 





?>