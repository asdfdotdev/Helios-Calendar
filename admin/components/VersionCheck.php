<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
?>
<br>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td valign="top" class="eventMain">
			<?
				$file = "http://dev.helioscalendar.com/update/";
				include '../events/includes/rss/RSS.php';
				
				if (isset($rss_channel["ITEMS"])) {
					if (count($rss_channel["ITEMS"]) > 0) {
						$i = 0;
					?>
						Current Release: <?echo $rss_channel["ITEMS"][$i]["CURVERSION"]; if($rss_channel["ITEMS"][$i]["CURVERSION"] > HC_Version){?> [ <a href="http://codex.helioscalendar.com/index.php?title=<?echo $rss_channel["ITEMS"][$i]["CURVERSION"];?>" target="_blank" class="main">Current Change Log</a> ]<?}//end if?><br>
						Your Version: <?echo HC_Version;?>
						<br><br>
			        <?
						if($rss_channel["ITEMS"][$i]["CURVERSION"] > HC_Version){
							echo "<b>" . $rss_channel["ITEMS"][$i]["PUBDATE"] . "</b><br>" . $rss_channel["ITEMS"][$i]["MESSAGE"];
						} elseif($rss_channel["ITEMS"][$i]["CURVERSION"] == HC_Version){
							echo "You have the newest version of Helios installed. Good job :)";
						}//end if
					} else {
						echo "Unable to check for new version now. Please try again later.";
					}//end if
				}//end if
			?>
		</td>
	</tr>
</table>