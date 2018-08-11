<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include(dirname(__FILE__).'/loader.php');
	
	$eID = (isset($_GET['eID']) && is_numeric($_GET['eID'])) ? cIn(strip_tags($_GET['eID'])) : 0;
	
	if(user_check_status() == 0 || !$eID > 0)
		go_home();
	
	$result = doQuery("SELECT Title FROM " . HC_TblPrefix . "events WHERE PkID = '" . $eID . "' AND OwnerID = '" . cIn(strip_tags($_SESSION['UserPkID'])) . "'");
	
	if(!hasRows($result))
		go_home();
	
	header('Content-type: application/csv');
	header('Content-Disposition: inline; filename="'.clean_filename(cleanQuotes(strip_tags(mysql_result($result,0,0)))).'.csv"');
	
	echo fetch_event_rsvp($eID,$hc_lang_core['RSVPHeader']);
?>
