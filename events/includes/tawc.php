<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/	
	$cacheNum = 60;			//	The Number of phrases to cache (should be at most count($hc_lang_twitter)-1, can be less to prevent newer phrases from being used)
	$postFreq = 43200;		//	Frequency to make posts (in seconds) - default every 12 hours (43200)
	
	// Only change the follow two variables if you use a proxy
	//	Be VERY CAREFUL when using a proxy - they could steal your Twitter account
	$twHost = "www.twitter.com";
	$twPort = "80";
	
	//	Do NOT edit any of the code below this point unless you use a proxy (only make changes according to 
	//	instructions in the documentation) or you will likely cause your twitter updates to fail.
	
	if(!file_exists(realpath('cache/tawc.php'))){
		rebuildCache(1);
	}//end if
	include('cache/tawc.php');
	
	if(($hc_tawc1 + $postFreq) <= date("U")){
		
		include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/twitter.php');
		$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN(46,47);");
		
		$twUser = mysql_result($result,0,0);
		$twPass = mysql_result($result,1,0);
		
		$recentTweets = explode("/",$hc_tawc2);
		$hc_lang_twitter = array_diff($hc_lang_twitter, $recentTweets);
		$abort = 0;
		$randPosts = array_rand($hc_lang_twitter, 2);
		$randPost = $randPosts[1];
		
		$twtrMsg = "";
		if(strstr($hc_lang_twitter[$randPost],"[clink1]")){
			$twtrMsg = str_replace("[clink1]",getCoreLink(1),$hc_lang_twitter[$randPost]);
		} elseif(strstr($hc_lang_twitter[$randPost],"[clink2]")) {
			$twtrMsg = str_replace("[clink2]",getCoreLink(2),$hc_lang_twitter[$randPost]);
		} elseif(strstr($hc_lang_twitter[$randPost],"[clink3]")) {
			$twtrMsg = str_replace("[clink3]",getCoreLink(3),$hc_lang_twitter[$randPost]);
		} elseif(strstr($hc_lang_twitter[$randPost],"[clink4]")) {
			$twtrMsg = str_replace("[clink4]",getCoreLink(4),$hc_lang_twitter[$randPost]);
		} elseif(strstr($hc_lang_twitter[$randPost],"[clink5]")) {
			$twtrMsg = str_replace("[clink5]",getCoreLink(5),$hc_lang_twitter[$randPost]);
		} elseif(strstr($hc_lang_twitter[$randPost],"[clink6]")) {
			$twtrMsg = str_replace("[clink6]",getCoreLink(6),$hc_lang_twitter[$randPost]);
		} elseif(strstr($hc_lang_twitter[$randPost],"[clink7]")) {
			$twtrMsg = str_replace("[clink7]",getCoreLink(7),$hc_lang_twitter[$randPost]);
		} elseif(strstr($hc_lang_twitter[$randPost],"[linkE]")) {
			$twtrMsg = str_replace("[linkE]",getAnEvent(1),$hc_lang_twitter[$randPost]);
		} elseif(strstr($hc_lang_twitter[$randPost],"[linkP]")) {
			$twtrMsg = str_replace("[linkP]",getAnEvent(2),$hc_lang_twitter[$randPost]);
		} elseif(strstr($hc_lang_twitter[$randPost],"[linkB]")) {
			$twtrMsg = str_replace("[linkB]",getAnEvent(3),$hc_lang_twitter[$randPost]);
		} elseif(strstr($hc_lang_twitter[$randPost],"[linkN]")) {
			$twtrMsg = str_replace("[linkN]",getAnEvent(4),$hc_lang_twitter[$randPost]);
		} elseif(strstr($hc_lang_twitter[$randPost],"[linkL]")) {
			$twtrMsg = str_replace("[linkL]",getALocation(),$hc_lang_twitter[$randPost]);
		} elseif(strstr($hc_lang_twitter[$randPost],"[countE]")) {
			$twtrMsg = str_replace("[countE]",getStat(1),$hc_lang_twitter[$randPost]);
		} elseif(strstr($hc_lang_twitter[$randPost],"[countD]")) {
			$twtrMsg = str_replace("[countD]",getStat(2),$hc_lang_twitter[$randPost]);
		} elseif(strstr($hc_lang_twitter[$randPost],"[countM]")) {
			$twtrMsg = str_replace("[countM]",getStat(3),$hc_lang_twitter[$randPost]);
		} elseif(strstr($hc_lang_twitter[$randPost],"[countF]")) {
			$twtrMsg = str_replace("[countF]",getStat(4),$hc_lang_twitter[$randPost]);
		} elseif(strstr($hc_lang_twitter[$randPost],"[countL]")) {
			$twtrMsg = str_replace("[countL]",getStat(5),$hc_lang_twitter[$randPost]);
		} else {
			$twtrMsg = $hc_lang_twitter[$randPost];
		}//end if
		
		if(strstr($twtrMsg,"[user]")) {
			$user = getAFollower($twUser, $twPass, $twHost, $twPort);
			if($user != ''){
				$twtrMsg = str_replace("[user]",$user,$twtrMsg);
			} else {
				$twtrMsg = "";
			}//end if
		}//end if
		
		if(strstr($twtrMsg,"[reply]")) {
			$user = getNewestReply($twUser, $twPass, $twHost, $twPort);
			if($user != ''){
				$twtrMsg = str_replace("[reply]",$user,$twtrMsg);
			} else {
				$twtrMsg = "";
			}//end if
		}//end if	
		
		if($twtrMsg != '' && $abort != 1){
			if(count($recentTweets > $cacheNum)){
				array_splice($recentTweets,$cacheNum-1);
			}//end if
			array_unshift($recentTweets,$randPost);
			$recentTweets = implode("/",$recentTweets);
			doQuery("UPDATE " . HC_TblPrefix . "settings SET SettingValue = '" . date("U") . "|" . $recentTweets . "' WHERE PkID = 48");
			
			$ip = gethostbyname($twHost);
			if(($fp = fsockopen($ip, $twPort, $errno, $errstr, 1)) ){
				$read = "";
				/*	The following line of code may not be altered. Removing Helios Calendar source credit from your twitter posts
					is not permitted under the terms of the Helios Calendar SLA		*/
				$request = "POST /statuses/update.xml?source=helioscalendar&status=" . urlencode($twtrMsg) . " HTTP/1.0\r\n";
				$request .= "Host: www.twitter.com\r\n";
				$request .= "User-Agent: Helios Calendar " . $hc_cfg49 . "\r\n";
				$request .= "Authorization: Basic " . base64_encode($twUser . ':' . $twPass) . "\r\n";
				//$request .= "Content-length: 0\r\n";
				$request .= "Connection: Close\r\n\r\n";
				
				fwrite($fp, $request);

				while (!feof($fp)) {
					$read .= fread($fp,1024);
				}//end while
				fclose($fp);
			}//end if
		}//end if
		
		rebuildCache(1);
	}//end if
	
	//	Get a stat total
	function getStat($typeID){
		switch($typeID){
			case 1:
				$result = doQuery("SELECT COUNT(*) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . date("Y-m-d") . "'");
				$stat = mysql_result($result,0,0);
				break;
			case 2:
				$result = doQuery("SELECT SUM(Downloads) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1");
				$stat = mysql_result($result,0,0);
				break;
			case 3:
				$result = doQuery("SELECT SUM(Directions) FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1");
				$stat = mysql_result($result,0,0);
				break;
			case 4:
				$result = doQuery("	SELECT COUNT(*)
									FROM " . HC_TblPrefix . "sendtofriend
										LEFT JOIN " . HC_TblPrefix . "events ON " . HC_TblPrefix . "events.PkID = " . HC_TblPrefix . "sendtofriend.EventID
									WHERE IsActive = 1 AND IsApproved = 1");
				$stat = mysql_result($result,0,0);
				break;
			case 5:
				$result = doQuery("	SELECT COUNT(DISTINCT " . HC_TblPrefix . "events.LocID)
									FROM " . HC_TblPrefix . "locations
										LEFT JOIN " . HC_TblPrefix . "events ON " . HC_TblPrefix . "events.LocID = " . HC_TblPrefix . "locations.PkID
									WHERE " . HC_TblPrefix . "locations.IsActive = 1 AND " . HC_TblPrefix . "events.IsActive = 1 AND " . HC_TblPrefix . "events.IsApproved = 1 AND StartDate >= '" . date("Y-m-d") . "'");
				$stat = mysql_result($result,0,0);
				break;
		}//end switch
		return $stat;
	}//end getAnEvent()
	
	//	Create a link to an event
	function getAnEvent($typeID){
		switch($typeID){
			case 1:
				$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . date("Y-m-d") . "' AND (StartDate BETWEEN '" . date("Y-m-d") . "' AND '" . date("Y-m-d",mktime(0,0,0,date("m")+1, date("d"), date("Y"))) . "') ORDER BY RAND() LIMIT 1 ");
				$event = mysql_result($result,0,0);
				break;
			case 2:
				$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . date("Y-m-d") . "' AND (StartDate BETWEEN '" . date("Y-m-d") . "' AND '" . date("Y-m-d",mktime(0,0,0,date("m")+1, date("d"), date("Y"))) . "') ORDER BY Views DESC LIMIT 1");
				$event = mysql_result($result,0,0);
				break;
			case 3:
				$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "events WHERE IsBillboard = 1 AND IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . date("Y-m-d") . "' AND (StartDate BETWEEN '" . date("Y-m-d") . "' AND '" . date("Y-m-d",mktime(0,0,0,date("m")+1, date("d"), date("Y"))) . "') ORDER BY RAND() LIMIT 1");
				$event = mysql_result($result,0,0);
				break;
			case 4:
				$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "events WHERE IsActive = 1 AND IsApproved = 1 AND StartDate >= '" . date("Y-m-d") . "' AND (StartDate BETWEEN '" . date("Y-m-d") . "' AND '" . date("Y-m-d",mktime(0,0,0,date("m")+1, date("d"), date("Y"))) . "') ORDER BY PublishDate DESC LIMIT 1");
				$event = mysql_result($result,0,0);
				break;
		}//end switch
		
		if($event == ''){
			$abort = 1;
		}//end if
		
		return CalRoot . "/?eID=" . $event;
	}//end getAnEvent()
	
	//	Create a link to a location
	function getALocation(){
		$result = doQuery("	SELECT " . HC_TblPrefix . "locations.PKid
							FROM " . HC_TblPrefix . "locations
								LEFT JOIN " . HC_TblPrefix . "events ON (" . HC_TblPrefix . "events.LocID = " . HC_TblPrefix . "locations.PkID)
							WHERE " . HC_TblPrefix . "locations.IsActive = 1 AND " . HC_TblPrefix . "events.IsActive = 1 AND " . HC_TblPrefix . "events.IsApproved = 1 AND " . HC_TblPrefix . "events.StartDate >= '" . date("Y-m-d") . "'
							ORDER BY RAND()
							LIMIT 1");
		$loc = mysql_result($result,0,0);
		return CalRoot . "index.php?lID=" . $loc;
	}//end getAnEvent()
	
	//	Create a link to Helios menu item
	function getCoreLink($linkID){
		$link = "";
		switch($linkID){
			case 1:
				$link = CalRoot . "/";
				break;
			case 2:
				$link = CalRoot . "/index.php?com=location";
				break;
			case 3:
				$link = CalRoot . "/index.php?com=submit";
				break;
			case 4:
				$link = CalRoot . "/index.php?com=search";
				break;
			case 5:
				$link = CalRoot . "/index.php?com=signup";
				break;
			case 6:
				$link = CalRoot . "/index.php?com=tools";
				break;
			case 7:
				$link = CalRoot . "/index.php?com=rss";
				break;
		}//end switch
		return $link;
	}//end getCoreLink()
	
	//	Fetch username of newest reply to my micro-blog
	function getNewestReply($twUser, $twPass, $twHost, $twPort){
		$newReply = "";
		
		$ip = gethostbyname($twHost);
		if(($fp = fsockopen($ip, $twPort, $errno, $errstr, 1)) ){
			$read = "";
			$request = "GET /statuses/replies.xml HTTP/1.0\r\n";
			$request .= "Host: www.twitter.com\r\n";
			$request .= "User-Agent: Helios Calendar " . $hc_cfg49 . "\r\n";
			$request .= "Authorization: Basic " . base64_encode($twUser . ':' . $twPass) . "\r\n";
			//$request .= "Content-length: 0\r\n";
			$request .= "Connection: Close\r\n\r\n";
			
			fwrite($fp, $request);
			while (!feof($fp)) {
				$read .= fread($fp,1024);
			}//end while
			
			$newReply = explode("<screen_name>",$read);
			if(isset($newReply[1]) && $newReply[1] != ''){
				$newReply = explode("</screen_name>", $newReply[1]);
				$newReply = $newReply[0];
			} else {
				$newReply = "";
			}//end if
		}//end if
		
		return $newReply;
	}//end getNewestReply()
	
	//	Fetch one of my followers at random
	function getAFollower($twUser, $twPass, $twHost, $twPort){
		$follower = "";
		
		$ip = gethostbyname($twHost);
		if(($fp = fsockopen($ip, $twPort, $errno, $errstr, 1)) ){
			$read = "";
			$request = "GET /statuses/followers.xml?lite=true HTTP/1.0\r\n";
			$request .= "Host: www.twitter.com\r\n";
			$request .= "User-Agent: Helios Calendar " . $hc_cfg49 . "\r\n";
			$request .= "Authorization: Basic " . base64_encode($twUser . ':' . $twPass) . "\r\n";
			//$request .= "Content-length: 0\r\n";
			$request .= "Connection: Close\r\n\r\n";
			
			fwrite($fp, $request);
			while (!feof($fp)) {
				$read .= fread($fp,1024);
			}//end while
			
			$data = explode("<?xml", $read);
			$data = "<?xml" . $data[1];
			
			global $step;
			global $names;
			$step = "";
			$names = array();
			
			function contents($parser, $data){
				global $step;
				global $names;
				
				if($step == "screen_name"){
					$names[] = $data;
				}//end if
				$step = "";
			}//end contents
			
			function startTag($parser, $data, $attrs){
				global $step;
				
				if(strtolower($data) == "screen_name"){
					$step = "screen_name";
					xml_set_character_data_handler($parser, "contents");
				}//end if
			}//end startTag
			
			function endTag($parser, $data){
			    //	do nothing
			}//end endTag
			
			$xml_parser = xml_parser_create();
			xml_set_element_handler($xml_parser, "startTag", "endTag");
			if(!(xml_parse($xml_parser, $data, feof($fp)))){
			    // do nothing
			}//end if
			xml_parser_free($xml_parser);
			fclose($fp);
			
			$chosenOne = "";
			if(count($names) >= 1){
				$chosenOne = str_replace(" ","",$names[rand(0,count($names)-1)]);
			}//end if
		}//end if
		
		return $chosenOne;
	}//end getAFollower()
?>