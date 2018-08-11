<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
?>
<script langauge="JavaScript">
function doDelete(eID){
	if(confirm('Are you sure you want to remove this billboard event?\n\n          Ok = YES Remove Event\n          Cancel = NO Don\'t Remove Event')){
		window.location.href='<?echo CalAdminRoot . "/" . HC_EventBillboardAction;?>?eID=' + eID;
	}//end if
}//end doDelete
</script>
<?php 
if (isset($_GET['msg'])){
	switch ($_GET['msg']){
		case "1" :
			feedback(1,"Event Removed From Billboard Successfully!");
			break;
			
	}//end switch
}//end if

$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsBillboard = 1 AND StartDate >= NOW() ORDER BY StartDate, Views DESC");

if(hasRows($result)){
	?>
	
	<?php
		appInstructions(0, "Billboard_Events", "Billboard Events", "<img src=\"" . CalAdminRoot . "/images/iconEdit.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\"> = Edit Event<br><img src=\"" . CalAdminRoot . "/images/iconDelete.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\"> = Remove Event From Billboard");
	?>
	
	<br>
	<table cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td width="300" class="eventMain"><b>Event Title</b></td>
			<td width="90" class="eventMain"><b>Event Date</b></td>
			<td width="40" class="eventMain"><b>Views</b></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr><td colspan="6" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
		<tr><td colspan="6"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
		<tr>
	<?php
		$cnt = 0;
		while($row = mysql_fetch_row($result)){
		?>
			<tr>
				<td class="eventMain" <?if($cnt % 2 == 0){echo "bgcolor=\"#EEEEEE\"";}//end if?>>&nbsp;<a href="<?echo CalAdminRoot;?>/index.php?com=eventedit&eID=<?echo $row[0];?>" class="main"><?echo cOut($row[1]);?></a></td>
				<td class="eventMain" <?if($cnt % 2 == 0){echo "bgcolor=\"#EEEEEE\"";}//end if?>>
					<?php
						$datepart = split("-",$row[9]);
						$datestamp = date("m/d/Y", mktime(0,0,0,$datepart[1],$datepart[2],$datepart[0]));
						echo $datestamp;
					?>
				</td>
				<td class="eventMain" <?if($cnt % 2 == 0){echo "bgcolor=\"#EEEEEE\"";}//end if?> align="right"><?echo cOut($row[28]);?>&nbsp;&nbsp;&nbsp;</td>
				<td class="eventMain" <?if($cnt % 2 == 0){echo "bgcolor=\"#EEEEEE\"";}//end if?> align="right"><a href="<?echo CalAdminRoot;?>/index.php?com=eventedit&eID=<?echo $row[0];?>" class="main" title="Edit Event"><img src="<?echo CalAdminRoot;?>/images/iconEdit.gif" width="15" height="15" alt="" border="0"></a>&nbsp;&nbsp;</td>
				<td class="eventMain" <?if($cnt % 2 == 0){echo "bgcolor=\"#EEEEEE\"";}//end if?>><a href="javascript:doDelete('<?echo $row[0];?>');" class="main" title="Remove From Billboard"><img src="<?echo CalAdminRoot;?>/images/iconDelete.gif" width="15" height="15" alt="" border="0"></a></td>
				<td class="eventMain" <?if($cnt % 2 == 0){echo "bgcolor=\"#EEEEEE\"";}//end if?>><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="5" height="1" alt="" border="0"><br></td>
			</tr>
			<tr><td colspan="6" <?if($cnt % 2 == 0){echo "bgcolor=\"#EEEEEE\"";}//end if?>><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="2" alt="" border="0"></td></tr>
		<?
			$cnt++;
		}//end while
	?>
		</tr>
		<tr><td colspan="6" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
		<tr><td class="eventMain" colspan="6"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="15" alt="" border="0"></td></tr>
	</table>
<?
} else {
?>
	<b>There are currently no events assigned to the billboard.</b>
<?
}//end if
?>