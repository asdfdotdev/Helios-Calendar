<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('isHC',true);
	define('isAction',true);
	include(dirname(__FILE__).'/loader.php');
	
	action_headers();
	
	if(!user_check_status())
		go_home();
	
	user_kill_session();
	user_new_session();
	
	header('Location: ' . CalRoot);
?>