<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_POST['sID'])){
		$sID = $_POST['sID'];
	} else {
		$sID = 0;
	}//end if
	
	if($sID == 1){
		appInstructions(1, "Editing_Events", "Event Edit Search Results", "Select an event from the list below to edit. To delete an event click the checkbox to the right of the event listing than click the 'Delete Marked' button below.<br><br><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td><img src=\"" . CalAdminRoot . "/images/icons/iconEdit.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\"></td><td class=\"eventMain\">&nbsp;=&nbsp;Edit Event</td></tr><tr><td><img src=\"" . CalAdminRoot . "/images/spacer.gif\" width=\"1\" height=\"4\" alt=\"\" border=\"0\"></td></tr><tr><td><img src=\"" . CalAdminRoot . "/images/icons/iconNewWindow.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\"></td><td class=\"eventMain\">&nbsp;=&nbsp;Edit Event In New Window</td></tr></table>");
	} elseif($sID == 2){
		appInstructions(1, "Delete_Event", "Delete Events Search Results", "Select events from the list below by clicking the checkbox beside the listing. Once you have made your selection(s) click the 'Delete Selected' button below to delete the events.");
	} elseif($sID == 3){
		appInstructions(1, "Create_Series", "Create Series Search Results", "Select events from the list below by clicking the checkbox beside the listing. Once you have made your selection(s) click the 'Create Event Series' button below to create the series.");
	} else {
		appInstructions(1, "Event_Activity_Report", "Event Report Search Results", "Select events from the list below by clicking the checkbox beside the listing. Once you have made your selection(s) click the 'Generate Report' button below.");
	}//end if
