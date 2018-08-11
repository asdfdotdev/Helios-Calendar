<?php
/**
 * This file is part of Helios Calendar, it's use is governed by the Helios Calendar Software License Agreement.
 *
 * @author Refresh Web Development LLC
 * @link http://www.refreshmy.com
 * @copyright (C) 2004-2011 Refresh Web Development
 * @license http://www.helioscalendar.com/license.html
 * @package Helios Calendar
 */
	define('isHC',true);
	define('isAction',true);
	include_once('../loader.php');

	header('Content-type: application/xml; charset="utf-8"');
	echo '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>'.CalRoot.'</loc>
  </url>
  <url>
    <loc>'.CalRoot.'/index.php?com=location</loc>
  </url>
  <url>
    <loc>'.CalRoot.'/index.php?com=submit</loc>
  </url>
  <url>
    <loc>'.CalRoot.'/index.php?com=search</loc>
  </url>
  <url>
    <loc>'.CalRoot.'/index.php?com=newsletter</loc>
  </url>
  <url>
    <loc>'.CalRoot.'/index.php?com=tools</loc>
  </url>
</urlset>';
?>