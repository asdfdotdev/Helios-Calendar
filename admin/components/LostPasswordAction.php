<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/login.php');
	
	$email = $_POST['email'];
	$result = doQuery("SELECT Email, Passwrd FROM " . HC_TblPrefix . "admin WHERE email = '" . cIn($email) ."' AND IsActive = 1");
	
	if(hasRows($result)){
		$pwKey = md5(date("U"));
		
		doQuery("UPDATE " . HC_TblPrefix . "admin SET PCKey = '" . cIn($pwKey) . "' WHERE Email = '" . $email . "'");
		
		$headers = "From: " . CalAdminEmail . "\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Reply-To: " . CalAdminEmail . "\n";
		$headers .= "Content-Type: text/html; charset=UTF-8;\n";
		$subject = CalName . " " . $hc_lang_login['LoginSubject'];
		$message = "<a href=\"" . CalAdminRoot . "/index.php?lp=2&k=" . $pwKey . "\">"  . CalAdminRoot . "/index.php?lp=2&k=" . $pwKey . "</a>";
		$message .= "<br><br>" . $hc_lang_login['LoginEmail'];
		$message .= " <b>" . $_SERVER["REMOTE_ADDR"] . "</b>";
		
		mail(mysql_result($result,0,0), "$subject", "$message", "$headers");
		
		header('Location: ' . CalAdminRoot . '/?msg=3');
	} else {
		header('Location: ' . CalAdminRoot . '/');
	}//end if	?>
