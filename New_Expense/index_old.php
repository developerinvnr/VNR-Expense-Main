<?php

session_start();

if(isset($_COOKIE["login"]) && !empty($_COOKIE["login"])){
  $_SESSION['login']=$_COOKIE['login'];
  $_SESSION['EmployeeID']=$_COOKIE['EmployeeID'];
  $_SESSION['EmpCode']=$_COOKIE['EmpCode'];
  $_SESSION['Fname']=$_COOKIE['Fname'];
  $_SESSION['EmpRole']=$_COOKIE['EmpRole'];
  //$_SESSION['FYearId']=$_COOKIE['FYearId'];
  //$_SESSION['FYear']=$_COOKIE['FYear'];
  //$_SESSION['todayDate']=$_COOKIE['todayDate'];
  //$_SESSION['todayMonth']=$_COOKIE['todayMonth'];
 
 
  setcookie("login", $_SESSION['login'], time() + (86400 * 30), "/");
   setcookie("EmployeeID", $_SESSION['EmployeeID'], time() + (86400 * 30), "/");
   setcookie("EmpCode", $_SESSION['EmpCode'], time() + (86400 * 30), "/");
   setcookie("Fname", $_SESSION['Fname'], time() + (86400 * 30), "/");

   setcookie("EmpRole", $_SESSION['EmpRole'], time() + (86400 * 30), "/");
   //setcookie("FYearId", $_SESSION['FYearId'], time() + (86400 * 30), "/");
   //setcookie("FYear", $_SESSION['FYear'], time() + (86400 * 30), "/");
   //setcookie("todayDate", $_SESSION['todayDate'], time() + (86400 * 30), "/");
   //setcookie("todayMonth", $_SESSION['todayMonth'], time() + (86400 * 30), "/");
   
   //header('location:home.php'); exit();
  
   

}


if(isset($_SESSION['login'])){
  // session_destroy();
  header('location:home.php'); exit();
}



include 'config.php';
?>
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css?family=Lemonada:400,700" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1">

<style type="text/css">
	body{
		/*background-color: #626d65!important;*/
		      background: #159957;  /* fallback for old browsers */
background: -webkit-linear-gradient(to right, #155799, #159957);  /* Chrome 10-25, Safari 5.1-6 */
background: linear-gradient(to right, #155799, #159957); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

	}
	#formdiv{
		background-color: #e3e3e3;
		padding: 30px;
		margin:10px;
		border-radius: 10px;
	}
	#title{
		margin-top: 0 auto;
		color: #fff;
		font-size: 70px;
		font-weight: bold;
		padding: 20px;
		float: right;
		font-weight:bold;font-family: 'Lemonada', cursive;
		text-align: center;

	}

	@media screen and (max-width: 800px) {
	  #title{
	    float: left;
	    padding-bottom: 0px;
	    margin-bottom: -250px;
	  }
	  #infotbl{
	  	font-size: 12px;
	  }
	}
	label{
		font-weight: 600;
	}
</style>

        <?php /*
	<table id="infotbl" style="position: fixed;top: 0px;left: 0px; background-color: #ffffe6;">
	<tr><td>admin</td>    <td>:</td> <td>admin</td></tr>
	<tr><td>claimer</td>	 <td>:</td> <td>123457</td></tr>
	<tr><td>mediator</td> <td>:</td> <td>mediator</td></tr>
	<tr><td>verifier</td> <td>:</td> <td>verifier</td></tr>
	<tr><td>approver</td> <td>:</td> <td>123460</td></tr>
	<tr><td>finance</td>  <td>:</td> <td>finance</td></tr>
	<tr><td>pass</td>     <td>:</td> <td>hellohello</td></tr>
	</table>
        */ ?>



<div class="container">
	<div class="h-100 row align-items-center">
		<div class="col-md-6">
			<div id="title">
				<img src="images/xeasytransparentlogo.png" style="width:50%;">
				<br>
				<br>
			</div>
			
		</div>
		<div class="col-md-6">
			<div class="row align-items-center">
				
				<div class="col-md-8" id="formdiv">
					<?php
					if(isset($_REQUEST['msg'])){
						?>
						<span class="btn btn-<?=$_REQUEST['msgcolor'];?>">
							<?=$_REQUEST['msg'];?>
						</span>
						<?php
					}
					?>
					<form action="login.php" method="post">
					  <div class="form-group">
					    <label for="exampleInputEmail1">Login-Id</label>
					    <input type="text" class="form-control" id="empcode" name="empcode" autofocus>
					    
					  </div>
					  <div class="form-group">
					    <label for="exampleInputPassword1">Password</label>
					    <input type="password" class="form-control" id="emppass" name="emppass" value="">
					  </div>

                                          <div class="form-group">
					    <!--<label for="exampleInputPassword1">Year</label><br>-->
						<?php $curMonth  = date('m');
						      if($curMonth >= 04){
						    		$curFYearId = date('Y').'-'.date('Y',strtotime('+1 year'));
						    	}else{
						    		$curFYearId = date('Y',strtotime('-1 year')).'-'.date('Y');
						    	}
						    	$sely=mysql_query("SELECT * FROM `financialyear` where status='Active'",$con);
							  	$selyd=mysql_fetch_assoc($sely); $FYearId=$selyd['YearId']; ?>
						<input type="hidden" class="form-control" name="FYear" value="<?=$FYearId?>">	
					   <!-- &nbsp;
					    <label>April to March</label>-->
					  </div>
					  
					  <button type="submit" class="btn btn-primary">Submit</button>
                                          <?php /*
					  <a href="forgetpass.php">
					  <small style="float: right;" class="form-text text-muted pull-right">Forget Password ?</small>
					  </a>
                                          */ ?>
					</form>
					
				</div>
				
			</div>
		</div>
	</div>
	
	
	
</div>








<script
        src="https://code.jquery.com/jquery-3.3.1.js"
        integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
        crossorigin="anonymous"></script>
        
        <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>


