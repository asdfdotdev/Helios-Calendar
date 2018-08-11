<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	$nID = (isset($_GET['n']) && is_numeric($_GET['n'])) ? cIn(strip_tags($_GET['n'])) : 0;
	$do =  (isset($_GET['d']) && is_numeric($_GET['d'])) ? cIn(strip_tags($_GET['d'])) : 0;
	$go = (isset($_GET['g'])) ? cIn($_GET['g']) : 0;

	switch($do){
		case 1:
			doQuery("UPDATE " . HC_TblPrefix . "newsletters SET Status = 1 WHERE PkID = '" . $nID . "' AND Status != 3");
			break;
		case 2:
			doQuery("UPDATE " . HC_TblPrefix . "newsletters SET Status = 2 WHERE PkID = '" . $nID . "' AND Status != 3");
			break;
	}

	if($go == 1)
		header("Location: " . AdminRoot . "/index.php?com=newsqueue&msg=3");
?>
