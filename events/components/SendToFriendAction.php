<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../includes/include.php');
	checkIt();
	
	$proof = "";
	if(isset($_POST['proof'])){$proof = $_POST['proof'];}
	spamIt($proof, 2);
		
	$myName = cIn(cleanEmailHeader($_POST['hc_fx1']));
	$myEmail = cIn(cleanEmailHeader($_POST['hc_fx2']));
	$friendName = cIn(cleanEmailHeader($_POST['hc_fx3']));
	$friendEmail = cIn(cleanEmailHeader($_POST['hc_fx4']));
	$sendMsg = cIn(nl2br(htmlspecialchars(strip_tags($_POST['hc_fx5']))));
	$eID = cIn($_POST['eID']);
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($eID));
	
	$headers = "From: " . $myName . " <" . $myEmail . ">\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Reply-To: " . $myName . " <" . $myEmail . ">\r\n";
	$headers .= "Content-Type: text/html; charset=iso-8859-1;\r\n";
	
	$subject = CalName . " Event Notice From " . $myName;
	
	$message = $friendName . ",<br><br>";
	$message .= $sendMsg . "<br><br>";
	$message .= "<b>" . CalRoot . "/index.php?com=detail&eID=" . $eID . "</b><br><br>";
	$message .= $myName . "<br>" . $myEmail;
	$message .= "<br><br>------------------------------------------------------------------------------------------<br>";
	$message .= "This email was sent using an automated form at " . CalName . ".<br> If you believe you have received this email in error, or need to report abuse,<br>please contact " . CalAdmin . " at " . CalAdminEmail;
	$message .="<br>------------------------------------------------------------------------------------------<br>";
	
	doQuery("INSERT INTO " . HC_TblPrefix . "sendtofriend(MyName, MyEmail, RecipientName, RecipientEmail, Message, EventID, IPAddress, SendDate)
			 Values('" . cIn($myName) . "', '" . cIn($myEmail) . "', '" . cIn($friendName) . "', '" . cIn($friendEmail) . "', '" . cIn(cleanSpecialChars(str_replace('<br>','\n',$message))) . "', " . cIn($eID) . ", '" . $_SERVER["REMOTE_ADDR"] . "', now())");
	
	mail($friendEmail, $subject, $message, $headers);
	
	doQuery("UPDATE " . HC_TblPrefix . "events SET EmailToFriend = EmailToFriend + 1 WHERE PkID = " . $eID);
	
	header("Location: " . CalRoot . "/index.php?com=send&eID=" . $eID . "&msg=1");
?>