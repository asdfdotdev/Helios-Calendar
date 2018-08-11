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
	
	if($hc_cfg65 == 1){
		$proof = isset($_POST['proof']) ? $_POST['proof'] : NULL;
		$challenge = isset($_SESSION[$hc_cfg00 . 'hc_cap']) ? $_SESSION[$hc_cfg00 . 'hc_cap'] : NULL;
	} elseif($hc_cfg65 == 2){
		$proof = isset($_POST["recaptcha_response_field"]) ? $_POST["recaptcha_response_field"] : NULL;
		$challenge = isset($_POST["recaptcha_challenge_field"]) ? $_POST["recaptcha_challenge_field"] : NULL;
	}//end if
	spamIt($proof,$challenge,3);
	
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/config.php');
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/register.php');
	
	$name = cleanBreaks($_POST['hc_f1']);
	$email = cleanBreaks($_POST['hc_f2']);
	$phone = $_POST['hc_f3'];
	$address = $_POST['hc_f4'];
	$address2 = $_POST['hc_f5']; 
	$city = $_POST['hc_f6'];
	$state = $_POST['locState'];
	$zip = $_POST['hc_f8'];
	$eID = $_POST['eventID'];
	$partySize = $_POST['hc_f7'] + 1;
	
	//$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "registrants WHERE Email = '" . cIn($email) . "' AND EventID = " . cIn($eID));

	$result = doQuery("SELECT PkID FROM " . HC_TblPrefix . "registrants WHERE Email = '" . cIn($email) . "' AND EventID = 0");
	if(hasRows($result)){
		header("Location: " . CalRoot . "/index.php?com=register&eID=" . $eID . "&msg=1");
	} else {
		$result = doQuery("SELECT Title, StartDate, StartTime, TBD, ContactEmail FROM " . HC_TblPrefix . "events WHERE PkID = " . cIn($eID));
		$eventTitle = mysql_result($result,0,0);
		$eventDate = stampToDate(mysql_result($result,0,1), $hc_cfg14);
		$eventEmail = mysql_result($result,0,4);
		$regName = $name;
		$groupID = ($partySize > 1) ? md5($regName . $eventTitle . date("U")) : '';
		
		switch(mysql_result($result,0,3)){
			case 0:
				$timepart = explode(":", mysql_result($result,0,2));
				$eventTime = ' @ ' . strftime($hc_cfg23, mktime($timepart[0], $timepart[1], $timepart[2]));
				break;
			case 1:
				$eventTime = ' - ' . $hc_lang_register['AllDay'];
				break;
			case 2:
				$eventTime = ' - ' . $hc_lang_register['TBA'];
				break;
		}//end switch
		
		for($x=1;$x<=$partySize;$x++){
			if($partySize > 1){
				$regName = $name . " - " . $x . "/" . $partySize;
			}//end if
			
			doQuery("INSERT into " . HC_TblPrefix . "registrants(Name, Email, Phone, Address, Address2, City, State, Zip, EventID, IsActive, RegisteredAt, GroupID)
					Values(	'" . cIn($regName) . "',
							'" . cIn($email) . "',
							'" . cIn($phone) . "',
							'" . cIn($address) . "',
							'" . cIn($address2) . "',
							'" . cIn($city) . "',
							'" . cIn($state) . "',
							'" . cIn($zip) . "',
							'" . cIn($eID) . "',
							1, NOW(),
							'" . cIn($groupID) . "');");
		}//end for
		
		$headers = "From: " . CalAdminEmail . "\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Reply-To: " . CalAdminEmail . "\n";
		$headers .= "Content-Type: text/html; charset=" . $hc_lang_config['CharSet'] . ";\n";
		
		$Rsubject = $hc_lang_register['Rsubject'] . " " . CalName;
		$Csubject = $hc_lang_register['Csubject'] . " " . CalName;
		
		$result = doQuery("SELECT COUNT(r.EventID), e.SpacesAvailable
							FROM " . HC_TblPrefix . "registrants r
								LEFT JOIN " . HC_TblPrefix . "events e ON (r.EventID = e.PkID)
							WHERE r.EventID = " . $eID . "
							GROUP BY EventID");
		
		$Rmsg = '<p>' . $hc_lang_register['Rmsg'] . '</p><p><a href="' . CalRoot . '/index.php?eID=' . $eID . '">' . $eventTitle . '</a><br /> ' . $eventDate . $eventTime . '<br /><br />';
		$Cmsg = '<b>' . $eventTitle . '</b><br /> ' . $eventDate . $eventTime . '<br /><br />' . $hc_lang_register['Cmsg'];
		$sig = '<br /><br />' . $hc_lang_register['ThankYou'] . '<br />' . CalAdmin;
		
		$Rdisclaimer = '<br /><br />' . $hc_lang_register['Rdisclaimer'];
		$Cdisclaimer = '<br /><br />' . $hc_lang_register['Cdisclaimer'];
				
		if(mysql_result($result,0,0) > mysql_result($result,0,1) && mysql_result($result,0,1) != 0){
  			$Rmsg .= " " . $hc_lang_register['Roverflow'];
  			$Cmsg .= "<br /><br />" . $hc_lang_register['Coverflow'];
		} elseif(mysql_result($result,0,0) == mysql_result($result,0,1) && mysql_result($result,0,1) != 0){
			$Cmsg .= "<br /><br />" . $hc_lang_register['Climit'];
		}//end if
		
		$Cmsg .= "<br /><br />" . $hc_lang_register['PartySize'] . " " . $partySize;
		$Cmsg .= "<br /><br />" . $name . " <br />" . $email;
		
		if($phone != ''){
			$Cmsg .= "<br />" . $phone;
		}//end if
		if($address != ''){
			$Cmsg .= "<br />" . $address;
		}//end if
		if($address2 != ''){
			$Cmsg .= "<br />" . $address2;
		}//end if
		if($city != ''){
			$Cmsg .= "<br />" . $city . ", ". $state . " " . $zip;
		}//end if
		
		$Rmsg .= $sig . $Rdisclaimer;
		$Cmsg .= $sig . $Cdisclaimer;

		mail($email, $Rsubject, $Rmsg, $headers);
		mail($eventEmail, $Csubject, $Cmsg, $headers);

		header("Location: " . CalRoot . "/index.php?com=register&eID=" . $eID . "&confirm=1");
	}//end if?>