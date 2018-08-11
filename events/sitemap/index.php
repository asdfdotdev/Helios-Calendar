<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development, LLC.
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	$isAction = 1;
	include('../includes/include.php');

	header('Content-type: application/xml; charset="utf-8"');

	echo '<?xml version="1.0" encoding="UTF-8"?>';
	echo '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
	echo '<sitemap>';
	echo '<loc>' . CalRoot . '/sitemap/events.php</loc>';
	echo '<lastmod>' . date("Y-m-d", mktime(0,0,0,(date("m")),1,date("Y"))) . '</lastmod>';
	echo '</sitemap>';
	echo '<sitemap>';
	echo '<loc>' . CalRoot . '/sitemap/site.php</loc>';
	echo '</sitemap>';
	echo '<sitemap>';
	echo '<loc>' . CalRoot . '/sitemap/mobile_events.php</loc>';
	echo '<lastmod>' . date("Y-m-d", mktime(0,0,0,(date("m")),1,date("Y"))) . '</lastmod>';
	echo '</sitemap>';
	echo '<sitemap>';
	echo '<loc>' . CalRoot . '/sitemap/mobile.php</loc>';
	echo '</sitemap>';
	echo '</sitemapindex>';
?>