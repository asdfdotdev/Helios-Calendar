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
	
	include(HCLANG . '/public/rsvp.php');

	$proof = $challenge = '';
	if($hc_cfg[65] == 1){
		$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
		$challenge = isset($_SESSION['hc_cap']) ? $_SESSION['hc_cap'] : NULL;
	} elseif($hc_cfg[65] == 2){
		$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
		$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
	}
	spamIt($proof,$challenge,3);
	
	$eID = (isset($_POST['eID']) && is_numeric($_POST['eID'])) ? cIn(strip_tags($_POST['eID'])) : 0;
	$regName = isset($_POST['hc_f1']) ? cIn(strip_tags(cleanBreaks($_POST['hc_f1']))) : '';
	$regEmail = isset($_POST['hc_f2']) ? cIn(strip_tags(cleanBreaks($_POST['hc_f2']))) : '';
	$phone = isset($_POST['hc_f3']) ? cIn(strip_tags($_POST['hc_f3'])) : '';
	$address = isset($_POST['hc_f4']) ? cIn(strip_tags($_POST['hc_f4'])) : '';
	$address2 = isset($_POST['hc_f5']) ? cIn(strip_tags($_POST['hc_f5'])) : '';
	$city = isset($_POST['hc_f6']) ? cIn(strip_tags($_POST['hc_f6'])) : '';
	$state = isset($_POST['locState']) ? cIn(strip_tags($_POST['locState'])) : '';
	$country = isset($_POST['hc_f9']) ? cIn(strip_tags($_POST['hc_f9'])) : '';
	$zip = isset($_POST['hc_f8']) ? cIn(strip_tags($_POST['hc_f8'])) : '';
	$partySize = (is_numeric($_POST['hc_f7'])) ? cIn(strip_tags($_POST['hc_f7'])) + 1 : 0;

	$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "registrants WHERE Email = '" . $regEmail . "' AND EventID = '" . $eID . "'");
	if(hasRows($result)){
		header("Location: " . CalRoot . "/index.php?com=rsvp&eID=".$eID."&msg=1");
	} else {
		$result = doQuery("SELECT Title, StartDate, StartTime, TBD, ContactEmail FROM " . HC_TblPrefix . "events WHERE PkID = '" . $eID . "'");
		
		$eventTitle = cOut(mysql_result($result,0,0));
		$eventDate = stampToDate(mysql_result($result,0,1), $hc_cfg[14]);
		$conEmail = mysql_result($result,0,4);
		$groupID = ($partySize > 1) ? md5($regName . $eventTitle . date("U")) : '';
		
		$eMsg = '<p><b>' . mysql_result($result,0,0) . '</b><br />' . stampToDate(mysql_result($result,0,1), $hc_cfg[14]) . ' - ';
		if(mysql_result($result,0,3) == 0)
			$eMsg .= stampToDate("1980-01-01 " . mysql_result($result,0,2), $hc_cfg[23]);
		elseif(mysql_result($result,0,3) == 1)
			$eMsg .= $hc_lang_rsvp['AllDay'];
		elseif(mysql_result($result,0,3) == 2)
			$eMsg .= $hc_lang_rsvp['TBA'];
		
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
		}
		
		$result = doQuery("SELECT COUNT(r.EventID), er.Space
							FROM " . HC_TblPrefix . "registrants r
								LEFT JOIN " . HC_TblPrefix . "eventrsvps er ON (r.EventID = er.EventID)
							WHERE r.EventID = '" . $eID . "' and r.IsActive = 1
							GROUP BY r.EventID, er.Space");
		$eOver = $eLimit = 0;
		if(mysql_result($result,0,0) > mysql_result($result,0,1) && mysql_result($result,0,1) != 0)
  			$eOver = 1;
		elseif(mysql_result($result,0,0) == mysql_result($result,0,1) && mysql_result($result,0,1) != 0)
			$eLimit = 1;
		
		$rMsg = '<p><b>' . cOut($hc_lang_rsvp['PartySize']) . " " . cOut($partySize) . '</b>';
		$rMsg .= '<br />' . cOut($regName) . '<br />' . cOut($regEmail);
		$rMsg .= ($phone != '') ? '<br />' . $phone : '';
		$rMsg .= ($address != '') ?  '<br />' . strip_tags(buildAddress($address,$address2,$city,$state,$zip,$country,$hc_lang_config['AddressType']),'<br>') : '';
		$rMsg .= '</p>';
		
		//	RSVP User Email
		$regSubj = cOut($hc_lang_rsvp['regSubject']) . $eventTitle;
		$regMsg = '<p>' . cOut($hc_lang_rsvp['regMsg']) . '</p>';
		$regMsg .= $eMsg . $rMsg;
		$regMsg .= ($eOver == 1) ? " " . cOut($hc_lang_rsvp['regOverflow']) : '';
		$regMsg .= '<p>' . cOut($hc_lang_rsvp['ThankYou']) . '<br />' . $hc_cfg[79] . '</p>';
  		$regMsg .= '<p>' . cOut($hc_lang_rsvp['regDisclaimer']) . '</p>';

		//	Event Contact Email
		$conSubj = cOut($hc_lang_rsvp['conSubject']) . $eventTitle;
		$conMsg = '<p>' . cOut($hc_lang_rsvp['conMsg']) . '</p>';
		$conMsg .= $eMsg;
		$conMsg .= ($eOver == 1) ? '<p>' . cOut($hc_lang_rsvp['conOverflow']) . '</p>' : '';
		$conMsg .= ($eLimit == 1) ? '<p>' . cOut($hc_lang_rsvp['conLimit']) . '</p>' : '';
		$conMsg .= $rMsg;
		$conMsg .= '<p>' . cOut($hc_lang_rsvp['ThankYou']) . '<br />' . $hc_cfg[79] . '</p>';
		$conMsg .= '<p>' . cOut($hc_lang_rsvp['conDisclaimer']) . '</p>';
		
		reMail($regName,$regEmail,$regSubj,$regMsg,$hc_cfg[79],$hc_cfg[78]);
		reMail('',$conEmail,$conSubj,$conMsg,$hc_cfg[79],$hc_cfg[78]);

		header("Location: " . CalRoot . "/index.php?com=rsvp&eID=".$eID."&msg=2");
	}
?>