<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	$isAction = 1;
	include('../includes/include.php');
	checkIt(1);
	
	if($_GET['eID']){
		$result = doQuery("SELECT Title, StartDate, ContactEmail FROM " . HC_TblPrefix . "events WHERE PkID = " . $_GET['eID']);
		$eventTitle = mysql_result($result,0,0);
		$eventDate = stampToDate(mysql_result($result,0,1), "l F jS Y");
		$contact = mysql_result($result,0,2);
		
		$result = doQuery("SELECT * FROM " . HC_TblPrefix . "registrants WHERE EventID = " . $_GET['eID']);
		if(hasRows($result)){
			
			$headers = "From: " . CalAdminEmail . "\n";
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Reply-To: " . CalAdminEmail . "\n";
			$headers .= "Content-Type: text/html; charset=UTF-8;\n";
			
			$subject = CalName . " Event Registrant Roster";
			
			$message = "Below is the list of registrants for the <b>" . $eventTitle . "</b> event occurring on " . $eventDate;
			$message .= "<br>If you have any questions please let me know.<br><br>" . CalAdmin . "<br>" . CalAdminEmail;
			
			$roster = "<br><br>Event Registrants<br>-------------------------";
			while($row = mysql_fetch_row($result)){
				$roster .= "<br>Name: " . $row[1];
				$roster .= "<br>Email: " . $row[2];
				$roster .= "<br>Phone: " . $row[3];
				$roster .= "<br>Address: " . $row[4] . " " . $row[5] . " " . $row[6] . ", " . $row[7] . " " . $row[8];
				$roster .= "<br>Registered At: " . $row[11] . "<br>";
			}//end while
			
			$message .= $roster;
			mail($contact, "$subject", "$message", "$headers");
			
			header("Location: " . CalAdminRoot . "/index.php?com=eventedit&eID=" . $_GET['eID'] . "&r=1&msg=6");
			exit;
		}//end if
	}//end if	
	
	header("Location: " . CalAdminRoot . "/index.php?com=eventedit&eID=" . $_GET['eID'] . "&r=1");?>