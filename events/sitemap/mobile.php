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
	echo '<loc>' . CalRoot . '/m/</loc>';
	echo '<priority>1.0</priority>';
	echo '</url>';
	echo '<url>';
	echo '<loc>' . CalRoot . '/m/browse.php</loc>';
	echo '<priority>1.0</priority>';
	echo '</url>';
	echo '<url>';
	echo '<loc>' . CalRoot . '/m/search.php</loc>';
	echo '<priority>1.0</priority>';
	echo '</url>';
	echo '<url>';
	echo '<loc>' . CalRoot . '/m/about.php</loc>';
	echo '<priority>1.0</priority>';
	echo '</url>';
	echo '<url>';
	echo '<loc>' . CalRoot . '/m/lang.php</loc>';
	echo '<priority>1.0</priority>';
	echo '</url>';
	echo '<url>';
	echo '<loc>' . CalRoot . '/m/help.php</loc>';
	echo '<priority>1.0</priority>';
	echo '</url>';
	echo '</urlset>';
?>