<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	
	$eID = $_POST['eID'];
	$eventStatus = $_POST['eventStatus'];
	$eventBillboard = $_POST['eventBillboard'];
	$eventTitle = $_POST['eventTitle'];
	$eventDesc = $_POST['eventDescription'];
	if(isset($_POST['eventDate'])){
		$eventDate = $_POST['eventDate'];
	}//end if
	$contactName = $_POST['contactName'];
	$contactEmail = $_POST['contactEmail'];
	$contactPhone = $_POST['contactPhone'];
	$contactURL = $_POST['contactURL'];
	$locID = $_POST['locPreset'];
	$cost = $_POST['cost'];
	
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
	
	$allowRegistration = $_POST['eventRegistration'];
	
	if($allowRegistration == 1){
		$maxRegistration = $_POST['eventRegAvailable'];
	} else {
		$maxRegistration = "0";
	}//end if
	
	if(!isset($_POST['overridetime'])){
		$startTimeHour = $_POST['startTimeHour'];
		$startTimeMins = $_POST['startTimeMins'];
		
		if($_POST['timeFormat'] == 12){
			if($_POST['startTimeAMPM'] == 'PM' AND $startTimeHour != 12){
				$startTimeHour = $startTimeHour + 12;
			}//end if
			
			if($_POST['startTimeAMPM'] == 'AM' AND $startTimeHour == 12){
				$startTimeHour = $startTimeHour + 12;
			}//end if
		}//end if
		
		$endTime = "NULL";
		if(!isset($_POST['ignoreendtime'])){
			$endTimeHour = $_POST['endTimeHour'];
			$endTimeMins = $_POST['endTimeMins'];
			
			if($_POST['timeFormat'] == 12){
				if($_POST['endTimeAMPM'] == 'PM' AND $endTimeHour != 12){
					$endTimeHour = $endTimeHour + 12;
				}//end if
				
				if($_POST['endTimeAMPM'] == 'AM' AND $endTimeHour == 12){
					$endTimeHour = $endTimeHour + 12;
				}//end if
			}//end if
				
			$endTime = "'" . cIn($endTimeHour) . ":" . cIn($endTimeMins) . ":00'";
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
		
	$query = "UPDATE " . HC_TblPrefix . "events
				SET Title = '" . cIn($eventTitle) . "',
					LocationName = '" . cIn($locName) . "',
					LocationAddress = '" . cIn($locAddress) . "',
					LocationAddress2 = '" . cIn($locAddress2) . "',
					LocationCity = '" . cIn($locCity) . "',
					LocationState = '" . cIn($locState) . "',
					LocationZip = '" . cIn($locZip) . "',
					Description = '" . cIn($eventDesc) . "',
					StartTime = " . $startTime . ",
					TBD = " . cIn($tbd) . ",
					EndTime = " . $endTime . ",
					ContactName = '" . cIn($contactName) . "',
					ContactEmail = '" . cIn($contactEmail) . "',
					ContactPhone = '" . cIn($contactPhone) . "',
					IsApproved = '" . cIn($eventStatus) . "',
					IsBillboard = '" . cIn($eventBillboard) . "',
					ContactURL = '" . cIn($contactURL) . "',
					AllowRegister = " . cIn($allowRegistration) . ",
					SpacesAvailable = " . cIn($maxRegistration) . ",
					LocID = " . cIn($locID) . ",
					Cost = '" . cIn($cost) . "',
					LocCountry = '" . cIn($locCountry) . "'";
					
	if($_POST['prevStatus'] == 2 && $eventStatus == 1){
		$query .= ", PublishDate = NOW()";
	}//end if
	
	if(!isset($_POST['editString'])){
		$query .= ", StartDate = '" . cIn(dateToMySQL($eventDate, "/", $_POST['dateFormat'])) . "'
							 WHERE PkID = '" . $eID . "' ";
		
		doQuery($query);
		
		$result = doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($eID));
		
		if(isset($_POST['catID'])){
			$catID = $_POST['catID'];
			$x = 0;
			while($x < count($catID)){
				doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($eID) . "', '" . cIn($catID[$x]) . "')");
				$x++;
			}//end while
		}//end if
		
		header("Location: " . CalAdminRoot . "/index.php?com=eventedit&eID=" . $eID  . "&msg=1");
	} else {
		$query = $query . " WHERE PkID IN (" . cIn($_POST['editString']) . ")";
		
		doQuery($query);
		
		$eventIDs = explode(",", $_POST['editString']);
		$catIDs = $_POST['catID'];
		$i = 1;
		while($i < count($eventIDs)){
			$result = doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($eventIDs[$i]));
			$x = 0;
			while($x < count($catIDs)){
				doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($eventIDs[$i]) . "', '" . cIn($catIDs[$x]) . "')");
				$x++;
			}//end while
			$i++;
		}//end while
		
		if(isset($_POST['makeseries'])){
			$seriesID = DecHex(microtime() * 1000000) . DecHex(microtime() * 9999999) . DecHex(microtime() * 8888888);
			doQuery("UPDATE " . HC_TblPrefix . "events SET SeriesID = '" . $seriesID . "' WHERE PkID IN (" . cIn($_POST['editString']) . ")");
		}//end if
		
		header("Location: " . CalAdminRoot . "/index.php?com=eventsearch&sID=1&msg=2");
	}//end if
?>

