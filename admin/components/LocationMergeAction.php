<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	post_only();
	
	$token = (isset($_POST['token'])) ? cIn(strip_tags($_POST['token'])) : '';
	if(!check_form_token($token))
		go_home();
	
	$locIDs = array_filter(explode(',',$_POST['locIDs']),'is_numeric');
	$locIDs = cIn(implode(',',$locIDs));
	$msgID = 4;
	
	if(is_numeric($_POST['mergeID'][0])){
		$msgID = 5;
		DoQuery("UPDATE " . HC_TblPrefix . "events SET LocID = ? WHERE LocID IN (?)", array(cIn($_POST['mergeID'][0]), $locIDs));
		DoQuery("UPDATE " . HC_TblPrefix . "locations SET IsActive = 0 WHERE PkID IN (?) AND PkID != ?", array($locIDs, cIn($_POST['mergeID'][0])));
	}
	
	clearCache();
	
	header('Location: ' . AdminRoot . '/index.php?com=location&msg=' . $msgID);
?>