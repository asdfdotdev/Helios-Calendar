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
		
	header('Content-type: text/Calendar');
	header('Content-Disposition: inline; filename="' . CalName . '.ics"');
	
	if(!file_exists(HCPATH.'/cache/ical'.SYSDATE)){
		purge_icalendar();
		
		include(dirname(__FILE__).'/tzs.php');
		$tzSelect = date("O") + (($hc_cfg[35] - date("I")) * 100);
		$result = doQuery("SELECT e.PkID, e.Title, e.Description, e.StartDate, e.StartTime, e.EndTime, e.TBD, e.ContactName, e.ContactEmail, e.ContactURL, e.ContactPhone, er.Type, 
							er.Space, e.LocID, e.SeriesID, e.Cost, e.LocationName, e.LocationAddress, e.LocationAddress2, e.LocationCity, e.LocationState, 
							e.LocationZip, e.LocCountry, e.ShortURL, l.Name, l.Address, l.Address2, l.City, l.State, l.Zip, l.Country, l.URL, l.Phone, l.Email, l.Lat, l.Lon
						FROM " . HC_TblPrefix . "events e
							LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
							LEFT JOIN " . HC_TblPrefix . "eventrsvps er ON (e.PkID = er.EventID)
						WHERE e.IsActive = 1 AND e.IsApproved = 1 AND e.StartDate >= '" . cIn(SYSDATE) . "' LIMIT ".$hc_cfg[88]);
		ob_start();
		$fp = fopen(HCPATH.'/cache/ical'.SYSDATE, 'w');
		fwrite($fp, "<?php\n//\tHelios iCalendar Cache - Delete this file when upgrading.?>\n");
		
		echo "BEGIN:VCALENDAR\r\n";
		echo "VERSION:2.0\r\n";
		echo "METHOD:PUBLISH\r\n";
		echo "CALSCALE:GREGORIAN\r\n";
		echo "PRODID:-//Helios Calendar//EN\r\n";
		echo "X-FROM-URL:".CalRoot."/\r\n";
		echo "X-WR-RELCALID:".CalName."\r\n";
		echo "X-WR-CALNAME:".CalName."\r\n";
		echo "X-WR-TIMEZONE:".$hc_timezones[$tzSelect]."\r\n";
		echo "X-PUBLISHED-TTL:PT".$hc_cfg[89]."M\r\n";
		 
		while($row = mysql_fetch_row($result)){
			$dtStamp = date("Ymd\TH:i:sO", mktime(0, 0, 0, 1, 1, 1971));
			$startdatepart = explode("-", $row[3]);
			$starttimepart = explode(":", $row[4]);
			$endtimepart = explode(":", $row[5]);
			$allDay = false;
						
			if($row[4] != ''){
				$startDate = date("Ymd\THis", mktime($starttimepart[0], $starttimepart[1], $starttimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));

				if($row[5] != ''){
					if($row[4] > $row[5]){
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
			$description = ($hc_cfg[109] > 0) ? clean_truncate(strip_tags($row[2]),$hc_cfg[109]).' '.$hc_lang_event['ReadMore'].' '.CalRoot.'/?eID='.$row[0] : $row[2];
			
			$location = ($row[13] > 0) ?
				$row[24].' - '.str_replace('<br />',' ',buildAddress($row[25],$row[26],$row[27],$row[28],$row[29],$row[30])):
				$row[16].' - '.str_replace('<br />',' ',buildAddress($row[17],$row[18],$row[19],$row[20],$row[21],$row[22]));
			
			$descFooter = "\\n______________________________\\niCalendar Feed powered by Helios Calendar";

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
		
		fwrite($fp, ob_get_contents());
		fclose($fp);
		ob_end_clean();
	}
	include(HCPATH.'/cache/ical'.SYSDATE);
	
	function purge_icalendar(){
		if(count(glob(HCPATH.'/cache/ical*')) > 0)
			foreach(glob(HCPATH.'/cache/ical*') as $file){
				unlink($file);
			}
	}
?>