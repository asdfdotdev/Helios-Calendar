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

	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/config.php');
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/admin/login.php');
	
	$email = cIn($_POST['email']);
	$result = doQuery("SELECT FirstName, LastName, Email, Passwrd FROM " . HC_TblPrefix . "admin WHERE email = '" . cIn($email) ."' AND IsActive = 1");
	
	if(hasRows($result)){
		$pwKey = md5(date("U"));
		
		doQuery("UPDATE " . HC_TblPrefix . "admin SET PCKey = '" . cIn($pwKey) . "' WHERE Email = '" . $email . "'");
		
		$subject = CalName . ' ' . $hc_lang_login['LoginSubject'];
		$message = '<a href="' . CalAdminRoot . '/index.php?lp=2&k=' . $pwKey . '">'  . CalAdminRoot . '/index.php?lp=2&k=' . $pwKey . '</a>';
		$message .= '<p>' . $hc_lang_login['LoginEmail'] . ' <b>' . $_SERVER["REMOTE_ADDR"] . "</b></p>";

		reMail(trim(mysql_result($result,0,0) . ' ' . mysql_result($result,0,1)), mysql_result($result,0,2), $subject, $message, $hc_cfg79, $hc_cfg78);
		
		header('Location: ' . CalAdminRoot . '/?lmsg=3');
	} else {
		header('Location: ' . CalAdminRoot . '/');
	}//end if	?>
