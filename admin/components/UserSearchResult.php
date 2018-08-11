<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	$query = "	SELECT *
				FROM " . HC_TblPrefix . "users
				WHERE IsRegistered = 1";
	
	if(isset($_POST['name'])){
		$query = $query . " AND LastName LIKE('%" . $_POST['name'] . "%')";
	}//end if
	
	if(isset($_POST['email'])){
		$query = $query . " AND Email LIKE('%" . $_POST['email'] . "%')";
	}//end if
	
	$query = $query . " ORDER BY LastName, FirstName";
	
	$result = doQuery($query);
	$row_num = mysql_num_rows($result);
	
	if($row_num > 0){
	?>
<script language="JavaScript">
function doDelete(dID){
	if(confirm('User Delete Is Perminant!\nAre you sure you want to delete the user?\n\n          Ok = YES Delete User\n          Cancel = NO Don\'t Delete User')){
		document.location.href = '<?echo CalAdminRoot . "/" . HC_UserEditAction;?>?dID=' + dID;
	}//end if
}//end doDelete
</script>
<?php
	appInstructions(1, "Alert Recipient Search Results", "Select an alert recipient from below to edit or delete.");
?>
<br>
<div align="right"><a href="<?echo CalAdminRoot . "/index.php?com=useredit";?>" class="main">&laquo;&laquo;Search Again</a></div>

	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="eventMain" width="140"><b>Name</b></td>
			<td class="eventMain" width="250"><b>Email</b></td>
			<td class="eventMain">&nbsp;</td>
			<td class="eventMain">&nbsp;</td>
			<td class="eventMain">&nbsp;</td>
		</tr>
		<tr><td colspan="5"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
		<tr><td colspan="5" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
		<tr><td colspan="5"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<?
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
		?>
			<tr>
				<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><?echo $row[1] . " " . $row[2];?></td>
				<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><?echo $row[3];?></td>
				<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><a href="<?echo CalAdminRoot;?>/index.php?com=useredit&uID=<?echo $row[0];?>" class="main" onMouseOver="window.status = 'Edit User: <?echo $row[1];?>'; return true;" onMouseOut="window.status = ''; return true;" title="Edit Event"><img src="<?echo CalAdminRoot;?>/images/iconEdit.gif" width="15" height="15" alt="" border="0" title="Edit User: <?echo $row[1];?>"></a>&nbsp;&nbsp;</td>
				<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><a href="javascript:doDelete('<?echo $row[0];?>');" onMouseOver="window.status = 'Delete User: <?echo $row[1];?>'; return true;" onMouseOut="window.status = ''; return true;" class="main" title="Delete Event"><img src="<?echo CalAdminRoot;?>/images/iconDelete.gif" width="15" height="15" alt="" border="0" title="Delete User: <?echo $row[1];?>"></a></td>
				<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="5" height="1" alt="" border="0"></td>
			</tr>
		<?
			$cnt++;
		}//end while
	?>
	<tr><td colspan="5"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<tr><td colspan="5" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
	<tr><td colspan="5"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
	<?
	} else {
	?>
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="eventMain">
				There are no users that meet that search criteria.<br>
				<a href="<?echo CalAdminRoot;?>/index.php?com=usersearch" class="main">Please click here to search again.</a>
				<br><br>
			</td>
		</tr>
	<?
	}//end if
?>
	</table>