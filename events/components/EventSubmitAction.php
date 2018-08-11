<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt();
	
	if($hc_cfg1 == 0){
		exit();
	}//end if
	
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/event.php');
	
	$appStatus = 2;	//1 = Auto Approve, 2 = Submit to Pending Queue (Default)
		
	$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
	spamIt($proof, 1);
	
	$subName = htmlspecialchars($_POST['submitName']);
	$subEmail = htmlspecialchars($_POST['submitEmail']);
	$eventTitle = htmlspecialchars($_POST['eventTitle']);
	$eventDesc = $_POST['eventDescription'];
	$eventDate = dateToMySQL(htmlspecialchars($_POST['eventDate']), "/", $hc_cfg24);
	$locID = $_POST['locPreset'];
	$contactName = htmlspecialchars($_POST['contactName']);
	$contactEmail = htmlspecialchars($_POST['contactEmail']);
	$contactPhone = htmlspecialchars($_POST['contactPhone']);
	$contactURL = htmlspecialchars($_POST['contactURL']);
	$cost = htmlspecialchars($_POST['cost']);
	$startTimeHour = $_POST['startTimeHour'];
	$endTimeHour = $_POST['endTimeHour'];
	
	if($locID > 0){
		$locName = "";
		$locAddress = "";
		$locAddress2 = "";
		$locCity = "";
		$locState = "";
		$locZip = "";
		$locCountry = "";
	} else {
		$locName = $_POST['locName'];
		$locAddress = $_POST['locAddress'];
		$locAddress2 = $_POST['locAddress2'];
		$locCity = $_POST['locCity'];
		$locState = $_POST['locState'];
		$locZip = $_POST['locZip'];
		$locCountry = $_POST['locCountry'];
	}//end if
	
	$contactURL = (!ereg("^http://*", $contactURL, $regs)) ? "http://" . $contactURL : $contactURL;
	$allowRegistration = htmlspecialchars($_POST['eventRegistration']);
	$adminMessage = (isset($_POST['adminmessage'])) ? "'" . cIn(htmlspecialchars($_POST['adminmessage'])) . "'" : 'NULL';
	$maxRegistration = ($allowRegistration == 1) ? $_POST['eventRegAvailable'] : 0;
	
	if(!isset($_POST['overridetime'])){
		if($hc_cfg31 == 12){
			$startTimeHour = ($_POST['startTimeAMPM'] == 'PM') ? ($_POST['startTimeHour'] < 12 ? $_POST['startTimeHour'] + 12 : $_POST['startTimeHour']) : ($_POST['startTimeHour'] == 12 ? 0 : $_POST['startTimeHour']);
			$endTimeHour = ($_POST['endTimeAMPM'] == 'PM') ? ($_POST['endTimeHour'] < 12 ? $_POST['endTimeHour'] + 12 : $_POST['endTimeHour']) : ($_POST['endTimeHour'] == 12 ? 0 : $_POST['endTimeHour']);
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
		$dates = array();
		
		switch(htmlspecialchars($_POST['recurType'])){
			case 'daily':
				$days = htmlspecialchars($_POST['dailyDays']);
				$curDate = $eventDate;
				$stopDate = dateToMySQL(htmlspecialchars($_POST['recurEndDate']), "/", $hc_cfg24);
				
				if(htmlspecialchars($_POST['dailyOptions']) == 'EveryXDays'){
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
				$stopDate = dateToMySQL(htmlspecialchars($_POST['recurEndDate']), "/", $hc_cfg24);
				
				if($_POST['monthlyOption'] == 'Day'){
					$day = htmlspecialchars($_POST['monthlyDays']);
					$months = htmlspecialchars($_POST['monthlyMonths']);
					
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
					$whichDay = htmlspecialchars($_POST['monthlyMonthOrder']);
					$whichDOW = htmlspecialchars($_POST['monthlyMonthDOW']);
					$whichRepeat = htmlspecialchars($_POST['monthlyMonthRepeat']);
					
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
							}//end if
							$curDate = date("Y-m-d", mktime(0, 0, 0, $curMonth + $whichRepeat, (1 + $cnt) + ((7 * $whichDay) - 7), $curYear));
						} else {
							$x = date("w", mktime(0, 0, 0, $curMonth + $whichRepeat + 1, 0, $curYear));
							$offset = 0;
							if($x < $whichDOW){$x = $x + 7;}
							while((abs($x) % 7) != $whichDOW){
								--$x;
								++$cnt;
							}//end if
							$curDate = date("Y-m-d", mktime(0, 0, 0, $curMonth + $whichRepeat + 1, 0 - $cnt, $curYear));
						}//end if
					}//end while
				}//end if
				break;
			
		}//end switch
		
		$curSubmit = (isset($_SESSION[$hc_cfg00 . 'hc_curSubmit'])) ? $_SESSION[$hc_cfg00 . 'hc_curSubmit'] : 0;
		
		if((count($dates) + $curSubmit) >= $hc_cfg40){
			exit($hc_lang_event['NoSubmit']);
		} else {
			$_SESSION[$hc_cfg00 . 'hc_curSubmit'] = $curSubmit + count($dates);
		}//end if
		
		$eventID = DecHex(microtime() * 1000000) . DecHex(microtime() * 9999999) . DecHex(microtime() * 8888888);
		foreach ($dates as $val){
			$eventDate = $val;
			$query = "	INSERT INTO " . HC_TblPrefix . "events(Title, LocationName, LocationAddress, LocationAddress2, 
									 LocationCity, LocationState, LocationZip, Description,
									 StartDate, StartTime, TBD, EndTime, ContactName,
									 ContactEmail, ContactPhone, ContactURL, IsActive, IsApproved,
									 IsBillboard, SubmittedByName, SubmittedByEmail, SubmittedAt, SeriesID,
									 AllowRegister, SpacesAvailable, Message, LocID, Cost, LocCountry)
						VALUES(	'" . cIn($eventTitle) . "', '" . cIn($locName) . "', '" . cIn($locAddress) . "', '" . cIn($locAddress2) . "',
								'" . cIn($locCity) . "', '" . cIn($locState) . "', '" . cIn($locZip) . "', '" . cIn($eventDesc) . "',
								'" . cIn($eventDate) . "', " . $startTime . ", " . cIn($tbd) . ", " . $endTime . ",
								'" . cIn($contactName) . "', '" . cIn($contactEmail) . "', '" . cIn($contactPhone) . "', '" . cIn($contactURL) . "',
								'1', '" . $appStatus . "', '0', '" . cIn($subName) . "', '" . cIn($subEmail) . "', NOW(), '" . cIn($eventID) . "',
								'" . cIn($allowRegistration) . "', '" . cIn($maxRegistration) . "', " . $adminMessage . ",
								'" . cIn($locID) . "', '" . cIn($cost) . "', '" . cIn($locCountry) . "');";
			if($eventDate != '' && $eventDate >= date("Y-m-d")){
				doQuery($query);
				$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "events");
				$newPkID = mysql_result($result,0,0);
			
				if(isset($_POST['catID'])){
					$catID = $_POST['catID'];
					foreach ($catID as $val){
						doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($newPkID) . "', '" . cIn($val) . "')");
					}//end foreach
				}//end if
			}//end if
		}//end foreach
		
	} else {
		$_SESSION[$hc_cfg00 . 'hc_curSubmit'] = (isset($_SESSION[$hc_cfg00 . 'hc_curSubmit'])) ? $_SESSION[$hc_cfg00 . 'hc_curSubmit'] + 1 : 1;
		
		if($_SESSION[$hc_cfg00 . 'hc_curSubmit'] >= $hc_cfg40){
			exit($hc_lang_event['NoSubmit']);
		}//end if
		
		$query = "INSERT INTO " . HC_TblPrefix . "events(Title, LocationName, LocationAddress, LocationAddress2, 
									 LocationCity, LocationState, LocationZip, Description,
									 StartDate, StartTime, TBD, EndTime, ContactName,
									 ContactEmail, ContactPhone, ContactURL, IsActive, IsApproved,
									 IsBillboard, SubmittedByName, SubmittedByEmail, SubmittedAt,
									 AllowRegister, SpacesAvailable, Message, LocID, Cost, LocCountry)
				VALUES(	'" . cIn($eventTitle) . "', '" . cIn($locName) . "', '" . cIn($locAddress) . "', '" . cIn($locAddress2) . "',
						'" . cIn($locCity) . "', '" . cIn($locState) . "', '" . cIn($locZip) . "', '" . cIn($eventDesc) . "',
						'" . cIn($eventDate) . "', " . $startTime . ", " . cIn($tbd) . ", " . $endTime . ",
						'" . cIn($contactName) . "', '" . cIn($contactEmail) . "', '" . cIn($contactPhone) . "', '" . cIn($contactURL) . "',
						'1', '" . $appStatus . "', '0', '" . cIn($subName) . "', '" . cIn($subEmail) . "', NOW(),
						'" . cIn($allowRegistration) . "', '" . cIn($maxRegistration) . "', " . $adminMessage . ",
						'" . cIn($locID) . "', '" . cIn($cost) . "', '" . cIn($locCountry) . "');";
		if($eventDate != '' && $eventDate >= date("Y-m-d")){
			doQuery($query);
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "events");
			$newPkID = mysql_result($result,0,0);
			
			if(isset($_POST['catID'])){
				$catID = $_POST['catID'];
				foreach ($catID as $val){
					doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($newPkID) . "', '" . cIn($val) . "')");
				}//end foreach
			}//end if
		}//end if
	}//end if
	
	if($newPkID > 0 && $hc_cfg25 == 1){
		$headers = "From: " . CalAdminEmail . "\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Reply-To: " . CalAdminEmail . "\n";
		$headers .= "Content-Type: text/html; charset=UTF-8;\n";
		
		$subject = CalName . " -- " . $hc_lang_event['NoticeSubject'];
		
		$message = $hc_lang_event['NoticeEmail1'] . "<br><br>";
		$message .= $hc_lang_event['NoticeEmail2'] . " " . $subName . " - " . $subEmail . "<br>" . $hc_lang_event['NoticeEmail3'] . " " . $_SERVER['REMOTE_ADDR'] . "<br>";
		$message .= $hc_lang_event['EventTitle'] . " " . $eventTitle . "<br>" . $eventDesc;
		$message .= "<br><br><a href=\"" . CalAdminRoot . "\">" . CalAdminRoot . "</a>";
		
		mail(CalAdminEmail,$subject,$message,$headers);
	}//end if
	
	header("Location: " . CalRoot . "/components/EventSubmitRepeatStop.php");	?>