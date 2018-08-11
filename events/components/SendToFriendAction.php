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
	
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/config.php');
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/public/sendtofriend.php');

	$proof = $challenge = '';
	if($hc_cfg65 == 1){
		$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
		$challenge = isset($_SESSION['hc_cap']) ? $_SESSION['hc_cap'] : NULL;
	} elseif($hc_cfg65 == 2){
		$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
		$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
	}//end if
	spamIt($proof,$challenge,2);
		
	$myName = cIn(cleanBreaks($_POST['hc_fx1']));
	$myEmail = cIn(cleanBreaks($_POST['hc_fx2']));
	$friendName = cIn(cleanBreaks($_POST['hc_fx3']));
	$friendEmail = cIn(cleanBreaks($_POST['hc_fx4']));
	$sendMsg = cleanBreaks(nl2br(strip_tags($_POST['hc_fx5'])));
	$eID = (isset($_POST['eID']) && is_numeric($_POST['eID'])) ? cIn($_POST['eID']) : 0;

	$result = doQuery("SELECT Title, StartDate, StartTime, TBD FROM " . HC_TblPrefix . "events WHERE PkID = '" . $eID . "'");
	
	if(hasRows($result) && $myName != '' && $myEmail != '' && $friendName != '' && $friendEmail != ''){
		$subject = CalName . " " . $hc_lang_sendtofriend['Subject']  . " " . $myName;
		$message = '<p>' . cOut($sendMsg) . '</p>';
		$message .= '<p><b>' . mysql_result($result,0,0) . '</b><br />' . stampToDate(mysql_result($result,0,1), $hc_cfg14) . ' - ';

		if(mysql_result($result,0,3) == 0){
			$message .= stampToDate("1980-01-01 " . mysql_result($result,0,2), $hc_cfg23);
		} elseif(mysql_result($result,0,3) == 1){
			$message .= $hc_lang_sendtofriend['AllDay'];
		} elseif(mysql_result($result,0,3) == 2){
			$message .= $hc_lang_sendtofriend['TBA'];
		}//end if
		
		$message .= '<br /><a href="' . CalRoot . '/index.php?eID=' . $eID . '">' . CalRoot . '/index.php?eID=' . $eID . '</a></p>';
		$message .= '<p>' . $hc_lang_sendtofriend['From'] . '<br />' . $myName . ' (' . $myEmail . ')</p>';
		$message .= '<p>' . $hc_lang_sendtofriend['AutoNotice'] . ' ' . $hc_cfg78;
		reMail($friendName,$friendEmail,$subject,$message,$myName,$myEmail);
		
		doQuery("INSERT INTO " . HC_TblPrefix . "sendtofriend(MyName, MyEmail, RecipientName, RecipientEmail, Message, EventID, IPAddress, SendDate)
				 Values('" . $myName . "', '" . $myEmail . "', '" . $friendName . "', '" . $friendEmail . "', '" . cleanSpecialChars(str_replace('<br>','\n',$message)) . "', " . $eID . ", '" . $_SERVER["REMOTE_ADDR"] . "', '" . date("Y-m-d") . "')");
		doQuery("UPDATE " . HC_TblPrefix . "events SET EmailToFriend = EmailToFriend + 1 WHERE PkID = '" . $eID . "'");
		
		header("Location: " . CalRoot . "/index.php?com=send&eID=" . $eID . "&msg=1");
	} else {
		header("Location: " . CalRoot . "/");
	}//end if
?>