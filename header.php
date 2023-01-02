<?php
session_start();

if(!isset($_SESSION['login'])){
  session_destroy();
  header('location:index.php');
}

include 'config.php';

if($_SESSION['EmpCode']>0)
{
$qry=mysql_query("select EmployeeID,EmpStatus,DateOfSepration from hrm_employee where EmpCode=".$_SESSION['EmpCode']." and EmpStatus!='De' and CompanyId=".$_SESSION['CompanyId'],$con2); $rqry=mysql_fetch_assoc($qry);
if($rqry['EmpStatus']=='D' AND $rqry['DateOfSepration']>='2022-05-01')
 {
  $DatAcc=date("Y-m-d",strtotime($rqry['DateOfSepration'].'+15 day'));
 }
 else
 {
  $qryV=mysql_query("select Emp_RelievingDate from hrm_employee_separation where EmployeeID=".$rqry['EmployeeID'],$con2); $rqryV=mysql_fetch_assoc($qryV); 
  $DatAcc=date("Y-m-d",strtotime($rqryV['Emp_RelievingDate'].'+15 day'));    
 }


 if($rqry['EmpStatus']=='A')
 {
  $num=1;       
 }
 elseif($rqry['EmpStatus']=='D' AND $DatAcc!='0000-00-00' && $DatAcc!='1970-01-01')
 {
  $run_qry=mysql_query("select * from hrm_employee where EmpCode=".$_SESSION['EmpCode']." and CompanyId=".$_SESSION['CompanyId']." and EmpStatus='D' AND '".date("Y-m-d")."'<='".$DatAcc."'",$con2);  
  $num=mysql_num_rows($run_qry); 
 }
 else{ $num=0; }
}
else
{
 $num=1;   
} 

if(isset($_COOKIE["login"]) && !empty($_COOKIE["login"]) && $num==1)
{
    
  $_SESSION['login']=$_COOKIE['login'];
  $_SESSION['EmployeeID']=$_COOKIE['EmployeeID'];
  $_SESSION['EmpCode']=$_COOKIE['EmpCode'];
  $_SESSION['Fname']=$_COOKIE['Fname'];
  $_SESSION['EmpRole']=$_COOKIE['EmpRole'];
  $_SESSION['CompanyId']=$_COOKIE['CompanyId'];
  $_SESSION['CheckLogin']=$_COOKIE['CheckLogin'];
  $_SESSION['FYearId']=$_COOKIE['FYearId'];
  
  $sely=mysql_query("SELECT * FROM `financialyear` where YearId=".$_SESSION['FYearId'],$con);
  $selyd=mysql_fetch_assoc($sely); $FYearId=$_SESSION['FYearId'];
  $_SESSION['FYear']=$selyd['Year'];
  $_SESSION['todayDate']=date("Y-m-d");
  $_SESSION['todayMonth']=date("m",strtotime(date('Y-m-d')));
  
  setcookie("login", $_SESSION['login'], time() + (86400 * 10), "/");
  setcookie("EmployeeID", $_SESSION['EmployeeID'], time() + (86400 * 10), "/");
  setcookie("EmpCode", $_SESSION['EmpCode'], time() + (86400 * 10), "/");
  setcookie("Fname", $_SESSION['Fname'], time() + (86400 * 10), "/");
  setcookie("EmpRole", $_SESSION['EmpRole'], time() + (86400 * 10), "/");
  setcookie("CompanyId", $_SESSION['CompanyId'], time() + (86400 * 10), "/");
  setcookie("FYearId", $_SESSION['FYearId'], time() + (86400 * 10), "/");
  setcookie("FYear", $_SESSION['FYear'], time() + (86400 * 10), "/");
  setcookie("CheckLogin", $_SESSION['CheckLogin'], time() + (86400 * 10), "/");
   
}
else
{
 echo "<script>window.location.href = 'logout.php?msg=Please Login Again&msgcolor=danger'</script>";
}


/*
if(isset($_COOKIE["login"]) && !empty($_COOKIE["login"]))
{
  $sely=mysql_query("SELECT * FROM `financialyear` where status='Active'",$con);
  $selyd=mysql_fetch_assoc($sely); $FYearId=$selyd['YearId'];

  $_SESSION['FYearId']=$selyd['YearId'];
  $_SESSION['FYear']=$selyd['Year'];
  $_SESSION['todayDate']=date("Y-m-d");
  $_SESSION['todayMonth']=date("m",strtotime(date('Y-m-d')));
}
*/

?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="images/faviconexpro.png" type="image/png" sizes="16x16">
<title>Xeasy</title>
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://unpkg.com/gijgo@1.9.11/css/gijgo.min.css" rel="stylesheet" type="text/css" />
<link href="https://fonts.googleapis.com/css?family=Lemonada:400,700" rel="stylesheet">
<link rel="stylesheet" href="css/jquery.datetimepicker.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css"> -->
<script type="text/javascript">//window.history.forward(1);</script>

