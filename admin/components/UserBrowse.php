<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['uID']) && is_numeric($_GET['uID'])){
		$uID = $_GET['uID'];
		$where = "Edit";
	} else {
		$uID = 0;
		$where = "Add";
		
		if(isset($_GET['name'])){
			$name = $_GET['name'];
		} else {
			$name = "";
		}//end if
		
		if(isset($_GET['email'])){
			$email = $_GET['email'];
		} else {
			$email = "";
		}//end if
		
	}//end if
?>
<script language="JavaScript">
function doDelete(dID){
	if(confirm('Newsletter Recipient Delete Is Perminant!\nAre you sure you want to delete?\n\n          Ok = YES Delete Recipient\n          Cancel = NO Don\'t Delete Recipient')){
		document.location.href = '<?echo CalAdminRoot . "/components/UserEditAction.php";?>?dID=' + dID;
	}//end if
}//end doDelete
</script>
<?php
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1, "User Deleted Successfully!");
				break;
				
		}//end switch
	}//end if
	
	appInstructions(0, "Newsletter_Recipients", "Newsletter Recipients", "Select the recipient below you wish to edit or delete.");
?>
<br>
<div align="right"><a href="<?echo CalAdminRoot;?>/index.php?com=useredit" class="main">&laquo;&laquo;Add Newsletter Recipient</a></div>
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign="top" class="eventMain">
			<?php 
				$result = doQuery("SELECT * FROM " . HC_TblPrefix  . "users WHERE IsRegistered = 1 ORDER BY LastName, FirstName");
				
				if(hasRows($result)){
					$firstname = cOut(mysql_result($result,0,1));
					$lastname = cOut(mysql_result($result,0,2));
					$email = cOut(mysql_result($result,0,3));
					$password = cOut(mysql_result($result,0,4));
					$email = cOut(mysql_result($result,0,2));
				?>
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td class="eventMain" width="150"><b>Name</b></td>
						<td class="eventMain" width="250"><b>Email</b></td>
						<td class="eventMain" colspan="2">&nbsp;</td>
					</tr>
					<tr><td colspan="4"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr><td colspan="4" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
					<tr><td colspan="4"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<?php
						mysql_data_seek($result,0);
						$cnt = 0;
						while($row = mysql_fetch_row($result)){
						?>
							<tr><td colspan="4" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>
							<tr>
								<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>>&nbsp;<?echo cOut($row[1]) . " " . cOut($row[2]);?></td>
								<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><?echo cOut($row[3]);?></td>
								<td <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?> align="right"><a href="<?echo CalAdminRoot;?>/?com=useredit&uID=<?echo cOut($row[0]);?>" class="main" title="Edit User"><img src="<?echo CalAdminRoot;?>/images/iconEdit.gif" width="15" height="15" title="Edit User" alt="" border="0"></a>&nbsp;&nbsp;</td>
								<td <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><a href="javascript:;" onclick="javascript:doDelete('<?echo cOut($row[0]);?>'); return false;" class="main" title="Delete User"><img src="<?echo CalAdminRoot;?>/images/iconDelete.gif" width="15" height="15"  title="Delete User" alt="" border="0"></a>&nbsp;</td>
							</tr>
							<tr><td colspan="4" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>
						<?
							$cnt++;
						}//end while
					?>
					<tr><td colspan="4"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr><td colspan="4" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
					<tr><td colspan="4"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				</table>
			<?	} else {	?>
				There are currently no registered newsletter recipients available.
			<?	}//end if	?>
		</td>
	</tr>
</table>