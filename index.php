<?php
session_start();
if(isset($_COOKIE["login"]) && !empty($_COOKIE["login"]))
{
  $_SESSION['login']=$_COOKIE['login'];
  $_SESSION['EmployeeID']=$_COOKIE['EmployeeID'];
  $_SESSION['EmpCode']=$_COOKIE['EmpCode'];
  $_SESSION['Fname']=$_COOKIE['Fname'];
  $_SESSION['EmpRole']=$_COOKIE['EmpRole'];
  $_SESSION['CompanyId']=$_COOKIE['CompanyId'];
 
  setcookie("login", $_SESSION['login'], time() + (86400 * 10), "/");
  setcookie("EmployeeID", $_SESSION['EmployeeID'], time() + (86400 * 10), "/");
  setcookie("EmpCode", $_SESSION['EmpCode'], time() + (86400 * 10), "/");
  setcookie("Fname", $_SESSION['Fname'], time() + (86400 * 10), "/");
  setcookie("EmpRole", $_SESSION['EmpRole'], time() + (86400 * 10), "/"); 
  setcookie("CompanyId", $_SESSION['CompanyId'], time() + (86400 * 10), "/");
}

if(isset($_SESSION['login']))
{ if(date("m")==1){$m=12;}else{$m=date("m")-1;} $m='';
  header('location:home.php?action=displayrec&v='.$m.'&chkval=2'); exit();
}

  //header('location:home.php'); exit();

?>
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css?family=Lemonada:400,700" rel="stylesheet">
<meta name="viewport" content="width=device-width, initial-scale=1">

<style type="text/css">
body
{
 /*background-color: #626d65!important;*/ /* fallback for old browsers */ /* Chrome 10-25, Safari 5.1-6 */
 background: #159957; background: -webkit-linear-gradient(to right, #155799, #159957);  
 background: linear-gradient(to right, #155799, #159957); 
 /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
}
#formdiv{ background-color: #e3e3e3; padding: 30px; margin:10px; border-radius: 10px; }
#title{ margin-top: 0 auto; color: #fff;font-size: 70px;font-weight: bold;padding: 20px;float: right; font-weight:bold;font-family: 'Lemonada', cursive;text-align: center; }

@media screen and (max-width: 800px) 
{ #title{float: left;padding-bottom: 0px;margin-bottom: -250px; }
  #infotbl{font-size: 12px; } 
}
label{font-weight: 600;}
</style>

<div class="container">

 <div class="h-100 row align-items-center">
  <div class="col-md-6">
   <div id="title"><img src="images/xeasytransparentlogo.png" style="width:50%;"><br></div>
  </div>
  <div class="col-md-6">
   <div class="row align-items-center">
    <div class="col-md-8" id="formdiv">
    <?php if(isset($_REQUEST['msg'])){ ?>
	 <span class="btn btn-<?=$_REQUEST['msgcolor'];?>"><?=$_REQUEST['msg'];?></span>
	<?php } ?>

    <form action="login.php" method="post">
    <div class="form-group">
	 <!--<label for="exampleInputEmail1">Company</label>-->
	 <select type="text" class="form-control" id="empcompany" name="empcompany" autofocus required>
	  <option value="">Select Company</option>
	  <option value="1">VNR SEEDS PVT. LTD.</option>
	  <option value="3">VNR NURSERY PVT. LTD.</option>
	  <option value="4">TEAMLEASE SERVICES LTD.</option>
	 </select>
    </div>  
    <div class="form-group">
	 <!--<label for="exampleInputEmail1">Login-Id</label>-->
	  <input type="text" class="form-control" id="empcode" name="empcode" autofocus placeholder="Login-Id" required>
    </div>
    <div class="form-group">
	 <!--<label for="exampleInputPassword1">Password</label>-->
	 <input type="password" class="form-control" id="emppass" name="emppass" value="" placeholder="Password" required>
    </div>
    <div class="form-group">
	 <!--<label for="exampleInputYear">Year</label>-->
	 <select type="text" class="form-control" id="empyear" name="empyear" autofocus required>
	   <option value="4" selected>2022-2023</option>
	   <option value="3">2021-2022</option>
	   <option value="2">2020-2021</option>
	 </select>
    </div>  
    <div class="form-group" style="text-align:center;">
	 <button type="submit" class="btn btn-primary" style="width:70%;">Submit</button>
	</div>
                        
   </form>

    </div>
   </div>
  </div>
 
 </div>

</div>

<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
crossorigin="anonymous"></script>
<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>


