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
	
	$seriesString = implode(',',array_filter($_POST['eventID'],'is_numeric'));
	$seriesID = DecHex(microtime() * 9999999) . DecHex(microtime() * 5555555) . DecHex(microtime() * 1111111);
	
	doQuery("UPDATE " . HC_TblPrefix . "events SET SeriesID = '" . $seriesID . "' WHERE PkID IN (" . $seriesString . ")");
	
	header("Location: " . AdminRoot . "/index.php?com=searchresults&srsID=" . $seriesID . "&msg=1");
?>