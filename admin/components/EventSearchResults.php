<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	if(!isset($hc_cfg00)){header("HTTP/1.1 403 No Direct Access");exit();}
	
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/search.php');
	
	$sID = (isset($_POST['sID']) && is_numeric($_POST['sID'])) ? cIn($_POST['sID']) : 0;
	$startDateSQL = dateToMySQL(strftime($hc_cfg24,mktime(0,0,0,date("m"),date("d"),date("Y"))), $hc_cfg24);
	$endDateSQL = dateToMySQL(strftime($hc_cfg24,mktime(0,0,0,date("m"),date("d") + 7,date("Y"))), $hc_cfg24);
	$startDate = (isset($_POST['startDate'])) ? cIn($_POST['startDate']) : '';
	$endDate = (isset($_POST['endDate'])) ? cIn($_POST['endDate']) : '';
	$startDateParts = explode('/', $startDate);
	$endDateParts = explode('/', $endDate);
	$keyword = (isset($_POST['keyword'])) ? cIn($_POST['keyword']) : '';
	$location = (isset($_POST['location']) && is_numeric($_POST['location'])) ? cIn($_POST['location']) : 0;
	$city = (isset($_POST['city'])) ? cIn($_POST['city']) : '';
	$state = (isset($_POST['locState'])) ? cIn($_POST['locState']) : '';
	$postal = (isset($_POST['postal'])) ? cIn($_POST['postal']) : '';
	$seriesOnly = (isset($_POST['seriesonly'])) ? 1 : 0;
	$catIDs = '0';
	if(isset($_POST['catID'])){
		$catID = $_POST['catID'];
		foreach($catID as $val){
			if(is_numeric(($val))){
				$catIDs = $catIDs . ',' . cIn($val);
			}//end if
		}//end while
	}//end if

	if($sID == 1 || isset($_GET['srsID'])){
		$sID = 1;
		$hlpText = ($seriesOnly == 1) ? $hc_lang_search['InstructEditRS'] : $hc_lang_search['InstructEditR'];
		appInstructions(1, "Editing_Events", $hc_lang_search['TitleEditR'], $hlpText);
		$frmAction = CalAdminRoot . "/index.php?com=eventedit";
		$buttonText = $hc_lang_search['EditGroup'];
	} elseif($sID == 2){
		appInstructions(1, "Delete_Event", $hc_lang_search['TitleDeleteR'], $hc_lang_search['InstructDeleteR']);
		$frmAction = CalAdminRoot . "/components/EventDelete.php";
		$buttonText = $hc_lang_search['DeleteGroup'];
	} elseif($sID == 3){
		appInstructions(1, "Event_Series", $hc_lang_search['TitleSeriesR'], $hc_lang_search['InstructSeriesR']);
		$frmAction = CalAdminRoot . "/components/EventCreateSeries.php";
		$buttonText = $hc_lang_search['CreateSeries'];
	} else {
		appInstructions(1, "Reports", $hc_lang_search['TitleReportR'], $hc_lang_search['InstructReportR']);
		$frmAction = CalAdminRoot . "/index.php?com=reportactivity";
		$buttonText = $hc_lang_search['GenerateReport'];
	}//end if

	switch($hc_cfg24){
		case '%m/%d/%Y':
			if(isset($startDateParts[2]) && $startDateParts[2] != '' && is_numeric($startDateParts[2]) && checkdate($startDateParts[0], $startDateParts[1], $startDateParts[2]) && $startDateParts[2] <= 2038){
				$startDateSQL = dateToMySQL($startDate, $hc_cfg24);
			}//end if
			
			if(isset($endDateParts[2]) && $endDateParts[2] != '' && is_numeric($endDateParts[2]) && checkdate($endDateParts[0], $endDateParts[1], $endDateParts[2]) && $endDateParts[2] <= 2038){
				$endDateSQL = dateToMySQL($endDate, $hc_cfg24);
			}//end if
			break;
			
		case '%d/%m/%Y':
			if(isset($startDateParts[2]) && $startDateParts[2] != '' && is_numeric($startDateParts[2]) && checkdate($startDateParts[1], $startDateParts[0], $startDateParts[2]) && $startDateParts[2] <= 2038){
				$startDateSQL = dateToMySQL($startDate, $hc_cfg24);
			}//end if
			
			if(isset($endDateParts[2]) && $endDateParts[2] != '' && is_numeric($endDateParts[2]) && checkdate($endDateParts[1], $endDateParts[0], $endDateParts[2]) && $endDateParts[2] <= 2038){
				$endDateSQL = dateToMySQL($endDate, $hc_cfg24);
			}//end if
			break;
			
		case '%Y/%m/%d':
			if(isset($startDateParts[2]) && $startDateParts[2] != '' && is_numeric($startDateParts[2]) && checkdate($startDateParts[1], $startDateParts[2], $startDateParts[0]) && $startDateParts[2] <= 2038){
				$startDateSQL = dateToMySQL($startDate, $hc_cfg24);
			}//end if
			
			if(isset($endDateParts[2]) && $endDateParts[2] != '' && is_numeric($endDateParts[2]) && checkdate($endDateParts[1], $endDateParts[2], $endDateParts[0]) && $endDateParts[2] <= 2038){
				$endDateSQL = dateToMySQL($endDate, $hc_cfg24);
			}//end if
			break;
	}//end switch

	$query = "SELECT DISTINCT e.*, l.City, l.IsActive
			FROM " . HC_TblPrefix . "events as e
				INNER JOIN " . HC_TblPrefix . "eventcategories as ec ON (e.PkID = ec.EventID)
				LEFT JOIN " . HC_TblPrefix . "locations as l ON (e.LocID = l.PkID) ";
	if(isset($_GET['srsID'])){
		$query .= "WHERE SeriesID = '" . cIn($_GET['srsID']) . "' ";
	} else {
		$query .= "WHERE (StartDate BETWEEN '" . cIn($startDateSQL) . "' AND '" . cIn($endDateSQL) . "')
						AND e.IsActive = 1
						AND e.IsApproved = 1 ";
		$query .= ($keyword != '') ? "AND MATCH(Title,LocationName,Description) AGAINST('" . str_replace("'", "\"", $keyword) . "' IN BOOLEAN MODE) " : '';
		$query .= ($location > 0) ? " AND l.PkID = '" . $location  . "'" : '';
		$query .= ($city != '') ? " AND (l.IsActive = 1 OR l.IsActive is NULL) AND (e.LocationCity = '" . cIn($city) . "' OR l.City = '" . cIn($city) . "')" : '';
		$query .= ($state != '') ? " AND (e.LocationState = '" . cIn($state) . "' or l.State = '" . cIn($state) . "')" : '';
		$query .= ($postal != '') ? " AND (e.LocationZip = '" . cIn($postal) . "' or l.Zip = '" . cIn($postal) . "')" : '';
		$query .= ($catIDs != '0') ? " AND (ec.CategoryID In(" . cIn($catIDs) . "))" : '';
	}//end if

	$query .= ($seriesOnly == 1) ? " AND SeriesID != '' GROUP BY SeriesID " : '';
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
<?php 	$cnt = 0;
		$curDate = "";

		if($seriesOnly == 0){
			echo '<div class="catCtrl">';
			echo '[ <a class="catLink" href="javascript:;" onclick="checkAllArray(\'eventSearchResult\', \'eventID[]\');">' . $hc_lang_search['SelectAll'] . '</a>';
			echo '&nbsp;|&nbsp; <a class="catLink" href="javascript:;" onclick="uncheckAllArray(\'eventSearchResult\', \'eventID[]\');">' . $hc_lang_search['DeselectAll'] . '</a> ]';
			echo '</div>';
		}//end if

		while($row = mysql_fetch_row($result)){
			if($curDate != $row[9]){
				$cnt = 0;
				$curDate = $row[9];
				echo '<div class="eventListDate">' . stampToDate($row[9], $hc_cfg14) . '</div>';
			}//end if
			
			echo ($cnt % 2 == 0) ? '<div class="eventListTime">' : '<div class="eventListTimeHL">';
			if($row[11] == 0){
				echo ($row[10] != '') ? stampToDate("1980-01-01 " . $row[10], $hc_cfg23) : '';
				echo ($row[12] != '') ? '-' . stampToDate("1980-01-01 " . $row[12], $hc_cfg23) : '';
			} elseif($row[11] == 1) {
				echo '<i>' . $hc_lang_search['AllDay'] . '</i>';
			} elseif($row[11] == 2) {
				echo '<i>' . $hc_lang_search['TBD'] . '</i>';
			}//end if
			echo '</div>';
			
			echo ($cnt % 2 == 0) ? '<div class="eventListTitle">' : '<div class="eventListTitleHL">';
			echo cOut($row[1]) . '</div>';
			
			echo ($cnt % 2 == 0) ? '<div class="eventListTools">' : '<div class="eventListToolsHL">';
			if($sID == 1 && $seriesOnly == 0){
				echo '<a href="' . CalAdminRoot . '/index.php?com=eventedit&amp;eID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEdit.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;&nbsp;';
				echo '<a href="' . CalAdminRoot . '/index.php?com=eventedit&amp;eID=' . $row[0] . '" class="main" target="_blank"><img src="' . CalAdminRoot . '/images/icons/iconNewWindow.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;&nbsp;';
				echo '<a href="' . CalAdminRoot . '/index.php?com=eventadd&amp;eID=' . $row[0] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconRecycle.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>';
			} elseif($sID == 1 && $seriesOnly == 1){
				echo '<a href="' . CalAdminRoot . '/index.php?com=eventedit&amp;sID=' . $row[19] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconEditGroup.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;&nbsp;';
				echo '<a href="' . CalAdminRoot . '/index.php?com=searchresults&amp;srsID=' . $row[19] . '" class="main"><img src="' . CalAdminRoot . '/images/icons/iconViewSeries.png" width="16" height="16" alt="" border="0" style="vertical-align:middle;" /></a>&nbsp;&nbsp;';
			}//end if
			echo '&nbsp;</div>';

			if($seriesOnly == 0){
				echo ($cnt % 2 == 0) ? '<div class="eventListCheckbox">' : '<div class="eventListCheckboxHL">';
				echo '<input type="checkbox" name="eventID[]" id="eventID_' . $row[0] . '" value="' . $row[0] . '" class="noBorderIE" />&nbsp;';
				echo '</div>';
			}//end if
	 		++$cnt;
		}//end while

		if($seriesOnly == 0){
			echo '<div class="catCtrl" style="clear:both;padding-top:10px;border-top: 1px solid #000000;">';
			echo '[ <a class="catLink" href="javascript:;" onclick="checkAllArray(\'eventSearchResult\', \'eventID[]\');">' . $hc_lang_search['SelectAll'] . '</a>';
			echo '&nbsp;|&nbsp; <a class="catLink" href="javascript:;" onclick="uncheckAllArray(\'eventSearchResult\', \'eventID[]\');">' . $hc_lang_search['DeselectAll'] . '</a> ]';
			echo '</div>';
			echo ($sID == 3 && $row_cnt < 2) ? $hc_lang_search['None01'] : '<input type="submit" name="submit" id="submit" value="' . $buttonText . '" class="button" />';
		}//end if
 		echo '</form>';
	} else {
		echo '<p>' . $hc_lang_search['None02'] . '</p>';
	}//end if	?>