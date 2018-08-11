<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_POST['sID'])){
		$sID = $_POST['sID'];
	} else {
		$sID = 0;
	}//end if
	
	if($sID == 0){
		appInstructions(1, "Editing_Events", "Event Edit Search Results", "Select an event from the list below to edit. To delete an event click the checkbox to the right of the event listing than click the 'Delete Marked' button below.<br><br><table cellpadding=\"0\" cellspacing=\"0\" border=\"0\"><tr><td><img src=\"" . CalAdminRoot . "/images/iconEdit.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\"></td><td class=\"eventMain\">&nbsp;=&nbsp;Edit Event</td></tr><tr><td><img src=\"" . CalAdminRoot . "/images/spacer.gif\" width=\"1\" height=\"4\" alt=\"\" border=\"0\"></td></tr><tr><td><img src=\"" . CalAdminRoot . "/images/iconNewWindow.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\"></td><td class=\"eventMain\">&nbsp;=&nbsp;Edit Event In New Window</td></tr></table>");
	} else {
		appInstructions(1, "Event_Activity_Report", "Event Report Search Results", "Select events from the list below by clicking the checkbox beside the listing. Once you have made your selection(s) click the 'Generate Report' button below.");
	}//end if
?>
<script language="JavaScript">
function chkFrm(){
	if(validateCheckArray('eventSearchResult','eventID[]',1) == 1){
		alert('No events selected.\nPlease select at least one event and try again.');
		return false;
<?	if($sID == 0){	?>
	} else {
		if(confirm('Event Delete Is Permanent!\nAre you sure you want to delete marked event(s)\n\n          Ok = YES Delete Event(s)\n          Cancel = NO Don\'t Delete Event(s)')){
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
		
		
		
		$query = "	SELECT " . HC_TblPrefix . "events.*, " . HC_TblPrefix . "eventcategories.CategoryID as Category
					FROM " . HC_TblPrefix . "events 
						INNER JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "eventcategories.EventID)
					WHERE StartDate BETWEEN '" . $startDate . "' AND '" . $endDate . "' 
									AND Title LIKE('%" . cIn($keyword) . "%') 
									AND IsActive = 1 
									AND IsApproved = 1 ";
					
				if(isset($catIDWhere)){
					$query = $query . " AND (" . HC_TblPrefix . "eventcategories.CategoryID In(" . cIn($catIDWhere) . "))";
				}//end if
		$query = $query . " ORDER BY StartDate, TBD, StartTime, Title";
		$result = doQuery($query);
		$row_cnt = mysql_num_rows($result);
		
		if($row_cnt > 0){
			if($sID == 0){	?>
			<form name="eventSearchResult" id="eventSearchResult" method="post" action="<?echo CalAdminRoot;?>/components/DeleteEvents.php" onSubmit="return chkFrm();"><?
			} else {?>
			<form name="eventSearchResult" id="eventSearchResult" method="post" action="<?echo CalAdminRoot;?>/index.php?com=eventviewdetail" onSubmit="return chkFrm();"><?
			}//end if	
			
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
					<tr><td colspan="7"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="20" alt="" border="0"></td></tr>
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
				}//end if
				
				if($curID != $row[0]){
					$curID = $row[0];
			?>
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
							<a href="<?echo CalAdminRoot;?>/index.php?com=eventedit&eID=<?echo $row[0];?>" class="main"><?echo cOut($row[1]);?></a>
						</td>
					<?php
						if($sID == 0){
					?>
							<td align="right" width="40" <?if($css % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><a href="<?echo CalAdminRoot;?>/index.php?com=eventedit&eID=<?echo $row[0];?>" class="main" onMouseOver="window.status = 'Edit Event: <?echo $row[1];?>'; return true;" onMouseOut="window.status = ''; return true;" title="Edit Event"><img src="<?echo CalAdminRoot;?>/images/iconEdit.gif" width="15" height="15" alt="" border="0" title="Edit Event"></a>&nbsp;&nbsp;</td>
							<td <?if($css % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><a href="<?echo CalAdminRoot;?>/index.php?com=eventedit&eID=<?echo $row[0];?>" class="main" onMouseOver="window.status = 'Edit Event In New Window: <?echo $row[1];?>'; return true;" onMouseOut="window.status = ''; return true;" title="Edit Event In New Window" target="_blank" title="Edit Event In New Window"><img src="<?echo CalAdminRoot;?>/images/iconNewWindow.gif" width="15" height="15" border="0" alt="Edit Event In New Window"></a>&nbsp;&nbsp;</td>
							<td <?if($css % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><input type="checkbox" name="eventID[]" id="eventID_<?echo $row[0];?>" value="<?echo $row[0];?>"></td>
					<?
						} else {
					?>
							<td <?if($css % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><input type="checkbox" name="eventID[]" id="eventID_<?echo $row[0];?>" value="<?echo $row[0];?>"></td>
					<?
						}//end if
					?>
						<td <?if($css % 2 == 1){echo "bgcolor=\"#EEEEEE\"";}//end if?>><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="3" height="1" alt="" border="0"><br></td>
					</tr>
			<?
					$css = $css + 1;
				}//end if
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
							<input type="submit" name="submit" id="submit" value=" <?if($sID == 0){echo "Delete Marked";}else{echo "Generate Report";}//end if?> " class="button">
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