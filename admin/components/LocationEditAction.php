<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	
	if(!isset($_GET['dID'])){
		$lID = $_POST['lID'];
		$lName = cleanQuotes($_POST['lName']);
		$address = $_POST['address'];
		$address2 = $_POST['address2'];
		$city = $_POST['city'];
		$state = $_POST['locState'];
		$country = (isset($_POST['selCountry']) && $_POST['selCountry'] != '') ? $_POST['selCountry'] : $_POST['country'];
		$zip = $_POST['zip'];
		$url = (strrpos($_POST['url'],"http://") === 0) ? $_POST['url'] : "http://" . $_POST['url'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$status = $_POST['status'];
		$descript = cleanQuotes($_POST['descript'],0);
		$lat = $_POST['lat'];
		$lon = $_POST['lon'];
		$gQuality = (isset($_POST['gStatus']) && $_POST['gStatus'] != '') ? cIn($_POST['gStatus']) : 0;
		
		
		if(isset($_POST['updateMap'])){
			require_once('../../events/includes/api/google/GetGeocode.php');
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
							Descript = '" . cIn($descript,0) . "',
							IsPublic = " . cIn($status) . ",
							Lat = '" . cIn($lat) . "',
							Lon = '" . cIn($lon) . "',
							GoogleAcc = '" . cIn($gQuality) . "'
						WHERE PkID = " . cIn($lID));
			
			$msgID = 2;
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
			
			$msgID = 1;
		}//end if
		
		$efID = $ebID = $efFetched = '';
		$efNew = $ebNew = true;
		$resultD = doQuery("SELECT * FROM " . HC_TblPrefix . "locationnetwork WHERE LocationID = '" . cIn($lID) . "'");
		if(hasRows($resultD)){
			while($row = mysql_fetch_row($resultD)){
				switch($row[2]){
					case 1:
						$efNew = false;
						$efID = $row[1];
						break;
					case 2:
						$ebNew = false;
						$ebID = $row[1];
						break;
				}//end if
			}//end while
		}//end if

		if(isset($_POST['doEventful'])){
			if($efID == ''){
				require_once('../../events/includes/api/eventful/LocationFetch.php');
			}//end if
			
			if($efFetched == ''){
				require_once('../../events/includes/api/eventful/LocationEdit.php');
			}//end if

			if($efNew == true && $efFetched == ''){
				doQuery("INSERT INTO " . HC_TblPrefix . "locationnetwork(LocationID,NetworkID,NetworkType,IsDownload,IsActive)
						VALUES('" . $lID . "','" . cIn($efID) . "',1,0,1);");
			}//end if
		}//end if

		if(isset($_POST['doEventbrite'])){
			$country_code = (isset($_POST['selCountry'])) ? cIn($_POST['selCountry']) : '';
			require_once('../../events/includes/api/eventbrite/LocationEdit.php');
			
			if($ebNew == true){
				doQuery("INSERT INTO " . HC_TblPrefix . "locationnetwork(LocationID,NetworkID,NetworkType,IsDownload,IsActive)
						VALUES('" . $lID . "','" . cIn($ebID) . "',2,0,1);");
			}//end if
		}//end if

		$hdrStr = 'Location: ' . CalAdminRoot . '/index.php?com=addlocation&lID=' . $lID . '&msg=' . $msgID;
	} else {
		$hdrStr = 'Location: ' . CalAdminRoot . '/index.php?com=location&lID=&msg=3';

		doQuery("UPDATE " . HC_TblPrefix . "locations SET IsActive = 0 WHERE PkID = '" . cIn($_GET['dID']) . "'");
		doQuery("UPDATE " . HC_TblPrefix . "events SET LocationName = 'Unknown', LocID = 0 WHERE LocID = '" . cIn($_GET['dID']) . "'");

		$resultD = doQuery("SELECT NetworkID, NetworkType FROM " . HC_TblPrefix . "locationnetwork WHERE LocationID = '" . cIn($_GET['dID']) . "' ORDER BY NetworkType");
		while($row = mysql_fetch_row($resultD)){
			$netID = $row[0];
			if($row[1] == 1){
				include('../../events/includes/api/eventful/LocationDelete.php');
			} elseif($row[1] == 2){
				//	Eventbrite doesn't support deleting Venue's via their API
				//	If/when they add support for it the code in the following file may work.
				//	include('../../events/includes/api/eventbrite/LocationDelete.php');
				doQuery("UPDATE " . HC_TblPrefix . "locationnetwork SET IsActive = 0 WHERE NetworkID = '" . $netID . "' AND NetworkType = 2");
			}//end if
		}//end if
	}//end if	
	
	$hourOffset = date("G") + ($hc_cfg35);
	$curCache = date("Ymd", mktime($hourOffset,0,0,date("m"),date("d"),date("Y")));
	if(file_exists(realpath('../../events/cache/lmap' . $curCache . '.php'))){
		unlink('../../events/cache/lmap' . $curCache . '.php');
	}//end if
	
	header($hdrStr);?>