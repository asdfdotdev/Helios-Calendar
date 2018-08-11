<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
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
		$challenge = isset($_SESSION[$hc_cfg00 . 'hc_cap']) ? $_SESSION[$hc_cfg00 . 'hc_cap'] : NULL;
	} elseif($hc_cfg65 == 2){
		$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
		$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
	}//end if
	spamIt($proof,$challenge,6);
	
	$comment = strip_tags($_POST['cmntText']);
	$eID = (isset($_POST['eID']) && is_numeric($_POST['eID'])) ? $_POST['eID'] : 0;
	$returnURL = CalRoot;
	
	$_SESSION[$hc_cfg00 . 'hc_LastComment'] = (!isset($_SESSION[$hc_cfg00 . 'hc_LastComment'])) ? (date("U") - $hc_cfg25) : $_SESSION[$hc_cfg00 . 'hc_LastComment'];

	if(isset($_SESSION[$hc_cfg00 . 'hc_OpenIDPkID']) && $eID > 0 && $comment != ''){
		if(($_SESSION[$hc_cfg00 . 'hc_LastComment'] + $hc_cfg25) > date("U")){
			$returnURL .= '/index.php?com=detail&eID=' . $eID . '&msg=7#cmnts';
		} else {
			doQuery("INSERT INTO " . HC_TblPrefix . "comments(Comment, PosterID, PostTime, TypeID, EntityID, Recomnds, IsActive)
					VALUES('" . cIn($comment) . "', '" . cIn($_SESSION[$hc_cfg00 . 'hc_OpenIDPkID']) . "', NOW(), 1, '" . cIn($eID) . "', 0, 1)");

			$returnURL .= '/index.php?com=detail&eID=' . $eID . '&msg=1#cmnts';
			$_SESSION[$hc_cfg00 . 'hc_LastComment'] = date("U");
		}//end if
	}//end if

	header('Location: ' . $returnURL);?>