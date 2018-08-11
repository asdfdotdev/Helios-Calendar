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
	$isAction = 1;
	include('../includes/include.php');
	checkIt();
	
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/public/event.php');
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(1,40)");
	if(mysql_result($result,0,1) == 0){
		exit();
	}//end if
	$maxSubmit = mysql_result($result,1,1);
	
	$proof = "";
	if(isset($_POST['proof'])){$proof = $_POST['proof'];}
	spamIt($proof, 1);
	
	$appStatus = 2;	//1 = Auto Approve, 2 = Submit to Pending Queue (Default)
	
	$subName = htmlspecialchars($_POST['submitName']);
	$subEmail = htmlspecialchars($_POST['submitEmail']);
	$eventTitle = htmlspecialchars($_POST['eventTitle']);
	$eventDesc = $_POST['eventDescription'];
	$eventDate = dateToMySQL(htmlspecialchars($_POST['eventDate']), "/", $_POST['dateFormat']);
	$locID = $_POST['locPreset'];
	$contactName = htmlspecialchars($_POST['contactName']);
	$contactEmail = htmlspecialchars($_POST['contactEmail']);
	$contactPhone = htmlspecialchars($_POST['contactPhone']);
	$contactURL = htmlspecialchars($_POST['contactURL']);
	$cost = htmlspecialchars($_POST['cost']);
	
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
	
	if( !ereg("^http://*", $contactURL, $regs) ){
	   $contactURL = "http://" . $contactURL;
	}//end if
	
	$allowRegistration = htmlspecialchars($_POST['eventRegistration']);
	
	if(isset($_POST['adminmessage'])){
		$adminMessage = "'" . cIn(htmlspecialchars($_POST['adminmessage'])) . "'";
	} else {
		$adminMessage = "NULL";
	}//end if
	
	if($allowRegistration == 1){
		$maxRegistration = $_POST['eventRegAvailable'];
	} else {
		$maxRegistration = 0;
	}//end if
	
	if($contactURL == 'http://'){
		$contactURL = "";
	}//end if
	
	if(!isset($_POST['overridetime'])){
		$startTimeHour = $_POST['startTimeHour'];
		$startTimeMins = $_POST['startTimeMins'];
		
		if($_POST['timeFormat'] == 12){
			if($_POST['startTimeAMPM'] == 'PM' AND $startTimeHour != 12){
				$startTimeHour = $startTimeHour + 12;
			}//end if
			
			if($_POST['startTimeAMPM'] == 'AM' AND $startTimeHour == 12){
				$startTimeHour = 0;
			}//end if 
		}//end if
		
		if(!isset($_POST['ignoreendtime'])){
			$endTimeHour = $_POST['endTimeHour'];
			$endTimeMins = $_POST['endTimeMins'];
			
			if($_POST['timeFormat'] == 12){
				if($_POST['endTimeAMPM'] == 'PM' AND $endTimeHour != 12){
					$endTimeHour = $endTimeHour + 12;
				}//end if
				
				if($_POST['endTimeAMPM'] == 'AM' AND $endTimeHour == 12){
					$endTimeHour = 0;
				}//end if
			}//end if
			
			$endTime = "'" . cIn($endTimeHour) . ":" . cIn($endTimeMins) . ":00'";
		} else {
			$endTime = "NULL";
		}//end if
		
		$tbd = 0;
		$startTime = "'" . cIn($startTimeHour) . ":" . cIn($startTimeMins) . ":00'";
		
	} else {
		$startTime = "NULL";
		$endTime = "NULL";
		$tbd = 2;
		if($_POST['specialtime'] == "allday"){
			$tbd = 1;
		}//end if
		
	}//end if
	
	if(isset($_POST['recurCheck'])){
		$dates = array();
		
		switch(htmlspecialchars($_POST['recurType'])){
			case 'daily':
				$days = htmlspecialchars($_POST['dailyDays']);
				$curDate = $eventDate;
				$stopDate = dateToMySQL(htmlspecialchars($_POST['recurEndDate']), "/", $_POST['dateFormat']);
				
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
				$stopDate = dateToMySQL($_POST['recurEndDate'], "/", $_POST['dateFormat']);
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
				$stopDate = dateToMySQL(htmlspecialchars($_POST['recurEndDate']), "/", $_POST['dateFormat']);
				
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
		
		$curSubmit = 0;
		if(isset($_SESSION['hc_curSubmit'])){
			$curSubmit = $_SESSION['hc_curSubmit'];
		}//end if
		if((count($dates) + $curSubmit) >= $maxSubmit){
			exit($hc_lang_event['NoSubmit']);
		} else {
			$_SESSION['hc_curSubmit'] = $curSubmit + count($dates);
		}//end if
		
		//loop array and insert dates
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
		if(isset($_SESSION['hc_curSubmit'])){
			$_SESSION['hc_curSubmit']++;
		} else {
			$_SESSION['hc_curSubmit'] = 1;
		}//end if
		if($_SESSION['hc_curSubmit'] >= $maxSubmit){
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
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 25");
	if($newPkID > 0 && mysql_result($result,0,0) == 1){
		$headers = "From: " . CalAdminEmail . "\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Reply-To: " . CalAdminEmail . "\n";
		$headers .= "Content-Type: text/html; charset=UTF-8;\n";
		
		$subject = CalName . " -- " . $hc_lang_event['NoticeSubject'];
		
		$message = $hc_lang_event['NoticeEmail1'] . "<br><br>";
		$message .= $hc_lang_event['NoticeEmail2'] . " " . $subName . " - " . $subEmail . "<br>" . $hc_lang_event['NoticeEmail3'] . " " . $_SERVER['REMOTE_ADDR'] . "<br>";
		$message .= "Event Title: " . $eventTitle . "<br>" . $eventDesc;
		$message .= "<br><br><a href=\"" . CalAdminRoot . "\">" . CalAdminRoot . "</a>";
		
		mail(CalAdminEmail, "$subject", "$message", "$headers");
	}//end if
	
	header("Location: " . CalRoot . "/components/EventSubmitRepeatStop.php");	?>