<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	if($efUser != '' && $efPass != ''){
		$ip = gethostbyname("api.evdb.com");
		if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
			$msgID = 8;
		} else {
			$efSend = $efRest;
			$efSend .= "?app_key=" . $efKey;
			$efSend .= "&user=" . $efUser;
			if($efRest == "/rest/events/modify"){
				$resultU = doQuery("SELECT NetworkID FROM " . HC_TblPrefix . "eventnetwork WHERE EventID = " . $newPkID);
				$efSend .= "&id=" . mysql_result($resultU,0,0);
			}//end if
			$efSend .= "&password=" . urlencode($efPass);
			$efSend .= "&title=" . urlencode($eventTitle);
			
			if($tbd == 0){
				$efSend .= "&start_time=" . $eventDate . "T" . str_replace("'", "", $startTime);
				if(!isset($_POST['ignoreendtime'])){
					$efSend .= "&stop_time=" . $eventDate . "T" . str_replace("'", "", $endTime);
				}//end if
				$efSend .= "&all_day=";
			} elseif($tbd = 1 || $tbd = 2) {
				$efSend .= "&start_time=" . $eventDate . "T00:00:00";
				$efSend .= "&all_day=" . $tbd;
			}//end if
			$efSend .= "&price=" . urlencode($cost);
			
			$resultCat = doQuery("	SELECT c.CategoryName
									FROM " . HC_TblPrefix . "eventcategories ec
										LEFT JOIN " . HC_TblPrefix . "categories c ON (ec.CategoryID = c.PkID)
									WHERE ec.EventID = $newPkID ");
			if(hasRows($resultCat)){
				$tags = "";
				while($row = mysql_fetch_row($resultCat)){
					$tags .= str_replace(" ", "_", $row[0]) . " ";
				}//end while
				$efSend .= "&tags=" . urlencode($tags) . "Helios_Calendar";
			}//end if
			$eventDescPrint = $eventDesc;
			$resultLoc = doQuery("SELECT NetworkID FROM " . HC_TblPrefix . "locationnetwork WHERE NetworkType = 1 AND LocationID = " . $locID);
			if(hasRows($resultLoc)){
				$efSend .= "&venue_id=" . mysql_result($resultLoc,0,0);
			} else {
				if($locID > 0){
					$resultLoc = doQuery("SELECT * From " . HC_TblPrefix . "locations WHERE PkID = " . $locID);
					if(hasRows($resultLoc)){
						$locName = mysql_result($resultLoc,0,1);
						$locAddress = mysql_result($resultLoc,0,2);
						$locAddress2 = mysql_result($resultLoc,0,3);
						$locCity = mysql_result($resultLoc,0,4);
						$locState = mysql_result($resultLoc,0,5);
						$locCountry = mysql_result($resultLoc,0,6);
						$locZip = mysql_result($resultLoc,0,7);
					}//end if
				}//end if
				$eventDescPrint .= "<p><b>Venue</b><br>";
				if($locName != ''){
					$eventDescPrint .= $locName . "<br>";
				}//end if
				if($locAddress != ''){
					$eventDescPrint .= $locAddress . "<br>";
				}//end if
				if($locAddress2 != ''){
					$eventDescPrint .= $locAddress2 . "<br>";
				}//end if
				if($locCity != ''){
					$eventDescPrint .= $locCity . ", ";
				}//end if
				if($locState != ''){
					$eventDescPrint .= $locState . " ";
				}//end if
				if($locCountry != ''){
					$eventDescPrint .=  $locCountry . " ";
				}//end if
				if($locZip != ''){
					$eventDescPrint .=  $locZip . "<br>";
				}//end if
				$eventDesc .= "</p>";
				$efSend .= "&venue_id=";
			}//end if
			
			$eventDescPrint .= "<p><br>" . mysql_result($result,3,1) . "<br>";
			$eventDescPrint .= "<b><a href=\"" . CalRoot . "\">" . CalRoot . "/</a></b></p>";
			$efSend .= "&description=" . urlencode(nl2br($eventDescPrint));
			
			$efSend .= "&privacy=1";	//1 = public, 2 = private
			
			$request = "GET " . $efSend . " HTTP/1.0\r\n";
			$request .= "Host: api.evdb.com\r\n";
			$request .= "Connection: Close\r\n\r\n";
						
			fwrite($fp, $request);
			$data = "";
			while (!feof($fp)) {
				$data .= fread($fp,1024);
			}//end while
			
			$data = explode("<?xml", $data);
			$data = "<?xml" . $data[1];
			
			global $step;
			global $efID;
			
			$step = "";
			$efID = 0;
			
			require_once('EventfulFunctions.php');
			
			$xml_parser = xml_parser_create();
			xml_set_element_handler($xml_parser, "startTag", "endTag");
			if(!(xml_parse($xml_parser, $data, feof($fp)))){
			    die("Error on line " . xml_get_current_line_number($xml_parser));
			}//end if
			xml_parser_free($xml_parser);
			fclose($fp);
			
			if($efRest == "/rest/events/new"){
				doQuery("INSERT INTO " . HC_TblPrefix . "eventnetwork(EventID,NetworkID,NetworkType,IsActive) VALUES('" . $newPkID . "','" . cIn($efID) . "',1,1);");
			} else {
				doQuery("UPDATE " . HC_TblPrefix . "eventnetwork SET NetworkID = '" . cIn($efID) . "' WHERE EventID = '" . $newPkID . "'");
			}//end if
		}//end if
	}//end if	?>