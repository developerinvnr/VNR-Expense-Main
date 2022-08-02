<?php
include "header.php";
?>
<!-- <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css"> -->
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6 h-100 previewdiv">
			
			<div id="" style="position: relative;">
				<center>
					
					<button id="removeupload" class="btn btn-danger" onclick="showuploadbtn()" style="position: absolute;float: right;right: 0px;display: none;">
						<i class="fa fa-times-circle-o fa-2x" aria-hidden="true"></i>
					</button>

					<span id="uploadform">
						<br><br><br><br><br><br><br><br><br><br>
						<div>
						<form id="imageform" method="post" enctype="multipart/form-data" action='ajaximage.php'>
		    
					      
						    <label class="btn btn-outline-primary font-weight-bold">
						    <input type="file"  id="NewFile" name="NewFile[]" multiple>
						    Upload
						    </label>
					      	<input type="hidden" id="uuid" name="uuid" value="<?php echo $_SESSION['EmployeeID']; ?>" />
					   		<div class="text-muted">
					   			Upload jpg, png or pdf file only
					   		</div>
					    </form>
						</div>
				    </span>
				    <span id="preview">
				    	
				    </span>
				    
				</center>
			</div>
			
		</div>
		<div class="col-md-6 shadow">
			<?php
					if(isset($_REQUEST['prevupload'])){
					?>
					<button class="btn btn-primary btn-sm" onclick="openprevupload('<?=$_REQUEST['prevupload']?>')" style="position: absolute;float: left;left: 0px;margin:2px 15px;">
						View Previous Upload
					</button>
					<?php
					}
					?>
			<br>
			<center>
			<div class="table-responsive">
				
				<table class="table table-sm claimtable">
				  
				<thead class="thead-dark">
					
					<tr>
						<th scope="row"><p class="h6 pull-right tht">Year:</p></th>
						<th>
						
						<select class=" claimheadsel form-control pull-left" id="claimYear" name="claimYear"  required style="width: 70px;"  form="claimform"  onchange="disfMonth(this.value)">
						    <option value="">Select</option>
						     <?php
							  $currently_selected = date('Y'); 
							  $year_arr = explode ("-", $_SESSION['FYearId']); 
							  $earliest_year = $year_arr[0]; 
							  $latest_year = $year_arr[1]; 
							  foreach ( range( $earliest_year,$latest_year ) as $i ) {
							    print '<option value="'.$i.'"'.($i === $currently_selected ? ' selected="selected"' : '').' >'.$i.'</option>';
							  }
							  
							  ?>
						</select>
						</th>

						<th scope="row"><p class="h6 pull-right tht">Month:</p></th>
						<th>
						
						<span id="monthselectspan">
							<select class=" claimheadsel form-control pull-left" id="claimMonth" name="claimMonth" required style="width: 105px;"  form="claimform" onchange="disfClaim()" disabled>
						    <option value="">Select</option>
						</select>
						</span>
						
						</th>
						
						<th scope="row" ><p class="h6 pull-right tht">Claim:</p></th>
						<th>
							<select id="claimtype" name="claimtype" class="claimheadsel form-control pull-left " onchange="showclaimform(this.value)" form="claimform" disabled>
								<option value="">--Select--</option>
								<?php
								$c=mysql_query("select ClaimId,ClaimName from claimtype where ClaimStatus='A'");
								while($cl=mysql_fetch_assoc($c)){
								?>
								<option value="<?=$cl['ClaimId']?>"><?=$cl['ClaimName']?></option>
								<?php
						  	}
								?>
							</select>
						</th>
					</tr>
				</thead>
				  
				  <tbody id="claimformbody">

					

				  </tbody>
				</table>
			</div>
			</center>
			
		</div>
		
	</div>
	
</div>




<?php
include "footer.php";
?>

<script type="text/javascript" src="js/claim.js"></script>

