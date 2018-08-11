<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	hookDB();
	
	$email = $_POST['email'];
	$result = doQuery("SELECT Email, Passwrd FROM " . HC_TblPrefix . "admin WHERE email = '" . cIn($email) ."' AND IsActive = 1");
	
	if(hasRows($result)){
		$pwKey = md5(date("U"));
		
		doQuery("UPDATE " . HC_TblPrefix . "admin SET PCKey = '" . cIn($pwKey) . "' WHERE Email = '" . $email . "'");
		
		$headers = "From: " . CalAdminEmail . "\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Reply-To: " . CalAdminEmail . "\n";
		$headers .= "Content-Type: text/html; charset=UTF-8;\n";
		
		$subject = CalName . " Password Reset";
		
		$message = "A request was submitted from the IP <b>" . $_SERVER["REMOTE_ADDR"] ."</b> to change the password for your Helios admin account. ";
		$message .= "If you made this request please copy and paste the URL below into your browser. ";
		$message .= "If you did not submit this request and wish to contact your system administrator please provide them the above IP Address.";
		$message .= "<br><br>This request was processed " . date("\o\\n F d, Y \a\\t h:i A");
		
		$message .= "<br><br>" . CalAdminRoot . "/index.php?lp=2&k=" . $pwKey;
		
		$message .= "<br><br>This is an automated email, please do not reply to it.";
		
		mail(mysql_result($result,0,0), "$subject", "$message", "$headers");
		
		header('Location: ' . CalAdminRoot . '/?msg=3');
	} else {
		header('Location: ' . CalAdminRoot . '/');
	}//end if	?>
