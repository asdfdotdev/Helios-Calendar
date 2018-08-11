<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
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
	if($browsePast == 0){
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
				feedback(2, "To View Past Events Select a Single Day.");
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
						if($browsePast == 0){
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
					WHERE StartDate BETWEEN '" . $startDate . "' AND '" . $stopDate . "'
						AND " . HC_TblPrefix . "events.StartDate >= NOW() 
						AND " . HC_TblPrefix . "events.IsActive = 1 
						AND " . HC_TblPrefix . "events.IsApproved = 1 ";
	}//end if
	
	if( isset($_SESSION['BrowseCatIDs']) ){
		$query = $query . " AND " . HC_TblPrefix . "eventcategories.CategoryID in (" . $_SESSION['BrowseCatIDs'] . ") ";
	}//end if
	
	$query = $query . " ORDER BY " . HC_TblPrefix . "events.StartDate, " . HC_TblPrefix . "events.TBD,  " . HC_TblPrefix . "events.StartTime, " . HC_TblPrefix . "events.Title";
	
	$result = doQuery($query);
	//echo $query;?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td colspan="3" align="right">
				<?php
					$prevDate = date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) - 7, $year));
					$prevPart = explode("-", $prevDate);
					$prevMonth = $prevPart[1];
					$prevYear = $prevPart[0];
					
					$nextDate = date("Y-m-d", mktime(0, 0, 0, $month, ($day - $remove) + 7, $year));
					$nextPart = explode("-", $nextDate);
					$nextMonth = $nextPart[1];
					$nextYear = $nextPart[0];
				?>
				<a href="<?echo CalRoot;?>/"><img src="<?echo CalRoot;?>/images/nav_home.gif" width="20" height="19" alt="" border="0"></a>
				<a href="<?echo CalRoot;?>/index.php?com=filter"><img src="<?echo CalRoot;?>/images/nav_filter.gif" width="20" height="19" alt="" border="0"></a>
				<a href="<?echo CalRoot;?>/?theDate=<?echo $prevDate?>&month=<?echo $prevMonth;?>&year=<?echo $prevYear;?>"><img src="<?echo CalRoot;?>/images/nav_left.gif" width="20" height="19" alt="" border="0"></a>
				<a href="<?echo CalRoot;?>/?theDate=<?echo $nextDate?>&month=<?echo $nextMonth;?>&year=<?echo $nextYear;?>"><img src="<?echo CalRoot;?>/images/nav_right.gif" width="20" height="19" alt="" border="0"></a>
			</td>
		</tr><?
	if(hasRows($result)){
		$cnt = 0;
		$curDate = "";
		while($row = mysql_fetch_row($result)){
			if(($curDate != $row[9]) or ($cnt == 0)){
				$curDate = $row[9];
				?>
				<tr><td colspan="3"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="10" alt="" border="0"><br></td></tr>
				<tr>
					<td class="eventMain" colspan="3">
						<b><?php
							$datepart = split("-",$row[9]);
							$datestamp = date("l, F jS Y", mktime(0,0,0,$datepart[1],$datepart[2],$datepart[0]));
							echo $datestamp;
						?></b>
					</td>
				</tr>
				<tr><td bgcolor="#666666" colspan="3"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>
			<?
				$cnt = 0;
			}//end if
		?>
			<tr>
				<td style="padding:2px;" <?if($cnt % 2 == 1){echo "class=\"eventListHL\"";}else{echo "class=\"eventMain\"";}//end if?> width="120" class="eventMain">
					<?php
						//check for valid start time
						if($row[10] != ''){
							$timepart = split(":", $row[10]);
							$startTime = date("h:i\&\\n\\b\\s\\p\\;A", mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
						} else {
							$startTime = "";
						}//end if
						
						//check for valid end time
						if($row[12] != ''){
							$timepart = split(":", $row[12]);
							$endTime = '-' . date("h:i\&\\n\\b\\s\\p\\;A", mktime($timepart[0], $timepart[1], $timepart[2], 01, 01, 1971));
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
				<td style="padding:2px;" <?if($cnt % 2 == 1){echo "class=\"eventListHL\"";}else{echo "class=\"eventMain\"";}//end if?> width="10">&nbsp;</td>
				<td style="padding:2px;" <?if($cnt % 2 == 1){echo "class=\"eventListHL\"";}else{echo "class=\"eventMain\"";}//end if?>>
					<?php
						if(isset($_GET['theDate'])){
							$calSaver = "&month=" . $month . "&year=" . $year;
						} else {
						 $calSaver = "";
						}//end if
					?>
						<a href="<?echo CalRoot;?>/index.php?com=detail&eID=<?echo $row[0] . $calSaver;?>" class="eventMain"><?echo $row[1];?></a>
					
				</td>
			</tr>
		<?
		$cnt++;
		}//end while
	} else {
	?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="eventMain">
				<br>
				There are no events scheduled for that date.
				<br><br>
				Please continue to navigate to select a new date.<br><br>
				<a href="<?echo CalRoot;?>" class="eventMain">Click here to view this weeks events.</a> 
				
				<?if(isset($_SESSION['BrowseCatIDs'])){	?>
				Or<br><br>
				<a href="<?echo CalRoot;?>/index.php?com=filter" class="eventMain">Click here to add more categories to your filter.</a>
				<?}//end if	?>
				<br><br>
			</td>
		</tr>
	<?php
	}//end if	?>
	<tr>
		<td colspan="3" align="right">
			<br>
			<a href="<?echo CalRoot;?>/"><img src="<?echo CalRoot;?>/images/nav_home.gif" width="20" height="19" alt="" border="0"></a>
			<a href="<?echo CalRoot;?>/index.php?com=filter"><img src="<?echo CalRoot;?>/images/nav_filter.gif" width="20" height="19" alt="" border="0"></a>
			<a href="<?echo CalRoot;?>/?theDate=<?echo $prevDate?>&month=<?echo $prevMonth;?>&year=<?echo $prevYear;?>"><img src="<?echo CalRoot;?>/images/nav_left.gif" width="20" height="19" alt="" border="0"></a>
			<a href="<?echo CalRoot;?>/?theDate=<?echo $nextDate?>&month=<?echo $nextMonth;?>&year=<?echo $nextYear;?>"><img src="<?echo CalRoot;?>/images/nav_right.gif" width="20" height="19" alt="" border="0"></a>
		</td>
	</tr>
</table>