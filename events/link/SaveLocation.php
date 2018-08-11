<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	$isAction = 1;
	include('../includes/include.php');
	
	$lID = 0;
	if(isset($_GET['lID']) && is_numeric($_GET['lID'])){
		$lID = $_GET['lID'];
	}//end if
	
	$cID = 0;
	if(isset($_GET['cID']) && is_numeric($_GET['cID'])){
		$cID = $_GET['cID'];
	}//end if
	
	if($lID > 0 && $cID > 0){
		switch($cID){
			case 1:
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = '" . cIn($lID) . "'");
				$locName = mysql_result($result,0,1);
				$location = mysql_result($result,0,1) . " - " . mysql_result($result,0,2) . " " . mysql_result($result,0,3) . ", " . mysql_result($result,0,4) . ", " . mysql_result($result,0,5) . " " . mysql_result($result,0,6) . " " . mysql_result($result,0,7);
				
				$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 35");
				if(mysql_result($result,0,0) == 0){
					$tzOffset = date("O");
				} else {	
					$tzOffset = date("O") + (mysql_result($result,0,0) * 100);
					
					if($tzOffset < 0){
						if(strlen($tzOffset) < 5){
							$tzOffset = ltrim($tzOffset,"-");
							$tzOffset = "-0" . $tzOffset;
						}//end if
					} elseif($tzOffset > 0) {
						if(strlen($tzOffset) < 4){
							$tzOffset = "+0" . $tzOffset;
						} else {
							$tzOffset = "+" . $tzOffset;
						}//end if
					} else {
						$tzOffset = "+0000";
					}//end if
				}//end if
				
				$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE LocID = '" . cIn($_GET['lID']) . "' AND StartDate >= NOW()");
				
				$descFooter = "\\n\\n______________________________\\nCalendar Subscription Powered by Helios Calendar";
				
				//header('Content-type: text/Calendar');
				//header('Content-Disposition: inline; filename="' . str_replace(" ", "", $summary) . '.ics"');
				
				echo "BEGIN:VCALENDAR\n";
				echo "METHOD:PUBLISH\n";
				echo "CALSCALE:GREGORIAN\n";
				echo "PRODID:-//Refresh Web Development//Helios Calendar//EN\n";
				echo "VERSION:2.0\n";
				echo "X-FROM-URL:" . CalRoot . "/\n";
				echo "X-WR-RELCALID:" . cleanSpecialChars(strip_tags($locName)) . " Event : Powered by Helios Calendar\n";
				echo "X-WR-CALNAME:" . cleanSpecialChars($locName) . "\n";
				
				while($row = mysql_fetch_row($result)){
				
					$dtStamp = date("Ymd\TH:i:sO", mktime(0, 0, 0, 1, 1, 1971));
					$starttimepart = split(":", $row[10]);
					$startdatepart = split("-", $row[9]);
					$endtimepart = split(":", $row[12]);
					
					//check for start time
					if($row[10] != ''){
						$startDate = date("Ymd\THis", mktime($starttimepart[0], $starttimepart[1], $starttimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
						
						if($row[12] != ''){
							$endDate = date("Ymd\THis", mktime($endtimepart[0], $endtimepart[1], $endtimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
						} else {
							$endDate = date("Ymd\THis", mktime($starttimepart[0], $starttimepart[1], $starttimepart[2], $startdatepart[1], $startdatepart[2], $startdatepart[0]));
						}//end if
					} else {
						$startDate = date("Ymd\THis", mktime(0, 0, 0, $startdatepart[1], $startdatepart[2], $startdatepart[0]));
						$endDate = date("Ymd\THis", mktime(0, 0, 0, $startdatepart[1], $startdatepart[2], $startdatepart[0]));
					}//end if
					
					$summary = $row[1];
					$description = ereg_replace( "\n", "", $row[8]);
					
					$resultC = doQuery("	SELECT c.CategoryName
										FROM " . HC_TblPrefix . "eventcategories ec
											LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
										WHERE ec.EventID = 1");
					if(hasRows($resultC)){
						$categories = "";
						while($rowC = mysql_fetch_row($resultC)){
							$categories .= $rowC[0] . ",";
						}//end while
					}//end if
					
					echo "BEGIN:VEVENT\n";
					echo "URL;VALUE=URI:" . CalRoot . "/index.php?com=detail&eID=" . $row[0] . "\n";
					echo "DTSTART:" . $startDate . "\n";
					echo "DTEND:" . $endDate . "\n";
					echo "SUMMARY:" . cleanSpecialChars(strip_tags($summary)) . "\n";
					echo "DESCRIPTION:" . wordwrap(cleanSpecialChars(strip_tags($description)) . $descFooter . "\n", 80,"\n\t", 1);
					echo "LOCATION:" . $location . "\n";
					echo "CATEGORIES:" . CalName . " Events\n";
					echo "PRIORITY:0\n";
					echo "TRANSP:TRANSPARENT\n";
					echo "END:VEVENT\n";
				}//end while
				
				echo "END:VCALENDAR";
				break;
		}//end switch
	} else {
		echo "oops";
		//header('Location: ' . CalRoot);
	}//end if
?>
