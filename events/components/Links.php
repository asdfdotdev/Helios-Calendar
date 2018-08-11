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
	include($hc_langPath . $_SESSION['LangSet'] . '/public/links.php');	?>
	<ul class="rssLinks">
		<li><a href="<?php echo CalRoot;?>/rss.php" class="controlPanel"><img class="controlPanel" src="<?php echo CalRoot;?>/images/rss/feedIcon.gif" width="16" height="16" alt="<?php echo CalName . " " . $hc_lang_links['All'] . " " . $hc_lang_links['Feed'];?>" style="vertical-align:middle;" /> <?php echo $hc_lang_links['All'];?></a></li>
		<li><a href="<?php echo CalRoot;?>/rss.php?s=1" class="controlPanel"><img class="controlPanel" src="<?php echo CalRoot;?>/images/rss/feedIcon.gif" width="16" height="16" alt="<?php echo CalName . " " . $hc_lang_links['Newest'] . " " . $hc_lang_links['Feed'];?>" style="vertical-align:middle;" /> <?php echo $hc_lang_links['Newest'];?></a></li>
		<li><a href="<?php echo CalRoot;?>/rss.php?s=3" class="controlPanel"><img class="controlPanel" src="<?php echo CalRoot;?>/images/rss/feedIcon.gif" width="16" height="16" alt="<?php echo CalName . " " . $hc_lang_links['Featured'] . " " . $hc_lang_links['Feed'];?>" style="vertical-align:middle;" /> <?php echo $hc_lang_links['Featured'];?></a></li>
		<li><a href="<?php echo CalRoot;?>/rss.php?s=2" class="controlPanel"><img class="controlPanel" src="<?php echo CalRoot;?>/images/rss/feedIcon.gif" width="16" height="16" alt="<?php echo CalName . " " . $hc_lang_links['Popular'] . " " . $hc_lang_links['Feed'];?>" style="vertical-align:middle;" /> <?php echo $hc_lang_links['Popular'];?></a></li>
		<li><a href="<?php echo CalRoot;?>/link/iCalendar.php" class="controlPanel"><img class="controlPanel" src="<?php echo CalRoot;?>/images/icons/iconiCal.png" width="16" height="16" alt="<?php echo CalName . " " . $hc_lang_links['Subscribe'];?>" style="vertical-align:middle;" /> <?php echo $hc_lang_links['Subscribe'];?></a></li>
	</ul>