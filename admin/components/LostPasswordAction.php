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
	define('hcAdmin',true);
	include('../loader.php');
	
	action_headers();
	post_only();
	
	include(HCLANG.'/admin/login.php');
	
	$proof = $challenge = '';
	if($hc_cfg[65] == 1){
		$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
		$challenge = isset($_SESSION['hc_cap']) ? $_SESSION['hc_cap'] : NULL;
	} elseif($hc_cfg[65] == 2){
		$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
		$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
	}
	spamIt($proof,$challenge,0);
	
	$email = isset($_POST['email']) ? cIn($_POST['email']) : '';
	$result = doQuery("SELECT FirstName, LastName, Email, Passwrd FROM " . HC_TblPrefix . "admin WHERE email = '" . $email ."' AND IsActive = 1");
	
	if(hasRows($result)){
		$pwKey = md5(date("U").md5(date("U")));
		
		doQuery("UPDATE " . HC_TblPrefix . "admin SET PCKey = '" . cIn($pwKey) . "' WHERE Email = '" . $email . "'");
		
		$subject = CalName . ' ' . $hc_lang_login['LoginSubject'];
		$message = '<a href="' . AdminRoot . '/index.php?lp=2&k=' . $pwKey . '">'  . AdminRoot . '/index.php?lp=2&k=' . $pwKey . '</a>';
		$message .= '<br /><br />' . $hc_lang_login['LoginEmail'] . ' <b>' . $_SERVER["REMOTE_ADDR"] . "</b>";
		
		reMail(trim(mysql_result($result,0,0).' '.mysql_result($result,0,1)), mysql_result($result,0,2), $subject, $message, $hc_cfg[79], $hc_cfg[78]);
		
		header('Location: ' . AdminRoot . '/?lmsg=3');
	} else {
		header('Location: ' . AdminRoot . '/');
	}
?>