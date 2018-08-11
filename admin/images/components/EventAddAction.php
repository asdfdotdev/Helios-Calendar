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
	checkIt(1);
	
	$eventStatus = $_POST['eventStatus'];
	$eventBillboard = $_POST['eventBillboard'];
	$eventTitle = $_POST['eventTitle'];
	$eventDesc = $_POST['eventDescription'];
	$eventDate = dateToMySQL($_POST['eventDate'], "/", $hc_cfg24);
	$contactName = $_POST['contactName'];
	$contactEmail = $_POST['contactEmail'];
	$contactPhone = $_POST['contactPhone'];
	$contactURL = $_POST['contactURL'];
	$allowRegistration = $_POST['eventRegistration'];
	$locID = $_POST['locPreset'];
	$startTime = "NULL";
	$endTime = "NULL";
	$maxRegistration = 0;
	$cost = $_POST['cost'];
	$msgID = 2;
	
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
		$dates = array();
		
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
		
		
		//loop array and insert dates
		$seriesID = DecHex(microtime() * 9999999) . DecHex(microtime() * 5555555) . DecHex(microtime() * 1111111);
		foreach ($dates as $val){
			$eventDate = $val;
			$query = "	INSERT INTO " . HC_TblPrefix . "events(Title, LocationName, LocationAddress, LocationAddress2, 
									LocationCity, LocationState, LocationZip, Description,
									StartDate, StartTime, TBD, EndTime, ContactName,
									ContactEmail, ContactPhone, ContactURL, IsActive, IsApproved,
									IsBillboard, SeriesID, AllowRegister, SpacesAvailable, PublishDate, LocID, Cost, LocCountry)
						VALUES('" . cIn($eventTitle) . "', '" . cIn($locName) . "', '" . cIn($locAddress) . "', '" . cIn($locAddress2) . "',
								'" . cIn($locCity) . "', '" . cIn($locState) . "', '" . cIn($locZip) . "', '" . cIn($eventDesc) . "',
								'" . cIn($eventDate) . "', " . $startTime . ", " . cIn($tbd) . ", " . $endTime . ",
								'" . cIn($contactName) . "', '" . cIn($contactEmail) . "', '" . cIn($contactPhone) . "', '" . cIn($contactURL) . "',
								'1', '" . cIn($eventStatus) . "', '" . cIn($eventBillboard) . "', '" . cIn($seriesID) . "',
								'" . cIn($allowRegistration) . "', '" . cIn($maxRegistration) . "', " . $publishDate . ",
								'" . cIn($locID) . "', '" . cIn($cost) . "', '" . cIn($locCountry) ."');";
			doQuery($query);
			
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "events");
			$newPkID = mysql_result($result,0,0);
			
			if(isset($_POST['catID'])){
				$catID = $_POST['catID'];
				foreach ($catID as $val){
					doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($newPkID) . "', '" . cIn($val) . "')");
				}//end foreach
			}//end if
			
			if(isset($_POST['doEventful'])){
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
				if(hasRows($result)){
					if(mysql_result($result,0,0) == '36' && mysql_result($result,0,1) == ''){
						$msgID = 7;
					} else {
						$efKey = mysql_result($result,0,1);
						$efUser = "";
						$efPass = "";
						if(mysql_result($result,1,1) == ''){
							if(isset($_POST['efUser'])){
								$efUser = $_POST['efUser'];
							}//end if
						} else {
							$efUser = mysql_result($result,1,1);
						}//end if
						if(mysql_result($result,2,1) == ''){
							if(isset($_POST['efPass'])){
								$efPass = $_POST['efPass'];
							}//end if
						} else {
							$efPass = mysql_result($result,2,1);
						}//end if
						
						$efRest = "/rest/events/new";
						include('EventfulAddEvent.php');
					}//end if
				}//end if
			}//end if
		}//end for
	} else {
		$query = "INSERT INTO " . HC_TblPrefix . "events(Title, LocationName, LocationAddress, LocationAddress2, 
									 LocationCity, LocationState, LocationZip, Description,
									 StartDate, StartTime, TBD, EndTime, ContactName,
									 ContactEmail, ContactPhone, ContactURL, IsActive, IsApproved,
									 IsBillboard, AllowRegister, SpacesAvailable, PublishDate, LocID, Cost, LocCountry)
				VALUES('" . cIn($eventTitle) . "', '" . cIn($locName) . "', '" . cIn($locAddress) . "', '" . cIn($locAddress2) . "',
						'" . cIn($locCity) . "', '" . cIn($locState) . "', '" . cIn($locZip) . "', '" . cIn($eventDesc) . "',
						'" . cIn($eventDate) . "', " . $startTime . ", '" . cIn($tbd) . "', " . $endTime . ",
						'" . cIn($contactName) . "', '" . cIn($contactEmail) . "', '" . cIn($contactPhone) . "', '" . cIn($contactURL) . "',
						'1', '" . cIn($eventStatus) . "', '" . cIn($eventBillboard) . "',
						'" . cIn($allowRegistration) . "', '" . cIn($maxRegistration) . "', " . $publishDate . ",
						'" . cIn($locID) . "', '" . cIn($cost) . "', '" . cIn($locCountry) . "');";
		doQuery($query);
		
		$result = doQuery("SELECT LAST_INSERT_ID()");
		$newPkID = mysql_result($result,0,0);
		
		if(isset($_POST['catID'])){
			$catID = $_POST['catID'];
			foreach ($catID as $val){
				doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($newPkID) . "', '" . cIn($val) . "')");
			}//end foreach
		}//end if
		
		if(isset($_POST['doEventful'])){
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
			if(hasRows($result)){
				if(mysql_result($result,0,0) == '36' && mysql_result($result,0,1) == ''){
					$msgID = 7;
				} else {
					$efKey = mysql_result($result,0,1);
					$efUser = "";
					$efPass = "";
					if(mysql_result($result,1,1) == ''){
						if(isset($_POST['efUser'])){
							$efUser = $_POST['efUser'];
						}//end if
					} else {
						$efUser = mysql_result($result,1,1);
					}//end if
					if(mysql_result($result,2,1) == ''){
						if(isset($_POST['efPass'])){
							$efPass = $_POST['efPass'];
						}//end if
					} else {
						$efPass = mysql_result($result,2,1);
					}//end if
					$msgID = 9;
					$efRest = "/rest/events/new";
					include('EventfulAddEvent.php');
				}//end if
			}//end if
		}//end if
	}//end if
	
	$hourOffset = date("G") + ($hc_cfg35);
	$curCache = date("Ymd", mktime($hourOffset,0,0,date("m"),date("d"),date("Y")));
	if(file_exists(realpath('../../events/cache/lmap' . $curCache . '.php'))){
		unlink('../../events/cache/lmap' . $curCache . '.php');
	}//end if
	
	header("Location: " . CalAdminRoot . "/index.php?com=eventedit&msg=" . $msgID . "&eID=" . $newPkID);	?>