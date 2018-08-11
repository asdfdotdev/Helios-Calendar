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
	checkIt(1);
	
	$result = doQuery("SELECT SettingValue FROM " . HC_TblPrefix . "settings WHERE PkID IN (14) ORDER BY PkID");
	$hc_dateFormat = cOut(mysql_result($result,0,0));
	
	include('../' . $hc_langPath . $_SESSION['LangSet'] . '/admin/register.php');
	
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		$result = doQuery("SELECT Title, StartDate, ContactEmail FROM " . HC_TblPrefix . "events WHERE PkID = " . $_GET['eID']);
		$eventTitle = mysql_result($result,0,0);
		$eventDate = stampToDate(mysql_result($result,0,1), $hc_dateFormat);
		$contact = mysql_result($result,0,2);
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "registrants WHERE EventID = " . $_GET['eID'] . " ORDER BY RegisteredAt");
		if(hasRows($result)){
			
			$headers = "From: " . CalAdminEmail . "\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Reply-To: " . CalAdminEmail . "\n";
			$headers .= "Content-Type: text/html; charset=UTF-8;\n";
			
			$subject = CalName . " " . $hc_lang_register['RosterSubject'];
			
			$message = $hc_lang_register['RosterEmailA'] . " <b>" . $eventTitle . "</b> " . $hc_lang_register['RosterEmailB'] . " " . $eventDate;
			
			$roster = "<br><br>Event Registrants<br>-------------------------";
			while($row = mysql_fetch_row($result)){
				$roster .= "<br>Name: " . $row[1];
				$roster .= "<br>Email: " . $row[2];
				$roster .= "<br>Phone: " . $row[3];
				$roster .= "<br>Address: " . $row[4] . " " . $row[5] . " " . $row[6] . ", " . $row[7] . " " . $row[8];
				$roster .= "<br>Registered At: " . $row[11] . "<br>";
			}//end while
			$message .= $roster;
			
			mail($contact, $subject, $message, $headers);
			header("Location: " . CalAdminRoot . "/index.php?com=eventedit&eID=" . $_GET['eID'] . "&r=1&msg=6");
		}//end if
	}//end if	
	
	header("Location: " . CalAdminRoot . "/index.php?com=eventedit&eID=" . $_GET['eID'] . "&r=1&msg=6");?>