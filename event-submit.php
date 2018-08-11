<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include(dirname(__FILE__).'/loader.php');
	
	action_headers();
	post_only();
	
	if($hc_cfg[1] == 0){
		exit();}

	include(HCPATH.HCINC.'/functions/events.php');
	include(HCLANG.'/config.php');
	include(HCLANG.'/public/submit.php');

	$proof = $challenge = '';
	if($hc_cfg[65] == 1){
		$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
		$challenge = isset($_SESSION['hc_cap']) ? $_SESSION['hc_cap'] : NULL;
	} elseif($hc_cfg[65] == 2){
		$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
		$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
	}
	spamIt($proof,$challenge,1);
	
	$newPkID = 0;
	$dates = array();
	$appStatus = (user_check_status() && $_SESSION['UserLevel'] == 2) ? 1 : 2;
	$pubDate = ($appStatus == 1) ? "'".SYSDATE.' '.SYSTIME."'" : 'NULL';
	$filter = array('/onclick=["\'][^"\']+["\']/i','/ondblclick=["\'][^"\']+["\']/i','/onkeydown=["\'][^"\']+["\']/i','/onkeypress=["\'][^"\']+["\']/i','/onkeyup=["\'][^"\']+["\']/i','/onmousedown=["\'][^"\']+["\']/i','/onmousemove=["\'][^"\']+["\']/i','/onmouseout=["\'][^"\']+["\']/i','/onmouseover=["\'][^"\']+["\']/i','/onmouseup=["\'][^"\']+["\']/i','/onmousemove=["\'][^"\']+["\']/i','/onfocus=["\'][^"\']+["\']/i','/onblur=["\'][^"\']+["\']/i');
	$eID = $tbd = $stop = 0;
	$subName = isset($_POST['submitName']) ? htmlspecialchars(strip_tags($_POST['submitName'])) : NULL;
	$subEmail = isset($_POST['submitEmail']) ? htmlspecialchars(strip_tags($_POST['submitEmail'])) : NULL;
	$subID = isset($_POST['submitID']) ? htmlspecialchars(strip_tags($_POST['submitID'])) : NULL;
	$eventTitle = isset($_POST['eventTitle']) ? htmlspecialchars(cleanQuotes(strip_tags($_POST['eventTitle']))) : NULL;
	$eventDesc = isset($_POST['eventDescription']) ? cleanQuotes(strip_tags($_POST['eventDescription'],'<abbr><acronym><blockquote><br><caption><center><cite><dd><del><dfn><dir><div><dl><dt><em><i><font><hr><img><legend><li><menu><ol><p><pre><listing><plaintext><q><small><span><strike><strong><b><style><sub><sup><table><td><tr><tt><u><ul><var>'),0) : NULL;
		$eventDesc = preg_replace($filter,'',$eventDesc);
	$locID = isset($_POST['locPreset']) ? htmlspecialchars(strip_tags($_POST['locPreset'])) : NULL;
	$contactName = isset($_POST['contactName']) ? htmlspecialchars(strip_tags($_POST['contactName'])) : NULL;
	$contactEmail = isset($_POST['contactEmail']) ? htmlspecialchars(strip_tags($_POST['contactEmail'])) : NULL;
	$contactPhone = isset($_POST['contactPhone']) ? htmlspecialchars(strip_tags($_POST['contactPhone'])) : NULL;
	$contactURL = (isset($_POST['contactURL'])) ? cIn(htmlspecialchars(strip_tags($_POST['contactURL']))) : NULL;
		$contactURL = (preg_match('/^https?:\/\//',$contactURL) || $contactURL == '') ? $contactURL : 'http://'.$contactURL;
	$cost = isset($_POST['cost']) ? htmlspecialchars(strip_tags($_POST['cost'])) : NULL;
	$startTimeHour = isset($_POST['startTimeHour']) ? strip_tags($_POST['startTimeHour']) : NULL;
	$endTimeHour = isset($_POST['endTimeHour']) ? strip_tags($_POST['endTimeHour']) : NULL;
	$adminMessage = (isset($_POST['adminmessage'])) ? cIn(htmlspecialchars(cleanQuotes(strip_tags($_POST['adminmessage'])))) : '';
	$rsvp_type = (isset($_POST['rsvp_type']) && is_numeric($_POST['rsvp_type'])) ? htmlspecialchars(strip_tags($_POST['rsvp_type'])) : 0;
	$rsvp_space = (isset($_POST['rsvp_space']) && is_numeric($_POST['rsvp_space'])) ? htmlspecialchars(strip_tags($_POST['rsvp_space'])) : 0;
	$rsvp_disp = (isset($_POST['rsvpFor']) && is_numeric($_POST['rsvpFor'])) ? htmlspecialchars(strip_tags($_POST['rsvpFor'])) : 0;
	$rsvp_open = isset($_POST['openDate']) ? dateToMySQL(htmlspecialchars(strip_tags($_POST['openDate'])), $hc_cfg[24]) : '';
	$rsvp_close = isset($_POST['closeDate']) ? dateToMySQL(htmlspecialchars(strip_tags($_POST['closeDate'])), $hc_cfg[24]) : '';
	$rsvp_notice = (isset($_POST['rsvpEmail']) && is_numeric($_POST['rsvpEmail'])) ? htmlspecialchars(strip_tags($_POST['rsvpEmail'])) : 0;
	
	if($locID > 0){
		$locName = $locAddress = $locAddress2 = $locCity = $locState = $locZip = $locCountry = '';
	} else {
		$locName = htmlspecialchars(strip_tags(cleanQuotes($_POST['locName'])));
		$locAddress = htmlspecialchars(strip_tags($_POST['locAddress']));
		$locAddress2 = htmlspecialchars(strip_tags($_POST['locAddress2']));
		$locCity = htmlspecialchars(strip_tags($_POST['locCity']));
		$locState = htmlspecialchars(strip_tags($_POST['locState']));
		$locZip = htmlspecialchars(strip_tags($_POST['locZip']));
		$locCountry = htmlspecialchars(strip_tags($_POST['locCountry']));
	}

	$stop += ($subName != '') ? 0 : 1;
	$stop += (preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/',$subEmail) == 1) ? 0 : 1;
	$stop += ($eventTitle != '') ? 0 : 1;
	$stop += ($eventDesc != '') ? 0 : 1;
	$stop += ($locName != '' || $locID > 0) ? 0 : 1;
	if(!isset($_POST['overridetime']))
		$stop += (is_numeric($_POST['startTimeHour']) && is_numeric($_POST['startTimeMins'])) ? 0 : 1;
	if(!isset($_POST['overridetime']) && !isset($_POST['ignoreendtime']))
		$stop += (is_numeric($_POST['endTimeHour']) && is_numeric($_POST['endTimeMins'])) ? 0 : 1;
	
	$dateS = explode('/',$_POST['eventDate']);
	if(isset($dateS[2])){
		$eventDate = dateToMySQL(htmlspecialchars(strip_tags($_POST['eventDate'])), $hc_cfg[24]);
		$stop += ($eventDate != '') ? 0 : 1;
		$stop += ($eventDate >= date("Y-m-d")) ? 0 : 1;
	} else {
		$stop += 1;
	}
	
	if($stop == 0){
		if(!isset($_POST['overridetime'])){
			if($hc_cfg[31] == 12){
				$startTimeHour = ($_POST['startTimeAMPM'] == 'PM') ? ($_POST['startTimeHour'] < 12 ? $_POST['startTimeHour'] + 12 : $_POST['startTimeHour']) : ($_POST['startTimeHour'] == 12 ? 0 : $_POST['startTimeHour']);
				if(!isset($_POST['ignoreendtime'])){
					$endTimeHour = ($_POST['endTimeAMPM'] == 'PM') ? ($_POST['endTimeHour'] < 12 ? $_POST['endTimeHour'] + 12 : $_POST['endTimeHour']) : ($_POST['endTimeHour'] == 12 ? 0 : $_POST['endTimeHour']);
				}
			}

			$startTime = "'" . cIn($startTimeHour) . ":" . cIn($_POST['startTimeMins']) . ":00'";
			$endTime = (!isset($_POST['ignoreendtime'])) ? "'" . cIn($endTimeHour) . ":" . cIn($_POST['endTimeMins']) . ":00'" : 'NULL';
		} else {
			$startTime = $endTime = 'NULL';
			$tbd = ($_POST['specialtime'] == 'allday') ? 1 : 2;
		}

		if(isset($_POST['recurCheck'])){
			$seriesID = "'" . DecHex(microtime() * 9999999) . DecHex(microtime() * 5555555) . DecHex(microtime() * 1111111) . "'";
			$dateE = explode('-',$eventDate);
			$curDate = $eventDate;
			if(isset($dateE[2]))
				$stopDate = dateToMySQL(htmlspecialchars($_POST['recurEndDate']), $hc_cfg[24]);
			else
				$stopDate = date("Y-m-d");
			
			switch(htmlspecialchars($_POST['recurType'])){
				case 'daily':
					$days = isset($_POST['dailyDays']) ? cIn($_POST['dailyDays']) : 1;
					
					if(htmlspecialchars($_POST['dailyOptions']) == 'EveryXDays'){
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

						if(($curDateDayOfWeek == 6) AND ($weeks > 1))
							$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + ((($weeks - 1) * 7) + 1), $dateParts[0]));
						else
							$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $dateParts[2] + 1, $dateParts[0]));
					}
					break;
				case 'monthly':
					if($_POST['monthlyOption'] == 'Day'){
						$day = isset($_POST['monthlyDays']) ? cIn($_POST['monthlyDays']) : 1;
						$months = isset($_POST['monthlyMonths']) ? cIn($_POST['monthlyMonths']) : 1;
						
						while(strtotime($curDate) <= strtotime($stopDate)){
							$dates[] = $curDate;
							$dateParts = explode("-", $curDate);

							if($dateParts[2] < $day)
								$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1], $day, $dateParts[0]));
							else
								$curDate = date("Y-m-d", mktime(0, 0, 0, $dateParts[1] + $months, $day, $dateParts[0]));
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
									++$x;
									++$cnt;
								}
								$curDate = date("Y-m-d", mktime(0, 0, 0, $curMonth + $whichRepeat, (1 + $cnt) + ((7 * $whichDay) - 7), $curYear));
							} else {
								$x = date("w", mktime(0, 0, 0, $curMonth + $whichRepeat + 1, 0, $curYear));
								$offset = 0;
								if($x < $whichDOW){$x = $x + 7;}
								while((abs($x) % 7) != $whichDOW){
									--$x;
									++$cnt;
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

		$curSubmit = (isset($_SESSION['hc_curSubmit'])) ? $_SESSION['hc_curSubmit'] : 0;
		if($hc_cfg[40] > 0 && ((count($dates) + $curSubmit) >= $hc_cfg[40])){
			exit($hc_lang_submit['NoSubmit']);
		} else {
			$_SESSION['hc_curSubmit'] = $curSubmit + count($dates);
		}

		foreach($dates as $val){
			$eventDate = $val;
			$query = "INSERT INTO " . HC_TblPrefix . "events(Title, LocationName, LocationAddress, LocationAddress2,
						LocationCity, LocationState, LocationZip, Description,
						StartDate, StartTime, TBD, EndTime, ContactName,
						ContactEmail, ContactPhone, ContactURL, IsActive, IsApproved,
						IsBillboard, SubmittedByName, SubmittedByEmail, SubmittedAt, SeriesID,
						Message, LocID, Cost, LocCountry, OwnerID, PublishDate, LastMod)
					VALUES(	'" . cIn($eventTitle) . "', '" . cIn($locName) . "', '" . cIn($locAddress) . "', '" . cIn($locAddress2) . "',
							'" . cIn($locCity) . "', '" . cIn($locState) . "', '" . cIn($locZip) . "', '" . cIn($eventDesc,0) . "',
							'" . cIn($eventDate) . "', " . $startTime . ", '" . cIn($tbd) . "', " . $endTime . ",
							'" . cIn($contactName) . "', '" . cIn($contactEmail) . "', '" . cIn($contactPhone) . "', '" . cIn($contactURL) . "',
							'1', '" . $appStatus . "', '0', '" . cIn($subName) . "', '" . cIn($subEmail) . "', '".SYSDATE." ".SYSTIME."' , " . $seriesID . ",
							'" . $adminMessage . "','" . cIn($locID) . "', '" . cIn($cost) . "', '" . cIn($locCountry) . "', '" . cIn($subID) . "',
							" . $pubDate . ", " . $pubDate . ");";
			doQuery($query);
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "events");
			$newPkID = mysql_result($result,0,0);

			if(isset($_POST['catID']) && is_array($_POST['catID'])){
				foreach ($_POST['catID'] as $val){
					if(is_numeric($val) && $val > 0)
						doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($newPkID) . "', '" . cIn($val) . "')");
				}
			}
			if($rsvp_type == 1 && $newPkID > 0){
				doQuery("INSERT INTO " . HC_TblPrefix . "eventrsvps(Type,EventID,OpenDate,CloseDate,Space,RegOption,Notices)
						VALUES('".$rsvp_type."','".$newPkID."','".$rsvp_open."','".$rsvp_close."','".$rsvp_space."','".$rsvp_disp."','".$rsvp_notice."')");
			}
		}

		if($newPkID > 0 && $hc_cfg[78] != '' && $hc_cfg[79] != ''){			
			$eventDates = strftime($hc_cfg[24],strtotime($dates[0])) . (($dates[0] != end($dates)) ? ' - '.strftime($hc_cfg[24],strtotime(end($dates))) : '');
			$occurs = count($dates);
			
			notice_public_event($subName,$subEmail,$adminMessage,$locID,$locName,$locAddress,$locAddress2,$locCity,$locState,$locCountry,$locZip,$eventTitle,$eventDesc,$eventDates,$occurs);
		}
		
		header("Location: " . CalRoot . "/index.php?com=submit&msg=1");
	} else {
		exit($hc_lang_submit['ValidFail']);
	}
?>