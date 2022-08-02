
<!-- 
ALTER TABLE `y1_expenseclaims` ADD `BillDate` DATE NOT NULL AFTER `CrDate`;

UPDATE y1_expenseclaims
SET BillDate = (
  SELECT BillDate
  FROM y1_g1_expensefilldata
  WHERE y1_g1_expensefilldata.Expid = y1_expenseclaims.Expid
);

UPDATE y1_expenseclaims
SET BillDate = (
  SELECT BillDate
  FROM y1_g2_expensefilldata
  WHERE y1_g2_expensefilldata.Expid = y1_expenseclaims.Expid
);

UPDATE y1_expenseclaims
SET BillDate = (
  SELECT BillDate
  FROM y1_g3_expensefilldata
  WHERE y1_g3_expensefilldata.Expid = y1_expenseclaims.Expid
);

UPDATE y1_expenseclaims
SET BillDate = (
  SELECT BillDate
  FROM y1_g4_expensefilldata
  WHERE y1_g4_expensefilldata.Expid = y1_expenseclaims.Expid
);

UPDATE y1_expenseclaims
SET BillDate = (
  SELECT BillDate
  FROM y1_g5_expensefilldata
  WHERE y1_g5_expensefilldata.Expid = y1_expenseclaims.Expid
);
 -->

<?php
error_reporting(E_ALL);
include 'config.php';
// $ad=mysql_query("ALTER TABLE `y1_expenseclaims` ADD `BillDate` DATE NOT NULL AFTER `CrDate`");
// if($ad){echo 'added BillDate';}



$up=mysql_query("UPDATE y1_expenseclaims SET BillDate = (SELECT BillDate FROM y1_g1_expensefilldata WHERE y1_g1_expensefilldata.Expid = y1_expenseclaims.Expid )"); 
if($up){echo 'updated BillDate<br><br>';}
$up=mysql_query("UPDATE y1_expenseclaims SET BillDate = (SELECT BillDate FROM y1_g2_expensefilldata WHERE y1_g2_expensefilldata.Expid = y1_expenseclaims.Expid )"); 
if($up){echo 'updated BillDate<br><br>';}
$up=mysql_query("UPDATE y1_expenseclaims SET BillDate = (SELECT BillDate FROM y1_g3_expensefilldata WHERE y1_g3_expensefilldata.Expid = y1_expenseclaims.Expid )"); 
if($up){echo 'updated BillDate<br><br>';}
$up=mysql_query("UPDATE y1_expenseclaims SET BillDate = (SELECT BillDate FROM y1_g4_expensefilldata WHERE y1_g4_expensefilldata.Expid = y1_expenseclaims.Expid )"); 
if($up){echo 'updated BillDate<br><br>';}
$up=mysql_query("UPDATE y1_expenseclaims SET BillDate = (SELECT BillDate FROM y1_g5_expensefilldata WHERE y1_g5_expensefilldata.Expid = y1_expenseclaims.Expid )"); 
if($up){echo 'updated BillDate<br><br>';}


?>