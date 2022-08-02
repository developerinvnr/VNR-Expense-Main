<?php
include "header.php";


if(isset($_POST['helpsavebtn'])){
	
	$newyr=mysql_query("INSERT INTO `help`( `title`, `description`,`helpfor`,`order`,`crdate`) VALUES ('".$_POST['title']."','".$_POST['description']."','".$_POST['helpfor']."','".$_POST['order']."',".date('Y-m-d').")");
	if($newyr){$msg='New Help Created'; $msgcolor='success';}
	
}


if(isset($_POST['helpupdtbtn'])){
	
	$yrupdt=mysql_query("UPDATE `help` set `title`='".$_POST['title']."', `description`='".$_POST['description']."',`helpfor`='".$_POST['helpfor']."',`order`='".$_POST['order']."' WHERE `id`='".$_POST['id']."'");
	if($yrupdt){$msg='Help Updated Successfully'; $msgcolor='success';}
	
}




function custom_echo($x, $length)
{
  if(strlen($x)<=$length)
  {
    echo $x;
  }
  else
  {
    $y=substr($x,0,$length) . '...';
    echo $y;
  }
}
?>


<script src="http://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>


<div class="container">
	<div class="row shadow">
		<div class="col-md-12">
			<?php if(isset($msg)){ ?>
				<div class="alert alert-<?=$msgcolor?> alert-dismissible">
			    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			    <strong><?=$msg?></strong>
			  </div>
			
			<?php } ?>
			<br>
			<button class="btn btn-sm btn-outline-primary " onclick="shownewadd()">New Help +</button>


			<div class="table-responsive">
				<table class="table shadow">

				  <thead class="thead-dark">
				    <tr>
				      <th scope="col" style="width:20px;">ID</th>
				      <th scope="col" style="min-width:200px !important;">Title</th>
				      <th scope="col">For</th>
				      <th scope="col">Order</th>
				      <th scope="col" style="width:450px;" >Description</th>
				      <th scope="col" style="width: 40px;">Action</th>
				      

				    </tr>

				    <tr id="newaddtr" style="display: none;">
				      <form action="helpedit.php" method="post">

				     	<th scope="row">
					      	
				      	</th>
						<td>
							<input class="form-control" type="text" name="title" value="<?=$seld['title']?>">
						</td>
						<td>
							
							<select class="form-control" name="helpfor">
					      		<option></option>
					      		<option value="E">E</option>
					      		<option value="A">A</option>
					      		<option value="M">M</option>
								<option value="V">V</option>
					      		<option value="F">F</option>
					      	</select>
						</td>
						<td>
							<input class="form-control" type="text" name="order" value="" style="width: 25px;">
						</td>
						<td>
							<textarea class="form-control" id="description" name="description"></textarea>
						</td>
						
						<td>
							<button class="btn btn-sm btn-primary" name="helpsavebtn" type="submit">Save</button>
						</td>

					  	</form>
				    </tr>
				  </thead>
				  <tbody>
				  	<?php
				  	$sel=mysql_query("SELECT * FROM `help`");

				  	while($seld=mysql_fetch_assoc($sel)){
				  		if(isset($_REQUEST['edit']) && $_REQUEST['edit']==$seld['id']){
				  			?>
						    <tr>
						    	<form action="helpedit.php" method="post">

						     	<th>
							      	<?=$seld['id']?>
						      		
						      	</th>
								<td>
									<input class="form-control" type="text" name="title" value="<?=$seld['title']?>">
								</td>
								<td>
									<select class="form-control" name="helpfor">
							      		<option></option>
							      		<option value="E" <?php if($seld['helpfor']=='E'){echo 'selected';}?>>E</option>
							      		<option value="A" <?php if($seld['helpfor']=='A'){echo 'selected';}?>>A</option>
							      		<option value="M" <?php if($seld['helpfor']=='M'){echo 'selected';}?>>M</option>
										<option value="V" <?php if($seld['helpfor']=='V'){echo 'selected';}?>>V</option>
							      		<option value="F" <?php if($seld['helpfor']=='F'){echo 'selected';}?>>F</option>
							      	</select>
								</td>
								<td>
									<input class="form-control" type="text" name="order" value="<?=$seld['order']?>" style="width: 25px;">
								</td>
								<td>
									<textarea class="form-control" id="description<?=$seld['id']?>" name="description"><?=$seld['description']?></textarea>
									
								</td>
								
								<td>
									<button class="btn btn-sm btn-primary" name="helpupdtbtn" type="submit">Update</button>
									<input type="hidden" name="id" value="<?=$seld['id']?>">
								</td>

							  	</form>

						    </tr>
						    

							<script>
								CKEDITOR.replace( 'description<?=$seld['id']?>', {
									filebrowserUploadUrl: "mediaupload.php",
								});
							</script>
				  		
						    <?php
				  			
				  		}else{
				  			?>
						   	<tr>
						    	
						     	<th scope="row">
							      	<?=$seld['id']?>
						      	</th>
								<td style="background-color: #E9ECEF; width: 100px;">
									<?=$seld['title']?>
								</td>
								
								<th style="background-color: #E9ECEF;">
							      	<?=$seld['helpfor']?>
						      	</th>
						      	<th style="background-color: #E9ECEF; text-align: center;">
							      	<?=$seld['order']?>
						      	</th>
						      	<td style="background-color: #E9ECEF;">
									<?php  custom_echo($seld['description'], 100); ?>
								</td>
								<td>
									<button class="btn btn-sm btn-primary" onclick="edit('<?=$seld['id']?>')">Edit</button>
								</td>

						    </tr>
						    <?php
				  		}


				  	
					}
					?>
				  </tbody>
				</table>
			</div>
			
		</div>

		<div class="col-md-4" id="udetsdiv">
			

			
			
		</div>
		
	</div>
	
</div>

<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>


<?php
include "footer.php";
?>

<script type="text/javascript" src="js/user.js"></script>
<script type="text/javascript">


function shownewadd(){
	$('#newaddtr').css('display','table-row');
}

function edit(id){
	window.location.href = 'helpedit.php?edit='+id;
}
</script>


<script>



CKEDITOR.replace( 'description', {
 
  filebrowserUploadUrl: "mediaupload.php",
  

});



							

</script>