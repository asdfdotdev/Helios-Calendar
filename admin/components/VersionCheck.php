<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
?>
<br>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td valign="top" class="eventMain">
			<?
				$host = "www.helioscalendar.com";
				$file = "/update.php";
			
			if(!($fp = fsockopen($host, 80, $errno, $errstr, 1)) ){
				feedback(3, "Connection to www.HeliosCalendar.com failed. The following error message was returned.<br><hr>Error #:" . $errno . " -- " . $errstr);
			} else {
				$read = "";
				$request = "GET $file HTTP/1.1\r\n";
				$request .= "Host: $host\r\n";
				$request .= "Connection: Close\r\n\r\n";
				fwrite($fp, $request);
				
				while (!feof($fp)) {
					$read .= fread($fp,1024);
				}//end while
				
				$output = explode("cut//", $read);
				$version = explode("//", $output[1]);
				if(isset($output[1])){	?>
					Your Version: <?echo HC_Version;?><br>
					Current Release: <?echo $version[0];?><br><br>
				<?	if($version[0] == HC_Version){	?>
						You have the newest version of Helios installed. Good Job :)
			<?		} else {	?>
						To download available updates, please visit the <a href="https://www.helioscalendar.com/members/" target="_blank" class="main">Helios Members Site</a>.
			<?		}//end if	?>
			<?	} else {
					echo "Helios Update Check Unavailable";
				}//end if
				
				fclose($fp);
			}//end if
			?>
		</td>
	</tr>
</table>