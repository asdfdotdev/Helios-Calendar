<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	appInstructions(0, "Welcome to the " . CalName . " Admin", "Look for instruction boxes like this one throughout the admin for information about how to use each section. Also information icons are available for more info throughout the Helios Admin. <a onmouseover=\"this.T_TITLE='Sample Information Box';this.T_SHADOWCOLOR='#3D3F3E';return escape('This is an example of how the information icons work. They are scattered throughout the administration console to assist you in using Helios.')\" href=\"javascript:;\"><img src=\"" . CalAdminRoot . "/images/icon-info.gif\" width=\"16\" height=\"16\" alt=\"\" border=\"0\"></a> &laquo;&laquo; Point your cursor here for an example.");
?>
<br>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td valign="top" class="eventMain">
		The latest Helios news from the official
		<a href="http://dev.helioscalendar.com" class="main">Helios Developement Blog</a>. 
		<br>
		Click on a title to read the full article.
		<br><br><br>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<?
				$file = "http://dev.helioscalendar.com/?feed=rss2";
				include '../events/includes/rss/RSS.php';
				
				if (isset($rss_channel["ITEMS"])) {
					if (count($rss_channel["ITEMS"]) > 0) {
						for($i = 0;$i < count($rss_channel["ITEMS"]);$i++) {
						?>
							<tr>
								<td class="eventMain" width="50%"><b><a href="<?echo $rss_channel["ITEMS"][$i]["LINK"];?>" class="main" target="_blank"><?echo $rss_channel["ITEMS"][$i]["TITLE"];?></a></b> [ <?echo $rss_channel["ITEMS"][$i]["CATEGORY"];?> ]</td>
								<td class="eventMain" width="50%" align="right"><i><?echo $rss_channel["ITEMS"][$i]["PUBDATE"];?></i></td>
							</tr>
							<tr><td class="eventMain" colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
							<tr><td class="eventMain" colspan="2" bgcolor="#000000"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="1" alt="" border="0"></td></tr>
							<tr><td class="eventMain" colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0"></td></tr>
							<tr><td class="eventMain" colspan="2"><?echo $rss_channel["ITEMS"][$i]["DESCRIPTION"];?></td></tr>
							<tr><td class="eventMain" colspan="2"><img src="<?echo CalAdminRoot;?>/images/spacer.gif" width="1" height="30" alt="" border="0"></td></tr>
						<?
						}
					}//end if
				}//end if
			?>
			</table>
		</td>
	</tr>
</table>