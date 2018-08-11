<?php
/*
	Helios Calendar
	Copyright (C) 2004-2010 Refresh Web Development, LLC. [www.RefreshMy.com]

	This file is part of Helios Calendar, it's usage is governed by
	the Helios Calendar SLA found at www.HeliosCalendar.com/license.html
*/
	$isAction = 1;
	include('../includes/include.php');

	header('Content-type: application/xml; charset="utf-8"');

	echo '<?xml version="1.0" encoding="UTF-8"?>';
	echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	echo '<url>';
	echo '<loc>' . CalRoot . '</loc>';
	echo '<priority>1.0</priority>';
	echo '</url>';
	echo '<url>';
	echo '<loc>' . CalRoot . '/index.php?com=location</loc>';
	echo '<priority>1.0</priority>';
	echo '</url>';
	echo '<url>';
	echo '<loc>' . CalRoot . '/index.php?com=submit</loc>';
	echo '<priority>1.0</priority>';
	echo '</url>';
	echo '<url>';
	echo '<loc>' . CalRoot . '/index.php?com=search</loc>';
	echo '<priority>1.0</priority>';
	echo '</url>';
	echo '<url>';
	echo '<loc>' . CalRoot . '/index.php?com=signup</loc>';
	echo '<priority>1.0</priority>';
	echo '</url>';
	echo '<url>';
	echo '<loc>' . CalRoot . '/index.php?com=tools</loc>';
	echo '<priority>1.0</priority>';
	echo '</url>';
	echo '<url>';
	echo '<loc>' . CalRoot . '/index.php?com=rss</loc>';
	echo '<priority>1.0</priority>';
	echo '</url>';
	echo '</urlset>';
?>