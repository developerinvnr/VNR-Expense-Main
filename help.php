<?php
include "header.php";
?>



<div class="container">
	<div class="row d-flex justify-content-around">
		<div class="col-md-10">
		<div class="accordion" id="accordionExample">
			<?php
		  	$sel=mysql_query("SELECT * FROM `help` where `helpfor`='".$_SESSION['EmpRole']."' order by `order` asc");
		  	$n=1;
		  	while($seld=mysql_fetch_assoc($sel)){

		  	?>
			<div class="card">
			<div class="card-header" id="heading<?=$seld['id']?>">
			  <h2 class="mb-0">
			    <button class="btn btn-link text-left" type="button" data-toggle="collapse" data-target="#collapse<?=$seld['id']?>" aria-expanded="true" aria-controls="collapse<?=$seld['id']?>">
			     <b> <?=$seld['title']?></b>
			    </button>
			  </h2>
			</div>

			<div id="collapse<?=$seld['id']?>" class="collapse <?php //if($n==1){echo 'show';}?>" aria-labelledby="heading<?=$seld['id']?>" data-parent="#accordionExample">
			  <div class="card-body">
			    <?=$seld['description']?>
			  </div>
			</div>
			</div>

			<?php
			$n++;
			}
			?>

			

		</div>

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