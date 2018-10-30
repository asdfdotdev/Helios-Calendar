<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include(dirname(__FILE__).'/loader.php');
	
	action_headers();
	
	$GUID = (isset($_GET['a'])) ? cIn(strip_tags($_GET['a'])) : '';
	$result = DoQuery("SELECT * FROM " . HC_TblPrefix . "subscribers WHERE GUID = ?", array($GUID));
	if(hasRows($result) && hc_mysql_result($result,0,6) == 0){
		DoQuery("UPDATE " . HC_TblPrefix . "subscribers SET IsConfirm = 1 WHERE PkID = ?", array(cIn(hc_mysql_result($result,0,0))));
		header('Location: '.CalRoot."/index.php?com=signup&t=3");
	} else {
		header('Location: '.CalRoot.'/');
	}
?>