<?php
/**
 * @package Helios Calendar
 * @license GNU General Public License version 2 or later; see LICENSE
 */
	define('hcAdmin',true);
	include('loader.php');
	
	admin_logged_in();
	action_headers();
	
	killAdminSession();
	startNewSession();
	
	header('Location: ' . AdminRoot);
?>