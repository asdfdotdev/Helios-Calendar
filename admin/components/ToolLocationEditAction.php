<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	include('../../events/includes/include.php');
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