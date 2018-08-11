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
	
	if(!isset($_GET['dID'])){
		$lID = $_POST['lID'];
		$lName = $_POST['lName'];
		$address = $_POST['address'];
		$address2 = $_POST['address2'];
		$city = $_POST['city'];
		$state = $_POST['locState'];
		$country = $_POST['country'];
		$zip = $_POST['zip'];
		$url = $_POST['url'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$status = $_POST['status'];
		$descript = $_POST['descript'];
		$lat = $_POST['lat'];
		$lon = $_POST['lon'];
		
		$gQuality = (isset($_POST['gStatus']) && $_POST['gStatus'] != '') ? cIn($_POST['gStatus']) : 0;
		
		if(!ereg("^http://*", $url, $regs)){
		   $contactURL = "http://" . $url;
		}//end if
		
		$failed = 0;
		if(isset($_POST['updateMap'])){
			$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 26");
			$googleAPI = mysql_result($result,0,0);
			if($googleAPI != ''){
				$ip = gethostbyname('maps.google.com');
				if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
					$failed = 1;
				} else {
					$read = "";
					$request = "GET /maps/geo?output=csv&key=" . $googleAPI . "&q=" . urlencode($address . " " . $city . " " . $state . " " . $zip) . " HTTP/1.1\r\n";
					$request .= "Host: maps.google.com\r\n";
					$request .= "Connection: Close\r\n\r\n";
					
					fwrite($fp, $request);
					
					while (!feof($fp)) {
						$read .= fread($fp,1024);
					}//end while
					
					$output = strtoupper($read);
					$output = explode('CONNECTION: CLOSE', $output);
					$output = str_replace("\r", " ", $output[1]);
					$output = str_replace("\n", " ", $output);
					$output = ltrim($output, " ");
					$output = rtrim($output, " 0");
					$output = explode(',',$output);
					
					if(is_numeric($output[1]) && is_numeric($output[2]) && is_numeric($output[3])){
						$gQuality = $output[1];
						$lat = $output[2];
						$lon = $output[3];
					} else {
						$failed = 1;
					}//end if
					
					fclose($fp);
				}//end if
			} else {
				$failed = 1;
			}//end if
		}//end if
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "locations WHERE PkID = '" . cIn($lID) . "'");
		
		if(hasRows($result)){
			doQuery("UPDATE " . HC_TblPrefix . "locations
						SET Name = '" . cIn($lName) . "',
							Address = '" . cIn($address) . "',
							Address2 = '" . cIn($address2) . "',
							City = '" . cIn($city) . "',
							State = '" . cIn($state) . "',
							Country = '" . cIn($country) . "',
							Zip = '" . cIn($zip) . "',
							URL = '" . cIn($url) . "',
							Phone = '" . cIn($phone) . "',
							Email = '" . cIn($email) . "',
							Descript = '" . cIn($descript) . "',
							IsPublic = " . cIn($status) . ",
							Lat = '" . cIn($lat) . "',
							Lon = '" . cIn($lon) . "',
							GoogleAcc = '" . cIn($gQuality) . "'
						WHERE PkID = " . cIn($lID));
			
			$msgID = ($failed == 1) ? 5 : 2;
		} else {
			doQuery("INSERT INTO " . HC_TblPrefix . "locations(Name, Address, Address2, City, State, Country, Zip, URL, Phone, Email, Descript, IsPublic, IsActive, Lat, Lon, GoogleAcc)
					VALUES( '" . cIn($lName) . "',
							'" . cIn($address) . "',
							'" . cIn($address2) . "',
							'" . cIn($city) . "',
							'" . cIn($state) . "',
							'" . cIn($country) . "',
							'" . cIn($zip) . "',
							'" . cIn($url) . "',
							'" . cIn($phone) . "',
							'" . cIn($email) . "',
							'" . cIn($descript) . "',
							" . cIn($status) . ",
							1,
							'" . cIn($lat) . "',
							'" . cIn($lon) . "',
							" . cIn($gQuality) . ")");
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "locations");
			$lID = mysql_result($result,0,0);
			
			$msgID = ($failed == 1) ? 4 : 1;
		}//end if
		
		if(isset($_POST['doEventful'])){
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
			if(hasRows($result)){
				if(mysql_result($result,0,0) == '36' && mysql_result($result,0,1) == ''){
					$msgID = 11;
				} else {
					$msgID = ($_POST['efSetting'] == "modify") ? 10 : 9;
					$efRest = "/rest/venues/" . $_POST['efSetting'];
					$efKey = mysql_result($result,0,1);
					$efUser = "";
					$efPass = "";
					if(mysql_result($result,1,1) == ''){
						if(isset($_POST['efUser'])){
							$efUser = $_POST['efUser'];
						}//end if
					} else {
						$efUser = mysql_result($result,1,1);
					}//end if
					if(mysql_result($result,2,1) == ''){
						if(isset($_POST['efPass'])){
							$efPass = $_POST['efPass'];
						}//end if
					} else {
						$efPass = mysql_result($result,2,1);
					}//end if
					
					include('EventfulAddLocation.php');
				}//end if
			}//end if
		}//end if
	} else {
		$msgID = 3;
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
		if(hasRows($result)){
			if(mysql_result($result,0,0) == '36' && mysql_result($result,0,1) != ''){
				$efKey = mysql_result($result,0,1);
				$efUser = mysql_result($result,1,1);
				$efPass = mysql_result($result,2,1);
				if($efUser != '' && $efPass != ''){
					$resultEF = doQuery("SELECT NetworkID FROM " . HC_TblPrefix . "locationnetwork WHERE LocationID = '" . cIn($_GET['dID']) . "'");
					if(hasRows($resultEF)){
						$ip = gethostbyname("api.evdb.com");
						if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
							$msgID = 8;
						} else {
							$efSend = "/rest/venues/withdraw";
							$efSend .= "?app_key=" . $efKey;
							$efSend .= "&user=" . $efUser;
							$efSend .= "&password=" . urlencode($efPass);
							$efSend .= "&id=" . mysql_result($resultEF,0,0);
							
							$request = "GET " . $efSend . " HTTP/1.1\r\n";
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
							
							doQuery("UPDATE " . HC_TblPrefix . "locationnetwork SET IsActive = 0 WHERE NetworkID = '" . mysql_result($resultEF,0,0) . "'");
						}//end if
					}//end if
				}//end if
			}//end if
		}//end if
		doQuery("UPDATE " . HC_TblPrefix . "locations SET IsActive = 0 WHERE PkID = '" . cIn($_GET['dID']) . "'");
		doQuery("UPDATE " . HC_TblPrefix . "events SET LocationName = 'Unknown', LocID = 0 WHERE LocID = '" . cIn($_GET['dID']) . "'");
	}//end if	
	
	$hourOffset = date("G") + ($hc_cfg35);
	$curCache = date("Ymd", mktime($hourOffset,0,0,date("m"),date("d"),date("Y")));
	if(file_exists(realpath('../../events/cache/lmap' . $curCache . '.php'))){
		unlink('../../events/cache/lmap' . $curCache . '.php');
	}//end if
	
	header('Location: ' . CalAdminRoot . '/index.php?com=location&msg=' . $msgID);?>