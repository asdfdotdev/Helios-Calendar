<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	post_only();
	
	$token = (isset($_POST['token'])) ? cIn(strip_tags($_POST['token'])) : '';
	if(!check_form_token($token))
		go_home();
	
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
	$imageURL = (isset($_POST['imageURL'])) ? cIn($_POST['imageURL']) : '';
	$imageURL = (preg_match('/^https?:\/\//',$imageURL) || $imageURL == '') ? $imageURL : 'http://'.$imageURL;
	$featured = (isset($_POST['eventFeatured']) && is_numeric($_POST['eventFeatured'])) ? cIn($_POST['eventFeatured']) : '0';
	$hide = (isset($_POST['eventHide']) && is_numeric($_POST['eventHide'])) ? cIn($_POST['eventHide']) : '0';
	$msgID = 2;
	$dates = array();
	$catID = (isset($_POST['catID'])) ? array_filter($_POST['catID'],'is_numeric') : '';
	$apiFail = false;
	$locName = $locAddress = $locAddress2 = $locCity = $locState = $locZip = $locCountry = '';
	$allowRegistration = isset($_POST['eventRegistration']) ? cIn($_POST['eventRegistration']) : '0';
	$maxRegistration = ($allowRegistration == 1) ? cIn($_POST['eventRegAvailable']) : 0;
	$follow_up = isset($_POST['follow_up']) ? cIn($_POST['follow_up']) : 0;
	$fnote = isset($_POST['follow_note']) ? cIn(cleanQuotes($_POST['follow_note'])) : '';
	$rsvp_type = (isset($_POST['rsvp_type']) && is_numeric($_POST['rsvp_type'])) ? cIn($_POST['rsvp_type']) : 0;
	$rsvp_space = (isset($_POST['rsvp_space']) && is_numeric($_POST['rsvp_space'])) ? cIn($_POST['rsvp_space']) : 0;
	$rsvp_disp = (isset($_POST['rsvpFor']) && is_numeric($_POST['rsvpFor'])) ? cIn($_POST['rsvpFor']) : 0;
	$rsvp_open = isset($_POST['openDate']) ? dateToMySQL(cIn($_POST['openDate']), $hc_cfg[24]) : '';
	$rsvp_close = isset($_POST['closeDate']) ? dateToMySQL(cIn($_POST['closeDate']), $hc_cfg[24]) : '';
	$rsvp_notice = (isset($_POST['rsvpEmail']) && is_numeric($_POST['rsvpEmail'])) ? cIn($_POST['rsvpEmail']) : 0;
	
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
			LocID = " . $locID . ",
			Cost = '" . $cost . "',
			LocCountry = '" . $locCountry . "',
			LastMod = '" . SYSDATE . ' ' . SYSTIME . "',
			Image = '" . $imageURL . "',
			IsFeature = '" . $featured . "',
			HideDays = '" . $hide . "'";
					
	if($_POST['prevStatus'] == 2 && $eventStatus == 1)
		$query .= ", PublishDate = '" . SYSDATE . ' ' . SYSTIME . "'";
	
	if(!isset($_POST['editString']) || $_POST['editString'] == ''){
		$msgID = 1;
		$query .= ", StartDate = '" . cIn($eventDate) . "' WHERE PkID = '" . $eID . "' ";
		$eventIDs = array($eID);
		$hdrStr = AdminRoot . "/index.php?com=eventedit&eID=" . $eID  . "&msg=" . $msgID;
	} else {
		$msgID = 2;
		$query = $query . " WHERE PkID IN (" . cIn($_POST['editString']) . ")";
		$eventIDs = explode(",", $_POST['editString']);
		$eID = $eventIDs[0];
		
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
		doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = '" . cIn($eventIDs[$i]) . "'");
		foreach($catID as $val)
			doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($eventIDs[$i]) . "', '" . cIn($val) . "')");
		
		$efID = $ebID = $fbID = '';
		$efNew = true;
		$resultD = doQuery("SELECT * FROM " . HC_TblPrefix . "eventnetwork WHERE EventID = '" . cIn($eventIDs[$i]) . "'");
		if(hasRows($resultD)){
			while($row = mysql_fetch_row($resultD)){
				switch($row[2]){
					case 1:
						$efNew = false;
						$efID = $row[1];
						break;
					case 2:
						$ebID = $row[1];
						break;
					case 5:
						$fbID = $row[1];
						break;
				}
			}
		}
		if($rsvp_type == 1){
			doQuery("DELETE FROM " . HC_TblPrefix . "eventrsvps WHERE EventID = '".cIn($eventIDs[$i])."'");
			
			doQuery("INSERT INTO " . HC_TblPrefix . "eventrsvps(Type,EventID,OpenDate,CloseDate,Space,RegOption,Notices)
					VALUES('".$rsvp_type."','".cIn($eventIDs[$i])."','".$rsvp_open."','".$rsvp_close."','".$rsvp_space."','".$rsvp_disp."','".$rsvp_notice."')");
		}
		if(isset($_POST['doEventbrite']) || isset($_POST['doFacebook'])){
			$result = doQuery("SELECT StartDate FROM " . HC_TblPrefix . "events WHERE PkID = '" . cIn($eventIDs[$i]) . "'");
			if(hasRows($result)){
				$eventDate = mysql_result($result,0,0);
				
				if(isset($_POST['doFacebook']) && isset($_POST['facebookEvent'])){
					$fbNew = ($fbID == '') ? true : false;
					$fbStatus = cleanQuotes($_POST['fbThis']);
					$fbLink = CalRoot . "/index.php?eID=" . $eventIDs[$i];
					$fbEventID = $eventIDs[$i];
					if(!isset($name) || $name == ''){
						$resultL = doQuery("SELECT Name, Address, Address2, City, State, Zip, Country FROM " . HC_TblPrefix . "locations WHERE PkID = '" . cIn($locID) . "'");
						$name = (hasRows($resultL)) ? mysql_result($resultL,0,0) : $locName;
						$add = (hasRows($resultL)) ? mysql_result($resultL,0,1) : $locAddress;
						$add2 = (hasRows($resultL)) ? mysql_result($resultL,0,2) : $locAddress2;
						$city = (hasRows($resultL)) ? mysql_result($resultL,0,3) : $locCity;
						$region = (hasRows($resultL)) ? mysql_result($resultL,0,4) : $locState;
						$postal = (hasRows($resultL)) ? mysql_result($resultL,0,5) : $locZip;
						$country = (hasRows($resultL)) ? mysql_result($resultL,0,6) : $locCountry;
					}

					include(HCPATH.HCINC.'/api/facebook/EventEdit.php');

					if($fbNew == true && $fbID != '')
						doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
								VALUES('" . cIn($eventIDs[$i]) . "','" . cIn($fbID) . "',5,1);");
				}
				if(isset($_POST['doEventbrite'])){
					$ebNew = ($ebID == '') ? true : false;
					
					include(HCPATH.HCINC.'/api/eventbrite/EventEdit.php');

					if($ebID != ''){
						if($ebNew){
							doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
									VALUES('" . cIn($eventIDs[$i]) . "','" . cIn($ebID) . "',2,1);");
						}
						
						include(HCPATH.HCINC.'/api/eventbrite/TicketEdit.php');

						if(isset($_POST['ebPaypal']) || isset($_POST['ebGoogleC']))
							include(HCPATH.HCINC.'/api/eventbrite/PaymentEdit.php');
					}
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
		require(HCPATH.HCINC.'/api/bitly/ShortenURL.php');
		
		$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(46,47,111,112)");
		if(hasRows($result)){
			$oauth_token = mysql_result($result,0,0);
			$oauth_secret = mysql_result($result,1,0);
			$consumer_key = mysql_result($result,2,0);
			$consumer_secret = mysql_result($result,3,0);
		} else {
			$apiFail = true;
			echo $hc_lang_event['APITwitterSettings'];
		}
		if($consumer_key != '' && $consumer_secret != ''){
			$tweetID = '';
			$twtrMsg = cleanQuotes($_POST['tweetThis']).' '.$shortLink.' '.$hc_cfg[59];
			
			require_once(HCPATH.HCINC.'/api/twitter/PostTweet.php');

			if($tweetID != '')
				doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
						VALUES('" . $eID . "','" . cIn($tweetID) . "',3,1);");
		}
	}
	if(isset($_POST['doFacebook']) && isset($_POST['facebookStatus'])){
		$fbStatusID = '';
		$fbStatus = cleanQuotes($_POST['fbThis']);
		$fbLink = CalRoot . "/index.php?eID=" . $eID;
		
		include(HCPATH.HCINC.'/api/facebook/StatusPost.php');

		if($fbStatusID != '')
			doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
					VALUES('" . $eID . "','" . cIn($fbStatusID) . "',4,1);");
	}
	
	clearCache();

	if($apiFail == false){
		header("Location: " . $hdrStr);
	} else {
		echo '<br /><br />' . $hc_lang_event['APIError'] . '<br /><br />';
		echo '<a href="' . $hdrStr . '">' . $hc_lang_event['APIErrorLink'] . '</a>';
	}
?>