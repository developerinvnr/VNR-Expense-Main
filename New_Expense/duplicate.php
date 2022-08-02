<?php 
$Db1='vnrseed2_expense'; 
$Db2='vnrseed2_expense_nr'; 
$Db3='vnrseed2_expense_tl'; 

define('HOST','localhost'); 
define('USER','vnrseed2_hr'); 
define('PASS','vnrhrims321'); 
$y=3;
 
//$con1=mysql_connect(HOST,USER,PASS); $dbA=mysql_select_db($Db1, $con1);
$con2=mysql_connect(HOST,USER,PASS); $dbB=mysql_select_db($Db2, $con2);
//$con3=mysql_connect(HOST,USER,PASS); $dbC=mysql_select_db($Db3, $con3);
?>

<?php //AAA?>
<div class="container">
 <div class="row shadow">
  <div class="col-md-12">
   <div class="table-responsive">
			
 <?php if($_REQUEST['act']=='DelDupId' AND $_REQUEST['DupId']>0)
 { 
   $con1=mysql_connect(HOST,USER,PASS); $dbA=mysql_select_db($Db1, $con1);  
   $sqlDel=mysql_query("delete from y".$y."_monthexpensefinal where id=".$_REQUEST['DupId'],$con1); } ?>
 
  <table border="0">      
  <?php  $sql=mysql_query("SELECT COUNT(*) AS repetitions, `EmployeeID`, Month, `Status` FROM y".$y."_monthexpensefinal WHERE `YearId`=".$y." GROUP BY EmployeeID, Month HAVING repetitions >1 ORDER BY EmployeeID ASC, Month ASC, id ASC",$con1); while($res=mysql_fetch_assoc($sql)){ ?>
    <tr>
     <td colspan="6" style="font-size:14px;color:#FFFFFF;font-family:Georgia;"><?php echo 'Dup:&nbsp;'.$res['repetitions'].',&nbsp;Emp:&nbsp;'.$res['EmployeeID'].',&nbsp;Month:&nbsp;'.$res['Month']; ?></td>
    </tr>
    <tr>
	 <td align="left">
	  <table bgcolor="#FFF" border="1" cellspacing="0" cellspacing="1">
      <?php $sql2=mysql_query("select id,Month,Status,Crdate,DateOfSubmit from y".$y."_monthexpensefinal where EmployeeID=".$res['EmployeeID']." AND YearId=".$y." AND Month=".$res['Month']." order by id ASC",$con1);
while($res2=mysql_fetch_assoc($sql2)){ ?>		  
	   <tr>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['id']; ?></td>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['Month']; ?></td>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['Status']; ?></td>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['Crdate']; ?></td>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['DateOfSubmit']; ?></td>
		<td style="width:50px;font-size:12px;" align="center"><span style="cursor:progress"><img src="images/delete.png" onClick="javascript:window.location='duplicate.php?action=displayrec&v=&chkval=2&act=DelDupId&ern1=r114&ern2w=234&ern3y=10234&ern=4e2&erne=4e&ernw=234&erney=110022344&rernr=09drfGe&ernS=eewwqq&yAchQ=2&DupId=<?php echo $res2['id']; ?>'"/></span></td>
	   </tr>
       <?php } //while($res2=mysql_fetch_assoc($sql2)) ?>		   
	  </table>
	 </td>
	</tr>  
  <?php } //while($res=mysql_fetch_assoc($sql)) ?>		
  </table>
				
   </div>	
  </div>
 </div>
</div>
<?php //AAA?>



