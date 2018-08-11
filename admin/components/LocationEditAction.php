<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	if(!isset($_GET['dID'])){
		post_only();
		
		$lID = (isset($_POST['lID']) && is_numeric($_POST['lID'])) ? cIn(strip_tags($_POST['lID'])) : 0;
		$name =  isset($_POST['name']) ? cIn(cleanQuotes($_POST['name'])) : '';
		$address =  isset($_POST['address']) ? cIn(strip_tags($_POST['address'])) : '';
		$address2 =  isset($_POST['address2']) ? cIn(strip_tags($_POST['address2'])) : '';
		$city =  isset($_POST['city']) ? cIn(strip_tags($_POST['city'])) : '';
		$state =  isset($_POST['locState']) ? cIn(strip_tags($_POST['locState'])) : '';
		$country = (isset($_POST['selCountry']) && $_POST['selCountry'] != '') ? cIn(strip_tags($_POST['selCountry'])) : cIn(strip_tags($_POST['country']));
		$zip =  isset($_POST['zip']) ? cIn(strip_tags($_POST['zip'])) : '';
		$website = isset($_POST['website']) ? cIn(strip_tags($_POST['website'])) : '';
		$website = (preg_match('/^https?:\/\//',$website) || $website == '') ? $website : 'http://'.$website;
		$email =  isset($_POST['email']) ? cIn(strip_tags($_POST['email'])) : '';
		$phone =  isset($_POST['phone']) ? cIn(strip_tags($_POST['phone'])) : '';
		$status =  isset($_POST['status']) ? cIn(strip_tags($_POST['status'])) : '';
		$descript =  isset($_POST['descript']) ? cIn(cleanQuotes($_POST['descript'],0)) : '';
		$lat =  isset($_POST['lat']) ? cIn(strip_tags($_POST['lat'])) : '';
		$lon =  isset($_POST['lat']) ? cIn(strip_tags($_POST['lon'])) : '';
		$gQuality = (isset($_POST['gStatus']) && $_POST['gStatus'] != '') ? cIn(strip_tags($_POST['gStatus'])) : 0;
		$follow_up = isset($_POST['follow_up']) ? cIn($_POST['follow_up']) : 0;
		$fnote = isset($_POST['follow_note']) ? cIn(cleanQuotes($_POST['follow_note'])) : '';
		
		if(isset($_POST['updateMap'])){
			$locString = str_replace("<br />",", ",buildAddress($address,$address2,$city,$state,$zip,$country,$hc_lang_config['AddressType']));
			require_once(HCPATH.HCINC.'/api/google/GetGeocode.php');}
		
		$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "locations WHERE PkID = '" . $lID . "'");
		if(hasRows($result)){
			doQuery("UPDATE " . HC_TblPrefix . "locations
					SET Name = '" . $name . "',
						Address = '" . $address . "',Address2 = '" . $address2 . "',City = '" . $city . "',State = '" . $state . "',Country = '" . $country . "',Zip = '" . $zip . "',
						URL = '" . $website . "',Phone = '" . $phone . "',Email = '" . $email . "',Descript = '" . $descript . "',
						IsPublic = '" . $status . "',Lat = '" . $lat . "',Lon = '" . $lon . "',GoogleAcc = '" . $gQuality . "'
					WHERE PkID = '" . $lID . "'");
			$msgID = 2;
		} else {
			doQuery("INSERT INTO " . HC_TblPrefix . "locations(Name, Address, Address2, City, State, Country, Zip, URL, Phone, Email, Descript, IsPublic, IsActive, Lat, Lon, GoogleAcc)
					VALUES(	'" . $name . "','" . $address . "','" . $address2 . "','" . $city . "','" . $state . "','" . $country . "','" . $zip . "',
							'" . $website . "','" . $phone . "','" . $email . "','" . $descript . "'," . $status . ",1,'" . $lat . "','" . $lon . "','" . $gQuality . "')");
			$result = doQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "locations");
			$lID = mysql_result($result,0,0);
			$msgID = 1;
		}
		
		$efID = $ebID = $efFetched = '';
		$efNew = $ebNew = true;
		$resultD = doQuery("SELECT * FROM " . HC_TblPrefix . "locationnetwork WHERE LocationID = '" . $lID . "'");
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
				}
			}
		}
		$entityID = $lID;
		$entityType = 3;
		if($follow_up > 0){
			$resultF = doQuery("SELECT * FROM " . HC_TblPrefix . "followup WHERE EntityID = '" . cIn($entityID) . "' AND EntityType = '" . cIn($entityType) . "'");
			if(hasRows($resultF)){
				doQuery("UPDATE " . HC_TblPrefix . "followup SET Note = '".$fnote."' WHERE EntityID = '" . cIn($entityID) . "' AND EntityType = '" . cIn($entityType) . "'");
			} else {
				doQuery("INSERT INTO " . HC_TblPrefix . "followup(EntityID, EntityType, Note) VALUES('".$entityID."','".$entityType."','".$fnote."')");
			}
		} else {
			doQuery("DELETE FROM " . HC_TblPrefix . "followup WHERE EntityID = '" . cIn($entityID) . "' AND EntityType = '" . cIn($entityType) . "'");
		}
		if(isset($_POST['doEventful'])){
			if($efID == '')
				require_once(HCPATH.HCINC.'/api/eventful/LocationFetch.php');
			
			if($efFetched == '')
				require_once(HCPATH.HCINC.'/api/eventful/LocationEdit.php');
			
			if($efNew == true && $efFetched == '')
				doQuery("INSERT INTO " . HC_TblPrefix . "locationnetwork(LocationID,NetworkID,NetworkType,IsDownload,IsActive)
						VALUES('" . $lID . "','" . cIn($efID) . "',1,0,1);");	
		}
		if(isset($_POST['doEventbrite'])){
			$country_code = (isset($_POST['selCountry'])) ? cIn($_POST['selCountry']) : '';
			require_once(HCPATH.HCINC.'/api/eventbrite/LocationEdit.php');
			
			if($ebNew == true){
				doQuery("INSERT INTO " . HC_TblPrefix . "locationnetwork(LocationID,NetworkID,NetworkType,IsDownload,IsActive)
						VALUES('" . $lID . "','" . cIn($ebID) . "',2,0,1);");
			}
		}
		if(isset($_POST['doBitly'])){
			$shortLink = CalRoot . "/index.php?com=location&lID=" . $lID;
			require(HCPATH.HCINC.'/api/bitly/ShortenURL.php');
		}

		$hdrStr = 'Location: ' . AdminRoot . '/index.php?com=addlocation&lID=' . $lID . '&msg=' . $msgID;
	} else {
		$hdrStr = 'Location: ' . AdminRoot . '/index.php?com=location&lID=&msg=3';
		
		$dID = cIn(strip_tags($_GET['dID']));
		doQuery("UPDATE " . HC_TblPrefix . "locations SET IsActive = 0 WHERE PkID = '" . $dID . "'");
		doQuery("UPDATE " . HC_TblPrefix . "events SET LocID = 0 WHERE LocID = '" . $dID . "'");

		$resultD = doQuery("SELECT NetworkID, NetworkType FROM " . HC_TblPrefix . "locationnetwork WHERE LocationID = '" . $dID . "' ORDER BY NetworkType");
		while($row = mysql_fetch_row($resultD)){
			$netID = cIn($row[0]);
			if($row[1] == 1){
				require_once(HCPATH.HCINC.'/api/eventful/LocationDelete.php');
			} elseif($row[1] == 2){
				//	Eventbrite doesn't currently support API Venue deletion. When they add support the following file may work.
				//	require_once(HCPATH.HCINC.'/api/eventbrite/LocationDelete.php');
				doQuery("UPDATE " . HC_TblPrefix . "locationnetwork SET IsActive = 0 WHERE NetworkID = '" . $netID . "' AND NetworkType = 2");
			}
		}
	}
	
	clearCache();
	
	header($hdrStr);
?>