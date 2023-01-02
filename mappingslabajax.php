<?php 
session_start();
include 'config.php';

if($_POST['act']=='MappedSlab' && $_POST['slb']!='' && $_POST['ei']>0)
{ 


 $sql=mysql_query("select * from vehicle_policyslab_employee where EmployeeID=".$_POST['ei']);
 $row=mysql_num_rows($sql);
 if($row==0)
 {
  $upins=mysql_query("insert into vehicle_policyslab_employee(EmployeeID, VPId, CrBy, CrDate, SysDate) values(".$_POST['ei'].", ".$_POST['slb'].", ".$_SESSION['EmployeeID'].", '".date("Y-m-d")."', '".date("Y-m-d")."')");
 }
 else
 {
  $upins=mysql_query("update vehicle_policyslab_employee set VPId=".$_POST['slb'].", CrBy=".$_SESSION['EmployeeID'].", CrDate='".date("Y-m-d")."', SysDate='".date("Y-m-d")."' where EmployeeID=".$_POST['ei']);
 }
 
 if($upins){echo 'Done';}
 
 
}
elseif($_POST['act']=='CheckKM' && $_POST['slb']>0 && $_POST['ei']>0 && $_POST['bd']!='' && $_POST['th']!='')
{ 
 
 $m=date("m",strtotime($_POST['bd'])); $y=date("Y",strtotime($_POST['bd']));
 $y1=$_POST['y1']; $y2=$_POST['y2'];
 
 $sql=mysql_query("select * from vehicle_policyslab sb inner join vehicle_policyslab_employee sbe on sb.VPId=sbe.VPId where sbe.EmployeeID=".$_POST['ei']." AND sb.2w4w=".$_POST['th']);
 $row=mysql_num_rows($sql);
 if($row>0)
 {
  
  /*
  $res=mysql_fetch_assoc($sql); $applym=$res['ApplyForMonth'];
  if($m==4){ $sm=04; $em=04; }elseif($m==1){ $sm=12; $em=12; }else{ $sm=$m-1; $em=$sm; }
  $slm=strlen($sm); if($slm==1){ $sm='0'.$sm; } $elm=strlen($em); if($elm==1){ $em='0'.$em; }
  
  if($sm==4 || $sm==6 || $sm==9 || $sm==11){ $ds=$y1."-".$sm."-01"; $de=$y1."-".$em."-30"; }
  elseif($sm==5 || $sm==7 || $sm==8 || $sm==10 || $sm==12){ $ds=$y1."-".$sm."-01"; $de=$y1."-".$em."-31"; }
  elseif($sm==1){ $ds=$y1."-".$sm."-01"; $de=$y2."-".$em."-31"; }
  elseif($sm==2){ $ds=$y2."-".$sm."-01"; $de=$y2."-".$em."-29"; }
  elseif($sm==3){ $ds=$y2."-".$sm."-01"; $de=$y2."-".$em."-31"; }  
  */
  
  /************* New open**************/
  $res=mysql_fetch_assoc($sql); $applym=$res['ApplyForMonth'];
  if($applym==1){ $ds=$y."-".$m."-01"; $de=$y."-".$m."-31"; }
  elseif($applym==2)
  {
   if($m==4){ $sm=04; $em=04; }elseif($m==1){ $sm=12; $em=01; }else{ $sm=$m-1; $em=$m; }
   $slm=strlen($sm); if($slm==1){ $sm='0'.$sm; } $elm=strlen($em); if($elm==1){ $em='0'.$em; } 
   
   if($m==4 || $m==6 || $m==9 || $m==11){ $ds=$y1."-".$sm."-01"; $de=$y1."-".$em."-30"; }
   elseif($m==5 || $m==7 || $m==8 || $m==10 || $m==12){ $ds=$y1."-".$sm."-01"; $de=$y1."-".$em."-31"; }
   elseif($m==1){ $ds=$y1."-".$sm."-01"; $de=$y2."-".$em."-31"; }
   elseif($m==2){ $ds=$y2."-".$sm."-01"; $de=$y2."-".$em."-29"; }
   elseif($m==3){ $ds=$y2."-".$sm."-01"; $de=$y2."-".$em."-31"; }  
  }
  elseif($applym==3)
  {
   if($m==4){ $sm=04; $em=04; }elseif($m==5){ $sm=04; $em=05; }elseif($m==6){ $sm=04; $em=06; }
   elseif($m==1){ $sm=11; $em=01; }elseif($m==2){ $sm=12; $em=02; }else{ $sm=$m-2; $em=$m; }
   $slm=strlen($sm); if($slm==1){ $sm='0'.$sm; } $elm=strlen($em); if($elm==1){ $em='0'.$em; }
   if($m==4 || $m==6 || $m==9 || $m==11){ $ds=$y1."-".$sm."-01"; $de=$y1."-".$em."-30"; }
   elseif($m==5 || $m==7 || $m==8 || $m==10 || $m==12){ $ds=$y1."-".$sm."-01"; $de=$y1."-".$em."-31"; }
   elseif($m==1){ $ds=$y1."-".$sm."-01"; $de=$y2."-".$em."-31"; }
   elseif($m==2){ $ds=$y1."-".$sm."-01"; $de=$y2."-".$em."-29"; }
   elseif($m==3){ $ds=$y2."-".$sm."-01"; $de=$y2."-".$em."-31"; }  
  }
  elseif($applym==4)
  {
   if($m==4){ $sm=04; $em=04; }elseif($m==5){ $sm=04; $em=05; }elseif($m==6){ $sm=04; $em=06; }
   elseif($m==7){ $sm=04; $em=07; }elseif($m==1){ $sm=10; $em=01; }elseif($m==2){ $sm=11; $em=02; }
   elseif($m==3){ $sm=12; $em=03; }else{ $sm=$m-3; $em=$m; }
   $slm=strlen($sm); if($slm==1){ $sm='0'.$sm; } $elm=strlen($em); if($elm==1){ $em='0'.$em; }
   if($m==4 || $m==6 || $m==9 || $m==11){ $ds=$y1."-".$sm."-01"; $de=$y1."-".$em."-30"; }
   elseif($m==5 || $m==7 || $m==8 || $m==10 || $m==12){ $ds=$y1."-".$sm."-01"; $de=$y1."-".$em."-31"; }
   elseif($m==1){ $ds=$y1."-".$sm."-01"; $de=$y2."-".$em."-31"; }
   elseif($m==2){ $ds=$y1."-".$sm."-01"; $de=$y2."-".$em."-29"; }
   elseif($m==3){ $ds=$y1."-".$sm."-01"; $de=$y2."-".$em."-31"; }
  }
  elseif($applym==6)
  {
   if($m==4){ $sm=04; $em=04; }elseif($m==5){ $sm=04; $em=05; }elseif($m==6){ $sm=04; $em=06; }
   elseif($m==7){ $sm=04; $em=07; }elseif($m==8){ $sm=04; $em=08; }elseif($m==9){ $sm=04; $em=09; }
   elseif($m==1){ $sm=08; $em=01; }elseif($m==2){ $sm=09; $em=02; }elseif($m==3){ $sm=10; $em=03; }
   else{ $sm=$m-5; $em=$m; }
   $slm=strlen($sm); if($slm==1){ $sm='0'.$sm; } $elm=strlen($em); if($elm==1){ $em='0'.$em; }
   if($m==4 || $m==6 || $m==9 || $m==11){ $ds=$y1."-".$sm."-01"; $de=$y1."-".$em."-30"; }
   elseif($m==5 || $m==7 || $m==8 || $m==10 || $m==12){ $ds=$y1."-".$sm."-01"; $de=$y1."-".$em."-31"; }
   elseif($m==1){ $ds=$y1."-".$sm."-01"; $de=$y2."-".$em."-31"; }
   elseif($m==2){ $ds=$y1."-".$sm."-01"; $de=$y2."-".$em."-29"; }
   elseif($m==3){ $ds=$y1."-".$sm."-01"; $de=$y2."-".$em."-31"; }
  }
  elseif($applym==12){ $ds=$y1."04-01"; $de=$y2."03-31"; }
  
  /************* New close**************/
  
  $subQ="inner join y".$_SESSION['FYearId']."_expenseclaims clm on w.ExpId=clm.ExpId where clm.ClaimStatus!='Deactivate' AND clm.CrBy=".$_POST['ei'];
  
  
  $sS=mysql_query("select SUM(Totalkm) as tot from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($ds)."' AND '".date($de)."' "); $rR=mysql_fetch_assoc($sS);
  
  if($rR['tot']<$res['Slab1_t']){ $tot=$res['Slab1_t']-$rR['tot']; }else{ $tot=0; }
  
  if($m==4 || $m==6 || $m==9 || $m==11){ $ds1=$y1."-".$m."-01"; $de1=$y1."-".$m."-30"; }
  elseif($m==5 || $m==7 || $m==8 || $m==10 || $m==12){ $ds1=$y1."-".$m."-01"; $de1=$y1."-".$m."-31"; }
  elseif($m==1){ $ds1=$y1."-".$m."-01"; $de1=$y2."-".$m."-31"; }
  elseif($m==2){ $ds1=$y2."-".$m."-01"; $de1=$y2."-".$m."-29"; }
  elseif($m==3){ $ds1=$y2."-".$m."-01"; $de1=$y2."-".$m."-31"; } 
  
  $sS1=mysql_query("select SUM(Totalkm) as tot from y".$_SESSION['FYearId']."_24_wheeler_entry w ".$subQ." AND JourneyStartDt between '".date($ds1)."' AND '".date($de1)."' "); $rR1=mysql_fetch_assoc($sS1);
  
  $totKm=$tot+$rR1['tot'];
  
  if($totKm>=$res['Slab1_f'] && $totKm<=$res['Slab1_t']){ $rate=$res['Slab1_rate']; }
  elseif($totKm>=$res['Slab2_f'] && $totKm<=$res['Slab2_t']){ $rate=$res['Slab2_rate']; }
  elseif($totKm>=$res['Slab3_f'] && $totKm<=$res['Slab3_t']){ $rate=$res['Slab3_rate']; }
  elseif($totKm>=$res['Slab4_f'] && $totKm<=$res['Slab4_t']){ $rate=$res['Slab4_rate']; }
  else{ $rate=$_POST['rate']; }
   
   //echo 'aa2='.$rate;
 }
 else{ $rate=$_POST['rate']; }
 
 echo trim($rate);
 
 //echo '<input type="hidden" id="rateN" value='.floatval($rate).' />';
 
 
}

?>
