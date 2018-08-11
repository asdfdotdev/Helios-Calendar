<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/

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
	} elseif(isset($_GET['st'])) {
		$state = urldecode($_GET['st']);
	}//end if
	if($state != ''){
		$query .= "	AND (e.LocationState = '" . cIn($state) . "' or l.State = '" . cIn($state) . "')";
		$linkS = "&st=" . urlencode(cIn($state));
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
	}//end if	?>
	
	<br />
	<b>Your Search Results</b> [<a href="<?php echo CalRoot;?>/index.php?com=searchresult&s=<?php echo urlencode($startDate);?>&e=<?php echo urlencode($endDate);?>&k=<?php echo urlencode($keyword) . $linkL . $linkC . $linkP . $linkS . $linkCat;?>" class="eventMain">Link to These Results</a>]<br />
<?php
	$query .= " ORDER BY e.StartDate, e.TBD, e.StartTime, e.Title";
	$result = doQuery($query);
	
	if(hasRows($result)){
		$cnt = 0;
		$curDate = "";
		while($row = mysql_fetch_row($result)){
			if(($curDate != $row[9]) or ($cnt == 0)){
				$curDate = $row[9];?>
				<div class="eventDateTitle"><?php echo stampToDate($row[9], $hc_dateFormat);?></div>
		<?php 	$cnt = 0;
			}//end if?>
			<div class="<?php if($cnt % 2 == 0){echo "eventListTime";}else{echo "eventListTimeHL";}?>">
		<?php 	$startTime = "";
				$endTime = "";
				if($row[10] != ''){
					$timepart = explode(":", $row[10]);
					$startTime = date($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
				}//end if
				
				if($row[12] != ''){
					$timepart = explode(":", $row[12]);
					$endTime = '&nbsp;-&nbsp;' . date($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
				}//end if
				
				if($row[11] == 0){
					echo $startTime . $endTime;
				} elseif($row[11] == 1) {
					echo "<i>All Day Event</i>";
				} elseif($row[11] == 2) {
					echo "<i>TBA</i>";
				}//end if?>
			
		<?php 	$calSaver = "";
				if(isset($_GET['theDate'])){
					$calSaver = "&amp;month=" . $month . "&amp;year=" . $year;
				}//end if	?>
			</div>
			<div class="<?php if($cnt % 2 == 0){echo "eventListTitle";}else{echo "eventListTitleHL";}?>"><a href="<?php echo CalRoot;?>/index.php?com=detail&amp;eID=<?php echo $row[0] . $calSaver;?>" class="eventListTitle"><?php echo cOut($row[1]);?></a></div>
<?php 	$cnt++;
		}//end while
	} else {	?>
		<br />
		There are no events that meet that search criteria.<br />
		<a href="<?php echo CalRoot;?>/index.php?com=search" class="eventMain">Please click here to search again.</a>
		<br /><br />
<?php 
	}//end if?>