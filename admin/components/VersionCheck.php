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
	include($hc_langPath . $_SESSION['LangSet'] . '/admin/tools.php');
	
	appInstructions(0, "Changelog", $hc_lang_tools['TitleUpdate'], $hc_lang_tools['InstructUpdate']);
	echo "<br />";
	if(!($fp = fsockopen("www.helioscalendar.com", 80, $errno, $errstr, 1)) ){
		echo $hc_lang_tools['CheckBroken'];
	} else {
		$read = "";
		$request = "GET /_update/version.php HTTP/1.1\r\n";
		$request .= "Host: www.helioscalendar.com\r\n";
		$request .= "Connection: Close\r\n\r\n";
		fwrite($fp, $request);
		while (!feof($fp)) {
			$read .= fread($fp,1024);
		}//end while
		$output = explode("/bof", $read);
		$version = explode("/eof", $output[1]);
		if(isset($output[1])){	
			echo $hc_lang_tools['UserVersion'] . " " . HC_Version . "<br /><br />";
			echo $hc_lang_tools['CurrentVersion'] . " " . $version[0] . "<br /><br />";
			if($version[0] == HC_Version){
				$hc_lang_tools['CheckGood'];
			} else {
				echo $hc_lang_tools['CheckUpdate'];
			}//end if
		} else {
			echo $hc_lang_tools['CheckBroken'];
		}//end if
		fclose($fp);
	}//end if
	echo "<br /><br /><br /><br /><br />";?>