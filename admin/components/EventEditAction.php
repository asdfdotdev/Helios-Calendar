<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	post_only();
	
	include(HCLANG.'/admin/event.php');

	$eID = isset($_POST['eID']) ? cIn($_POST['eID']) : 0;
	$fID = isset($_POST['fID']) ? cIn($_POST['fID']) : 0;
	$eventStatus = isset($_POST['eventStatus']) ? cIn($_POST['eventStatus']) : '';
	$eventBillboard = isset($_POST['eventBillboard']) ? cIn($_POST['eventBillboard']) : '0';
	$eventTitle = isset($_POST['eventTitle']) ? cIn(cleanQuotes($_POST['eventTitle'])) : '';
	$eventDesc = isset($_POST['eventDescription']) ? cleanQuotes($_POST['eventDescription'],0) : '';
	$eventDate = isset($_POST['eventDate']) ? dateToMySQL(cIn($_POST['eventDate']), $hc_cfg[24]) : '';
	$contactName = isset($_POST['contactName']) ? cIn($_POST['contactName']) : NULL;
	$contactEmail = isset($_POST['contactEmail']) ? cIn($_POST['contactEmail']) : NULL;
	$contactPhone = isset($_POST['contactPhone']) ? cIn($_POST['contactPhone']) : NULL;
	$contactURL = (isset($_POST['contactURL'])) ? cIn($_POST['contactURL']) : '';
	$contactURL = (preg_match('/^https?:\/\//',$contactURL) || $contactURL == '') ? $contactURL : 'http://'.$contactURL;
	$locID = isset($_POST['locPreset']) ? cIn($_POST['locPreset']) : '0';
	$startTimeHour = isset($_POST['startTimeHour']) ? cIn($_POST['startTimeHour']) : NULL;
	$startTimeMins = isset($_POST['startTimeMins']) ? cIn($_POST['startTimeMins']) : NULL;
	$startTimeAMPM = isset($_POST['startTimeAMPM']) ? cIn($_POST['startTimeAMPM']) : NULL;
	$endTimeHour = isset($_POST['endTimeHour']) ? cIn($_POST['endTimeHour']) : NULL;
	$endTimeMins = isset($_POST['endTimeMins']) ? cIn($_POST['endTimeMins']) : NULL;
	$endTimeAMPM = isset($_POST['endTimeAMPM']) ? cIn($_POST['endTimeAMPM']) : NULL;
	$cost = isset($_POST['cost']) ? cIn($_POST['cost']) : '';
	$msgID = 2;
	$dates = array();
	$catID = (isset($_POST['catID'])) ? array_filter($_POST['catID'],'is_numeric') : '';
	$apiFail = false;
	$locName = $locAddress = $locAddress2 = $locCity = $locState = $locZip = $locCountry = '';
	$allowRegistration = isset($_POST['eventRegistration']) ? cIn($_POST['eventRegistration']) : '0';
	$maxRegistration = ($allowRegistration == 1) ? cIn($_POST['eventRegAvailable']) : 0;
	$publishDate = ($eventStatus == 1) ? "'".cIn(date("Y-m-d H:i:s"))."'" : 'NULL';
	$follow_up = isset($_POST['follow_up']) ? cIn($_POST['follow_up']) : 0;
	$fnote = isset($_POST['follow_note']) ? cIn(cleanQuotes($_POST['follow_note'])) : '';
	if($locID == 0){
		$locName = cIn(cleanQuotes($_POST['locName']));
		$locAddress = cIn($_POST['locAddress']);
		$locAddress2 = cIn($_POST['locAddress2']);
		$locCity = cIn($_POST['locCity']);
		$locState = cIn($_POST['locState']);
		$locZip = cIn($_POST['locZip']);
		$locCountry = cIn($_POST['locCountry']);
	}
	if(!isset($_POST['overridetime'])){
		if($hc_cfg[31] == 12){
			$startTimeHour = ($startTimeAMPM == 'PM') ? ($startTimeHour < 12 ? $startTimeHour + 12 : $startTimeHour) : ($startTimeHour == 12 ? 0 : $startTimeHour);
			if(!isset($_POST['ignoreendtime']))
				$endTimeHour = ($endTimeAMPM == 'PM') ? ($endTimeHour < 12 ? $endTimeHour + 12 : $endTimeHour) : ($endTimeHour == 12 ? 0 : $endTimeHour);
		}
		
		$tbd = 0;
		$startTime = "'".$startTimeHour.":".$startTimeMins.":00'";
		$endTime = (!isset($_POST['ignoreendtime'])) ? "'".$endTimeHour.":".$endTimeMins.":00'" : 'NULL';
	} else {
		$startTime = $endTime = 'NULL';
		$tbd = ($_POST['specialtime'] == 'allday') ? 1 : 2;
	}
	
	$query = "UPDATE " . HC_TblPrefix . "events
			SET Title = '" . $eventTitle . "',
			LocationName = '" . $locName . "',
			LocationAddress = '" . $locAddress . "',
			LocationAddress2 = '" . $locAddress2 . "',
			LocationCity = '" . $locCity . "',
			LocationState = '" . $locState . "',
			LocationZip = '" . $locZip . "',
			Description = '" . cIn($eventDesc,0) . "',
			StartTime = " . $startTime . ",
			TBD = " . $tbd . ",
			EndTime = " . $endTime . ",
			ContactName = '" . $contactName . "',
			ContactEmail = '" . $contactEmail . "',
			ContactPhone = '" . $contactPhone . "',
			IsApproved = '" . $eventStatus . "',
			IsBillboard = '" . $eventBillboard . "',
			ContactURL = '" . $contactURL . "',
			AllowRegister = " . $allowRegistration . ",
			SpacesAvailable = " . $maxRegistration . ",
			LocID = " . $locID . ",
			Cost = '" . $cost . "',
			LocCountry = '" . $locCountry . "'";
					
	if($_POST['prevStatus'] == 2 && $eventStatus == 1)
		$query .= ", PublishDate = '" . date("Y-m-d H:i:s") . "'";
	
	if(!isset($_POST['editString']) || $_POST['editString'] == ''){
		$msgID = 1;
		$query .= ", StartDate = '" . cIn($eventDate) . "' WHERE PkID = '" . $eID . "' ";
		$eventIDs = array($eID);
		$hdrStr = AdminRoot . "/index.php?com=eventedit&eID=" . $eID  . "&msg=" . $msgID;
	} else {
		$msgID = 2;
		$query = $query . " WHERE PkID IN (" . cIn($_POST['editString']) . ")";
		$eventIDs = explode(",", $_POST['editString']);

		if(isset($_POST['makeseries'])){
			$seriesID = DecHex(microtime() * 1000000) . DecHex(microtime() * 9999999) . DecHex(microtime() * 8888888);
			doQuery("UPDATE " . HC_TblPrefix . "events SET SeriesID = '" . $seriesID . "' WHERE PkID IN (" . cIn($_POST['editString']) . ")");
		}
		
		$hdrStr = AdminRoot . "/index.php?com=eventsearch&sID=1&msg=" . $msgID;
	}

	doQuery($query);
	$stop = count($eventIDs);
	$catID = $_POST['catID'];
	$i = 0;
	while($i < $stop){
		doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($eventIDs[$i]));
		foreach($catID as $val)
			doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($eventIDs[$i]) . "', '" . cIn($val) . "')");
		
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
				}
			}
		}
		if(isset($_POST['doEventful']) || isset($_POST['doEventbrite'])){
			$result = doQuery("SELECT StartDate FROM " . HC_TblPrefix . "events WHERE PkID = '" . cIn($eventIDs[$i])."'");
			if(hasRows($result)){
				$eventDate = mysql_result($result,0,0);
				
				if(isset($_POST['doEventful'])){
					require(HCPATH.HCINC.'/api/eventful/EventEdit.php');

					if($efNew == true && $efID != '')
						doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
								VALUES('" . $eventIDs[$i] . "','" . cIn($efID) . "',1,1);");
				}
				if(isset($_POST['doEventbrite'])){
					require(HCPATH.HCINC.'/api/eventbrite/EventEdit.php');

					if($ebNew == true && $ebID != '')
						doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
								VALUES('" . $eventIDs[$i] . "','" . cIn($ebID) . "',2,1);");
				}
			}
		}
		if(isset($_POST['doBitly']) && $eventIDs[$i] > 0){
			$shortLink = CalRoot . "/index.php?eID=" . $eventIDs[$i];
			$eID = $eventIDs[$i];
			require(HCPATH.HCINC.'/api/bitly/ShortenURL.php');
		}
		++$i;
	}
	$entityID = $fID;
	$entityType = ($stop > 1) ? 2 : 1;
	if($follow_up > 0){
		$resultF = doQuery("SELECT * FROM " . HC_TblPrefix . "followup WHERE EntityID = '" . cIn($entityID) . "' AND EntityType = '" . cIn($entityType) . "'");
		if(hasRows($resultF)){
			doQuery("UPDATE " . HC_TblPrefix . "followup SET Note = '".$fnote."' WHERE EntityID = '" . cIn($entityID) . "' AND EntityType = '" . cIn($entityType) . "'");
		} else {
			doQuery("INSERT INTO " . HC_TblPrefix . "followup(EntityID, EntityType, Note) VALUES('".$entityID."','".$entityType."','".$fnote."')");
		}
	} else {
		doQuery("DELETE FROM " . HC_TblPrefix . "followup WHERE EntityID = '" . cIn($entityID) . "' AND EntityType = '" . cIn($entityType) . "'");
	}
	if(isset($_POST['doTwitter'])){
		$shortLink = CalRoot . "/index.php?eID=" . $eID;
		require_once(HCPATH.HCINC.'/api/bitly/ShortenURL.php');
		
		$tweetID = '';
		$tweetLink = $shortLink;
		$tweetHash = $hc_cfg[59];
		$twtrMsg = cleanQuotes($_POST['tweetThis']) . ' ' . $tweetLink . ' ' . $tweetHash;
		$showStatus = 1;
		require_once(HCPATH.HCINC.'/api/twitter/PostTweet.php');

		if($tweetID != '')
			doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
					VALUES('" . $eID . "','" . cIn($tweetID) . "',3,1);");
	}
	
	clearCache();

	if($apiFail == false){
		header("Location: " . $hdrStr);
	} else {
		echo '<br /><br />' . $hc_lang_event['APIError'] . '<br /><br />';
		echo '<a href="' . $hdrStr . '">' . $hc_lang_event['APIErrorLink'] . '</a>';
	}
?>