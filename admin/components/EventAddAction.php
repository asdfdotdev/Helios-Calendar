<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	hookDB();
	
	$eventStatus = $_POST['eventStatus'];
	$eventBillboard = $_POST['eventBillboard'];
	$eventTitle = $_POST['eventTitle'];
	$eventDesc = $_POST['eventDescription'];
	$eventDate = $_POST['eventDate'];
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
	
	if($eventStatus == 1){
		$publishDate = "NOW()";
	} else {
		$publishDate = "'NULL'";
	}//end if
	
	if($allowRegistration == 1){
		$maxRegistration = $_POST['eventRegAvailable'];
	} else {
		$maxRegistration = 0;
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
		} else {
			$endTime = "NULL";
		}//end if
		
		$tbd = 0;
		$startTime = "'" . cIn($startTimeHour) . ":" . cIn($startTimeMins) . ":00'";
		
	} else {
		$startTime = "NULL";
		$endTime = "NULL";
		if($_POST['specialtime'] == "allday"){
			$tbd = 1;
		} else {
			$tbd = 2;
		}//end if
		
	}//end if
	
	
	if(isset($_POST['recurCheck'])){
	//this is a recurring event
		
		$isRecur = true;
		$recurEndDate = $_POST['recurEndDate'];
		$dates = array();
		
		
		//type of recurrence (daily, weekly, monthly)
		$recurType = $_POST['recurType'];
		
		
		//determine recur type and build array of dates
		switch($recurType){
			case 'daily':
				
				$days = $_POST['dailyDays'];
				$curDate = $eventDate;
				$stopParts = split("/", $_POST['recurEndDate']);
				$stopDate = date("m/d/Y", mktime(0, 0, 0, $stopParts[0], $stopParts[1] + $days, $stopParts[2]));
				
				if($_POST['dailyOptions'] == 'EveryXDays'){
					
					//			Testing			\\
					//echo $curDate . " -- " . $stopDate . "  (" . $days . ")<br>";
					
					while(strtotime($curDate) <= strtotime($stopDate)){
						
						//			Testing			\\
						//echo strtotime($curDate) . "  --  " . strtotime($stopDate) . "  --  " . $curDate . "<br>";
						
						$dateParts = split("/", $curDate);
						$dates[] = $curDate;
						$curDate = date("m/d/Y", mktime(0, 0, 0, $dateParts[0], $dateParts[1] + $days, $dateParts[2]));
					}//end while
					
				} else {
					//			Testing			\\
					//echo $curDate . " -- " . $stopDate . "  (" . $days . ")<br>";
					
					while(strtotime($curDate) <= strtotime($stopDate)){
						
						//			Testing			\\
						//echo strtotime($curDate) . "  --  " . strtotime($stopDate) . "  --  " . $curDate . "<br>";
						
						$dateParts = split("/", $curDate);
						
						$curDayOfWeek = date("w", mktime(0, 0, 0, $dateParts[0], $dateParts[1], $dateParts[2]));
						
						if(($curDayOfWeek != 0) AND ($curDayOfWeek != 6)){
							$dates[] = $curDate;
						}//end if
						$curDate = date("m/d/Y", mktime(0, 0, 0, $dateParts[0], $dateParts[1] + 1, $dateParts[2]));
					}//end while
					
				}//end if
				
				break;
				
			case 'weekly':
				
				$curDate = $eventDate;
				$stopParts = split("/", $_POST['recurEndDate']);
				$stopDate = date("m/d/Y", mktime(0, 0, 0, $stopParts[0], $stopParts[1], $stopParts[2]));
				$weeks = $_POST['recWeekly'];
				
				while(strtotime($curDate) <= strtotime($stopDate)){
				
				$dateParts = split("/", $curDate);
				$curDateDayOfWeek = date("w", mktime(0, 0, 0, $dateParts[0], $dateParts[1], $dateParts[2]));
				
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
						$curDate = date("m/d/Y", mktime(0, 0, 0, $dateParts[0], $dateParts[1] + ((($weeks - 1) * 7) + 1), $dateParts[2]));
						
					} else {
						$curDate = date("m/d/Y", mktime(0, 0, 0, $dateParts[0], $dateParts[1] + 1, $dateParts[2]));
						
					}//end if
				
				}//end while
				
				break;
				
			case 'monthly':
				
				$curDate = $eventDate;
				$dateParts = split("/", $curDate);
				$stopParts = split("/", $_POST['recurEndDate']);
				$stopDate = date("m/d/Y", mktime(0, 0, 0, $stopParts[0], $stopParts[1], $stopParts[2]));
				$day = $_POST['monthlyDays'];
				$months = $_POST['monthlyMonths'];
				
				$curDate = date("m/d/Y", mktime(0, 0, 0, $dateParts[0], $day, $dateParts[2]));
				
				while(strtotime($curDate) <= strtotime($stopDate)){
					$dates[] = $curDate;
					$dateParts = split("/", $curDate);
					$curDate = date("m/d/Y", mktime(0, 0, 0, $dateParts[0] + $months, $day, $dateParts[2]));
					
				}//end while
				
				break;
			
		}//end switch
		
		//loop array and insert dates
		$eventID = DecHex(microtime() * 1000000) . DecHex(microtime() * 9999999) . DecHex(microtime() * 8888888);
		foreach ($dates as $val){
			//echo $val . "  <-- <br>";
			$dateParts = split("/", $val);
			
			$eventDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[0], $dateParts[1], $dateParts[2]));
			
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
	//event doesn't recur
		$dateParts = split("/", $eventDate);
		$eventDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[0], $dateParts[1], $dateParts[2]));
		
		$query = "INSERT INTO " . HC_TblPrefix . "events(Title, LocationName, LocationAddress, LocationAddress2, 
									 LocationCity, LocationState, LocationZip, Description,
									 StartDate, StartTime, TBD, EndTime, ContactName,
									 ContactEmail, ContactPhone, ContactURL, IsActive, IsApproved,
									 IsBillboard, AllowRegister, SpacesAvailable, PublishDate
									)
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
