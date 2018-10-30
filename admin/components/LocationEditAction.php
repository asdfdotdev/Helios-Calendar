<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	$token = '';
	$token = ($token == '' && isset($_POST['token'])) ? cIn(strip_tags($_POST['token'])) : $token;
	$token = ($token == '' && isset($_GET['tkn'])) ? cIn(strip_tags($_GET['tkn'])) : $token;
	
	if(!check_form_token($token))
		go_home();
	
	$apiFail = false;
	
	if(!isset($_GET['dID'])){
		post_only();
		
		$lID = (isset($_POST['lID']) && is_numeric($_POST['lID'])) ? cIn(strip_tags($_POST['lID'])) : 0;
		$name =  isset($_POST['name']) ? cIn(cleanQuotes($_POST['name'])) : '';
		$address =  isset($_POST['address']) ? cIn(strip_tags($_POST['address'])) : '';
		$address2 =  isset($_POST['address2']) ? cIn(strip_tags($_POST['address2'])) : '';
		$city =  isset($_POST['city']) ? cIn(strip_tags($_POST['city'])) : '';
		$state =  isset($_POST['locState']) ? cIn(strip_tags($_POST['locState'])) : '';
		$country = (isset($_POST['doEventbrite']) && isset($_POST['selCountry']) && $_POST['selCountry'] != '') ? cIn(strip_tags($_POST['selCountry'])) : cIn(strip_tags($_POST['country']));
		$zip =  isset($_POST['zip']) ? cIn(strip_tags($_POST['zip'])) : '';
		$website = isset($_POST['website']) ? cIn(strip_tags($_POST['website'])) : '';
		$website = (preg_match('/^https?:\/\//',$website) || $website == '') ? $website : 'http://'.$website;
		$email =  isset($_POST['email']) ? cIn(strip_tags($_POST['email'])) : '';
		$phone =  isset($_POST['phone']) ? cIn(strip_tags($_POST['phone'])) : '';
		$status =  isset($_POST['status']) ? cIn(strip_tags($_POST['status'])) : '';
		$descript =  isset($_POST['descript']) ? cIn(cleanQuotes($_POST['descript'],0)) : '';
		$lat =  isset($_POST['lat']) ? cIn(strip_tags($_POST['lat'])) : '';
		$lon =  isset($_POST['lat']) ? cIn(strip_tags($_POST['lon'])) : '';
		$gQuality = '0';
		$imageURL = (isset($_POST['imageURL'])) ? cIn($_POST['imageURL']) : '';
		$imageURL = (preg_match('/^https?:\/\//',$imageURL) || $imageURL == '') ? $imageURL : 'http://'.$imageURL;
		$follow_up = isset($_POST['follow_up']) ? cIn($_POST['follow_up']) : 0;
		$fnote = isset($_POST['follow_note']) ? cIn(cleanQuotes($_POST['follow_note'])) : '';
		
		if(isset($_POST['updateMap'])){
			$locString = str_replace("<br />",", ",buildAddress($address,$address2,$city,$state,$zip,$country,$hc_lang_config['AddressType']));
			require_once(HCPATH.HCINC.'/api/google/GetGeocode.php');}
		
		$result = DoQuery("SELECT PkID FROM " . HC_TblPrefix . "locations WHERE PkID = ? AND IsActive = 1", array($lID));
		if(hasRows($result)){
			DoQuery("UPDATE " . HC_TblPrefix . "locations
					SET Name = ?,
						Address = ?,Address2 = ?,City = ?,State = ?,Country = ?,Zip = ?,
						URL = ?,Phone = ?,Email = ?,Descript = ?,
						IsPublic = ?,Lat = ?,Lon = ?,GoogleAcc = ?, LastMod = '" . SYSDATE . ' ' . SYSTIME . "',
						Image = ?
					WHERE PkID = ?", 
					array(
						$name,
						$address,
						$address2,
						$city,
						$state,
						$country,
						$zip,
						$website,
						$phone,
						$email,
						$descript,
						$status,
						$lat,
						$lon,
						$gQuality,
						$imageURL,
						$lID 
					));
			$msgID = 2;
		} else {
			DoQuery("INSERT INTO " . HC_TblPrefix . "locations(Name, Address, Address2, City, State, Country, Zip, URL, Phone, Email, Descript, IsPublic, IsActive, Lat, Lon, GoogleAcc, LastMod, Image)
					VALUES(	?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?, '" . SYSDATE . ' ' . SYSTIME . "', ?)",
					array(
						$name,
						$address,
						$address2,
						$city,
						$state,
						$country,
						$zip,
						$website,
						$phone,
						$email,
						$descript,
						$status,
						$lat,
						$lon,
						$gQuality,
						$imageURL
					));
			$result = DoQuery("SELECT LAST_INSERT_ID() FROM " . HC_TblPrefix . "locations");
			$lID = hc_mysql_result($result,0,0);
			$msgID = 1;
		}
		
		$efID = $ebID = $efFetched = '';
		$efNew = $ebNew = true;
		$resultD = DoQuery("SELECT * FROM " . HC_TblPrefix . "locationnetwork WHERE LocationID = ?", array($lID));
		if(hasRows($resultD)){
			while($row = hc_mysql_fetch_row($resultD)){
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
			$resultF = DoQuery("SELECT * FROM " . HC_TblPrefix . "followup WHERE EntityID = ?", array(cIn($entityID) . "' AND EntityType = '" . cIn($entityType)));
			if(hasRows($resultF)){
				DoQuery("UPDATE " . HC_TblPrefix . "followup SET Note = ? WHERE EntityID = ? AND EntityType = ?", array($fnote, cIn($entityID), cIn($entityType)));
			} else {
				DoQuery("INSERT INTO " . HC_TblPrefix . "followup(EntityID, EntityType, Note) VALUES(?,?,?)", array($entityID, $entityType, $fnote));
			}
		} else {
			DoQuery("DELETE FROM " . HC_TblPrefix . "followup WHERE EntityID = ?", array(cIn($entityID) . "' AND EntityType = '" . cIn($entityType)));
		}
		if(isset($_POST['doEventbrite'])){
			$ebNew = ($ebID == '') ? true : false;
			
			require_once(HCPATH.HCINC.'/api/eventbrite/LocationEdit.php');
			
			if($ebID != '' && $ebNew == true){
				DoQuery("INSERT INTO " . HC_TblPrefix . "locationnetwork(LocationID,NetworkID,NetworkType,IsDownload,IsActive)
						VALUES(?,?,2,0,1)", array($lID, cIn($ebID)));
			}
		}
		if(isset($_POST['doBitly'])){
			$shortLink = CalRoot . "/index.php?com=location&lID=" . $lID;
			require(HCPATH.HCINC.'/api/bitly/ShortenURL.php');
		}

		$hdrStr = 'Location: ' . AdminRoot . '/index.php?com=addlocation&lID=' . $lID . '&msg=' . $msgID;
	} else {
		if(isset($_GET['dpID']))
			$hdrStr = 'Location: ' . AdminRoot . '/index.php?com=reportdupl&msg=1';
		else
			$hdrStr = 'Location: ' . AdminRoot . '/index.php?com=location&lID=&msg=3';
		
		$dID = cIn(strip_tags($_GET['dID']));
		DoQuery("UPDATE " . HC_TblPrefix . "locations SET IsActive = ? WHERE PkID = ?", array(0, $dID));
		DoQuery("UPDATE " . HC_TblPrefix . "events SET LocID = ? WHERE LocID = ?", array(0, $dID));

		$resultD = DoQuery("SELECT NetworkID, NetworkType FROM " . HC_TblPrefix . "locationnetwork WHERE LocationID = ? ORDER BY NetworkType", array($dID));
		while($row = hc_mysql_fetch_row($resultD)){
			$netID = cIn($row[0]);
			if($row[1] == 1){
				//	Nothing
			} elseif($row[1] == 2){
				//	Eventbrite doesn't support API Venue deletion.
				DoQuery("UPDATE " . HC_TblPrefix . "locationnetwork SET IsActive = 0 WHERE NetworkID = ? AND NetworkType = 2", array($netID));
			}
		}
	}
	
	clearCache();
	
	if($apiFail != false)
		exit();
	
	header($hdrStr);
?>