<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	if(isset($_GET['l'])){
		$catIDs = "0";
		$catID = explode(",", $_GET['l']);
		foreach ($catID as $val){
			if(is_numeric($val)){
				$catIDs = $catIDs . "," . $val;
			}
		}//end while
		if($catIDs != "0"){
			$_SESSION['hc_favorites'] = cIn($catIDs);
			
		} else {
			unset($_SESSION["hc_favorites"]);
		}//end if
	}//end if
	
	if (isset($_GET['msg'])){
		switch ($_GET['msg']){
			case "1" :
				feedback(1,"Newsletter Subscription Successfully Deleted.");
				break;
				
		}//end switch
	}//end if
	
	$day = date("d");
	$month = date("m");
	$year = date("Y");
	
	if(isset($_GET['month']) && is_numeric($_GET['month'])){
		if($_GET['month'] > $month || $_GET['year'] > $year){
			$day = 1;
		}//end if
		$month = $_GET['month'];
	}//end if
	
	if(isset($_GET['day']) && is_numeric($_GET['day'])){
		$day = $_GET['day'];
		$dayView = 1;
	}//end if
	
	if(isset($_GET['year']) && is_numeric($_GET['year'])){
		$year = $_GET['year'];
	}//end if
	
	if(isset($_GET['theDate'])){
		$theDate = $_GET['theDate'];
		$datePart = explode("-", $theDate);
		$day = $datePart[2];
		
	} else {
		$theDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
	}//end if
	
	//	check for valid date
	if($hc_browsePast == 0){
		if( ($theDate < date("Y-m-d")) || (!checkdate($month, $day, $year)) ){
			feedback(2, "Unable to Display Invalid or Past Date");
			
			$day = date("d");
			$month = date("m");
			$year = date("Y");
			$theDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
			if(isset($dayView)){
				$dayView = false;
			}//end if
		}//end if
		
	} else {
		if( (!isset($_GET['day']) && $theDate < date("Y-m-d")) || (!checkdate($month, $day, $year)) ){
			if(!checkdate($month, $day, $year)){
				feedback(2, "Unable to Display Invalid Date");
			} else {
				feedback(1, "To View Past Events Select a Single Day From the Mini-Cal.");
			}//end if
			
			$day = date("d");
			$month = date("m");
			$year = date("Y");
			$theDate = date("Y-m-d", mktime(0,0,0,$month,$day,$year));
			if(isset($dayView)){
				$dayView = false;
			}//end if
		}//end if
		
	}//end if
	
	$remove = (date("w", mktime(0,0,0,$month,$day,$year)) + 6) % 7;
	
	if(isset($dayView) && $dayView == true){
	//	show only this day
		$query = "	SELECT DISTINCT " . HC_TblPrefix . "events.*
					FROM " . HC_TblPrefix . "events 
						LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "eventcategories.EventID = " . HC_TblPrefix . "events.PkID)
					WHERE " . HC_TblPrefix . "events.StartDate = '" . $theDate . "'";
						if($hc_browsePast == 0){
							$query .= " AND " . HC_TblPrefix . "events.StartDate >= NOW() ";
						}//end if
			$query .= "	AND " . HC_TblPrefix . "events.IsActive = 1 
						AND " . HC_TblPrefix . "events.IsApproved = 1 ";
		
	} else {
	//	show this week through sunday
		$startDate = date("Y-m-d", mktime(0, 0, 0, $month, $day - $remove, $year));
		$stopDate = date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) + 6, $year));
		
		$query = "	SELECT DISTINCT " . HC_TblPrefix . "events.*
					FROM " . HC_TblPrefix . "events 
						LEFT JOIN " . HC_TblPrefix . "eventcategories ON (" . HC_TblPrefix . "eventcategories.EventID = " . HC_TblPrefix . "events.PkID)
					WHERE StartDate BETWEEN '" . cIn($startDate) . "' AND '" . cIn($stopDate) . "'
						AND " . HC_TblPrefix . "events.StartDate >= NOW() 
						AND " . HC_TblPrefix . "events.IsActive = 1 
						AND " . HC_TblPrefix . "events.IsApproved = 1 ";
	}//end if
	
	if( isset($_SESSION['hc_favorites']) && $_SESSION['hc_favorites'] != '' ){
		$query = $query . " AND " . HC_TblPrefix . "eventcategories.CategoryID in (" . $_SESSION['hc_favorites'] . ") ";
	}//end if
	
	$query = $query . " ORDER BY " . HC_TblPrefix . "events.StartDate, " . HC_TblPrefix . "events.TBD,  " . HC_TblPrefix . "events.StartTime, " . HC_TblPrefix . "events.Title";
	
	$result = doQuery($query);	?>
	
