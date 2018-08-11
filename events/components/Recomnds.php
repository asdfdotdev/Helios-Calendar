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
	
	$cID = (isset($_GET['cID']) && is_numeric($_GET['cID'])) ? $_GET['cID'] : 0;
	$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? $_GET['eID'] : 0;
	$tID = (isset($_GET['tID']) && is_numeric($_GET['tID'])) ? $_GET['tID'] : 0;
	$uID = (isset($_SESSION[$hc_cfg00 . 'hc_OpenIDPkID']) && is_numeric($_SESSION[$hc_cfg00 . 'hc_OpenIDPkID'])) ? $_SESSION[$hc_cfg00 . 'hc_OpenIDPkID'] : 0;
	$score = (isset($_GET['s'])) ? $_GET['s'] : 0;
	$returnURL = CalRoot;
	
	switch($score){
		case 'u':
			$score = '1';
			break;
		case 'd':
			$score = '-1';
			break;
		default:
			$sore = 0;
			break;
	}//end switch
	
	if($eID > 0 && $cID > 0 && $tID > 0 && $score != 0){	
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "recomndslog WHERE CommentID = '" . cIn($cID) . "' AND OIDUser = '" . cIn($uID) . "'");
		if(hasRows($result)){
			$returnURL .= '/index.php?com=detail&eID=' . $eID . '&msg=3#cmnts';
		} else {
			if(!isset($_SESSION[$hc_cfg00 . 'hc_OpenIDPkID'])){
				$returnURL .= '/index.php?com=detail&eID=' . $eID . '&msg=4#cmnts';
			} else if($tID == 1){
				doQuery("INSERT INTO " . HC_TblPrefix . "recomndslog(CommentID, OIDUser, Score)
							VALUES('" . cIn($cID) . "', '" . cIn($_SESSION[$hc_cfg00 . 'hc_OpenIDPkID']) . "', '" . cIn($score) . "')");
				doQuery("UPDATE " . HC_TblPrefix . "comments c 
							SET c.Recomnds = (SELECT SUM(rl.Score) FROM " . HC_TblPrefix . "recomndslog rl WHERE rl.CommentID = '" . cIn($cID) . "')
							WHERE c.PkID = '" . cIn($cID) . "'");
				$returnURL .= '/index.php?com=detail&eID=' . $eID . '&msg=2#cmnts';
			}//end if
		}//end if
	}//end if
	
	header('Location: ' . $returnURL);?>