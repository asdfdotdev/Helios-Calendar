<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	appInstructions(0, "Changelog", "Update Check", "Helios will now check to see if a new version is available.<br /><br />You can <a href=\"http://www.helioscalendar.com/documentation/index.php?title=Changelog\" class=\"main\" target=\"_blank\">view the change log here</a>.");	?>
	<br />
<?	if(!($fp = fsockopen("www.helioscalendar.com", 80, $errno, $errstr, 1)) ){
		echo "Helios update information is temporarily unavailable.";
		//feedback(3, "Connection to www.HeliosCalendar.com failed. The following error message was returned.<br /><hr>Error #:" . $errno . " -- " . $errstr);
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
		if(isset($output[1])){	?>
			Your Version: <?echo HC_Version;?><br />
			Current Release: <?echo $version[0];?><br /><br />
		<?	if($version[0] == HC_Version){	?>
				You are using the latest version of Helios Calendar.
	<?		} else {	?>
				To download available updates, please visit the <a href="https://www.helioscalendar.com/members/" target="_blank" class="main">Helios Members Site</a>.
	<?		}//end if	?>
	<?	} else {
			echo "Helios Update Check Unavailable";
		}//end if
		
		fclose($fp);
	}//end if
	?>
	<br /><br /><br /><br /><br />