<?	$prevDate = date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) - 7, $year));
	$prevPart = explode("-", $prevDate);
	$prevMonth = $prevPart[1];
	$prevYear = $prevPart[0];
	
	$nextDate = date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) + 7, $year));
	$nextPart = explode("-", $nextDate);
	$nextMonth = $nextPart[1];
	$nextYear = $nextPart[0];	?>
	<div id="nav-top">
		<a href="<?echo CalRoot;?>/index.php?com=filter" title="Select Your Favorite Categories"><img src="<?echo CalRoot;?>/images/nav/filter.gif" alt="" border="0" /></a>
		<a href="<?echo CalRoot;?>/" title="Return to This Weeks Events"><img src="<?echo CalRoot;?>/images/nav/home.gif" alt="" border="0" /></a>
		<a href="<?echo CalRoot;?>/?theDate=<?echo $prevDate?>&amp;year=<?echo $prevYear;?>&amp;month=<?echo $prevMonth;?>" title="Browse Back One Week"><img src="<?echo CalRoot;?>/images/nav/left.gif" alt="" border="0" /></a>
		<a href="<?echo CalRoot;?>/?theDate=<?echo $nextDate?>&amp;year=<?echo $nextYear;?>&amp;month=<?echo $nextMonth;?>" title="Browse Forward One Week"><img src="<?echo CalRoot;?>/images/nav/right.gif" alt="" border="0" /></a>
	</div>
<?	if(hasRows($result)){
		$cnt = 0;
		$curDate = "";
		while($row = mysql_fetch_row($result)){
			if(($curDate != $row[9]) or ($cnt == 0)){
				$curDate = $row[9];?>
				<div class="eventDateTitle"><?echo stampToDate($row[9], "l, " . $hc_dateFormat);?></div>
			<?	$cnt = 0;
			}//end if?>
			<div class="<?if($cnt % 2 == 0){echo "eventListTime";}else{echo "eventListTimeHL";}//end if?>">
			<?	$startTime = "";
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
			
			<?	$calSaver = "";
				$calSaver = "&amp;year=" . $year . "&amp;month=" . $month;?>
			</div>
			<div class="<?if($cnt % 2 == 0){echo "eventListTitle";}else{echo "eventListTitleHL";}//end if?>"><a href="<?echo CalRoot;?>/index.php?com=detail&amp;eID=<?echo $row[0] . $calSaver;?>" class="eventListTitle"><?echo cOut($row[1]);?></a></div>
	<?	$cnt++;
		}//end while
	} else {
	?>
		You are browsing criteria that there currently isn't events for.
		<br /><br />
		From here you can:
		<ol>
			<li style="line-height: 30px;">Select a shaded day <span class="miniCalEvents" style="padding:3px;">17</span> from the mini-cal.</li>
			<li style="line-height: 30px;">Use the mini-cal to Navigate forward <span class="miniCalNav" style="padding:3px;">&gt;</span> or backward <span class="miniCalNav" style="padding:3px;">&lt;</span> a month.</li>
			
			<?if(isset($_SESSION['hc_favorites'])){	?>
			<li>Click <a href="<?echo CalRoot;?>/index.php?com=filter" class="eventMain"><img src="<?echo CalRoot;?>/images/nav/filter.gif" width="16" height="16" alt=""></a> and add additional categories to your filter.</li>
			<?}//end if	?>
		</ol>
<?	}//end if	?>
	<div id="nav-bottom">
		<a href="<?echo CalRoot;?>/index.php?com=filter" title="Select Your Favorite Categories"><img src="<?echo CalRoot;?>/images/nav/filter.gif" alt="" border="0" /></a>
		<a href="<?echo CalRoot;?>/" title="Return to This Weeks Events"><img src="<?echo CalRoot;?>/images/nav/home.gif" alt="" border="0" /></a>
		<a href="<?echo CalRoot;?>/?theDate=<?echo $prevDate?>&amp;year=<?echo $prevYear;?>&amp;month=<?echo $prevMonth;?>" title="Browse Back One Week"><img src="<?echo CalRoot;?>/images/nav/left.gif" alt="" border="0" /></a>
		<a href="<?echo CalRoot;?>/?theDate=<?echo $nextDate?>&amp;year=<?echo $nextYear;?>&amp;month=<?echo $nextMonth;?>" title="Browse Forward One Week"><img src="<?echo CalRoot;?>/images/nav/right.gif" alt="" border="0" /></a>
	</div>