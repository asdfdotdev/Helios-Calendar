<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include(dirname(__FILE__).'/loader.php');
	
	action_headers();
	
	if(isset($_GET['l']))
		$_SESSION['hc_favCat'] = implode(',',array_filter(explode(',',$_GET['l']),'is_numeric'));
	
	if(isset($_GET['c']))
		$_SESSION['hc_favCity'] = array_map('cIn',array_map('strip_tags',explode(',',str_replace("_"," ",$_GET['c']))));
	
	header('Location: '.CalRoot.'/');
?>
