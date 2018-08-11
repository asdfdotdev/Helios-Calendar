<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('loader.php');
	
	admin_logged_in();
	action_headers();
	
	$_SESSION['Instructions'] = ($_SESSION['Instructions'] == 1) ? 0 : 1;	
	doQuery("UPDATE " . HC_TblPrefix . "admin SET ShowInstructions = '" . cIn($_SESSION['Instructions']) . "' WHERE PkID = '" . cIn($_SESSION['AdminPkID']) . "'");
	
	if(isset($_SERVER['HTTP_REFERER']) && preg_match('(^'.AdminRoot.')',$_SERVER['HTTP_REFERER']))
		header("Location: " . cIn(strip_tags($_SERVER['HTTP_REFERER'])));
	else
		header("Location: " . AdminRoot);
?>