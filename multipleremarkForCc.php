<div style="background-color: #F3F4F9;width: 100%;max-height:60px;overflow: scroll;padding:0px;border-radius: 4px;border: 2px solid #E2E4E5;font-size: 12px;">
	
	<span id="chatarea">
	<?php
	$myname=''; $othname='';
	$sr = mysql_query("select * from y".$_SESSION['FYearId']."_expenseremark where ExpId='".$exp['ExpId']."' order by rid asc");
	while($srd = mysql_fetch_assoc($sr)){

		if($_SESSION['EmpRole']=='M'){
			if($srd['ByRole']=='M'){
			$selmy=mysql_fetch_assoc(mysql_query("select Fname,Sname,Lname from hrm_user  where EmployeeID=".$_SESSION['EmployeeID']));
			$myname=$selmy['Fname'].' '.$selmy['Sname'].' '.$selmy['Lname'];
			}else{

			$seloth=mysql_fetch_assoc(mysql_query("select Fname,Sname,Lname from ".dbemp.".hrm_employee where EmployeeID=".$srd['By']));
			$othname=$seloth['Fname'].' '.$seloth['Sname'].' '.$seloth['Lname'];
			}

		}else{
			if($srd['ByRole']!='M'){
			$selmy=mysql_fetch_assoc(mysql_query("select Fname,Sname,Lname from ".dbemp.".hrm_employee  where EmployeeID=".$_SESSION['EmployeeID']));
			$myname=$selmy['Fname'].' '.$selmy['Sname'].' '.$selmy['Lname'];
			}else{
			$seloth=mysql_fetch_assoc(mysql_query("select Fname,Sname,Lname from hrm_user where EmployeeID=".$srd['By']));
			$othname=$seloth['Fname'].' '.$seloth['Sname'].' '.$seloth['Lname'];
			}
		}

		if($srd['By'] == $_SESSION['EmployeeID']){
			?>
			<div style="width: 80%;background-color: #DAE1E7;float: right;padding:4px;margin:4px;border-radius: 4px;border: 1px solid #E2E4E5;font-size: 12px;">
				<span style="font-size: 11px;color:#0469D9;"><?=$myname?><br></span>
				<?=$srd['Remark']?>
				
				<span style="float: right;font-size: 11px;color:#757575;"><br><?=date("d-m-Y H:i",strtotime($srd['CrDateTime']))?></span>
			</div>
			<?php
		}else{
			?>
			<div style="width: 80%;background-color: #ffffff;float: left;padding:4px;margin:4px;border-radius: 4px;border: 1px solid #E2E4E5;font-size: 12px;">
				<span style="font-size: 11px;color:#0469D9;"><?=$othname?><br></span>
				<?=$srd['Remark']?>
				<span style="float: right;font-size: 11px;color:#757575;"><br><?=date("d-m-Y H:i",strtotime($srd['CrDateTime']))?></span>
			</div>
			<?php
		}

	}
	?>
	</span>
	

	<script type="text/javascript">
		function addnr(expid,id,emprole,myname,othname) {
			var r = $("#nrem").val();
			// alert(r);
			$.post("addremarkajax.php",{act:"addremark",expid:expid,byid:id,remark:r,emprole:emprole},function(data){
				// console.log(data);
				//$('#claimformbody').html(data);
				if(data.includes('added')){
					// alert('added');
					// $("#chatarea").html('');
					$.post("addremarkajax.php",{act:"refreshChatArea",expid:expid,myname:myname,othname:othname,sessionid:id},function(data){
						// console.log(data);
							$("#chatarea").html(data);
							$("#nrem").val('');
					});

				}
			});

		}

		function onChatPress() {
		    var key = window.event.keyCode;

		    // If the user has pressed enter
		    if (key === 13) {
		        $("#chatbtn").click();
		    }
		    
		}
	</script>

	
	
</div>