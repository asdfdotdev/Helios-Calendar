<?php
/*
	Helios Calendar - Professional Event Management System
	Copyright © 2007 Refresh Web Development [http://www.refreshwebdev.com]
	
	Developed By: Chris Carlevato <chris@refreshwebdev.com>
	
	For the most recent version, visit the Helios Calendar website:
	[http://www.helioscalendar.com]
	
	License Information is found in docs/license.html
*/
?>
	<ul class="rssLinks">
		<li><a href="<?php echo CalRoot;?>/rss.php" class="controlPanel"><img class="controlPanel" src="<?php echo CalRoot;?>/images/rss/feedIcon.gif" width="16" height="16" alt="<?php echo CalName;?> All Events RSS Feeds" /> All Events</a></li>
		<li><a href="<?php echo CalRoot;?>/rss.php?s=1" class="controlPanel"><img class="controlPanel" src="<?php echo CalRoot;?>/images/rss/feedIcon.gif" width="16" height="16" alt="<?php echo CalName;?> Newest Events RSS Feeds" /> Newest Events</a></li>
		<li><a href="<?php echo CalRoot;?>/rss.php?s=3" class="controlPanel"><img class="controlPanel" src="<?php echo CalRoot;?>/images/rss/feedIcon.gif" width="16" height="16" alt="<?php echo CalName;?> Featured RSS Feeds" /> Featured Events</a></li>
		<li><a href="<?php echo CalRoot;?>/rss.php?s=2" class="controlPanel"><img class="controlPanel" src="<?php echo CalRoot;?>/images/rss/feedIcon.gif" width="16" height="16" alt="<?php echo CalName;?> Most Popular RSS Feeds" /> Most Popular Events</a></li>
	</ul>