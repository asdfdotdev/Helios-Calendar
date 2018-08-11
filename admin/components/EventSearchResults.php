<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
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
		appInstructions(1, "Reports", "Event Report Search Results", "Select events from the list below by clicking the checkbox beside the listing. Once you have made your selection(s) click the 'Generate Report' button below.");
		$frmAction = CalAdminRoot . "/index.php?com=reportactivity";
		$buttonText = " Generate Event Report ";
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
		if(validateCheckArray('eventSearchResult','eventID[]',1) == 1){
			alert('No events selected.\nPlease select at least one event and try again.');
			return false;
<?php 	if($sID == 1){	?>
		} else {
			if(confirm('Editing events as a group will begin with the first selected event. All others will be updated to that events details.\nAre you sure you want to edit the selected events as a group?\n\n          Ok = YES Edit the Selected Events as a Group\n          Cancel = NO Do Not Continue')){
				return true;
			} else {
				return false;
			}//end if
<?php 	} elseif($sID == 2){	?>
		} else {
			if(confirm('Event Delete Is Permanent!\nAre you sure you want to delete the selected event(s)\n\n          Ok = YES Delete Event(s)\n          Cancel = NO Don\'t Delete Event(s)')){
				return true;
			} else {
				return false;
			}//end if
<?php 	} elseif($sID == 3){	?>
		} else {
			if(confirm('Creating an event series will remove the selected events from any other series they may be a part of. Are you sure you want to create a new series with the selected events?\n\n          Ok = YES Create New Event Series With the Selected Events\n          Cancel = NO Do Not Continue')){
				return true;
			} else {
				return false;
			}//end if
<?php 	}//end if	?>
		}//end if
		return true;
	}//end chkFrm()
	//-->
	</script>
	<br />
