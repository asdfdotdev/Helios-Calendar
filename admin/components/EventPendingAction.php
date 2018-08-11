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
	
	$editthis = $_POST['editthis'];
	$edittype = $_POST['edittype'];
	$eventStatus = $_POST['eventStatus'];
	$eventBillboard = $_POST['eventBillboard'];
	$eventTitle = $_POST['eventTitle'];
	$eventDesc = $_POST['eventDescription'];
	$contactName = $_POST['contactName'];
	$contactEmail = $_POST['contactEmail'];
	$contactPhone = $_POST['contactPhone'];
	$contactURL = $_POST['contactURL'];
	$locID = $_POST['locPreset'];
	$cost = $_POST['cost'];
	$subname = $_POST['subname'];
	$subemail = $_POST['subemail'];
	$eventTitle = $_POST['eventTitle'];
	$eventDescription = $_POST['eventDescription'];
	
	if(isset($_POST['eventDate'])){
		$eventDate = dateToMySQL($_POST['eventDate'], "/", $_POST['dateFormat']);
		
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
	}//end if
	
	if($locID > 0){
		$locName = "";
		$locAddress = "";
		$locAddress2 = "";
		$locCity = "";
		$locState = "";
		$locZip = "";
	} else {
		$locName = $_POST['locName'];
		$locAddress = $_POST['locAddress'];
		$locAddress2 = $_POST['locAddress2'];
		$locCity = $_POST['locCity'];
		$locState = $_POST['locState'];
		$locZip = $_POST['locZip'];
	}//end if
	
	if( !ereg("^http://*", $contactURL, $regs) ){
	   $contactURL = "http://" . $contactURL;
	}//end if
	
	$maxRegistration = "0";
	$allowRegistration = $_POST['eventRegistration'];
	if($allowRegistration == 1){
		$maxRegistration = $_POST['eventRegAvailable'];
	}//end if
	
	$sendmsg = 0;
	if(isset($_POST['sendmsg']) && $_POST['sendmsg'] != "no" ){
		$sendmsg = 1;
	}//end if
	if(isset($_POST['message'])){
		$message = $_POST['message'];
	}//end if
	
	$query = "UPDATE " . HC_TblPrefix . "events SET
				Title = '" . cIn($eventTitle) . "',
				Description = '" . cIn($eventDescription) . "',
				LocationName = '" . cIn($locName) . "',
				LocationAddress = '" . cIn($locAddress) . "',
				LocationAddress2 = '" . cIn($locAddress2) . "',
				LocationCity = '" . cIn($locCity) . "',
				LocationState = '" . cIn($locState) . "',
				LocationZip = '" . cIn($locZip) . "',
				ContactName = '" . cIn($contactName) . "',
				ContactEmail = '" . cIn($contactEmail) . "',
				ContactPhone = '" . cIn($contactPhone) . "',
				ContactURL = '" . cIn($contactURL) . "',
				AllowRegister = '" . cIn($allowRegistration) . "',
				SpacesAvailable = '" . cIn($maxRegistration) . "',
				IsApproved = '" . cIn($eventStatus) . "',
				IsBillboard = '" . cIn($eventBillboard) . "',
				PublishDate = NOW(),
				LocID = '" . cIn($locID) . "',
				Cost = '" . cIn($cost) . "'";
	
	if(isset($_POST['eventDate'])){
		$query .= ", StartTime = " . $startTime . ",
					TBD = " . cIn($tbd) . ",
					EndTime = " . $endTime . ",
					StartDate = '" . cIn($eventDate) . "'";
	}//end if
	
	if($edittype == 1){
		$msgID = 1;
		$query .= " WHERE PkID = '" . cIn($editthis) . "'";
		if(isset($_POST['doEventful']) && $eventStatus == 1){
			$msgID = 6;
			$newPkID = cIn($editthis);
			$efRest = "/rest/events/new";
			include('EventfulAddEvent.php');
		}//end if
	} else {
		$msg = 2;
		$query .= " WHERE IsApproved = 2 AND SeriesID = '" . cIn($editthis) . "'";
		if(isset($_POST['doEventful']) && $eventStatus == 1){
			$msgID = 7;
			$resultEF = doQuery("Select PkID, StartDate, StartTime, EndTime, TBD FROM " . HC_TblPrefix . "events WHERE SeriesID = '" . $editthis . "'");
			while($row = mysql_fetch_row($resultEF)){
				$newPkID = $row[0];
				$eventDate = $row[1];
				$startTime = $row[2];
				$endTime = $row[3];
				$tbd  = $row[4];
				$efRest = "/rest/events/new";
				include('EventfulAddEvent.php');
			}//end while
		}//end if
		
	}//end if
	
	doQuery($query);
	
	if($eventStatus > 0){
		if($edittype == 1){
			doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($editthis));
			if(isset($_POST['catID'])){
				$catID = $_POST['catID'];
				foreach ($catID as $val){
					doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($editthis) . "', '" . cIn($val) . "')");
				}//end for
			}//end if
		} else {
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE SeriesID = '" . cIn($editthis) . "'");
			$catID = $_POST['catID'];
			while($row = mysql_fetch_row($result)){
				doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($row[0]));
				foreach ($catID as $val){
					doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($row[0]) . "', '" . cIn($val) . "')");
				}//end for
			}//end while
		}//end if
	} else {
		if($edittype == 1){
			$msg = 3;
			doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($editthis));
		} else {
			$msg = 4;
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE SeriesID = '" . cIn($editthis) . "'");
			
			while($row = mysql_fetch_row($result)){
				doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($row[0]));
			}//end while
		}//end if
	}//end if
	
	if($sendmsg > 0){
		$headers = "From: " . CalAdmin . " <" . CalAdminEmail . ">\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Reply-To: " . CalAdmin . " <" . CalAdminEmail . ">\n";
		$headers .= "Content-Type: text/html; charset=UTF-8;\n";
		
		$subject = CalName . " Event Status Change";
		$message = $subname . ",<br /><br />" . $message . "<br /><br />" . CalAdmin . "<br />" . CalAdminEmail;
		
		mail($subemail, $subject, $message, $headers);
	}//end if
	
	header("Location: " . CalAdminRoot . "/index.php?com=eventpending&msg=" . $msgID);	?>