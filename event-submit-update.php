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
	define('isHC',true);
	define('isAction',true);
	include(dirname(__FILE__).'/loader.php');
	
	action_headers();
	post_only();
	
	if($hc_cfg[1] == 0 || !user_check_status()){
		exit();}
		
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
	
	$appStatus = (user_check_status() && $_SESSION['UserLevel'] == 2) ? 1 : 2;		//1 = Auto Approve, 2 = Submit to Pending Events Queue (Default)
	$pubDate = ($appStatus == 1) ? "'".SYSDATE.' '.SYSTIME."'" : 'NULL';
	$filter = array('/onclick=["\'][^"\']+["\']/i','/ondblclick=["\'][^"\']+["\']/i','/onkeydown=["\'][^"\']+["\']/i','/onkeypress=["\'][^"\']+["\']/i','/onkeyup=["\'][^"\']+["\']/i','/onmousedown=["\'][^"\']+["\']/i','/onmousemove=["\'][^"\']+["\']/i','/onmouseout=["\'][^"\']+["\']/i','/onmouseover=["\'][^"\']+["\']/i','/onmouseup=["\'][^"\']+["\']/i','/onmousemove=["\'][^"\']+["\']/i','/onfocus=["\'][^"\']+["\']/i','/onblur=["\'][^"\']+["\']/i');
	$eID = $tbd = $stop = 0;
	$subName = htmlspecialchars(strip_tags($_POST['submitName']));
	$subEmail = htmlspecialchars(strip_tags($_POST['submitEmail']));
	$subID = htmlspecialchars(strip_tags($_POST['submitID']));
	$eventTitle = htmlspecialchars(cleanQuotes(strip_tags($_POST['eventTitle'])));
	$eventDesc = cleanQuotes(strip_tags($_POST['eventDescription'],'<abbr><acronym><blockquote><br><caption><center><cite><dd><del><dfn><dir><div><dl><dt><em><i><font><hr><img><legend><li><menu><ol><p><pre><listing><plaintext><q><small><span><strike><strong><b><style><sub><sup><table><td><tr><tt><u><ul><var>'),0);
	$eventDesc = preg_replace($filter,'',$eventDesc);
	$eventDate = isset($_POST['eventDate']) ? dateToMySQL(cIn($_POST['eventDate']), $hc_cfg[24]) : '';
	$locID = htmlspecialchars(strip_tags($_POST['locPreset']));
	$contactName = htmlspecialchars(strip_tags($_POST['contactName']));
	$contactEmail = htmlspecialchars(strip_tags($_POST['contactEmail']));
	$contactPhone = htmlspecialchars(strip_tags($_POST['contactPhone']));
	$contactURL = (isset($_POST['contactURL'])) ? cIn(htmlspecialchars(strip_tags($_POST['contactURL']))) : '';
	$contactURL = (preg_match('/^https?:\/\//',$contactURL) || $contactURL == '') ? $contactURL : 'http://'.$contactURL;
	$cost = htmlspecialchars(strip_tags($_POST['cost']));
	$startTimeHour = isset($_POST['startTimeHour']) ? strip_tags($_POST['startTimeHour']) : NULL;
	$endTimeHour = isset($_POST['endTimeHour']) ? strip_tags($_POST['endTimeHour']) : NULL;
	$dates = array();
	$catID = (isset($_POST['catID'])) ? $_POST['catID'] : '';
	$newPkID = 0;
	$allowRegistration = ($hc_cfg['IsRSVP'] == 1) ? htmlspecialchars(strip_tags($_POST['eventRegistration'])) : '0';
	$adminMessage = (isset($_POST['adminmessage'])) ? cIn(htmlspecialchars(cleanQuotes(strip_tags($_POST['adminmessage'])))) : '';
	$maxRegistration = ($allowRegistration == 1) ? $_POST['eventRegAvailable'] : 0;
	$eID = isset($_POST['eID']) ? htmlspecialchars(strip_tags($_POST['eID'])) : 0;
	
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
					AllowRegister = " . cIn($allowRegistration) . ",
					SpacesAvailable = " . cIn($maxRegistration) . ",
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
			$eventIDs = explode(",", $_POST['editString']);
			$eID = $eventIDs[0];
		}
		
		doQuery($query);
		$stop = count($eventIDs);
		$catID = $_POST['catID'];
		$i = 0;
		while($i < $stop){
			doQuery("DELETE FROM " . HC_TblPrefix . "eventcategories WHERE EventID = " . cIn($eventIDs[$i]));
			foreach($catID as $val)
				doQuery("INSERT INTO " . HC_TblPrefix . "eventcategories(EventID, CategoryID) VALUES('" . cIn($eventIDs[$i]) . "', '" . cIn($val) . "')");
			
			++$i;
		}

		if($eID > 0 && $hc_cfg[78] != '' && $hc_cfg[79] != ''){
			$resultE = doQuery("SELECT a.FirstName, a.LastName, a.Email
							FROM " . HC_TblPrefix . "adminnotices n
								LEFT JOIN " . HC_TblPrefix . "admin a ON (n.AdminID = a.PkID)
							WHERE a.IsActive = 1 AND n.IsActive = 1 AND n.TypeID = 0");
			if(hasRows($resultE)){
				$toNotice = array();
				while($row = mysql_fetch_row($resultE)){
					$toNotice[trim($row[0] . ' ' .$row[1])] = $row[2];
				}

				$user_level = (isset($_SESSION['UserLevel'])) ? cIn($_SESSION['UserLevel']) : 0;
				$subject = $hc_lang_submit['NoticeSubject'] . ' - ' . CalName;
				$message = '<p>' . $hc_lang_submit['NoticeEmail1'] . '</p>';
				$message .= '<p><b>' . $hc_lang_submit['NoticeEmail2'] . '</b> ' . $subName . ' - ' . $subEmail . '<br />';
				$message .= '<b>' . $hc_lang_submit['NoticeEmail5'] . '</b> '.$hc_lang_submit['NoticeEmail5'.$user_level] . '<br />';
				$message .= '<b>' . $hc_lang_submit['NoticeEmail3'] . '</b> ' . strip_tags($_SERVER['REMOTE_ADDR']) . '</p>';
				$message .= ($adminMessage != '') ? '<p><b>' . $hc_lang_submit['NoticeEmail4'] . '</b> ' . cOut(str_replace('<br />', ' ', strip_tags(cleanBreaks($_POST['adminmessage']),'<br>'))) . '</p>' : '';

				$message .= '<p>';
				if($locID == 0){
					$message .= $locName . ', ';
					$message .= str_replace('<br />', ' ', strip_tags(buildAddress($locAddress,$locAddress2,$locCity,$locState,$locZip,$locCountry,$hc_lang_config['AddressType']),'<br>'));
				} else {
					$result = doQuery("SELECT Name, Address, Address2, City, State, Country, Zip FROM " . HC_TblPrefix . "locations WHERE PkID = '" . cIn($locID) . "'");
					$message .= mysql_result($result,0,0) . ', ';
					$message .= str_replace('<br />', ' ', strip_tags(buildAddress(mysql_result($result,0,1),mysql_result($result,0,2),mysql_result($result,0,3),mysql_result($result,0,4),mysql_result($result,0,5),mysql_result($result,0,6),$hc_lang_config['AddressType']),'<br>'));
				}
				$message .= '</p>';

				$message .= '<p><b>' . $hc_lang_submit['EventTitle'] . '</b> ' . cOut($eventTitle) . '</p><p>' . cOut(strip_tags($eventDesc)) . '</p>';
				$message .= '<p><a href="' . AdminRoot . '">' . AdminRoot . '</a></p>';
				
				reMail('', $toNotice, $subject, $message);
			}
		}
		
		header("Location: " . CalRoot . "/index.php?com=submit&eID=".$eventIDs[0]."&msg=1");
	} else {
		exit($hc_lang_submit['ValidFail']);
	}
?>