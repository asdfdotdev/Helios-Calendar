<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/search.php');
	
	$sID = 0;
	if(isset($_POST['sID'])){
		$sID = $_POST['sID'];
	}//end if
	
	if($sID == 1){
		appInstructions(1, "Editing_Events", $hc_lang_search['TitleEditR'], $hc_lang_search['InstructEditR']);
		$frmAction = CalAdminRoot . "/index.php?com=eventedit";
		$buttonText = $hc_lang_search['EditGroup'];
	} elseif($sID == 2){
		appInstructions(1, "Delete_Event", $hc_lang_search['TitleDeleteR'], $hc_lang_search['InstructDeleteR']);
		$frmAction = CalAdminRoot . "/components/EventDelete.php";
		$buttonText = $hc_lang_search['DeleteGroup'];
	} elseif($sID == 3){
		appInstructions(1, "Create_Series", $hc_lang_search['TitleSeriesR'], $hc_lang_search['InstructSeriesR']);
		$frmAction = CalAdminRoot . "/components/EventCreateSeries.php";
		$buttonText = $hc_lang_search['CreateSeries'];
	} else {
		appInstructions(1, "Reports", $hc_lang_search['TitleReportR'], $hc_lang_search['InstructReportR']);
		$frmAction = CalAdminRoot . "/index.php?com=reportactivity";
		$buttonText = $hc_lang_search['GenerateReport'];
	}//end if	?>
	<script language="JavaScript" type="text/JavaScript" src="<?php echo CalRoot;?>/includes/java/Checkboxes.js"></script>
	<script language="JavaScript" type="text/JavaScript">
	//<!--
	function chkFrm(){
		if(validateCheckArray('eventSearchResult','eventID[]',1) == 1){
			alert('<?php echo $hc_lang_search['Valid09'];?>');
			return false;
<?php 	if($sID == 1){	?>
		} else {
			if(confirm('<?php echo $hc_lang_search['Valid10'] . "\\n\\n          " . $hc_lang_search['Valid11'] . "\\n          " . $hc_lang_search['Valid12'];?>')){
				return true;
			} else {
				return false;
			}//end if
<?php 	} elseif($sID == 2){	?>
		} else {
			if(confirm('<?php echo $hc_lang_search['Valid13'] . "\\n\\n          " . $hc_lang_search['Valid14'] . "\\n          " . $hc_lang_search['Valid15'];?>')){
				return true;
			} else {
				return false;
			}//end if
<?php 	} elseif($sID == 3){	?>
		} else {
			if(confirm('<?php echo $hc_lang_search['Valid16'] . "\\n\\n          " . $hc_lang_search['Valid17'] . "\\n          " . $hc_lang_search['Valid18'];?>')){
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
	$startDateSQL = dateToMySQL(strftime($hc_popDateFormat,mktime(0,0,0,date("m"),date("d"),date("Y"))), "/", $hc_popDateFormat);
	$endDateSQL = dateToMySQL(strftime($hc_popDateFormat,mktime(0,0,0,date("m"),date("d") + 7,date("Y"))), "/", $hc_popDateFormat);
	
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
		case '%m/%d/%Y':
			if(isset($startDateParts[2]) && $startDateParts[2] != '' && is_numeric($startDateParts[2]) && checkdate($startDateParts[0], $startDateParts[1], $startDateParts[2]) && $startDateParts[2] <= 2038){
				$startDateSQL = dateToMySQL($startDate, "/", $hc_popDateFormat);
			}//end if
			
			if(isset($endDateParts[2]) && $endDateParts[2] != '' && is_numeric($endDateParts[2]) && checkdate($endDateParts[0], $endDateParts[1], $endDateParts[2]) && $endDateParts[2] <= 2038){
				$endDateSQL = dateToMySQL($endDate, "/", $hc_popDateFormat);
			}//end if
			break;
			
		case '%d/%m/%Y':
			if(isset($startDateParts[2]) && $startDateParts[2] != '' && is_numeric($startDateParts[2]) && checkdate($startDateParts[1], $startDateParts[0], $startDateParts[2]) && $startDateParts[2] <= 2038){
				$startDateSQL = dateToMySQL($startDate, "/", $hc_popDateFormat);
			}//end if
			
			if(isset($endDateParts[2]) && $endDateParts[2] != '' && is_numeric($endDateParts[2]) && checkdate($endDateParts[1], $endDateParts[0], $endDateParts[2]) && $endDateParts[2] <= 2038){
				$endDateSQL = dateToMySQL($endDate, "/", $hc_popDateFormat);
			}//end if
			break;
			
		case '%Y/%m/%d':
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
						AND e.IsActive = 1 
						AND e.IsApproved = 1 ";
	
	if($keyword != ''){
		$query .= "AND MATCH(Title,LocationName,Description) AGAINST('" . str_replace("'", "\"", $keyword) . "' IN BOOLEAN MODE) ";
	}//end if
	
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
			[ <a class="main" href="javascript:;" onclick="checkAllArray('eventSearchResult', 'eventID[]');"><?php echo $hc_lang_search['SelectAll'];?></a> 
			&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('eventSearchResult', 'eventID[]');"><?php echo $hc_lang_search['DeselectAll'];?></a> ]
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
					$startTime = strftime($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
				} else {
					$startTime = "";
				}//end if
				
				//check for valid end time
				if($row[12] != ''){
					$timepart = explode(":", $row[12]);
					$endTime = '-' . strftime($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
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
				<a href="<?php echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?php echo $row[0];?>" class="main"><img src="<?php echo CalAdminRoot;?>/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;
				<a href="<?php echo CalAdminRoot;?>/index.php?com=eventedit&amp;eID=<?php echo $row[0];?>" class="main" target="_blank"><img src="<?php echo CalAdminRoot;?>/images/icons/iconNewWindow.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>
			</div>
		<?php 	}//end if	?>
			<div class="<?php if($cnt % 2 == 0){echo "eventListCheckbox";}else{echo "eventListCheckboxHL";}?>">
				<input type="checkbox" name="eventID[]" id="eventID_<?php echo $row[0];?>" value="<?php echo $row[0];?>" class="noBorderIE" />&nbsp;
			</div>
	<?php 	$cnt++;
		}//end while	?>
		<div style="text-align:right;clear:both;padding-top:10px;border-top: 1px solid #000000;">
			[ <a class="main" href="javascript:;" onclick="checkAllArray('eventSearchResult', 'eventID[]');"><?php echo $hc_lang_search['SelectAll'];?></a> 
			&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray('eventSearchResult', 'eventID[]');"><?php echo $hc_lang_search['DeselectAll'];?></a> ]
		</div>
<?php 	if($sID == 3 && $row_cnt < 2){
			echo $hc_lang_search['None01'];
 		} else {	?>
		<input type="submit" name="submit" id="submit" value=" <?php echo $buttonText;?> " class="button" />
<?php 	}//end if	?>
		</form>
<?php
	} else {
		echo $hc_lang_search['None02'];
		echo "<br /><br />";
	}//end if	?>