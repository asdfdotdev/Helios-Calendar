<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2005 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['rID']) && is_numeric($_GET['rID'])){
		$rID = $_GET['rID'];
	} else {
		$rID = 0;
	}//end if
?>
<script language="JavaScript">

function updateLink(){
	document.rss.rssLink.value = '<?echo CalRoot;?>/rss.php?cID=' + checkUpdateString('rss', 'catID[]');
}//end updateLink()
</script>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="eventMain">
			<b>RSS Resources</b>
			<li><a href="<?echo CalRoot;?>/index.php?com=rss&rID=1" class="eventMain">Recommended Readers</a>
			<li><a href="<?echo CalRoot;?>/index.php?com=rss&rID=2" class="eventMain">What is RSS?</a>
			<li><a href="<?echo CalRoot;?>/index.php?com=rss&rID=3" class="eventMain">Usage Guidelines</a>
			<li><a href="<?echo CalRoot;?>/index.php?com=rss" class="eventMain">Our Feeds</a>
			<br><br>
<?switch($rID){
	case 0:
	?>
		<b>Access Our RSS Feed:</b><br>
		<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="4" alt="" border="0">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="20">&nbsp;</td>
					<td class="eventMain">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td colspan="4" align="center">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td class="eventMain"><a href="<?echo CalRoot;?>/rss.php"><img src="<?echo CalRoot;?>/images/xml.gif" width="36" height="14" alt="" border="0"></a></td>
											<td class="eventMain">&nbsp;&nbsp;All Events&nbsp;&nbsp;</td>
											<td class="eventMain"><a href="<?echo CalRoot;?>/rss.php?s=1"><img src="<?echo CalRoot;?>/images/xml.gif" width="36" height="14" alt="" border="0"></a></td>
											<td class="eventMain">&nbsp;&nbsp;Newest Events&nbsp;&nbsp;</td>
											<td class="eventMain"><a href="<?echo CalRoot;?>/rss.php?s=2"><img src="<?echo CalRoot;?>/images/xml.gif" width="36" height="14" alt="" border="0"></a></td>
											<td class="eventMain">&nbsp;&nbsp;Most Popular Events&nbsp;&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						
					</td>
				</tr>
			</table>
		<br>
		<b>Or Create Your Own Custom Feed</b><br><br>
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
				<form name="rss" id="rss" method="post" action="<?echo CalRoot;?>/rss.php" target="_blank">
					<td width="20">&nbsp;</td>
					<td class="eventMain">
					<b>Step 1) Select Your Categories:</b><br>
						<table cellpadding="0" cellspacing="0" border="0">
							<?php
								$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 AND PkID > 0 ORDER BY CategoryName");
								$cnt = 0;
								
								while($row = mysql_fetch_row($result)){
									if(($cnt % 3 == 0) && ($cnt > 0) ){echo "</tr><tr>";}//end if
								?>
									<td class="eventMain"><input onClick="updateLink();" type="checkbox" name="catID[]" id="catID_<?echo $row[0];?>" value="<?echo $row[0];?>"></td>
									<td class="eventMain"><label for="catID_<?echo $row[0];?>"><?echo $row[1];?></label>&nbsp;&nbsp;</td>
								<?
									$cnt = $cnt + 1;
								}//end while
							?>
						</table>
						<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"><br>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td class="eventMain">
									<b>Step 2) Paste the URL Below Into Your RSS Reader</b>
								</td>
							</tr>
							<tr>
								<td><input type="text" name="rssLink" id="rssLink" size="50" maxlength="200" value="<?echo CalRoot;?>/rss.php" class="eventInput">&nbsp;</td>
							</tr>
							<tr><td><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
							<tr>
								<td><input type="reset" name="reset" id="reset" value=" Start Over" class="eventButton"></td>
							</tr>
						</table>
						
					</td>
				</form>
				</tr>
			</table>
<?
	break;
	
	case 1:
	?>
		<b>Recommended RSS Readers</b><br>
		
			<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0">
			<table cellpadding="0" cellspacing="0" border="0" width="95%">
				<tr>
					<td colspan="2" class="eventMain">
						Looking for a great program to read the <?echo CalName;?> RSS feeds with? Well here's a few of our suggested choices.
					</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td valign="middle" align="center" height="40"><img src="<?echo CalRoot;?>/images/logoWindows.jpg" width="33" height="30" alt="" border="0"></td>
					<td class="eventMain" valign="middle"><a href="http://www.download.com/3120-20_4-0.html?qt=rss+reader&tg=dl-2001&search.x=30&search.y=3&search=+Go%21+" class="eventMain" target="_blank">Visit Download.com</a></td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td valign="middle" align="center" height="40"><img src="<?echo CalRoot;?>/images/logoApple.jpg" width="25" height="30" alt="" border="0"></td>				
					<td class="eventMain" valign="middle">
						NewsTicker by <img src="<?echo CalRoot;?>/images/logoNullriver.jpg" width="51" height="11" alt="" border="0"> [ <a href="http://www.nullriver.com/index/products/newsticker" class="eventMain" target="_blank">Download</a> ]</td>
				</tr>
				<tr><td colspan="2"><img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0"></td></tr>
				<tr>
					<td valign="middle" align="center" height="40"><img src="<?echo CalRoot;?>/images/logoTux.jpg" width="25" height="30" alt="" border="0"></td>
					<td class="eventMain" valign="middle"><a href="http://www.google.com/search?q=linux+rss+readers" class="eventMain" target="_blank">Find at Google</a></td>
				</tr>
			</table>
		
<?
	break;
	
	case 2:
	?>
			<b>What is RSS?</b><br>
			<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0">
			<table cellpadding="0" cellspacing="0" border="0" width="95%">
				<tr>
					<td class="eventMain">
						RDF Site Syndication (RSS) is an XML-based format for content distribution. The <?echo CalName;?> utilizes an RSS 2.0 standards 
						compliant format to syndicate its event content. For a full rundown of what RSS is, how it works, and how you can use it
						visit <a href="http://en.wikipedia.org/wiki/RSS_%28file_format%29" class="eventMain" target="_blank">Wikipedia</a>
					</td>
				</tr>
			</table>
<?
	break;
	
	case 3:
	?>
			<b>Usage Guidelines</b><br>
			<img src="<?echo CalRoot;?>/images/spacer.gif" width="1" height="7" alt="" border="0">
			<table cellpadding="0" cellspacing="0" border="0" width="95%">
				<tr>
					<td class="eventMain">
						We encourage you to use the feeds made available here, so long as you provide proper attribution, and a link back to our site.
						Whenever you post <?echo CalName;?> content on your web site or anywhere else, please provide attribution, and a link back to, 
						our site.
						<br><br>
						While we appreciate that you want to use our feeds to promote event information, we do reserve all rights in and to our content 
						and your right to use said content. Your rights to do so are limited to providing attribution in connection with the RSS feed. 
						We don't require anything dramatic, but we do ask that you always note the <?echo CalName;?> as the source.
						<br><br>
						Priviledge to use these feeds is left to our sole discretion and the terms that govern their use may be adjusted at anytime.
					</td>
				</tr>
			</table>
<?
	break;
	
}//end switch?>
		</td>
	</tr>
</table>