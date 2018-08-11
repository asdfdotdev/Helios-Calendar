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
	
	$eID = 0;
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
	$publishDate = ($eventStatus == 1) ? "'" . SYSDATE . ' ' . SYSTIME . "'" : 'NULL';
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
	if(isset($_POST['recurCheck'])){
		$seriesID = "'" . DecHex(microtime() * 9999999) . DecHex(microtime() * 5555555) . DecHex(microtime() * 1111111) . "'";
		$stopDate = isset($_POST['recurEndDate']) ? dateToMySQL(cIn($_POST['recurEndDate']), $hc_cfg[24]) : '';
		$curDate = $eventDate;
		
		switch($_POST['recurType']){
			case 'daily':
				$days = isset($_POST['dailyDays']) ? cIn($_POST['dailyDays']) : 1;
				
				if($_POST['dailyOptions'] == 'EveryXDays'){
					while(strtotime($curDate) <= strtotime($stopDate)){
						$dates[] = $curDate;
						
						$dateParts = explode("-", $curDate);
						$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + $days, $dateParts[0]));
					}
				} else {
					while(strtotime($curDate) <= strtotime($stopDate)){
						$dateParts = explode("-", $curDate);
						$curDayOfWeek = date("w", mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
						
						if((($curDayOfWeek != 0) AND ($curDayOfWeek != 6)) OR $eventDate == $curDate)
							$dates[] = $curDate;
						
						$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + 1, $dateParts[0]));
					}
				}
				break;
			case 'weekly':
				$weeks = isset($_POST['recWeekly']) ? cIn($_POST['recWeekly']) : 1;
				$recWeeklyDay = isset($_POST['recWeeklyDay']) ? array_filter($_POST['recWeeklyDay'],'is_numeric') : array();
				
				while(strtotime($curDate) <= strtotime($stopDate)){
					$dateParts = explode("-", $curDate);
					$curDateDayOfWeek = date("w", mktime(0, 0, 0, $dateParts[1], $dateParts[2], $dateParts[0]));
					
					if(in_array($curDateDayOfWeek, $recWeeklyDay) OR $eventDate == $curDate)
						$dates[] = $curDate;
					
					$curDate = (($curDateDayOfWeek == 6) AND ($weeks > 1)) ?
							date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + ((($weeks - 1) * 7) + 1), $dateParts[0])) :
							date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + 1, $dateParts[0]));
				}
				break;
			case 'monthly':
				if($_POST['monthlyOption'] == 'Day'){
					$day = isset($_POST['monthlyDays']) ? cIn($_POST['monthlyDays']) : 1;
					$months = isset($_POST['monthlyMonths']) ? cIn($_POST['monthlyMonths']) : 1;
					
					while(strtotime($curDate) <= strtotime($stopDate)){
						$dates[] = $curDate;
						$dateParts = explode("-", $curDate);
						$curDate = ($dateParts[2] < $day) ? 
								date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $day, $dateParts[0])) : 
								date("Y-m-d", mktime(0, 0, 0, $dateParts[1] + $months, $day, $dateParts[0]));	
					}
				} else {
					$whichDay = isset($_POST['monthlyMonthOrder']) ? cIn($_POST['monthlyMonthOrder']) : 1;
					$whichDOW = isset($_POST['monthlyMonthDOW']) ? cIn($_POST['monthlyMonthDOW']) : 0;
					$whichRepeat = isset($_POST['monthlyMonthRepeat']) ? cIn($_POST['monthlyMonthRepeat']) : 1;
					
					while(strtotime($curDate) <= strtotime($stopDate)){
						$dates[] = $curDate;
						$dateParts = explode("-", $curDate);
						$curMonth = $dateParts[1];
						$curYear = $dateParts[0];
						$cnt = 0;
						
						if($whichDay != 0){
							$x = date("w", mktime(0, 0, 0, $curMonth + $whichRepeat, 1, $curYear));
							while($x % 7 != $whichDOW){
								$x++;
								$cnt++;
							}
							$curDate = date("Y-m-d", mktime(0, 0, 0, $curMonth + $whichRepeat, (1 + $cnt) + ((7 * $whichDay) - 7), $curYear));
						} else {
							$x = date("w", mktime(0, 0, 0, $curMonth + $whichRepeat + 1, 0, $curYear));
							$offset = 0;
							if($x < $whichDOW){$x = $x + 7;}
							while((abs($x) % 7) != $whichDOW){
								$x--;
								$cnt++;
							}
							$curDate = date("Y-m-d", mktime(0, 0, 0, $curMonth + $whichRepeat + 1, 0 - $cnt, $curYear));
						}
					}
				}
				break;
		}
	} else {
		$seriesID = 'NULL';
		$dates[] = $eventDate;
	}
	
	foreach($dates as $eventDate){
		doQuery("INSERT INTO " . HC_TblPrefix . "events(Title, LocationName, LocationAddress, LocationAddress2, LocationCity, LocationState, LocationZip, Description,
						StartDate, StartTime, TBD, EndTime, ContactName,ContactEmail, ContactPhone, ContactURL, IsActive, IsApproved,
						IsBillboard, SeriesID, PublishDate, LocID, Cost, LocCountry, LastMod, Image, IsFeature, HideDays)
				VALUES('".$eventTitle."', '".$locName."','".$locAddress."','".$locAddress2."','".$locCity."','".$locState."','".$locZip."','".cIn($eventDesc,0) . "',
						'".$eventDate."',".$startTime.",'".$tbd."',".$endTime.",'".$contactName."','".$contactEmail."','".$contactPhone."','".$contactURL."',
						'1','".$eventStatus."','".$eventBillboard."',".$seriesID.",".$publishDate.",
						'".$locID."','".$cost."','".$locCountry."','" . SYSDATE . ' ' . SYSTIME . "', '".$imageURL."', '".$featured."', '".$hide."')");

		$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "events");
		$newPkID = mysql_result($result,0,0);
		$eID = ($eID == 0) ? $newPkID : $eID;
		foreach($catID as $val){
			doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($newPkID) . "', '" . cIn($val) . "')");
		}
		
		if($rsvp_type == 1 && $newPkID > 0)
			doQuery("INSERT INTO " . HC_TblPrefix . "eventrsvps(Type,EventID,OpenDate,CloseDate,Space,RegOption,Notices)
					VALUES('".$rsvp_type."','".$newPkID."','".$rsvp_open."','".$rsvp_close."','".$rsvp_space."','".$rsvp_disp."','".$rsvp_notice."')");
		
		if(isset($_POST['doFacebook']) && isset($_POST['facebookEvent'])){
			$fbID = '';
			$fbLink = CalRoot . "/index.php?eID=" . $newPkID;
			$fbEventID = $newPkID;
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

			if($fbID != '')
				doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
						VALUES('" . $newPkID . "','" . cIn($fbID) . "',5,1);");
		}
		if(isset($_POST['doEventbrite'])){
			$ebID = '';
			include(HCPATH.HCINC.'/api/eventbrite/EventEdit.php');
			
			if($ebID != ''){
				doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
						VALUES('" . $newPkID . "','" . cIn($ebID) . "',2,1);");
				
				include(HCPATH.HCINC.'/api/eventbrite/TicketEdit.php');
				
				if(isset($_POST['ebPaypal']) || isset($_POST['ebGoogleC']))
					include(HCPATH.HCINC.'/api/eventbrite/PaymentEdit.php');
			}
		}
	}
	if($follow_up > 0){
		$entityID = ($seriesID != 'NULL') ? str_replace('\'','',$seriesID) : $eID;
		$entityType = ($seriesID != 'NULL') ? 2 : 1;
		doQuery("INSERT INTO " . HC_TblPrefix . "followup(EntityID, EntityType, Note) VALUES('".$entityID."','".$entityType."','".$fnote."')");
	}
	if(isset($_POST['doBitly'])){
		$shortLink = CalRoot . "/index.php?eID=" . $eID;
		require(HCPATH.HCINC.'/api/bitly/ShortenURL.php');
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
						VALUES('" . $newPkID . "','" . cIn($tweetID) . "',3,1);");
		}
	}
	if(isset($_POST['doFacebook']) && isset($_POST['facebookStatus'])){
		$fbStatusID = '';
		$fbStatus = cleanQuotes($_POST['fbThis']);
		$fbLink = CalRoot . "/index.php?eID=" . $eID;
		
		include(HCPATH.HCINC.'/api/facebook/StatusPost.php');

		if($fbStatusID != '')
			doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive)
					VALUES('" . $newPkID . "','" . cIn($fbStatusID) . "',4,1);");
	}
	
	clearCache();
	
	if($apiFail == false){
		header("Location: " . AdminRoot . "/index.php?com=eventedit&msg=" . $msgID . "&eID=" . $eID);
	} else {
		echo '<br /><br />' . $hc_lang_event['APIError'] . '<br /><br />';
		echo '<a href="' . AdminRoot . '/index.php?com=eventedit&msg=' . $msgID . '&eID=' . $eID . '">' . $hc_lang_event['APIErrorLink'] . '</a>';
	}
?>