<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	include('../includes/include.php');
	
	if(isset($_GET['eID']) && is_numeric($_GET['eID'])){
		hookDB();
		
		$result = doQuery("SELECT Title, ContactName, ContactEmail, ContactPhone, ContactURL FROM " . HC_TblPrefix . "events WHERE PkID = " . $_GET['eID']);
		
		$descFooter = " Event Contact=0D=0A=0D=0A______________________________________________=
_________________=0D=0AThis Contact Downloaded From a Helios " . HC_Version . " Powered S=
ite=0D=0A";
		$descFooter .= CalRoot . "/";
		
		header('Content-type: text/x-vCard');
		header('Content-Disposition: inline; filename="' . mysql_result($result,0,1) . '.vcs"');
		
		echo "BEGIN:VCARD" . "\n";
		echo "VERSION:2.0" . "\n";
		echo "N:" . mysql_result($result,0,1) . "\n";
		echo "FN:" . mysql_result($result,0,1) . "" . "\n";
		echo "ORG: Helios Calendar Event Contact" . "\n";
		echo "TITLE:" . "\n";
		echo "NOTE;ENCODING=QUOTED-PRINTABLE:" . mysql_result($result,0,0) . $descFooter . " \n";
		echo "TEL;WORK;VOICE:" . mysql_result($result,0,3) . "\n";
		echo "URL;WORK:" . mysql_result($result,0,4) . "\n";
		echo "EMAIL;PREF;INTERNET:" . mysql_result($result,0,2) . "\n";
		echo "END:VCARD";
		
	} else {
		header('Location: ' . CalRoot);
	}//end if
?>
