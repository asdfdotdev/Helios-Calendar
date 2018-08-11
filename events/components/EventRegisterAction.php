<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	$isAction = 1;
	include('../includes/include.php');
	checkIt();

	$proof = $challenge = '';
	if($hc_cfg65 == 1){
		$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
		$challenge = isset($_SESSION['hc_cap']) ? $_SESSION['hc_cap'] : NULL;
	} elseif($hc_cfg65 == 2){
		$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
		$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
	}//end if
	spamIt($proof,$challenge,3);
	
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/config.php');
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/public/register.php');

	$eID = (isset($_POST['eventID']) && is_numeric($_POST['eventID'])) ? $_POST['eventID'] : 0;
	$regName = cIn(cleanBreaks($_POST['hc_f1']));
	$regEmail = cIn(cleanBreaks($_POST['hc_f2']));
	$phone = cIn($_POST['hc_f3']);
	$address = cIn($_POST['hc_f4']);
	$address2 = cIn($_POST['hc_f5']);
	$city = cIn($_POST['hc_f6']);
	$state = cIn($_POST['locState']);
	$country = cIn($_POST['hc_f9']);
	$zip = cIn($_POST['hc_f8']);
	$partySize = (is_numeric($_POST['hc_f7'])) ? $_POST['hc_f7'] + 1 : 0;

	$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "registrants WHERE Email = '" . cIn($regEmail) . "' AND EventID = '" . cIn($eID) . "'");
	if(hasRows($result)){
		header("Location: " . CalRoot . "/index.php?com=register&eID=" . $eID . "&msg=1");
	} else {
		$result = doQuery("SELECT Title, StartDate, StartTime, TBD, ContactEmail FROM " . HC_TblPrefix . "events WHERE PkID = '" . cIn($eID) . "'");
		
		$eventTitle = mysql_result($result,0,0);
		$eventDate = stampToDate(mysql_result($result,0,1), $hc_cfg14);
		$conEmail = mysql_result($result,0,4);
		$groupID = ($partySize > 1) ? md5($regName . $eventTitle . date("U")) : '';
		
		$eMsg = '<p><b>' . mysql_result($result,0,0) . '</b><br />' . stampToDate(mysql_result($result,0,1), $hc_cfg14) . ' - ';
		if(mysql_result($result,0,3) == 0){
			$eMsg .= stampToDate("1980-01-01 " . mysql_result($result,0,2), $hc_cfg23);
		} elseif(mysql_result($result,0,3) == 1){
			$eMsg .= $hc_lang_register['AllDay'];
		} elseif(mysql_result($result,0,3) == 2){
			$eMsg .= $hc_lang_register['TBA'];
		}//end if
		$eMsg .= '<br /><a href="' . CalRoot . '/index.php?eID=' . $eID . '">' . CalRoot . '/index.php?eID=' . $eID . '</a></p>';
		
		for($x=1;$x<=$partySize;$x++){
			$addName = ($partySize > 1) ? $regName . " - " . $x . "/" . $partySize : $regName;
			doQuery("INSERT into " . HC_TblPrefix . "registrants(Name, Email, Phone, Address, Address2, City, State, Zip, EventID, IsActive, RegisteredAt, GroupID)
					Values(	'" . cIn($addName) . "',
							'" . $regEmail . "',
							'" . $phone . "',
							'" . $address . "','" . $address2 . "','" . $city . "','" . $state . "','" . $zip . "',
							'" . $eID . "',
							1, NOW(),
							'" . cIn($groupID) . "');");
		}//end for
		
		$result = doQuery("SELECT COUNT(r.EventID), e.SpacesAvailable
							FROM " . HC_TblPrefix . "registrants r
								LEFT JOIN " . HC_TblPrefix . "events e ON (r.EventID = e.PkID)
							WHERE r.EventID = " . $eID . "
							GROUP BY EventID");
		$eOver = $eLimit = 0;
		if(mysql_result($result,0,0) > mysql_result($result,0,1) && mysql_result($result,0,1) != 0){
  			$eOver = 1;
		} elseif(mysql_result($result,0,0) == mysql_result($result,0,1) && mysql_result($result,0,1) != 0){
			$eLimit = 1;
		}//end if
		
		$rMsg = '<p><b>' . $hc_lang_register['PartySize'] . " " . $partySize . '</b>';
		$rMsg .= '<br />' . $regName . '<br />' . $regEmail;
		$rMsg .= ($phone != '') ? '<br />' . $phone : '';
		$rMsg .= ($address != '') ?  '<br />' . strip_tags(buildAddress($address,$address2,$city,$state,$zip,$country,$hc_lang_config['AddressType']),'<br>') : '';
		$rMsg .= '</p>';
		
		//	RSVP User Email
		$regSubj = $hc_lang_register['regSubject'] . $eventTitle;
		$regMsg = '<p>' . $hc_lang_register['regMsg'] . '</p>';
		$regMsg .= $eMsg . $rMsg;
		$regMsg .= ($eOver == 1) ? " " . $hc_lang_register['regOverflow'] : '';
		$regMsg .= '<p>' . $hc_lang_register['ThankYou'] . '<br />' . $hc_cfg79 . '</p>';
  		$regMsg .= '<p>' . $hc_lang_register['regDisclaimer'] . '</p>';

		//	Event Contact Email
		$conSubj = $hc_lang_register['conSubject'] . $eventTitle;
		$conMsg = '<p>' . $hc_lang_register['conMsg'] . '</p>';
		$conMsg .= $eMsg;
		$conMsg .= ($eOver == 1) ? '<p>' . $hc_lang_register['conOverflow'] . '</p>' : '';
		$conMsg .= ($eLimit == 1) ? '<p>' . $hc_lang_register['conLimit'] . '</p>' : '';
		$conMsg .= $rMsg;
		$conMsg .= '<p>' . $hc_lang_register['ThankYou'] . '<br />' . $hc_cfg79 . '</p>';
		$conMsg .= '<p>' . $hc_lang_register['conDisclaimer'] . '</p>';
		
		reMail($regName,$regEmail,$regSubj,$regMsg,$hc_cfg79,$hc_cfg78);
		reMail('',$conEmail,$conSubj,$conMsg,$hc_cfg79,$hc_cfg78);

		header("Location: " . CalRoot . "/index.php?com=register&eID=" . $eID . "&msg=2");
	}//end if?>