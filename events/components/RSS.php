<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2006 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
	
	if(isset($_GET['rID']) && is_numeric($_GET['rID'])){
		$rID = $_GET['rID'];
	} else {
		$rID = 0;
	}//end if	?>
	<div class="rssTitle">RSS Resources</div>
	<div id="rssMenu">
		<ul>
			<li><a href="<?echo CalRoot;?>/index.php?com=rss&amp;rID=1" class="eventMain">Recommended Readers</a></li>
			<li><a href="<?echo CalRoot;?>/index.php?com=rss&amp;rID=2" class="eventMain">What is RSS?</a></li>
			<li><a href="<?echo CalRoot;?>/index.php?com=rss&amp;rID=3" class="eventMain">Usage Guidelines</a></li>
			<li><a href="<?echo CalRoot;?>/index.php?com=rss" class="eventMain">Our Feeds</a></li>
		</ul>
	</div>
<?	switch($rID){
		case 0:	?>
			<script language="JavaScript" type="text/JavaScript" src="<?echo CalRoot;?>/includes/java/Checkboxes.js"></script>
			<script language="JavaScript" type="text/JavaScript">
			//<!--
				function updateLink(){
					document.frmEventRSS.rssLink.value = '<?echo CalRoot;?>/rss.php?cID=' + checkUpdateString('frmEventRSS', 'catID[]');
				}//end updateLink()
			//-->
			</script>
			<div class="rssTitle">Subscribe to Our Feed</div>
			<div class="rssSynd">
				<a href="http://feeds.my.aol.com/add.jsp?url=<?echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?echo CalRoot;?>/images/rss/synd_aol.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
				<a href="http://www.bloglines.com/sub/<?echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?echo CalRoot;?>/images/rss/synd_bloglines.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
				<a href="http://del.icio.us/post?url=<?echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?echo CalRoot;?>/images/rss/synd_delicious.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
				<a href="http://fyuze.com/add/rss/<?echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?echo CalRoot;?>/images/rss/synd_fyuze.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
				<a href="http://www.google.com/reader/preview/*/feed/<?echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?echo CalRoot;?>/images/rss/synd_google.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
			</div>
			<div class="rssSynd">
				<a href="http://kinja.com/checksiteform.knj?pop=y&amp;add=<?echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?echo CalRoot;?>/images/rss/synd_kinja.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
				<a href="http://my.msn.com/addtomymsn.armx?id=rss&amp;ut=<?echo CalRoot;?>/rss.php&amp;ru=<?echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?echo CalRoot;?>/images/rss/synd_msn.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
				<a href="http://www.newsburst.com/Source/?add=<?echo CalRoot;?>/rss.php" class="rssSynd target="_blank"><img src="<?echo CalRoot;?>/images/rss/synd_newsburst.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
				<a href="http://www.newsisfree.com/user/sub/?url=<?echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?echo CalRoot;?>/images/rss/synd_newsisfree.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
				<a href="http://www.newsgator.com/ngs/subscriber/subext.aspx?url=<?echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?echo CalRoot;?>/images/rss/synd_newsgator.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
			</div>
			<div class="rssSynd">
				<a href="http://client.pluck.com/pluckit/prompt.aspx?a=<?echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?echo CalRoot;?>/images/rss/synd_pluck.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
				<a href="http://www.rojo.com/add-subscription?resource=<?echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?echo CalRoot;?>/images/rss/synd_rojo.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
				<a href="http://add.my.yahoo.com/content?url=<?echo CalRoot;?>/rss.php" class="rssSynd" target="_blank"><img src="<?echo CalRoot;?>/images/rss/synd_yahoo.gif" width="91" height="17" alt="" border="0" />&nbsp;</a><br />
			</div>
			
			<form name="frmEventRSS" id="frmEventRSS" method="post" action="<?echo CalRoot;?>/rss.php" target="_blank">
			<div class="rssTitle">Create Your Own Custom Feed</div>
			<fieldset>
				<legend>Step 1) Select Your Categories</legend>
				<div class="frmReq">
					<table cellpadding="0" cellspacing="0" border="0">
					<?	$result = doQuery("SELECT * FROM " . HC_TblPrefix . "categories WHERE IsActive = 1 AND PkID > 0 ORDER BY CategoryName");
						$cnt = 0;
						
						while($row = mysql_fetch_row($result)){
							if(($cnt % 2 == 0) && ($cnt > 0) ){echo "</tr><tr>";}	?>
							<td><label for="catID_<?echo $row[0];?>" class="category"><input onclick="updateLink();" name="catID[]" id="catID_<?echo $row[0];?>" type="checkbox" value="<?echo $row[0];?>" class="noBorderIE" /><?echo cOut($row[1]);?></label></td>
						<?	$cnt = $cnt + 1;
						}//end while?>
					</table>
				</div>
			</fieldset>
			<br />
			<fieldset>
				<legend>Step 2) Paste the Address Below Into Your RSS Reader</legend>
				<div class="frmReq">
					<input name="rssLink" id="rssLink" type="text" size="80" maxlength="200" value="<?echo CalRoot;?>/rss.php" />
				</div>
				<div class="frmSubmit">
					<input name="reset" id="reset" type="reset" value=" Start Over" />
				</div>
			</fieldset>
			</form>
	<?		break;
		
		case 1:	?>
			<div class="rssTitle">Recommended RSS Readers</div>
			
			Are you in need of a great program to read the <?echo CalName;?> RSS feeds with?
			<br />
			Well you're in luck, here are few a few places you can find one.
			<br /><br />
			<a href="http://www.download.com/3120-20_4-0.html?qt=rss+reader&amp;tg=dl-2001&amp;search.x=30&amp;search.y=3&amp;search=+Go%21+" class="rssReader" target="_blank"><img class="rssReader" src="<?echo CalRoot;?>/images/rss/logoWindows.jpg" width="33" height="30" alt="" border="0" />&nbsp;Visit Download.com</a>
			<br /><br />
			<a href="http://www.download.com/3120-20_4-0.html?tag=srch&amp;qt=rss+reader&amp;tg=dl-2003&amp;search.x=0&amp;search.y=0&search=+Go%21" class="rssReader" target="_blank"><img class="rssReader" src="<?echo CalRoot;?>/images/rss/logoApple.jpg" width="25" height="30" alt="" border="0" />&nbsp;&nbsp;Visit Download.com</a>
			<br /><br />
			<a href="http://www.google.com/search?q=linux+rss+readers" class="rssReader" target="_blank"><img class="rssReader" src="<?echo CalRoot;?>/images/rss/logoTux.jpg" width="25" height="30" alt="" border="0" />&nbsp;&nbsp;Find at Google</a>
			
	<?		break;
		
		case 2:	?>
			<div class="rssTitle">What is RSS?</div>
			RDF Site Summary (RSS) is an XML-based format for content distribution. The <?echo CalName;?> utilizes an RSS 2.0 standards 
			compliant format to syndicate its event content.
			<br /><br />
			For a full definition of what RSS is, how it works, and how you can use it
			visit <a href="http://en.wikipedia.org/wiki/RSS_%28file_format%29" class="eventMain" target="_blank">Wikipedia</a>
	<?		break;
		
		case 3:	?>
			<div class="rssTitle">Usage Guidelines</div>
			We encourage you to use the feeds made available here, so long as you provide proper attribution, and a link back to our site.
			Whenever you post <?echo CalName;?> content on your web site or anywhere else, please provide attribution, and a link back to, 
			our site.
			<br><br>
			While we appreciate that you want to use our feeds to promote event information, we do reserve all rights to our content 
			and your right to use it. Your rights to do so are limited to providing attribution in connection with the RSS feed. 
			We don't require anything dramatic, but we do ask that you always note the <?echo CalName;?> as the source.
			<br><br>
			Priviledge to use these feeds is left to our sole discretion and the terms that govern their use may be adjusted at anytime.
			<br /><br />
			If you have questions about our RSS feeds, or their acceptable use, you may contact:
			<br />
			<script language="JavaScript" type="text/JavaScript">
			<?	$eParts = explode("@", CalAdminEmail);?>
				var ename = '<?echo $eParts[0];?>';
				var edomain = '<?echo $eParts[1];?>';
				document.write('<a href="mailto:' + ename + '@' + edomain + '" class="eventMain"><?echo CalAdmin;?></a><br />');
			</script>
	<?		break;
	}//end switch?>