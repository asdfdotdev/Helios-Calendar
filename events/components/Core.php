<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	if(!file_exists(realpath('cache/meta.php'))){
		rebuildCache(3);
	}//end if
	include('cache/meta.php');

	$com = "components/EventList.php";
	$hc_pTitle = $hc_pt1;
	$hc_mDesc = $hc_md1;
	$hc_mKeys = $hc_mk1;

	if(isset($_GET['d'])){
		$passDateParts = explode("-",$_GET['d']);
		$curYear = (isset($passDateParts[0]) && is_numeric($passDateParts[0])) ? $passDateParts[0] : NULL;
		$curMonth = (isset($passDateParts[1]) && is_numeric($passDateParts[1])) ? $passDateParts[1] : NULL;
	}//end if
	
	if(isset($_GET['com'])){
		switch ($_GET['com']){
			case 'detail':
				$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? $_GET['eID'] : 0;
				$resultED = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND PkID = " . cIn($eID));
				if(hasRows($resultED)){
					$hc_pTitle = html_entity_decode(cOut(mysql_result($resultED,0,1)));
					$hc_mDesc = substr(strip_tags(cOut(mysql_result($resultED,0,8))),0,160);
					$curYear = stampToDate(mysql_result($resultED,0,9),"%Y");
					$curMonth = stampToDate(mysql_result($resultED,0,9),"%m");
				}//end if
				$com = "components/EventDetail.php";
				break;
			case 'location':
				if($hc_cfg45 == 1){
					$hc_pTitle = $hc_pt2;
					$hc_mDesc = $hc_md2;
					$hc_mKeys = $hc_mk2;
					$com = "components/Location.php";
				}//end if
				break;
			case 'submit':
				if($hc_cfg1 == 1){
					$hc_pTitle = $hc_pt3;
					$hc_mDesc = $hc_md3;
					$hc_mKeys = $hc_mk3;
					$com = "components/EventSubmit.php";
				}//end if
				break;
			case 'search':
				$hc_pTitle = $hc_pt4;
				$hc_mDesc = $hc_md4;
				$hc_mKeys = $hc_mk4;
				$com = "components/Search.php";
				break;
			case 'searchresult':
				$hc_pTitle = $hc_pt5;
				$hc_mDesc = $hc_md5;
				$hc_mKeys = $hc_mk5;
				$com = "components/SearchResults.php";
				break;
			case 'signup':
				$hc_pTitle = $hc_pt6;
				$hc_mDesc = $hc_md6;
				$hc_mKeys = $hc_mk6;
				$com = "components/SignUp.php";
				break;
			case 'send':
				$hc_pTitle = $hc_pt7;
				$hc_mDesc = $hc_md7;
				$hc_mKeys = $hc_mk7;
				$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? $_GET['eID'] : 0;
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND PkID = " . cIn($eID));
				if(hasRows($result)){
					$curYear = stampToDate(mysql_result($result,0,9),"%Y");
					$curMonth = stampToDate(mysql_result($result,0,9),"%m");
				}//end if
				$com = "components/SendToFriend.php";
				break;
			case 'register':
				$hc_pTitle = $hc_pt8;
				$hc_mDesc = $hc_md8;
				$hc_mKeys = $hc_mk8;
				$com = "components/EventRegister.php";
				break;
			case 'serieslist':
				$sID = (isset($_GET['sID'])) ? $_GET['sID'] : 0;
				$result = doQuery("SELECT *
								FROM " . HC_TblPrefix . "events
								WHERE SeriesID = '" . cIn($sID) . "'
									  AND IsActive = 1 AND IsApproved = 1
									  AND StartDate >= '" . date("Y-m-d") . "'
								ORDER BY StartDate, Title, TBD, StartTime");
				if(hasRows($result)){
					$hc_pTitle = html_entity_decode(mysql_result($result,0,1));
					$hc_mDesc = substr(strip_tags(mysql_result($result,0,8)),0,160);
					$curYear = stampToDate(mysql_result($result,0,9),"%Y");
					$curMonth = stampToDate(mysql_result($result,0,9),"%m");
					mysql_data_seek($result,0);
				}//end if
				$com = "components/SeriesList.php";
				break;
			case 'tools':
				$hc_pTitle = $hc_pt9;
				$hc_mDesc = $hc_md9;
				$hc_mKeys = $hc_mk9;
				$com = "components/Tools.php";
				break;
			case 'rss':
				$hc_pTitle = $hc_pt10;
				$hc_mDesc = $hc_md10;
				$hc_mKeys = $hc_mk10;
				$com = "components/RSS.php";
				break;
			case 'editreg':
				$hc_pTitle = $hc_pt11;
				$hc_mDesc = $hc_md11;
				$hc_mKeys = $hc_mk11;
				$com = "components/EditRegistration.php";
				break;
			case 'unsubscribe':
				$hc_pTitle = $hc_pt12;
				$hc_mDesc = $hc_md12;
				$hc_mKeys = $hc_mk12;
				$com = "components/Unsubscribe.php";
				break;
			case 'filter':
				$hc_pTitle = $hc_pt13;
				$hc_mDesc = $hc_md13;
				$hc_mKeys = $hc_mk13;
				$com = "components/Filter.php";
				break;
			case 'login':
				$hc_pTitle = $hc_pt14;
				$hc_mDesc = $hc_md14;
				$hc_mKeys = $hc_mk14;
				$com = "components/LoginForm.php";
				break;
			case 'about':
				$hc_pTitle = $hc_pt15;
				$hc_mDesc = $hc_md15;
				$hc_mKeys = $hc_mk15;
				$com = "components/LoginAbout.php";
				break;
			case 'oacc':
				$hc_pTitle = $hc_pt16;
				$hc_mDesc = $hc_md16;
				$hc_mKeys = $hc_mk16;
				$com = (isset($_SESSION[$hc_cfg00 . 'hc_OpenIDLoggedIn'])) ? "components/OIDAccount.php" : $com;
				break;
			case 'ocomm':
				$hc_pTitle = $hc_pt16;
				$hc_mDesc = $hc_md16;
				$hc_mKeys = $hc_mk16;
				$com = (isset($_SESSION[$hc_cfg00 . 'hc_OpenIDLoggedIn'])) ? "components/OIDComments.php" : $com;
				break;
			case 'report':
				$hc_pTitle = $hc_pt17;
				$hc_mDesc = $hc_md17;
				$hc_mKeys = $hc_mk17;
				$com = "components/CommentReport.php";
		}//end switch
	}//end if

	define('HC_Core', $com);?>