<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	appInstructions(0, "Main_Page", "Welcome to the Helios Calendar Administration Console", "Look for instruction boxes like this one throughout the admin for information about how to use each section. Also information icons are available to provide you with additional context relivant information.<br /><br /><a onmouseover=\"this.T_TITLE='Sample Information Box';this.T_SHADOWCOLOR='#3D3F3E';return escape('This is an example of how the information icons work. When you see one move your mouse over it for more information.')\" href=\"javascript:;\"><img src=\"" . CalAdminRoot . "/images/icons/iconInfo.gif\" width=\"16\" height=\"16\" alt=\"\" border=\"0\" align=\"absmiddle\" /></a> &laquo;&laquo; Point your cursor here for an example.");
?>
<br />
<div style="float:left;padding-right:13px;padding-bottom:20px;width:350px;">
<?	if(!($fp = fsockopen("www.helioscalendar.com", 80, $errno, $errstr, 1)) ){
		//feedback(3, "Connection to www.HeliosCalendar.com failed. The following error message was returned.<br /><hr>Error #:" . $errno . " -- " . $errstr);
		echo "Helios Feed Unavailable";
	} else {
		$read = "";
		$request = "GET /_update/feed.php HTTP/1.1\r\n";
		$request .= "Host: www.helioscalendar.com\r\n";
		$request .= "Connection: Close\r\n\r\n";
		fwrite($fp, $request);
		
		while (!feof($fp)) {
			$read .= fread($fp,1024);
		}//end while
		
		$output = explode("/bof", $read);
		if(isset($output[1])){
			$message = explode("/eof", $output[1]);
			echo $message[0];
		} else {
			echo "Helios Feed Unavailable";
		}//end if
		
		fclose($fp);
	}//end if	?>
</div>
<div style="float:left;width:165px;padding:15px 0px 15px 20px;border:1px solid #666666;background:#EFEFEF;">
	<img src="<?echo CalAdminRoot;?>/images/icons/iconCodex.gif" width="16" height="16" alt="" border="0" style="vertical-align:middle;" />
	<a href="http://codex.helioscalendar.com/" target="_blank" class="main"><b>Visit Helios Codex</b></a>
	<br /><br />
	<img src="<?echo CalAdminRoot;?>/images/icons/homeForum.gif" width="16" height="16" alt="" border="0" style="vertical-align:middle;" />
	<a href="http://www.helioscalendar.com/forum/" class="main" target="_blank"><b>Visit Helios Forum</b></a>
</div>