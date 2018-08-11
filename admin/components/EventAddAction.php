<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/event.php');

	$eID = 0;
	$eventStatus = $_POST['eventStatus'];
	$eventBillboard = $_POST['eventBillboard'];
	$eventTitle = cleanQuotes($_POST['eventTitle']);
	$eventDesc = cleanQuotes($_POST['eventDescription']);
	$eventDate = dateToMySQL($_POST['eventDate'], "/", $hc_cfg24);
	$contactName = $_POST['contactName'];
	$contactEmail = $_POST['contactEmail'];
	$contactPhone = $_POST['contactPhone'];
	$contactURL = $_POST['contactURL'];
	$allowRegistration = $_POST['eventRegistration'];
	$locID = $_POST['locPreset'];
	$startTimeHour = isset($_POST['startTimeHour']) ? $_POST['startTimeHour'] : NULL;
	$endTimeHour = isset($_POST['endTimeHour']) ? $_POST['endTimeHour'] : NULL;
	$startTime = "NULL";
	$endTime = "NULL";
	$maxRegistration = 0;
	$cost = $_POST['cost'];
	$msgID = 2;
	$dates = array();
	$catID = (isset($_POST['catID'])) ? $_POST['catID'] : '';
	$apiFail = false;
	
	if($locID > 0){
		$locName = "";
		$locAddress = "";
		$locAddress2 = "";
		$locCity = "";
		$locState = "";
		$locZip = "";
		$locCountry = "";
	} else {
		$locName = cleanQuotes($_POST['locName']);
		$locAddress = $_POST['locAddress'];
		$locAddress2 = $_POST['locAddress2'];
		$locCity = $_POST['locCity'];
		$locState = $_POST['locState'];
		$locZip = $_POST['locZip'];
		$locCountry = $_POST['locCountry'];
	}//end if
	
	$contactURL = (!ereg("^http://*", $contactURL, $regs)) ? "http://" . $contactURL : $contactURL;
	$allowRegistration = htmlspecialchars($_POST['eventRegistration']);
	$maxRegistration = ($allowRegistration == 1) ? $_POST['eventRegAvailable'] : 0;
	$publishDate = ($eventStatus == 1) ? "'" . cIn(date("Y-m-d H:i:s")) . "'" : 'NULL';
	
	if(!isset($_POST['overridetime'])){
		if($hc_cfg31 == 12){
			$startTimeHour = ($_POST['startTimeAMPM'] == 'PM') ? ($_POST['startTimeHour'] < 12 ? $_POST['startTimeHour'] + 12 : $_POST['startTimeHour']) : ($_POST['startTimeHour'] == 12 ? 0 : $_POST['startTimeHour']);
			if(!isset($_POST['ignoreendtime'])){
				$endTimeHour = ($_POST['endTimeAMPM'] == 'PM') ? ($_POST['endTimeHour'] < 12 ? $_POST['endTimeHour'] + 12 : $_POST['endTimeHour']) : ($_POST['endTimeHour'] == 12 ? 0 : $_POST['endTimeHour']);
			}//end if
		}//end if
		
		$tbd = 0;
		$startTime = "'" . cIn($startTimeHour) . ":" . cIn($_POST['startTimeMins']) . ":00'";
		$endTime = (!isset($_POST['ignoreendtime'])) ? "'" . cIn($endTimeHour) . ":" . cIn($_POST['endTimeMins']) . ":00'" : 'NULL';
	} else {
		$startTime = 'NULL';
		$endTime = 'NULL';
		$tbd = ($_POST['specialtime'] == 'allday') ? 1 : 2;
	}//end if


	if(isset($_POST['recurCheck'])){
		$seriesID = "'" . DecHex(microtime() * 9999999) . DecHex(microtime() * 5555555) . DecHex(microtime() * 1111111) . "'";
		
		switch($_POST['recurType']){
			case 'daily':
				$days = $_POST['dailyDays'];
				$curDate = $eventDate;
				$stopDate = dateToMySQL($_POST['recurEndDate'], "/", $hc_cfg24);
				
				if($_POST['dailyOptions'] == 'EveryXDays'){
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
				$stopDate = dateToMySQL($_POST['recurEndDate'], "/", $hc_cfg24);
				$weeks = $_POST['recWeekly'];
				$recWeeklyDay = $_POST['recWeeklyDay'];
				
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
				$stopDate = dateToMySQL($_POST['recurEndDate'], "/", $hc_cfg24);
				
				if($_POST['monthlyOption'] == 'Day'){
					$day = $_POST['monthlyDays'];
					$months = $_POST['monthlyMonths'];
					
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
					$whichDay = $_POST['monthlyMonthOrder'];
					$whichDOW = $_POST['monthlyMonthDOW'];
					$whichRepeat = $_POST['monthlyMonthRepeat'];
					
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
	} else {
		$seriesID = 'NULL';
		$dates[] = $eventDate;
	}//end if
	
	foreach($dates as $eventDate){
		doQuery("INSERT INTO " . HC_TblPrefix . "events(Title, LocationName, LocationAddress, LocationAddress2,
						LocationCity, LocationState, LocationZip, Description,
						StartDate, StartTime, TBD, EndTime, ContactName,
						ContactEmail, ContactPhone, ContactURL, IsActive, IsApproved,
						IsBillboard, SeriesID, AllowRegister, SpacesAvailable, PublishDate, LocID, Cost, LocCountry)
				VALUES('" . cIn($eventTitle) . "', '" . cIn($locName) . "', '" . cIn($locAddress) . "', '" . cIn($locAddress2) . "',
						'" . cIn($locCity) . "', '" . cIn($locState) . "', '" . cIn($locZip) . "', '" . cIn($eventDesc) . "',
						'" . cIn($eventDate) . "', " . $startTime . ", " . cIn($tbd) . ", " . $endTime . ",
						'" . cIn($contactName) . "', '" . cIn($contactEmail) . "', '" . cIn($contactPhone) . "', '" . cIn($contactURL) . "',
						'1', '" . cIn($eventStatus) . "', '" . cIn($eventBillboard) . "', " . $seriesID . ",
						'" . cIn($allowRegistration) . "', '" . cIn($maxRegistration) . "', " . $publishDate . ",
						'" . cIn($locID) . "', '" . cIn($cost) . "', '" . cIn($locCountry) ."')");

		$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "events");
		$newPkID = mysql_result($result,0,0);
		$eID = ($eID == 0) ? $newPkID : $eID;
		foreach($catID as $val){
			doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($newPkID) . "', '" . cIn($val) . "')");
		}//end foreach

		if(isset($_POST['doEventful'])){
			$efID = '';
			include('../../events/includes/api/eventful/EventEdit.php');

			if($efID != ''){
				doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
						VALUES('" . $newPkID . "','" . cIn($efID) . "',1,1);");
			}//end if
		}//end if

		if(isset($_POST['doEventbrite'])){
			$ebID = '';
			include('../../events/includes/api/eventbrite/EventEdit.php');

			if($ebID != ''){
				doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
						VALUES('" . $newPkID . "','" . cIn($ebID) . "',2,1);");
			}//end if
		}//end if
	}//end for

	if(isset($_POST['doTwitter'])){
		$shortLink = CalRoot . "/index.php?com=detail&eID=" . $eID;
		require_once('../../events/includes/api/bitly/ShortenURL.php');
		
		$tweetLink = $shortLink;
		$tweetHash = $hc_cfg59;
		$twtrMsg = cleanQuotes($_POST['tweetThis']) . ' ' . $tweetLink . ' ' . $tweetHash;
		$showStatus = 1;
		require_once('../../events/includes/api/twitter/PostTweet.php');

		if($tweetID != ''){
			doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
					VALUES('" . $newPkID . "','" . cIn($tweetID) . "',3,1);");
		}//end if
	}//end if

	$hourOffset = date("G") + ($hc_cfg35);
	$curCache = date("Ymd", mktime($hourOffset,0,0,date("m"),date("d"),date("Y")));
	if(file_exists(realpath('../../events/cache/lmap' . $curCache . '.php'))){
		unlink('../../events/cache/lmap' . $curCache . '.php');
	}//end if
	if(file_exists(realpath('../../events/cache/sitemap_events.php'))){
		foreach(glob('../../events/cache/sitemap*.*') as $filename){
			unlink($filename);
		}//end foreach
	}//end if

	if($apiFail == false){
		header("Location: " . CalAdminRoot . "/index.php?com=eventedit&msg=" . $msgID . "&eID=" . $eID);
	} else {
		echo '<br /><br />' . $hc_lang_event['APIError'] . '<br /><br />';
		echo '<a href="' . CalAdminRoot . '/index.php?com=eventedit&msg=' . $msgID . '&eID=' . $eID . '">' . $hc_lang_event['APIErrorLink'] . '</a>';
	}//end if?>