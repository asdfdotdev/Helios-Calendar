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
	
	if($hc_cfg56 == 0){
		exit();
	}//end if
	
	$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
	spamIt($proof, 6);
	
	$comment = strip_tags($_POST['cmntText']);
	$eID = (isset($_POST['eID']) && is_numeric($_POST['eID'])) ? $_POST['eID'] : 0;
	$returnURL = CalRoot;
	
	if(isset($_SESSION[$hc_cfg00 . 'hc_OpenIDPkID']) && $eID > 0 && $comment != ''){
		doQuery("INSERT INTO " . HC_TblPrefix . "comments(Comment, PosterID, PostTime, TypeID, EntityID, Recomnds, IsActive)
					VALUES('" . cIn($comment) . "', '" . cIn($_SESSION[$hc_cfg00 . 'hc_OpenIDPkID']) . "', NOW(), 1, '" . cIn($eID) . "', 0, 1)");
		
		$returnURL .= '/index.php?com=detail&eID=' . $eID . '&msg=1#comments';
	}//end if
	
	header('Location: ' . $returnURL);?>