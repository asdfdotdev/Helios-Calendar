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
		
		$failed = 0;
		
		if( !ereg("^http://*", $url, $regs) ){
		   $contactURL = "http://" . $url;
		}//end if
		
		$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID = 28");
		$yahooAPI = mysql_result($result,0,0);
		$failed = 0;
		if(isset($_POST['updateMap'])){
			if($yahooAPI != ''){
				$ip = gethostbyname('api.local.yahoo.com');
				if(!($fp = fsockopen($ip, 80, $errno, $errstr, 1)) ){
					$msgID = 4;
				} else {
					$read = "";
					$request = "GET /MapsService/V1/geocode?appid=" . $yahooAPI . "&street=" . urlencode($address) . "&city=" . urlencode($city) . "&state=" . urlencode($state) . "&zip=" . urlencode($zip) . " HTTP/1.1\r\n";
					$request .= "Host: api.local.yahoo.com\r\n";
					$request .= "Connection: Close\r\n\r\n";
					fwrite($fp, $request);
					
					while (!feof($fp)) {
						$read .= fread($fp,1024);
					}//end while
					
					$output = explode('<?', $read);
					$output = "<?" . $output[1];
					
					$getLat = explode('<Latitude>', $output);
					if(isset($getLat[1])){
						$getLat = explode('</Latitude>', $getLat[1]);
						$lat = $getLat[0];
					} else {
						$failed = 1;
					}//end if
					
					$getLon = explode('<Longitude>', $output);
					if(isset($getLon[1])){
						$getLon = explode('</Longitude>', $getLon[1]);
						$lon = $getLon[0];
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
							Lon = '" . cIn($lon) . "'
						WHERE PkID = " . cIn($lID));
			if($failed == 1){
				$msgID = 6;
			} else {
				$msgID = 2;
			}//end if
		} else {
			doQuery("INSERT INTO " . HC_TblPrefix . "locations(Name, Address, Address2, City, State, Country, Zip, URL, Phone, Email, Descript, IsPublic, IsActive, Lat, Lon)
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
							'" . cIn($lon) . "')");
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "locations");
			$lID = mysql_result($result,0,0);
			
			if($failed == 1){
				$msgID = 5;
			} else {
				$msgID = 1;
			}//end if
		}//end if
		
		if(isset($_POST['doEventful'])){
			$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
			if(hasRows($result)){
				if(mysql_result($result,0,0) == '36' && mysql_result($result,0,1) == ''){
					$msgID = 11;
				} else {
					if($_POST['efSetting'] == "modify"){
						$msgID = 10;
					} else {
						$msgID = 9;
					}//end if
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
		
		header('Location: ' . CalAdminRoot . '/index.php?com=location&msg=' . $msgID);
	} else {
		$msgID = 3;
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "settings WHERE PkID IN(36,37,38,39);");
		if(hasRows($result)){
			if(mysql_result($result,0,0) == '36' && mysql_result($result,0,1) != ''){
				$efKey = mysql_result($result,0,1);
				$efUser = mysql_result($result,1,1);
				$efPass = mysql_result($result,2,1);
				$msgID = 7;
				
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
		header('Location: ' . CalAdminRoot . '/index.php?com=location&msg=' . $msgID);
	}//end if	?>