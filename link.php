<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
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
