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
	checkIt(1);
	
	$eID = $_POST['eID'];
	$eventStatus = $_POST['eventStatus'];
	$eventBillboard = $_POST['eventBillboard'];
	$eventTitle = $_POST['eventTitle'];
	$eventDesc = $_POST['eventDescription'];
	if(isset($_POST['eventDate'])){
		$eventDate = dateToMySQL($_POST['eventDate'], "/", $_POST['dateFormat']);
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
				$startTimeHour = 0;
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
					$endTimeHour = 0;
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
		$query .= ", PublishDate = '" . date("Y-m-d H:i:s") . "'";
	}//end if
	
	if(!isset($_POST['editString'])){
		$msgID = 1;
		$query .= ", StartDate = '" . cIn($eventDate) . "'
							 WHERE PkID = '" . $eID . "' ";
		
		doQuery($query);
		$eventIDs = array($eID);
		$result = doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($eID));
		
		if(isset($_POST['catID'])){
			$catID = $_POST['catID'];
			$x = 0;
			while($x < count($catID)){
				doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($eID) . "', '" . cIn($catID[$x]) . "')");
				$x++;
			}//end while
		}//end if
		
		$hdrStr = "Location: " . CalAdminRoot . "/index.php?com=eventedit&eID=" . $eID  . "&msg=" . $msgID;
	} else {
		$msgID = 2;
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
		
		$hdrStr = "Location: " . CalAdminRoot . "/index.php?com=eventsearch&sID=1&msg=" . $msgID;
	}//end if
	
	if(isset($_POST['doEventful'])){
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
		if(hasRows($result)){
			if(mysql_result($result,0,0) == '36' && mysql_result($result,0,1) == ''){
				$msgID = 7;
			} else {
				$msgID = 4;
				$i = 0;
				while($i < count($eventIDs)){
					$newPkID = $eventIDs[$i];
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
					
					$resultEF = doQuery("Select StartDate FROM " . HC_TblPrefix . "events WHERE PkID = " . $newPkID);
					$eventDate = mysql_result($resultEF,0,0);
					$resultEF = doQuery("SELECT * FROM " . HC_TblPrefix . "eventnetwork WHERE EventID = " . $newPkID);
					if(hasRows($resultEF)){
						$efRest = "/rest/events/modify";
					} else {
						$efRest = "/rest/events/new";
					}//end if
					
					include('EventfulAddEvent.php');
					$i++;
				}//end while
			}//end if
		}//end if
	}//end if
	
	header($hdrStr);	?>