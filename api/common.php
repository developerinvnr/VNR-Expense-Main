<?php date_default_timezone_set('Asia/Kolkata');
include 'config.php';



if($_REQUEST['value'] == ''){ echo json_encode(array("msg" => "Parameter Missing!") ); }
 
//Company
elseif($_REQUEST['value'] == 'companylist')
{    
 $run_qry=mysqli_query($con2,"select CompanyId as ComId,CompanyName as ComName from hrm_company_basic where Status='A' order by CompanyId asc");
 $num=mysqli_num_rows($run_qry); $carray = array();
 if($num>0)
 {
  while($res=mysqli_fetch_assoc($run_qry)){ $carray[]=$res; }
  echo json_encode(array("Code" => "300", "company_list" => $carray) ); 
 }
 else{ echo json_encode(array("Code" => "100", "msg" => "Error!") ); }  
}




//Last
else
{
 echo json_encode(array("Code" => "100", "msg" => "Invalid value!") );
}

 
