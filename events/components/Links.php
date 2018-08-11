<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
/*
	$incPrefix = 'events/';												//	Path from where you integrate this file to your /events/ directory
	include_once($incPrefix . 'includes/include_int.php');						//	Include for your int_include.php file (/events/includes/int_include.php)
	include_once($incPrefix . 'includes/lang/' . $hc_cfg28 . '/public/links.php');	//	New Include for your event.php language pack file
*/	
	include($hc_langPath . $_SESSION[$hc_cfg00 . 'LangSet'] . '/public/links.php');
	
	echo '<div class="rssLinksAll"><a href="' . CalRoot . '/rss/" class="controlPanel" rel="nofollow">' . $hc_lang_links['All'] . '</a></div>';
	echo '<div class="rssLinksNew"><a href="' . CalRoot . '/rss/?s=1" class="controlPanel" rel="nofollow">' . $hc_lang_links['Newest'] . '</a></div>';
	echo '<div class="rssLinksBillboard"><a href="' . CalRoot . '/rss/?s=3" class="controlPanel" rel="nofollow">' . $hc_lang_links['Featured'] . '</a></div>';
	echo '<div class="rssLinksPopular"><a href="' . CalRoot . '/rss/?s=2" class="controlPanel" rel="nofollow">' . $hc_lang_links['Popular'] . '</a></div>';
	echo '<div class="rssLinksDiscuss"><a href="' . CalRoot . '/rss/?s=4" class="controlPanel" rel="nofollow">' . $hc_lang_links['Discuss'] . '</a></div>';
	echo '<div class="iCalLinksSubscribe"><a href="webcal://' . substr(CalRoot, 7) . '/link/iCalendar.php" class="controlPanel" rel="nofollow">' . $hc_lang_links['Subscribe'] . '</a></div>';?>