<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	$token = (isset($_GET['tkn'])) ? cIn(strip_tags($_GET['tkn'])) : '';
	if(!check_form_token($token))
		go_home();
	
	$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? cIn(strip_tags($_GET['eID'])) : 0;
	DoQuery("UPDATE " . HC_TblPrefix . "events SET IsBillboard = ? WHERE PkID = ?", array(0, $eID));
	
	clearCache();
	
	header('Location: ' . AdminRoot . '/index.php?com=eventbillboard&msg=1');
?>