<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	appInstructions(0, "Recently_Added_Events", "Recently Added Events", "The following are the fifty newest events added to " . CalName . ".<br><br><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td><img src=\"" . CalAdminRoot . "/images/iconEdit.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\"></td><td class=\"eventMain\">&nbsp;= Edit Event</td></tr></table>");
	$result = doQuery("	SELECT * 
							FROM " . HC_TblPrefix . "events 
						WHERE StartDate >= NOW() 
							AND IsActive = 1 
							AND IsApproved = 1
						ORDER BY PublishDate DESC, StartDate
						LIMIT 50");
?>
<br>
<table cellpadding="0" cellspacing="0" border="0">
	<?php
		if(hasRows($result)){
		?>
			<tr>
				<td class="eventMain" width="325"><b>Event</b></td>
				<td width="5">&nbsp;</td>
				<td class="eventMain" width="80"><b>Added</b></td>
				<td class="eventMain" width="80"><b>Occurs</b></td>
				<td colspan="3">&nbsp;</td>
			</tr>
		<?
			$cnt = 0;
			$curDate = "";
			while($row = mysql_fetch_row($result)){	?>
				<tr>
					<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>>
						<?echo cOut($row[1]);?>
					</td>
					<td <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>>&nbsp;</td>
					<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>>
						<?echo stampToDate(cOut($row[27]), "m/d/Y")?>
					</td>
					<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>>
						<?echo stampToDate(cOut($row[9]), "m/d/Y")?>
					</td>
					<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?> align="right">&nbsp;</td>
					<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><a href="<?echo CalAdminRoot;?>/index.php?com=eventedit&eID=<?echo $row[0];?>" class="main" onMouseOver="window.status = 'Edit Event: <?echo $row[1];?>'; return true;" onMouseOut="window.status = ''; return true;" title="Edit Event"><img src="<?echo CalAdminRoot;?>/images/iconEdit.gif" width="15" height="15" alt="" border="0" title="Edit Event"></a>&nbsp;&nbsp;</td>
					<?	if(!isset($_GET['mID'])){?>
					<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>>
						<?php
							if($row[18] == 1){
						?>
							<a href="<?echo CalAdminRoot;?>/index.php?com=eventbillboard" class="main"><img src="<?echo CalAdminRoot;?>/images/iconBillboard.gif" width="15" height="15" alt="" border="0"></a>&nbsp;&nbsp;
						<?
							} else {
						?>
							<img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="15" height="15" alt="" border="0">&nbsp;&nbsp;
						<?
							}//end if
						?>
								
					</td>
					<?	}//end if?>
				</tr>
				<tr><td colspan="7"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
			<?
			$cnt = $cnt + 1;
			}//end while
	} else {
	?>
	<tr>
		<td class="eventMain">
			<br>
			There are currently no events with views for this report.
		</td>
	</tr>
	<?php
	}//end if
	?>
</table>