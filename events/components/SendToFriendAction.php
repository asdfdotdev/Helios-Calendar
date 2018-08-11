<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../includes/include.php');
	hookDB();
	
	$myname = cleanEmailHeader($_POST['myName']);
	$myemail = cleanEmailHeader($_POST['myEmail']);
	$recname = cleanEmailHeader($_POST['recipName']);
	$recemail = cleanEmailHeader($_POST['recipEmail']);
	$msg = str_replace(chr(13),'<br>',$_POST['message']);
	$eID = $_POST['eID'];
	
	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($eID));
	
	$headers = "From: " . $myname . " <" . $myemail . ">\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Reply-To: " . $myname . " <" . $myemail . ">\r\n";
	$headers .= "Content-Type: text/html; charset=iso-8859-1;\r\n";
	
	$subject = CalName . " Event Notice From " . $myname;
	
	$message = $recname . ",<br><br>";
	$message .= "I was visiting the " . CalName . " today and found a listing for the <b>" . str_replace("'","\'",mysql_result($result,0,1)) . "</b> event and thought you would like to check it out.<br><br>You can get all the information about it at:<br><a href=\"" . CalRoot . "/index.php?com=detail&eID=" . $eID . "\" target=\"_new\" class=\"eventMain\">" . CalRoot . "/?eID=" . $eID . "</a>";
	$message .="<br><br>" . $msg . "<br><br>" . $myname . "<br>" . $myemail;
	$message .="<br><br>------------------------------------------------------------------------------------------<br>";
	$message .="This email was sent using an automated form at the <a href=\"" . CalRoot . "\" target=\"new\">" . CalName . " website</a>.<br> If you believe you\'ve received this
	email in error, or need to report abuse,<br>please contact " . CalAdmin . " at " . CalAdminEmail;
	$message .="<br>------------------------------------------------------------------------------------------<br>";
	
	doQuery("INSERT INTO " . HC_TblPrefix . "sendtofriend(MyName, MyEmail, RecipientName, RecipientEmail, Message, EventID, IPAddress, SendDate)
			 Values('" . cIn($myname) . "', '" . cIn($myemail) . "', '" . cIn($recname) . "', '" . cIn($recemail) . "', '" . cIn(str_replace('<br>','\n',$message)) . "', " . cIn($eID) . ", '" . $_SERVER["REMOTE_ADDR"] . "', now())");
	
	mail($recemail, $subject, $message, $headers);
	
	doQuery("UPDATE " . HC_TblPrefix . "events SET EmailToFriend = EmailToFriend + 1 WHERE PkID = " . $eID);
	
	header("Location: " . CalRoot . "/index.php?com=send&eID=" . $eID . "&msg=1");
?>