?>
<script language="JavaScript">
function chkFrm(){
	if(validateCheckArray('eventSearchResult','eventID[]',1) == 1){
		alert('No events selected.\nPlease select at least one event and try again.');
		return false;
<?	if($sID == 1){	?>
	} else {
		if(confirm('Editing events as a group will begin with the first selected event. All others will be updated to that events details.\nAre you sure you want to edit the selected events as a group?\n\n          Ok = YES Edit the Selected Events as a Group\n\n          Cancel = NO Do Not Continue')){
			return true;
		} else {
			return false;
		}//end if
<?	} elseif($sID == 2){	?>
	} else {
		if(confirm('Event Delete Is Permanent!\nAre you sure you want to delete the selected event(s)\n\n          Ok = YES Delete Event(s)\n          Cancel = NO Don\'t Delete Event(s)')){
			return true;
		} else {
			return false;
		}//end if
<?	} elseif($sID == 3){	?>
	} else {
		if(confirm('Creating an event series will remove the selected events from any other series they may be a part of. Are you sure you want to create a new series with the selected events?\n\n          Ok = YES Create New Event Series With the Selected Events\n          Cancel = NO Do Not Continue')){
			return true;
		} else {
			return false;
		}//end if
<?	}//end if	?>
	
	}//end if
	
	return true;
}//end chkFrm()
</script>
<br>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<?php
		$startDate = htmlspecialchars($_POST['startDate']);
		$endDate = htmlspecialchars($_POST['endDate']);
		$keyword = htmlspecialchars($_POST['keyword']);
		
		$startPart = split("/", $startDate);
		$endPart = split("/", $endDate);
		
		$startDate = $startPart[2] . "-" . $startPart[0] . "-" . $startPart[1];
		$endDate = $endPart[2] . "-" . $endPart[0] . "-" . $endPart[1];
		
		if(isset($_POST['catID'])){
			$catID = $_POST['catID'];
			$catIDWhere = "";
			$cnt = 0;
			foreach ($catID as $val){
				$catIDWhere = $catIDWhere . $val . ",";
				$cnt = $cnt + 1;
			}//end while
			$catIDWhere = substr($catIDWhere, 0, strlen($catIDWhere) - 1);
		}//end if
		
		
		
		$query = "	SELECT DISTINCT " . HC_TblPrefix . "events.*
					FROM " . HC_TblPrefix . "events 
						INNER JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
					WHERE StartDate BETWEEN '" . $startDate . "' AND '" . $endDate . "' 
									AND Title LIKE('%" . cIn($keyword) . "%')
									AND IsActive = 1 
									AND IsApproved = 1 ";
					
				if(isset($catIDWhere)){
					$query = $query . " AND (" . HC_TblPrefix . "eventcategories.CategoryID In(" . cIn($catIDWhere) . "))";
				}//end if
				
				if($_POST['city'] != ''){
					$query = $query . " AND " . HC_TblPrefix . "events.LocationCity = '" . $_POST['city'] . "'";
				}//end if
				
		$query = $query . " ORDER BY StartDate, TBD, StartTime, Title";
		$result = doQuery($query);
		$row_cnt = mysql_num_rows($result);
		
		if($row_cnt > 0){
			if($sID == 1){	?>
			<form name="eventSearchResult" id="eventSearchResult" method="post" action="<?echo CalAdminRoot;?>/index.php?com=eventedit" onSubmit="return chkFrm();">
		<?	} elseif($sID == 2) {?>
			<form name="eventSearchResult" id="eventSearchResult" method="post" action="<?echo CalAdminRoot;?>/components/EventDelete.php" onSubmit="return chkFrm();">
		<?	} elseif($sID == 3) {?>
			<form name="eventSearchResult" id="eventSearchResult" method="post" action="<?echo CalAdminRoot;?>/components/EventCreateSeries.php" onSubmit="return chkFrm();">
		<?	} else {?>
			<form name="eventSearchResult" id="eventSearchResult" method="post" action="<?echo CalAdminRoot;?>/index.php?com=eventviewdetail" onSubmit="return chkFrm();">
		<?	}//end if	
			
			if($row_cnt > 1){?>
				<div align="right">
					[ <a class="main" href="javascript:;" onClick="checkAllArray('eventSearchResult', 'eventID[]');">Select All</a> 
					&nbsp;|&nbsp; <a class="main" href="javascript:;" onClick="uncheckAllArray('eventSearchResult', 'eventID[]');">Deselect All</a> ]
				</div>
		<?	}//end if
			$cnt = 0;
			$css = 0;
			$curDate = "";
			$curID = "";
			while( $row = mysql_fetch_row($result)){
				if(($curDate != $row[9]) or ($cnt == 0)){
					$curDate = $row[9];
					
					if($cnt > 0){
				?>
					<tr><td colspan="7"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr><td colspan="7" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
					<tr><td colspan="7"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr><td colspan="7"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0"></td></tr>
				<?
					}//end if
				?> 
					<tr>
						<td class="eventMain" colspan="7">
							<b><?php
								$css = 0;
								$datepart = split("-",$row[9]);
								$datestamp = date("l, F jS Y", mktime(0,0,0,$datepart[1],$datepart[2],$datepart[0]));
								echo $datestamp;
							?></b>
						</td>
					</tr>
					<tr><td colspan="7"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
					<tr><td colspan="7" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
					<tr><td colspan="7"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
				<?
				}//end if	?>
				<tr>
					<td width="120" class="eventMain" <?if($css % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>>
						
						<?php
							//check for valid start time
							if($row[10] != ''){
								$timepart = split(":", $row[10]);
								$startTime = date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
							} else {
								$startTime = "";
							}//end if
							
							//check for valid end time
							if($row[12] != ''){
								$timepart = split(":", $row[12]);
								$endTime = '-' . date("h:i A", mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
							} else {
								$endTime = "";
							}//end if
								
							//check for valid start time
							if($row[11] == 0){
								echo $startTime . $endTime;
							} elseif($row[11] == 1) {
								echo "<i>All Day Event</i>";
								
							} elseif($row[11] == 2) {
								echo "<i>TBA</i>";
								
							}//end if
						?>
					</td>
					<td width="10" <?if($css % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="20" alt="" border="0"></td>
					<td class="eventMain" <?if($css % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>>
				<?	if($sID == 1){	?>
						<a href="<?echo CalAdminRoot;?>/index.php?com=eventedit&eID=<?echo $row[0];?>" class="main"><?echo cOut($row[1]);?></a>
				<?	} else {	?>
						<?echo cOut($row[1]);?>
				<?	}//end if	?>
					</td>
				<?	if($sID == 1){
				?>
						<td align="right" width="40" <?if($css % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><a href="<?echo CalAdminRoot;?>/index.php?com=eventedit&eID=<?echo $row[0];?>" class="main" onMouseOver="window.status = 'Edit Event: <?echo $row[1];?>'; return true;" onMouseOut="window.status = ''; return true;" title="Edit Event"><img src="<?echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0" title="Edit Event"></a>&nbsp;&nbsp;</td>
						<td <?if($css % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><a href="<?echo CalAdminRoot;?>/index.php?com=eventedit&eID=<?echo $row[0];?>" class="main" onMouseOver="window.status = 'Edit Event In New Window: <?echo $row[1];?>'; return true;" onMouseOut="window.status = ''; return true;" title="Edit Event In New Window" target="_blank" title="Edit Event In New Window"><img src="<?echo CalAdminRoot;?>/images/icons/iconNewWindow.gif" width="15" height="15" border="0" alt="Edit Event In New Window"></a>&nbsp;&nbsp;</td>
						<td <?if($css % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><input type="checkbox" name="eventID[]" id="eventID_<?echo $row[0];?>" value="<?echo $row[0];?>"></td>
				<?
					} else {
				?>
						<td <?if($css % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?> width="30" align="right"><input type="checkbox" name="eventID[]" id="eventID_<?echo $row[0];?>" value="<?echo $row[0];?>"></td>
				<?
					}//end if
				?>
					<td <?if($css % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="3" height="1" alt="" border="0"><br></td>
				</tr>
			<?
				$css = $css + 1;
				$cnt = $cnt + 1;
			}//end while
			?>
			<tr><td colspan="7"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
			<tr><td colspan="7" class="eventSeparator"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="100%" height="1" alt="" border="0"></td></tr>
			<tr><td colspan="7"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
			<tr>
				<td colspan="7" align="right" class="eventMain">
				<?	if($row_cnt > 1){?>
						<div align="right">
							[ <a class="main" href="javascript:;" onClick="checkAllArray('eventSearchResult', 'eventID[]');">Select All</a> 
							&nbsp;|&nbsp; <a class="main" href="javascript:;" onClick="uncheckAllArray('eventSearchResult', 'eventID[]');">Deselect All</a> ]
						</div>
				<?	}//end if	?>
					<br>
				<?	if($sID == 1){?>
					<input type="submit" name="submit" id="submit" value="Edit Selected As Group" class="button">
				<?	} elseif($sID == 2){?>
					<input type="submit" name="submit" id="submit" value="Delete Selected" class="button">
				<?	} elseif($sID == 3){?>
					<input type="submit" name="submit" id="submit" value="Create Event Series" class="button">
				<?	} else {?>
					<input type="submit" name="submit" id="submit" value="Generate Event Report" class="button">
				<?	}//end if?>
				<br><br>
				</td>
			</tr>
			</form>
			<?
	} else {
	?>
	<tr>
		<td class="eventMain">
			There are no events that meet that search criteria.<br>
			<a href="<?echo CalAdminRoot;?>/index.php?com=eventsearch&sID=<?echo $sID;?>" class="main">Please click here to search again.</a>
			<br><br>
		</td>
	</tr>
	<?php
	}//end if
	?>
</table>