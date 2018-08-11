<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2012 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('isHC',true);
	define('isAction',true);
	include('../loader.php');
	
	header('Content-type: text/Calendar');
	header('Content-Disposition: inline; filename="' . CalName . '.ics"');
	
	if(!file_exists(HCPATH.'/cache/ical'.SYSDATE)){
		purge_icalendar();
		
		include(dirname(__FILE__).'/tzs.php');
		$tzSelect = date("O") + (($hc_cfg[35] - date("I")) * 100);
		$result = doQuery("SELECT e.PkID, e.Title, e.Description, e.StartDate, e.StartTime, e.EndTime, e.TBD, e.ContactName, e.ContactEmail, e.ContactURL, e.ContactPhone, e.AllowRegister, 
							e.SpacesAvailable, e.LocID, e.SeriesID, e.Cost, e.LocationName, e.LocationAddress, e.LocationAddress2, e.LocationCity, e.LocationState, 
							e.LocationZip, e.LocCountry, e.ShortURL, l.Name, l.Address, l.Address2, l.City, l.State, l.Zip, l.Country, l.URL, l.Phone, l.Email, l.Lat, l.Lon
						FROM " . HC_TblPrefix . "events e
							LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
						WHERE e.IsActive = 1 AND e.IsApproved = 1 AND e.StartDate >= '" . cIn(SYSDATE) . "' LIMIT ".$hc_cfg[88]);
		ob_start();
		$fp = fopen(HCPATH.'/cache/ical'.SYSDATE, 'w');
		fwrite($fp, "<?php\n//\tHelios iCalendar Cache - Delete this file when upgrading.?>\n");
		
		echo "BEGIN:VCALENDAR\r\n";
		echo "VERSION:2.0\r\n";
		echo "METHOD:PUBLISH\r\n";
		echo "CALSCALE:GREGORIAN\r\n";
		echo "PRODID:-//Refresh Web Development//Helios Calendar//EN\r\n";
		echo "X-FROM-URL:".CalRoot."/\r\n";
		echo "X-WR-RELCALID:".CalName."\r\n";
		echo "X-WR-CALNAME:".CalName."\r\n";
		echo "X-WR-TIMEZONE:".$hc_timezones[$tzSelect]."\r\n";
		echo "X-PUBLISHED-TTL:PT".$hc_cfg[89]."M\r\n";

		while($row = mysql_fetch_row($result)){
			$allDay = false;
			if($row[6] == 0){
				$startDate = ($row[4] != '') ? stampToDate($row[3].' '.$row[4], '%Y%m%dT%H%M%S') : '';
				$endDate = ($row[5] != '') ? stampToDate($row[3].' '.$row[5], '%Y%m%dT%H%M%S') : stampToDate($row[3].' '.$row[4], '%Y%m%dT%H%M%S');
			} else {
				$allDay = true;
				$startDate = ($row[4] != '') ? stampToDate($row[3], '%Y%m%d') : '';
				$endDate = ($row[5] != '') ? stampToDate($row[3], '%Y%m%d') : '';}
			$summary = $row[1];
			$description = $row[2];

			$location = ($row[13] > 0) ?
				$row[24].' - '.str_replace('<br />',' ',buildAddress($row[25],$row[26],$row[27],$row[28],$row[29],$row[30])):
				$row[16].' - '.str_replace('<br />',' ',buildAddress($row[17],$row[18],$row[19],$row[20],$row[21],$row[22]));
			
			$descFooter = "\\n______________________________\\niCalendar Feed powered by Helios Calendar";

			echo "BEGIN:VEVENT\r\n";
			echo "URL;VALUE=URI:" . CalRoot . "/index.php?com=detail&eID=" . $row[0] . "\r\n";
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