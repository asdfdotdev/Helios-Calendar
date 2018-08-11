<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/search.php');
	
	$hourOffset = date("G") + ($hc_cfg35);
	$startDateSQL = dateToMySQL(date($hc_cfg24,mktime($hourOffset,0,0,date("m"),date("d"),date("Y"))), "/", $hc_cfg24);
	$endDateSQL = dateToMySQL(date($hc_cfg24,mktime(0,0,0,date("m"),date("d") + 30,date("Y"))), "/", $hc_cfg24);
	
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
	
	$keyword = "";
	if(isset($_POST['keyword'])) {
		$keyword = str_replace("\"","'",$_POST['keyword']);
	} elseif(isset($_GET['k'])) {
		$keyword = str_replace("\"","'",htmlspecialchars($_GET['k']));
	}//end if
	
	$query = "	SELECT DISTINCT e.*, l.City, l.IsActive
				FROM " . HC_TblPrefix . "events as e
					INNER JOIN " . HC_TblPrefix . "eventcategories as ec ON (e.PkID = ec.EventID)
					LEFT JOIN " . HC_TblPrefix . "locations as l ON (e.LocID = l.PkID)
				WHERE (StartDate BETWEEN '" . cIn($startDateSQL) . "' AND '" . cIn($endDateSQL) . "')
						AND e.IsActive = 1 
						AND e.IsApproved = 1 ";
	
	if($keyword != ''){
		$query .= "AND MATCH(Title,LocationName,Description) AGAINST('" . str_replace("'", "\"", cIn($keyword)) . "' IN BOOLEAN MODE) ";
	}//end if
	
	$location = 0;
	$linkL = '';
	if(isset($_POST['location'])) {
		$location = $_POST['location'];
	} elseif(isset($_GET['l'])) {
		$location = urldecode($_GET['l']);
	}//end if
	if(is_numeric($location) && $location > 0){
		$query .= "	AND l.PkID = '" . $location  . "'";
		$linkL = "&amp;l=" . urlencode(cIn($location));
	}//end if
	
	$city = $linkC = "";
	if(isset($_POST['city'])) {
		$city = $_POST['city'];
	} elseif(isset($_GET['c'])) {
		$city = urldecode($_GET['c']);
	}//end if
	if($city != ''){
		$query .= "	AND (l.IsActive = 1 OR l.IsActive is NULL)
					AND (e.LocationCity = '" . cIn($city) . "' OR l.City = '" . cIn($city) . "')";
		$linkC = "&amp;c=" . urlencode(cIn($city));
	}//end if
	
	$state = $linkS = '';
	if(isset($_POST['locState'])) {
		$state = $_POST['locState'];
	} elseif(isset($_GET['st'])) {
		$state = urldecode($_GET['st']);
	}//end if
	if($state != ''){
		$query .= "	AND (e.LocationState = '" . cIn($state) . "' or l.State = '" . cIn($state) . "')";
		$linkS = "&amp;st=" . urlencode(cIn($state));
	}//end if
	
	$postal = $linkP = '';
	if(isset($_POST['postal'])) {
		$postal = $_POST['postal'];
	} elseif(isset($_GET['p'])) {
		$postal = urldecode($_GET['p']);
	}//end if
	if($postal != ''){
		$query .= "	AND (e.LocationZip = '" . cIn($postal) . "' or l.Zip = '" . cIn($postal) . "')";
		$linkP = "&amp;p=" . urlencode(cIn($postal));
	}//end if
	
	$catIDs = $linkCat = '';
	if(isset($_POST['catID'])){
		$catID = $_POST['catID'];
		foreach ($catID as $val){
			if(is_numeric($val)){
				$catIDs = $catIDs . $val . ",";
			}//end if
		}//end while
		$catIDs = substr($catIDs, 0, strlen($catIDs) - 1);
	} elseif(isset($_GET['t'])) {
		$catID = explode(',', urldecode($_GET['t']));
		foreach ($catID as $val){
			if(is_numeric($val)){
				$catIDs = $catIDs . $val . ",";
			}//end if
		}//end while
		$catIDs = substr($catIDs, 0, strlen($catIDs) - 1);
	}//end if
	
	if($catIDs != ''){
		$query .= " AND (ec.CategoryID In(" . cIn($catIDs) . "))";
		$linkCat = "&amp;t=" . urlencode($catIDs);
	}//end if	

	$doRecur = 0;
	if(isset($_POST['recurSet']) && is_numeric($_POST['recurSet'])){
		$doRecur = cIn($_POST['recurSet']);
	} elseif(isset($_GET['r']) && is_numeric($_GET['r'])){
		$doRecur = cIn($_GET['r']);
	}//end if

	echo "<br /><b>" . $hc_lang_search['ResultLabel'] . "</b>&nbsp;";
	echo "[<a href=\"" . CalRoot . "/index.php?com=searchresult&amp;r=" . $doRecur . "&amp;s=" . urlencode($startDate) . "&amp;e=" . urlencode($endDate);?>&amp;k=<?php echo urlencode($keyword) . $linkL . $linkC . $linkP . $linkS . $linkCat . "\" class=\"eventMain\">" . $hc_lang_search['ResultLink'] . "</a>]";
	echo "<br />";

	$query .= " ORDER BY e.StartDate, e.TBD, e.StartTime, e.Title";
	$result = doQuery($query);
	
	if(hasRows($result)){
		$cnt = 0;
		$curDate = "";
		$hideSeries = array();
		while($row = mysql_fetch_row($result)){
			if($row[19] == '' || !in_array($row[19], $hideSeries)){
				if(($curDate != $row[9]) or ($cnt == 0)){
					$curDate = $row[9];
					echo '<div class="eventDateTitle">' . stampToDate($row[9], $hc_cfg14) . '</div>';
					$cnt = 0;
				}//end if

				echo ($cnt % 2 == 0) ? '<div class="eventListTime">' : '<div class="eventListTimeHL">';

				$startTime = $endTime = '';
				if($row[10] != ''){
					$timepart = explode(":", $row[10]);
					$startTime = strftime($hc_cfg23, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
				}//end if

				if($row[12] != ''){
					$timepart = explode(":", $row[12]);
					$endTime = '&nbsp;-&nbsp;' . strftime($hc_cfg23, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
				}//end if

				if($row[11] == 0){
					echo $startTime . $endTime;
				} elseif($row[11] == 1) {
					echo "<i>" . $hc_lang_search['AllDay'] . "</i>";
				} elseif($row[11] == 2) {
					echo "<i>" . $hc_lang_search['TBA'] . "</i>";
				}//end if
				echo '</div>';

				echo ($cnt % 2 == 0) ? '<div class="eventListTitle">' : '<div class="eventListTitleHL">';
				echo '<a href="' . CalRoot . '/index.php?com=detail&amp;eID=' . $row[0] . '" class="eventListTitle">' . cOut($row[1]) . '</a></div>';
				
				if($doRecur == 1 && $row[19] != '' && (!in_array($row[19], $hideSeries))){
					$hideSeries[] = $row[19];
				}//end if
			++$cnt;
			}//end if
		}//end while
	} else {	
		echo '<br />' . $hc_lang_search['NoResults'];
		echo '<br /><br />';
		echo '<a href="' . CalRoot . '/index.php?com=search" class="eventMain">' . $hc_lang_search['SearchAgain'] . '</a>';
	}//end if?>