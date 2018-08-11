<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/search.php');
	
	$sID = (isset($_POST['sID']) && is_numeric($_POST['sID'])) ? cIn($_POST['sID']) : 0;
	
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
	}//end if
	
	$startDateSQL = dateToMySQL(strftime($hc_cfg24,mktime(0,0,0,date("m"),date("d"),date("Y"))), "/", $hc_cfg24);
	$endDateSQL = dateToMySQL(strftime($hc_cfg24,mktime(0,0,0,date("m"),date("d") + 7,date("Y"))), "/", $hc_cfg24);
	$startDate = (isset($_POST['startDate'])) ? cIn($_POST['startDate']) : '';
	$endDate = (isset($_POST['endDate'])) ? cIn($_POST['endDate']) : '';
	$startDateParts = explode('/', $startDate);
	$endDateParts = explode('/', $endDate);
	$keyword = (isset($_POST['keyword'])) ? cIn($_POST['keyword']) : '';
	$location = (isset($_POST['location']) && is_numeric($_POST['location'])) ? cIn($_POST['location']) : 0;
	$city = (isset($_POST['city'])) ? cIn($_POST['city']) : '';
	$state = (isset($_POST['locState'])) ? cIn($_POST['locState']) : '';
	$postal = (isset($_POST['postal'])) ? cIn($_POST['postal']) : '';
	
	$catIDs = '0';
	if(isset($_POST['catID'])){
		$catID = $_POST['catID'];
		foreach($catID as $val){
			if(is_numeric(($val))){
				$catIDs = $catIDs . ',' . cIn($val);
			}//end if
		}//end while
	}//end if
	
	switch($hc_cfg24){
		case '%m/%d/%Y':
			if(isset($startDateParts[2]) && $startDateParts[2] != '' && is_numeric($startDateParts[2]) && checkdate($startDateParts[0], $startDateParts[1], $startDateParts[2]) && $startDateParts[2] <= 2038){
				$startDateSQL = dateToMySQL($startDate, "/", $hc_cfg24);
			}//end if
			
			if(isset($endDateParts[2]) && $endDateParts[2] != '' && is_numeric($endDateParts[2]) && checkdate($endDateParts[0], $endDateParts[1], $endDateParts[2]) && $endDateParts[2] <= 2038){
				$endDateSQL = dateToMySQL($endDate, "/", $hc_cfg24);
			}//end if
			break;
			
		case '%d/%m/%Y':
			if(isset($startDateParts[2]) && $startDateParts[2] != '' && is_numeric($startDateParts[2]) && checkdate($startDateParts[1], $startDateParts[0], $startDateParts[2]) && $startDateParts[2] <= 2038){
				$startDateSQL = dateToMySQL($startDate, "/", $hc_cfg24);
			}//end if
			
			if(isset($endDateParts[2]) && $endDateParts[2] != '' && is_numeric($endDateParts[2]) && checkdate($endDateParts[1], $endDateParts[0], $endDateParts[2]) && $endDateParts[2] <= 2038){
				$endDateSQL = dateToMySQL($endDate, "/", $hc_cfg24);
			}//end if
			break;
			
		case '%Y/%m/%d':
			if(isset($startDateParts[2]) && $startDateParts[2] != '' && is_numeric($startDateParts[2]) && checkdate($startDateParts[1], $startDateParts[2], $startDateParts[0]) && $startDateParts[2] <= 2038){
				$startDateSQL = dateToMySQL($startDate, "/", $hc_cfg24);
			}//end if
			
			if(isset($endDateParts[2]) && $endDateParts[2] != '' && is_numeric($endDateParts[2]) && checkdate($endDateParts[1], $endDateParts[2], $endDateParts[0]) && $endDateParts[2] <= 2038){
				$endDateSQL = dateToMySQL($endDate, "/", $hc_cfg24);
			}//end if
			break;
	}//end switch
	
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
	
	if($location > 0){
		$query .= "	AND l.PkID = '" . $location  . "'";
	}//end if
	
	if($city != ''){
		$query .= "	AND (l.IsActive = 1 OR l.IsActive is NULL)
					AND (e.LocationCity = '" . cIn($city) . "' OR l.City = '" . cIn($city) . "')";
	}//end if
	
	if($state != ''){
		$query .= "	AND (e.LocationState = '" . cIn($state) . "' or l.State = '" . cIn($state) . "')";
	}//end if
	
	if($postal != ''){
		$query .= "	AND (e.LocationZip = '" . cIn($postal) . "' or l.Zip = '" . cIn($postal) . "')";
	}//end if
	
	if($catIDs != '0'){
		$query .= " AND (ec.CategoryID In(" . cIn($catIDs) . "))";
	}//end if
	
	$query .= " ORDER BY e.StartDate, e.TBD, e.StartTime, e.Title";
	$result = doQuery($query);
	$row_cnt = mysql_num_rows($result);
	
	if(hasRows($result)){	?>	
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
				$curDate = $row[9];
				echo '<div class="eventListDate">' . stampToDate($row[9], $hc_cfg14) . '</div>';
			}//end if
			
			echo ($cnt % 2 == 0) ? '<div class="eventListTime">' : '<div class="eventListTimeHL">';
		 	
			$startTime = '';
			if($row[10] != ''){
				$timepart = explode(":", $row[10]);
				$startTime = strftime($hc_cfg23, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
			}//end if
			
			$endTime = '';
			if($row[12] != ''){
				$timepart = explode(":", $row[12]);
				$endTime = '-' . strftime($hc_cfg23, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
			}//end if
			
			if($row[11] == 0){
				echo $startTime . $endTime;
			} elseif($row[11] == 1) {
				echo '<i>' . $hc_lang_search['AllDay'] . '</i>';
			} elseif($row[11] == 2) {
				echo '<i>' . $hc_lang_search['TBD'] . '</i>';
			}//end if
			echo '</div>';
			
			echo ($cnt % 2 == 0) ? '<div class="eventListTitle">' : '<div class="eventListTitleHL">';
			echo cOut($row[1]) . '</div>';
			
			echo ($cnt % 2 == 0) ? '<div class="eventListTools">' : '<div class="eventListToolsHL">';
			if($sID == 1){
				echo '<a href="' . CalAdminRoot . '/index.php?com=eventedit&amp;eID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;';
				echo '<a href="' . CalAdminRoot . '/index.php?com=eventedit&amp;eID=' . $row[0] . '" class="main" target="_blank"><img src="' . CalAdminRoot . '/images/icons/iconNewWindow.png" width="15" height="15" alt="" border="0" style="vertical-align:middle;" /></a>';
			}//end if
			echo '&nbsp;</div>';
			
			echo ($cnt % 2 == 0) ? '<div class="eventListCheckbox">' : '<div class="eventListCheckboxHL">';
			echo '<input type="checkbox" name="eventID[]" id="eventID_' . $row[0] . '" value="' . $row[0] . '" class="noBorderIE" />&nbsp;';
			echo '</div>';
	 		++$cnt;
		}//end while
		
		echo '<div style="text-align:right;clear:both;padding-top:10px;border-top: 1px solid #000000;">';
		echo '[ <a class="main" href="javascript:;" onclick="checkAllArray(\'eventSearchResult\', \'eventID[]\');">' . $hc_lang_search['SelectAll'] . '</a>';
		echo '&nbsp;|&nbsp; <a class="main" href="javascript:;" onclick="uncheckAllArray(\'eventSearchResult\', \'eventID[]\');">' . $hc_lang_search['DeselectAll'] . '</a> ]';
		
		echo '</div>';
		echo ($sID == 3 && $row_cnt < 2) ? $hc_lang_search['None01'] : '<input type="submit" name="submit" id="submit" value="' . $buttonText . '" class="button" />';
 		echo '</form>';
	} else {
		echo $hc_lang_search['None02'];
		echo "<br /><br />";
	}//end if	?>