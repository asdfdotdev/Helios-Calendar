<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include('../loader.php');
	
	if($hc_cfg[108] == 0)
		go_home();
	
	include_once(HCLANG.'/public/event.php');
	
	action_headers();
	
	$lID = (isset($_GET['lID']) && is_numeric($_GET['lID'])) ? cIn($_GET['lID']) : 0;
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = '" . $lID . "'");
	if(hasRows($result)){
		$locName = mysql_result($result,0,1);
		$location = mysql_result($result,0,1) . " - " . mysql_result($result,0,2) . " " . mysql_result($result,0,3) . ", " . mysql_result($result,0,4) . ", " . mysql_result($result,0,5) . " " . mysql_result($result,0,6) . " " . mysql_result($result,0,7);

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

		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE LocID = '" . $lID . "' AND StartDate >= '" . date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y"))) . "' ORDER BY StartDate, StartTime");
		$descFooter = "\\n______________________________\\nCalendar Feed Powered by Helios Calendar";

		header('Content-type: text/Calendar');
		header('Content-Disposition: inline; filename="' . cleanXMLChars(str_replace(" ", "", $locName),1) . '.ics"');

		echo "BEGIN:VCALENDAR\r\n";
		echo "VERSION:2.0\r\n";
		echo "METHOD:PUBLISH\r\n";
		echo "CALSCALE:GREGORIAN\r\n";
		echo "PRODID:-//Helios Calendar//EN\r\n";
		echo "X-FROM-URL:" . CalRoot . "/\r\n";
		echo "X-WR-RELCALID:" . cleanSpecialChars(strip_tags($locName)) . " Event : Powered by Helios Calendar\r\n";
		echo "X-WR-CALNAME:" . cleanSpecialChars($locName) . "\r\n";

		while($row = mysql_fetch_row($result)){
			$dtStamp = date("Ymd\TH:i:sO", mktime(0, 0, 0, 1, 1, 1971));
			$starttimepart = explode(":", $row[10]);
			$startdatepart = explode("-", $row[9]);
			$endtimepart = explode(":", $row[12]);

			$allDay = false;
			if($row[10] != ''){
				$startDate = date("Ymd\THis", mktime($starttimepart[0], $starttimepart[1], $starttimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));

				if($row[12] != ''){
					if($row[10] > $row[12]){
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

			$summary = $row[1];
			$description = ($hc_cfg[109] > 0) ? clean_truncate(strip_tags($row[8]),$hc_cfg[109]).' '.$hc_lang_event['ReadMore'].' '.CalRoot.'/?eID='.$row[0] : $row[8];

			echo "BEGIN:VEVENT\r\n";
			echo "URL;VALUE=URI:" . CalRoot . "/index.php?eID=" . $row[0] . "\r\n";
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
		}

		echo "END:VCALENDAR";
	} else {
		header('Location: ' . CalRoot);
	}?>