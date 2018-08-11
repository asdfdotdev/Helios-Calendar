<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(!isset($_GET['mID'])){
		appInstructions(0, "Most Popular Active Calendar Events", "The following events are the most viewed active events on the calendar.<br><br><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td><img src=\"" . CalAdminRoot . "/images/iconEdit.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\"></td><td class=\"eventMain\">&nbsp;= Edit Event</td></tr><tr><td colspan=\"2\"><img src=\"" . CalAdminRoot . "/images/spacer.gif\" width=\"1\" height=\"7\" alt=\"\" border=\"0\"></td></tr><tr><td><img src=\"" . CalAdminRoot . "/images/iconBillboard.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\"></td><td class=\"eventMain\">&nbsp;= Event Appears on Billboard (<i>Click to Administer Billboard</i>)</td></tr></table>");
		$result = doQuery("	SELECT * 
								FROM " . HC_TblPrefix . "events 
							WHERE StartDate >= NOW() 
								AND IsActive = 1 
								AND IsApproved = 1
								AND Views > 0
							ORDER BY Views DESC, StartDate
							LIMIT 50");
	} else {
		appInstructions(0, "Most Popular Active Mobile Events", "The following events are the most viewed active events on the mobile calendar.<br><br><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td><img src=\"" . CalAdminRoot . "/images/iconEdit.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\"></td><td class=\"eventMain\">&nbsp;= Edit Event</td></tr></table>");
		$result = doQuery("	SELECT * 
								FROM " . HC_TblPrefix . "events 
							WHERE StartDate >= NOW() 
								AND IsActive = 1 
								AND IsApproved = 1 
								AND MViews > 0
							ORDER BY MViews DESC, StartDate
							LIMIT 50");
	}//end if
?>
<br>
<table cellpadding="0" cellspacing="0" border="0">
	<?php
		if(hasRows($result)){
		?>
			<tr>
				<td class="eventMain" width="325"><b>Event</b></td>
				<td width="5">&nbsp;</td>
				<td class="eventMain" width="80"><b>Occurs</b></td>
				<td class="eventMain"><b>Views</b></td>
				<td colspan="3">&nbsp;</td>
			</tr>
		<?
			$cnt = 0;
			$curDate = "";
			while($row = mysql_fetch_row($result)){
				if(isset($_GET['mID'])){
					$viewCount = $row[34];
				} else {
					$viewCount = $row[28];
				}//end if
				
				if($viewCount == 0){
					break;
				}//end if
				?>
				<tr>
					<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>>
						<?echo $row[1];?>
					</td>
					<td <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>>&nbsp;</td>
					<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>>
						<?	$datepart = split("-",$row[9]);
							$datestamp = date("m/d/Y", mktime(0,0,0,$datepart[1],$datepart[2],$datepart[0]));
							echo $datestamp;	?>
					</td>
					<td class="eventMain" <?if($cnt % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?> align="right"><?echo $viewCount;?></td>
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