<?php 	
	$startDateSQL = dateToMySQL(date($hc_popDateFormat,mktime(0,0,0,date("m"),date("d"),date("Y"))), "/", $hc_popDateFormat);
	$endDateSQL = dateToMySQL(date($hc_popDateFormat,mktime(0,0,0,date("m"),date("d") + 7,date("Y"))), "/", $hc_popDateFormat);
	
	$startDate = "";
	if(isset($_POST['startDate'])) {
		$startDate = $_POST['startDate'];
	} elseif(isset($_GET['s'])) {
		$startDate = urldecode($_GET['s']);
	}//end if
	
	$endDate = "";
	if(isset($_POST['endDate'])) {
		$endDate = $_POST['endDate'];
	} elseif(isset($_GET['e'])) {
		$endDate = urldecode($_GET['e']);
	}//end if
	
	$startDateParts = explode('/', $startDate);
	$endDateParts = explode('/', $endDate);
	switch($hc_popDateFormat){
		case 'm/d/Y':
			if(isset($startDateParts[2]) && $startDateParts[2] != '' && is_numeric($startDateParts[2]) && checkdate($startDateParts[0], $startDateParts[1], $startDateParts[2]) && $startDateParts[2] <= 2038){
				$startDateSQL = dateToMySQL($startDate, "/", $hc_popDateFormat);
			}//end if
			
			if(isset($endDateParts[2]) && $endDateParts[2] != '' && is_numeric($endDateParts[2]) && checkdate($endDateParts[0], $endDateParts[1], $endDateParts[2]) && $endDateParts[2] <= 2038){
				$endDateSQL = dateToMySQL($endDate, "/", $hc_popDateFormat);
			}//end if
			break;
			
		case 'd/m/Y':
			if(isset($startDateParts[2]) && $startDateParts[2] != '' && is_numeric($startDateParts[2]) && checkdate($startDateParts[1], $startDateParts[0], $startDateParts[2]) && $startDateParts[2] <= 2038){
				$startDateSQL = dateToMySQL($startDate, "/", $hc_popDateFormat);
			}//end if
			
			if(isset($endDateParts[2]) && $endDateParts[2] != '' && is_numeric($endDateParts[2]) && checkdate($endDateParts[1], $endDateParts[0], $endDateParts[2]) && $endDateParts[2] <= 2038){
				$endDateSQL = dateToMySQL($endDate, "/", $hc_popDateFormat);
			}//end if
			break;
			
		case 'Y/m/d':
			if(isset($startDateParts[2]) && $startDateParts[2] != '' && is_numeric($startDateParts[2]) && checkdate($startDateParts[1], $startDateParts[2], $startDateParts[0]) && $startDateParts[2] <= 2038){
				$startDateSQL = dateToMySQL($startDate, "/", $hc_popDateFormat);
			}//end if
			
			if(isset($endDateParts[2]) && $endDateParts[2] != '' && is_numeric($endDateParts[2]) && checkdate($endDateParts[1], $endDateParts[2], $endDateParts[0]) && $endDateParts[2] <= 2038){
				$endDateSQL = dateToMySQL($endDate, "/", $hc_popDateFormat);
			}//end if
			break;
	}//end switch
	
	$keyword = "";
	if(isset($_POST['keyword'])) {
		$keyword = $_POST['keyword'];
	} elseif(isset($_GET['k'])) {
		$keyword = urldecode($_GET['k']);
	}//end if
	
	$query = "	SELECT DISTINCT e.*, l.City, l.IsActive
				FROM " . HC_TblPrefix . "events as e
					INNER JOIN " . HC_TblPrefix . "eventcategories as ec ON (e.PkID = ec.EventID)
					LEFT JOIN " . HC_TblPrefix . "locations as l ON (e.LocID = l.PkID)
				WHERE (StartDate BETWEEN '" . cIn($startDateSQL) . "' AND '" . cIn($endDateSQL) . "')
						AND e.Title LIKE('%" . cIn($keyword) . "%')
						AND e.IsActive = 1 
						AND e.IsApproved = 1 ";
	
	$location = 0;
	$linkL = "";
	if(isset($_POST['location'])) {
		$location = $_POST['location'];
	} elseif(isset($_GET['l'])) {
		$location = urldecode($_GET['l']);
	}//end if
	if(is_numeric($location) && $location > 0){
		$query .= "	AND l.PkID = '" . $location  . "'";
		$linkL = "&l=" . urlencode(cIn($location));
	}//end if
	
	$city = "";
	$linkC = "";
	if(isset($_POST['city'])) {
		$city = $_POST['city'];
	} elseif(isset($_GET['c'])) {
		$city = urldecode($_GET['c']);
	}//end if
	if($city != ''){
		$query .= "	AND (l.IsActive = 1 OR l.IsActive is NULL)
					AND (e.LocationCity = '" . cIn($city) . "' OR l.City = '" . cIn($city) . "')";
		$linkC = "&c=" . urlencode(cIn($city));
	}//end if
	
	$state = "";
	$linkS = "";
	if(isset($_POST['locState'])) {
		$state = $_POST['locState'];
	} elseif(isset($_GET['s'])) {
		$state = urldecode($_GET['s']);
	}//end if
	if($state != ''){
		$query .= "	AND (e.LocationState = '" . cIn($state) . "' or l.State = '" . cIn($state) . "')";
		$linkS = "&s=" . urlencode(cIn($state));
	}//end if
	
	$postal = "";
	$linkP = "";
	if(isset($_POST['postal'])) {
		$postal = $_POST['postal'];
	} elseif(isset($_GET['p'])) {
		$postal = urldecode($_GET['p']);
	}//end if
	if(is_numeric($postal) && $postal > 0){
		$query .= "	AND (e.LocationZip = '" . cIn($postal) . "' or l.Zip = '" . cIn($postal) . "')";
		$linkP = "&p=" . urlencode(cIn($postal));
	}//end if
	
	$catIDs = "";
	$linkCat = "";
	if(isset($_POST['catID'])){
		$catID = $_POST['catID'];
		foreach ($catID as $val){
			$catIDs = $catIDs . $val . ",";
		}//end while
		$catIDs = substr($catIDs, 0, strlen($catIDs) - 1);
	} elseif(isset($_GET['t'])) {
		$catIDs = urldecode($_GET['t']);
	}//end if
	
	if($catIDs != ''){
		$query .= " AND (ec.CategoryID In(" . cIn($catIDs) . "))";
		$linkCat = "&t=" . urlencode($catIDs);
	}//end if

	$query .= " ORDER BY e.StartDate, e.TBD, e.StartTime, e.Title";
	$result = doQuery($query);
	$row_cnt = mysql_num_rows($result);
	
	if(hasRows($result)){	?>
		<form name="eventSearchResult" id="eventSearchResult" method="post" action="<?php echo $frmAction;?>" onsubmit="return chkFrm();">
		<div align="right">
			[ <a class="main" href="javascript:;" onclick="checkAllArray('eventSearchResult', 'eventID[]');">Select All</a> 
			&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('eventSearchResult', 'eventID[]');">Deselect All</a> ]
		</div>
<?php 	$cnt = 0;
		$curDate = "";
		while($row = mysql_fetch_row($result)){
			if($curDate != $row[9]){
				$cnt = 0;
				$curDate = $row[9];	?>
				<div class="eventListDate">
		<?php 	echo stampToDate($row[9], $hc_dateFormat);	?>
				</div>
	<?php 	}//end if	?>
			<div class="<?php if($cnt % 2 == 0){echo "eventListTime";}else{echo "eventListTimeHL";}?>">
		<?php 	if($row[10] != ''){
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
			<div class="<?php if($cnt % 2 == 0){echo "eventListTitle";}else{echo "eventListTitleHL";}?>">
	<?php	echo cOut($row[1]);?>
			</div>
		<?php 	if($sID == 1){	?>
			<div class="<?php if($cnt % 2 == 0){echo "eventListTools";}else{echo "eventListToolsHL";}?>">
				<a href="<?php echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?php echo $row[0];?>" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconEdit.gif" width="15" height="15" alt="" border="0" align="absmiddle" /></a>&nbsp;
				<a href="<?php echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?php echo $row[0];?>" class="main" target="_blank"><img src="<?php echo CalAdminRoot;?>/images/icons/iconNewWindow.gif" width="15" height="15" alt="" border="0" align="absmiddle" /></a>
			</div>
		<?php 	}//end if	?>
			<div class="<?php if($cnt % 2 == 0){echo "eventListCheckbox";}else{echo "eventListCheckboxHL";}?>">
				<input type="checkbox" name="eventID[]" id="eventID_<?php echo $row[0];?>" value="<?php echo $row[0];?>" class="noBorderIE" />&nbsp;
			</div>
	<?php 	$cnt++;
		}//end while	?>
		<div style="text-align:right;clear:both;padding-top:10px;border-top: 1px solid #000000;">
			[ <a class="main" href="javascript:;" onclick="checkAllArray('eventSearchResult', 'eventID[]');">Select All</a> 
			&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('eventSearchResult', 'eventID[]');">Deselect All</a> ]
		</div>
<?php 	if($sID == 3 && $row_cnt < 2){	?>
		<b>You must have at least 2 events to create a series.</b><br />
		<a href="<?php echo CalAdminRoot;?>/index.php?com=eventsearch&sID=3" class="main">Click here to search again.</a>
<?php 	} else {	?>
		<input type="submit" name="submit" id="submit" value="<?php echo $buttonText;?>" class="button" />
<?php 	}//end if	?>
		</form>
<?php
	} else {	?>
		There are no events that meet that search criteria.<br />
		<a href="<?echo CalAdminRoot;?>/index.php?com=eventsearch&amp;sID=<?echo $sID;?>" class="main">Please click here to search again.</a>
		<br /><br />
<?php
	}//end if	?>