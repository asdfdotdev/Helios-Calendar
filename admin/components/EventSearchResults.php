<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
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
		appInstructions(1, "Editing_Events", "Event Edit Search Results", "Select an event from the list below to edit. To delete an event click the checkbox to the right of the event listing than click the 'Delete Marked' button below.<br /><br /><img src=\"" . CalAdminRoot . "/images/icons/iconEdit.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"middle\" /> = Edit Event<br /><img src=\"" . CalAdminRoot . "/images/icons/iconNewWindow.gif\" width=\"15\" height=\"15\" alt=\"\" border=\"0\" align=\"middle\" /> = Edit Event In New Window");
		$frmAction = CalAdminRoot . "/index.php?com=eventedit";
		$buttonText = " Edit Selected Events As A Group ";
	} elseif($sID == 2){
		appInstructions(1, "Delete_Event", "Delete Events Search Results", "Select events from the list below by clicking the checkbox beside the listing. Once you have made your selection(s) click the 'Delete Selected' button below to delete the events.");
		$frmAction = CalAdminRoot . "/components/EventDelete.php";
		$buttonText = " Delete Selected Events ";
	} elseif($sID == 3){
		appInstructions(1, "Create_Series", "Create Series Search Results", "Select events from the list below by clicking the checkbox beside the listing. Once you have made your selection(s) click the 'Create Event Series' button below to create the series.");
		$frmAction = CalAdminRoot . "/components/EventCreateSeries.php";
		$buttonText = " Create Event Series ";
	} else {
		appInstructions(1, "Event_Activity_Report", "Event Report Search Results", "Select events from the list below by clicking the checkbox beside the listing. Once you have made your selection(s) click the 'Generate Report' button below.");
		$frmAction = CalAdminRoot . "/index.php?com=reportactivity";
		$buttonText = " Generate Event Report ";
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
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
	<br />
	<?	$startDate = dateToMySQL($_POST['startDate'], "/", $hc_popDateFormat);
		$endDate = dateToMySQL($_POST['endDate'], "/", $hc_popDateFormat);
		$keyword = cIn($_POST['keyword']);
		
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
					WHERE (StartDate BETWEEN '" . $startDate . "' AND '" . $endDate . "') 
							AND Title LIKE('%" . cIn($keyword) . "%')
							AND IsActive = 1 
							AND IsApproved = 1 ";
		
		if(isset($_POST['catID'])){
			$query = $query . " AND (" . HC_TblPrefix . "eventcategories.CategoryID In(" . cIn($catIDWhere) . "))";
		}//end if
		
		if($_POST['city'] != ''){
			$query = $query . " AND " . HC_TblPrefix . "events.LocationCity = '" . $_POST['city'] . "'";
		}//end if
		
		$query = $query . " ORDER BY StartDate, TBD, StartTime, Title";
		
		$result = doQuery($query);
		$row_cnt = mysql_num_rows($result);
		
		if(hasRows($result)){	?>
			<form name="eventSearchResult" id="eventSearchResult" method="post" action="<?echo $frmAction;?>" onsubmit="return chkFrm();">
			<div align="right">
				[ <a class="main" href="javascript:;" onclick="checkAllArray('eventSearchResult', 'eventID[]');">Select All</a> 
				&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('eventSearchResult', 'eventID[]');">Deselect All</a> ]
			</div>
		<?	$cnt = 0;
			$curDate = "";
			while($row = mysql_fetch_row($result)){
				if($curDate != $row[9]){
					$cnt = 0;
					$curDate = $row[9];	?>
					<div class="eventListDate">
					<?	echo stampToDate($row[9], $hc_dateFormat);	?>
					</div>
			<?	}//end if	?>
				<div class="<?if($cnt % 2 == 0){echo "eventListTime";}else{echo "eventListTimeHL";}?>">
				<?	//check for valid start time
					if($row[10] != ''){
						$timepart = explode(":", $row[10]);
						$startTime = date($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
					} else {
						$startTime = "";
					}//end if
					
					//check for valid end time
					if($row[12] != ''){
						$timepart = explode(":", $row[12]);
						$endTime = '-' . date($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
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
					}//end if	?>
				</div>
				<div class="<?if($cnt % 2 == 0){echo "eventListTitle";}else{echo "eventListTitleHL";}?>">
					<?echo cOut($row[1]);?>
				</div>
				<?	if($sID == 1){	?>
				<div class="<?if($cnt % 2 == 0){echo "eventListTools";}else{echo "eventListToolsHL";}?>">
					<a href="<?echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?echo $row[0];?>" class="main"><img src="<?echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0" align="middle" /></a>&nbsp;
					<a href="<?echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?echo $row[0];?>" class="main" target="_blank"><img src="<?echo CalAdminRoot;?>/images/icons/iconNewWindow.gif" width="15" height="15" alt="" border="0" align="middle" /></a>
				</div>
				<?	}//end if	?>
				<div class="<?if($cnt % 2 == 0){echo "eventListCheckbox";}else{echo "eventListCheckboxHL";}?>">
					<input type="checkbox" name="eventID[]" id="eventID_<?echo $row[0];?>" value="<?echo $row[0];?>" class="noBorderIE" />&nbsp;
				</div>
			<?	$cnt++;
			}//end while	?>
			<div style="text-align:right;clear:both;padding-top:10px;border-top: 1px solid #000000;">
				[ <a class="main" href="javascript:;" onclick="checkAllArray('eventSearchResult', 'eventID[]');">Select All</a> 
				&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('eventSearchResult', 'eventID[]');">Deselect All</a> ]
			</div>
		<?	if($sID == 3 && $row_cnt < 2){	?>
			<b>You must have at least 2 events to create a series.</b><br />
			<a href="<?echo CalAdminRoot;?>/index.php?com=eventsearch&sID=3" class="main">Click here to search again.</a>
		<?	} else {	?>
			<input type="submit" name="submit" id="submit" value="<?echo $buttonText;?>" class="button" />
		<?	}//end if	?>
			</form>
	<?	} else {	?>
			There are no events that meet that search criteria.<br />
			<a href="<?echo CalAdminRoot;?>/index.php?com=eventsearch&amp;sID=<?echo $sID;?>" class="main">Please click here to search again.</a>
			<br /><br />
	<?	}//end if	?>