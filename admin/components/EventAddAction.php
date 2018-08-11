<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	checkIt(1);
	
	$eventStatus = $_POST['eventStatus'];
	$eventBillboard = $_POST['eventBillboard'];
	$eventTitle = $_POST['eventTitle'];
	$eventDesc = $_POST['eventDescription'];
	$eventDate = dateToMySQL($_POST['eventDate'], "/", $_POST['dateFormat']);
	$locName = $_POST['locName'];
	$locAddress = $_POST['locAddress'];
	$locAddress2 = $_POST['locAddress2'];
	$locCity = $_POST['locCity'];
	$locState = $_POST['locState'];
	$locZip = $_POST['locZip'];
	$contactName = $_POST['contactName'];
	$contactEmail = $_POST['contactEmail'];
	$contactPhone = $_POST['contactPhone'];
	$contactURL = $_POST['contactURL'];
	$allowRegistration = $_POST['eventRegistration'];
	$startTime = "NULL";
	$endTime = "NULL";
	$maxRegistration = 0;
	$publishDate = "'NULL'";
	$longitude = "NULL";
	$latitude = "NULL";
	
	if($eventStatus == 1){
		$publishDate = "NOW()";
	}//end if
	
	if($allowRegistration == 1){
		$maxRegistration = $_POST['eventRegAvailable'];
	}//end if
	
	if($contactURL == "http://"){
		$contactURL = NULL;
	}//end if
	
	if(!isset($_POST['overridetime'])){
		$startTimeHour = $_POST['startTimeHour'];
		$startTimeMins = $_POST['startTimeMins'];
		
		if($_POST['startTimeAMPM'] == 'PM' AND $startTimeHour != 12){
			$startTimeHour = $startTimeHour + 12;
		}//end if
		
		if($_POST['startTimeAMPM'] == 'AM' AND $startTimeHour == 12){
			$startTimeHour = $startTimeHour + 12;
		}//end if
		
		$endTime = "NULL";
		if(!isset($_POST['ignoreendtime'])){
			$endTimeHour = $_POST['endTimeHour'];
			$endTimeMins = $_POST['endTimeMins'];
			
			if($_POST['endTimeAMPM'] == 'PM' AND $endTimeHour != 12){
				$endTimeHour = $endTimeHour + 12;
			}//end if
			
			if($_POST['endTimeAMPM'] == 'AM' AND $endTimeHour == 12){
				$endTimeHour = $endTimeHour + 12;
			}//end if
			
			$endTime = "'" . cIn($endTimeHour) . ":" . cIn($endTimeMins) . ":00'";
		}//end if
		
		$tbd = 0;
		$startTime = "'" . cIn($startTimeHour) . ":" . cIn($startTimeMins) . ":00'";
		
	} else {
		$tbd = 2;
		if($_POST['specialtime'] == "allday"){
			$tbd = 1;
		}//end if
	}//end if
	
	if(isset($_POST['recurCheck'])){
		$dates = array();
		
		switch($_POST['recurType']){
			case 'daily':
				$days = $_POST['dailyDays'];
				$curDate = $eventDate;
				$stopDate = dateToMySQL($_POST['recurEndDate'], "/", $_POST['dateFormat']);
				
				$stopParts = split("-", $stopDate);
				$stopDate = date("Y-m-d", mktime(0, 0, 0, $stopParts[0], $stopParts[1] + $days, $stopParts[2]));
				
				if($_POST['dailyOptions'] == 'EveryXDays'){
					while(strtotime($curDate) < strtotime($stopDate)){
						$dates[] = $curDate;
						
						$dateParts = split("-", $curDate);
						$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + $days, $dateParts[0]));
					}//end while
					
				} else {
					while(strtotime($curDate) < strtotime($stopDate)){
						$dateParts = split("-", $curDate);
						$curDayOfWeek = date("w", mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
						
						if(($curDayOfWeek != 0) AND ($curDayOfWeek != 6)){
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
				
				while(strtotime($curDate) <= strtotime($stopDate)){
					$dateParts = split("-", $curDate);
					$curDateDayOfWeek = date("w", mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
					
					switch($curDateDayOfWeek){
						case 0:
							if(isset($_POST['recWeeklyDay1'])){
								$dates[] = $curDate;
							}//end if
							break;
						
						case 1:
							if(isset($_POST['recWeeklyDay2'])){
								$dates[] = $curDate;
							}//end if
							break;
							
						case 2:
							if(isset($_POST['recWeeklyDay3'])){
								$dates[] = $curDate;
							}//end if
							break;
							
						case 3:
							if(isset($_POST['recWeeklyDay4'])){
								$dates[] = $curDate;
							}//end if
							break;
							
						case 4:
							if(isset($_POST['recWeeklyDay5'])){
								$dates[] = $curDate;
							}//end if
							break;
							
						case 5:
							if(isset($_POST['recWeeklyDay6'])){
								$dates[] = $curDate;
							}//end if
							break;
							
						case 6:
							if(isset($_POST['recWeeklyDay7'])){
								$dates[] = $curDate;
							}//end if
							break;
							
					}//end switch
				
					if(($curDateDayOfWeek == 6) AND ($weeks > 1)){
						$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + ((($weeks - 1) * 7) + 1), $dateParts[0]));
						
					} else {
						$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + 1, $dateParts[0]));
						
					}//end if
				
				}//end while
				break;
				
			case 'monthly':
				$curDate = $eventDate;
				$stopDate = dateToMySQL($_POST['recurEndDate'], "/", $_POST['dateFormat']);
				
				$day = $_POST['monthlyDays'];
				$months = $_POST['monthlyMonths'];
				
				$dateParts = split("-", $curDate);
				$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[0], $day, $dateParts[2]));
				
				while(strtotime($curDate) <= strtotime($stopDate)){
					$dates[] = $curDate;
					$dateParts = split("-", $curDate);
					$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1] + $months, $day, $dateParts[0]));
					
				}//end while
				
				break;
			
		}//end switch
		
		//loop array and insert dates
		$eventID = DecHex(microtime() * 1000000) . DecHex(microtime() * 9999999) . DecHex(microtime() * 8888888);
		foreach ($dates as $val){
			//echo $val . "  <-- <br />";
			$eventDate = $val;
			$query = "	INSERT INTO " . HC_TblPrefix . "events(Title, LocationName, LocationAddress, LocationAddress2, 
									LocationCity, LocationState, LocationZip, Description,
									StartDate, StartTime, TBD, EndTime, ContactName,
									ContactEmail, ContactPhone, ContactURL, IsActive, IsApproved,
									IsBillboard, SeriesID, AllowRegister, SpacesAvailable, PublishDate)
						VALUES('" . cIn($eventTitle) . "', '" . cIn($locName) . "', '" . cIn($locAddress) . "', '" . cIn($locAddress2) . "',
								'" . cIn($locCity) . "', '" . cIn($locState) . "', '" . cIn($locZip) . "', '" . cIn($eventDesc) . "',
								'" . cIn($eventDate) . "', " . $startTime . ", " . cIn($tbd) . ", " . $endTime . ",
								'" . cIn($contactName) . "', '" . cIn($contactEmail) . "', '" . cIn($contactPhone) . "', '" . cIn($contactURL) . "',
								'1', '" . cIn($eventStatus) . "', '" . cIn($eventBillboard) . "', '" . cIn($eventID) . "',
								'" . cIn($allowRegistration) . "', '" . cIn($maxRegistration) . "', " . cIn($publishDate) . ");";
			doQuery($query);
			
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "events");
			$newPkID = mysql_result($result,0,0);
			
			if(isset($_POST['catID'])){
				$catID = $_POST['catID'];
					foreach ($catID as $val){
						doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($newPkID) . "', '" . cIn($val) . "')");
					}//end foreach
				
			}//end if
		}//end for
		
		$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "events WHERE SeriesID = '" . $eventID . "' ORDER BY StartDate LIMIT 1");
		$newPkID = mysql_result($result,0,0);
	} else {
		$query = "INSERT INTO " . HC_TblPrefix . "events(Title, LocationName, LocationAddress, LocationAddress2, 
									 LocationCity, LocationState, LocationZip, Description,
									 StartDate, StartTime, TBD, EndTime, ContactName,
									 ContactEmail, ContactPhone, ContactURL, IsActive, IsApproved,
									 IsBillboard, AllowRegister, SpacesAvailable, PublishDate)
				VALUES('" . cIn($eventTitle) . "', '" . cIn($locName) . "', '" . cIn($locAddress) . "', '" . cIn($locAddress2) . "',
						'" . cIn($locCity) . "', '" . cIn($locState) . "', '" . cIn($locZip) . "', '" . cIn($eventDesc) . "',
						'" . cIn($eventDate) . "', " . $startTime . ", '" . cIn($tbd) . "', " . $endTime . ",
						'" . cIn($contactName) . "', '" . cIn($contactEmail) . "', '" . cIn($contactPhone) . "', '" . cIn($contactURL) . "',
						'1', '" . cIn($eventStatus) . "', '" . cIn($eventBillboard) . "',
						'" . cIn($allowRegistration) . "', '" . cIn($maxRegistration) . "', " . cIn($publishDate) . ");";
		doQuery($query);
		
		$result = doQuery("SELECT LAST_INSERT_ID()");
		$newPkID = mysql_result($result,0,0);
		
		if(isset($_POST['catID'])){
			$catID = $_POST['catID'];
				foreach ($catID as $val){
					doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($newPkID) . "', '" . cIn($val) . "')");
				}//end foreach
			
		}//end if
		
	}//end if
	
	header("Location: " . CalAdminRoot . "/index.php?com=eventedit&msg=2&eID=" . $newPkID);
?>
