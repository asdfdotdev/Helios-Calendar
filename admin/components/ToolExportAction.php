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
	checkIt(1);
	
	$catID = $_POST['catID'];
	$catIDWhere = '0,' . implode(',',$_POST['catID']);
		
	switch($_POST['eID']){
		case 1:	
			header ('Content-Type:text/plain; charset=utf-8');
			$result = doQuery("SELECT DISTINCT e.Title, c.CategoryName, MIN(e.StartDate), MAX(e.StartDate), e.Description, 
								e.LocationName, e.LocationAddress, e.LocationAddress2, e.LocationCity, e.LocationState, 
								e.LocationZip, e.LocCountry, e.ContactName, e.ContactEmail, e.ContactPhone, e.ContactURL, e.StartTime, e.EndTime, e.LocID,
								l.Name, l.Address, l.Address2, l.City, l.State, l.Zip, l.Country
								FROM " . HC_TblPrefix . "events e
									LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
									LEFT JOIN " . HC_TblPrefix . "categories c ON (c.PkID = ec.CategoryID  AND c.PkID IN (" . $catIDWhere . "))
									LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
								WHERE e.IsActive = 1 AND
											e.IsApproved = 1 AND
											(e.StartDate BETWEEN '" . dateToMySQL($_POST['startDate'], "/", $_POST['dateFormat']) . "' AND '" . dateToMySQL($_POST['endDate'], "/", $_POST['dateFormat']) . "') AND
											c.IsActive = 1
								GROUP BY e.Title
								ORDER BY CategoryName, StartDate, Title");
			if(hasRows($result)){
				if($_POST['mID'] == 2){
					header('Content-Disposition:attachment; filename=' . date("Y-m-d") . 'HeliosOutput.rtf');
				}//end if
				echo "<Helios Output>" . chr(13) . chr(10);
				$curCat = "";
				while($row = mysql_fetch_row($result)){
					if($curCat != $row[1]){
						$curCat = $row[1];
						echo "@event head:" . $row[1] . chr(13) . chr(10);
					}//end if
					
					if($row[2] != $row[3]){
						echo "@date head:" . stampToDate($row[2], "%B %d") . " through " . stampToDate($row[3], "%B %d") . chr(13) . chr(10);
					} else {
						echo "@date head:" . stampToDate($row[2], "%B %d") . chr(13) . chr(10);
					}//end if
					
					if($row[18] == 0){
						$locName = $row[5];
						$locAddress = $row[6];
						$locAddress2 = $row[7];
						$locCity = $row[8];
						$locState = $row[9];
						$locZip = $row[10];
						$locCountry = $row[11];
					} else {
						$locName = $row[19];
						$locAddress = $row[20];
						$locAddress2 = $row[21];
						$locCity = $row[22];
						$locState = $row[23];
						$locZip = $row[24];
						$locCountry = $row[25];
					}//end if
					
					$output = "@calendar copy:<B>" . unhtmlentities(preg_replace(array('/\r/', '/\n/'), "", strip_tags($row[0]))) . "</B>,";
					$output .= " Location: " . $locName . " " . $locAddress . " " . $locAddress2 . " " . $locCity . " " . $locState . " " . $locZip . " " . $locCountry;
					$output .= ". " . unhtmlentities(preg_replace(array('/\r/', '/\n/'), "", strip_tags($row[4])));
					if($row[16] != ''){
						$timepart = explode(":", $row[16]);
						$startTime = strftime($_POST['timeFormat'], mktime($timepart[0], $timepart[1], $timepart[2]));
						$output .= " Time: " .  $startTime;
						if($row[17] != ''){
							$timepart = explode(":", $row[17]);
							$endTime = strftime($_POST['timeFormat'], mktime($timepart[0], $timepart[1], $timepart[2]));
							$output .= " - " . $endTime;
						}//end if
					}//end if
					$output .= " Contact: " . $row[12] . " " . $row[13] . " " . $row[14] . " " . $row[15];
					echo str_replace("   ", "", $output);
					echo chr(13) . chr(10) . chr(13) . chr(10);
				}//end while
			} else {
				echo "There are no events available for that criteria.";
			}//end if
			break;
			
		case 2:
			header('Content-Type:text/plain; charset=utf-8');
			$result = doQuery("SELECT e.Title, e.Description, e.Cost, e.StartDate, e.StartTime, e.EndTime, e.TBD,
								e.LocID, e.LocationName, e.LocationAddress, e.LocationAddress2,
								e.LocationCity, e.LocationState, e.LocationZip, e.LocCountry,
								l.Name, l.Address, l.Address2, l.City, l.State, l.Zip, l.Country,
								e.ContactName, e.ContactEmail, e.ContactPhone, e.ContactURL,
								e.IsBillboard, e.SeriesID, e.AllowRegister, e.SpacesAvailable
								FROM " . HC_TblPrefix . "events e
									LEFT JOIN " . HC_TblPrefix . "eventcategories ec ON (e.PkID = ec.EventID)
									LEFT JOIN " . HC_TblPrefix . "categories c ON (c.PkID = ec.CategoryID  AND c.PkID IN (" . $catIDWhere . "))
									LEFT JOIN " . HC_TblPrefix . "locations l ON (e.LocID = l.PkID)
								WHERE e.IsActive = 1 AND
									e.IsApproved = 1 AND
									(e.StartDate BETWEEN '" . dateToMySQL($_POST['startDate'], "/", $hc_cfg24) . "' AND '" . dateToMySQL($_POST['endDate'], "/", $hc_cfg24) . "') AND
									c.IsActive = 1
								ORDER BY CategoryName, StartDate, Title, SeriesID");
			if(hasRows($result)){
				if($_POST['mID'] == 2){
					header('Content-Disposition:attachment; filename=' . date("Y-m-d") . 'HeliosOutput.csv');
				}//end if
				echo "EventTitle,Description,Cost,EventDate,StartTime,EndTime,AllDay,LocationID,LocatioName,LocationAddress,LocationAddress2,LocationCity,LocationState,LocationZip,LocationCountry,ContactName,ContactEmail,ContactPhone,ContactURL,Billboard,SeriesID,Registration,SpaceAvailable\n";
				
				while($row = mysql_fetch_row($result)){
					echo cleanBreaks(str_replace("'", "'", str_replace(",", "", strip_tags($row[0]))),1) . ',';
					echo cleanBreaks(str_replace("'", "'", str_replace(",", "", $row[1])), 1) . ',';
					echo cleanBreaks(str_replace("'", "'", str_replace(",", "", $row[2])), 1) . ',';
					echo $row[3]. ',';
					echo $row[4] . ',';
					echo $row[5] . ',';
					echo $row[6] . ',';
					echo $row[7] . ',';
					
					if($row[7] == 0){
						$locName = $row[8];
						$locAddress = $row[9];
						$locAddress2 = $row[10];
						$locCity = $row[11];
						$locState = $row[12];
						$locPostal = $row[13];
						$locCountry = $row[14];
					} else {
						$locName = $row[15];
						$locAddress = $row[16];
						$locAddress2 = $row[17];
						$locCity = $row[18];
						$locState = $row[19];
						$locPostal = $row[20];
						$locCountry = $row[21];
					}//end if
					
					echo $locName . ',';
					echo $locAddress . ',';
					echo $locAddress2 . ',';
					echo $locCity . ',';
					echo $locState . ',';
					echo $locPostal . ',';
					echo $locCountry . ',';
					echo str_replace("'", "", str_replace(",", "", $row[22])) . ',';
					echo str_replace("'", "", str_replace(",", "", $row[23])) . ',';
					echo str_replace("'", "", str_replace(",", "", $row[24])) . ',';
					echo ($row[25] != 'http://') ? str_replace("'", "", str_replace(",", "", $row[25])) . ',' : ',';
					echo ($row[26] == 0) ? '0,' : '1,';
					echo $row[27] . ',';
					echo $row[28] . ',';
					echo $row[29];
					echo "\n";
				}//end while
			} else {
				echo "There were no events found for that criteria.";
			}//end if
			break;
	}//end switch
	
	function unhtmlentities($text) {
	   $text= html_entity_decode($text,ENT_QUOTES,"ISO-8859-1");
	   $text= preg_replace('/&#(\d+);/me',"chr(\\1)",$text);
	   $text= preg_replace('/&#x([a-f0-9]+);/mei',"chr(0x\\1)",$text);
	   return $text;
	}//end unhtmlentities()	?>