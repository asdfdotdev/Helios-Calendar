<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/config.php');
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/event.php');
	
	$editthis = $_POST['editthis'];
	$edittype = $_POST['edittype'];
	$eventStatus = $_POST['eventStatus'];
	$eventBillboard = $_POST['eventBillboard'];
	$eventTitle = cleanQuotes($_POST['eventTitle']);
	$eventDesc = cleanQuotes($_POST['eventDescription'],0);
	$contactName = $_POST['contactName'];
	$contactEmail = $_POST['contactEmail'];
	$contactPhone = $_POST['contactPhone'];
	$contactURL = $_POST['contactURL'];
	$locID = $_POST['locPreset'];
	$cost = $_POST['cost'];
	$subname = $_POST['subname'];
	$subemail = $_POST['subemail'];
	$startTimeHour = (isset($_POST['startTimeHour'])) ? $_POST['startTimeHour'] : NULL;
	$endTimeHour = (isset($_POST['endTimeHour'])) ? $_POST['endTimeHour'] : NULL;
	$apiFail = false;
	
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
	
	if($locID == 0 && isset($_POST['newLoc'])){
		$address = $_POST['locAddress'];
		$address2 = $_POST['locAddress2'];
		$city = $_POST['locCity'];
		$state = $_POST['locState'];
		$country = $_POST['locCountry'];
		$zip = $_POST['locZip'];

		require_once('../../events/includes/api/google/GetGeocode.php');
		
		if($apiFail == false  && $lat != '' && $lon != ''){
			doQuery("INSERT INTO " . HC_TblPrefix . "locations(Name, Address, Address2, City, State, Country, Zip, Lat, Lon, IsPublic, IsActive, Phone)
				VALUES( '" . cIn(cleanQuotes($_POST['locName'])) . "',
						'" . cIn($address) . "',
						'" . cIn($address2) . "',
						'" . cIn($city) . "',
						'" . cIn($state) . "',
						'" . cIn($country) . "',
						'" . cIn($zip) . "',
						'" . cIn($lat) . "',
						'" . cIn($lon) . "',
						1,1,NULL)");
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "locations");
			$locID = mysql_result($result,0,0);
		}//end if
	}//end if
	
	$locName = ($locID == 0) ? cleanQuotes($_POST['locName']) : '';
	$locAddress = ($locID == 0) ? $_POST['locAddress'] : '';
	$locAddress2 = ($locID == 0) ? $_POST['locAddress2'] : '';
	$locCity = ($locID == 0) ? $_POST['locCity'] : '';
	$locState = ($locID == 0) ? $_POST['locState'] : '';
	$locZip = ($locID == 0) ? $_POST['locZip'] : '';
	$locCountry = ($locID == 0) ? $_POST['locCountry'] : '';
	$contactURL = (strrpos($contactURL,"http://") === 0) ? $contactURL : "http://" . $contactURL;
	$allowRegistration = $_POST['eventRegistration'];
	$maxRegistration = ($allowRegistration == 1) ? $_POST['eventRegAvailable'] : '0';
	$sendmsg = (isset($_POST['sendmsg']) && $_POST['sendmsg'] != "no" ) ? 1 : 0;
	$message = (isset($_POST['message'])) ? $_POST['message'] : '';
	
	$query = "UPDATE " . HC_TblPrefix . "events SET
				Title = '" . cIn($eventTitle) . "',
				Description = '" . cIn($eventDesc,0) . "',
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
		$eventIDs = array($editthis);
	} else {
		$msgID = ($eventStatus == 2) ? 9 : 2;
		$query .= " WHERE IsApproved = 2 AND SeriesID = '" . cIn($editthis) . "'";
		
		$resultD = doQuery("SELECT PkID FROM " . HC_TblPrefix . "events WHERE SeriesID = '" . cIn($editthis) . "'");
		if(hasRows($resultD)){
			$eventIDs = array();
			while($row = mysql_fetch_row($resultD)){
				$eventIDs[] = $row[0];
			}//end while
		}//end if
	}//end if
	
	doQuery($query);
	$stop = count($eventIDs);
	$catID = $_POST['catID'];
	$i = 0;
	while($i < $stop){
		doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($eventIDs[$i]));
		if(count($catID) > 0){
			foreach($catID as $val){
				doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($eventIDs[$i]) . "', '" . cIn($val) . "')");
			}//end foreach
		}//end if

		$efID = $ebID = '';
		$efNew = $ebNew = true;
		$tweets = array();
		$resultD = doQuery("SELECT * FROM " . HC_TblPrefix . "eventnetwork WHERE EventID = '" . cIn($eventIDs[$i]) . "'");
		if(hasRows($resultD)){
			while($row = mysql_fetch_row($resultD)){
				switch($row[2]){
					case 1:
						$efNew = false;
						$efID = $row[1];
						break;
					case 2:
						$ebNew = false;
						$ebID = $row[1];
						break;
				}//end if
			}//end while
		}//end if

		if(isset($_POST['doEventful'])){
			require_once('../../events/includes/api/eventful/EventEdit.php');

			if($efNew == true && $efID != ''){
				doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
						VALUES('" . cIn($eventIDs[$i]) . "','" . cIn($efID) . "',1,1);");
			}//end if
		}//end if

		if(isset($_POST['doEventbrite'])){
			require_once('../../events/includes/api/eventbrite/EventEdit.php');

			if($ebNew == true && $ebID != ''){
				doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
						VALUES('" . cIn($eventIDs[$i]) . "','" . cIn($ebID) . "',2,1);");
			}//end if
		}//end if
		++$i;
	}//end while

	if(isset($_POST['doTwitter'])){
		$eID = $eventIDs[0];
		$shortLink = CalRoot . "/index.php?com=detail&eID=" . $eID;
		require_once('../../events/includes/api/bitly/ShortenURL.php');

		$tweetLink = $shortLink;
		$tweetHash = $hc_cfg59;
		$twtrMsg = cleanQuotes($_POST['tweetThis']) . ' ' . $tweetLink . ' ' . $tweetHash;
		$showStatus = 1;
		require_once('../../events/includes/api/twitter/PostTweet.php');

		if($tweetID != ''){
			doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
					VALUES('" . $eID . "','" . cIn($tweetID) . "',3,1);");
		}//end if
	}//end if

	if($eventStatus == 0){
		$msgID = ($edittype == 1) ? 3 : 4;
	}//end if

	if($sendmsg > 0){
		$headers = "From: " . CalAdmin . " <" . CalAdminEmail . ">\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Reply-To: " . CalAdmin . " <" . CalAdminEmail . ">\n";
		$headers .= "Content-Type: text/html; charset=" . $hc_lang_config['CharSet'] . ";\n";
		
		$subject = CalName . " " . $hc_lang_event['EmailSubject'];
		$message = $subname . ",<br /><br />" . $message . "<br /><br />" . CalAdmin . "<br />" . CalAdminEmail;
		
		mail($subemail, $subject, $message, $headers);
	}//end if
	
	$hourOffset = date("G") + ($hc_cfg35);
	$curCache = date("Ymd", mktime($hourOffset,0,0,date("m"),date("d"),date("Y")));
	if(file_exists(realpath('../../events/cache/lmap' . $curCache . '.php'))){
		unlink('../../events/cache/lmap' . $curCache . '.php');
	}//end if
	if(file_exists(realpath('../../events/cache/sitemap_events.php'))){
		foreach(glob('../../events/cache/sitemap*.*') as $filename){
			unlink($filename);
		}//end foreach
	}//end if

	if($apiFail == false){
		header("Location: " . CalAdminRoot . "/index.php?com=eventpending&msg=" . $msgID);
	} else {
		echo '<br /><br />' . $hc_lang_event['APIError'] . '<br /><br />';
		echo '<a href="' . CalAdminRoot . "/index.php?com=eventpending&msg=" . $msgID . '">' . $hc_lang_event['APIErrorLink'] . '</a>';
	}//end if?>