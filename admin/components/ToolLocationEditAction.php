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
				if(!($fp = fsockopen("api.local.yahoo.com", 80, $errno, $errstr, 1)) ){
					$msg = 4;
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
				$msg = 6;
			} else {
				$msg = 2;
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
			if($failed == 1){
				$msg = 5;
			} else {
				$msg = 1;
			}//end if
		}//end if
		
		header('Location: ' . CalAdminRoot . '/index.php?com=location&msg=' . $msg);
	} else {
		doQuery("DELETE FROM " . HC_TblPrefix . "locations WHERE PkID = '" . cIn($_GET['dID']) . "'");
		header('Location: ' . CalAdminRoot . '/index.php?com=location&msg=3');
	}//end if	?>