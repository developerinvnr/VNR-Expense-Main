<?php
error_reporting(0);




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
  foreach($_FILES["NewFile"]["tmp_name"] as $key=>$tmp_name){

  // $file_name=$_FILES["files"]["name"][$key];
  // $file_tmp=$_FILES["files"]["tmp_name"][$key];

    $sourcePath = $_FILES['NewFile']['tmp_name'][$key];
    $targetPath = "documents/".$_FILES['NewFile']['name'][$key];
      
    $ext = substr($targetPath, strrpos($targetPath, '.') + 1);
    $without_ext = basename($targetPath, '.'.$ext);
    $newfileName=$without_ext.'_'.date("dmy").'_'.$_POST['uuid'];
    $new_targetPath = "documents/".$newfileName.'.'.$ext;
    



    if($ext=='jpg' || $ext=='jpeg' || $ext=='JPG' || $ext=='JPEG' || $ext=='png' || $ext=='PNG' || $ext=='gif' || $ext=='GIF'){ 

      //here 'S' stands for submit so for example the name "S12" shows the sequence of file when selected multiple files
      $new_targetPath = "documents/beforeCompress/".$newfileName.'_S'.$filecount.'.'.$ext;

      $second_new_targetPath = "documents/".$newfileName.'_S'.$filecount.'.'.$ext;


      if(move_uploaded_file($sourcePath,$new_targetPath)){

        if($d = compress($new_targetPath, $second_new_targetPath, 50)){
          echo '<input type="hidden" id="ufilename" name="ufilename" value="'.$newfileName.'"form="claimform"  />';
          echo '<input type="hidden" id="extname" name="extname" value="'.$ext.'" form="claimform" />';
          echo '<iframe src="imgPreBeforeUpdate.php?imglink='.$second_new_targetPath.'" border="1" style="width:100%;height:93%;"/></iframe>';

          unlink($new_targetPath);
        }

      }

    }elseif($ext=='pdf' || $ext=='PDF'){

      if(move_uploaded_file($sourcePath,$new_targetPath)){
        echo '<input type="hidden" id="ufilename" name="ufilename" value="'.$newfileName.'" form="claimform" />';
        echo '<input type="hidden" id="extname" name="extname" value="'.$ext.'" form="claimform" />';
        echo '<iframe src="'.$new_targetPath.'" border="1" style="width:100%;height:93%;"></iframe>';
      }

    }else{
      echo "<br><br><br><br><br><div class='btn-danger'>Error: Only jpg, png, gif or pdf file is allowed</div>";
    }

    $filecount++;

  }
  
   
} 





?>