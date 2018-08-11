<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	appInstructions(0, "Main_Page", "Welcome to the " . CalName . " Admin", "Look for instruction boxes like this one throughout the admin for information about how to use each section. Also information icons are available for more info throughout the Helios Admin.<br><br><a onmouseover=\"this.T_TITLE='Sample Information Box';this.T_SHADOWCOLOR='#3D3F3E';return escape('This is an example of how the information icons work. They are scattered throughout the administration console to assist you in using Helios.')\" href=\"javascript:;\"><img src=\"" . CalAdminRoot . "/images/icons/iconInfo.gif\" width=\"16\" height=\"16\" alt=\"\" border=\"0\" align=\"middle\"></a> &laquo;&laquo; Point your cursor here for an example.");
?>
<br>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td valign="top" class="eventMain" width="40%">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="33%" align="center" class="main">
						<a href="http://forum.helioscalendar.com" class="main" target="_blank"><img src="<?echo CalAdminRoot;?>/images/icons/homeForum.gif" alt="" border="0"><br>Forum</a>
					</td>
					<td width="33%" align="center" class="main">
						<a href="http://dev.helioscalendar.com" class="main" target="_blank"><img src="<?echo CalAdminRoot;?>/images/icons/homeBlog.gif" alt="" border="0"><br>Blog</a>
					</td>
					<td width="33%" align="center" class="main">
						<a href="http://codex.helioscalendar.com" class="main" target="_blank"><img src="<?echo CalAdminRoot;?>/images/icons/homeDocumentation.gif" alt="" border="0"><br>Codex</a>
					</td>
				</tr>
			</table>
		</td>
		<td width="5">&nbsp;</td>
		<td valign="top" class="eventMain">
			<?
				$host = "www.helioscalendar.com";
				$file = "/feed.php";
			
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
				$message = explode("//", $output[1]);
				if(isset($output[1])){
					echo $message[0];
				} else {
					echo "Helios Feed Unavailable";
				}//end if
				
				fclose($fp);
			}//end if
			?>
		</td>
	</tr>
</table>