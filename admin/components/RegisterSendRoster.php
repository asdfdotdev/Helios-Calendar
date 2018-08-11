<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2004-2009 Refresh Web Development [www.RefreshMy.com]
	
	For the most recent version, visit the Helios Calendar website:
	[www.HeliosCalendar.com]
	
	This file is part of Helios Calendar, usage governed by 
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	
	include('../' . $hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/admin/register.php');
	
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		$result = doQuery("SELECT Title, StartDate, StartTime, TBD, ContactEmail FROM " . HC_TblPrefix . "events WHERE PkID = " . $_GET['eID']);
		$eventTitle = mysql_result($result,0,0);
		$eventDate = stampToDate(mysql_result($result,0,1), $hc_cfg14);
		switch(mysql_result($result,0,3)){
			case 0:
				$timepart = explode(":", mysql_result($result,0,2));
				$eventTime = strftime($hc_cfg23, mktime($timepart[0], $timepart[1], $timepart[2]));
				break;
			case 1:
				$eventTime = $hc_lang_register['AllDay'];
				break;
			case 2:
				$eventTime = $hc_lang_register['TBA'];
				break;
		}//end switch
		
		$contact = mysql_result($result,0,4);
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "registrants WHERE EventID = " . $_GET['eID'] . " ORDER BY RegisteredAt");
		if(hasRows($result)){
			$headers = "From: " . CalAdminEmail . "\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Reply-To: " . CalAdminEmail . "\n";
			$headers .= "Content-Type: text/html; charset=UTF-8;\n";
						
			$subject = CalName . ' ' . $hc_lang_register['RosterSubject'];
			$message = $hc_lang_register['RosterEmailA'] . ' <b>' . $eventTitle . '</b> ' . $hc_lang_register['RosterEmailB'] . ' ' . $eventDate . ' @ ' . $eventTime;
			
			$roster = '<br><br>' . $hc_lang_register['EventReg'] . '<br>-------------------------';
			while($row = mysql_fetch_row($result)){
				$roster .= '<br>' . $hc_lang_register['Name'] . ' ' . $row[1];
				$roster .= '<br>' . $hc_lang_register['Email'] . ' ' . $row[2];
				$roster .= '<br>' . $hc_lang_register['Phone'] . ' ' . $row[3];
				$roster .= '<br>' . $hc_lang_register['Address'] . ' ' . $row[4] . ' ' . $row[5] . ' ' . $row[6] . ', ' . $row[7] . ' ' . $row[8];
				$roster .= '<br>' . $hc_lang_register['RegAt'] . ' ' . $row[11] . '<br>';
			}//end while
			$message .= $roster;

			mail($contact, $subject, $message, $headers);
			header("Location: " . CalAdminRoot . "/index.php?com=eventedit&eID=" . $_GET['eID'] . "&r=1&msg=6");
		}//end if
	}//end if	
	
	header("Location: " . CalAdminRoot . "/index.php?com=eventedit&eID=" . $_GET['eID'] . "&r=1&msg=6");?>