<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('../loader.php');
	
	admin_logged_in();
	action_headers();
	
	$pID = (isset($_GET['pID']) && is_numeric($_GET['pID'])) ? cIn(strip_tags($_GET['pID'])) : 0;

	$result = doQuery("SELECT TemplateSource FROM " . HC_TblPrefix . "templatesnews WHERE PkID = '" . $pID . "'");
	echo (hasRows($result)) ? cOut(mysql_result($result,0,0)) : $hc_lang_news['InvalidTemplate'];
?>