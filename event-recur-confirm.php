<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include(dirname(__FILE__).'/loader.php');
	include(HCLANG.'/public/search.php');
	
	if(!isset($_GET['recurType']) || !isset($_GET['eventDate']))
		go_home();
	
	action_headers();
	header('content-type: text/html; charset=' . $hc_lang_config['CharSet']);

	include(HCLANG.'/public/submit.php');
	
	echo '
		<a href="javascript:;" onclick="confirmRecurDates();">'.$hc_lang_submit['ConfirmDateAgain'].'</a><br /><br />
		<b>'.$hc_lang_submit['RecurNotice'] . '</b>';

	$eventDate = dateToMySQL(htmlspecialchars($_GET['eventDate']), $hc_cfg[24]);
	$dates = array();
	
	switch(htmlspecialchars($_GET['recurType'])){
		case 'daily':
			$curDate = $eventDate;
			$stopDate = dateToMySQL(htmlspecialchars($_GET['recurEndDate']), $hc_cfg[24]);
			
			if(htmlspecialchars($_GET['dailyOptions']) == 'EveryXDays'){
				$days = htmlspecialchars($_GET['dailyDays']);
				while(strtotime($curDate) <= strtotime($stopDate)){
					$dates[] = $curDate;
					$dateParts = explode("-", $curDate);
					$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + $days, $dateParts[0]));
				}
			} else {
				while(strtotime($curDate) <= strtotime($stopDate)){
					$dateParts = explode("-", $curDate);
					$curDayOfWeek = date("w", mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
					if((($curDayOfWeek != 0) AND ($curDayOfWeek != 6)) OR $eventDate == $curDate)
						$dates[] = $curDate;
					
					$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + 1, $dateParts[0]));
				}
			}
			break;
		case 'weekly':
			$curDate = $eventDate;
			$stopDate = dateToMySQL($_GET['recurEndDate'], $hc_cfg[24]);
			$weeks = $_GET['recWeekly'];
			$recWeeklyDay = explode(',', $_GET['recWeeklyDay']);
			
			while(strtotime($curDate) <= strtotime($stopDate)){
				$dateParts = explode("-", $curDate);
				$curDateDayOfWeek = date("w", mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
				
				if(in_array($curDateDayOfWeek, $recWeeklyDay) OR $eventDate == $curDate)
					$dates[] = $curDate;
				
				if(($curDateDayOfWeek == 6) AND ($weeks > 1))
					$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + ((($weeks - 1) * 7) + 1), $dateParts[0]));
				else
					$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + 1, $dateParts[0]));
			}
			break;
		case 'monthly':
			$curDate = $eventDate;
			$stopDate = dateToMySQL(htmlspecialchars($_GET['recurEndDate']), $hc_cfg[24]);
			
			if($_GET['monthlyOption'] == 'Day'){
				$day = htmlspecialchars($_GET['monthlyDays']);
				$months = htmlspecialchars($_GET['monthlyMonths']);
				
				while(strtotime($curDate) <= strtotime($stopDate)){
					$dates[] = $curDate;
					$dateParts = explode("-", $curDate);
					
					if($dateParts[2] < $day)
						$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $day, $dateParts[0]));
					else
						$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1] + $months, $day, $dateParts[0]));
				}
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
							++$x;
							++$cnt;
						}
						$curDate = date("Y-m-d", mktime(0, 0, 0, $curMonth + $whichRepeat, (1 + $cnt) + ((7 * $whichDay) - 7), $curYear));
					} else {
						$x = date("w", mktime(0, 0, 0, $curMonth + $whichRepeat + 1, 0, $curYear));
						$offset = 0;
						if($x < $whichDOW){$x = $x + 7;}
						while((abs($x) % 7) != $whichDOW){
							--$x;
							++$cnt;
						}
						$curDate = date("Y-m-d", mktime(0, 0, 0, $curMonth + $whichRepeat + 1, 0 - $cnt, $curYear));
					}
				}
			}
			break;
	}
	$x = 0;
	foreach ($dates as $val){
		echo ($x % 7 == 0) ? '<br />' : ', ';
		echo stampToDate($val, $hc_cfg[24]);
		++$x;
	}
?>