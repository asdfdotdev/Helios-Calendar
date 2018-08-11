<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt();
	
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/sendtofriend.php');
	
	$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
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