<?php 
$dir='/expense/';
// echo $_SERVER['PHP_SELF'];
// echo '<br>'.$dir."home.php";
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-light">
  <a class="navbar-brand font-weight-bold" href="home.php" style="font-family: 'Lemonada', cursive;">
    <img src="images/xeasytransparentlogo.png" style="width: 40px;cursor: pointer;">
  </a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>


  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">

      <li class="nav-item <?php if($_SERVER['PHP_SELF']==$dir."home.php"){echo 'active';}?>">
        <?php if(date("m")==1){$m=12;}else{$m=date("m")-1;} $m=''; ?>
        <a class="nav-link" href="home.php?action=displayrec&v=<?=$m?>&chkval=2">Home</a>
      </li>
	  
      <?php if($_SESSION['EmpRole']=='S'){ ?>
      <li class="nav-item <?php if($_SERVER['PHP_SELF']==$dir."users.php"){echo 'active';}?>" >
        <a class="nav-link" href="users.php">Users</a>
      </li>
      <li class="nav-item <?php if($_SERVER['PHP_SELF']==$dir."employees.php"){echo 'active';}?>" >
        <a class="nav-link" href="employees.php">Employees</a>
      </li>
      <?php } ?>
      
	  <?php if($_SESSION['EmpRole']=='E' || $_SESSION['EmpRole']=='A'){ ?>
      <li class="nav-item <?php if($_SERVER['PHP_SELF']==$dir."claim.php"){echo 'active';}?>">
        <a class="nav-link" href="claim.php">Claim</a>
      </li>
      <?php } ?>
      
      <?php /*?><li class="nav-item <?php if($_SERVER['PHP_SELF']==$dir."reports.php"){echo 'active';}?>">
        <a class="nav-link" href="reports.php">Report</a>
      </li><?php */?>
	  
	  <!--sssssssssssssssssssssssssssssss-->
	  <?php if($_SESSION['EmpRole']!='E' AND $_SESSION['EmpRole']!='A' AND ($_SESSION['CompanyId']==1 OR $_SESSION['CompanyId']==4 OR ($_SESSION['CompanyId']==3 && $_SESSION['EmpRole']!='M'))){ ?>
	  
	  <li class="nav-item dropdown  <?php if($_SERVER['PHP_SELF']==$dir."reports.php"){echo 'active';}?>">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Report
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php if($_SESSION['EmpRole']!='E' && $_SESSION['EmpRole']!='A'){?>
		   <a class="dropdown-item" href="reports.php">Claim Report</a>
		  <?php } ?>
          
		  <?php if($_SESSION['EmpRole']=='S' OR $_SESSION['EmpRole']=='P' OR $_SESSION['EmpRole']=='AA' OR $_SESSION['EmpRole']=='OO'){ ?>
		  
		   <?php if($_SESSION['EmpRole']=='S' OR $_SESSION['EmpRole']=='P' OR $_SESSION['EmpRole']=='AA'){ ?>
           <a class="dropdown-item" href="dailyfillup.php">Daily Activity</a>
           <a class="dropdown-item" href="claimstage.php">Claim Stage</a>
           
           <?php } ?>
           
           <a class="dropdown-item" href="claim2stage.php?&v=2&chkval=2&yi=<?=$_SESSION['FYearId']?>&ee=we23&er=1013&rr=wew101">Claim Stage - 2</a>
           <a class="dropdown-item" href="employeests.php">Employee Status</a>
           <a class="dropdown-item" href="deactive2claim.php">Deactivate Calim </a>
           <a class="dropdown-item" href="deactiveclaim.php">Deactivate Calim (After Filled)</a>
           
           
           <?php if($_SESSION['EmpRole']=='S' OR $_SESSION['EmpRole']=='P' OR $_SESSION['EmpRole']=='AA'){ ?>
           <a class="dropdown-item" href="claimamount.php">Claim Amount</a>
           
           <a class="dropdown-item" href="toprating.php">Top Rating [Same Day Upload]</a>
           <a class="dropdown-item" href="deviation.php">Deviation Reports (Xeasy->Focuse)</a>
           <?php } ?>
           
          <?php } ?>
        </div>
      </li>
      
      <?php } ?>
	  <!--eeeeeeeeeeeeeeeeeeeeeeeeeeeeeee-->
     
      <?php if($_SESSION['EmpRole']=='S' OR $_SESSION['EmpRole']=='P'){ ?>
      <li class="nav-item <?php if($_SERVER['PHP_SELF']==$dir."uploadclaiam.php"){echo 'active';}?>">
        <a class="nav-link" href="uploadclaim.php">Upload Claim</a>
      </li> 
	  <?php } ?>
      
      <li class="nav-item <?php if($_SERVER['PHP_SELF']==$dir."help.php"){echo 'active';}?>">
        <a class="nav-link" href="help.php">Help</a>
      </li> 

      <?php if($_SESSION['EmpRole']=='S' OR $_SESSION['EmpRole']=='P'){ ?>
      <li class="nav-item dropdown  <?php if($_SERVER['PHP_SELF']==$dir."financialyearset.php"){echo 'active';}?>">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Settings</a>
        
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <?php if($_SESSION['EmpRole']=='S'){ ?>    
          <a class="dropdown-item" href="financialyearset.php">Financial Year Set</a>
          <a class="dropdown-item" href="claimmaster.php">Claim Master</a>
          <a class="dropdown-item" href="helpedit.php">Help Edit</a>
          <a class="dropdown-item" href="#">Another action</a>
        <?php } ?>  
		<?php if($_SESSION['EmpRole']=='S' OR $_SESSION['EmpRole']=='P'){ ?>
          <a class="dropdown-item" href="vehicleslabmas.php">Vehicle Slab Masters</a>
          <a class="dropdown-item" href="mappingslab.php?d=0">Mapping Vehicle Slab</a>
          <a class="dropdown-item" href="openmonth.php?d=0">Open Month</a>
        <?php } ?>  
          
        </div>
      </li>
      <?php } ?>
    </ul>
    <ul class="navbar-nav my-2 my-lg-0">
      <li style="color:white;font-size: 13px;">
        Financial Year: <?=$_SESSION['FYear']?>&emsp;
      </li>
    </ul>
    <ul class="navbar-nav my-2 my-lg-0">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?=$_SESSION['Fname']?>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
          
          <a class="dropdown-item" href="profile.php">Profile</a>
          
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="logout.php">Log Out <i class="fa fa-sign-out" aria-hidden="true"></i></a>
        </div>
      </li>
      
    </ul>
    
  </div>
</nav>




