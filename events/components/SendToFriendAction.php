<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../includes/include.php');
	
	checkIt();
	hookDB();
	
	$myName = cleanEmailHeader($_POST['myName']);
	$myEmail = cleanEmailHeader($_POST['myEmail']);
	$friendName = cleanEmailHeader($_POST['friendName']);
	$friendEmail = cleanEmailHeader($_POST['friendEmail']);
	$sendMsg = nl2br($_POST['message']);
	$eID = $_POST['eID'];
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($eID));
	
	$headers = "From: " . $myName . " <" . $myEmail . ">\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Reply-To: " . $myName . " <" . $myEmail . ">\r\n";
	$headers .= "Content-Type: text/html; charset=iso-8859-1;\r\n";
	
	$subject = CalName . " Event Notice From " . $myName;
	
	$message = $friendName . ",<br><br>";
	$message .= "I was visiting the " . CalName . " today and found a listing for the <b>" . str_replace("'","\'",mysql_result($result,0,1)) . "</b> event and thought you would like to check it out.<br><br>You can get all the information about it at:<br>" . CalRoot . "/index.php?com=detail&eID=" . $eID;
	$message .="<br><br>" . $sendMsg . "<br><br>" . $myName . "<br>" . $myEmail;
	$message .="<br><br>------------------------------------------------------------------------------------------<br>";
	$message .="This email was sent using an automated form at the " . CalName . ".<br> If you believe you\'ve received this
	email in error, or need to report abuse,<br>please contact " . CalAdmin . " at " . CalAdminEmail;
	$message .="<br>------------------------------------------------------------------------------------------<br>";
	
	doQuery("INSERT INTO " . HC_TblPrefix . "sendtofriend(MyName, MyEmail, RecipientName, RecipientEmail, Message, EventID, IPAddress, SendDate)
			 Values('" . cIn($myName) . "', '" . cIn($myEmail) . "', '" . cIn($friendName) . "', '" . cIn($friendEmail) . "', '" . cIn(str_replace('<br>','\n',$message)) . "', " . cIn($eID) . ", '" . $_SERVER["REMOTE_ADDR"] . "', now())");
	
	mail($friendEmail, $subject, $message, $headers);
	
	doQuery("UPDATE " . HC_TblPrefix . "events SET EmailToFriend = EmailToFriend + 1 WHERE PkID = " . $eID);
	
	header("Location: " . CalRoot . "/index.php?com=send&eID=" . $eID . "&msg=1");
?>