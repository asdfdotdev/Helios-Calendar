<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../../events/includes/include.php');
	hookDB();
	
	$name = $_POST['name'];
	$email = $_POST['email'];
	$ihave = $_POST['ihave'];
	$message = $_POST['message'];
	
	/*
		Send the email
	*/
	$from = CalName . "@HeliosCalendar.com";
	$subject = CalName . " Admin " . $ihave;
	$message = $name . "\n" . $email . "\n" . CalName . "\n" . $ihave . "\n" . $_SERVER["REMOTE_ADDR"] . "\n\n" . $message;
	$mailsend = mail("helios@refreshwebdev.com", "$subject", "$message", "From: $from");
	
	header("Location: " . CalAdminRoot . "/index.php?com=contact&msg=1");
?>