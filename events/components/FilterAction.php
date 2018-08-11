<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	$isAction = 1;
	include('../includes/include.php');

	if(!isset($_GET['clear']) || isset($_POST['catID']) || isset($_POST['catIDf']) || isset($_POST['cityName'])){
		$catIDs = "0";

		if(isset($_POST['catID']) || isset($_POST['catIDf'])){
			$catID = (isset($_POST['catID'])) ? $_POST['catID'] : $_POST['catIDf'];
			foreach ($catID as $val){
				$catIDs .= (is_numeric($val)) ? ',' . cleanXMLChars(strip_tags(cIn($val))) : '';
			}//end for
		} else {
			unset($_SESSION['hc_favCat']);
		}//end if
		
		$cityNames = '';
		if(isset($_POST['cityName'])){
			$cityName = $_POST['cityName'];
			$cityNames = '';
			foreach ($cityName as $val){
				$cityNames .= ($cityNames != '') ? "," . "'" . strip_tags(cIn($val)) . "'" : "'" . strip_tags(cIn($val)) . "'";
			}//end for
		} else {
			unset($_SESSION['hc_favCity']);
		}//end if
		
		$_SESSION['hc_favCat'] = ($catIDs != '0') ? $catIDs : NULL;
		$_SESSION['hc_favCity'] = ($cityNames != "") ? $cityNames : NULL;

		if(isset($_POST['cookieme'])){
			if($catIDs != "0"){
				setcookie($hc_cfg00p . '_fn', base64_encode($catIDs), time()+604800, '/', false, 0);
			}//end if
			if($cityNames != ''){
				setcookie($hc_cfg00p . '_fc', base64_encode($cityNames), time()+604800, '/', false, 0);
			}//end if
		} else {
			setcookie($hc_cfg00p . '_fn', "", time()-86400, '/', false, 0);
			setcookie($hc_cfg00p . '_fc', "", time()-86400, '/', false, 0);
		}//end if
		
		$msgID = 1;
		
	} else {
		setcookie($hc_cfg00p . '_fn', "", time()-86400, '/', false, 0);
		setcookie($hc_cfg00p . '_fc', "", time()-86400, '/', false, 0);
		unset($_SESSION['hc_favCat']);
		unset($_SESSION['hc_favCity']);
		
		$msgID = 2;
	}//end if
	
	$destination = (isset($_POST['r']) || isset($_GET['r'])) ? CalRoot . '/index.php' : CalRoot . '/index.php?com=filter&msg=' . $msgID;
	header("Location: " . $destination);
?>