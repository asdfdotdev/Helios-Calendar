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
	
	if($hc_cfg56 == 0){
		exit();
	}//end if

	$proof = $challenge = '';
	if($hc_cfg65 == 1){
		$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
		$challenge = isset($_SESSION['hc_cap']) ? $_SESSION['hc_cap'] : NULL;
	} elseif($hc_cfg65 == 2){
		$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
		$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
	}//end if
	spamIt($proof,$challenge,6);
	
	$comment = strip_tags($_POST['cmntText']);
	$eID = (isset($_POST['eID']) && is_numeric($_POST['eID'])) ? $_POST['eID'] : 0;
	$returnURL = CalRoot;
	
	$_SESSION['hc_LastComment'] = (!isset($_SESSION['hc_LastComment'])) ? (date("U") - $hc_cfg25) : $_SESSION['hc_LastComment'];

	if(isset($_SESSION['hc_OpenIDPkID']) && $eID > 0 && $comment != ''){
		if(($_SESSION['hc_LastComment'] + $hc_cfg25) > date("U")){
			$returnURL .= '/index.php?com=detail&eID=' . $eID . '&msg=7#cmnts';
		} else {
			doQuery("INSERT INTO " . HC_TblPrefix . "comments(Comment, PosterID, PostTime, TypeID, EntityID, Recomnds, IsActive)
					VALUES('" . cIn($comment) . "', '" . cIn($_SESSION['hc_OpenIDPkID']) . "', NOW(), 1, '" . cIn($eID) . "', 0, 1)");

			$returnURL .= '/index.php?com=detail&eID=' . $eID . '&msg=1#cmnts';
			$_SESSION['hc_LastComment'] = date("U");
		}//end if
	}//end if

	$hourOffset = date("G") + ($hc_cfg35);
	$curCache = date("Ymd", mktime($hourOffset,0,0,date("m"),date("d"),date("Y")));
	if(file_exists(realpath('../cache/rss' . $curCache . '_4.php'))){
		unlink(realpath('../cache/rss' . $curCache . '_4.php'));
	}//end if

	header('Location: ' . $returnURL);?>