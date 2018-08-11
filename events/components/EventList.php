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
	include($hc_langPath . $_SESSION['LangSet'] . '/public/event.php');
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,$hc_lang_event['Feed02']);
				break;
		}//end switch
	}//end if
	
	if(isset($_GET['lID']) && is_numeric($_GET['lID'])){
		include('components/LocationDetail.php');
	}//end if
	
	$day = date("d");
	$month = date("m");
	$year = date("Y");
	$theDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
	$dayView = false;
	
	$hourOffset = date("G");
	if($hc_timezoneOffset > 0){
		$hourOffset = $hourOffset + abs($hc_timezoneOffset);
	} else {
		$hourOffset = $hourOffset - abs($hc_timezoneOffset);
	}//end if
	
	if(isset($_GET['month']) && is_numeric($_GET['month'])){
		if($_GET['month'] > $month || $_GET['year'] > $year || $hc_browsePast == 1){
			$day = 1;
		}//end if
		$month = $_GET['month'];
	}//end if
	
	if(isset($_GET['day']) && is_numeric($_GET['day'])){
		$day = $_GET['day'];
		$dayView = true;
	}//end if
	
	if(isset($_GET['year']) && is_numeric($_GET['year'])){
		$year = $_GET['year'];
	}//end if
	
	if(isset($_GET['theDate'])){
		$datePart = explode("-", $_GET['theDate']);
		$day = $datePart[2];
	}//end if
	
	$pastQuery = "";
	if(!checkdate($month, $day, $year) || (strtotime(date("Y-m-d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")))) > strtotime(date("Y-m-d", mktime(0,0,0,$month,$day,$year))) && $hc_browsePast == 0) ){
		$day = date("d",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));
		$month = date("n",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));
		$year = date("Y",mktime($hourOffset,date("i"),date("s"),date("m"),date("d"),date("Y")));
	
		$theDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
		$remove = (date("w", mktime(0,0,0,$month,$day,$year)) + 6) % 7;
		$dayView = false;
		
		$goErr = $hc_lang_event['InvalidDate'];
		if($hc_browsePast == 0){
			$pastQuery .= " AND e.StartDate >= '" . $theDate . "'";
			$goErr = $hc_lang_event['NoPastDates'];
		}//end if
		feedback(2, $goErr);
	} else {
		$theDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
		$remove = (date("w", mktime(0,0,0,$month,$day,$year)) + 6) % 7;
	}//end if
		
	if($theDate == date("Y-m-d")){
		$startDate = date("Y-m-d", mktime($hourOffset, date("i"), date("s"), $month, $day, $year));
	} else {
		$startDate = date("Y-m-d", mktime(0, 0, 0, $month, $day - $remove, $year));
	}//end if
	
	if($hc_browseType == 0){
		$lType = $hc_lang_event['Week'];
		$stopDate = date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) + 6, $year));
		
		$prevDate = date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) - 7, $year));
		$nextDate = date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) + 7, $year));
		
		$prevPart = explode("-", $prevDate);
		$prevMonth = $prevPart[1];
		$prevYear = $prevPart[0];
		
		$nextPart = explode("-", $nextDate);
		$nextMonth = $nextPart[1];
		$nextYear = $nextPart[0];
		
		$prevLink =  CalRoot . "/?theDate=" . $prevDate . "&amp;year=" . $prevYear . "&amp;month=" . $prevMonth;
		$nextLink = CalRoot . "/?theDate=" . $nextDate . "&amp;year=" . $nextYear . "&amp;month=" . $nextMonth;
	} else {
		$lType = $hc_lang_event['Month'];
		$stopDate = date("Y-m-d", mktime(0, 0, 0, $month + 1, 0, $year));
		
		$prevLink = CalRoot . "?year=" . date("Y", mktime(0, 0, 0, $month - 1, 1, $year)) . "&amp;month=" . date("m", mktime(0, 0, 0, $month -1, 1, $year));
		$nextLink = CalRoot . "?year=" . date("Y", mktime(0, 0, 0, $month + 1, 1, $year)) . "&amp;month=" . date("m", mktime(0, 0, 0, $month + 1, 1, $year));
	}//end if
	
	$query = "	SELECT DISTINCT e.*
				FROM " . HC_TblPrefix . "events e
					LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (ec.EventID = e.PkID)
					LEFT JOIN " . HC_TblPrefix . "locations as l ON (e.LocID = l.PkID) ";
	
	if(!$dayView == false){
		$query .=" WHERE e.StartDate = '" . $theDate . "'";
	} else {
		$query .= "	WHERE e.StartDate BETWEEN '" . cIn($startDate) . "' AND '" . cIn($stopDate) . "' ";
	}//end if
	
	$query .= $pastQuery;
	$query .= "	AND e.IsActive = 1 
				AND e.IsApproved = 1 ";
	
	if(isset($_SESSION['hc_favCat']) && $_SESSION['hc_favCat'] != ''){
		$query = $query . " AND ec.CategoryID in (" . $_SESSION['hc_favCat'] . ") ";
	}//end if
	
	if(isset($_SESSION['hc_favCity']) && $_SESSION['hc_favCity'] != ''){
		$query = $query . " AND (e.LocationCity IN (" . $_SESSION['hc_favCity'] . ") OR l.City IN (" . $_SESSION['hc_favCity'] . "))";
	}//end if
	
	$locSaver = "";
	if(isset($locID) && $locID > 0){
		$query = $query . " AND LocID = '" . cIn($locID) . "'";
		$locSaver = "&lID=" . cIn($locID);
		$prevLink = $prevLink . $locSaver;
		$nextLink = $nextLink . $locSaver;
	}//end if
	
	$query = $query . " ORDER BY e.StartDate, e.TBD, e.StartTime, e.Title";
	
	$result = doQuery($query);	?>
	<div id="nav-top">
		<a href="<?php echo CalRoot;?>/index.php?com=filter" title="<?php echo $hc_lang_event['Filter'];?>"><img src="<?php echo CalRoot;?>/images/nav/filter.png" alt="" border="0" /></a>
		<a href="<?php echo CalRoot . "/"; if($locSaver != ''){echo "?" . substr($locSaver,1);}?>" title="<?php echo $hc_lang_event['Home'];?>"><img src="<?php echo CalRoot;?>/images/nav/home.png" alt="" border="0" /></a>
		<a href="<?php echo $prevLink;?>" title="<?php echo $hc_lang_event['BrowseBack'] . " " . $lType;?>"><img src="<?php echo CalRoot;?>/images/nav/left.png" alt="" border="0" /></a>
		<a href="<?php echo $nextLink;?>" title="<?php echo $hc_lang_event['BrowseForward'] . " " . $lType;?>"><img src="<?php echo CalRoot;?>/images/nav/right.png" alt="" border="0" /></a>
	</div>
