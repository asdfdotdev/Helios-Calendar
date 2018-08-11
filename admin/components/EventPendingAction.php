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
	
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/event.php');
		
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
	$startTimeHour = $_POST['startTimeHour'];
	$endTimeHour = $_POST['endTimeHour'];
	
	if(isset($_POST['eventDate'])){
		$eventDate = dateToMySQL($_POST['eventDate'], "/", $hc_cfg24);
		
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
	}//end if
	
	$locName = "";
	$locAddress = "";
	$locAddress2 = "";
	$locCity = "";
	$locState = "";
	$locZip = "";
	$locCountry = "";
	if($locID == 0){
		if(isset($_POST['newLoc'])){
			$lat = "";
			$lon = "";
			
			$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 26");
			$googleAPI = mysql_result($result,0,0);
			if($googleAPI != ''){
				$ip = gethostbyname('maps.google.com');
				if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
					$failed = 1;
				} else {
					$read = "";
					$request = "GET /maps/geo?output=csv&key=" . $googleAPI . "&q=" . urlencode(cIn($_POST['locAddress']) . " " . cIn($_POST['locCity']) . " " . cIn($_POST['locState']) . " " . cIn($_POST['locZip'])) . " HTTP/1.1\r\n";
					$request .= "Host: maps.google.com\r\n";
					$request .= "Connection: Close\r\n\r\n";
					
					fwrite($fp, $request);
					
					while (!feof($fp)) {
						$read .= fread($fp,1024);
					}//end while
					
					$output = strtoupper($read);
					$output = explode('CONNECTION: CLOSE', $output);
					$output = str_replace("\r", " ", $output[1]);
					$output = str_replace("\n", " ", $output);
					$output = ltrim($output, " ");
					$output = rtrim($output, " 0");
					$output = explode(',',$output);
					
					if(is_numeric($output[1]) && is_numeric($output[2]) && is_numeric($output[3])){
						$gQuality = $output[1];
						$lat = $output[2];
						$lon = $output[3];
					} else {
						$failed = 1;
					}//end if
					
					fclose($fp);
				}//end if
			} else {
				$failed = 1;
			}//end if
			
			doQuery("INSERT INTO " . HC_TblPrefix . "locations(Name, Address, Address2, City, State, Country, Zip, Lat, Lon, IsPublic, IsActive, Phone)
				VALUES( '" . cIn($_POST['locName']) . "',
						'" . cIn($_POST['locAddress']) . "',
						'" . cIn($_POST['locAddress2']) . "',
						'" . cIn($_POST['locCity']) . "',
						'" . cIn($_POST['locState']) . "',
						'" . cIn($_POST['locCountry']) . "',
						'" . cIn($_POST['locZip']) . "',
						'" . cIn($lat) . "',
						'" . cIn($lon) . "',
						1,1,NULL)");
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "locations");
			$locID = mysql_result($result,0,0);
		} else {
			$locName = $_POST['locName'];
			$locAddress = $_POST['locAddress'];
			$locAddress2 = $_POST['locAddress2'];
			$locCity = $_POST['locCity'];
			$locState = $_POST['locState'];
			$locZip = $_POST['locZip'];
			$locCountry = $_POST['locCountry'];
		}//end if
	}//end if
	
	if( !ereg("^http://*", $contactURL, $regs) ){
	   $contactURL = "http://" . $contactURL;
	}//end if
	
	$allowRegistration = $_POST['eventRegistration'];
	$maxRegistration = ($allowRegistration == 1) ? $_POST['eventRegAvailable'] : '0';
	$sendmsg = (isset($_POST['sendmsg']) && $_POST['sendmsg'] != "no" ) ? 1 : 0;
	
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
		$msgID = ($eventStatus == 2) ? 8 : 1;
		
		$query .= " WHERE PkID = '" . cIn($editthis) . "'";
		if(isset($_POST['doEventful']) && $eventStatus == 1){
			$msgID = 6;
			$newPkID = cIn($editthis);
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
		}//end if
	} else {
		$msgID = ($eventStatus == 2) ? 9 : 2;
		
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
			$msgID = 3;
			doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($editthis));
		} else {
			$msgID = 4;
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
		
		$subject = CalName . " " . $hc_lang_event['EmailSubject'];
		$message = $subname . ",<br /><br />" . $message . "<br /><br />" . CalAdmin . "<br />" . CalAdminEmail;
		
		mail($subemail, $subject, $message, $headers);
	}//end if
	
	$hourOffset = date("G") + ($hc_cfg35);
	$curCache = date("Ymd", mktime($hourOffset,0,0,date("m"),date("d"),date("Y")));
	if(file_exists(realpath('../../events/cache/lmap' . $curCache . '.php'))){
		unlink('../../events/cache/lmap' . $curCache . '.php');
	}//end if
	
	header("Location: " . CalAdminRoot . "/index.php?com=eventpending&msg=" . $msgID);	?>