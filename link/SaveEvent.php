<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
		
	include('../loader.php');
	action_headers();
		
	$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? cIn(strip_tags($_GET['eID'])) : 0;
	$cID = (isset($_GET['cID']) && is_numeric($_GET['cID'])) ? cIn(strip_tags($_GET['cID'])) : 0;
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = '" . $eID . "'");
	
	include_once(HCLANG.'/public/event.php');
	
	if(hasRows($result)){
		switch($cID){
			case 1:
				if(!preg_match("$hc_cfg[85]i",$_SERVER['HTTP_USER_AGENT']))
					doQuery("UPDATE " . HC_TblPrefix . "events SET Downloads = Downloads + 1 WHERE PkID = '" . $eID . "'");

				$link = "http://www.google.com/calendar/event?action=TEMPLATE";
				if(mysql_result($result,0,11) == 0){
					if(mysql_result($result,0,12) != ''){
						$link .= "&dates=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "%Y%m%dT%H%M%S") . "/" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,12), "%Y%m%dT%H%M%S");
					} else {
						$link .= "&dates=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "%Y%m%dT%H%M%S") . "/" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "%Y%m%dT%H%M%S");
					}
				} else {
					$link .= "&dates=" . stampToDate(mysql_result($result,0,9), "%Y%m%d") . "/" . stampToDate(mysql_result($result,0,9), "%Y%m%d");
				}
				
				$link .= "&text=" . urlencode(mysql_result($result,0,1));
				if(strlen(mysql_result($result,0,8)) > 400){
					$link .= "&details=" . urlencode(htmlspecialchars(strip_tags(cOut(substr(mysql_result($result,0,8),0,400)))) . "...<br /><br />Full Description available at: " . CalRoot . "/index.php?eID=" . $eID);
				} else {
					$link .= "&details=" . urlencode(htmlspecialchars(strip_tags(cOut(mysql_result($result,0,8)))));
				}
				if(mysql_result($result,0,35) == 0 || mysql_result($result,0,35) == ''){
					$link .= "&location=" . urlencode(mysql_result($result,0,3) . " " . mysql_result($result,0,4) . " " . mysql_result($result,0,5) . " " . mysql_result($result,0,6) . " " . mysql_result($result,0,37) . " " . mysql_result($result,0,7));
				} else {
					$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = '" . cIn(mysql_result($result,0,35)) . "'");
					$link .= "&location=" . urlencode(mysql_result($result,0,2) . " " . mysql_result($result,0,3) . " " . mysql_result($result,0,4) . " " . mysql_result($result,0,5) . " " . mysql_result($result,0,6) . " " . mysql_result($result,0,7));
				}
				
				header("Location: $link");
				break;
			case 2:
				if(!preg_match("$hc_cfg[85]i",$_SERVER['HTTP_USER_AGENT']))
					doQuery("UPDATE " . HC_TblPrefix . "events SET Downloads = Downloads + 1 WHERE PkID = '" . $eID . "'");

				$link = "http://calendar.yahoo.com/?v=60&view=d&type=20";
				if(mysql_result($result,0,11) == 1){
					$link .= "&st=" . stampToDate(mysql_result($result,0,9), "%Y%m%d");
				} else {
					$link .= "&st=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "%Y%m%dT%H%M%S");
				}
				$link .= "&title=" . urlencode(mysql_result($result,0,1));
				
				if(strlen(mysql_result($result,0,8)) > 400){
					$link .= "&desc=" . urlencode(strip_tags(substr(mysql_result($result,0,8),0,400) . "...\n\nFull Description available at: " . CalRoot . "/index.php?eID=" . $eID));
				} else {
					$link .= "&description=" . urlencode(strip_tags(mysql_result($result,0,8)));
				}
				
				if(mysql_result($result,0,35) == 0 || mysql_result($result,0,35) == ''){
					$link .= "&in_loc=" . urlencode(mysql_result($result,0,2));
					$link .= "&in_st=" . urlencode(mysql_result($result,0,3) . " " . mysql_result($result,0,4));
					$link .= "&in_csz=" . urlencode(mysql_result($result,0,5) . ", " . mysql_result($result,0,6) . " " . mysql_result($result,0,37) . " " . mysql_result($result,0,7));
				} else {
					$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = '" . cIn(mysql_result($result,0,35)) . "'");
					$link .= "&in_loc=" . urlencode(mysql_result($result,0,1));
					$link .= "&in_st=" . urlencode(mysql_result($result,0,2) . " " . mysql_result($result,0,3));
					$link .= "&in_csz=" . urlencode(mysql_result($result,0,4) . ", " . mysql_result($result,0,5) . " " . mysql_result($result,0,6) . " " . mysql_result($result,0,7));
				}
				header("Location: $link");
				break;
			case 3:
			case 4:
				if($hc_cfg[108] == 0)
					go_home();
				
				if(!preg_match("$hc_cfg[85]i",$_SERVER['HTTP_USER_AGENT']))
					doQuery("UPDATE " . HC_TblPrefix . "events SET Downloads = Downloads + 1 WHERE PkID = '" . $eID . "'");
				
				include('tzs.php');
				$tzSelect = date("O") + (($hc_cfg[35] - date("I")) * 100);
				$tzSend = $hc_timezones[$tzSelect];
				
				$tzOffset = date("O") + ($hc_cfg[35] * 100);
				if($tzOffset == 0){
					$tzOffset = "+0000";
				} elseif($tzOffset < 0){
					if(strlen($tzOffset) < 5){
						$tzOffset = ltrim($tzOffset,"-");
						$tzOffset = "-0" . $tzOffset;
					}
				} elseif($tzOffset > 0) {
					$tzOffset = (strlen($tzOffset) < 4) ? '+0' . $tzOffset : '+' . $tzOffset;
				}
				
				$dtStamp = date("Ymd\TH:i:sO", mktime(0, 0, 0, 1, 1, 1971));
				$starttimepart = explode(":", mysql_result($result,0,10));
				$startdatepart = explode("-", mysql_result($result,0,9));
				$endtimepart = explode(":", mysql_result($result,0,12));
				
				$allDay = false;
				if(mysql_result($result,0,10) != ''){
					$startDate = date("Ymd\THis", mktime($starttimepart[0], $starttimepart[1], $starttimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
					
					if(mysql_result($result,0,12) != ''){
						if(mysql_result($result,0,10) > mysql_result($result,0,12)){
							$endDate = date("Ymd\THis", mktime($endtimepart[0], $endtimepart[1], $endtimepart[2], $startdatepart[1], $startdatepart[2]+1, $startdatepart[0]));
						} else {
							$endDate = date("Ymd\THis", mktime($endtimepart[0], $endtimepart[1], $endtimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
						}
					} else {
						$endDate = date("Ymd\THis", mktime($starttimepart[0], $starttimepart[1], $starttimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
					}
				} else {
					$allDay = true;
					$startDate = date("Ymd", mktime(0, 0, 0, $startdatepart[1], $startdatepart[2], $startdatepart[0]));
					$endDate = date("Ymd", mktime(0, 0, 0, $startdatepart[1], $startdatepart[2] + 1, $startdatepart[0]));
				}
				
				$summary = mysql_result($result,0,1);
				$description = ($hc_cfg[109] > 0) ? clean_truncate(strip_tags(mysql_result($result,0,8)),$hc_cfg[109]).' '.$hc_lang_event['ReadMore'].' '.CalRoot.'/?eID='.$eID : mysql_result($result,0,8);
				
				if(mysql_result($result,0,33) == 0 OR mysql_result($result,0,33) == ''){
					$location = mysql_result($result,0,2) . " - " . mysql_result($result,0,3) . " " . mysql_result($result,0,4) . ", " . mysql_result($result,0,5) . ", " . mysql_result($result,0,6) . " " . mysql_result($result,0,35) . " " . mysql_result($result,0,7);
				} else {
					$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = '" . cIn(mysql_result($result,0,33)) . "'");
					$location = mysql_result($result,0,1) . " - " . mysql_result($result,0,2) . " " . mysql_result($result,0,3) . ", " . mysql_result($result,0,4) . ", " . mysql_result($result,0,5) . " " . mysql_result($result,0,6) . " " . mysql_result($result,0,7);
				}
				
				$descFooter = "\\n______________________________\\nEvent Downloaded Powered by Helios Calendar";
				
				if($cID == 3){
					header('Content-type: text/Calendar');
					header('Content-Disposition: inline; filename="' . cleanXMLChars(str_replace(" ", "", $summary),1) . '.ics"');
				} else {
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
					header('Content-type: text/x-vCalendar');
					header('Content-Disposition: inline; filename="' . cleanXMLChars(str_replace(" ", "", $summary),1) . '.vcs"');
				}
				
				echo "BEGIN:VCALENDAR\r\n";
				echo "VERSION:2.0\r\n";
				echo "METHOD:PUBLISH\r\n";
				echo "CALSCALE:GREGORIAN\r\n";
				echo "PRODID:-//Helios Calendar//EN\r\n";
				echo "X-FROM-URL:" . CalRoot . "/index.php?eID=" . $eID . "\r\n";
				echo "X-WR-RELCALID:" . CalName . " " . cOut(cleanSpecialChars(strip_tags($summary))) . " " . $startDate . "\r\n";
				echo "X-WR-TIMEZONE:" . $tzSend . "\r\n";
				echo "X-WR-CALNAME:" . CalName . "\r\n";
				echo "BEGIN:VEVENT\r\n";
				echo "URL;VALUE=URI:" . CalRoot . "/index.php?eID=" . $eID . "\r\n";
				
				if($allDay == false){
					echo "DTSTART:" . $startDate . "\r\n";
					echo "DTEND:" . $endDate . "\r\n";
				} else {
					echo "DTSTART;VALUE=DATE:" . $startDate . "\r\n";
					echo "DTEND;VALUE=DATE:" . $endDate . "\r\n";
				}
				
				echo "SUMMARY:" . str_replace(";", "\;",str_replace(",", "\,",html_entity_decode(cOut(cleanSpecialChars(strip_tags($summary)))))) . "\r\n";
				echo "DESCRIPTION:" . str_replace(";", "\;",str_replace(",", "\,",html_entity_decode(cOut(cleanSpecialChars(strip_tags(cleanBreaks($description))))) . $descFooter)) . "\r\n";
				echo "LOCATION:" . str_replace(";", "\;",str_replace(",", "\,",html_entity_decode(cOut($location)))) . "\r\n";
				echo "CATEGORIES:" . CalName . " Events\r\n";
				echo "PRIORITY:0\r\n";
				echo "TRANSP:TRANSPARENT\r\n";
				echo "END:VEVENT\r\n";
				echo "END:VCALENDAR";
				break;
			case 5:				
				if(!preg_match("$hc_cfg[85]i",$_SERVER['HTTP_USER_AGENT']))
					doQuery("UPDATE " . HC_TblPrefix . "events SET Downloads = Downloads + 1 WHERE PkID = '" . $eID . "'");

				$link = "http://calendar.live.com/calendar/calendar.aspx?rru=addevent";

				if(mysql_result($result,0,11) == 0){
					if(mysql_result($result,0,12) != ''){
						$link .= "&dtstart=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "%Y%m%dT%H%M%S") . "&dtend=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,12), "%Y%m%dT%H%M%S");
					} else {
						$link .= "&dtstart=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "%Y%m%dT%H%M%S") . "&dtend=" . stampToDate(mysql_result($result,0,9) . " " . mysql_result($result,0,10), "%Y%m%dT%H%M%S");
					}
				} else {
					$link .= "&dtstart=" . stampToDate(mysql_result($result,0,9), "%Y%m%d") . "T000000&dtend=" . stampToDate(mysql_result($result,0,9), "%Y%m%d") . "T000000";
				}

				$link .= "&summary=" . urlencode(mysql_result($result,0,1));
			
				if(strlen(mysql_result($result,0,8)) > 400){
					$link .= "&description=" . urlencode(htmlspecialchars(strip_tags(cOut(substr(mysql_result($result,0,8),0,400)))) . "...\n\nFull Description available at: " . CalRoot . "/index.php?eID=" . $eID);
				} else {
					$link .= "&description=" . urlencode(htmlspecialchars(strip_tags(cOut(mysql_result($result,0,8)))));
				}
			
				if(mysql_result($result,0,35) == 0 || mysql_result($result,0,35) == ''){
					$link .= "&location=" . urlencode(mysql_result($result,0,3) . " " . mysql_result($result,0,4) . " " . mysql_result($result,0,5) . " " . mysql_result($result,0,6) . " " . mysql_result($result,0,37) . " " . mysql_result($result,0,7));
				} else {
					$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = '" . cIn(mysql_result($result,0,35)) . "'");
					$link .= "&location=" . urlencode(mysql_result($result,0,2) . " " . mysql_result($result,0,3) . " " . mysql_result($result,0,4) . " " . mysql_result($result,0,5) . " " . mysql_result($result,0,6) . " " . mysql_result($result,0,7));
				}
				
				header("Location: $link");
				break;
		}
	} else {
		header('Location: ' . CalRoot);
	}
?>