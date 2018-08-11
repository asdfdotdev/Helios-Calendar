<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2008 Refresh Web Development [www.RefreshMy.com]
	
	Developed By: Chris Carlevato <support@refreshmy.com>
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar EUL found at www.HeliosCalendar.com/license.pdf
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt();
	
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/public/sendtofriend.php');
	
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
	
	if(hasRows($result) && $myName != '' && $myEmail != '' && $friendName != '' && $friendEmail != ''){
		$headers = "From: " . $myEmail . "\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Reply-To: " . $myName . " <" . $myEmail . ">\n";
		$headers .= "Content-Type: text/html; charset=UTF-8;\n";
		
		$subject = CalName . " " . $hc_lang_sendtofriend['Subject']  . " " . $myName;
		
		$message = $friendName . ",<br><br>";
		$message .= $sendMsg . "<br><br>";
		$message .= "<a href=\"" . CalRoot . "/index.php?com=detail&eID=" . $eID . "\">" . CalRoot . "/index.php?com=detail&eID=" . $eID . "</a><br><br>";
		$message .= $myName . "<br>" . $myEmail;
		$message .= "<br><br>";
		$message .= $hc_lang_sendtofriend['AutoNotice'] . " " . CalAdmin . " - " . CalAdminEmail;
		
		doQuery("INSERT INTO " . HC_TblPrefix . "sendtofriend(MyName, MyEmail, RecipientName, RecipientEmail, Message, EventID, IPAddress, SendDate)
				 Values('" . cIn($myName) . "', '" . cIn($myEmail) . "', '" . cIn($friendName) . "', '" . cIn($friendEmail) . "', '" . cIn(cleanSpecialChars(str_replace('<br>','\n',$message))) . "', " . cIn($eID) . ", '" . $_SERVER["REMOTE_ADDR"] . "', '" . date("Y-m-d") . "')");
		
		mail($friendEmail, $subject, $message, $headers);
		
		doQuery("UPDATE " . HC_TblPrefix . "events SET EmailToFriend = EmailToFriend + 1 WHERE PkID = " . $eID);
		
		header("Location: " . CalRoot . "/index.php?com=send&eID=" . $eID . "&msg=1");
	} else {
		header("Location: " . CalRoot . "/");
	}//end if
?>