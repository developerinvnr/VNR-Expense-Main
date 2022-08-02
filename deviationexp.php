<?php

define('HOST','localhost');
define('USER','expense_user'); 
define('PASS','expense@192'); 

define('USER2','hrims_user'); 
define('PASS2','hrims@192'); 
define('dbexpro','expense');
define('dbemp','hrims');  
define('CHARSET','utf8'); 
$con2=mysql_connect(HOST,USER2,PASS2) or die(mysql_error());
$empdb=mysql_select_db(dbemp, $con2) or die(mysql_error());
$con=mysql_connect(HOST,USER,PASS,true) or die(mysql_error());
$exprodb=mysql_select_db(dbexpro,$con) or die(mysql_error());
mysql_query("SET NAMES utf8");
date_default_timezone_set('Asia/Kolkata');

if($_REQUEST['act']=='resultexp')
{
 
 $xls_filename = 'Deviation_Reports_'.$_REQUEST['f'].'_'.$_REQUEST['t'].'.xls';
 header("Content-Type: application/xls");
 header("Content-Disposition: attachment; filename=$xls_filename");
 header("Pragma: no-cache"); header("Expires: 0"); $sep = "\t"; 
 echo "Sn\tEmployee\tClaim Type\tMonth\tFinance Date\tXeasy Amount\tFocus Amount\tDeviation Amount\tDocument No\tCost Center";
 print("\n");

//echo 'http://45.124.144.98:6868/focus/json?fdate='.$fd.'&tdate='.$td.'&year='.$yeard.'&eid='.$ei;
//echo 'http://192.168.3.250/focus/json?fdate='.$fd.'&tdate='.$td.'&year='.$yeard.'&eid='.$ei;
 
if($_REQUEST['ei']==0 OR $_REQUEST['ei']=='')
{ $json = file_get_contents('http://45.124.144.98:6868/focus/json?fdate='.$_REQUEST['fd'].'&tdate='.$_REQUEST['td'].'&year='.$_REQUEST['y']); }
else
{ $json = file_get_contents('http://45.124.144.98:6868/focus/json?fdate='.$_REQUEST['fd'].'&tdate='.$_REQUEST['td'].'&eid='.$_REQUEST['ei'].'&year='.$_REQUEST['y']); }

$obj = json_decode($json); 
 
 
if(isset($_REQUEST['u']) && $_REQUEST['u']!='' && $_REQUEST['u']!='ALL'){
	$ucond="x.CrBy=".$_REQUEST['u'];
}else{ $ucond="1=1"; }

if(isset($_REQUEST['f']) && isset($_REQUEST['t'])){
	$f=$_REQUEST['f']!='' ? date("Y-m-d",strtotime($_REQUEST['f'])) : date("Y-m-d");
	$t=$_REQUEST['t']!='' ? date("Y-m-d",strtotime($_REQUEST['t'])) : date("Y-m-d");
	
	if($_REQUEST['ff']=='B'){ $dtcond="x.BillDate between '".$f."' and '".$t."'"; }
	elseif($_REQUEST['ff']=='F'){ $dtcond="f.Finance_Date between '".$f."' and '".$t."'"; }
	else{ $dtcond="x.BillDate between '".$f."' and '".$t."'"; }
	
}else{ $dtcond="1=1"; }


$q="SELECT x.`ClaimId`,ClaimName,x.`ClaimMonth`,x.CrBy,sum(`FinancedTAmt`) as TotalAmount,x.FinancedDate, x.CrBy FROM `y".$_REQUEST['yi']."_expenseclaims` x inner join claimtype ct on x.ClaimId=ct.ClaimId inner join y".$_REQUEST['yi']."_monthexpensefinal f on (x.CrBy=f.EmployeeID AND x.ClaimMonth=f.Month) where x.`ClaimStatus`='Financed' AND x.FinancedBy>0 AND x.`FinancedDate`!='0000-00-00' AND x.`FinancedDate`!='1970-01-01' AND ".$ucond." and ".$dtcond." group by x.`ClaimId`, x.`ClaimMonth`, x.`CrBy` order by x.`CrBy`, x.`ClaimMonth` ";
	
$seleq=mysql_query($q);

$i=1;
while($exp=mysql_fetch_assoc($seleq))
  {
 
   $u=mysql_query("SELECT EmpCode,Fname,Sname,Lname,GradeId FROM `hrm_employee` e inner join hrm_employee_general g on e.EmployeeID=g.EmployeeID where e.EmployeeID=".$exp['CrBy'], $con2); $un=mysql_fetch_assoc($u); 
 
  if(($exp['ClaimId']!=19 AND $exp['ClaimId']!=20 AND $un['GradeId']<=66) OR $un['GradeId']>66 OR $_REQUEST['wda']==1)
  { 
  
  $schema_insert = "";
  $schema_insert .= $i.$sep;	
  
  
  
  $schema_insert .= $un['Fname'].' '.$un['Sname'].' '.$un['Lname'].$sep;
  $schema_insert .= $exp['ClaimName'].$sep;
  $schema_insert .= date("F",strtotime($exp['FinancedDate'])).$sep;
  $schema_insert .= date("d-m-Y",strtotime($exp['FinancedDate'])).$sep;
  $schema_insert .= $exp['TotalAmount'].$sep;
  
  $amount=0; $focusAmt=0;
  foreach($obj as $key =>$value)
  {
    $ei=$value->emp_id;
    $claimid=$value->claim_id;
    $month=$value->claim_month;
    $year=$value->year_id;
    $amount=$value->amount;
    $fin_date=date("d-m-Y",strtotime($value->created_at));
    
    //$ee=$ei.'=='.$exp['CrBy'].' && '.$claimid.'=='.$exp['ClaimId'].' && '.$month.'=='.$exp['ClaimMonth'].' && '.$year.'=='.$yeard;
    if($ei==$exp['CrBy'] && $claimid==$exp['ClaimId'] && $month==$exp['ClaimMonth'] && $year==$_REQUEST['y'])
    { $focusAmt=$amount; 
      $schema_insert .= $focusAmt.$sep; 
    } 
    
	 

  }
  $amount=0; $MinAmt=0;
  foreach($obj as $key =>$value)
  {
    $ei=$value->emp_id;
    $claimid=$value->claim_id;
    $month=$value->claim_month;
    $year=$value->year_id;
    $amount=$value->amount;
    
    $fin_date=date("d-m-Y",strtotime($value->created_at));
    if($ei==$exp['CrBy'] && $claimid==$exp['ClaimId'] && $month==$exp['ClaimMonth'] && $year==$_REQUEST['y'])
    { $MinAmt=$exp['TotalAmount']-$amount;  
      $schema_insert .= $MinAmt.$sep;    
      $schema_insert .= $value->document_no.$sep;
      $schema_insert .= $value->cost_center.$sep;
    } 
	

  }
   
  $schema_insert = str_replace($sep."$", "", $schema_insert);
  $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
  $schema_insert .= "\t";
  print(trim($schema_insert)); print "\n"; 
  
  $i++;
  } 
 
  } //while
 

} //if($_REQUEST['act']=='resultexp')

?>