<?php //BBB?>
<div class="container">
 <div class="row shadow">
  <div class="col-md-12">
   <div class="table-responsive">
			
 <?php if($_REQUEST['act']=='Del2DupId' AND $_REQUEST['DupId']>0)
 { 
   $con2=mysql_connect(HOST,USER,PASS); $dbB=mysql_select_db($Db2, $con2);  
   $sqlDel=mysql_query("delete from y".$y."_monthexpensefinal where id=".$_REQUEST['DupId'],$con2); } ?>
 
  <table border="0">      
  <?php $sql=mysql_query("SELECT COUNT(*) AS repetitions, `EmployeeID`, Month, `Status` FROM y".$y."_monthexpensefinal WHERE `YearId`=".$y." GROUP BY EmployeeID, Month HAVING repetitions >1 ORDER BY EmployeeID ASC, Month ASC, id ASC",$con2); while($res=mysql_fetch_assoc($sql)){ ?>
    <tr>
     <td colspan="6" style="font-size:14px;color:#FFFFFF;font-family:Georgia;"><?php echo 'Dup:&nbsp;'.$res['repetitions'].',&nbsp;Emp:&nbsp;'.$res['EmployeeID'].',&nbsp;Month:&nbsp;'.$res['Month']; ?></td>
    </tr>
    <tr>
	 <td align="left">
	  <table bgcolor="#FFF" border="1" cellspacing="0" cellspacing="1">
      <?php $sql2=mysql_query("select id,Month,Status,Crdate,DateOfSubmit from y".$y."_monthexpensefinal where EmployeeID=".$res['EmployeeID']." AND YearId=".$y." AND Month=".$res['Month']." order by id ASC",$con2);
while($res2=mysql_fetch_assoc($sql2)){ ?>		  
	   <tr>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['id']; ?></td>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['Month']; ?></td>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['Status']; ?></td>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['Crdate']; ?></td>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['DateOfSubmit']; ?></td>
		<td style="width:50px;font-size:12px;" align="center"><span style="cursor:progress"><img src="images/delete.png" onClick="javascript:window.location='duplicate.php?action=displayrec&v=&chkval=2&act=Del2DupId&ern1=r114&ern2w=234&ern3y=10234&ern=4e2&erne=4e&ernw=234&erney=110022344&rernr=09drfGe&ernS=eewwqq&yAchQ=2&DupId=<?php echo $res2['id']; ?>'"/></span></td>
	   </tr>
       <?php } //while($res2=mysql_fetch_assoc($sql2)) ?>		   
	  </table>
	 </td>
	</tr>  
  <?php } //while($res=mysql_fetch_assoc($sql)) ?>		
  </table>
				
   </div>	
  </div>
 </div>
</div>
<?php //BBB?>



<?php //CCC ?>
<div class="container">
 <div class="row shadow">
  <div class="col-md-12">
   <div class="table-responsive">
			
 <?php if($_REQUEST['act']=='Del3DupId' AND $_REQUEST['DupId']>0)
 { 
   $con3=mysql_connect(HOST,USER,PASS); $dbC=mysql_select_db($Db3, $con3);  
   $sqlDel=mysql_query("delete from y".$y."_monthexpensefinal where id=".$_REQUEST['DupId'],$con3); } ?>
 
  <table border="0">      
  <?php $sql=mysql_query("SELECT COUNT(*) AS repetitions, `EmployeeID`, Month, `Status` FROM y".$y."_monthexpensefinal WHERE `YearId`=".$y." GROUP BY EmployeeID, Month HAVING repetitions >1 ORDER BY EmployeeID ASC, Month ASC, id ASC",$con3); while($res=mysql_fetch_assoc($sql)){ ?>
    <tr>
     <td colspan="6" style="font-size:14px;color:#FFFFFF;font-family:Georgia;"><?php echo 'Dup:&nbsp;'.$res['repetitions'].',&nbsp;Emp:&nbsp;'.$res['EmployeeID'].',&nbsp;Month:&nbsp;'.$res['Month']; ?></td>
    </tr>
    <tr>
	 <td align="left">
	  <table bgcolor="#FFF" border="1" cellspacing="0" cellspacing="1">
      <?php $sql2=mysql_query("select id,Month,Status,Crdate,DateOfSubmit from y".$y."_monthexpensefinal where EmployeeID=".$res['EmployeeID']." AND YearId=".$y." AND Month=".$res['Month']." order by id ASC",$con3);
while($res2=mysql_fetch_assoc($sql2)){ ?>		  
	   <tr>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['id']; ?></td>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['Month']; ?></td>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['Status']; ?></td>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['Crdate']; ?></td>
		<td style="width:100px;font-size:12px;" align="center"><?php echo $res2['DateOfSubmit']; ?></td>
		<td style="width:50px;font-size:12px;" align="center"><span style="cursor:progress"><img src="images/delete.png" onClick="javascript:window.location='duplicate.php?action=displayrec&v=&chkval=2&act=Del3DupId&ern1=r114&ern2w=234&ern3y=10234&ern=4e2&erne=4e&ernw=234&erney=110022344&rernr=09drfGe&ernS=eewwqq&yAchQ=2&DupId=<?php echo $res2['id']; ?>'"/></span></td>
	   </tr>
       <?php } //while($res2=mysql_fetch_assoc($sql2)) ?>		   
	  </table>
	 </td>
	</tr>  
  <?php } //while($res=mysql_fetch_assoc($sql)) ?>		
  </table>
				
   </div>	
  </div>
 </div>
</div>
<?php //CCC?>


<script type="text/javascript" src="js/slab.js"></script>

