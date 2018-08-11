<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
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

	$eID = 0;
	$subName = htmlspecialchars($_POST['submitName']);
	$subEmail = htmlspecialchars($_POST['submitEmail']);
	$eventTitle = htmlspecialchars(cleanQuotes($_POST['eventTitle']));
	$eventDesc = cleanQuotes($_POST['eventDescription']);
	$eventDate = dateToMySQL(htmlspecialchars($_POST['eventDate']), "/", $hc_cfg24);
	$locID = $_POST['locPreset'];
	$contactName = htmlspecialchars($_POST['contactName']);
	$contactEmail = htmlspecialchars($_POST['contactEmail']);
	$contactPhone = htmlspecialchars($_POST['contactPhone']);
	$contactURL = htmlspecialchars($_POST['contactURL']);
	$cost = htmlspecialchars($_POST['cost']);
	$startTimeHour = isset($_POST['startTimeHour']) ? $_POST['startTimeHour'] : NULL;
	$endTimeHour = isset($_POST['endTimeHour']) ? $_POST['endTimeHour'] : NULL;
	$dates = array();
	$catID = (isset($_POST['catID'])) ? $_POST['catID'] : '';
	$newPkID = 0;
	
	if($locID > 0){
		$locName = '';
		$locAddress = '';
		$locAddress2 = '';
		$locCity = '';
		$locState = '';
		$locZip = '';
		$locCountry = '';
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
	$adminMessage = (isset($_POST['adminmessage'])) ? cIn(htmlspecialchars($_POST['adminmessage'])) : '';
	$maxRegistration = ($allowRegistration == 1) ? $_POST['eventRegAvailable'] : 0;
	
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
	} else {
		$seriesID = 'NULL';
		$dates[] = $eventDate;
	}//end if

	$curSubmit = (isset($_SESSION[$hc_cfg00 . 'hc_curSubmit'])) ? $_SESSION[$hc_cfg00 . 'hc_curSubmit'] : 0;
	if((count($dates) + $curSubmit) >= $hc_cfg40){
		exit($hc_lang_event['NoSubmit']);
	} else {
		$_SESSION[$hc_cfg00 . 'hc_curSubmit'] = $curSubmit + count($dates);
	}//end if

	foreach ($dates as $val){
		$eventDate = $val;
		$query = "INSERT INTO " . HC_TblPrefix . "events(Title, LocationName, LocationAddress, LocationAddress2,
							 LocationCity, LocationState, LocationZip, Description,
							 StartDate, StartTime, TBD, EndTime, ContactName,
							 ContactEmail, ContactPhone, ContactURL, IsActive, IsApproved,
							 IsBillboard, SubmittedByName, SubmittedByEmail, SubmittedAt, SeriesID,
							 AllowRegister, SpacesAvailable, Message, LocID, Cost, LocCountry)
				VALUES(	'" . cIn($eventTitle) . "', '" . cIn($locName) . "', '" . cIn($locAddress) . "', '" . cIn($locAddress2) . "',
						'" . cIn($locCity) . "', '" . cIn($locState) . "', '" . cIn($locZip) . "', '" . cIn($eventDesc) . "',
						'" . cIn($eventDate) . "', " . $startTime . ", " . cIn($tbd) . ", " . $endTime . ",
						'" . cIn($contactName) . "', '" . cIn($contactEmail) . "', '" . cIn($contactPhone) . "', '" . cIn($contactURL) . "',
						'1', '" . $appStatus . "', '0', '" . cIn($subName) . "', '" . cIn($subEmail) . "', NOW(), " . $seriesID . ",
						'" . cIn($allowRegistration) . "', '" . cIn($maxRegistration) . "', '" . $adminMessage . "',
						'" . cIn($locID) . "', '" . cIn($cost) . "', '" . cIn($locCountry) . "');";

		if($eventDate != '' && $eventDate >= date("Y-m-d")){
			doQuery($query);
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "events");
			$newPkID = mysql_result($result,0,0);

			if(isset($_POST['catID'])){
				foreach ($catID as $val){
					doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($newPkID) . "', '" . cIn($val) . "')");
				}//end foreach
			}//end if
		}//end if
	}//end foreach

     if($newPkID > 0){
		$resultE = doQuery("SELECT a.Email
						FROM " . HC_TblPrefix . "adminnotices n
							LEFT JOIN " . HC_TblPrefix . "admin a ON (n.AdminID = a.PkID)
						WHERE a.IsActive = 1 AND
							n.IsActive = 1 AND
							n.TypeID = 0");
		if(hasRows($resultE)){
			$headers = "From: " . CalAdminEmail . "\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Reply-To: " . CalAdminEmail . "\n";
			$headers .= "Content-Type: text/html; charset=UTF-8;\n";
			$subject = CalName . ' -- ' . $hc_lang_event['NoticeSubject'];
			$message = $hc_lang_event['NoticeEmail1'];
			$message .= '<br><br><b>' . $hc_lang_event['NoticeEmail2'] . '</b> ' . $subName . ' - ' . $subEmail;
			$message .= '<br><b>' . $hc_lang_event['NoticeEmail3'] . '</b> ' . $_SERVER['REMOTE_ADDR'];

			if($adminMessage != ''){
				$message .= '<br><br><b>' . $hc_lang_event['NoticeEmail4'] . '</b> ' . cIn(strip_tags(cleanBreaks($_POST['adminmessage'],1)));
			}//end if

			$message .= '<br><br><b>' . $hc_lang_event['Location'] . '</b> ';
			if($locID == 0){
				$message .= $locName . ', ' . $locAddress . ', ' . $locCity . ', ' . $locState . ', ' . $locZip . ' ' . $locCountry;
			} else {
				$result = doQuery("SELECT Name, Address, City, State, Country, Zip FROM " . HC_TblPrefix . "locations WHERE PkID = '" . cIn($locID) . "'");
				$message .= mysql_result($result,0,0) . ', ' . mysql_result($result,0,1) . ', ' . mysql_result($result,0,2) . ', ' . mysql_result($result,0,3) . ', ' . mysql_result($result,0,4) . ' ' . mysql_result($result,0,5);
			}//end if
			$message .= '<br><b>' . $hc_lang_event['EventTitle'] . '</b> ' . $eventTitle . '<br><br>' . strip_tags($eventDesc);
			$message .= '<br><br><a href="' . CalAdminRoot . '">' . CalAdminRoot . '</a>';

			while($row = mysql_fetch_row($resultE)){
				mail($row[0],$subject,$message,$headers);
			}//end while
		}//end if
	}//end if
	
	header("Location: " . CalRoot . "/components/EventSubmitRepeatStop.php");	?>