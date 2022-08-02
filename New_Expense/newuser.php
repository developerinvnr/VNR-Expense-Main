<?php
session_start();
if(!isset($_SESSION['login'])){
  session_destroy();
  header('location:index.php');
}
include 'config.php';
?>

<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="css/style.css">


<div class="table-responsive">
	<table class="table table-sm shadow minpadtable">
	  <thead class="thead-dark">
	    <tr>
	      <th scope="col" colspan="2">
		      Details
		      
			  <button  type="button" id="savebtn" class="btn btn-sm btn-outline-light pull-right vsmbtn " onclick="savenewuser()">
			      <i class="fa fa-save" aria-hidden="true"></i> Save
			  </button>
			  <div id="editsts" class="pull-right"></div>
		  </th>
	      
	    </tr>
	  </thead>

	  
	  <tbody>
	  	<tr>
	      <th scope="row" colspan="2">
		      Name
		      <input  class="form-control namesinp" type="text" id="Fnameinp" value="" placeholder="First Name">
		      <input  class="form-control namesinp" type="text" id="Snameinp" value="" placeholder="Second Name">
		      <input  class="form-control namesinp" type="text" id="Lnameinp" value="" placeholder="Last Name">
		  </th>
	      
	    </tr>

	    <tr>
	      <th scope="row">Login Id</th>
	      <td><input  class="form-control" type="text" id="EmpCodeinp" value=""></td>
		</tr>
		<tr>
	      <th scope="row">Password</th>
	      <td><input  class="form-control" type="password" id="EmpPassinp" value=""></td>
		</tr>
		<tr>
	      <th scope="row">User Role</th>
	      <td>
	      	<select disabled class="form-control" id="EmpRoleinp">
	      		<option></option>
	      		<option value="S">Admin</option>
	      		<option value="M">Mediator</option>
				<option value="V">Verifier</option>
	      		<option value="F">Finance</option>
	      	</select>
	      </td>
		</tr>
		<tr>
	      <th scope="row">Status</th>
	      <td>
	      	<select  class="form-control" id="EmpStatusinp">
	      	
	      		<option value="A" >Active</option>
	      		<option value="D" >Deactive</option>
	      	</select>
	      	
	      </td>
		</tr>
		<tr>
	    </tr>
		<tr>
	      <th scope="row">Email</th>
	      <td><input  class="form-control" type="email" id="EmailIdinp" value=""></td>
		</tr>
		<tr>
	    </tr>
	    <tr>
	      <th scope="row">Mobile</th>
	      <td><input  class="form-control" type="text" id="MobileNoinp" value="" onkeypress="return isNumber(event)" maxlength="10"></td>
		</tr>
		<tr>
	    </tr>
	  </tbody>
	</table>
</div>


<script
        src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
        
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>

<script src="https://unpkg.com/gijgo@1.9.11/js/gijgo.min.js" type="text/javascript"></script>

<script type="text/javascript" src="js/user.js"></script>