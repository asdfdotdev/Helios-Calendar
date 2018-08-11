<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	$isAction = 1;
	include('../includes/include.php');
	
	$a = (isset($_POST['a'])) ? cIn(strip_tags($_POST['a'])) : 0;
	$k = (isset($_POST['k'])) ? cIn(strip_tags($_POST['k'])) : 0;
	$pass1 = (isset($_POST['pass1'])) ? cIn(strip_tags($_POST['pass1'])) : '';
	$pass2 = (isset($_POST['pass2'])) ? cIn(strip_tags($_POST['pass2'])) : '';
	$target = 'Location: ' . CalAdminRoot . '/';

	$result = doQuery("SELECT PkID, Email FROM " . HC_TblPrefix . "admin WHERE PkID = '" . $a . "' AND PCKey = '" . $k . "'");
	if(hasRows($result)){
		$target = 'Location: ' . CalAdminRoot . '/?lmsg=5';
		if($pass1 == $pass2){
			doQuery("UPDATE " . HC_TblPrefix . "admin SET Passwrd = '" . md5(md5($pass1) . mysql_result($result,0,1)) . "', PCKey = NULL WHERE PkID = '" . mysql_result($result,0,0) . "'");
			$target = 'Location: ' . CalAdminRoot . '/?lmsg=4';
		}//end if
	}//end if

	header($target);
?>