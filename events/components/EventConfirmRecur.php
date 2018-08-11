<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/	?>
<a href="javascript:;" onclick="confirmRecurDates();" class="eventMain">Click Here to Confirm Dates Again</a><br /><br />
<label>&nbsp;</label><b>With the current settings, this event will be added for:</b>
<?	include('../includes/code.php');
	
	$eventDate = dateToMySQL(htmlspecialchars($_GET['eventDate']), "/", $_GET['dateFormat']);
	$dates = array();
	
	switch(htmlspecialchars($_GET['recurType'])){
		case 'daily':
			$curDate = $eventDate;
			$stopDate = dateToMySQL(htmlspecialchars($_GET['recurEndDate']), "/", $_GET['dateFormat']);
			
			if(htmlspecialchars($_GET['dailyOptions']) == 'EveryXDays'){
				$days = htmlspecialchars($_GET['dailyDays']);
				while(strtotime($curDate) <= strtotime($stopDate)){
					$dates[] = $curDate;
					
					$dateParts = explode("-", $curDate);
					$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + $days, $dateParts[0]));
				}//end while
				
			} else {
				while(strtotime($curDate) <= strtotime($stopDate)){
					$dateParts = explode("-", $curDate);
					$curDayOfWeek = date("w", mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
					
					if((($curDayOfWeek != 0) AND ($curDayOfWeek != 6)) OR $eventDate == $curDate){
						$dates[] = $curDate;
					}//end if
					$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + 1, $dateParts[0]));
				}//end while
				
			}//end if
			break;
			
		case 'weekly':
			$curDate = $eventDate;
			$stopDate = dateToMySQL($_GET['recurEndDate'], "/", $_GET['dateFormat']);
			$weeks = $_GET['recWeekly'];
			$recWeeklyDay = explode(',', $_GET['recWeeklyDay']);
			
			while(strtotime($curDate) <= strtotime($stopDate)){
				$dateParts = explode("-", $curDate);
				$curDateDayOfWeek = date("w", mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
				
				if(in_array($curDateDayOfWeek, $recWeeklyDay) OR $eventDate == $curDate){
					$dates[] = $curDate;
				}//end if
				
				if(($curDateDayOfWeek == 6) AND ($weeks > 1)){
					$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + ((($weeks - 1) * 7) + 1), $dateParts[0]));
				} else {
					$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + 1, $dateParts[0]));
				}//end if
			}//end while
			break;
			
		case 'monthly':
			$curDate = $eventDate;
			$stopDate = dateToMySQL(htmlspecialchars($_GET['recurEndDate']), "/", $_GET['dateFormat']);
			
			if($_GET['monthlyOption'] == 'Day'){
				$day = htmlspecialchars($_GET['monthlyDays']);
				$months = htmlspecialchars($_GET['monthlyMonths']);
				
				while(strtotime($curDate) <= strtotime($stopDate)){
					$dates[] = $curDate;
					$dateParts = explode("-", $curDate);
					
					if($dateParts[2] < $day){
						$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $day, $dateParts[0]));
					} else {
						$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1] + $months, $day, $dateParts[0]));
					}//end if
				}//end while
			} else {
				$whichDay = htmlspecialchars($_GET['monthlyMonthOrder']);
				$whichDOW = htmlspecialchars($_GET['monthlyMonthDOW']);
				$whichRepeat = htmlspecialchars($_GET['monthlyMonthRepeat']);
				
				while(strtotime($curDate) <= strtotime($stopDate)){
					$dates[] = $curDate;
					
					$dateParts = explode("-", $curDate);
					$curMonth = $dateParts[1];
					$curYear = $dateParts[0];
					$cnt = 0;
					
					if($whichDay != 0){
						$x = date("w", mktime(0, 0, 0, $curMonth + $whichRepeat, 1, $curYear));
						while($x % 7 != $whichDOW){
							$x++;
							$cnt++;
						}//end if
						$curDate = date("Y-m-d", mktime(0, 0, 0, $curMonth + $whichRepeat, (1 + $cnt) + ((7 * $whichDay) - 7), $curYear));
					} else {
						$x = date("w", mktime(0, 0, 0, $curMonth + $whichRepeat + 1, 0, $curYear));
						$offset = 0;
						if($x < $whichDOW){$x = $x + 7;}
						while((abs($x) % 7) != $whichDOW){
							$x--;
							$cnt++;
						}//end if
						$curDate = date("Y-m-d", mktime(0, 0, 0, $curMonth + $whichRepeat + 1, 0 - $cnt, $curYear));
					}//end if
				}//end while
			}//end if
			break;
		
	}//end switch
	
	$x = 0;
	foreach ($dates as $val){
		if($x % 5 == 0){
			echo "<br /><label style=\"clear:both\">&nbsp;</label>";
		} else {
			echo ", ";
		}//end if
		echo stampToDate($val, $_GET['dateFormat']);
		$x++;
	}//end for	?>

