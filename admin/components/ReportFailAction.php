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
	
	$dID = (isset($_GET['dID']) && is_numeric($_GET['dID'])) ? cIn($_GET['dID']) : '';
	$target = AdminRoot . '/index.php?com=reportfail';
	
	if($dID > 0){
		doQuery("DELETE FROM " . HC_TblPrefix . "adminloginhistory WHERE PkID = '" . $dID . "'");
		$target = AdminRoot . '/index.php?com=reportfail&msg=1';
	}
	
	header("Location: " . $target);
?>