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
	
	if($hc_cfg[1] == 0 || !user_check_status()){
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
	
	$dates = array();
	$eventDate = isset($_POST['eventDate']) ? dateToMySQL(cIn($_POST['eventDate']), $hc_cfg[24]) : '';
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
	$eID = isset($_POST['eID']) ? cIn(htmlspecialchars(strip_tags($_POST['eID']))) : 0;
	
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

		$query = "UPDATE " . HC_TblPrefix . "events
					SET Title = '" . cIn($eventTitle) . "',
					LocationName = '" . cIn($locName) . "',
					LocationAddress = '" . cIn($locAddress) . "',
					LocationAddress2 = '" . cIn($locAddress2) . "',
					LocationCity = '" . cIn($locCity) . "',
					LocationState = '" . cIn($locState) . "',
					LocationZip = '" . cIn($locZip) . "',
					Description = '" . cIn($eventDesc,0) . "',
					StartTime = " . $startTime . ",
					TBD = '" . cIn($tbd) . "',
					EndTime = " . $endTime . ",
					ContactName = '" . cIn($contactName) . "',
					ContactEmail = '" . cIn($contactEmail) . "',
					ContactPhone = '" . cIn($contactPhone) . "',
					IsApproved = '" . cIn($appStatus) . "',
					ContactURL = '" . cIn($contactURL) . "',
					LocID = '" . cIn($locID) . "',
					Cost = '" . cIn($cost) . "',
					LocCountry = '" . cIn($locCountry) . "',
					LastMod = ".$pubDate.",
					Message = '" . cIn($adminMessage) . "'";
		
		if(!isset($_POST['editString']) || $_POST['editString'] == ''){
			$msgID = 1;
			$query .= ", StartDate = '" . cIn($eventDate) . "' WHERE PkID = '" . $eID . "' ";
			$eventIDs = array($eID);
			$hdrStr = AdminRoot . "/index.php?com=eventedit&eID=" . $eID  . "&msg=" . $msgID;
		} else {
			$msgID = 2;
			$query = $query . " WHERE PkID IN (" . cIn(strip_tags($_POST['editString'])) . ")";
			$eventIDs = array_filter(explode(',',$_POST['editString']),'is_numeric');
			$eID = cIn($eventIDs[0]);
		}
		
		doQuery($query);
		$stop = count($eventIDs);
		$i = 0;
		while($i < $stop){
			$doID = cIn($eventIDs[$i]);
			
			if(isset($_POST['catID']) && is_array($_POST['catID'])){
				doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = '".$doID."'");
				
				foreach ($_POST['catID'] as $val){
					if(is_numeric($val) && $val > 0)
						doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('".$doID."', '".cIn($val)."')");
				}
			}
			if($rsvp_type == 1 && is_numeric($doID) && $doID > 0){
				doQuery("DELETE FROM " . HC_TblPrefix . "eventrsvps WHERE EventID = '".$doID."'");
				doQuery("INSERT INTO " . HC_TblPrefix . "eventrsvps(Type,EventID,OpenDate,CloseDate,Space,RegOption,Notices)
						VALUES('".$rsvp_type."','".$doID."','".$rsvp_open."','".$rsvp_close."','".$rsvp_space."','".$rsvp_disp."','".$rsvp_notice."')");
			}
			++$i;
		}

		if($eID > 0 && $hc_cfg[78] != '' && $hc_cfg[79] != ''){
			notice_public_event($subName,$subEmail,$adminMessage,$locID,$locName,$locAddress,$locAddress2,$locCity,$locState,$locCountry,$locZip,$eventTitle,$eventDesc,'',0);
		}
		
		header("Location: " . CalRoot . "/index.php?com=submit&eID=".$eID."&msg=1");
	} else {
		exit($hc_lang_submit['ValidFail']);
	}
?>