<?php
	if(hasRows($result)){
		$cnt = 0;
		$curDate = "";
		$calSaver = "&amp;year=" . $year . "&amp;month=" . $month . $locSaver;
		while($row = mysql_fetch_row($result)){
			if(($curDate != $row[9]) or ($cnt == 0)){
				$curDate = $row[9];?>
				<div class="eventDateTitle"><?php echo stampToDate($row[9], $hc_dateFormat);?></div>
		<?php 	$cnt = 0;
			}//end if?>
			<div class="eventListTime<?php if($cnt % 2 == 1){echo "HL";}//end if?>">
		<?php 	$startTime = "";
				$endTime = "";
				if($row[10] != ''){
					$timepart = explode(":", $row[10]);
					$startTime = strftime($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
				}//end if
				
				if($row[12] != ''){
					$timepart = explode(":", $row[12]);
					$endTime = '&nbsp;-&nbsp;' . strftime($hc_timeFormat, mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
				}//end if
				
				if($row[11] == 0){
					echo $startTime . $endTime;
				} elseif($row[11] == 1) {
					echo "<i>" . $hc_lang_event['AllDay'] . "</i>";
				} elseif($row[11] == 2) {
					echo "<i>" . $hc_lang_event['TBA'] . "</i>";
				}//end if?>
			</div>
			<div class="eventListTitle<?php if($cnt % 2 == 1){echo "HL";}//end if?>"><a href="<?php echo CalRoot;?>/index.php?com=detail&amp;eID=<?php echo $row[0] . $calSaver;?>" class="eventListTitle"><?php echo cOut($row[1]);?></a></div>
<?php 	$cnt++;
		}//end while
	} else {
		echo $hc_lang_event['NoEventBrowse'];?>
		<ol>
			<li style="line-height: 30px;"><?php echo $hc_lang_event['NoEvent1'];?> <span class="miniCalEvents" style="padding:3px;">03</span></li>
			<li style="line-height: 30px;"><?php echo $hc_lang_event['NoEvent2'];?> <span class="miniCalNav" style="padding:3px;">&lt;</span> <span class="miniCalNav" style="padding:3px;">&gt;</span></li>
	<?php	if(isset($_SESSION["hc_favCat"]) || isset($_SESSION['hc_favCity'])){
			echo "<li>" . $hc_lang_event['NoEvent4'] . " <a href=\"" . CalRoot . "/index.php?com=filter\" class=\"eventMain\"><img src=\"" . CalRoot . "/images/nav/filter.png\" width=\"16\" height=\"16\" alt=\"\" style=\"vertical-align:middle;\" /></a></li>";
			}//end if	
		echo "</ol>";
	}//end if	?>
	<div id="nav-bottom">
		<a href="<?php echo CalRoot;?>/index.php?com=filter" title="<?php echo $hc_lang_event['Filter'];?>"><img src="<?php echo CalRoot;?>/images/nav/filter.png" alt="" border="0" /></a>
		<a href="<?php echo CalRoot . "/"; if($locSaver != ''){echo "?" . substr($locSaver,1);}?>" title="<?php echo $hc_lang_event['Home'];?>"><img src="<?php echo CalRoot;?>/images/nav/home.png" alt="" border="0" /></a>
		<a href="<?php echo $prevLink;?>" title="<?php echo $hc_lang_event['BrowseBack'] . " " . $lType;?>"><img src="<?php echo CalRoot;?>/images/nav/left.png" alt="" border="0" /></a>
		<a href="<?php echo $nextLink;?>" title="<?php echo $hc_lang_event['BrowseForward'] . " " . $lType;?>"><img src="<?php echo CalRoot;?>/images/nav/right.png" alt="" border="0" /></a>
	</div>