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
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/event.php');

	$eID = $_POST['eID'];
	$eventStatus = $_POST['eventStatus'];
	$eventBillboard = $_POST['eventBillboard'];
	$eventTitle = $_POST['eventTitle'];
	$eventDesc = $_POST['eventDescription'];
	if(isset($_POST['eventDate'])){
		$eventDate = dateToMySQL($_POST['eventDate'], "/", $hc_cfg24);
	}//end if
	$contactName = $_POST['contactName'];
	$contactEmail = $_POST['contactEmail'];
	$contactPhone = $_POST['contactPhone'];
	$contactURL = $_POST['contactURL'];
	$locID = $_POST['locPreset'];
	$cost = $_POST['cost'];
	$startTimeHour = (isset($_POST['startTimeHour'])) ? $_POST['startTimeHour'] : NULL;
	$endTimeHour = (isset($_POST['endTimeHour'])) ? $_POST['endTimeHour'] : NULL;
	$apiFail = false;
	
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
	$allowRegistration = $_POST['eventRegistration'];
	$maxRegistration = ($allowRegistration == 1) ? $_POST['eventRegAvailable'] : '0';
	
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
		$eventIDs = array($eID);
		$hdrStr = "Location: " . CalAdminRoot . "/index.php?com=eventedit&eID=" . $eID  . "&msg=" . $msgID;
	} else {
		$msgID = 2;
		$query = $query . " WHERE PkID IN (" . cIn($_POST['editString']) . ")";
		$eventIDs = explode(",", $_POST['editString']);

		if(isset($_POST['makeseries'])){
			$seriesID = DecHex(microtime() * 1000000) . DecHex(microtime() * 9999999) . DecHex(microtime() * 8888888);
			doQuery("UPDATE " . HC_TblPrefix . "events SET SeriesID = '" . $seriesID . "' WHERE PkID IN (" . cIn($_POST['editString']) . ")");
		}//end if
		
		$hdrStr = "Location: " . CalAdminRoot . "/index.php?com=eventsearch&sID=1&msg=" . $msgID;
	}//end if

	doQuery($query);
	$stop = count($eventIDs);
	$catID = $_POST['catID'];
	$i = 0;
	while($i < $stop){
		doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($eventIDs[$i]));
		$x = 0;
		$stopC = count($catID);
		foreach($catID as $val){
			doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($eventIDs[$i]) . "', '" . cIn($val) . "')");
		}//end foreach

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
						VALUES('" . $eventIDs[$i] . "','" . cIn($efID) . "',1,1);");
			}//end if
		}//end if

		if(isset($_POST['doEventbrite'])){
			require_once('../../events/includes/api/eventbrite/EventEdit.php');

			if($ebNew == true && $ebID != ''){
				doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
						VALUES('" . $eventIDs[$i] . "','" . cIn($ebID) . "',2,1);");
			}//end if
		}//end if
		++$i;
	}//end while

	if(isset($_POST['doTwitter'])){
		$shortLink = CalRoot . "/index.php?com=detail&eID=" . $eID;
		require_once('../../events/includes/api/bitly/ShortenURL.php');

		$tweetLink = $shortLink;
		$tweetHash = $hc_cfg59;
		$twtrMsg = $_POST['tweetThis'] . ' ' . $tweetLink . ' ' . $tweetHash;
		$showStatus = 1;
		require_once('../../events/includes/api/twitter/PostTweet.php');

		if($tweetID != ''){
			doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
					VALUES('" . $eID . "','" . cIn($tweetID) . "',3,1);");
		}//end if
	}//end if
	
	$hourOffset = date("G") + ($hc_cfg35);
	$curCache = date("Ymd", mktime($hourOffset,0,0,date("m"),date("d"),date("Y")));
	if(file_exists(realpath('../../events/cache/lmap' . $curCache . '.php'))){
		unlink('../../events/cache/lmap' . $curCache . '.php');
	}//end if
	foreach(glob('../../events/cache/sitemap*.*') as $filename){
		unlink($filename);
	}//end foreach

	if($apiFail == false){
		header($hdrStr);
	} else {
		echo '<br /><br />' . $hc_lang_event['APIError'] . '<br /><br />';
		echo '<a href="' . $hdrStr . '">' . $hc_lang_event['APIErrorLink'] . '</a>';
	}//end if?>