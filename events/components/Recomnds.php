<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
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
	}//end switch
	
	if($eID > 0 && $cID > 0 && $tID > 0 && $score != 0){	
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "recomndslog WHERE CommentID = '" . cIn($cID) . "' AND OIDUser = '" . cIn($uID) . "'");
		if(hasRows($result)){
			$returnURL .= '/index.php?com=detail&eID=' . $eID . '&msg=3#comments';
		} else {
			if(!isset($_SESSION[$hc_cfg00 . 'hc_OpenIDPkID'])){
				$returnURL .= '/index.php?com=detail&eID=' . $eID . '&msg=4#comments';
			} else if($tID == 1){
				doQuery("INSERT INTO " . HC_TblPrefix . "recomndslog(CommentID, OIDUser, Score)
							VALUES('" . cIn($cID) . "', '" . cIn($_SESSION[$hc_cfg00 . 'hc_OpenIDPkID']) . "', '" . cIn($score) . "')");
				doQuery("UPDATE " . HC_TblPrefix . "comments c 
							SET c.Recomnds = (SELECT SUM(rl.Score) FROM " . HC_TblPrefix . "recomndslog rl WHERE rl.CommentID = '" . cIn($cID) . "')
							WHERE c.PkID = '" . cIn($cID) . "'");
				$returnURL .= '/index.php?com=detail&eID=' . $eID . '&msg=2#comments';
			}//end if
		}//end if
	}//end if
	
	header('Location: ' . $returnURL);?>